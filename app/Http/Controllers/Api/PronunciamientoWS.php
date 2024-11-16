<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pronunciamiento;
use App\Models\Empresa;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class PronunciamientoWS extends Controller
{
    public $objEmpresaGlobal            = null;

    public function __construct()
    {
        $this->objEmpresaGlobal     = Empresa::withUbigeo()->first();
    }

    public function ListPronouncements(){
        $Pronunciamientos       = [];
        $objPronunciamientos    = Pronunciamiento::orderBy("fecha_publicacion_inicio", "ASC")->get();
        foreach($objPronunciamientos as $value){
            $Pronunciamientos[] = [
                "titulo"=>$value["titulo"]
                ,"id"=>$value["slug"]
                ,"descripcion"=>$value["descripcion"]
                ,"fecha"=>Carbon::parse($value["fecha"])->format("d")." de ".$this->meses[Carbon::parse($value["fecha"])->format("m")]." del ".Carbon::parse($value["fecha"])->format("Y")
                ,"flayer"=>$value["url_imagen_flayer"]
            ];
        }

        $dato["Titulo"]         = "..::Pronunciamientos::..";
        $dato["Pronunciamientos"]    = $Pronunciamientos;

        return sendSuccess($dato, "OK");
    }

    public function getPronouncement(Request $request){
        $slug               = $request->filled("id")?$request->input("id"):"-";
        $Pronunciamiento    = [];
        $Pronunciamientos   = [];

        $objComunicado      = Pronunciamiento::orderBy("fecha", "DESC")->get();
        foreach($objComunicado as $value){
            if($value["slug"] == $slug){
                $Pronunciamiento = [
                    "titulo"=>$value->titulo
                    ,"imagen"=>$value->url_imagen
                    ,"flayer"=>$value->url_imagen_flayer
                    ,"descripcion"=>$value->descripcion
                    ,"fecha"=>Carbon::parse($value["fecha"])->format("d")." de ".$this->meses[Carbon::parse($value["fecha"])->format("m")]." del ".Carbon::parse($value["fecha"])->format("Y")
                ];
            }else{
                $Pronunciamientos[] = [
                    "titulo"=>$value->titulo
                    ,"id"=>$value->slug
                    ,"fecha"=>$value->fecha_es
                ];
            }
        }

        $dato["Titulo"]             = "..::Pronunciamiento::..";
        $dato["Pronunciamiento"]    = $Pronunciamiento;
        $dato["Pronunciamientos"]   = $Pronunciamientos;

        return sendSuccess($dato, "OK");
    }

    public function searchPronouncement(Request $request){
        $valor    = $request->filled("title")?$request->input("title"):"";
        $valor    = Str::upper($valor);
        $consulta = Pronunciamiento::where('titulo','LIKE','%'.$valor.'%')->get();

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

        $dato["Titulo"]             = "..::Búsqueda Pronunciamientos::..";
        $dato["Pronunciamientos"]   = $array;

        return sendSuccess($dato, "OK");
    }
}
