<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class GaleriaActividadInstitucional extends Model
{
    use SoftDeletes;

    protected $table        = "galeria_actividad_institucional";
    protected $primaryKey   = "codgaleria_actividad_institucional";

    protected $fillable     = ["codactividad_institucional", "imagen", "orden"];
    protected $appends      = ['url_imagen'];
    protected $hidden       = ['created_at', 'updated_at', "deleted_at"];
    const PATH_FILE         = 'app/img/galeria_actividades/';
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

    public function getUrlImgen(){
        return url($this->getPath().$this->getImagen());
    }

    public function getUrlImagenAttribute()
    {
        return $this->getUrlImgen();
    }
}
