<?php

namespace App\Http\Controllers;

use App\Models\SliderPrincipal;
use App\Models\Empresa;
use App\Actions\DataTableInternal;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Yajra\DataTables\DataTables;

class SliderPrincipalController extends Controller
{
    public $modulo                  = "Slider Principal DRASAM";
    public $path_controller         = "slider_principal";

    public $dataTableServer         = null;

    public $dimensiones             = ["ancho"=>1920, "alto"=>1080];
    public $model                   = null;
    public $name_table              = "";
    public $path_file               = "";

    public function __construct()
    {
        $this->model                = new SliderPrincipal();
        $this->name_table           = $this->model->getTable();
        $this->path_file            = $this->model->getPath();

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
        $datos['botones']           = ["new"=>1, "edit"=>1];

        $datos["tabla_grid"]        = $this->dataTableServer->createTable(false);
        $datos["script_grid"]       = $this->dataTableServer->createScript();
        $datos["extend_slider"]     = [];

        $datos["empresa"]           = Empresa::get();
        $datos["dimensiones"]       = $this->dimensiones;
        $datos["default_imagen"]    = $this->model->getUrlImagen();

        return view("{$this->path_controller}.index",$datos);
    }

    /**
     * Funcion creada para la grilla de datatables
     */
    public function grilla()
    {
        $objeto = SliderPrincipal::get();
        return  DataTables::of($objeto)->addIndexColumn()
                ->addColumn("imagen", function($row){
                    return ("<center><img src='".$row->url_imagen."' style='width:80%' class='img-fluid'></center>");
                })
                ->rawColumns(['imagen'])
                ->make(true);
    }

    public function uploadFile(Request $request){
        $filePath   = $request->file('file');
        $fileName   = uniqid().".".$filePath->getClientOriginalExtension();
        $path       = $this->path_file;

        if(!in_array($filePath->getClientOriginalExtension(), ["jpg", "png", "jpeg"]))
            throw ValidationException::withMessages(["file"=>"No subió el formato correcto [jpg, png, jpeg]"]);

        if(!$filePath->move($path, $fileName))
            throw ValidationException::withMessages(["Error al subir el archivo"]);

        return response()->json(["status"=>TRUE, "filename"=>$fileName, "path_file_img"=>url($this->path_file."/".$fileName)]);
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'codempresa'=>'required'
            ,'titulo'=>'required'
            ,'subtitulo'=>'required'
            ,'orden'=>'required|integer'
        ], [
            "codempresa.required"=>"Obligatorio"
            ,'titulo.required'=>'Obligatorio'
            ,'subtitulo.required'=>'Obligatorio'
            ,'orden.required'=>'Reque.'
        ]);

        $obj                = SliderPrincipal::find($request->input("cod{$this->name_table}"));
        if(is_null($obj)){
            $obj            = new SliderPrincipal();
        }
        $obj->fill($request->all());
        $obj->save();
        return response()->json($obj);
    }

    public function edit($id)
    {
        $obj=SliderPrincipal::find($id);
        return response()->json($obj);
    }

    public function destroy($id)
    {
        SliderPrincipal::destroy($id);
        return response()->json();
    }
}
