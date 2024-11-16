<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    use SoftDeletes;

    protected $table        = "curso";
    protected $primaryKey   = "codcurso";

    protected $fillable     = [
                                "codtipo_curso"
                                , "titulo"
                                , "slug"
                                , "fecha_inicio"
                                , "fecha_fin"
                                , "descripcion"
                                , "horas_lectivas"
                                , "imagen_flayer"
                                , "con_certificado"
                                , "plantilla_certificado"
                                , "codcolegiado_vicedecano"
                                , "codcolegiado_director"
                            ];
    protected $appends      = ['url_flayer', 'url_plantilla_certificado'];
    protected $hidden       = ['created_at', 'updated_at', "deleted_at"];
    const PATH_FILE         = 'app/img/curso/';
    const DEFAULT_PHOTO     = 'default.jpeg';
    const DEFAULT_CERT      = 'certificado_default.jpeg';

    public function getDefaultFlayer()
    {
        return static::DEFAULT_PHOTO;
    }

    public function getDefaultPlantillaCertificado()
    {
        return static::DEFAULT_CERT;
    }

    public function getPath()
    {
        return static::PATH_FILE;
    }

    public function getFlayer(){

        return (($this->imagen_flayer)??$this->getDefaultFlayer());
    }

    public function getPlantillaCertificado(){
        return (($this->plantilla_certificado)??$this->getDefaultPlantillaCertificado());
    }

    public function getPathCurso()
    {
        return $this->getPath();
    }

    public function getUrlFlayer(){
        return url($this->getPathCurso().$this->getFlayer());
    }

    public function getUrlPlantillaCertificado(){
        return url($this->getPathCurso().$this->getPlantillaCertificado());
    }

    public function getUrlFlayerAttribute()
    {
        return $this->getUrlFlayer();
    }

    public function getUrlPlantillaCertificadoAttribute()
    {
        return $this->getUrlPlantillaCertificado();
    }

    public function tipo_curso()
    {
        return $this->belongsTo(TipoCurso::class,'codtipo_curso');
    }

    public function vicedecano()
    {
        return $this->belongsTo(Colegiado::class,'codcolegiado_vicedecano');
    }
    public function director()
    {
        return $this->belongsTo(Colegiado::class,'codcolegiado_director');
    }

    public function participantes(){
        return $this->hasMany(ParticipanteCurso::class,'codcurso');
    }

    public function scopeWithParticipantesCurso($query){
        return $query->with(["participantes"=>function($q){
            $q->join("persona_natural", "persona_natural.codpersona_natural", "=", "participante_curso.codparticipante")
            ->selectRaw("
                participante_curso.*,
                persona_natural.numero_documento_identidad as dni_participante,
                CONCAT(persona_natural.apellido_paterno, ' ',persona_natural.apellido_materno,', ', persona_natural.nombres) as nombres_participante
            ");
        }]);
    }

    public function getColumnDataTable(){
        return [
            ["descripcion"=>"#"             ,"ancho"=>"05%", "jsColumn"=>["data"=>"DT_RowIndex", "orderable"=>false, "searchable"=>false, "className"=>"text-center"]]
           ,["descripcion"=>"Tipo Curso"    ,"ancho"=>"10%", "jsColumn"=>["data"=>"tipo_curso.descripcion"]]
           ,["descripcion"=>"Curso"         ,"ancho"=>"30%", "jsColumn"=>["data"=>"titulo"]]
           ,["descripcion"=>"Descripcion"   ,"ancho"=>"20%", "jsColumn"=>["data"=>"descripcion_curso"]]
           ,["descripcion"=>"Flayer"        ,"ancho"=>"10%", "jsColumn"=>["data"=>"flayer_curso"]]
       ];
    }
}
