<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sexo extends Model
{
    use SoftDeletes;

    protected $table        = "sexo";
    protected $primaryKey   = "codsexo";

    protected $fillable     = ["descripcion", "simbolo"];

    protected $hidden = ['created_at', 'updated_at', "deleted_at"];

    public function getColumnDataTable(){
        return [
            ["descripcion"=>"#"         ,"ancho"=>"05%", "jsColumn"=>["data"=>"DT_RowIndex", "orderable"=>false, "searchable"=>false, "className"=>"text-center"]]
           ,["descripcion"=>"Sexo"     ,"ancho"=>"95%", "jsColumn"=>["data"=>"descripcion"]]
       ];
    }
}
