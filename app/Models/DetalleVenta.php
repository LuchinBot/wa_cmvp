<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class DetalleVenta extends Model
{
    protected $table        = "detalle_venta";
    protected $primaryKey   = "iddetalle_venta";

    protected $fillable     = ["idproducto", "cantidad"];
    public function producto()
    {
        return $this->belongsTo(Producto::class,'idproducto');
    }
}
