<?php

namespace App\Http\Controllers;

use App\Models\Empresa;

use Illuminate\Http\Request;
use App\Actions\DataTableInternal;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class EmpresaController extends Controller
{
    public $modulo                  = "Empresa";
    public $path_controller         = "empresas";

    public $dataTableServer         = null;

    public $model                   = null;
    public $name_table              = "";
    public $path_empresa            = "";
    public $default_logo            = "";
    public $default_objetivo        = "";
    public $default_consejo_cmv     = "";
    public $default_asamblea        = "";
    public $formato_valido          = ["jpg", "jpeg", "png", "PNG"];
    public $dimensiones_logo        = ["ancho"=>864, "alto"=>120];
    public $dimensiones_objetivo    = ["ancho"=>1846, "alto"=>1096];
    public $dimensiones_consejo_d   = ["ancho"=>1920, "alto"=>1080];
    public $dimensiones_asamblea    = ["ancho"=>1020, "alto"=>558];

    public function __construct()
    {
        $this->model                = new Empresa();
        $this->name_table           = $this->model->getTable();
        $this->path_empresa         = $this->model->getPath();
        $this->default_logo         = $this->model->getUrlLogo();
        $this->default_objetivo     = $this->model->getUrlImagenObjetivo();
        $this->default_consejo_cmv  = $this->model->getUrlImagenConsejo();
        $this->default_asamblea     = $this->model->getUrlImagenAsamblea();

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
        $datos["botones"]           = ["edit"=>1];

        $datos["tabla_grid"]        = $this->dataTableServer->createTable(false);
        $datos["script_grid"]       = $this->dataTableServer->createScript();

        $datos["extend_empresas"]   = [
                                        "ubigeo.form"=>[
                                            "table_name"=>"ubigeo"
                                            , "pathController"=>"ubigeo"
                                            , "aliaspathController"=>"ubigeo"
                                            , "modulo"=>"Ubigeo "
                                            , "prefix"=>"_ubg_prov"
                                            , "version"=>rand()
                                        ]
                                    ];
        $datos["default_logo"]      = $this->default_logo;
        $datos["default_obj"]       = $this->default_objetivo;
        $datos["default_cmvsm"]     = $this->default_consejo_cmv;
        $datos["default_asamblea"]  = $this->default_asamblea;

        $datos["formato_valido"]    = $this->formato_valido;
        $datos["dimension_logo"]    = $this->dimensiones_logo;
        $datos["dimension_objetivo"]= $this->dimensiones_objetivo;
        $datos["dimension_consejo_d"]= $this->dimensiones_consejo_d;
        $datos["dimension_asamblea"]= $this->dimensiones_asamblea;

        return view("{$this->path_controller}.index",$datos);
    }

    public function grilla()
    {
        $objeto = Empresa::get();
        return  DataTables::of($objeto)
                            ->addIndexColumn()
                            ->addColumn("logo_colegio", function($row){
                                return ("<center><img  src='".$row->url_logo."' class='img-fluid'></center>");
                            })
                            ->rawColumns(['logo_colegio'])
                            ->make(true);
    }

    public function uploadFile(Request $request){
        $filePath   = $request->file('file');
        $fileName   = "asamblea_".uniqid().".".$filePath->getClientOriginalExtension();
        $path       = $this->path_empresa;

        if(!in_array($filePath->getClientOriginalExtension(), ["jpg", "png", "jpeg"]))
            throw ValidationException::withMessages(["file"=>"No subió el formato correcto [jpg, png, jpeg]"]);

        if(!$filePath->move($path, $fileName))
            throw ValidationException::withMessages(["Error al subir el archivo"]);

        return response()->json(["status"=>TRUE, "filename"=>$fileName, "path_file_img"=>url($this->path_empresa."/".$fileName)]);
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'ruc'=>'required|min:11'
            ,'razon_social'=>'required'
            ,'abreviatura'=>'required'
            ,"file"=>"nullable|mimes:".implode(",", $this->formato_valido)
        ], [
            "ruc.required"=>"Obligatorio"
            ,"razon_social.required"=>"Obligatorio"
            ,"abreviatura.required"=>"Obligatorio"
        ]);

        $path       = $this->path_empresa;

        /**
         * Subir logo
         */
        $filePath   = $request->file('file');
        $fileName   = $request->input("logo");
        if($filePath){
            $fileName   = "logo_".uniqid().".".$filePath->getClientOriginalExtension();
            if(!file_exists($path))
                mkdir($path, 0777, true);

            if(!in_array($filePath->getClientOriginalExtension(), $this->formato_valido))
                throw ValidationException::withMessages(["file"=>"No subió el formato correcto [".implode(",", $this->formato_valido)."], el formato ".$filePath->getClientOriginalExtension()." no está permitido"]);


            if(!$filePath->move($path, $fileName))
                throw ValidationException::withMessages(["Error al subir el archivo, la ruta especificada {$path}, posiblemente no existe"]);
        }

        /**
         * Subir Objetivo
         */
        $filePath   = $request->file('file_objetivo');
        $fileObj    = $request->input("imagen_objetivo");
        if($filePath){
            $fileObj   = "objetivo_".uniqid().".".$filePath->getClientOriginalExtension();
            if(!file_exists($path))
                mkdir($path, 0777, true);

            if(!in_array($filePath->getClientOriginalExtension(), $this->formato_valido))
                throw ValidationException::withMessages(["file_objetivo"=>"No subió el formato correcto [".implode(",", $this->formato_valido)."]"]);


            if(!$filePath->move($path, $fileObj))
                throw ValidationException::withMessages(["Error al subir el archivo, la ruta especificada {$path}, posiblemente no existe"]);
        }

        /**
         * Subir Consejo Departamental
         */
        $filePath   = $request->file('file_consejo');
        $fileCDM    = $request->input("imagen_consejo");
        $path       = $this->path_empresa;
        if($filePath){
            $fileCDM   = "CMDSM_".uniqid().".".$filePath->getClientOriginalExtension();
            if(!file_exists($path))
                mkdir($path, 0777, true);

            if(!in_array($filePath->getClientOriginalExtension(), $this->formato_valido))
                throw ValidationException::withMessages(["file_consejo"=>"No subió el formato correcto [".implode(",", $this->formato_valido)."]"]);


            if(!$filePath->move($path, $fileCDM))
                throw ValidationException::withMessages(["Error al subir el archivo, la ruta especificada {$path}, posiblemente no existe"]);
        }

        $obj                = Empresa::find($request->input("cod{$this->name_table}"));
        if(is_null($obj)){
            $obj            = new Empresa();
        }
        $obj->fill($request->all());
        $obj->logo              = $fileName;
        $obj->imagen_objetivo   = $fileObj;
        $obj->imagen_consejo    = $fileCDM;
        $obj->save();

        return response()->json($obj);
    }

    public function edit($id)
    {
        $obj=Empresa::selectRaw("
            empresa.*
            ,CONCAT(departamento.nombre,' - ',provincia.nombre,' - ',distrito.nombre) as ubigeo_descr
        ")
        ->leftJoin("ubigeo as distrito", "distrito.id_ubigeo", "=", "empresa.id_ubigeo")
        ->leftJoin("ubigeo as provincia", function($sq){
            $sq->on("provincia.cod_dpto", "=", "distrito.cod_dpto");
            $sq->on("provincia.cod_prov", "=", "distrito.cod_prov");
            $sq->on("provincia.cod_dist", "=", DB::raw("'00'"));
        })
        ->leftJoin("ubigeo as departamento", function($sq){
            $sq->on("departamento.cod_dpto", "=", "distrito.cod_dpto");
            $sq->on("departamento.cod_prov", "=", DB::raw("'00'"));
            $sq->on("departamento.cod_dist", "=", DB::raw("'00'"));
        })
        ->find($id);
        return response()->json($obj);
    }

    public function destroy($id)
    {
        Empresa::destroy($id);
        return response()->json();
    }
}
