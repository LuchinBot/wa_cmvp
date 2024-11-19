<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use SoftDeletes;
    protected $table        = "producto";
    protected $primaryKey   = "codproducto";

    protected $fillable     = ["descripcion", "nota", "precio","stock","controla_stock" ];
    protected $hidden       = ['created_at', 'updated_at', "deleted_at"];
    public function getColumnDataTable(){
        return [
            ["descripcion"=>"#"             ,"ancho"=>"05%", "jsColumn"=>["data"=>"DT_RowIndex", "orderable"=>false, "searchable"=>false, "className"=>"text-center"]]
           ,["descripcion"=>"Producto"        ,"ancho"=>"40%", "jsColumn"=>["data"=>"descripcion"]]
           ,["descripcion"=>"Nota"        ,"ancho"=>"40%", "jsColumn"=>["data"=>"nota"]]
           ,["descripcion"=>"Precio"        ,"ancho"=>"10%", "jsColumn"=>["data"=>"precio","className"=>"text-right"]]
           ,["descripcion"=>"¿Stock?"       ,"ancho"=>"05%", "jsColumn"=>["data"=>"controla_stock", "className"=>"text-center"]]
       ];
    }
}
