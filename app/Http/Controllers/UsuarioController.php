<?php

namespace App\Http\Controllers;


use App\Models\Empleado;
use App\Models\Empresa;
use App\Models\User;
use App\Models\AsignacionesBien;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

use App\Actions\LegajoFormat_LCS;

class UsuarioController extends Controller
{
    public $modulo                  = "Usuario";
    public $model                   = null;
    public $name_table              = null;

    public $path_controller         = "usuarios";
    public $path_photo              = "";
    public $rename_file             = true;
    public $default_photo           = "anonimo.png";

    public $size_php_ini            = 0;//Parametro "upload_max_filesize" puesto en php.ini (En Mb) /*Maximum allowed size for uploaded files.*/
    public $dataTableServer         = null;

    public function __construct(){
        $this->model                = new User();
        $this->name_table           = $this->model->getTable();
        $this->path_photo           = $this->model->getPathAvatar();
    }

    public function perfil(){
        $datos["modulo"]            = "{$this->modulo}/Perfil";
        $datos["table_name"]        = (new User())->getTable();
        $datos["pathController"]    = $this->path_controller;
        $datos["usuario"]           = auth()->user();
        $datos["prefix"]            = "";
        $datos["path_photo"]        = $this->path_photo;
        $datos["default_photo"]     = $this->default_photo;
        $datos["version"]           = rand();

        return view("seguridad.{$this->path_controller}.perfil_usuario", $datos);
    }

    public function actualizar_perfil(Request $request){
        $this->validate($request,[
            'codusuario' => 'required'
            ,'password_nuevo'   => [
                "required_unless:cambiar_clave, S"
                ,'max:50'
                ,Password::min(6)
                ->mixedCase()
                ->letters()
                ->numbers()
            ]
        ]);

        return DB::transaction(function() use ($request){
            $fileName   = $request->input("avatar");
            $filePath   = $request->file('file');

            $obj                = User::find($request->input('codusuario'));
            if($request->input("cambiar_clave")=="S"){
                if(!$request->filled("password_nuevo")){
                    throw ValidationException::withMessages(["Ingrese su nueva clave"]);
                }
                if(!$request->filled("password_re_nuevo")){
                    throw ValidationException::withMessages(["Repita su nueva clave"]);
                }
                if($request->input("password_nuevo") != $request->input("password_re_nuevo")){
                    throw ValidationException::withMessages(["Las claves no coinciden"]);
                }
            }else{
                if($request->filled("password_nuevo")){
                    throw ValidationException::withMessages(["No debe ingresar nueva clave"]);
                }
                if($request->filled("password_re_nuevo")){
                    throw ValidationException::withMessages(["No debe repetir nueva clave"]);
                }
            }

            if($filePath){
                $extension  = $filePath->getClientOriginalExtension();
                $size       = $filePath->getSize();
                $fileName   = uniqid().".".$extension;

                if($size<1)
                    throw ValidationException::withMessages(["El avatar del usuario, excede el tamaño permitido ({$this->size_php_ini}), por favor comprima el archivo o comunicarse con SOPORTE. (Seccion I)"]);

                Storage::disk('ftp_server')->put($this->path_photo.$fileName, fopen($request->file('file'), 'r+'));
            }

            if($request->input("cambiar_clave")=="S"){
                $obj->password      = Hash::make($request->input("password_nuevo"));
            }
            $obj->avatar    = $fileName;
            $obj->save();
            return response()->json($obj);
        });
    }
}
