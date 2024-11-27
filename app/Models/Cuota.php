<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Cuota extends Model
{
    use SoftDeletes;

    protected $table        = "cuota";
    protected $primaryKey   = "codcuota";

    protected $fillable     = ["codcolegiado", "mes", "anio"];
    protected $hidden       = ['created_at', 'updated_at', "deleted_at"];

    public function colegiado()
    {
        return $this->belongsTo(Colegiado::class,'codcolegiado' , 'codcolegiado');
    }
}
