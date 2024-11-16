<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Caja extends Model
{
    use SoftDeletes;

    protected $table        = "caja";
    protected $primaryKey   = "codcaja";

    protected $fillable     = ["codusuario", "idtipo_caja", "fecha_apertura","fecha_cierra","monto_apertura","monto_cierre","estado"];
    protected $hidden       = ['created_at', 'updated_at', "deleted_at"];

    public function usuario()
    {
        return $this->belongsTo(User::class,'codusuario');
    }
    public function tipo_caja()
    {
        return $this->belongsTo(TipoCaja::class,'idtipo_caja');
    }
    public function getColumnDataTable(){
        return [
            ["descripcion"=>"#"         ,"ancho"=>"05%", "jsColumn"=>["data"=>"DT_RowIndex", "orderable"=>false, "searchable"=>false, "className"=>"text-center"]]
           ,["descripcion"=>"Tipo de Caja"     ,"ancho"=>"20%%", "jsColumn"=>["data"=>"tipo_caja.nombre"]]
           ,["descripcion"=>"Usuario"     ,"ancho"=>"40%", "jsColumn"=>["data"=>"usuario.usuario"]]
           ,["descripcion"=>"Fecha Apertura"     ,"ancho"=>"35%", "jsColumn"=>["data"=>"fecha_apertura"]]
           ,["descripcion"=>"Estado"       ,"ancho"=>"10%", "jsColumn"=>["data"=>"estado", "orderable"=>false, "searchable"=>false]]
       ];
    }
}
