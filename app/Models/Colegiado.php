<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Colegiado extends Model
{
    use SoftDeletes;

    protected $table        = "colegiado";
    protected $primaryKey   = "codcolegiado";

    protected $fillable     = ["codpersona_natural", "numero_colegiatura", "fecha_colegiatura", "curriculum_vitae", "estado_colegiado"];
    protected $appends      = ['fecha_colegiatura_es', 'url_cv'];
    protected $hidden       = ['created_at', 'updated_at', "deleted_at"];
    const PATH_FILE         = 'app/file/cv/';
    const DEFAULT_CV        = 'default.pdf';

    public function getDefaultFoto(){
        return (new PersonaNatural())->getDefaultFoto();
    }

    public function getDefaultCV()
    {
        return static::DEFAULT_CV;
    }

    public function getPath()
    {
        return static::PATH_FILE;
    }

    /*
    public function getPathFoto()
    {
        return (new PersonaNatural())->getPath();
    }

    public function getFoto(){

        return (new PersonaNatural())->getFoto();
    }
    */

    public function getCV(){

        return (($this->curriculum_vitae)??$this->getDefaultCV());
    }

    public function getUrlCV(){
        return url($this->getPath().$this->getCV());
    }

    public function getUrlCvAttribute()
    {
        return $this->getUrlCV();
    }

    public function getFechaColegiaturaEsAttribute()
    {
        return (!is_null($this->fecha_colegiatura))?Carbon::parse($this->fecha_colegiatura)->format("d/m/Y"):"";
    }

    public function persona_natural()
    {
        return $this->belongsTo(PersonaNatural::class,'codpersona_natural');
    }

    public function especialidad_colegiado()
    {
        return $this->hasMany(ColegiadoEspecialidad::class,'codcolegiado');
    }

    public function getColumnDataTable(){
        return [
            ["descripcion"=>"#"             ,"ancho"=>"05%", "jsColumn"=>["data"=>"DT_RowIndex", "orderable"=>false, "searchable"=>false, "className"=>"text-center"]]
            ,["descripcion"=>"N° CMV"       ,"ancho"=>"15%", "jsColumn"=>["data"=>"numero_colegiatura"]]
            ,["descripcion"=>"Colegiado"    ,"ancho"=>"35%", "jsColumn"=>["data"=>"persona_natural.nombre_completo"]]
            ,["descripcion"=>"DNI"          ,"ancho"=>"15%", "jsColumn"=>["data"=>"persona_natural.numero_documento_identidad"]]
            ,["descripcion"=>"F. Colegiado" ,"ancho"=>"15%", "jsColumn"=>["data"=>"fecha_colegiatura"]]
            ,["descripcion"=>"Estado"       ,"ancho"=>"10%", "jsColumn"=>["data"=>"estado_colegiado", "orderable"=>false, "searchable"=>false]]
            ,["descripcion"=>"C.V"          ,"ancho"=>"05%", "jsColumn"=>["data"=>"c_v", "orderable"=>false, "searchable"=>false]]
            ,["descripcion"=>"Foto"         ,"ancho"=>"05%", "jsColumn"=>["data"=>"foto_colegiado", "orderable"=>false, "searchable"=>false]]
       ];
    }

    public function scopeWithEspecialidadColegiado($query){
        return $query->with(["especialidad_colegiado"=>function($q){
            $q->join("especialidad", "especialidad.codespecialidad", "=", "colegiado_especialidad.codespecialidad")
            ->selectRaw("
                colegiado_especialidad.*,
                especialidad.descripcion as especialidad_colegiado
            ");
        }]);
    }
}
