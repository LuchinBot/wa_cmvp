<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SliderPrincipal extends Model
{
    use SoftDeletes;

    protected $table        = "slider_principal";
    protected $primaryKey   = "codslider_principal";

    protected $fillable     = ["titulo", "subtitulo", "imagen", "orden"];
    protected $appends      = ['url_imagen'];
    protected $hidden       = ['created_at', 'updated_at', "deleted_at"];

    const PATH_FILE         = 'app/img/slider_principal/';
    const DEFAULT_IMG       = 'default.png';

    public function getDefaultImagen()
    {
        return static::DEFAULT_IMG;
    }

    public function getPath()
    {
        return static::PATH_FILE;
    }

    public function getImagen(){
        return (($this->imagen)??$this->getDefaultImagen());
    }

    public function getPathImagen()
    {
        return ($this->getPath());
    }

    public function getUrlImagen(){
        return url($this->getPathImagen().$this->getImagen());
    }

    public function getUrlImagenAttribute()
    {
        return $this->getUrlImagen();
    }

    public function getColumnDataTable(){
        return [
            ["descripcion"=>"#"     ,"ancho"=>"05%", "jsColumn"=>["data"=>"DT_RowIndex", "orderable"=>false, "searchable"=>false, "className"=>"text-center"]]
           ,["descripcion"=>"Titulo","ancho"=>"25%", "jsColumn"=>["data"=>"titulo"]]
           ,["descripcion"=>"Imagen","ancho"=>"25%", "jsColumn"=>["data"=>"imagen"]]
           ,["descripcion"=>"Orden" ,"ancho"=>"05%", "jsColumn"=>["data"=>"orden"]]
       ];
    }
}
