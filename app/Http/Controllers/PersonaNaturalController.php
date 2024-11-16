<?php

namespace App\Http\Controllers;

use App\Actions\ConsultaPersonaNatural;
use App\Actions\DataTableInternal;

use App\Models\Boton;
use App\Models\PersonaNatural;
use App\Models\TipoDocumentoIdentidad;

use Yajra\DataTables\DataTables;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class PersonaNaturalController extends Controller
{
    public $modulo                  = "Persona Natural";
    public $path_controller         = "persona_natural";

    public $model                   = null;
    public $name_table              = "";

    public $dataTableServer         = null;
    public $api_key_consulta_dni    = "";
    public $path_photo              = null;
    public $default_photo           = "";
    public $size_php_ini            = 0;//Parametro "upload_max_filesize" puesto en php.ini (En Mb) /*Maximum allowed size for uploaded files.*/
    public $doc_identidad           = ["DNI", "CEXT", "PASP"];

    public function __construct()
    {
        $this->model                = new PersonaNatural();
        $this->name_table           = $this->model->getTable();
        $this->path_photo           = $this->model->getPath();
        $this->default_photo        = $this->model->getUrlFoto();

        $this->dataTableServer          = new DataTableInternal($this->path_controller.".grilla");
        $this->dataTableServer->setModel($this->model);
        $this->dataTableServer->setColumns($this->model->getColumnDataTable());
    }

    public function index()
    {
        $datos["table_name"]                = $this->name_table;
        $datos["pathController"]            = $this->path_controller;
        $datos["aliaspathController"]       = $this->path_controller;
        $datos["prefix"]                    = "";
        $datos["version"]                   = rand();
        $datos["modulo"]                    = $this->modulo;
        $datos["path_photo"]                = $this->path_photo;
        $datos["default_photo"]             = $this->default_photo;
        //$datos['botones']                   = Boton::botones($this->path_controller)->get();
        $datos['botones']           = ["new"=>1, "edit"=>1];

        $datos["extend_persona_natural"]    = [
                                                "ubigeo.form"=>[
                                                    "table_name"=>"ubigeo"
                                                    , "pathController"=>"ubigeo"
                                                    , "aliaspathController"=>"ubigeo"
                                                    , "modulo"=>"Ubigeo "
                                                    , "prefix"=>"_ubg_prov"
                                                    , "version"=>rand()
                                                ]
                                            ];

        $datos["tabla_grid"]        = $this->dataTableServer->createTable(false);
        $datos["script_grid"]       = $this->dataTableServer->createScript();

        return view("{$this->path_controller}.index",$datos);
    }

    public function grilla()
    {
        $objeto = PersonaNatural::get();
        return  DataTables::of($objeto)->addIndexColumn()->make(true);
    }

    public function grillaPopup(){
        $this->dataTableServer          = new DataTableInternal($this->path_controller.".grilla");
        $this->dataTableServer->setModel($this->model);
        $this->dataTableServer->setColumns($this->model->getColumnDataTablePopUp());
        $this->dataTableServer->setPopup(true);

        $datos["tabla_grid"]        = $this->dataTableServer->createTable(false);
        $datos["script_grid"]       = $this->dataTableServer->createScript();

        return response()->json($datos);
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            "codtipo_documento_identidad"=>"required",
            "nombres"=>"required",
            "apellido_paterno"=>"required",
            "apellido_materno"=>"required",
            'numero_documento_identidad'=>[
                "required"
                , Rule::unique("{$this->model->getTable()}", "numero_documento_identidad")
                        ->ignore($request->input("cod{$this->name_table}"), "cod{$this->name_table}")
                ,"digits:8"
            ],
            "email"=>"email|max:255|nullable",
            "telefono"=>"digits:9|nullable"
        ],[
            "nombres.required"=>"Ingrese los nombres",
            "apellido_paterno.required"=>"Ingrese apellido paterno",
            "apellido_materno.required"=>"Ingrese apellido materno",
            "numero_documento_identidad.required"=>"Ingrese el Nro. Doc. Ident.",
            "numero_documento_identidad.unique"=>"El Nro. Doc. Ident. ya existe",
            "email.email"=>"Ingrese email valido",
            "telefono.digits"=>"Ingrese 9 digitos",
        ]);

        return DB::transaction(function() use ($request){
            $fileName   = $request->input("foto");
            $filePath   = $request->file('file');

            if($filePath){
                $extension  = $filePath->getClientOriginalExtension();
                $size       = $filePath->getSize();
                $fileName   = uniqid().".".$extension;
                $path       = public_path($this->path_photo);

                if($size<1)
                    throw ValidationException::withMessages(["La foto del empleado, excede el tamaño permitido ({$this->size_php_ini}), por favor comprima el archivo o comunicarse con SOPORTE. (Seccion I)"]);

                if(!file_exists($path))
                    mkdir($path, 0777, true);

                if(!$filePath->move($path, $fileName))
                    throw ValidationException::withMessages(["Error al subir LA FOTO"]);
            }

            if($request->filled("telefono")){
                if(strlen($request->input("telefono"))!=9){
                    throw ValidationException::withMessages(["El telefono móvil debe tener 9 digitos"]);
                }
            }
            $obj        = PersonaNatural::find($request->input("cod{$this->name_table}"));

            if(is_null($obj)){
                $obj    = new PersonaNatural();
            }
            $obj->fill($request->all());
            $obj->foto    = $fileName;
            $obj->save();

            return response()->json($obj);
        });
    }

    public function edit($id)
    {
        $obj = PersonaNatural::selectRaw("
                persona_natural.*
                ,CONCAT(departamento.nombre,' - ',provincia.nombre,' - ',distrito.nombre) as ubigeo_descr
            ")
            ->leftJoin("ubigeo as distrito", "distrito.id_ubigeo", "=", "persona_natural.id_ubigeo")
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
        $obj=PersonaNatural::findOrFail($id);
        $obj->delete();
        return response()->json();
    }

    public function searchInternal(Request $request){
        $this->validate($request,[
            "numero_documento_identidad"=>"required|digits:8",
            "codtipo_documento_identidad"=>"required"
        ],[
            "numero_documento_identidad.required"=>"Obligatorio",
            "numero_documento_identidad.digits"=>"8 dígitos requeridos",
            "codtipo_documento_identidad"=>"Ingrese tipo doc. identidad"
        ]);

        $objPersona = PersonaNatural::where("codtipo_documento_identidad", $request->input("codtipo_documento_identidad"))
                        ->where("numero_documento_identidad", $request->input("numero_documento_identidad"))
                        ->first();

        return response()->json($objPersona);
    }

    public function searchExternal(Request $request){
        $this->validate($request,[
            "numero_documento_identidad"=>"required|digits:8",
            "codtipo_documento_identidad"=>"required"
        ],[
            "numero_documento_identidad.required"=>"Nro de identificacion obligatorio",
            "codtipo_documento_identidad"=>"Ingrese tipo doc. identidad"
        ]);

        $ObjTipoDocId   = TipoDocumentoIdentidad::find($request->input("codtipo_documento_identidad"));
        if(is_null($ObjTipoDocId))
            return response()->json(["status"=>false, "message"=>"Ingrese el tipo de documento de identidad", "data"=>[]]);

        if(!in_array($ObjTipoDocId->id_api, $this->doc_identidad)){
            return response()->json(["status"=>false, "message"=>"El tipo de documento de identidad ingresado o seleccionado no es valido para una persona natural", "data"=>[]]);
        }
        $requestInput = $request->merge(["tipo_documento_identidad"=>$ObjTipoDocId->id_api]);
        $apiConsulta = (new ConsultaPersonaNatural)->ConsultaExternaLCS($requestInput);

        return response()->json($apiConsulta);
    }

    public function searchExternalSave(Request $request){
        $this->validate($request,[
            "numero_documento_identidad"=>"required|digits:8",
            "codtipo_documento_identidad"=>"required"
        ],[
            "numero_documento_identidad.required"=>"Nro de identificacion obligatorio",
            "codtipo_documento_identidad"=>"Ingrese tipo doc. identidad"
        ]);

        $ObjTipoDocId   = TipoDocumentoIdentidad::find($request->input("codtipo_documento_identidad"));
        if(is_null($ObjTipoDocId))
            return response()->json(["status"=>false, "message"=>"Ingrese el tipo de documento de identidad", "data"=>[]]);

        if(!in_array($ObjTipoDocId->id_api, $this->doc_identidad)){
            return response()->json(["status"=>false, "message"=>"El tipo de documento de identidad ingresado o seleccionado no es valido para una persona natural", "data"=>[]]);
        }
        $requestInput = $request->merge(["tipo_documento_identidad"=>$ObjTipoDocId->id_api]);
        $apiConsulta = (new ConsultaPersonaNatural)->ConsultaExternaLCS($requestInput);

        if($apiConsulta["status"])
            (new ConsultaPersonaNatural)->GuardarPersona(new Request([$apiConsulta["data"]]));

        return response()->json($apiConsulta);
    }

    public function searchAll(Request $request){
        $this->validate($request,[
            "numero_documento_identidad"=>"required|min:8",
            "codtipo_documento_identidad"=>"required"
        ],[
            "numero_documento_identidad.required"=>"Obligatorio",
            "codtipo_documento_identidad"=>"Ingrese tipo doc. identidad"
        ]);

        $objPersona = json_decode($this->searchInternal($request)->getContent(), true);

        if(count($objPersona)<1){
            $datosExternos = json_decode($this->searchExternal($request)->getContent(), true);
            if($datosExternos["status"]==TRUE){
                $post_data = new Request($datosExternos["data"]);
                $objPersona = json_decode($this->store($post_data->merge(["codtipo_documento_identidad"=>$request->input("codtipo_documento_identidad")]))->getContent());
            }
        }

        if(!is_array($objPersona))
            $objPersona = (array) $objPersona;

        if(count($objPersona)>0 && $request->filled("save")){
            //dd($objPersona);
            (new ConsultaPersonaNatural)->GuardarPersona(new Request($objPersona));
        }

        return response()->json($objPersona);
    }

    public function autocomplete(Request $request){
        $valor    = $request->get('valor');
        $valor    = Str::upper($valor);
        $consulta = PersonaNatural::where('nombres','LIKE','%'.$valor.'%')
                    ->orWhere("apellido_paterno",'LIKE','%'.$valor.'%')
                    ->orWhere("apellido_materno",'LIKE','%'.$valor.'%')
                    ->orWhere("numero_documento_identidad",'LIKE','%'.$valor.'%')
                    ->take(10)
                    ->get();

        $array=[];
        if (count($consulta) > 0) {
            foreach ($consulta as $key) {
                $array[]=[
                            "label"   =>"{$key->numero_documento_identidad} - {$key->apellido_paterno} {$key->apellido_materno}, {$key->nombres}",
                            "nombres"                     =>$key->nombres,
                            "apellido_paterno"            =>$key->apellido_paterno,
                            "apellido_materno"            =>$key->apellido_materno,
                            "numero_documento_identidad"  =>$key->numero_documento_identidad,
                            "id"                          =>$key->codpersona_natural,
                            "codpersona_natural"           =>$key->codpersona_natural,
                        ];
            }
        }
        return $array;
    }
}
