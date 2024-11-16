<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\Empresa;
use App\Models\DocumentoNormativo;
use SplFileInfo;

class DocumentosNormativosWS extends Controller
{
    public $objEmpresaGlobal        = null;

    public function __construct()
    {
        $this->objEmpresaGlobal     = Empresa::withUbigeo()->first();
    }

    public function listDocuments(){
        $DocumentosNormativos       = [];
        $objDocumentos              = DocumentoNormativo::orderBy("orden")->get();
        foreach($objDocumentos as $value){
            $file_local = null;
                if(!is_null($value["archivo"])){
                    $file       = public_path((new DocumentoNormativo())->getPath().$value["archivo"]);
                    $fileInfo   = new SplFileInfo((string) $file);

                    if(!is_null($fileInfo))
                        $file_local = "{$value["titulo"]}.".$fileInfo->getextension();
                }

            $DocumentosNormativos[] = [
                "titulo"=>$value["titulo"]
                ,"archivo"=>$value["archivo"]
                ,"file"=>$file_local
            ];
        }

        $dato["Titulo"]                 = "..::Documentos Normativos::..";
        $dato["Documentos_normativos"]  = $DocumentosNormativos;

        return sendSuccess($dato, "OK");
    }
}
