<?php

namespace App\Http\Controllers;

use App\Models\Caja;
use App\Models\Usuario;
use App\Models\TipoCaja;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use Yajra\DataTables\DataTables;
use App\Actions\DataTableInternal;
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
        $objeto = Caja::with(["usuario","tipo_caja"])->get();
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

        $cajaAbierta = Caja::where('estado', 1)
            ->where('idtipo_caja', $request->input('idtipo_caja'))
            ->first();

        if ($cajaAbierta) {
            return response()->json([
                'message' => 'Ya existe una caja abierta con este tipo.',
                'caja_abierta' => $cajaAbierta
            ], 400);
        }

        // Validar los datos enviados
        $this->validate($request, [
            'monto_apertura' => ["required", "numeric", "min:0.5"]
        ], [
            "monto_apertura.required" => "El monto de apertura es obligatorio.",
            "monto_apertura.min" => "El monto de apertura debe ser al menos 0.5."
        ]);
        $date = Carbon::now();
        $obj = Caja::find($request->input("cod{$this->name_table}"));
        if (is_null($obj)) {
            $obj = new Caja();
            $obj->estado = 1; 
            $obj->codusuario = $usuario;
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
        $obj    =   Caja::findOrFail($id);
        $obj->delete();
        return response()->json();
    }
}
