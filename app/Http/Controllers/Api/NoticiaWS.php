<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\Empresa;
use App\Models\Noticia;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class NoticiaWS extends Controller
{
    public $objEmpresaGlobal        = null;

    public function __construct()
    {
        $this->objEmpresaGlobal     = Empresa::withUbigeo()->first();
    }

    public function ListNews(){
        $Noticia        = [];
        $Noticias       = [];

        $objNoticia     = Noticia::orderBy("fecha", "DESC")->get();
        foreach($objNoticia as $key=>$value){
            if($key==0){
                $Noticia = [
                    "titulo"=>$value->titulo
                    //,"id"=>$value->slug
                    ,"imagen"=>$value->url_imagen
                    ,"descripcion"=>$value->descripcion
                    ,"fecha"=>Carbon::parse($value["fecha"])->format("d")." de ".$this->meses[Carbon::parse($value["fecha"])->format("m")]." del ".Carbon::parse($value["fecha"])->format("Y")
                ];
            }else{
                $Noticias[] = [
                    "titulo"=>$value->titulo
                    ,"id"=>$value->slug
                    ,"imagen"=>$value->url_imagen
                    ,"descripcion"=>$value->descripcion
                    ,"fecha"=>$value->fecha_es
                ];
            }

        }

        $dato["Titulo"] = "..::Noticias::..";
        $dato["Noticia"]    = $Noticia;
        $dato["Noticias"]   = $Noticias;

        return sendSuccess($dato, "OK");
    }

    public function getNews(Request $request){
        $slug           = $request->filled("id")?$request->input("id"):"-";
        $Noticia        = [];
        $Noticias       = [];

        $objNoticia     = Noticia::orderBy("fecha", "DESC")->get();
        foreach($objNoticia as $key=>$value){
            if($value["slug"] == $slug){
                $Noticia = [
                    "titulo"=>$value->titulo
                    ,"imagen"=>$value->url_imagen
                    ,"descripcion"=>$value->descripcion
                    ,"fecha"=>Carbon::parse($value["fecha"])->format("d")." de ".$this->meses[Carbon::parse($value["fecha"])->format("m")]." del ".Carbon::parse($value["fecha"])->format("Y")
                ];
            }else{
                $Noticias[] = [
                    "titulo"=>$value->titulo
                    ,"id"=>$value->slug
                    ,"imagen"=>$value->url_imagen
                    ,"descripcion"=>$value->descripcion
                    ,"fecha"=>$value->fecha_es
                ];
            }
        }

        $dato["Titulo"] = "..::Noticia::..";
        $dato["Noticia"]    = $Noticia;
        $dato["Noticias"]   = $Noticias;

        return sendSuccess($dato, "OK");
    }

    public function searchNews(Request $request){
        $valor    = $request->filled("title")?$request->input("title"):"";
        $valor    = Str::upper($valor);
        $consulta = Noticia::where('titulo','LIKE','%'.$valor.'%')->get();

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

        $dato["Titulo"] = "..::Búsqueda Noticias::..";
        $dato["Noticias"]   = $array;

        return sendSuccess($dato, "OK");
    }
}
