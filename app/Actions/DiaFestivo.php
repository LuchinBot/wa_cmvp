<?php

namespace App\Actions;

use Illuminate\Http\Request;
use App\Models\DiasFestivos;
use Carbon\Carbon;

class DiaFestivo
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

    public function verificarDía()
    {
        $fechaActual = Carbon::create($this->anioActual, $this->mesActual, $this->diaActual)->toDateString();
        $diaFestivo = DiasFestivos::where('fecha', $fechaActual)->first();
    
        $obj = [
            'status' => false,
            'message' => 'Día normal',
        ];
    
        if ($diaFestivo) {
            $obj = [
                'status' => true,
                'message' => 'Día festivo',
                'data' => $diaFestivo,
            ];
        }
    
        return $obj;
    }
    
}
