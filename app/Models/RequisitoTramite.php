<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class RequisitoTramite extends Model
{
    use SoftDeletes;

    protected $table        = "requisito_tramite";
    protected $primaryKey   = "codrequisito_tramite";

    protected $fillable     = ["codtramite", "codrequisito", "nota", "archivo", "orden"];
    protected $appends      = ['url_file'];
    protected $hidden       = ['created_at', 'updated_at', "deleted_at"];

    const PATH_FILE         = 'app/file/requisito_tramite/';
    const DEFAULT_FILE      = 'not_found.pdf';

    public function getDefaultFile()
    {
        return static::DEFAULT_FILE;
    }

    public function getPath()
    {
        return static::PATH_FILE;
    }

    public function getFile(){

        return (($this->archivo)??$this->getDefaultFile());
    }

    public function getPathFile()
    {
        return $this->getPath();
    }

    public function getUrlFile(){
        if($this->archivo)
            return url($this->getPath().$this->getFile());
        else
            return null;
    }

    public function getUrlFileAttribute()
    {
        return $this->getUrlFile();
    }
}
