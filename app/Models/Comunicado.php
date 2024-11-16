<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Comunicado extends Model
{
    use SoftDeletes;

    protected $table        = "comunicado";
    protected $primaryKey   = "codcomunicado";

    protected $fillable     = ["titulo", "slug", "descripcion", "imagen", "imagen_flayer", "fecha", "fecha_publicacion_inicio", "fecha_publicacion_fin"];
    protected $appends      = ["url_imagen", "url_imagen_flayer", "fecha_es", "fecha_publicacion_inicio_es", "fecha_publicacion_fin_es"];
    protected $hidden       = ['created_at', 'updated_at', "deleted_at"];

    const PATH_FILE         = 'app/img/comunicado/';
    const DEFAULT_PHOTO     = 'default.png';
    const DEFAULT_FLAYER    = 'flayer_default.png';

    public function getDefaultImagen()
    {
        return static::DEFAULT_PHOTO;
    }

    public function getDefaultFlayer()
    {
        return static::DEFAULT_FLAYER;
    }

    public function getPath()
    {
        return static::PATH_FILE;
    }

    public function getImagen(){

        return (($this->imagen)??$this->getDefaultImagen());
    }

    public function getImagenFlayer(){
        return (($this->imagen_flayer)??$this->getDefaultFlayer());
    }

    public function getPathImagen()
    {
        return $this->getPath();
    }

    public function getUrlImagen(){
        return url($this->getPathImagen().$this->getImagen());
    }

    public function getUrlImagenFlayer(){
        return url($this->getPathImagen().$this->getImagenFlayer());
    }

    public function getUrlImagenAttribute()
    {
        return $this->getUrlImagen();
    }

    public function getUrlImagenFlayerAttribute()
    {
        return $this->getUrlImagenFlayer();
    }

    public function getFechaEsAttribute()
    {
        return Carbon::parse($this->fecha)->format("d/m/Y");
    }

    public function getFechaPublicacionInicioEsAttribute()
    {
        return Carbon::parse($this->fecha_publicacion_inicio)->format("d/m/Y");
    }

    public function getFechaPublicacionFinEsAttribute()
    {
        return Carbon::parse($this->fecha_publicacion_fin)->format("d/m/Y");
    }

    public function getColumnDataTable(){
        return [
            ["descripcion"=>"#"                 ,"ancho"=>"05%", "jsColumn"=>["data"=>"DT_RowIndex", "orderable"=>false, "searchable"=>false, "className"=>"text-center"]]
           ,["descripcion"=>"Titulo"            ,"ancho"=>"30%", "jsColumn"=>["data"=>"titulo"]]
           ,["descripcion"=>"Inicio Publicacion","ancho"=>"13%", "jsColumn"=>["data"=>"fecha_publicacion_inicio_es"]]
           ,["descripcion"=>"Fin Publicacion"   ,"ancho"=>"13%", "jsColumn"=>["data"=>"fecha_publicacion_fin_es"]]
           ,["descripcion"=>"Flayer"            ,"ancho"=>"12%", "jsColumn"=>["data"=>"flayer_comunicado", "orderable"=>false, "searchable"=>false, "className"=>"text-center"]]
       ];
    }
}
