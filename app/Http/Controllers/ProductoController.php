<?php

namespace App\Http\Controllers;

use App\Models\Producto;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use Yajra\DataTables\DataTables;
use App\Actions\DataTableInternal;
use App\Models\Venta;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;


class ProductoController extends Controller
{
    public $modulo                  = "Producto";
    public $path_controller         = "productos";

    public $model                   = null;
    public $name_table              = "";

    public $dataTableServer         = null;

    public function __construct()
    {
        $this->model                = new Producto();
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

        return view("{$this->path_controller}.index", $datos);
    }

    public function grilla()
    {
        $usuario = Auth::id();
        $objeto = Producto::get();
        return  DataTables::of($objeto)
            ->addIndexColumn()
            ->make(true);
    }

    public function store(Request $request)
    {
        //
        //$obj->fill($request->all());
        //$obj->save();

        //return response()->json($obj);
    }


    public function edit($id)
    {
        //
    }

    public function destroy($id)
    {
        $obj = Producto::findOrFail($id);
        $obj->delete();
        return response()->json($obj);

    }
}
