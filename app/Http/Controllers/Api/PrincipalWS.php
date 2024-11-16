<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ActividadInstitucional;
use App\Models\Empresa;
use App\Models\Comunicado;
use App\Models\Curso;
use App\Models\Evento;
use App\Models\JuntaDirectiva;
use App\Models\PersonaNatural;
use App\Models\Pronunciamiento;
use App\Models\SliderPrincipal;
use App\Models\Tramite;

use App\Mail\ContactenosCorreo;
use App\Models\Ubigeo;
use Exception;
use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PrincipalWS extends Controller
{
    public $title_page                  = "";
    public $api_consulta_doc_nro_doc    = null;
    public $objEmpresaGlobal            = null;

    public function __construct()
    {
        $this->objEmpresaGlobal     = Empresa::withUbigeo()->first();
    }
    //Propiedades Empresa OK
    public function propertyCompany()
    {
        $result["Institucion"]      = [
                                        "razon_social"=>$this->objEmpresaGlobal->razon_social??""
                                        ,"abreviatura"=>$this->objEmpresaGlobal->abreviatura??""
                                        ,"ruc"=>$this->objEmpresaGlobal->ruc??""
                                        ,"telefonos"=>$this->objEmpresaGlobal->telefonos??""
                                        ,"correo"=>$this->objEmpresaGlobal->email??""
                                        ,"logo"=>$this->objEmpresaGlobal->url_logo??""
                                        ,"direccion"=>$this->objEmpresaGlobal->direccion??""
                                        ,"horario_atencion"=>$this->objEmpresaGlobal->horario_atencion??""
                                        ,"ubigeo"=>$this->objEmpresaGlobal->ubigeo->ubigeo_descr??""
                                        ,"objetivo"=>$this->objEmpresaGlobal->objetivo??""
                                        ,"pagina_web"=>$this->objEmpresaGlobal->pagina_web??"www.google.com"
                                        ,"fan_page"=>"https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Fwww.facebook.com%2FCMVDSM&tabs=timeline&width=340&height=500&small_header=false&adapt_container_width=true&hide_cover=false&show_facepile=true&appId=5098249470225307"
                                    ];

        return sendSuccess($result, "OK");
    }

    public function mainWeb(Request $request){
        $SliderPrincipal            = [];
        $objSliderPrincipal         = SliderPrincipal::orderBy("orden")->get();
        foreach($objSliderPrincipal as $value){
            $SliderPrincipal[] = [
                "titulo"=>$value["titulo"]
                ,"subtitulo"=>$value["subtitulo"]
                ,"imagen"=>$value["url_imagen"]
            ];
        }

        $SliderFlotante             = [];
        $Comunicado                 = [];
        $objComunicados             = Comunicado::where("fecha_publicacion_fin", ">=", date("Y-m-d"))->where("fecha_publicacion_inicio", "<=", date("Y-m-d"))->orderBy("fecha")->get();
        foreach($objComunicados as $key=>$value){
            $SliderFlotante[] = [
                "titulo"=>$value["titulo"]
                ,"id"=>$value["slug"]
                ,"subtitulo"=>$value["subtitulo"]
                ,"imagen"=>$value["url_imagen"]
                ,"tipo"=>"COMUNICADO"
            ];

            if($key==0)
                $Comunicado = [
                    "titulo"=>$value["titulo"]
                    ,"id"=>$value["slug"]
                    ,"descripcion"=>$value["descripcion"]
                    ,"imagen"=>$value["url_imagen"]
                    ,"fecha"=>$value["fecha_es"]
                ];
        }

        $Pronunciamiento            = [];
        $objPronunciamiento         = Pronunciamiento::where("fecha_publicacion_fin", ">=", date("Y-m-d"))->where("fecha_publicacion_inicio", "<=", date("Y-m-d"))->orderBy("fecha")->get();
        foreach($objPronunciamiento as $key=>$value){
            $SliderFlotante[] = [
                "titulo"=>$value["titulo"]
                ,"id"=>$value["slug"]
                ,"subtitulo"=>$value["subtitulo"]
                ,"imagen"=>$value["url_imagen"]
                ,"tipo"=>"PRONUNCIAMIENTO"
            ];

            if($key==0)
                $Pronunciamiento = [
                    "titulo"=>$value["titulo"]
                    ,"id"=>$value["slug"]
                    ,"descripcion"=>$value["descripcion"]
                    ,"imagen"=>$value["url_imagen"]
                    ,"fecha"=>$value["fecha_es"]
                ];
        }

        $Tramites                   = [];
        $objTramites                = Tramite::withRequisitosTramite()->orderBy("orden")->get();
        foreach($objTramites as $value){
            $requisitos = [];
            foreach($value["requisitos"] as $requisito){
                $requisitos[] = [
                    "requisito"=>$requisito["requisito"]
                    ,"nota"=>$requisito["nota"]
                    ,"archivo"=>$requisito["url_file"]
                ];
            }
            $titulo     = "";
            $subtitulo  = "";
            $arrTitulo  = explode(" ", $value["titulo"]);
            if(count($arrTitulo)>0){
                $titulo     = $arrTitulo[0];
                $subtitulo  = trim(str_replace($titulo, "", $value["titulo"]));

            }
            $Tramites[] = [
                "titulo"=>$titulo
                ,"subtitulo"=>$subtitulo
                ,"id"=>$value["slug"]
                ,"descripcion"=>$value["descripcion"]
                ,"derecho_pago"=>$value["derecho_pago"]
                ,"icono"=>$value["icono"]??"fa-folder"
                ,"requisitos"=>$requisitos
            ];
        }

        $dato["Titulo"]             = "..::Bienvenido::..";
        $dato["Slider_principal"]   = $SliderPrincipal;
        $dato["Slider_flotante"]    = $SliderFlotante;
        $dato["Tramites"]           = $Tramites;
        $dato["Comunicado"]         = $Comunicado;
        $dato["Pronunciamiento"]    = $Pronunciamiento;
        $dato["fecha_onomastico"]   = date("d")." de ".$this->meses[date("m")];
        $dato["cant_cursos"]        = Curso::count();
        $dato["cant_eventos"]       = Evento::count();

        return sendSuccess($dato, "OK");
    }

    public function process(){
        $Tramites                   = [];
        $objTramites                = Tramite::withRequisitosTramite()->orderBy("orden")->get();
        foreach($objTramites as $value){
            $titulo     = "";
            $subtitulo  = "";
            $arrTitulo  = explode(" ", $value["titulo"]);
            if(count($arrTitulo)>0){
                $titulo     = $arrTitulo[0];
                $subtitulo  = trim(str_replace($titulo, "", $value["titulo"]));
            }
            $Tramites[] = [
                "titulo"=>$titulo
                ,"subtitulo"=>$subtitulo
                ,"id"=>$value["slug"]
                ,"descripcion"=>$value["descripcion"]
                ,"derecho_pago"=>$value["derecho_pago"]
                ,"icono"=>$value["icono"]??"fa-folder"
            ];
        }

        $dato["Tramites"]   = $Tramites;

        return sendSuccess($dato, "OK");
    }

    public function About(){
        $Institucion    = [
                            "razon_social"=>$this->objEmpresaGlobal->razon_social??""
                            ,"abreviatura"=>$this->objEmpresaGlobal->abreviatura??""
                            ,"ruc"=>$this->objEmpresaGlobal->ruc??""
                            ,"telefonos"=>$this->objEmpresaGlobal->telefonos??""
                            ,"correo"=>$this->objEmpresaGlobal->email??""
                            ,"logo"=>$this->objEmpresaGlobal->url_logo??""
                            ,"direccion"=>$this->objEmpresaGlobal->direccion??""
                            ,"horario_atencion"=>$this->objEmpresaGlobal->horario_atencion??""
                            ,"ubigeo"=>$this->objEmpresaGlobal->ubigeo->ubigeo_descr??""
                            ,"objetivo"=>$this->objEmpresaGlobal->objetivo??""
                            ,"imagen_objetivo"=>$this->objEmpresaGlobal->url_imagen_objetivo??""
                            ,"historia"=>$this->objEmpresaGlobal->historia??""
                            ,"descripcion_consejo"=>$this->objEmpresaGlobal->descripcion_consejo??""
                            ,"imagen_consejo"=>$this->objEmpresaGlobal->url_imagen_consejo??""
                            ,"imagen_asamblea"=>$this->objEmpresaGlobal->url_imagen_asamblea??""
                            ,"mision"=>$this->objEmpresaGlobal->mision??""
                            ,"vision"=>$this->objEmpresaGlobal->vision??""
                        ];

        $Representantes     = [];
        $Lista_Decanos      = [];
        $objJuntaDirectiva  = JuntaDirectiva::withIntegrantesJunta()->orderBy("fecha_periodo_inicio", "DESC")->get();
        foreach($objJuntaDirectiva as $k=>$value){
            foreach($value["integrantes_junta"] as $representante){
                if($k==0){
                    $Representantes[] = [
                        "nombres"=>$representante["integrante_nombre"]
                        ,"cargo"=>$representante["cargo_integrante"]
                        ,"numero_colegiatura"=>$representante["numero_colegiatura"]
                        ,"foto"=>url((new PersonaNatural())->getPath().(new PersonaNatural())->getDefaultFoto($representante["nombre_foto"]))
                    ];
                }else{
                    if($representante["id_cargo"]=="DECA"){
                        $Lista_Decanos[] = [
                            "nombres"=>$representante["integrante_nombre"]
                            ,"numero_colegiatura"=>$representante["numero_colegiatura"]
                            ,"periodo"=>Carbon::parse($value["fecha_periodo_fin"])->format("Y")." - ".Carbon::parse($value["fecha_periodo_inicio"])->format("Y")
                            ,"foto"=>url((new PersonaNatural())->getPath().(new PersonaNatural())->getDefaultFoto($representante["nombre_foto"]))
                        ];
                    }
                }
            }
        }

        $dato["Titulo"]             = "..::Nosotros::..";
        $dato["Institucion"]        = $Institucion;
        $dato["Representantes"]     = $Representantes;
        $dato["Anteriores_decanos"] = $Lista_Decanos;

        return sendSuccess($dato, "OK");
    }

    public function Galery(){
        $Galeria = [];
        $objGaleria = ActividadInstitucional::with(["galeria"])->get();
        foreach($objGaleria as $value){
            $array      = [];
            foreach($value->galeria as $val){
                $array[] = [
                    "imagen"=>$val["url_imagen"]
                ];
            }
            $Galeria[] = [
                "titulo"=>$value->titulo
                ,"fecha"=>$value->fecha_es
                ,"imagen"=>$value["url_imagen"]
                ,"galeria"=>$array
            ];
        }
        $dato["Titulo"] = "..::Galeria Institucional::..";
        $dato["Galeria"]     = $Galeria;
        return sendSuccess($dato, "OK");
    }

    public function ContactMail(Request $request){
        $this->validate($request,[
            'remitente'=>'required'
            ,"correo"=>"required|email"
            ,"asunto"=>"required"
            ,"mensaje"=>"required"
        ], [
            "remitente.required"=>"Debe ingresar su nombre completo"
            ,"correo.required"=>"Debe ingresar su correo electronico"
            ,"asunto.required"=>"Debe ingresar el asunto"
            ,"mensaje.required"=>"Obligatorio"
        ]);

        $empresa = Empresa::first();

        $request->merge(["empresa"=>$empresa]);

        try{
            Mail::to($empresa->email, $empresa->abreviatura)->send(new ContactenosCorreo($request->all()));
        }catch(Exception $e){
            return response()->json([
                "type"=>"error"
                ,"status"=>FALSE
                ,"errors"=>[$e->getMessage()]
            ], 424);
        }
        $request->merge(["type"=>"success", "status"=>TRUE, "msg"=>"Correo enviado de manera correcta, lo atenderemos a la brevedad posible."]);
        return response()->json($request->all());
    }

    public function ListProvinces(Request $request){
        $cod_dpto   = $request->filled("cod_dpto")?$request->input("cod_dpto"):22;
        $Provincias = [];

        $objUbigeo  = Ubigeo::where("cod_prov", "<>", "00")
                    ->where("cod_dist", "00")
                    ->where("cod_dpto", $cod_dpto)
                    ->get();
        foreach($objUbigeo as $value){
            $Provincias[] = [
                "id"=>$value["cod_prov"]
                ,"provincia"=>$value["nombre"]
            ];
        }

        $dato["Titulo"]     = "..::Provincias::..";
        $dato["Provincias"] = $Provincias;

        return sendSuccess($dato, "OK");
    }
}
