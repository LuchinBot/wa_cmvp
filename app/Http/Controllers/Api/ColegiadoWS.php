<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\Empresa;
use App\Models\Colegiado;
use App\Models\PersonaNatural;
use App\Models\Sexo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ColegiadoWS extends Controller
{
    public $objEmpresaGlobal        = null;

    public function __construct()
    {
        $this->objEmpresaGlobal     = Empresa::withUbigeo()->first();
    }

    public function ListSexes(){
        $Sexos      = [];
        $objSexos   = Sexo::get();

        foreach($objSexos as $value){
            $Sexos[] = [
                "sexo"=>$value["descripcion"]
                ,"id"=>$value["simbolo"]
            ];
        }

        return sendSuccess(["Sexos"=>$Sexos], "OK");
    }

    public function ListOnomastic(){
        $Colegiados     = [];
        $objColegiados  = Colegiado::with(["persona_natural"])->get();
        foreach($objColegiados as $value){
            if($value->persona_natural->hb_today)
                $Colegiados[] = [
                    "colegiado"=>"{$value->persona_natural->nombres} {$value->persona_natural->apellido_paterno} {$value->persona_natural->apellido_materno}"
                    ,"foto"=>$value->persona_natural->url_foto
                    ,"numero_colegiatura"=>$value->numero_colegiatura
                ];
        }

        $dato["Titulo"]         = "..::Colegiados::..";
        $dato["fecha_actual"]   = date("d")." de ".$this->meses[date("m")]." del ".date("Y");
        $dato["Colegiados"]     = $Colegiados;

        return sendSuccess($dato, "OK");
    }

    public function Statistics(Request $request){
        $sexo           = $request->filled("sexo")?$request->input("sexo"):"";
        $ubigeo         = $request->filled("provincia")?$request->input("provincia"):"";

        $Statistics     = [];
        $objColegiados  = Colegiado::selectRaw("
                            YEAR(fecha_colegiatura) as anio
                            ,count(*) as cantidad
                        ")
                        ->join("persona_natural", "persona_natural.codpersona_natural", "=", "colegiado.codpersona_natural")
                        ->join("sexo", "sexo.codsexo", "=", "persona_natural.codsexo")
                        ->join("ubigeo", "ubigeo.id_ubigeo", "=", "persona_natural.id_ubigeo")
                        ->when($sexo, function($q, $simbolo){
                            $q->where("sexo.simbolo", "=", $simbolo);
                        })
                        ->when($ubigeo, function($q, $provincia){
                            $q->where("ubigeo.cod_prov", $provincia)
                            ->where("ubigeo.cod_dpto", DB::raw("'22'"))
                            ;
                        })
                        ->groupBy("anio")
                        ->get()
                        ;

        foreach($objColegiados as $value){
            $Statistics[] = [
                "cantidad"=>$value["cantidad"]
                ,"anio"=>$value["anio"]
            ];
        }

        return sendSuccess(["Statistics"=>$Statistics], "OK");
    }

    public function searchCollegiate(Request $request){
        $parametro      = $request->filled("parametro")?$request->input("parametro"):"";
        $valor          = $request->filled("valor")?$request->input("valor"):"-";

        if(empty($parametro)){
            return sendError("No se encontró el parametro");
        }else{
            if(!in_array($parametro, ["nombres", "numero_documento_identidad", "numero_colegiatura"])){
                return sendError("El criterio de búsqueda no es el correcto, intente con otro");
            }
        }

        if(empty($valor)){
            return sendError("No se encontró el valor de la búsqueda");
        }

        $obj = null;
        if($parametro=="nombres"){
            $obj = Colegiado::withEspecialidadColegiado()
                    ->with(["persona_natural"])
                    ->join("persona_natural", "persona_natural.codpersona_natural", "=", "colegiado.codpersona_natural")
                    ->where('nombres','LIKE','%'.$valor.'%')
                    ->orWhere("apellido_paterno",'LIKE','%'.$valor.'%')
                    ->orWhere("apellido_materno",'LIKE','%'.$valor.'%')
                    ->get();
        }else if($parametro=="numero_documento_identidad"){
            $obj = Colegiado::withEspecialidadColegiado()
                    ->with(["persona_natural"])
                    ->join("persona_natural", "persona_natural.codpersona_natural", "=", "colegiado.codpersona_natural")
                    ->where('persona_natural.numero_documento_identidad', $valor)
                    ->get();
        }else if($parametro=="numero_colegiatura"){
            $obj = Colegiado::withEspecialidadColegiado()
                ->with(["persona_natural"])
                ->join("persona_natural", "persona_natural.codpersona_natural", "=", "colegiado.codpersona_natural")
                ->where('colegiado.numero_colegiatura', $valor)
                ->get();
        }

        $array     = [];
        if(!is_null($obj)){
            foreach($obj as $val){
                $especialidades = [];
                foreach($val["especialidad_colegiado"] as $especialidad){
                    $especialidades[] = [
                        "especialidad"=>$especialidad["especialidad_colegiado"]
                        ,"nota"=>$especialidad["nota"]
                    ];
                }
                $array[] = [
                    "colegiado"=>$val["nombres"]." ".$val["apellido_paterno"]." ".$val["apellido_materno"]
                    ,"numero_colegiatura"=>$val["numero_colegiatura"]
                    ,"estado"=>($val["estado_colegiado"]=="H")?"HABILITADO":"INHABILITADO"
                    ,"incorporacion"=>$val["fecha_colegiatura_es"]
                    ,"foto"=>$val["persona_natural"]["url_foto"]
                    ,"especialidades"=>$especialidades
                ];
            }
        }

        return sendSuccess(["Resultado"=>$array], "OK");
    }
}
