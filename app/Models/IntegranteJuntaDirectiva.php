<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class IntegranteJuntaDirectiva extends Model
{
    use SoftDeletes;

    protected $table        = "integrante_junta_directiva";
    protected $primaryKey   = "codintegrante_junta_directiva";

    protected $fillable     = ["codjunta_directiva", "codcargo", "codcolegiado"];

    protected $hidden       = ['created_at', 'updated_at', "deleted_at"];

    public function cargo()
    {
        return $this->belongsTo(Cargo::class,'codcargo');
    }

    public function colegiado()
    {
        return $this->belongsTo(Cargo::class,'codcolegiado');
    }
}
