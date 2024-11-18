<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class DetalleVenta extends Model
{
    use SoftDeletes;
    protected $table        = "detalle_venta";
    protected $primaryKey   = "iddetalle_venta";

    protected $fillable     = ["idproducto", "cantidad"];
    protected $hidden       = ['created_at', 'updated_at', "deleted_at"];

    public function producto()
    {
        return $this->belongsTo(Producto::class,'idproducto');
    }
}
