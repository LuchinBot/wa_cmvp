<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EstadoCivil extends Model
{
    use SoftDeletes;

    protected $table        = "estado_civil";
    protected $primaryKey   = "codestado_civil";

    protected $fillable     = ["descripcion", "abreviatura", "identificador"];

    protected $hidden       = ['created_at', 'updated_at', "deleted_at"];

    public function getColumnDataTable(){
        return [
            ["descripcion"=>"#"         ,"ancho"=>"05%", "jsColumn"=>["data"=>"DT_RowIndex", "orderable"=>false, "searchable"=>false, "className"=>"text-center"]]
           ,["descripcion"=>"Estado civil"     ,"ancho"=>"75%", "jsColumn"=>["data"=>"descripcion"]]
           ,["descripcion"=>"Abreviatura"     ,"ancho"=>"10%", "jsColumn"=>["data"=>"abreviatura"]]
           ,["descripcion"=>"Identificador"     ,"ancho"=>"10%", "jsColumn"=>["data"=>"identificador"]]
       ];
    }
}
