<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\Empresa;
use Illuminate\Http\Request;
use SplFileInfo;
use Illuminate\Support\Facades\Response;

class DescargaWS extends Controller
{
    public $objEmpresaGlobal        = null;

    public function __construct()
    {
        $this->objEmpresaGlobal     = Empresa::withUbigeo()->first();
    }

    public function download(Request $request){
        $type_view = $request->filled("disposition") ? $request->input("disposition") : "inline";
        $path = public_path("app/file/{$request->input('dir')}/" . $request->input('file'));

        if (!file_exists($path)) {
            abort(404, 'Archivo no encontrado');
        }

        $filename = basename($path);
        $mimetype = mime_content_type($path);

        return response()->file($path, [
            'Content-Disposition' => "$type_view; filename=\"$filename\"",
            'Content-Type' => $mimetype ?: 'application/octet-stream',
            'Cache-Control' => 'public, must-revalidate, max-age=0',
            'Pragma' => 'public',
        ]);
    }
}
