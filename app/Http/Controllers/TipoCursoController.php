<?php

namespace App\Http\Controllers;

use App\Models\Boton;
use App\Models\TipoCurso;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use Yajra\DataTables\DataTables;
use App\Actions\DataTableInternal;

class TipoCursoController extends Controller
{
    public $modulo                  = "Tipo Curso";
    public $path_controller         = "tipo_cursos";

    public $model                   = null;
    public $name_table              = "";

    public $dataTableServer         = null;

    public function __construct()
    {
        $this->model                = new TipoCurso();
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
        $datos["botones"]           = ["new"=>1, "edit"=>1];

        $datos["tabla_grid"]        = $this->dataTableServer->createTable(false);
        $datos["script_grid"]       = $this->dataTableServer->createScript();
        $datos["extend_tipo_cursos"]= [];

        return view("{$this->path_controller}.index",$datos);
    }

    public function grilla()
    {
        $objeto = TipoCurso::get();
        return  DataTables::of($objeto)->addIndexColumn()->make(true);
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'descripcion'=>[
                "required"
                ,"max:250"
                , Rule::unique("{$this->model->getTable()}", "descripcion")
                ->ignore($request->input("cod{$this->name_table}"), "cod{$this->name_table}")
            ]
        ]);

        $obj                = TipoCurso::find($request->input("cod{$this->name_table}"));

        if(is_null($obj)){
            $obj            = new TipoCurso();
        }
        $obj->fill($request->all());
        $obj->save();
        return response()->json($obj);
    }

    public function edit($id)
    {
        $obj    =   TipoCurso::find($id);
        return response()->json($obj);
    }

    public function destroy($id)
    {
        $obj    =   TipoCurso::findOrFail($id);
        $obj->delete();
        return response()->json();
    }
}
