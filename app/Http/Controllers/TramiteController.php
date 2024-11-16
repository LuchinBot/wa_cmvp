<?php

namespace App\Http\Controllers;

use App\Models\Boton;
use App\Models\Tramite;
use App\Models\Requisito;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

use Yajra\DataTables\DataTables;
use App\Actions\DataTableInternal;
use App\Models\RequisitoTramite;
use Illuminate\Support\Str as Str;
use Illuminate\Validation\ValidationException;

class TramiteController extends Controller
{
    public $modulo                  = "Trámite";
    public $path_controller         = "tramites";

    public $model                   = null;
    public $name_table              = null;

    public $dataTableServer         = null;
    public $formato_valido          = ["pdf"];

    public function __construct()
    {
        $this->model                = new Tramite();
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
        $datos["botones"]           = ["new"=>1, "edit"=>1, "delete"=>1];
        $datos["tabla_grid"]        = $this->dataTableServer->createTable(false);
        $datos["script_grid"]       = $this->dataTableServer->createScript();
        $datos["extend_tramites"]   = [
                                        "requisitos.form"=>[
                                            "table_name"=>(new Requisito())->getTable()
                                            , "pathController"=>"requisitos"
                                            , "modulo"=>"Requisito"
                                            , "prefix"=>"_requ"
                                            , "version"=>rand()
                                        ]
        ];

        $datos["iconos"]            = $this->get_icons()??[];
        $datos["requisitos"]        = Requisito::get();
        //dd($datos["iconos"]);
        return view("{$this->path_controller}.index", $datos);
    }

    public function grilla()
    {
        $objeto = Tramite::get();
        //fa_icono
        return DataTables::of($objeto)->addIndexColumn()
        ->addColumn("fa_icono", function($row){
            return "<i class='fa fa-2x {$row->icono}'></i>";
        })
        ->rawColumns(['fa_icono'])
        ->make(true);
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'titulo'=>[
                "required"
                , "max:250"
                , Rule::unique("{$this->model->getTable()}", "titulo")->ignore($request->input("cod{$this->name_table}"), "cod{$this->name_table}")
            ]
            ,'descripcion'=>[
                "required"
            ]
            ,'icono'=>[
                "required"
            ]
            ,"orden"=>[
                "required"
                ,"integer"
            ]
            ,"derecho_pago"=>[
                "required"
            ]
        ],[
            "titulo.required"=>"Obligatorio"
            ,"titulo.unique"=>"Ya existe"
            ,"descripcion.required"=>"Obligatorio"
            ,"icono.required"=>"Obligatorio"
            ,"orden.required"=>"Obligatorio"
            ,"derecho_pago.required"=>"Obligatorio"
        ]);
        $obj                = Tramite::find($request->input("cod{$this->name_table}"));
        if(is_null($obj)){
            $obj            = new Tramite();
        }
        $obj->fill($request->all());
        $obj->slug      = Str::slug($request->input("titulo"));
        if($obj->save()){
            $arrRequisitos = [];
            if($request->filled("requisitos")){
                foreach($request->input("requisitos") as $key=>$value){
                    if(!empty($value['codrequisito_tramite']))
                        $detalle    = RequisitoTramite::find($value['codrequisito_tramite']);
                    else
                        $detalle    = new RequisitoTramite();

                    $detalle->codtramite    = $obj->codtramite;
                    $detalle->codrequisito  = $value['codrequisito'];
                    $detalle->archivo       = $value['archivo'];
                    $detalle->nota          = $value['nota'];
                    $detalle->save();

                    $arrRequisitos[]        = $detalle->codrequisito_tramite;
                }
            }

            RequisitoTramite::where("codtramite", $obj->codtramite)->whereNotIn("codrequisito_tramite", $arrRequisitos)->delete();
        }

        return response()->json($obj);
    }

    public function edit($id)
    {
        $obj=Tramite::withRequisitosTramite()->find($id);
        return response()->json($obj);
    }

    public function destroy($id)
    {
        $obj=Tramite::findOrFail($id);
        $obj->delete();
        return response()->json();
    }

    public function uploadFile(Request $request){
        $this->validate($request,[
            "file"=>"required|mimes:".implode(", ", $this->formato_valido)
        ], [
            "file.required"=>"Obligatorio"
        ]);

        $filePath   = $request->file('file');
        $path       = (new RequisitoTramite())->getPath();
        $fileName   = "requisito_".uniqid().".".$filePath->getClientOriginalExtension();
        if($filePath){
            if(!file_exists($path))
                mkdir($path, 0777, true);

            if(!in_array($filePath->getClientOriginalExtension(), $this->formato_valido))
                throw ValidationException::withMessages(["file"=>"No subió el formato correcto [".implode(",", $this->formato_valido)."]"]);


            if(!$filePath->move($path, $fileName))
                throw ValidationException::withMessages(["Error al subir el archivo, la ruta especificada {$path}, posiblemente no existe"]);
        }

        return response()->json(["file"=>url($path.$fileName), "archivo"=>$fileName]);
    }
}
