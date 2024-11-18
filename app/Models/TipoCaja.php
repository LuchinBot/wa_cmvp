<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class TipoCaja extends Model
{
    use SoftDeletes;

    protected $table        = "tipo_caja";
    protected $primaryKey   = "codtipo_caja";

    protected $fillable     = ["descripcion"];
    protected $hidden       = ['created_at', 'updated_at', "deleted_at"];

}
