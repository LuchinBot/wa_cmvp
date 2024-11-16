<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Tramite extends Model
{
    use SoftDeletes;

    protected $table        = "tramite";
    protected $primaryKey   = "codtramite";

    protected $fillable     = ["titulo", "slug", "descripcion", "derecho_pago", "icono", "orden"];

    protected $hidden       = ['created_at', 'updated_at', "deleted_at"];

    public function getColumnDataTable(){
        return [
            ["descripcion"=>"#"             ,"ancho"=>"05%", "jsColumn"=>["data"=>"DT_RowIndex", "orderable"=>false, "searchable"=>false, "className"=>"text-center"]]
           ,["descripcion"=>"Titulo"        ,"ancho"=>"25%", "jsColumn"=>["data"=>"titulo"]]
           ,["descripcion"=>"Descripcion"   ,"ancho"=>"25%", "jsColumn"=>["data"=>"descripcion"]]
           ,["descripcion"=>"Pago"          ,"ancho"=>"05%", "jsColumn"=>["data"=>"derecho_pago"]]
           ,["descripcion"=>"Icono"         ,"ancho"=>"05%", "jsColumn"=>["data"=>"fa_icono", "orderable"=>false, "searchable"=>false]]
       ];
    }

    public function requisitos(){
        return $this->hasMany(RequisitoTramite::class,'codtramite');
    }

    public function scopeWithRequisitosTramite($query){
        return $query->with(["requisitos"=>function($q){
            $q->join("requisito", "requisito.codrequisito", "=", "requisito_tramite.codrequisito")
            ->selectRaw("
                requisito_tramite.*,
                requisito.descripcion as requisito
            ");
        }]);
    }
}
