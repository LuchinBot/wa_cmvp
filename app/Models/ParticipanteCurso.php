<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class ParticipanteCurso extends Model
{
    use SoftDeletes;

    protected $table        = "participante_curso";
    protected $primaryKey   = "codparticipante_curso";

    protected $fillable     = ["codcurso", "codparticipante", "pagado"];

    protected $hidden       = ['created_at', 'updated_at', "deleted_at"];
}
