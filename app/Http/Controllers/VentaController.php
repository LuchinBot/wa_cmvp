<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\Caja;
use App\Models\TipoCaja;
use App\Models\Producto;
use App\Models\DetalleVenta;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use Yajra\DataTables\DataTables;
use App\Actions\DataTableInternal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;


class VentaController extends Controller
{
    public $modulo                  = "Venta";
    public $path_controller         = "ventas";

    public $model                   = null;
    public $name_table              = "";

    public $dataTableServer         = null;

    public function __construct()
    {
        $this->model                = new Venta();
        $this->name_table           = $this->model->getTable();

        $this->dataTableServer          = new DataTableInternal($this->path_controller . ".grilla");
        $this->dataTableServer->setModel($this->model);
        $this->dataTableServer->setColumns($this->model->getColumnDataTable());
    }

    public function index()
    {
        $datos["table_name"]        = $this->name_table;
        $datos["pathController"]    = $this->path_controller;
        $datos["prefix"]            = "";
        $datos["modulo"]            = $this->modulo;
        $datos['botones']           = ["new" => 1, "delete" => 1];

        $datos["tabla_grid"]        = $this->dataTableServer->createTable(false);
        $datos["script_grid"]       = $this->dataTableServer->createScript();;
        $datos["tipo_caja"]    = TipoCaja::where('ver_en_recibo_ingreso', 'S')->get();
        $datos["productos"]    = Producto::get();

        return view("{$this->path_controller}.index", $datos);
    }

    public function grilla()
    {
        $objeto = Venta::select('venta.*', 'tipo_caja.nombre as tipo_caja')
            ->join('caja', 'venta.codcaja', '=', 'caja.codcaja')
            ->join('tipo_caja', 'caja.codtipo_caja', '=', 'tipo_caja.codtipo_caja')
            ->get();
        return DataTables::of($objeto)
            ->addIndexColumn()
            ->make(true);
    }

    public function store(Request $request)
    {
        // Obtener el usuario logueado
        $usuario = Auth::id();
        $date = Carbon::now();
        $cajaAbierta = '';

        if ($request->input('codtipo_caja') == 1) {
            $cajaAbierta = Caja::where('estado', 1)->first();
        } else {
            $cajaAbierta = Caja::where('estado', 1)
                ->where('codtipo_caja', 2)
                ->where('codusuario_apertura', $usuario)
                ->first();
        }

        if ($cajaAbierta) {
            // Guardar la venta
            $obj = Venta::find($request->input("id{$this->name_table}"));
            if (is_null($obj)) {
                $obj = new Venta();
                $obj->codcaja = $cajaAbierta->codcaja;
                $obj->total = 100;
                $obj->fecha = $date;
            }
            $cajaAbierta->monto_cierre = $cajaAbierta->monto_cierre + 100;
            $cajaAbierta->save();

            $obj->fill($request->all());
            if ($obj->save()) {
                $arrDetallVenta = [];
                if ($request->filled("detalle_venta")) {
                    foreach ($request->input("detalle_venta") as $key => $value) {
                        if (!empty($value['idddetalle_venta']))
                            $detalle    = DetalleVenta::find($value['idddetalle_venta']);
                        else
                            $detalle    = new DetalleVenta();

                        $detalle->idventa    = $obj->idventa;
                        $detalle->idproducto  = $value['idproducto'];
                        $detalle->cantidad    = 1;
                        $detalle->save();

                        $arrDetallVenta[]        = $detalle->iddetalle_venta;
                    }
                }

                DetalleVenta::where("idventa", $obj->idventa)->whereNotIn("iddetalle_venta", $arrDetallVenta)->delete();
            }
        } else {
            return response()->json([
                'message' => 'No existe una caja abierta con este tipo.' . $request->input('idtipo_caja'),
                'caja_abierta' => $cajaAbierta
            ], 400);
        }


        return response()->json($obj);
    }


    public function edit($id)
    {
        //
    }
    public function destroy($id)
    {
        $obj = Venta::findOrFail($id);
        $importe = $obj->total;
        $CajaAfectada = Caja::findOrFail($obj->codcaja);
        if ($CajaAfectada->estado == 1) {

            if ($CajaAfectada->monto_cierre >= $importe) {
                // Actualizar monto de cierre
                $CajaAfectada->monto_cierre -= $importe;
                $CajaAfectada->save();
                $obj->delete();

                return response()->json($obj);
            } else {
                return response()->json([
                    'message' => 'No hay suficiente saldo en la caja para revertir esta venta.'
                ], 400);
            }
        } else {
            return response()->json([
                'message' => 'La Caja no está abierta.'
            ], 400);
        }
    }
}
