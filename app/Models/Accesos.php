<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Accesos extends Model
{
    use SoftDeletes;

    protected $table        = "accesos";
    protected $primaryKey   = "codaccesos";

    protected $fillable     = ["codmodulos", "codperfil", "acceder"];
    protected $hidden       = ['created_at', 'updated_at', "deleted_at"];
}
