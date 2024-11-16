<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class Modulo extends Model{
    use SoftDeletes;

    protected $table        = "modulos";
    protected $primaryKey   = "codmodulos";

    protected $fillable     = ["codsistema"
                                , "codpadre"
                                , "descripcion"
                                , "abreviatura"
                                , "url"
                                , "icono"
                                , "orden"
                                , "acceso_directo"
                                , "deleted_at"
                            ];
    protected $appends      = ['estado'];
    protected $hidden       = ['created_at', 'updated_at', "deleted_at"];

    public function getEstadoAttribute()
    {
        return (is_null($this->deleted_at))?'S':'N';
    }

    //-------------------------------------------- Relaciones
    public function sistema(){
        return $this->belongsTo(Sistema::class,'codsistema');
    }

    public function modulopadre(){
        return $this->belongsTo(Modulo::class,'codpadre');
    }

    public function scopeWithBotones($query){
        return $query->with(['botones'=>function($q){
            $q->Join('boton as b','b.codboton','detalle_boton.codboton')
                ->selectRaw(
                  'detalle_boton.codmodulos,
                   detalle_boton.coddetalle_boton,
                   b.codboton,
                   detalle_boton.coddetalle_boton,
                   detalle_boton.coddetalle_boton,
                   b.descripcion as  boton,
                   b.descripcion as text,
                   b.icono,
                   b.orden,
                   b.clase_name as clase,
                   b.codboton as id,
                   detalle_boton.codmodulos
                   '
                )
                ->orderBy('b.orden');
        }]);
    }

    public function scopeWithAccesoBotones($query,$codperfil=0){
        return $query->with(['botones'=>function($q) use ($codperfil){
            $q->Join('boton as b','b.codboton','detalle_boton.codboton')
             ->leftJoin('acceso_boton',function($join) use($codperfil){
                 $join->on('acceso_boton.codmodulos','=','detalle_boton.codmodulos');
                 $join->on('acceso_boton.codboton','=','detalle_boton.codboton');
                 $join->on('acceso_boton.codperfil','=', DB::raw($codperfil));
             })
              ->selectRaw(
                  "detalle_boton.codmodulos,
                   detalle_boton.coddetalle_boton,
                   b.codboton,
                   b.descripcion as  boton,
                   b.icono,
                   b.orden,
                   b.clase_name as clase_boton,
                   b.id_name as id_boton,
                   COALESCE(acceso_boton.acceder,'0') as estado_acceso
                   "
                )
                ->whereNull('b.deleted_at')
                ->orderBy('b.orden');
        }]);
    }

    public function scopeAccesoRapido($query, $limit=""){
        $codperfil      = auth()->user()->codperfil;

        return $query->with(["AccesoModulo"=>function($q) use ($codperfil){
            $q->selectRaw("*");
            $q->where("accesos.codperfil", $codperfil);
        }])
        ->where("modulos.acceso_directo", "S")
        ->whereNotNull("modulos.url")
        ->when($limit, function($sq, $limit){
            $sq->limit($limit);
        });
    }
}
