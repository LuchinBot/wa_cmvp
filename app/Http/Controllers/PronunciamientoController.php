<?php

namespace App\Http\Controllers;

use App\Models\Boton;
use App\Models\Pronunciamiento;

use Illuminate\Http\Request;
use Illuminate\Support\Str as Str;
use Illuminate\Validation\ValidationException;

use Yajra\DataTables\DataTables;
use App\Actions\DataTableInternal;

class PronunciamientoController extends Controller
{
    public $modulo                  = "Pronunciamiento";
    public $path_controller         = "pronunciamientos";

    public $model                   = null;
    public $name_table              = null;
    public $path_pronunciamiento    = "";
    public $dataTableServer         = null;
    public $default_imagen          = "";
    public $default_flayer          = "";
    public $formato_valido          = ["jpg", "jpeg", "png"];
    public $dimensiones_imagen      = ["ancho"=>720, "alto"=>980];
    public $dimensiones_flayer      = ["ancho"=>980, "alto"=>1200];

    public function __construct()
    {
        $this->model                = new Pronunciamiento();
        $this->name_table           = $this->model->getTable();
        $this->path_pronunciamiento      = $this->model->getPath();
        $this->default_imagen       = $this->model->getUrlImagen();
        $this->default_flayer       = $this->model->getUrlImagenFlayer();

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
        $datos["botones"]           = ["new"=>1, "edit"=>1];
        $datos["tabla_grid"]        = $this->dataTableServer->createTable(false);
        $datos["script_grid"]       = $this->dataTableServer->createScript();
        $datos["extend_pronunciaminto"]= [];

        $datos["default_imagen"]    = $this->default_imagen;
        $datos["default_flayer"]    = $this->default_flayer;
        $datos["formato_valido"]    = $this->formato_valido;
        $datos["dimension_imagen"]  = $this->dimensiones_imagen;
        $datos["dimension_flayer"]  = $this->dimensiones_flayer;

        return view("{$this->path_controller}.index",$datos);
    }

    public function grilla()
    {
        $objeto = Pronunciamiento::get();

        return DataTables::of($objeto)
                            ->addIndexColumn()
                            ->addColumn("flayer_pronunciamiento", function($row){
                                return ("<center><img  src='".$row->url_imagen_flayer."' style='width:30%;' class='img-fluid'></center>");
                            })
                            ->rawColumns(['flayer_pronunciamiento'])
                        ->make(true);
    }

    public function uploadFile(Request $request){
        $filePath   = $request->file('file');
        $fileName   = uniqid().".".$filePath->getClientOriginalExtension();
        $path       = $this->path_pronunciamiento;

        if(!in_array($filePath->getClientOriginalExtension(), ["jpg", "png", "jpeg"]))
            throw ValidationException::withMessages(["file"=>"No subió el formato correcto [jpg, png, jpeg]"]);

        if(!$filePath->move($path, $fileName))
            throw ValidationException::withMessages(["Error al subir el archivo"]);

        return response()->json(["status"=>TRUE, "filename"=>$fileName, "path_file_img"=>url($this->path_pronunciamiento."/".$fileName)]);
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'titulo'=>["required","max:250"],
            'descripcion'=>["required"],
            'fecha'=>["required", "date"],
            'fecha_publicacion_inicio'=>["required", "date"],
            'fecha_publicacion_fin'=>["required", "date"],
        ],[
            "titulo.required"=>"Obligatorio"
            ,"titulo.max"=>"Maximo 250 caracteres"
            ,"descripcion.required"=>"Obligatorio"
            ,"fecha.required"=>"Obligatorio"
            ,"fecha_publicacion_inicio.required"=>"Obligatorio"
            ,"fecha_publicacion_fin.required"=>"Obligatorio"
        ]);


        $filePath   = $request->file('file_archivo');
        $fileName   = $request->input("imagen");
        $path       = $this->path_pronunciamiento;
        if($filePath){
            $fileName   = "principal_".uniqid().".".$filePath->getClientOriginalExtension();
            if(!file_exists($path))
                mkdir($path, 0777, true);

            if(!in_array($filePath->getClientOriginalExtension(), $this->formato_valido))
                throw ValidationException::withMessages(["file_archivo"=>"No subió el formato correcto [".implode(",", $this->formato_valido)."]"]);


            if(!$filePath->move($path, $fileName))
                throw ValidationException::withMessages(["Error al subir el archivo, la ruta especificada {$path}, posiblemente no existe"]);
        }

        $obj                = Pronunciamiento::find($request->input("cod{$this->name_table}"));
        if(is_null($obj)){
            $obj            = new Pronunciamiento();
        }

        $obj->fill($request->all());
        $obj->slug      = Str::slug($request->input("titulo"));
        $obj->imagen    = $fileName;
        $obj->save();
        return response()->json($obj);
    }

    public function edit($id)
    {
        $obj=Pronunciamiento::find($id);
        return response()->json($obj);
    }

    public function destroy($id)
    {
        $obj=Pronunciamiento::findOrFail($id);
        $obj->delete();
        return response()->json();
    }
}
