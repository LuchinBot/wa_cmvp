<?php

namespace App\Actions;

use App\Models\EstadoCivil;
use App\Models\Param;
use App\Models\PersonaNatural;
use App\Models\Sexo;
use App\Models\TipoDocumentoIdentidad;
use App\Models\Ubigeo;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ConsultaPersonaNatural
{
    public $api_key_consulta_dni    = "";
    public $url_consulta_dni        = "https://consulta-dni.luasmart.pe/api/consulta";

    public function ConsultaExternaLCS(Request $request){
        if(!$request->filled("numero_documento_identidad"))
            return ["status"=>false, "message"=>"Debe ingresar el [numero_documento_identidad]", "data"=>[], "code"=>422];
        if(!$request->filled("tipo_documento_identidad"))
            return ["status"=>false, "message"=>"Debe ingresar el [tipo_documento_identidad]", "data"=>[], "code"=>422];

        $ObjTipoDocId   = TipoDocumentoIdentidad::where("id_api", $request->input("tipo_documento_identidad"))->first();
        if(strlen($request->input("numero_documento_identidad"))!=$ObjTipoDocId->longitud)
            return ["status"=>false, "message"=>"El documento de identidad ingresado, debe tener {$ObjTipoDocId->longitud} digitos", "code"=>422];
        if(empty($this->api_key_consulta_dni))
            $this->api_key_consulta_dni = Param::getParam("token_api_consulta_ruc_dni");
        if(empty($this->api_key_consulta_dni))
            return ["status"=>false, "message"=>"No se encontró el token para consultas DNI/RUC", "code"=>422];

        if(empty($ObjTipoDocId->id_api))
            return ["status"=>false, "message"=>"No se encontró el valor [id_api], del tipo de documento de identidad {$ObjTipoDocId->descripcion}", "code"=>422];
        $response   = Http::withHeaders([
                        "Content-Type"=> "application/json"
                        ,"Accept"=>"application/json"
                    ])
                    ->withToken($this->api_key_consulta_dni)
                    ->withOptions(["verify"=>false]) //(Esta linea se pone para evitar el error) cURL error 60: SSL certificate problem: certificate has expired (see https://curl.haxx.se/libcurl/c/libcurl-errors.html)
                    ->get("{$this->url_consulta_dni}/{$ObjTipoDocId->id_api}/{$request->input("numero_documento_identidad")}");
        $objResponse = $response->json();

        if(is_null($objResponse))
            return ["status"=>false, "data"=>[], "message"=>$objResponse["message"]??'', "code"=>500];
        if(isset($objResponse["success"]) && !$objResponse["success"])
            return ["status"=>false, "data"=>[], "message"=>$objResponse["message"]??'--', "code"=>422];
        if(!isset($objResponse["data"]))
            return ["status"=>false, "data"=>[], "message"=>$objResponse["message"]??'-', "code"=>500];

        $dataResponse = $objResponse["data"];

        if(empty($dataResponse))
            return ["status"=>false, "data"=>[], "message"=>$objResponse["msg"]??'No se obtuvo datos de la persona', "code"=>422];

        $ubi_descr  = null;
        $cod_dpto   = null;
        $cod_prov   = null;
        $cod_dist   = null;
        if(!is_null($dataResponse["id_ubigeo"])){
            $objUbigeo = Ubigeo::selectRaw("
                            CONCAT(departamento.nombre,' - ',provincia.nombre,' - ',distrito.nombre) as ubigeo_descr
                            ,ubigeo.cod_dist
                            ,ubigeo.cod_dpto
                            ,ubigeo.cod_prov
                        ")
                        ->where("ubigeo.id_ubigeo", $dataResponse["id_ubigeo"])
                        ->leftJoin("ubigeo as distrito", "distrito.id_ubigeo", "=", "ubigeo.id_ubigeo")
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
                        ->first();
            if(!is_null($objUbigeo)){
                $ubi_descr  = $objUbigeo->ubigeo_descr;
                $cod_dpto   = $objUbigeo->cod_dpto;
                $cod_prov   = $objUbigeo->cod_prov;
                $cod_dist   = $objUbigeo->cod_dist;
            }
        }

        $foto       = null;
        $fotoPah    = null;

        if(isset($dataResponse["fotoBase64"]) && !is_null($dataResponse["fotoBase64"])){
            $foto = uniqid().".png";
            $pathPersonaNatural = public_path((new PersonaNatural())->getPathFoto());
            if(!file_exists($pathPersonaNatural))
                mkdir($pathPersonaNatural, 7777);
            $imagenBinaria = base64_decode($dataResponse["fotoBase64"]);
            if(file_put_contents($pathPersonaNatural."/".$foto, $imagenBinaria)){
                $fotoPah    = url((new PersonaNatural)->getPathFoto().$foto);
            }
        }
        /**/

        $codsexo = null;
        if(!is_null($dataResponse["sexo"])){
            $objSexo = Sexo::where("descripcion", $dataResponse["sexo"])->first();
            if(!is_null($objSexo)){
                $codsexo = $objSexo->codsexo;
            }
        }

        $codestado_civil = null;
        if(!is_null($dataResponse["estado_civil"])){
            $objEstadoCivil = EstadoCivil::where("descripcion", $dataResponse["estado_civil"])->first();
            if(!is_null($objEstadoCivil)){
                $codestado_civil = $objEstadoCivil->codestado_civil;
            }
        }

        $data   = [
            "nombres"=>(trim($dataResponse["nombres"])??"")
            ,"apellido_paterno"=>(trim($dataResponse["apellido_paterno"])??"")
            ,"apellido_materno"=>(trim($dataResponse["apellido_materno"])??"")
            ,"nombre_completo"=>(trim($dataResponse["nombre_completo"])??"")
            ,"tipo_documento_identidad"=>$ObjTipoDocId->id_api
            ,"numero_documento_identidad"=>$dataResponse["numero_documento_identidad"]
            ,"fecha_nacimiento"=>$dataResponse["fecha_nacimiento"]
            ,"fecha_emision_documento_identidad"=>$dataResponse["fecha_emision_documento_identidad"]
            ,"direccion"=>(trim($dataResponse["direccion"])??null)
            ,"telefono"=>(trim($dataResponse["telefono_movil"])??null)
            ,"codsexo"=>$codsexo
            ,"codestado_civil"=>$codestado_civil
            ,"codigo_verificacion_documento_identidad"=>(trim($dataResponse["codigo_verificacion_documento_identidad"])??null)
            ,"id_ubigeo"=>$dataResponse["id_ubigeo"]
            ,"ubigeo_descr"=>$ubi_descr
            ,"cod_dpto"=>$cod_dpto
            ,"cod_prov"=>$cod_prov
            ,"cod_dist"=>$cod_dist
            ,"foto"=>$foto
            ,"fotoPath"=>$fotoPah
        ];

        return ["status"=>(empty($data))?false:true, "data"=>$data, "message"=>"",  "code"=>200];
    }

    public function GuardarPersona(Request $request){
        return DB::transaction(function() use ($request){
            $tipo_doc   = TipoDocumentoIdentidad::find($request->input("codtipo_documento_identidad"));
            if(is_null($tipo_doc))
                throw ValidationException::withMessages(["Tipo de documento de identidad invalido"]);

            if($tipo_doc->longitud<>strlen($request->input("numero_documento_identidad")))
                throw ValidationException::withMessages(["El valor del numero de documento de identidad ingresado no es valido para el {$tipo_doc->descripcion}"]);

            if($request->filled("telefono_movil")){
                if(strlen($request->input("telefono_movil"))!=9){
                    throw ValidationException::withMessages(["El telefono móvil debe tener 9 digitos"]);
                }
            }

            if($request->filled("codpersona_natural"))
                $obj    = PersonaNatural::find($request->input("codpersona_natural"));
            else{
                $obj    = PersonaNatural::where("numero_documento_identidad", $request->input("numero_documento_identidad"))->first();
            }

            if(is_null($obj)){
                $obj    = new PersonaNatural();
            }
            $obj->fill($request->all());
            if($obj->save())
                return ["status"=>true, "data"=>[], "message"=>"",  "code"=>200];
        });
    }
}
