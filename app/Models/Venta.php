<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $table        = "venta";
    protected $primaryKey   = "idventa";

    protected $fillable     = ["idcaja", "total","fecha" ];
    public function caja()
    {
        return $this->belongsTo(Caja::class,'idcaja');
    }
    public function getColumnDataTable(){
        return [
            ["descripcion"=>"#"         ,"ancho"=>"05%", "jsColumn"=>["data"=>"DT_RowIndex", "orderable"=>false, "searchable"=>false, "className"=>"text-center"]]
           ,["descripcion"=>"Fecha venta"     ,"ancho"=>"20%%", "jsColumn"=>["data"=>"fecha"]]
           ,["descripcion"=>"Monto total"     ,"ancho"=>"40%", "jsColumn"=>["data"=>"total"]]
       ];
    }
}
