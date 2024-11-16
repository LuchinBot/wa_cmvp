<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\Empresa;
use App\Models\JuntaDirectiva;
use App\Models\PersonaNatural;
use Carbon\Carbon;

class AboutControllerWS extends Controller
{
    public $objEmpresaGlobal        = null;

    public function __construct()
    {
        $this->objEmpresaGlobal     = Empresa::withUbigeo()->first();
    }

    public function About(){
        $Institucion    = [
                            "razon_social"=>$this->objEmpresaGlobal->razon_social??""
                            ,"abreviatura"=>$this->objEmpresaGlobal->abreviatura??""
                            ,"ruc"=>$this->objEmpresaGlobal->ruc??""
                            ,"telefonos"=>$this->objEmpresaGlobal->telefonos??""
                            ,"correo"=>$this->objEmpresaGlobal->email??""
                            ,"logo"=>$this->objEmpresaGlobal->url_logo??""
                            ,"direccion"=>$this->objEmpresaGlobal->direccion??""
                            ,"horario_atencion"=>$this->objEmpresaGlobal->horario_atencion??""
                            ,"ubigeo"=>$this->objEmpresaGlobal->ubigeo->ubigeo_descr??""
                            ,"objetivo"=>$this->objEmpresaGlobal->objetivo??""
                            ,"imagen_objetivo"=>$this->objEmpresaGlobal->url_imagen_objetivo??""
                            ,"historia"=>$this->objEmpresaGlobal->historia??""
                            ,"descripcion_consejo"=>$this->objEmpresaGlobal->descripcion_consejo??""
                            ,"imagen_consejo"=>$this->objEmpresaGlobal->url_imagen_consejo??""
                        ];

        $Representantes     = [];
        $Lista_Decanos      = [];
        $objJuntaDirectiva  = JuntaDirectiva::withIntegrantesJunta()->orderBy("fecha_periodo_inicio", "DESC")->get();
        foreach($objJuntaDirectiva as $k=>$value){
            foreach($value["integrantes_junta"] as $representante){
                if($k==0){
                    $Representantes[] = [
                        "nombres"=>$representante["integrante_nombre"]
                        ,"cargo"=>$representante["cargo_integrante"]
                        ,"numero_colegiatura"=>$representante["numero_colegiatura"]
                        ,"foto"=>url((new PersonaNatural())->getPath().(new PersonaNatural())->getDefaultFoto($representante["nombre_foto"]))
                    ];
                }else{
                    if($representante["id_cargo"]=="DECA"){
                        $Lista_Decanos[] = [
                            "nombres"=>$representante["integrante_nombre"]
                            ,"numero_colegiatura"=>$representante["numero_colegiatura"]
                            ,"periodo"=>Carbon::parse($value["fecha_periodo_fin"])->format("Y")." - ".Carbon::parse($value["fecha_periodo_inicio"])->format("Y")
                            ,"foto"=>url((new PersonaNatural())->getPath().(new PersonaNatural())->getDefaultFoto($representante["nombre_foto"]))
                        ];
                    }
                }
            }
        }

        $dato["Titulo"]             = "..::Nosotros::..";
        $dato["Institucion"]        = $Institucion;
        $dato["Representantes"]     = $Representantes;
        $dato["Anteriores_decanos"] = $Lista_Decanos;

        return sendSuccess($dato, "OK");
    }
}
