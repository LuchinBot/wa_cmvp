<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ubigeo extends Model
{
    protected $table        = "ubigeo";
    protected $primaryKey   = "id_ubigeo";
    public $timestamps      = false;

    protected $fillable     = ["cod_dpto"
                                , "cod_prov"
                                , "cod_dist"
                                , "codccpp"
                                , "nombre"
                                , "reniec"];
    public function getTableName(){
        return $this->table;
    }

    public function scopeSelectApi($query)
    {
        return $query->select([
            'id_ubigeo',
            'cod_dpto',
            'cod_prov',
            'cod_dist',
            'reniec',
            'nombre'
        ])->orderBy('nombre');
    }

    public function scopeDepartamentos($query)
    {
        return $query->where('cod_prov','00')
            ->where('cod_dist','00')->where('codccpp','0000');
    }

    public function scopeProvincias($query,$codDpto=null)
    {
        return $query->where('cod_prov','<>','00')
            ->where('cod_dist','00')->where('codccpp','0000')
            ->when($codDpto,function($q,$codDpto){
                return $q->where('cod_dpto',$codDpto);
            });
    }

    public function scopeDistritos($query,$codDpto=null,$codProv=null)
    {
        return $query->where('cod_dist','<>','00')
            ->where('codccpp','0000')
            ->when($codDpto,function($q,$codDpto){
                return $q->where('cod_dpto',$codDpto);
            })->when($codProv,function($q,$codProv){
                return $q->where('cod_prov',$codProv);
            });

    }
}
