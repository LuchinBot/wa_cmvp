<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sistema extends Model
{
    protected $table        = "sistema";
    protected $primaryKey   = "codsistema";
    public $timestamps      = true;

    protected $fillable     = ["identificador", "descripcion", "abreviatura", "url", "contenido", "icono", "orden"];

    public function modulos()
    {
        return $this->hasMany('App\Models\Modulo','codsistema');
    }

    public function scopeAccesoModulos($query, $acceso_directo='')
    {
        $codperfil = auth()->user()->codperfil;

        return $query->with(['modulos'=>function($q) use ($codperfil, $acceso_directo){
            $q->join('accesos','accesos.codmodulos','=','modulos.codmodulos');
            $q->where('accesos.codperfil',$codperfil)
            ->where('accesos.acceder',1);
            if(!is_null($acceso_directo))
                $q->where("modulos.acceso_directo", $acceso_directo);
            $q->orderBy("modulos.orden");
        }]);
    }

    public function getModulos($modulos, $codpadre = null, $acceso_directo=null)
    {
        $menus=[];
        foreach($modulos as $item){
            if($codpadre==$item->codpadre){
                $sub_menu               = $this->getModulos($modulos, $item->codmodulos);
                $value                  = [];
                $value['text']          = $item->descripcion;
                $value['system']  = $item->abreviatura;
                $value['abbreviation']  = $item->abreviatura;
                $value['icon']          = "fas fa-fw ".($item->icono?"{$item->icono}":"fa-adjust");
                $value['url']           = $item->url;
                //$value['padre']         = $item->codpadre;
                if(count($sub_menu)>0)
                    $value['submenu']       = $this->getModulos($modulos, $item->codmodulos);

                $menus[]        = $value;
            }

        }
        return $menus;
    }

    public static function menu($acceso_directo=null)
    {
        $sistemas=static::accesoModulos($acceso_directo)->selectRaw('codsistema,descripcion, abreviatura,icono')
        ->where("identificador", config('app.appSystem'))
        ->orderBy('orden')
        ->get();

        $menus=[];
        foreach($sistemas as $item){
            if($item->modulos->count()){
                $menus = $item->getModulos($item->modulos, null);
            }
        }

        return $menus;
    }

    public function scopeMenuRapido($query){
        $sistemas=static::accesoModulos("S")
        ->selectRaw('codsistema,descripcion, abreviatura,icono')
        ->where("identificador", config('app.appSystem'))
        ->orderBy('orden')
        ->get();

        $menu = [];
        foreach($sistemas as $item){
            if($item->modulos->count()){
                foreach($item->modulos as $val){
                    $value                  = [];
                    $value['text']          = $val->descripcion;
                    //$value['abbreviation']  = $val->abreviatura;
                    //$value['acceso_rapido'] = ($val->acceso_directo=="S");
                    $value['icon']          = "fas fa-fw ".($val->icono?"{$val->icono}":"fa-adjust");
                    $value['url']           = $val->url;

                    $menu[] = $value;
                }
            }
        }

        return ($menu);
    }
}
