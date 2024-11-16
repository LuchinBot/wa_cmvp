<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Especialidad extends Model
{
    use SoftDeletes;

    protected $table        = "especialidad";
    protected $primaryKey   = "codespecialidad";

    protected $fillable     = ["identificador", "descripcion", "abreviatura", "nota"];

    protected $hidden = ['created_at', 'updated_at', "deleted_at"];

    public function getColumnDataTable(){
        return [
            ["descripcion"=>"#"             ,"ancho"=>"05%", "jsColumn"=>["data"=>"DT_RowIndex", "orderable"=>false, "searchable"=>false, "className"=>"text-center"]]
           ,["descripcion"=>"Especialidad"  ,"ancho"=>"30%", "jsColumn"=>["data"=>"descripcion"]]
           ,["descripcion"=>"Nota"          ,"ancho"=>"65%", "jsColumn"=>["data"=>"nota"]]
       ];
    }
}
