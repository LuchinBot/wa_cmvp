<?php

namespace App\Http\Controllers;

use App\Models\Ubigeo;
use Illuminate\Http\Request;

class UbigeoController
{
    public function departamentos()
    {
        $departamentos=Ubigeo::selectApi()->departamentos()
            ->get();
        return response()->json($departamentos);
    }

    public function provincia(Request $request)
    {
        $provincias=Ubigeo::selectApi()->provincias($request->input('cod_dpto'))
            ->get();
        return response()->json($provincias);
    }

    public function distrito(Request $request)
    {
        $distritos=Ubigeo::selectApi()
            ->distritos(
                $request->input('cod_dpto'),
                $request->input('cod_prov')
            )
            ->get();
        return response()->json($distritos);
    }
}
