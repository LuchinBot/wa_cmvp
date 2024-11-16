<?php

namespace App\Http\Controllers;

use App\Models\Boton;
use App\Models\Perfil;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use Yajra\DataTables\DataTables;
use App\Actions\DataTableInternal;

class PerfilController extends Controller
{
    public $modulo                  = "Perfil";
    public $path_controller         = "perfiles";

    public $model                   = null;
    public $name_table              = "";

    public $dataTableServer         = null;

    public function __construct()
    {
        $this->model                = new Perfil();
        $this->name_table           = $this->model->getTable();

        $this->dataTableServer          = new DataTableInternal($this->path_controller.".grilla");
        $this->dataTableServer->setModel($this->model);
        $this->dataTableServer->setColumns($this->model->getColumnDataTable());
    }

    public function index()
    {
        $datos["table_name"]        = $this->name_table;
        $datos["pathController"]    = $this->path_controller;
        $datos["prefix"]            = "";
        $datos["modulo"]            = $this->modulo;
        //$datos['botones']           = Boton::accesoBoton($this->path_controller)->get();
        $datos['botones']           = ["new"=>1, "edit"=>1];

        $datos["tabla_grid"]        = $this->dataTableServer->createTable(false);
        $datos["script_grid"]       = $this->dataTableServer->createScript();

        $datos["extend_perfiles"]   = [];

        return view("seguridad.{$this->path_controller}.index",$datos);
    }

    public function grilla()
    {
        $objeto = Perfil::get();
        return  DataTables::of($objeto)->addIndexColumn()->make(true);
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'descripcion'=>[
                "required"
                ,"max:200"
                , Rule::unique("{$this->model->getTable()}", "descripcion")
                        ->ignore($request->input("cod{$this->name_table}"), "cod{$this->name_table}")
            ]
        ]);

        $obj                = Perfil::find($request->input("cod{$this->name_table}"));

        if(is_null($obj)){
            $obj            = new Perfil();
        }
        $obj->fill($request->all());
        $obj->save();
        return response()->json($obj);
    }

    public function edit($id)
    {
        $obj    =   Perfil::find($id);
        return response()->json($obj);
    }

    public function destroy($id)
    {
        $obj    =   Perfil::findOrFail($id);
        $obj->delete();
        return response()->json();
    }
}
