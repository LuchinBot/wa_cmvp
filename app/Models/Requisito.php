<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Requisito extends Model
{
    use SoftDeletes;

    protected $table        = "requisito";
    protected $primaryKey   = "codrequisito";

    protected $fillable     = ["descripcion", "abreviatura"];
    protected $hidden       = ['created_at', 'updated_at', "deleted_at"];

    public function getColumnDataTable(){
        return [
            ["descripcion"=>"#"         ,"ancho"=>"05%", "jsColumn"=>["data"=>"DT_RowIndex", "orderable"=>false, "searchable"=>false, "className"=>"text-center"]]
           ,["descripcion"=>"Requisito" ,"ancho"=>"75%", "jsColumn"=>["data"=>"descripcion"]]
           ,["descripcion"=>"Abreviatura" ,"ancho"=>"30%", "jsColumn"=>["data"=>"abreviatura"]]
       ];
    }
}
