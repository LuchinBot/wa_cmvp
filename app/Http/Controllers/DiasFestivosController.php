<?php

namespace App\Http\Controllers;

use App\Models\Boton;
use App\Models\DiasFestivos;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use Yajra\DataTables\DataTables;
use App\Actions\DataTableInternal;

use Illuminate\Support\Str as Str;
use Illuminate\Validation\ValidationException;

class DiasFestivosController extends Controller
{
    public $modulo                  = "Días festivos";
    public $path_controller         = "dias_festivos";

    public $model                   = null;
    public $name_table              = "";

    public $dataTableServer         = null;
    public $path_dias_festivos             = "";
    public $default_flayer          = "";
    public $default_plantilla       = "";
    public $formato_valido          = ["jpg", "jpeg", "png"];
    public $dimensiones_flayer      = ["ancho" => 720, "alto" => 980];
    public $dimensiones_certificado = ["ancho" => 1200, "alto" => 980];

    public function __construct()
    {
        $this->model                = new DiasFestivos();
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
        $datos["botones"]           = ["new" => 1, "edit" => 1];

        $datos["tabla_grid"]        = $this->dataTableServer->createTable(false);
        $datos["script_grid"]       = $this->dataTableServer->createScript();
        $datos["extend_cursos"]     = [];

        return view("{$this->path_controller}.index", $datos);
    }

    public function grilla()
    {
        $objeto = DiasFestivos::get();
        return  DataTables::of($objeto)
            ->addIndexColumn()
            ->make(true);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'titulo' => [
                "required",
                "max:250",
                Rule::unique("{$this->model->getTable()}", "titulo")
                    ->ignore($request->input("cod{$this->name_table}"), "cod{$this->name_table}")
            ],
            'descripcion' => ["required"],
            'fecha' => ["required"]
        ], [
            "titulo.required" => "Obligatorio",
            "descripcion.required" => "Obligatorio",
            "fecha.required" => "Obligatorio"
        ]);

        $obj                = DiasFestivos::find($request->input("cod{$this->name_table}"));

        if (is_null($obj)) {
            $obj            = new DiasFestivos();
        }
        $obj->fill($request->all());
        $obj->titulo = $request->input("titulo");
        $obj->descripcion = $request->input("descripcion");
        $obj->fecha = $request->input("fecha");

        $obj->save();
        return response()->json($obj);
    }

    public function edit($id)
    {
        $obj    =   DiasFestivos::find($id);
        return response()->json($obj);
    }

    public function destroy($id)
    {
        $obj    =   DiasFestivos::findOrFail($id);
        $obj->delete();
        return response()->json();
    }
}
