<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class ActividadInstitucional extends Model
{
    use SoftDeletes;

    protected $table        = "actividad_institucional";
    protected $primaryKey   = "codactividad_institucional";

    protected $fillable     = ["titulo", "slug", "fecha", "imagen_principal"];
    protected $appends      = ['url_imagen', 'fecha_es'];
    protected $hidden       = ['created_at', 'updated_at', "deleted_at"];

    public function getDefaultImagen()
    {
        return (new GaleriaActividadInstitucional())->getDefaultImagen();
    }

    public function getPath()
    {
        return (new GaleriaActividadInstitucional())->getPath();
    }

    public function getImagen(){

        return (($this->imagen_principal)??$this->getDefaultImagen());
    }

    public function getUrlImgen(){
        return url($this->getPath().$this->getImagen());
    }

    public function getUrlImagenAttribute()
    {
        return $this->getUrlImgen();
    }

    public function getFechaEsAttribute()
    {
        if(is_null($this->fecha))
            return null;
        return Carbon::parse($this->fecha)->format("d/m/Y");
    }

    public function getColumnDataTable(){
        return [
            ["descripcion"=>"#"         ,"ancho"=>"05%", "jsColumn"=>["data"=>"DT_RowIndex", "orderable"=>false, "searchable"=>false, "className"=>"text-center"]]
           ,["descripcion"=>"Actividad" ,"ancho"=>"70%", "jsColumn"=>["data"=>"titulo"]]
           ,["descripcion"=>"Fecha"     ,"ancho"=>"10%", "jsColumn"=>["data"=>"fecha_es"]]
           ,["descripcion"=>"Imagen"    ,"ancho"=>"15%", "jsColumn"=>["data"=>"imagen"]]
       ];
    }

    public function galeria(){
        return $this->hasMany(GaleriaActividadInstitucional::class,'codactividad_institucional');
    }
}
