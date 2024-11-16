<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Boton extends Model
{
    use SoftDeletes;

    protected $table        = "boton";
    protected $primaryKey   = "codboton";

    protected $fillable     = ["descripcion", "icono", "id_name", "clase_name", "alias", "atajo", "orden", "callback"];
    protected $hidden       = ['created_at', 'updated_at', "deleted_at"];

    public function accesoBotones()
    {
        return $this->hasMany(AccesoBoton::class,'codboton');
    }

    public function scopeBotones($query,$url){
        $codperfil = auth()->user()->codperfil;
        return $query->whereHas('accesoBotones',function($query) use ($url,$codperfil){
            $query->where('acceso_boton.codperfil',$codperfil);
            $query->where('acceso_boton.acceder',1);
            $query->whereHas('modulos',function($q) use ($url){
                $q->where('url',$url);
            })
            ->join("detalle_boton as db", function($query){
                $query->on("db.codmodulos", "=", "acceso_boton.codmodulos");
                $query->on("db.codboton", "=", "acceso_boton.codboton");
                $query->whereNull("db.deleted_at");
            });
        })->orderBy('orden');
    }
}
