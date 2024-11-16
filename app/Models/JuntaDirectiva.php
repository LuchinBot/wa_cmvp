<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class JuntaDirectiva extends Model
{
    use SoftDeletes;

    protected $table        = "junta_directiva";
    protected $primaryKey   = "codjunta_directiva";

    protected $fillable     = ["descripcion", "fecha_periodo_inicio", "fecha_periodo_fin"];
    protected $appends      = ['fecha_periodo_inicio_es', 'fecha_periodo_fin_es'];
    protected $hidden       = ['created_at', 'updated_at', "deleted_at"];

    public function getFechaPeriodoInicioEsAttribute(){
        return Carbon::parse($this->fecha_periodo_inicio)->format("d/m/Y");
    }

    public function getFechaPeriodoFinEsAttribute(){
        return Carbon::parse($this->fecha_periodo_fin)->format("d/m/Y");
    }

    public function getColumnDataTable(){
        return [
            ["descripcion"=>"#"             ,"ancho"=>"05%", "jsColumn"=>["data"=>"DT_RowIndex", "orderable"=>false, "searchable"=>false, "className"=>"text-center"]]
           ,["descripcion"=>"Inicio Periodo","ancho"=>"15%", "jsColumn"=>["data"=>"fecha_periodo_inicio_es"]]
           ,["descripcion"=>"Fin Periodo"   ,"ancho"=>"15%", "jsColumn"=>["data"=>"fecha_periodo_fin_es"]]
           ,["descripcion"=>"Integrantes"   ,"ancho"=>"45%", "jsColumn"=>["data"=>"integrantes_junta", "orderable"=>false, "searchable"=>false]]
       ];
    }

    public function integrantes_junta(){
        return $this->hasMany(IntegranteJuntaDirectiva::class,'codjunta_directiva');
    }

    public function scopeWithIntegrantesJunta($query){
        return $query->with(["integrantes_junta"=>function($q){
            $q->join("colegiado", "colegiado.codcolegiado", "=", "integrante_junta_directiva.codcolegiado")
            ->join("persona_natural", "persona_natural.codpersona_natural", "=", "colegiado.codpersona_natural")
            ->join("cargo", "cargo.codcargo", "=", "integrante_junta_directiva.codcargo")
            ->selectRaw("
                integrante_junta_directiva.*
                ,CONCAT(persona_natural.apellido_paterno, ' ', persona_natural.apellido_materno,', ', persona_natural.nombres) as integrante
                ,CONCAT(persona_natural.nombres, ' ',persona_natural.apellido_paterno, ' ', persona_natural.apellido_materno) as integrante_nombre
                ,persona_natural.numero_documento_identidad as dni_integrante
                ,cargo.descripcion as cargo_integrante
                ,colegiado.numero_colegiatura
                ,persona_natural.foto as nombre_foto
                ,cargo.identificador as id_cargo
            ");
        }]);
    }
}
