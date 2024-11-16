<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class TipoDocumentoIdentidad extends Model
{
    use SoftDeletes;

    protected $table        = "tipo_documento_identidad";
    protected $primaryKey   = "codtipo_documento_identidad";

    protected $fillable     = ["descripcion", "abreviatura", "longitud", "id_api"];

    protected $hidden       = ['created_at', 'updated_at', "deleted_at"];
}
