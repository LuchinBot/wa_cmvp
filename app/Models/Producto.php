<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use SoftDeletes;
    protected $table        = "producto";
    protected $primaryKey   = "idproducto";

    protected $fillable     = ["descripcion", "precio","stock" ];
    protected $hidden       = ['created_at', 'updated_at', "deleted_at"];

}
