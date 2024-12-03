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
use App\Actions\Cajas;



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


        if ((new Cajas())->verificarCaja()) {
            return view("{$this->path_controller}.index", $datos);
        } else {
            return response()->json([
                'message' => 'No existe una caja chica abierta.',
                'caja_abierta' => ''
            ], 400);
        }
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
        $tipo_concepto = "S";
        $tipo_caja = $request->input('codtipo_caja');
        $monto = 100;

        $cajas = new Cajas();
        if ($cajas->verificarCaja()) {
            $codCaja = $cajas->actualizarCaja($monto, $tipo_concepto, $tipo_caja);
            if ($codCaja != false) {
                $obj = Venta::find($request->input("id{$this->name_table}"));
                if (is_null($obj)) {
                    $obj = new Venta();
                    $obj->codcaja = $codCaja;
                    $obj->total = 100;
                    $obj->fecha = $date;
                }

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
            }else{
                return response()->json([
                    'message' => 'No hay plata',
                    'caja_abierta' => ''
                ], 400);
            }
        } else {
            return response()->json([
                'message' => 'No existe una caja abierta con este tipo.' . $request->input('idtipo_caja'),
                'caja_abierta' => ''
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
        $codcaja = $obj->codcaja;
        $importe = $obj->total;
        $cajas = new Cajas();
        if ($cajas->extornarCaja($codcaja, $importe)) {
            $obj->delete();
        }
    }
}
