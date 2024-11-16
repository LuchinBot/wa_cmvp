<?php

namespace App\Http\Controllers;

use App\Models\Boton;
use App\Models\JuntaDirectiva;

use Illuminate\Http\Request;

use Yajra\DataTables\DataTables;
use App\Actions\DataTableInternal;
use App\Models\Cargo;
use App\Models\IntegranteJuntaDirectiva;

class JuntaDirectivaController extends Controller
{
    public $modulo                  = "Junta Directiva";
    public $path_controller         = "juntas_directivas";

    public $model                   = null;
    public $name_table              = "";

    public $dataTableServer         = null;

    public function __construct()
    {
        $this->model                = new JuntaDirectiva();
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

        $datos["extend_juntas_directivas"]   = [];
        $datos["cargos"]            = Cargo::get();

        return view("{$this->path_controller}.index",$datos);
    }

    public function grilla()
    {
        $objeto = JuntaDirectiva::withIntegrantesJunta()->get();
        return  DataTables::of($objeto)
                ->addIndexColumn()
                ->addColumn("integrantes_junta", function($row){
                    if(count($row->integrantes_junta)>0){
                        $list = "<ul class='list-group list-group-flush'>";
                        foreach($row->integrantes_junta as $val){
                            $list.="<li style='padding: .35rem 1.25rem; background-color: transparent;' class='list-group-item'>{$val->dni_integrante} - {$val->integrante} (<i>{$val->cargo_integrante}</i>)</li>";
                        }

                        $list.= "</ul>";

                        return $list;
                    }else{
                        return "<i>Sin integrantes</i>";
                    }

                })
                ->rawColumns(['integrantes_junta'])
                ->make(true);
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'descripcion'=>["required"]
            ,'fecha_periodo_inicio'=>["required"]
            ,'fecha_periodo_fin'=>['required']
        ],[
            "descripcion.required"=>"Obligatorio"
            ,"fecha_periodo_inicio.required"=>"Obligatorio"
            ,"fecha_periodo_fin.required"=>"Obligatorio"
        ]);

        $obj                = JuntaDirectiva::find($request->input("cod{$this->name_table}"));

        if(is_null($obj)){
            $obj            = new JuntaDirectiva();
        }
        $obj->fill($request->all());
        if($obj->save()){
            $arrIntegrantes = [];
            if($request->filled("integrantes")){
                foreach($request->input("integrantes") as $key=>$value){
                    if(!empty($value['codintegrante_junta_directiva']))
                        $detalle    = IntegranteJuntaDirectiva::find($value['codintegrante_junta_directiva']);
                    else
                        $detalle    = new IntegranteJuntaDirectiva();

                    $detalle->codjunta_directiva    = $obj->codjunta_directiva;
                    $detalle->codcolegiado          = $value['codcolegiado'];
                    $detalle->codcargo              = $value['codcargo'];
                    $detalle->save();

                    $arrIntegrantes[]        = $detalle->codintegrante_junta_directiva;
                }
            }

            IntegranteJuntaDirectiva::where("codjunta_directiva", $obj->codjunta_directiva)->whereNotIn("codintegrante_junta_directiva", $arrIntegrantes)->delete();
        }
        return response()->json($obj);
    }

    public function edit($id)
    {
        $obj    =   JuntaDirectiva::withIntegrantesJunta()->find($id);
        return response()->json($obj);
    }

    public function destroy($id)
    {
        $obj    =   JuntaDirectiva::findOrFail($id);
        $obj->delete();
        return response()->json();
    }
}
