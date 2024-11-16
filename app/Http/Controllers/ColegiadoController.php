<?php

namespace App\Http\Controllers;

use App\Models\Boton;
use App\Models\Perfil;
use App\Models\Colegiado;
use App\Models\PersonaNatural;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use Yajra\DataTables\DataTables;
use App\Actions\DataTableInternal;
use App\Models\ColegiadoEspecialidad;
use App\Models\Especialidad;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ColegiadoController extends Controller
{
    public $modulo                  = "Colegiado";
    public $path_controller         = "colegiados";

    public $model                   = null;
    public $name_table              = "";

    public $dataTableServer         = null;
    public $path_colegiado          = "";
    public $default_cv              = "";
    public $formato_valido          = ["pdf"];

    public function __construct()
    {
        $this->model                = new Colegiado();
        $this->name_table           = $this->model->getTable();
        $this->path_colegiado       = $this->model->getPath();
        $this->default_cv           = $this->model->getUrlCV();

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

        $datos["extend_colegiados"] = [
                                        "persona_natural.form"=>[
                                            "table_name"=>(new PersonaNatural())->getTable()
                                            , "pathController"=>"persona_natural"
                                            , "modulo"=>"Persona Natural"
                                            , "prefix"=>"_pers"
                                            , "version"=>rand()
                                            , "default_photo"=>(new PersonaNatural())->getUrlFoto()
                                            , "path_photo"=>(new PersonaNatural())->getPath()
                                            , 'extend_persona_natural'=>[
                                                "ubigeo.form"=>[
                                                    "table_name"=>"ubigeo"
                                                    , "pathController"=>"ubigeo"
                                                    , "modulo"=>"Ubigeo"
                                                    , "prefix"=>"_ubg_prov"
                                                    , "version"=>rand()
                                                ]
                                            ]
                                        ]];

        $datos["default_cv"]        = $this->default_cv;
        $datos["formato_valido"]    = $this->formato_valido;
        $datos["especialidades"]    = Especialidad::get();

        return view("{$this->path_controller}.index",$datos);
    }

    public function grilla()
    {
        $objeto = Colegiado::with(["persona_natural"])->get();
        return  DataTables::of($objeto)
                ->addIndexColumn()
                ->addColumn("foto_colegiado", function($row){
                    return ("<center><img  src='".$row->persona_natural->url_foto."' style='width:80%;' class='img-fluid'></center>");
                })
                ->addColumn("estado_colegiado", function($row){
                    $estado = ($row->estado_colegiado=="H")?"Habilitado":"Inhabilitado";
                    $clase  = ($row->estado_colegiado=="H")?"success":"danger";
                    return "<span style='width:100%;' class='badge bg-{$clase}'>{$estado}</span>";
                })
                ->addColumn("c_v", function($row){
                    if($row->curriculum_vitae)
                        return ("<center><a href='".$row->url_cv."' target='_blank' class='btn btn-xs btn-default' ><i class='fa fa-file-pdf fa-2x'></i></a></center>");
                    else
                        return "<center>-</center>";
                })
                ->rawColumns(['foto_colegiado', 'estado_colegiado', 'c_v'])
                ->make(true);
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'codpersona_natural'=>[
                "required"
                , Rule::unique("{$this->model->getTable()}", "codpersona_natural")
                        ->ignore($request->input("cod{$this->name_table}"), "cod{$this->name_table}")
            ]
            ,'numero_colegiatura'=>[
                "required"
                ,"max:20"
                , Rule::unique("{$this->model->getTable()}", "numero_colegiatura")
                        ->ignore($request->input("cod{$this->name_table}"), "cod{$this->name_table}")
            ]
            //,"fecha_colegiatura"=>["required"]
        ],[
            "codpersona_natural.required"=>"Obligatorio"
            ,"codpersona_natural.unique"=>"Ya existe el colegiado"
            ,"numero_colegiatura.required"=>"Obligatorio"
            ,"numero_colegiatura.unique"=>"Ya existe N°"
            //,"fecha_colegiatura.required"=>"Obligatorio"
        ]);

        $filePath   = $request->file('file');
        $fileName   = $request->input("curriculum_vitae");
        $path       = $this->path_colegiado;
        if($filePath){
            $fileName   = "cv_".uniqid().".".$filePath->getClientOriginalExtension();
            if(!file_exists($path))
                mkdir($path, 0777, true);

            if(!in_array($filePath->getClientOriginalExtension(), $this->formato_valido))
                throw ValidationException::withMessages(["file"=>"No subió el formato correcto [".implode(",", $this->formato_valido)."], el formato ".$filePath->getClientOriginalExtension()." no está permitido"]);


            if(!$filePath->move($path, $fileName))
                throw ValidationException::withMessages(["Error al subir el archivo, la ruta especificada {$path}, posiblemente no existe"]);
        }

        $obj                = Colegiado::find($request->input("cod{$this->name_table}"));

        if(is_null($obj)){
            $obj                    = new Colegiado();
            $obj->estado_colegiado  = "H";
        }
        $obj->fill($request->all());
        $obj->curriculum_vitae = $fileName;
        if($obj->save()){
            $arrEspecialides = [];
            if($request->filled("especialides")){
                foreach($request->input("especialides") as $key=>$value){
                    if(!empty($value['codcolegiado_especialidad']))
                        $detalle    = ColegiadoEspecialidad::find($value['codcolegiado_especialidad']);
                    else
                        $detalle    = new ColegiadoEspecialidad();

                    $detalle->codcolegiado    = $obj->codcolegiado;
                    $detalle->codespecialidad  = $value['codespecialidad'];
                    $detalle->save();

                    $arrEspecialides[]        = $detalle->codcolegiado_especialidad;
                }
            }

            ColegiadoEspecialidad::where("codcolegiado", $obj->codcolegiado)->whereNotIn("codcolegiado_especialidad", $arrEspecialides)->delete();
        }
        return response()->json($obj);
    }

    public function edit($id)
    {
        $obj    =   Colegiado::withEspecialidadColegiado()
                    ->with(["persona_natural.tipo_documento_identidad"])->find($id);
        return response()->json($obj);
    }

    public function destroy($id)
    {
        $obj    =   Colegiado::findOrFail($id);
        $obj->delete();
        return response()->json();
    }

    public function autocomplete(Request $request){
        $valor    = $request->get('valor');
        $valor    = Str::upper($valor);
        $consulta = Colegiado::join("persona_natural", "persona_natural.codpersona_natural", "=", "colegiado.codpersona_natural")
                    ->where('nombres','LIKE','%'.$valor.'%')
                    ->orWhere("apellido_paterno",'LIKE','%'.$valor.'%')
                    ->orWhere("apellido_materno",'LIKE','%'.$valor.'%')
                    ->orWhere("numero_documento_identidad",'LIKE','%'.$valor.'%')
                    ->take(10)
                    ->get();

        $array=[];
        if (count($consulta) > 0) {
            foreach ($consulta as $key) {
                $array[]=[
                            "label"                     =>"{$key->numero_documento_identidad} - {$key->apellido_paterno} {$key->apellido_materno}, {$key->nombres}",
                            "nombres"                     =>$key->nombres,
                            "apellido_paterno"            =>$key->apellido_paterno,
                            "apellido_materno"            =>$key->apellido_materno,
                            "numero_documento_identidad"  =>$key->numero_documento_identidad,
                            "id"                          =>$key->codcolegiado,
                            "codpersona_natural"           =>$key->codpersona_natural,
                        ];
            }
        }
        return $array;
    }
}
