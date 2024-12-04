<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Cuota extends Model
{
    use SoftDeletes;

    protected $table        = "cuota";
    protected $primaryKey   = "codcuota";

    protected $fillable     = ["codcolegiado", "mes", "anio","monto"];
    protected $hidden       = ['created_at', 'updated_at', "deleted_at"];

    public function colegiado()
    {
        return $this->belongsTo(Colegiado::class,'codcolegiado' , 'codcolegiado');
    }

    public function getColumnDataTable(){
        return [
            ["descripcion"=>"#"             ,"ancho"=>"05%", "jsColumn"=>["data"=>"DT_RowIndex", "orderable"=>false, "searchable"=>false, "className"=>"text-center"]]
           ,["descripcion"=>"Colegiado"    ,"ancho"=>"10%", "jsColumn"=>["data"=>"colegiado.numero_colegiatura"]]
           ,["descripcion"=>"Cuota pagada"         ,"ancho"=>"30%", "jsColumn"=>["data"=>"mes"]]
       ];
    }
}
