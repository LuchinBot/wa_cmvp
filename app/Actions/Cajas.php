<?php

namespace App\Actions;

use Illuminate\Http\Request;
use App\Models\Caja;
use Carbon\Carbon;

class Cajas
{
    public $anioActual;
    public $mesActual;
    public $diaActual;

    public function __construct()
    {
        $this->anioActual = Carbon::now()->year;
        $this->mesActual = Carbon::now()->month;
        $this->diaActual = Carbon::now()->day;
    }

    public function verificarCaja()
    {
           
        //return $obj;
    }
    
}
