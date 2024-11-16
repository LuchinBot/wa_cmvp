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
        // Obtener el usuario logueado
        $usuario = Auth::id();
        $objeto = Caja::with(["usuario_apertura", "tipo_caja"])->where('codusuario_apertura', $usuario)->get();
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
        // Obtener el usuario logueado
        $usuario = Auth::id();
        if ($request->input('idtipo_caja') == 2) {
            $cajaPrincipal = Caja::where('estado', 1)
                ->where('idtipo_caja', 2)
                ->first();
            // Si hay una caja principal abierta
            if ($cajaPrincipal) {
                return response()->json([
                    'message' => 'Ya existe una caja abierta con este tipo.',
                    'caja_abierta' => $cajaPrincipal
                ], 400);
            }
        } else {
            $cajaPrincipal = Caja::where('estado', 1)
                ->where('idtipo_caja', 2)
                ->first();
            // Si no hay una caja principal abierta
            if (!$cajaPrincipal) {
                return response()->json([
                    'message' => 'La caja principal no está abierta.',
                    'caja_abierta' => $cajaPrincipal
                ], 400);
            }
        }

        $this->validate($request, [
            'monto_apertura' => ["required", "numeric", "min:0"]
        ], [
            "monto_apertura.required" => "El monto de apertura es obligatorio.",
            "monto_apertura.min" => "El monto de apertura debe ser al menos 0."
        ]);

        $date = Carbon::now();
        $obj = Caja::find($request->input("cod{$this->name_table}"));
        if (is_null($obj)) {
            $obj = new Caja();
            $obj->estado = 1;
            $obj->codusuario_apertura = $usuario;
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
    
        // Calcular el monto total de cierre de esa caja
        $ventas = Venta::where('idcaja', $id)->get();
        $total = $ventas->sum('total');
    
        // Verificar si hay ventas
        if ($ventas->isEmpty()) {
            return response()->json([
                'message' => 'No existen ventas en la caja.',
                'ventas' => ''
            ], 400);
        }
    
        try {
            // Subir los cambios del cierre
            $obj = Caja::findOrFail($id);
            $obj->estado = 0;
            $obj->codusuario_cierre = $usuario;
            $obj->fecha_cierra = $date;
            $obj->monto_cierre = $total;
            $obj->save();
    
            return response()->json($obj);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al cerrar la caja: ' . $e->getMessage()], 500);
        }
    }
}
