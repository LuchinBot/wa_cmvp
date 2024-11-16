<?php

namespace App\Http\Controllers;

use App\Models\Boton;
use App\Models\Requisito;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

use Yajra\DataTables\DataTables;
use App\Actions\DataTableInternal;

class RequisitoController extends Controller
{
    public $modulo                  = "Requisito";
    public $path_controller         = "requisitos";

    public $model                   = null;
    public $name_table              = null;

    public $dataTableServer         = null;

    public function __construct()
    {
        $this->model                = new Requisito();
        $this->name_table           = $this->model->getTable();

        $this->dataTableServer      = new DataTableInternal($this->path_controller.".grilla");
        $this->dataTableServer->setModel($this->model);
        $this->dataTableServer->setColumns($this->model->getColumnDataTable());


    }

    public function index()
    {
        $datos["table_name"]        = $this->name_table;
        $datos["pathController"]    = $this->path_controller;
        $datos["prefix"]            = "";
        $datos["modulo"]            = $this->modulo;
        $datos["version"]           = rand();

        //$datos['botones']           = Boton::botones($this->path_controller)->get();
        $datos["botones"]           = ["edit"=>1];
        $datos["tabla_grid"]        = $this->dataTableServer->createTable(false);
        $datos["script_grid"]       = $this->dataTableServer->createScript();
        $datos["extend_requisitos"] = [];

        return view("{$this->path_controller}.index", $datos);
    }

    public function grilla()
    {
        $objeto = Requisito::get();

        return DataTables::of($objeto)->addIndexColumn()->make(true);
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'descripcion'=>[
                "required"
                , "max:250"
                , Rule::unique("{$this->model->getTable()}", "descripcion")->ignore($request->input("cod{$this->name_table}"), "cod{$this->name_table}")
            ]
            ,'abreviatura'=>[
                "nullable"
                , "max:220"
            ]
        ],[
            "descripcion.required"=>"Obligatorio"
            ,"descripcion.unique"=>"Ya existe"
            ,"abreviatura.required"=>"Obligatorio"
        ]);
        $obj                = Requisito::find($request->input("cod{$this->name_table}"));
        if(is_null($obj)){
            $obj            = new Requisito();
        }
        $obj->fill($request->all());
        $obj->save();
        return response()->json($obj);
    }

    public function edit($id)
    {
        $obj=Requisito::find($id);
        return response()->json($obj);
    }

    public function destroy($id)
    {
        $obj=Requisito::findOrFail($id);
        $obj->delete();
        return response()->json();
    }
}
