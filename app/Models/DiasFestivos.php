<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class DiasFestivos extends Model
{
    use SoftDeletes;

    protected $table        = "dias_festivos";
    protected $primaryKey   = "coddias_festivos";

    protected $fillable     = ["titulo","descripcion","fecha"];

    protected $hidden       = ['created_at', 'updated_at', "deleted_at"];

    public function getColumnDataTable(){
        return [
            ["descripcion"=>"#"                 ,"ancho"=>"05%", "jsColumn"=>["data"=>"DT_RowIndex", "orderable"=>false, "searchable"=>false, "className"=>"text-center"]]
           ,["descripcion"=>"Titulo"        ,"ancho"=>"40%", "jsColumn"=>["data"=>"titulo"]]
           ,["descripcion"=>"Fecha"       ,"ancho"=>"20%", "jsColumn"=>["data"=>"fecha"]]
       ];
    }
}
