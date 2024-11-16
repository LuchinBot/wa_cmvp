<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BolsaTrabajo;
use App\Models\Empresa;
use App\Models\Colegiado;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConvocatoriaWS extends Controller
{
    public $objEmpresaGlobal        = null;

    public function __construct()
    {
        $this->objEmpresaGlobal     = Empresa::withUbigeo()->first();
    }

    public function ListJobsCall(){
        $Convocatorias      = [];
        $objConvocatorias   = BolsaTrabajo::orderBy("fecha_inicio", "DESC")->get();
        foreach($objConvocatorias as $value){
            $Convocatorias[] = [
                "institucion"=>$value["nombre_institucion"]
                ,"inicio"=>$value["fecha_inicio_es"]
                ,"fin"=>$value["fecha_fin_es"]
                ,"pagina_web"=>$value["url_referencial"]
            ];
        }

        $dato["Titulo"]         = "..::Convocatorias de Trabajos::..";
        $dato["Convocatorias"]  = $Convocatorias;

        return sendSuccess($dato, "OK");
    }
}
