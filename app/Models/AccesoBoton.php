<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class AccesoBoton extends Model
{
    use SoftDeletes;

    protected $table        = "acceso_boton";
    protected $primaryKey   = "codacceso_boton";

    protected $fillable     = ["codmodulos", "codperfil", "codboton", "acceder"];
    protected $hidden       = ['created_at', 'updated_at', "deleted_at"];

    public function modulos()
    {
        return $this->belongsTo(Modulo::class,'codmodulos');
    }
}
