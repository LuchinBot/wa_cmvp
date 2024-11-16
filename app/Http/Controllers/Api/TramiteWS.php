<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\RequisitoTramite;
use App\Models\Tramite;
use Carbon\Carbon;
use Illuminate\Http\Request;
use SplFileInfo;

class TramiteWS extends Controller
{
    public $objEmpresaGlobal            = null;

    public function __construct()
    {
        $this->objEmpresaGlobal     = Empresa::withUbigeo()->first();
    }

    public function ListProcess(){
        $Tramites     = [];
        $objTramites   = Tramite::orderBy("orden", "ASC")->get();
        foreach($objTramites as $value){
            $titulo     = "";
            $subtitulo  = "";
            $arrTitulo  = explode(" ", $value["titulo"]);
            if(count($arrTitulo)>0){
                $titulo     = $arrTitulo[0];
                $subtitulo  = trim(str_replace($titulo, "", $value["titulo"]));

            }

            $Tramites[] = [
                "titulo"=>$titulo
                ,"subtitulo"=>$subtitulo
                ,"id"=>$value["slug"]
                ,"descripcion"=>$value["descripcion"]
                ,"derecho_pago"=>$value["derecho_pago"]
                ,"icono"=>$value["icono"]??"fa-folder"
            ];
        }

        $dato["Titulo"] = "..::Trámites::..";
        $dato["Cursos"] = $Tramites;

        return sendSuccess($dato, "OK");
    }

    public function getProcess(Request $request){
        $slug           = $request->filled("id")?$request->input("id"):"-";
        $Tramite        = [];
        $objTramite     = Tramite::withRequisitosTramite()->where("slug", $slug)->first();
        if(!is_null($objTramite)){
            $requisitos = [];
            foreach($objTramite->requisitos as $requisito){
                $file_local = null;
                if(!is_null($requisito["archivo"])){
                    $file       = public_path((new RequisitoTramite())->getPath().$requisito["archivo"]);
                    $fileInfo   = new SplFileInfo((string) $file);

                    if(!is_null($fileInfo))
                        $file_local = "{$requisito["requisito"]}.".$fileInfo->getextension();
                }

                $requisitos[] = [
                    "requisito"=>$requisito["requisito"]
                    ,"nota"=>$requisito["nota"]
                    ,"archivo"=>$requisito["archivo"]
                    ,"file"=>$file_local
                ];
            }
            $Tramite = [
                "titulo"=>$objTramite->titulo
                ,"descripcion"=>$objTramite->titulo
                ,"derecho_pago"=>$objTramite->derecho_pago
                ,"icono"=>$objTramite->icono
                ,"requisitos"=>$requisitos
            ];
        }

        $dato["Titulo"]         = "..::Trámite::..";
        $dato["Tramite"]  = $Tramite;

        return sendSuccess($dato, "OK");
    }
}
