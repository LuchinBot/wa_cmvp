<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Empresa extends Model
{
    use SoftDeletes;

    protected $table        = "empresa";
    protected $primaryKey   = "codempresa";

    protected $fillable     = [
                                 "id_ubigeo"
                                ,"razon_social"
                                , "abreviatura"
                                , "ruc"
                                , "telefonos"
                                , "email"
                                , "direccion"
                                , "logo"
                                , "longitud"
                                , "latitud"
                                , "horario_atencion"
                                , "token"
                                , "pagina_web"
                                , "objetivo"
                                , "imagen_objetivo"
                                , "historia"
                                , "mision"
                                , "vision"
                                , "descripcion_consejo"
                                , "imagen_consejo"
                                , "imagen_asamblea"
                            ];

    protected $appends      = ['url_logo', 'url_imagen_objetivo', 'url_imagen_consejo', 'url_imagen_asamblea'];
    protected $hidden       = ['created_at', 'updated_at', "deleted_at"];

    const PATH_FILE         = 'app/img/empresa/';
    const DEFAULT_PHOTO     = 'default.png';
    const DEFAULT_OBJ       = 'objetivo_default.png';
    const DEFAULT_CONSEJO   = 'consejo_default.png';
    const DEFAULT_ASAMBLEA  = 'asamblea_default.png';

    public function getDefaultLogo()
    {
        return static::DEFAULT_PHOTO;
    }

    public function getDefaultObj()
    {
        return static::DEFAULT_OBJ;
    }

    public function getDefaultConsejo()
    {
        return static::DEFAULT_CONSEJO;
    }

    public function getDefaultAsamblea()
    {
        return static::DEFAULT_ASAMBLEA;
    }

    public function getPath()
    {
        return static::PATH_FILE;
    }

    public function getLogo(){

        return (($this->logo)??$this->getDefaultLogo());
    }

    public function getImagenObjetivo(){
        return (($this->imagen_objetivo)??$this->getDefaultObj());
    }

    public function getImagenConsejo(){
        return (($this->imagen_consejo)??$this->getDefaultConsejo());
    }

    public function getImagenAsamblea(){
        return (($this->imagen_asamblea)??$this->getDefaultAsamblea());
    }

    public function getPathLogo()
    {
        return $this->getPath();
    }

    public function getUrlLogo(){
        return url($this->getPathLogo().$this->getLogo());
    }

    public function getUrlImagenObjetivo(){
        return url($this->getPathLogo().$this->getImagenObjetivo());
    }

    public function getUrlImagenConsejo(){
        return url($this->getPathLogo().$this->getImagenConsejo());
    }

    public function getUrlImagenAsamblea(){
        return url($this->getPathLogo().$this->getImagenAsamblea());
    }

    public function getUrlLogoAttribute()
    {
        return $this->getUrlLogo();
    }

    public function getUrlImagenObjetivoAttribute()
    {
        return $this->getUrlImagenObjetivo();
    }

    public function getUrlImagenConsejoAttribute()
    {
        return $this->getUrlImagenConsejo();
    }

    public function getUrlImagenAsambleaAttribute()
    {
        return $this->getUrlImagenAsamblea();
    }

    public function getColumnDataTable(){
        return [
            ["descripcion"=>"#"             ,"ancho"=>"05%", "jsColumn"=>["data"=>"DT_RowIndex", "orderable"=>false, "searchable"=>false, "className"=>"text-center"]]
           ,["descripcion"=>"Razon Social"  ,"ancho"=>"25%", "jsColumn"=>["data"=>"razon_social"]]
           ,["descripcion"=>"RUC"           ,"ancho"=>"10%", "jsColumn"=>["data"=>"ruc"]]
           ,["descripcion"=>"Dirección"     ,"ancho"=>"30%", "jsColumn"=>["data"=>"direccion"]]
           ,["descripcion"=>"Logo"          ,"ancho"=>"10%", "jsColumn"=>["data"=>"logo_colegio", "orderable"=>false, "searchable"=>false, "className"=>"text-center"]]
       ];
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
