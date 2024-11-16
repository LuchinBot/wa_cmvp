<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Param extends Model{
    protected $table        = "param";
    protected $primaryKey   = "codparam";
    protected $keyType      = "string";
    public $timestamps      = true;

    protected $fillable     = ['codparam','valor', 'descripcion'];
    protected $hidden       = ['created_at', 'updated_at', "deleted_at"];

    public function getTableName(){
        return (explode(".", $this->table))[1];
    }

    public function getSchemaName(){
        return (explode(".", $this->table))[0]??"public";
    }

    public function getColumnDataTable(){
        return [
            ["descripcion"=>"#"             ,"ancho"=>"10%", "jsColumn"=>["data"=>"DT_RowIndex", "orderable"=>false, "searchable"=>false, "className"=>"text-center"]]
            ,["descripcion"=>"Key"          ,"ancho"=>"20%", "jsColumn"=>["data"=>"codparam"]]
            ,["descripcion"=>"Valor"        ,"ancho"=>"35%", "jsColumn"=>["data"=>"valor"]]
            ,["descripcion"=>"Descripcion"  ,"ancho"=>"35%", "jsColumn"=>["data"=>"descripcion"]]
        ];
    }

    static function getParam($key="", $default=""){
        if(empty($key))
            return $default;

        $obj = Param::where("codparam", $key)->first();
        if(isset($obj->valor) && !empty($obj->valor))
            return $obj->valor;

        return $default;
    }
}
