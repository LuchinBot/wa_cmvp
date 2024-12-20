<?php

namespace App\Actions;

use Illuminate\Http\Request;
use App\Models\Cuota;
use App\Models\Colegiado;
use Carbon\Carbon;

class CuotasColegiado
{
    public $codcolegiado = null;
    public $tipoCuota = "pagadas";
    public $totalCuota = null;
    public $anioActual = null;
    public $mesActual = null;

    public function __construct($id)
    {
        $this->codcolegiado = $id;
        $this->anioActual = Carbon::now()->year;
        $this->mesActual = Carbon::now()->month;
    }

    public function calculoCuotas($type, $total)
    {
        $this->tipoCuota = $type;
        $this->totalCuota = $total;
        $obj = [];
        $colegiado = Colegiado::find($this->codcolegiado);

        if (!$colegiado) {
            return response()->json(['error' => 'Colegido no encontrado'], 404);
        }

        if ($this->tipoCuota == 'pagadas') {
            $cuotas = Cuota::with('colegiado')
                ->where('codcolegiado', $this->codcolegiado)
                ->where('anio', '<=', $this->anioActual)
                ->where('mes', '<=', $this->mesActual)
                ->get();

            $mesesPagados = $cuotas->map(function ($cuota) {
                return [
                    'mes' => $cuota->mes,
                    'anio' => $cuota->anio,
                ];
            });

            $obj = [
                'colegiado' => $colegiado,
                'cuotas' => $mesesPagados,
            ];
        } elseif ($this->tipoCuota == 'pendientes') {
            $cuotasPagadas = Cuota::where('codcolegiado', $this->codcolegiado)
                ->where('anio', '<=', $this->anioActual)
                ->where('mes', '<=', $this->mesActual)
                ->get();

            $mesesPendientes = [];
            $anioInicio = $cuotasPagadas->min('anio');
            $mesInicio = $cuotasPagadas->min('mes');

            for ($anio = $anioInicio; $anio <= $this->anioActual; $anio++) {
                for ($mes = ($anio == $anioInicio ? $mesInicio : 1); $mes <= ($anio == $this->anioActual ? $this->mesActual : 12); $mes++) {
                    $pagada = $cuotasPagadas->first(function ($cuota) use ($mes, $anio) {
                        return $cuota->mes == $mes && $cuota->anio == $anio;
                    });

                    if (!$pagada) {
                        $mesesPendientes[] = ['mes' => strval($mes), 'anio' => $anio];
                    }
                }
            }

            if (empty($mesesPendientes)) {
                $ultimoAnioPagado = $cuotasPagadas->max('anio');
                $ultimoMesPagado = $cuotasPagadas->where('anio', $ultimoAnioPagado)->max('mes');

                // Puede pagar máximo un año
                for ($i = 1; $i <= 12; $i++) {
                    $ultimoMesPagado++;
                    if ($ultimoMesPagado > 12) {
                        $ultimoMesPagado = 1;
                        $ultimoAnioPagado++;
                    }
                    $mesesPendientes[] = ['mes' => strval($ultimoMesPagado), 'anio' => $ultimoAnioPagado];
                }
            } else {
                $ultimoMesAnio = end($mesesPendientes);
                $ultimoAnioPagado = $ultimoMesAnio['anio'];
                $ultimoMesPagado = $ultimoMesAnio['mes'];

                // Calcular los próximos 12 meses a partir de este último
                $mesesPendientesPlus = [];

                for ($i = 1; $i <= 12; $i++) {
                    $ultimoMesPagado++;

                    if ($ultimoMesPagado > 12) {
                        $ultimoMesPagado = 1;
                        $ultimoAnioPagado++;
                    }

                    $mesesPendientesPlus[] = ['mes' => strval($ultimoMesPagado), 'anio' => $ultimoAnioPagado];
                }

                $mesesPendientes = array_merge($mesesPendientes, $mesesPendientesPlus);
            }

            $mesesPendientesLimitados = array_slice($mesesPendientes, 0, $this->totalCuota);

            $obj = [
                'colegiado' => $colegiado,
                'cuotas' => $mesesPendientesLimitados,
            ];
        }
        return response()->json($obj);
    }
    public function calculoDeudas()
    {
        // Máximo mes para deuda
        $maxMes = 3;

        // Verificar las cuotas pagadas y obtener meses pendientes
        $cuotasPagadas = Cuota::where('codcolegiado', $this->codcolegiado)
            ->where('anio', '<=', $this->anioActual)
            ->where('mes', '<=', $this->mesActual)
            ->get();

        $mesesPendientes = [];
        $anioInicio = $cuotasPagadas->min('anio');
        $mesInicio = $cuotasPagadas->min('mes');

        for ($anio = $anioInicio; $anio <= $this->anioActual; $anio++) {
            for ($mes = ($anio == $anioInicio ? $mesInicio : 1); $mes <= ($anio == $this->anioActual ? $this->mesActual : 12); $mes++) {
                $pagada = $cuotasPagadas->first(function ($cuota) use ($mes, $anio) {
                    return $cuota->mes == $mes && $cuota->anio == $anio;
                });
                if (!$pagada) {
                    $mesesPendientes[] = ['mes' => strval($mes), 'anio' => $anio];
                }
            }
        }
        $obj = Colegiado::find($this->codcolegiado);
        if (count($mesesPendientes) > $obj->max_mes) {
            $obj->estado_colegiado = 'I';
            $obj->max_mes = 0;
            $msg = 'Colegiado Inhabilitado';
        } else {
            $obj->estado_colegiado = 'H';
            $msg = 'Colegiado Habilitado';
        }


        // Guardar
        $obj->save();

        $response = [
            'status' => true,
            'message' => $msg,
            'data'=>$mesesPendientes
        ];

        return response()->json($response);
    }

    public function pagarCuota($desde, $hasta)
    {
        list($mesDesde, $anioDesde) = explode('/', $desde);
        list($mesHasta, $anioHasta) = explode('/', $hasta);

        $mesDesde = intval($mesDesde);
        $anioDesde = intval($anioDesde);
        $mesHasta = intval($mesHasta);
        $anioHasta = intval($anioHasta);
        $count = 0;
        for ($anio = $anioDesde; $anio <= $anioHasta; $anio++) {
            $mesInicio = ($anio == $anioDesde) ? $mesDesde : 1;
            $mesFin = ($anio == $anioHasta) ? $mesHasta : 12;

            for ($mes = $mesInicio; $mes <= $mesFin; $mes++) {
                // Agregar a la tabla Cuota uwu
                $obj = new Cuota();
                if ($obj) {
                    $obj->codcolegiado = $this->codcolegiado;
                    $obj->mes = $mes;
                    $obj->anio = $anio;
                }
                $count++;
                $obj->save();
            }
        }
        $obj = Colegiado::find($this->codcolegiado);
        if($count == 12){
            $obj->max_mes = 3;
            $obj->save();
        }
    }
    public function anularCuota($desde, $hasta)
    {
        list($mesDesde, $anioDesde) = explode('/', $desde);
        list($mesHasta, $anioHasta) = explode('/', $hasta);

        $mesDesde = intval($mesDesde);
        $anioDesde = intval($anioDesde);
        $mesHasta = intval($mesHasta);
        $anioHasta = intval($anioHasta);

        for ($anio = $anioDesde; $anio <= $anioHasta; $anio++) {
            $mesInicio = ($anio == $anioDesde) ? $mesDesde : 1;
            $mesFin = ($anio == $anioHasta) ? $mesHasta : 12;
            for ($mes = $mesInicio; $mes <= $mesFin; $mes++) {
                // Eliminar de la tabla Cuota uwu
                Cuota::where('codcolegiado', $this->codcolegiado)
                    ->where('mes', $mes)
                    ->where('anio', $anio)
                    ->delete();
            }
        }
    }
}
