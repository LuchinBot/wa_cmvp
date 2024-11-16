<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Perfil extends Model
{
    use SoftDeletes;

    protected $table        = "perfil";
    protected $primaryKey   = "codperfil";

    protected $fillable     = ["descripcion", "abreviatura"];

    protected $hidden       = ['created_at', 'updated_at', "deleted_at"];

    public function getColumnDataTable(){
        return [
            ["descripcion"=>"#"             ,"ancho"=>"05%", "jsColumn"=>["data"=>"DT_RowIndex", "orderable"=>false, "searchable"=>false, "className"=>"text-center"]]
           ,["descripcion"=>"Perfil"        ,"ancho"=>"95%", "jsColumn"=>["data"=>"descripcion"]]
       ];
    }
}
