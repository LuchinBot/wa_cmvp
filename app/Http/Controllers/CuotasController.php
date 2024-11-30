<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cuota;
use App\Models\Colegiado;

use App\Actions\CuotasColegiado;
use App\Actions\DeudaColegiado;

class CuotasController extends Controller
{
    public $path_controller         = "cuotas";

    public function index()
    {
        $cuotas = Cuota::get();
        return json_decode($cuotas);
    }


    public function colegiado($id, $type,$total)
    {
        return response()->json((new CuotasColegiado($id))->calculoCuotas($type,$total));
    }
    public function habilitado($id)
    {
        return response()->json((new CuotasColegiado($id))->calculoDeudas());
    }
}
