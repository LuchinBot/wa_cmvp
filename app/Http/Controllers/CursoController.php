<?php

namespace App\Http\Controllers;

use App\Models\Boton;
use App\Models\Curso;
use App\Models\TipoCurso;
use App\Models\ParticipanteCurso;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use Yajra\DataTables\DataTables;
use App\Actions\DataTableInternal;

use Illuminate\Support\Str as Str;
use Illuminate\Validation\ValidationException;

class CursoController extends Controller
{
    public $modulo                  = "Curso";
    public $path_controller         = "cursos";

    public $model                   = null;
    public $name_table              = "";

    public $dataTableServer         = null;
    public $path_curso              = "";
    public $default_flayer          = "";
    public $default_plantilla       = "";
    public $formato_valido          = ["jpg", "jpeg", "png"];
    public $dimensiones_flayer      = ["ancho"=>720, "alto"=>980];
    public $dimensiones_certificado = ["ancho"=>1200, "alto"=>980];

    public function __construct()
    {
        $this->model                = new Curso();
        $this->name_table           = $this->model->getTable();
        $this->path_curso           = $this->model->getPathCurso();
        $this->default_flayer       = $this->model->getUrlFlayer();
        $this->default_plantilla    = $this->model->getUrlPlantillaCertificado();

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
        $datos["botones"]           = ["new"=>1, "edit"=>1];

        $datos["tabla_grid"]        = $this->dataTableServer->createTable(false);
        $datos["script_grid"]       = $this->dataTableServer->createScript();
        $datos["extend_cursos"]     = [

        ];

        $datos["tipo_cursos"]       = TipoCurso::get();
        $datos["default_flayer"]    = $this->default_flayer;
        $datos["default_plantilla"] = $this->default_plantilla;
        $datos["formato_valido"]    = $this->formato_valido;
        $datos["dimension_flayer"]  = $this->dimensiones_flayer;
        $datos["dimension_certificado"]= $this->dimensiones_certificado;

        return view("{$this->path_controller}.index",$datos);
    }

    public function grilla()
    {
        $objeto = Curso::with(["tipo_curso"])->get();
        return  DataTables::of($objeto)
                ->addIndexColumn()
                ->addColumn("descripcion_curso", function($row){
                    return $row->descripcion;
                })
                ->addColumn("flayer_curso", function($row){
                    return ("<center><img  src='".$row->url_flayer."' style='width:35%;' class='img-fluid'></center>");
                })
                ->rawColumns(['descripcion_curso', 'flayer_curso'])
                ->make(true);
    }

    public function uploadFile(Request $request){
        $filePath   = $request->file('file');
        $fileName   = uniqid().".".$filePath->getClientOriginalExtension();
        $path       = $this->path_curso;

        if(!in_array($filePath->getClientOriginalExtension(), ["jpg", "png", "jpeg"]))
            throw ValidationException::withMessages(["file"=>"No subió el formato correcto [jpg, png, jpeg]"]);

        if(!$filePath->move($path, $fileName))
            throw ValidationException::withMessages(["Error al subir el archivo"]);

        return response()->json(["status"=>TRUE, "filename"=>$fileName, "path_file_img"=>url($this->path_curso."/".$fileName)]);
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'codtipo_curso'=>["required"]
            ,'titulo'=>[
                "required"
                ,"max:250"
                , Rule::unique("{$this->model->getTable()}", "titulo")
                ->ignore($request->input("cod{$this->name_table}"), "cod{$this->name_table}")
            ]
            ,'descripcion'=>["required"]
            ,'horas_lectivas'=>["required"]
            ,'fecha_inicio'=>["required"]
            ,'fecha_fin'=>["required"]
        ],[
            "codtipo_curso.required"=>"Obligatorio"
            ,"titulo.required"=>"Obligatorio"
            ,"descripcion.required"=>"Obligatorio"
            ,"horas_lectivas.required"=>"Obligatorio"
            ,"fecha_inicio.required"=>"Obligatorio"
            ,"fecha_fin.required"=>"Obligatorio"
        ]);

        $filePath       = $request->file('file_flayer');
        $ImagenFlayer   = $request->input("imagen_flayer");
        $path           = $this->path_curso;
        if($filePath){
            $ImagenFlayer   = "flayer_".uniqid().".".$filePath->getClientOriginalExtension();
            if(!file_exists($path))
                mkdir($path, 0777, true);

            if(!in_array($filePath->getClientOriginalExtension(), $this->formato_valido))
                throw ValidationException::withMessages(["file_flayer"=>"No subió el formato correcto [".implode(",", $this->formato_valido)."], el formato ".$filePath->getClientOriginalExtension()." no está permitido"]);


            if(!$filePath->move($path, $ImagenFlayer))
                throw ValidationException::withMessages(["Error al subir el archivo, la ruta especificada {$path}, posiblemente no existe"]);
        }

        $filePath       = $request->file('file_plantilla');
        $PlantillaCert  = $request->input("plantilla_certificado");
        $path           = $this->path_curso;
        if($filePath){
            $PlantillaCert   = "plantilla_".uniqid().".".$filePath->getClientOriginalExtension();
            if(!file_exists($path))
                mkdir($path, 0777, true);

            if(!in_array($filePath->getClientOriginalExtension(), $this->formato_valido))
                throw ValidationException::withMessages(["file_plantilla"=>"No subió el formato correcto [".implode(",", $this->formato_valido)."], el formato ".$filePath->getClientOriginalExtension()." no está permitido"]);


            if(!$filePath->move($path, $ImagenFlayer))
                throw ValidationException::withMessages(["Error al subir el archivo, la ruta especificada {$path}, posiblemente no existe"]);
        }

        $obj                = Curso::find($request->input("cod{$this->name_table}"));

        if(is_null($obj)){
            $obj            = new Curso();
        }
        $obj->fill($request->all());
        $obj->slug      = Str::slug($request->input("titulo"));
        $obj->plantilla_certificado = $PlantillaCert;
        $obj->imagen_flayer         = $ImagenFlayer;
        if($obj->save()){
            $arrParticipantes = [];
            if($request->filled("participantes")){
                foreach($request->input("participantes") as $k=>$value){
                    if(!empty($value['codparticipante_curso']))
                        $detalle    = ParticipanteCurso::find($value['codparticipante_curso']);
                    else
                        $detalle    = new ParticipanteCurso();

                    $detalle->codcurso          = $obj->codcurso;
                    $detalle->codparticipante   = $value['codparticipante'];
                    $detalle->pagado            = "N";
                    $detalle->save();

                    $arrParticipantes[]        = $detalle->codparticipante_curso;
                }
            }

            ParticipanteCurso::where("codcurso", $obj->codcurso)->whereNotIn("codparticipante_curso", $arrParticipantes)->delete();
        }
        return response()->json($obj);
    }

    public function edit($id)
    {
        $obj    =   Curso::with(["vicedecano.persona_natural", "director.persona_natural"])
                    ->withParticipantesCurso()
                    ->find($id);
        return response()->json($obj);
    }

    public function destroy($id)
    {
        $obj    =   Curso::findOrFail($id);
        $obj->delete();
        return response()->json();
    }
}
