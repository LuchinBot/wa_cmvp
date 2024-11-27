<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cuota;
use App\Models\Colegiado;
use Carbon\Carbon;


class CuotasController extends Controller
{
    public function index()
    {
        $cuotas = Cuota::get();
        return json_decode($cuotas);
    }


    public function colegiado($id, $estado)
    {
        $anioActual = Carbon::now()->year;
        $mesActual = Carbon::now()->month;

        $colegiado = Colegiado::find($id);

        if (!$colegiado) {
            return response()->json(['error' => 'Colegido no encontrado'], 404);
        }

        if ($estado == 'pagadas') {
            $cuotas = Cuota::with('colegiado')
                ->where('codcolegiado', $id)
                ->where('anio', '<=', $anioActual)
                ->where(function ($query) use ($mesActual) {
                    $query->where('mes', '<=', $mesActual);
                })
                ->get();

            $mesesPagados = $cuotas->map(function ($cuota) {
                return [
                    'mes' => $cuota->mes,
                    'anio' => $cuota->anio,
                ];
            });

            // Respuesta
            $objeto = [
                'colegiado' => $colegiado,
                'cuotas' => $mesesPagados,
            ];
        } elseif ($estado == 'pendientes') {
            $cuotasPagadas = Cuota::where('codcolegiado', $id)
                ->where('anio', '<=', $anioActual)
                ->where(function ($query) use ($mesActual) {
                    $query->where('mes', '<=', $mesActual);
                })
                ->get();

            $mesesPendientes = [];
            $anioInicio = $cuotasPagadas->min('anio'); // Obtener el primer año de pago
            $mesInicio = $cuotasPagadas->min('mes');  // Obtener el primer mes de pago

            // Generar meses pendientes
            for ($anio = $anioInicio; $anio <= $anioActual; $anio++) {
                for ($mes = ($anio == $anioInicio ? $mesInicio : 1); $mes <= ($anio == $anioActual ? $mesActual : 12); $mes++) {
                    // Buscar si ya se pagó la cuota para este mes/año
                    $pagada = $cuotasPagadas->first(function ($cuota) use ($mes, $anio) {
                        return $cuota->mes == $mes && $cuota->anio == $anio;
                    });

                    // Si no está pagada, agregar a pendientes
                    if (!$pagada) {
                        $mesesPendientes[] = ['mes' => $mes, 'anio' => $anio];
                    }
                }
            }

            // Si no hay pendientes, mostrar que puede pagar un año (uwu)
            if (empty($mesesPendientes)) {
                // Obtener el último mes y año pagado
                $ultimoAnioPagado = $cuotasPagadas->max('anio');
                $ultimoMesPagado = $cuotasPagadas->where('anio', $ultimoAnioPagado)->max('mes');

                // Calcular los próximos 12 meses
                for ($i = 1; $i <= 12; $i++) {
                    $ultimoMesPagado++;
                    if ($ultimoMesPagado > 12) {
                        $ultimoMesPagado = 1;
                        $ultimoAnioPagado++;
                    }
                    $mesesPendientes[] = ['mes' => $ultimoMesPagado, 'anio' => $ultimoAnioPagado];
                }
            }

            // Respuesta para cuotas pendientes
            $objeto = [
                'colegiado' => $colegiado,
                'cuotas' => $mesesPendientes,
            ];
        }

        return response()->json($objeto);
    }
}
