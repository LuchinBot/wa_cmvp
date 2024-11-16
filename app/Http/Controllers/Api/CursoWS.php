<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\Empresa;
use App\Models\Curso;

class CursoWS extends Controller
{
    public $objEmpresaGlobal        = null;

    public function __construct()
    {
        $this->objEmpresaGlobal     = Empresa::withUbigeo()->first();
    }

    public function ListCourses(){
        $Cursos     = [];
        $objCursos   = Curso::orderBy("fecha_inicio")->get();
        foreach($objCursos as $value){
            $Cursos[] = [
                "titulo"=>$value->titulo
                ,"id"=>$value->slug
                ,"imagen"=>$value->url_flayer
                ,"descripcion"=>$value->descripcion
            ];
        }

        $dato["Titulo"] = "..::Cursos::..";
        $dato["Cursos"] = $Cursos;

        return sendSuccess($dato, "OK");
    }
}
