<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use SoftDeletes;
    protected $table = "venta";
    protected $primaryKey = "idventa";
    protected $fillable = ["codcaja", "total", "fecha"];
    protected $hidden       = ['created_at', 'updated_at', "deleted_at"];


    public function caja()
    {
        return $this->belongsTo(Caja::class, 'codcaja', 'codcaja');
    }

    public function tipo_caja()
    {
        return $this->belongsTo(TipoCaja::class, 'codtipo_caja', 'codtipo_caja');
    }

    public function getColumnDataTable()
    {
        return [
            ["descripcion" => "#", "ancho" => "5%", "jsColumn" => ["data" => "DT_RowIndex", "orderable" => false, "searchable" => false, "className" => "text-center"]],
            ["descripcion" => "Caja", "ancho" => "20%", "jsColumn" => ["data" => "tipo_caja"]],
            ["descripcion" => "Fecha venta", "ancho" => "20%", "jsColumn" => ["data" => "fecha"]],
            ["descripcion" => "Monto total", "ancho" => "40%", "jsColumn" => ["data" => "total"]],
        ];
    }
}