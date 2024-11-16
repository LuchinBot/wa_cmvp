<?php

namespace App\Http\Controllers;

use App\Models\Boton;
use App\Models\ActividadInstitucional;
use App\Models\GaleriaActividadInstitucional;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str as Str;

use Yajra\DataTables\DataTables;
use App\Actions\DataTableInternal;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ActividadInstitucionalController extends Controller
{
    public $modulo                  = "Actividad Institucional";
    public $path_controller         = "actividad_institucional";

    public $model                   = null;
    public $name_table              = "";
    public $path_img                = "";
    public $formato_valido          = ["jpg", "jpeg", "png", "PNG"];
    public $dataTableServer         = null;

    public function __construct()
    {
        $this->model                = new ActividadInstitucional();
        $this->name_table           = $this->model->getTable();
        $this->path_img             = $this->model->getPath();

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

        $datos["extend_actividad"]  = [];

        return view("{$this->path_controller}.index",$datos);
    }

    public function grilla()
    {
        $objeto = ActividadInstitucional::get();
        return  DataTables::of($objeto)
                ->addColumn("imagen", function($row){
                    return ("<center><img  src='".$row->url_imagen."' class='img-fluid'></center>");
                })
                ->rawColumns(['imagen'])
                ->make(true);
    }

    public function uploadFile(Request $request){
        $this->validate($request, [
            "file"=>"required|mimes:jpg,bmp,png"
        ]);

        return DB::transaction(function() use ($request){
            $filePath   = $request->file('file');
            $path       = $this->path_img;
            if($filePath){
                $fileName   = uniqid().".".$filePath->getClientOriginalExtension();
                if(!file_exists($path))
                    mkdir($path, 0777, true);

                if(!in_array($filePath->getClientOriginalExtension(), $this->formato_valido))
                    throw ValidationException::withMessages(["file"=>"No subió el formato correcto [".implode(",", $this->formato_valido)."], el formato ".$filePath->getClientOriginalExtension()." no está permitido"]);


                if(!$filePath->move($path, $fileName))
                    throw ValidationException::withMessages(["Error al subir el archivo, la ruta especificada {$path}, posiblemente no existe"]);
            }else{
                throw ValidationException::withMessages(["No existe el archivo que desea subir"]);
            }

            return response()->json(["status"=>TRUE, "filename"=>$fileName, "url_imagen"=>url($this->path_img.$fileName)]);
        });
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'titulo'=>[
                "required"
                ,"max:250"
                , Rule::unique("{$this->model->getTable()}", "titulo")
                        ->ignore($request->input("cod{$this->name_table}"), "cod{$this->name_table}")
            ]
            ,'fecha'=>["required"]
        ],[
            "titulo.required"=>"Obligatorio"
            ,"fecha.required"=>"Obligatorio"
        ]);

        $obj                = ActividadInstitucional::find($request->input("cod{$this->name_table}"));

        if(is_null($obj)){
            $obj            = new ActividadInstitucional();
        }
        $obj->fill($request->all());
        $obj->slug      = Str::slug($request->input("titulo"));
        if($obj->save()){
            $galeria_activas = [];

            $imagen = null;
            if ($request->has('galeria_bien')){
                foreach($request->input('galeria_bien') as $key=>$value){
                    if($key==0)
                        $imagen = $value["imagen"];

                    if(!empty($value["codgaleria_actividad_institucional"]))
                        $galeria                    = GaleriaActividadInstitucional::find($value["codgaleria_actividad_institucional"]);
                    else{
                        $galeria                    = GaleriaActividadInstitucional::where("codgaleria_actividad_institucional", $value["codgaleria_actividad_institucional"])
                                                    ->where("codactividad_institucional", $obj->codactividad_institucional)
                                                    ->withTrashed()
                                                    ->first();

                        if(!is_null($galeria))
                            $galeria->deleted_at    = null;
                        else
                            $galeria                = new GaleriaActividadInstitucional();
                    }

                    $galeria->codactividad_institucional=	$obj->codactividad_institucional;
                    $galeria->imagen                    =	$value["imagen"];
                    $galeria->orden                     =	($key+1);
                    $galeria->save();

                    $galeria_activas[] = $galeria->codgaleria_actividad_institucional;
                }
            }
            $obj->where("cod{$this->name_table}", $obj->codactividad_institucional)->update(["imagen_principal"=>$imagen]);
            GaleriaActividadInstitucional::where("codactividad_institucional", $obj->codactividad_institucional)->whereNotIn("codgaleria_actividad_institucional", $galeria_activas)->delete();
        }
        return response()->json($obj);
    }

    public function edit($id)
    {
        $obj    =   ActividadInstitucional::with(["galeria"])->find($id);
        return response()->json($obj);
    }

    public function destroy($id)
    {
        $obj    =   ActividadInstitucional::findOrFail($id);
        $obj->delete();
        return response()->json();
    }
}
