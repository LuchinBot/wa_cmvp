<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Caja extends Model
{
    use SoftDeletes;

    protected $table        = "caja";
    protected $primaryKey   = "codcaja";

    protected $fillable     = ["codusuario_apertura", "codusuario_cierre", "idtipo_caja", "fecha_apertura","fecha_cierra","monto_apertura","monto_cierre","estado"];
    protected $hidden       = ['created_at', 'updated_at', "deleted_at"];

    public function usuario_apertura()
    {
        return $this->belongsTo(User::class,'codusuario_apertura' , 'codusuario');
    }
    public function usuario_cierre()
    {
        return $this->belongsTo(User::class,'codusuario_cierre','codusuario');
    }

    public function tipo_caja()
    {
        return $this->belongsTo(TipoCaja::class,'idtipo_caja');
    }
    public function getColumnDataTable(){
        return [
            ["descripcion"=>"#"         ,"ancho"=>"05%", "jsColumn"=>["data"=>"DT_RowIndex", "orderable"=>false, "searchable"=>false, "className"=>"text-center"]]
           ,["descripcion"=>"Tipo"     ,"ancho"=>"10%%", "jsColumn"=>["data"=>"tipo_caja.nombre"]]
           ,["descripcion"=>"Usuario"     ,"ancho"=>"10%", "jsColumn"=>["data"=>"usuario_apertura.usuario"]]
           ,["descripcion"=>"F. Apertura"     ,"ancho"=>"20%", "jsColumn"=>["data"=>"fecha_apertura"]]
           ,["descripcion"=>"M. Apertura"     ,"ancho"=>"20%", "jsColumn"=>["data"=>"monto_apertura"]]
           ,["descripcion"=>"F. Cierre"     ,"ancho"=>"20%", "jsColumn"=>["data"=>"fecha_cierre"]]
           ,["descripcion"=>"M. Cierre"     ,"ancho"=>"20%", "jsColumn"=>["data"=>"monto_cierre"]]
           ,["descripcion"=>"Estado"       ,"ancho"=>"15%", "jsColumn"=>["data"=>"estado", "orderable"=>false, "searchable"=>false]]
       ];
    }
}
