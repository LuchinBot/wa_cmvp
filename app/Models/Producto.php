<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table        = "producto";
    protected $primaryKey   = "idproducto";

    protected $fillable     = ["descripcion", "precio","precio","stock" ];
}
