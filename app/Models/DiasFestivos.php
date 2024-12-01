<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class DiasFestivos extends Model
{
    use SoftDeletes;

    protected $table        = "dias_festivos";
    protected $primaryKey   = "coddias_festivos";

    protected $fillable     = ["titulo", "descripcion", "fecha","imagen"];

    protected $appends      = ['url_flayer'];
    protected $hidden       = ['created_at', 'updated_at', "deleted_at"];
    const PATH_FILE         = 'app/img/dias_festivos/';
    const DEFAULT_PHOTO     = 'default.jpeg';

    public function getDefaultFlayer()
    {
        return static::DEFAULT_PHOTO;
    }
    public function getPath()
    {
        return static::PATH_FILE;
    }

    public function getFlayer()
    {

        return (($this->imagen) ?? $this->getDefaultFlayer());
    }
    public function getPathDiasFestivos()
    {
        return $this->getPath();
    }

    public function getUrlFlayer()
    {
        return url($this->getPathDiasFestivos() . $this->getFlayer());
    }
    public function getUrlFlayerAttribute()
    {
        return $this->getUrlFlayer();
    }

    public function getColumnDataTable()
    {
        return [
            ["descripcion" => "#", "ancho" => "05%", "jsColumn" => ["data" => "DT_RowIndex", "orderable" => false, "searchable" => false, "className" => "text-center"]],
            ["descripcion" => "Titulo", "ancho" => "40%", "jsColumn" => ["data" => "titulo"]],
            ["descripcion" => "Fecha", "ancho" => "20%", "jsColumn" => ["data" => "fecha"]]
        ];
    }
}
