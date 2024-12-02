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
        $datos['botones']           = ["new" => 1, "delete" => 1, "edit" => 1];

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
            ->addColumn("controla_stock", function ($row) {
                $controla_stock = ($row->controla_stock == 'S') ? "SI" : "NO";
                $clase  = ($row->controla_stock == 'S') ? "success" : "danger";
                return "<span style='width:100%;' class='badge bg-{$clase}'>{$controla_stock}</span>";
            })
            ->rawColumns(['controla_stock'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'descripcion' => ['required', 'max:250'],
            'precio' => ['required', 'numeric'],
        ], [
            'descripcion.required' => 'La descripción es obligatoria.',
            'descripcion.max' => 'La descripción no debe exceder los 250 caracteres.',
            'precio.required' => 'El precio es obligatorio.',
            'precio.numeric' => 'El precio debe ser un valor numérico.',
        ]);
    
        $controla_stock = $request->has('controla_stock') ? 'S' : 'N';
    
        $obj = Producto::find($request->input("cod{$this->name_table}")) ?? new Producto;
        
        $obj->fill($request->except('controla_stock')); 
        $obj->controla_stock = $controla_stock;
    
        $obj->save();
            return response()->json($obj);
    }
    


    public function edit($id)
    {
        $obj    =   Producto::find($id);
        return response()->json($obj);
    }

    public function destroy($id)
    {
        $obj = Producto::findOrFail($id);
        $obj->delete();
        return response()->json($obj);
    }
}
