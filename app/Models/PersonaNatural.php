<?php

namespace App\Models;

use Carbon\Carbon;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PersonaNatural extends Model
{
    use SoftDeletes;

    protected $table        = "persona_natural";
    protected $primaryKey   = "codpersona_natural";

    protected $fillable     = ["codtipo_documento_identidad"
                                , "codsexo"
                                , "codestado_civil"
                                , "id_ubigeo"
                                , "cod_dpto"
                                , "cod_prov"
                                , "cod_dist"
                                , "nombres"
                                , "apellido_paterno"
                                , "apellido_materno"
                                , "numero_documento_identidad"
                                , "fecha_emision_documento_identidad"
                                , "codigo_verificacion_documento_identidad"
                                , "direccion"
                                , "telefono_movil"
                                , "email"
                                , "fecha_nacimiento"
                                , "foto"
                            ];

    protected $hidden       = ['created_at', 'updated_at', "deleted_at"];

    protected $appends      = ['url_foto', "hb_today", "nombre_completo", "fecha_nacimiento_es"];
    const PATH_FILE         = 'app/img/persona_natural/';
    const DEFAULT_PHOTO     = 'anonimo.png';

    public function getTableName(){
        return $this->table;
    }

    public function getDefaultFoto($nombre_foto="")
    {
        return (!empty($nombre_foto))?$nombre_foto:static::DEFAULT_PHOTO;
    }

    public function getPath()
    {
        return static::PATH_FILE;
    }

    public function getFoto(){
        if(!empty($this->foto))
            return $this->foto;
        return $this->getDefaultFoto();
    }

    public function getPathFoto()
    {
        return ($this->getPath());
    }

    public function getUrlFoto(){
        return url($this->getPathFoto().$this->getFoto());
    }

    public function getNombreCompletoAttribute()
    {
        return $this->apellido_paterno." ".$this->apellido_materno.", ".$this->nombres;
    }

    public function getUrlFotoAttribute()
    {
        return $this->getUrlFoto();
    }

    public function getFechaNacimientoEsAttribute()
    {
        if(is_null($this->fecha_nacimiento))
            return null;
        return Carbon::parse($this->fecha_nacimiento)->format("d/m/Y");
    }

    public function getHbTodayAttribute()
    {
        if(is_null($this->fecha_nacimiento))
            return false;
        return (Carbon::parse($this->fecha_nacimiento)->format("m-d")==date("m-d"));
    }

    public function getColumnDataTable(){
        return [
            ["descripcion"=>"#"             ,"ancho"=>"05%", "jsColumn"=>["data"=>"DT_RowIndex", "orderable"=>false, "searchable"=>false, "className"=>"text-center"]]
            ,["descripcion"=>"Nombres"      ,"ancho"=>"20%", "jsColumn"=>["data"=>"nombres"]]
            ,["descripcion"=>"Ap. Paterno"  ,"ancho"=>"15%", "jsColumn"=>["data"=>"apellido_paterno"]]
            ,["descripcion"=>"Ap. Materno"  ,"ancho"=>"15%", "jsColumn"=>["data"=>"apellido_materno"]]
            ,["descripcion"=>"DNI"          ,"ancho"=>"08%", "jsColumn"=>["data"=>"numero_documento_identidad"]]
            ,["descripcion"=>"F. Nac"       ,"ancho"=>"08%", "jsColumn"=>["data"=>"fecha_nacimiento_es", "name"=>"fecha_nacimiento", "orderable"=>false, "searchable"=>false]]
            ,["descripcion"=>"Tel. Movil"   ,"ancho"=>"12%", "jsColumn"=>["data"=>"telefono_movil"]]
            ,["descripcion"=>"Foto"         ,"ancho"=>"05%", "jsColumn"=>["data"=>"foto_persona", "orderable"=>false, "searchable"=>false, "className"=>"text-center"]]
        ];
    }

    public function getColumnDataTablePopUp(){
        return [
            ["descripcion"=>"#"             ,"ancho"=>"05%", "jsColumn"=>["data"=>"DT_RowIndex", "orderable"=>false, "searchable"=>false, "className"=>"text-center"]]
            ,["descripcion"=>"Nombres"      ,"ancho"=>"20%", "jsColumn"=>["data"=>"nombres"]]
            ,["descripcion"=>"Ap. Paterno"  ,"ancho"=>"15%", "jsColumn"=>["data"=>"apellido_paterno"]]
            ,["descripcion"=>"Ap. Materno"  ,"ancho"=>"15%", "jsColumn"=>["data"=>"apellido_materno"]]
            ,["descripcion"=>"DNI"          ,"ancho"=>"15%", "jsColumn"=>["data"=>"numero_documento_identidad"]]
        ];
    }

    public function estado_civil(){
        return $this->belongsTo(EstadoCivil::class,'codestado_civil');
    }

    public function sexo(){
        return $this->belongsTo(Sexo::class,'codsexo');
    }

    public function tipo_documento_identidad(){
        return $this->belongsTo(TipoDocumentoIdentidad::class,'codtipo_documento_identidad');
    }

    public function ubigeo()
    {
        return $this->belongsTo(Ubigeo::class,'id_ubigeo');
    }

    public function scopeWithUbigeo($query){
        return $query->with(["ubigeo"=>function($q){
            $q->leftJoin("ubigeo as provincia", function($sq){
                $sq->on("provincia.cod_dpto", "=", "ubigeo.cod_dpto");
                $sq->on("provincia.cod_prov", "=", "ubigeo.cod_prov");
                $sq->on("provincia.cod_dist", "=", DB::raw("'00'"));
            })
            ->leftJoin("ubigeo as departamento", function($sq){
                $sq->on("departamento.cod_dpto", "=", "ubigeo.cod_dpto");
                $sq->on("departamento.cod_prov", "=", DB::raw("'00'"));
                $sq->on("departamento.cod_dist", "=", DB::raw("'00'"));
            })
            ->selectRaw("
                ubigeo.nombre as distrito
                ,provincia.nombre as provincia
                ,departamento.nombre as departamento
                ,CONCAT(departamento.nombre,' - ',provincia.nombre,' - ',ubigeo.nombre) as ubigeo_descr
                ,ubigeo.id_ubigeo
            ")
            ->whereNull("ubigeo.deleted_at");
        }]);
    }
}
