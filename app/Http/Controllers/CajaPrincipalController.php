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


class CajaPrincipalController extends Controller
{
    public $modulo                  = "Caja Principal";
    public $path_controller         = "cajas_principal";

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
        $objeto = Caja::with(["usuario_apertura", "tipo_caja"])->where('codtipo_caja', 1)->get();
        return  DataTables::of($objeto)
            ->addIndexColumn()
            ->addColumn("abierto", function ($row) {
                $estado = ($row->abierto == 'S') ? "Abierta" : "Cerrado";
                $clase  = ($row->abierto == 'S') ? "success" : "danger";
                return "<span style='width:100%;' class='badge bg-{$clase}'>{$estado}</span>";
            })
            ->rawColumns(['abierto'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $usuario = Auth::id();
        $cajaPrincipal = Caja::where('abierto', 'S')
            ->where('codtipo_caja', 1)
            ->first();
        // Si hay una caja principal abierta
        if ($cajaPrincipal) {
            return response()->json([
                'message' => 'Ya existe una caja principal abierta.',
                'caja_abierta' => $cajaPrincipal
            ], 400);
        }

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
            $obj->abierto = 'S';
            $obj->codusuario_apertura = $usuario;
            $obj->monto_cierre = $monto_actual;
            $obj->codtipo_caja = 1;
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
        /*
        // Calcular el monto total de cierre de esa caja
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
        try {
            // Subir los cambios del cierre
            $obj = Caja::findOrFail($id);
            $obj->abierto = 'N';
            $obj->codusuario_cierre = $usuario;
            $obj->fecha_cierre = $date;
            //$obj->monto_cierre = $total;
            $obj->save();

            return response()->json($obj);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al cerrar la caja: ' . $e->getMessage()], 500);
        }
    }
}
