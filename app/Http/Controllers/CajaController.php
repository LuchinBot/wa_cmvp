<?php

namespace App\Http\Controllers;

use App\Models\Caja;
use App\Models\Usuario;
use App\Models\TipoCaja;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use Yajra\DataTables\DataTables;
use App\Actions\DataTableInternal;
use App\Models\Venta;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;


class CajaController extends Controller
{
    public $modulo                  = "Caja";
    public $path_controller         = "cajas";

    public $model                   = null;
    public $name_table              = "";

    public $dataTableServer         = null;

    public function __construct()
    {
        $this->model                = new Caja();
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
        $datos["tipo_caja"]    = TipoCaja::get();

        return view("{$this->path_controller}.index", $datos);
    }

    public function grilla()
    {
        $usuario = Auth::id();
        $objeto = Caja::with(["usuario_apertura"])->where('codusuario_apertura', $usuario)->where('codtipo_caja', 2)->get();
        return  DataTables::of($objeto)
            ->addIndexColumn()
            ->addColumn("estado", function ($row) {
                $estado = ($row->estado == 1) ? "Abierta" : "Cerrado";
                $clase  = ($row->estado == 1) ? "success" : "danger";
                return "<span style='width:100%;' class='badge bg-{$clase}'>{$estado}</span>";
            })
            ->rawColumns(['estado'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $usuario = Auth::id();
        // Caja principal
        $cajaPrincipal = Caja::where('estado', 1)
            ->where('codtipo_caja', 1)
            ->first();
        if (!$cajaPrincipal) {
            return response()->json([
                'message' => 'No existe una caja principal abierta.',
                'caja_abierta' => $cajaPrincipal
            ], 400);
        }

        // Caja chica
        $cajaChica = Caja::where('estado', 1)
            ->where('codtipo_caja', 2)
            ->where('codusuario_apertura', $usuario)
            ->first();
        if ($cajaChica) {
            return response()->json([
                'message' => 'Ya existe una caja chica abierta.',
                'caja_abierta' => $cajaChica
            ], 400);
        }

        // Validador
        $this->validate($request, [
            'monto_apertura' => ["required", "numeric", "min:0"]
        ], [
            "monto_apertura.required" => "El monto de apertura es obligatorio.",
            "monto_apertura.min" => "El monto de apertura debe ser al menos 0."
        ]);

        $date = Carbon::now();
        $monto_actual = $request->input("monto_apertura");
        
        $obj = Caja::find($request->input("cod{$this->name_table}"));
        if (is_null($obj)) {
            $obj = new Caja();
            $obj->estado = 1;
            $obj->codusuario_apertura = $usuario;
            $obj->monto_cierre = $monto_actual;
            $obj->codtipo_caja = 2;
            $obj->fecha_apertura = $date;
        }

        $obj->fill($request->all());
        $obj->save();

        return response()->json($obj);
    }


    public function edit($id)
    {
        //
    }

    public function destroy($id)
    {
        $usuario = Auth::id();
        $date = Carbon::now();
        $total = 0;

        /* Calcular el monto total de cierre de esa caja
        $ventas = Venta::where('codcaja', $id)->get();
        $total = $ventas->sum('total');

        // Verificar si hay ingresos
        if ($ventas->isEmpty()) {
            return response()->json([
                'message' => 'No existen ingresos en la caja.',
                'ventas' => ''
            ], 400);
        }
        */
        // Caja chica que se cerrará
        $Caja = Caja::findOrFail($id);
        $total = $Caja->sum('monto_cierre');
        try {
            $Caja->estado = 0;
            $Caja->codusuario_cierre = $usuario;
            $Caja->fecha_cierre = $date;
            $Caja->save();

            $CajaPrinciapl = Caja::where('estado',1)->where('codtipo_caja',1)->first();
            $CajaPrinciapl->monto_cierre += $total;
            $CajaPrinciapl->save();

            return response()->json($CajaPrinciapl);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al cerrar la caja: ' . $e->getMessage()], 500);
        }
    }
}
