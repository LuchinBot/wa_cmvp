<?php

namespace App\Http\Controllers;

use App\Models\Boton;
use App\Models\Noticia;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use Yajra\DataTables\DataTables;
use App\Actions\DataTableInternal;
use Illuminate\Support\Str as Str;
use Illuminate\Validation\ValidationException;

class NoticiaController extends Controller
{
    public $modulo                  = "Noticia";
    public $path_controller         = "noticias";

    public $model                   = null;
    public $name_table              = "";

    public $dataTableServer         = null;
    public $path_noticia            = "";
    public $default_noticia         = "";
    public $dimensiones             = ["ancho"=>1920, "alto"=>1080];

    public function __construct()
    {
        $this->model                = new Noticia();
        $this->name_table           = $this->model->getTable();
        $this->path_noticia         = $this->model->getPath();
        $this->default_noticia      = $this->model->getUrlImagen();

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

        $datos["extend_noticias"]   = [];
        $datos["dimensiones"]       = $this->dimensiones;
        $datos["path_noticia"]      = $this->path_noticia;
        $datos["default_noticia"]   = $this->default_noticia;

        return view("{$this->path_controller}.index",$datos);
    }

    public function grilla()
    {
        $objeto = Noticia::get();
        return  DataTables::of($objeto)
                ->addIndexColumn()
                ->addColumn("descripcion_noticia", function($row){
                    return $row->descripcion;
                })
                ->addColumn("imagen_noticia", function($row){
                    return ("<center><img src='".$row->url_imagen."' style='width:50%' class='img-fluid'></center>");
                })
                ->rawColumns(['descripcion_noticia', 'imagen_noticia'])
                ->make(true);
    }

    public function uploadFile(Request $request){
        $filePath   = $request->file('file');
        $fileName   = uniqid().".".$filePath->getClientOriginalExtension();
        $path       = $this->path_noticia;

        if(!in_array($filePath->getClientOriginalExtension(), ["jpg", "png", "jpeg"]))
            throw ValidationException::withMessages(["file"=>"No subió el formato correcto [jpg, png, jpeg]"]);

        if(!$filePath->move($path, $fileName))
            throw ValidationException::withMessages(["Error al subir el archivo"]);

        return response()->json(["status"=>TRUE, "filename"=>$fileName, "path_file_img"=>url($this->path_noticia."/".$fileName)]);
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
            ,'descripcion'=>["required"]
            ,'fecha'=>["required"]
        ],[
            "titulo.required"=>"Obligatorio"
            ,"descripcion.required"=>"Obligatorio"
            ,"fecha.required"=>"Obligatorio"
        ]);

        $obj                = Noticia::find($request->input("cod{$this->name_table}"));

        if(is_null($obj)){
            $obj            = new Noticia();
        }
        $obj->fill($request->all());
        $obj->slug      = Str::slug($request->input("titulo"));
        $obj->save();
        return response()->json($obj);
    }

    public function edit($id)
    {
        $obj    =   Noticia::find($id);
        return response()->json($obj);
    }

    public function destroy($id)
    {
        $obj    =   Noticia::findOrFail($id);
        $obj->delete();
        return response()->json();
    }
}
