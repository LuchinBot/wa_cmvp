<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public $meses = [
        "01"=>"Enero"
        ,"02"=>"Febrero"
        ,"03"=>"Marzo"
        ,"04"=>"Abril"
        ,"05"=>"Mayo"
        ,"06"=>"Junio"
        ,"07"=>"Julio"
        ,"08"=>"Agosto"
        ,"09"=>"Setiembre"
        ,"10"=>"Octubre"
        ,"11"=>"Noviembre"
        ,"12"=>"Diciembre"
    ];

    public function get_icons(){
        $str = @file_get_contents(public_path("vendor/fontawesome-free/css/fontawesome.min.css"));

        $result =  null;
        if(preg_match_all("/.fa-([a-z\-]+)\:before/", $str, $result)) {
			$result = $result[1];
		}
        return $result;
    }

    /*
    public function get_solid_icons() {
        // Cargar el contenido del archivo CSS
        $str = @file_get_contents(public_path("vendor/fontawesome-free/css/fontawesome.min.css"));

        $result = null;

        // Usar una expresión regular para encontrar todas las clases que comienzan con 'fa-solid'
        if (preg_match_all("/\.fa-solid\-([a-zA-Z0-9\-]+)\s*:\s*before/", $str, $result)) {
            $result = $result[1]; // Obtener solo los nombres de los íconos
        }
    }
    */
}
