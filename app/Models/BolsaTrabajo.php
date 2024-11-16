<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class BolsaTrabajo extends Model
{
    use SoftDeletes;

    protected $table        = "bolsa_trabajo";
    protected $primaryKey   = "codbolsa_trabajo";

    protected $fillable     = ["nombre_institucion", 'ruc_institucion', "fecha_inicio", "fecha_fin", "url_referencial"];
    protected $appends      = ['fecha_inicio_es', 'fecha_fin_es'];
    protected $hidden       = ['created_at', 'updated_at', "deleted_at"];

    public function getFechaInicioEsAttribute()
    {
        return (!is_null($this->fecha_inicio))?Carbon::parse($this->fecha_inicio)->format("d/m/Y"):"";
    }

    public function getFechaFinEsAttribute()
    {
        return (!is_null($this->fecha_fin))?Carbon::parse($this->fecha_fin)->format("d/m/Y"):"";
    }

    public function getColumnDataTable(){
        return [
            ["descripcion"=>"#"             ,"ancho"=>"05%", "jsColumn"=>["data"=>"DT_RowIndex", "orderable"=>false, "searchable"=>false, "className"=>"text-center"]]
           ,["descripcion"=>"Institucion"   ,"ancho"=>"30%", "jsColumn"=>["data"=>"nombre_institucion"]]
           ,["descripcion"=>"Inicio"        ,"ancho"=>"10%", "jsColumn"=>["data"=>"fecha_inicio_es"]]
           ,["descripcion"=>"Fin"           ,"ancho"=>"10%", "jsColumn"=>["data"=>"fecha_fin_es"]]
           ,["descripcion"=>"Página web"    ,"ancho"=>"35%", "jsColumn"=>["data"=>"url_referencial"]]
       ];
    }
}
