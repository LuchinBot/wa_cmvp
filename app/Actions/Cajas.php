<?php

namespace App\Actions;

use Illuminate\Http\Request;
use App\Models\Caja;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;


class Cajas
{
    public $anioActual;
    public $mesActual;
    public $diaActual;
    public $caja = null;
    public $tipo_concepto = null;
    public $tipo_caja = null;
    public $monto = null;

    public function __construct()
    {
        $this->anioActual = Carbon::now()->year;
        $this->mesActual = Carbon::now()->month;
        $this->diaActual = Carbon::now()->day;
    }

    public function verificarCaja()
    {
        $usuario = Auth::id();
        $cajaChica = Caja::where('abierto', 'S')
            ->where('codtipo_caja', 2)
            ->where('codusuario_apertura', $usuario)
            ->first();
        if ($cajaChica) {
            return true;
        }
    }
    public function actualizarCaja($monto, $tipo_concepto,$tipo_caja)
    {
        $usuario = Auth::id();
        $this->monto = $monto;
        $this->tipo_concepto = $tipo_concepto;
        $this->tipo_caja = $tipo_caja;

        if ($this->tipo_caja == 1) {
            $cajaAbierta = Caja::where('abierto', 'S')->first();
        } else {
            $cajaAbierta = Caja::where('abierto', 'S')
                ->where('codtipo_caja', 2)
                ->where('codusuario_apertura', $usuario)
                ->first();
        }
        $this->caja = $cajaAbierta->codcaja;
        $cajaSeleccionada = Caja::where('codcaja', $this->caja)->first();
        if ($cajaSeleccionada->monto_cierre >= $this->monto) {
            $cajaSeleccionada->monto_cierre = ($this->tipo_concepto == 'S') ? $cajaSeleccionada->monto_cierre - $this->monto : $cajaSeleccionada->monto_cierre + $this->monto;
            if ($cajaSeleccionada->save()) {
                return $this->caja;
            }
        }else{
            return false;
        }
       
    }
    public function extornarCaja($id,$monto){
        $this->caja = $id;
        $this->monto = $monto;

        $CajaAfectada = Caja::findOrFail($this->caja);
        if ($CajaAfectada->abierto == 'S') {

            if ($CajaAfectada->monto_cierre >= $monto) {
                $CajaAfectada->monto_cierre -= $this->monto;
                $CajaAfectada->save();
                return true;
            } else {
                return false;
            }
        } else {
           return false;
        }
    }
}
