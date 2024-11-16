<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Noticia extends Model
{
    use SoftDeletes;

    protected $table        = "noticia";
    protected $primaryKey   = "codnoticia";

    protected $fillable     = [
                                "titulo"
                                , "slug"
                                , "fecha"
                                , "descripcion"
                                , "imagen"
                            ];
    protected $appends      = ['url_imagen', 'fecha_es'];
    protected $hidden       = ['created_at', 'updated_at', "deleted_at"];
    const PATH_FILE         = 'app/img/noticia/';
    const DEFAULT_PHOTO     = 'default.png';


    public function getDefaultImagen()
    {
        return static::DEFAULT_PHOTO;
    }

    public function getPath()
    {
        return static::PATH_FILE;
    }

    public function getImagen(){

        return (($this->imagen)??$this->getDefaultImagen());
    }

    public function getUrlImagen(){
        return url($this->getPath().$this->getImagen());
    }

    public function getUrlImagenAttribute()
    {
        return $this->getUrlImagen();
    }

    public function getFechaEsAttribute(){
        return Carbon::parse($this->fecha)->format("d/m/Y");
    }

    public function getColumnDataTable(){
        return [
            ["descripcion"=>"#"             ,"ancho"=>"05%", "jsColumn"=>["data"=>"DT_RowIndex", "orderable"=>false, "searchable"=>false, "className"=>"text-center"]]
           ,["descripcion"=>"Titulo"        ,"ancho"=>"30%", "jsColumn"=>["data"=>"titulo"]]
           ,["descripcion"=>"Fecha"         ,"ancho"=>"10%", "jsColumn"=>["data"=>"fecha_es"]]
           ,["descripcion"=>"Descripcion"   ,"ancho"=>"20%", "jsColumn"=>["data"=>"descripcion_noticia"]]
           ,["descripcion"=>"Imagen"        ,"ancho"=>"10%", "jsColumn"=>["data"=>"imagen_noticia"]]
       ];
    }
}
