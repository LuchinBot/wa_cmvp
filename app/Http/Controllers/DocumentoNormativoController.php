<?php

namespace App\Http\Controllers;

use App\Models\Boton;
use App\Models\DocumentoNormativo;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use Yajra\DataTables\DataTables;
use App\Actions\DataTableInternal;
use Illuminate\Validation\ValidationException;

class DocumentoNormativoController extends Controller
{
    public $modulo                  = "Documento Normativo";
    public $path_controller         = "documentos_normativos";

    public $model                   = null;
    public $name_table              = "";

    public $dataTableServer         = null;
    public $path_doc_norm           = "";
    public $default_doc             = "";
    public $formato_valido          = ["pdf"];

    public function __construct()
    {
        $this->model                = new DocumentoNormativo();
        $this->name_table           = $this->model->getTable();

        $this->path_doc_norm        = $this->model->getPath();
        $this->default_doc          = $this->model->getUrlArchivo();

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

        $datos["extend_documentos_normativos"]   = [];
        $datos["formato_valido"]    = $this->formato_valido;

        return view("{$this->path_controller}.index",$datos);
    }

    public function grilla()
    {
        $objeto = DocumentoNormativo::get();
        return  DataTables::of($objeto)
                ->addIndexColumn()
                ->addColumn("archivo_normativo", function($row){
                    if($row->archivo)
                        return ("<center><a href='".$row->url_archivo."' target='_blank' class='btn btn-xs btn-default' ><i class='fa fa-file-pdf fa-2x'></i></a></center>");
                    else
                        return "<center>-</center>";
                })
                ->rawColumns(['archivo_normativo'])
                ->make(true);
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
            ,"orden"=>["required"]
        ],[
            "titulo.required"=>"Obligatorio"
            ,"orden.required"=>"Obligatorio"
        ]);

        $filePath   = $request->file('file');
        $fileName   = $request->input("archivo");
        $path       = $this->path_doc_norm;
        if($filePath){
            $fileName   = uniqid().".".$filePath->getClientOriginalExtension();
            if(!file_exists($path))
                mkdir($path, 0777, true);

            if(!in_array($filePath->getClientOriginalExtension(), $this->formato_valido))
                throw ValidationException::withMessages(["file"=>"No subió el formato correcto [".implode(",", $this->formato_valido)."], el formato ".$filePath->getClientOriginalExtension()." no está permitido"]);


            if(!$filePath->move($path, $fileName))
                throw ValidationException::withMessages(["Error al subir el archivo, la ruta especificada {$path}, posiblemente no existe"]);
        }

        $obj                = DocumentoNormativo::find($request->input("cod{$this->name_table}"));

        if(is_null($obj)){
            $obj            = new DocumentoNormativo();
        }
        $obj->fill($request->all());
        $obj->archivo = $fileName;
        $obj->save();
        return response()->json($obj);
    }

    public function edit($id)
    {
        $obj    =   DocumentoNormativo::find($id);
        return response()->json($obj);
    }

    public function destroy($id)
    {
        $obj    =   DocumentoNormativo::findOrFail($id);
        $obj->delete();
        return response()->json();
    }
}
