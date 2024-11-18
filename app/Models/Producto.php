<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use SoftDeletes;
    protected $table        = "producto";
    protected $primaryKey   = "codproducto";

    protected $fillable     = ["descripcion", "tipo_producto", "precio","stock","controla_stock" ];
    protected $hidden       = ['created_at', 'updated_at', "deleted_at"];
    public function getColumnDataTable(){
        return [
            ["descripcion"=>"#"             ,"ancho"=>"05%", "jsColumn"=>["data"=>"DT_RowIndex", "orderable"=>false, "searchable"=>false, "className"=>"text-center"]]
           ,["descripcion"=>"Descripcion"        ,"ancho"=>"95%", "jsColumn"=>["data"=>"descripcion"]]
       ];
    }
}
