<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ColegiadoEspecialidad extends Model
{
    use SoftDeletes;

    protected $table        = "colegiado_especialidad";
    protected $primaryKey   = "codcolegiado_especialidad";

    protected $fillable     = ["codcolegiado", "codespecialidad"];

    protected $hidden = ['created_at', 'updated_at', "deleted_at"];
}
