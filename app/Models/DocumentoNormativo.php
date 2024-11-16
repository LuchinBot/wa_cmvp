<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class DocumentoNormativo extends Model
{
    use SoftDeletes;

    protected $table        = "documento_normativo";
    protected $primaryKey   = "coddocumento_normativo";

    protected $fillable     = ["titulo", "archivo", "orden"];
    protected $appends      = ['url_archivo'];
    protected $hidden       = ['created_at', 'updated_at', "deleted_at"];

    const PATH_FILE         = 'app/file/documento_normativo/';
    const DEFAULT_DOC       = 'default.pdf';

    public function getDefaultArchivo()
    {
        return static::DEFAULT_DOC;
    }

    public function getPath()
    {
        return static::PATH_FILE;
    }

    public function getArchivo(){

        return (($this->archivo)??$this->getDefaultArchivo());
    }

    public function getUrlArchivo(){
        return url($this->getPath().$this->getArchivo());
    }

    public function getUrlArchivoAttribute()
    {
        return $this->getUrlArchivo();
    }

    public function getColumnDataTable(){
        return [
            ["descripcion"=>"#"         ,"ancho"=>"05%", "jsColumn"=>["data"=>"DT_RowIndex", "orderable"=>false, "searchable"=>false, "className"=>"text-center"]]
           ,["descripcion"=>"Titulo"    ,"ancho"=>"95%", "jsColumn"=>["data"=>"titulo"]]
           ,["descripcion"=>"Archivo"   ,"ancho"=>"05%", "jsColumn"=>["data"=>"archivo_normativo", "orderable"=>false, "searchable"=>false]]
           ,["descripcion"=>"Orden"     ,"ancho"=>"05%", "jsColumn"=>["data"=>"orden"]]
       ];
    }
}
