<?php

namespace App\Http\Controllers;

use App\Models\Boton;
use App\Models\BolsaTrabajo;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use Yajra\DataTables\DataTables;
use App\Actions\DataTableInternal;

class BolsaTrabajoController extends Controller
{
    public $modulo                  = "Bolsa Trabajo";
    public $path_controller         = "bolsa_trabajos";

    public $model                   = null;
    public $name_table              = "";

    public $dataTableServer         = null;

    public function __construct()
    {
        $this->model                = new BolsaTrabajo();
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

        $datos["extend_bolsa_trabajos"]   = [];

        return view("{$this->path_controller}.index",$datos);
    }

    public function grilla()
    {
        $objeto = BolsaTrabajo::get();
        return  DataTables::of($objeto)->addIndexColumn()->make(true);
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'nombre_institucion'=>[
                "required"
                ,"max:250"
                , Rule::unique("{$this->model->getTable()}", "nombre_institucion")
                        ->ignore($request->input("cod{$this->name_table}"), "cod{$this->name_table}")
            ]
            ,'fecha_inicio'=>['required']
            ,'fecha_fin'=>['required']
            ,'url_referencial'=>['required', 'url']
        ],[
            'nombre_institucion.required'=>"Obligatorio"
            ,'fecha_inicio.required'=>"Obligatorio"
            ,'fecha_fin.required'=>"Obligatorio"
            ,'url_referencial.required'=>"Obligatorio"
        ]);

        $obj                = BolsaTrabajo::find($request->input("cod{$this->name_table}"));

        if(is_null($obj)){
            $obj            = new BolsaTrabajo();
        }
        $obj->fill($request->all());
        $obj->save();
        return response()->json($obj);
    }

    public function edit($id)
    {
        $obj    =   BolsaTrabajo::find($id);
        return response()->json($obj);
    }

    public function destroy($id)
    {
        $obj    =   BolsaTrabajo::findOrFail($id);
        $obj->delete();
        return response()->json();
    }
}
