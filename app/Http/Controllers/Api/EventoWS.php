<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\Empresa;
use App\Models\Evento;

class EventoWS extends Controller
{
    public $objEmpresaGlobal            = null;

    public function __construct()
    {
        $this->objEmpresaGlobal     = Empresa::withUbigeo()->first();
    }

    public function ListEvents(){
        $Cursos     = [];
        $objCursos   = Evento::orderBy("fecha")->get();
        foreach($objCursos as $value){
            $Cursos[] = [
                "titulo"=>$value->titulo
                ,"id"=>$value->slug
                ,"imagen"=>$value->url_imagen
                ,"descripcion"=>$value->descripcion
            ];
        }

        $dato["Titulo"]     = "..::Eventos::..";
        $dato["Eventos"]    = $Cursos;

        return sendSuccess($dato, "OK");
    }
}
