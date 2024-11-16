<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class TipoCurso extends Model
{
    use SoftDeletes;

    protected $table        = "tipo_curso";
    protected $primaryKey   = "codtipo_curso";

    protected $fillable     = ["descripcion", "abreviatura"];

    protected $hidden       = ['created_at', 'updated_at', "deleted_at"];

    public function getColumnDataTable(){
        return [
            ["descripcion"=>"#"                 ,"ancho"=>"05%", "jsColumn"=>["data"=>"DT_RowIndex", "orderable"=>false, "searchable"=>false, "className"=>"text-center"]]
           ,["descripcion"=>"Tipo Curso"        ,"ancho"=>"40%", "jsColumn"=>["data"=>"descripcion"]]
           ,["descripcion"=>"Abreviatura"       ,"ancho"=>"20%", "jsColumn"=>["data"=>"abreviatura"]]
       ];
    }
}
