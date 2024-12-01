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
    public $dimension_flayer      = ["ancho" => 980, "alto" => 980];

    public function __construct()
    {
        $this->model                = new DiasFestivos();
        $this->name_table           = $this->model->getTable();
        $this->path_dias_festivos           = $this->model->getPathDiasFestivos();
        $this->default_flayer       = $this->model->getUrlFlayer();
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
        $datos["default_flayer"]    = $this->default_flayer;
        $datos["default_plantilla"] = $this->default_plantilla;
        $datos["formato_valido"]    = $this->formato_valido;
        $datos["dimension_flayer"]  = $this->dimension_flayer;
        $datos["dimension_certificado"]= $this->dimension_flayer;
        return view("{$this->path_controller}.index", $datos);
    }

    public function grilla()
    {
        $objeto = DiasFestivos::get();
        return  DataTables::of($objeto)
            ->addIndexColumn()
            ->make(true);
    }
    public function uploadFile(Request $request){
        $filePath   = $request->file('file');
        $fileName   = uniqid().".".$filePath->getClientOriginalExtension();
        $path       = $this->path_dias_festivos;

        if(!in_array($filePath->getClientOriginalExtension(), ["jpg", "png", "jpeg"]))
            throw ValidationException::withMessages(["file"=>"No subió el formato correcto [jpg, png, jpeg]"]);

        if(!$filePath->move($path, $fileName))
            throw ValidationException::withMessages(["Error al subir el archivo"]);

        return response()->json(["status"=>TRUE, "filename"=>$fileName, "path_file_img"=>url($this->path_dias_festivos."/".$fileName)]);
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
        $filePath       = $request->file('file_flayer');
        $ImagenFlayer   = $request->input("imagen");
        $path           = $this->path_dias_festivos;
        if($filePath){
            $ImagenFlayer   = "flayer_".uniqid().".".$filePath->getClientOriginalExtension();
            if(!file_exists($path))
                mkdir($path, 0777, true);

            if(!in_array($filePath->getClientOriginalExtension(), $this->formato_valido))
                throw ValidationException::withMessages(["file_flayer"=>"No subió el formato correcto [".implode(",", $this->formato_valido)."], el formato ".$filePath->getClientOriginalExtension()." no está permitido"]);


            if(!$filePath->move($path, $ImagenFlayer))
                throw ValidationException::withMessages(["Error al subir el archivo, la ruta especificada {$path}, posiblemente no existe"]);
        }
        $obj                = DiasFestivos::find($request->input("cod{$this->name_table}"));

        if (is_null($obj)) {
            $obj            = new DiasFestivos();
        }
        $obj->fill($request->all());
        $obj->titulo = $request->input("titulo");
        $obj->descripcion = $request->input("descripcion");
        $obj->fecha = $request->input("fecha");
        $obj->imagen         = $ImagenFlayer;
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
