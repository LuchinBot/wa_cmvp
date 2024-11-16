<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comunicado;
use App\Models\Empresa;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class ComunicadoWS extends Controller
{
    public $objEmpresaGlobal            = null;

    public function __construct()
    {
        $this->objEmpresaGlobal     = Empresa::withUbigeo()->first();
    }

    public function ListReleases(){
        $Comunicados     = [];
        $objComunicados = Comunicado::orderBy("fecha_publicacion_inicio", "ASC")->get();
        foreach($objComunicados as $value){
            $Comunicados[] = [
                "titulo"=>$value["titulo"]
                ,"id"=>$value["slug"]
                ,"descripcion"=>$value["descripcion"]
                ,"fecha"=>Carbon::parse($value["fecha"])->format("d")." de ".$this->meses[Carbon::parse($value["fecha"])->format("m")]." del ".Carbon::parse($value["fecha"])->format("Y")
                ,"flayer"=>$value["url_imagen_flayer"]
            ];
        }

        $dato["Titulo"]         = "..::Comunicados::..";
        $dato["Comunicados"]    = $Comunicados;

        return sendSuccess($dato, "OK");
    }

    public function getRelease(Request $request){
        $slug           = $request->filled("id")?$request->input("id"):"-";
        $Comunicado     = [];
        $Comunicados    = [];

        $objComunicado  = Comunicado::orderBy("fecha", "DESC")->get();
        foreach($objComunicado as $value){
            if($value["slug"] == $slug){
                $Comunicado = [
                    "titulo"=>$value->titulo
                    ,"imagen"=>$value->url_imagen
                    ,"flayer"=>$value->url_imagen_flayer
                    ,"descripcion"=>$value->descripcion
                    ,"fecha"=>Carbon::parse($value["fecha"])->format("d")." de ".$this->meses[Carbon::parse($value["fecha"])->format("m")]." del ".Carbon::parse($value["fecha"])->format("Y")
                ];
            }else{
                $Comunicados[] = [
                    "titulo"=>$value->titulo
                    ,"id"=>$value->slug
                    ,"fecha"=>$value->fecha_es
                ];
            }
        }

        $dato["Titulo"]         = "..::Comunicado::..";
        $dato["Comunicado"]     = $Comunicado;
        $dato["Comunicados"]    = $Comunicados;

        return sendSuccess($dato, "OK");
    }

    public function searchReleases(Request $request){
        $valor    = $request->filled("title")?$request->input("title"):"";
        $valor    = Str::upper($valor);
        $consulta = Comunicado::where('titulo','LIKE','%'.$valor.'%')->get();

        $array=[];
        if (count($consulta) > 0) {
            foreach ($consulta as $value) {
                $array[]=[
                    "titulo"=>$value->titulo
                    ,"id"=>$value->slug
                    ,"imagen"=>$value->url_imagen
                    ,"descripcion"=>$value->descripcion
                    ,"fecha"=>$value->fecha_es
                ];
            }
        }

        $dato["Titulo"] = "..::Búsqueda Comunicados::..";
        $dato["Comunicados"]   = $array;

        return sendSuccess($dato, "OK");
    }
}
