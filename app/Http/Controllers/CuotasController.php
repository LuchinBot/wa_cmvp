<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cuota;
use App\Models\Colegiado;

use App\Actions\CuotasColegiado;

use Yajra\DataTables\DataTables;
use App\Actions\DataTableInternal;

class CuotasController extends Controller
{
    public $modulo                  = "Cuotas";
    public $path_controller         = "cuotas";
    public $model                   = null;
    public $name_table              = "";
    public $dataTableServer         = null;

    public function __construct()
    {
        $this->model                = new Cuota();
        $this->name_table           = $this->model->getTable();
        $this->dataTableServer          = new DataTableInternal($this->path_controller . ".grilla");
        $this->dataTableServer->setModel($this->model);
        $this->dataTableServer->setColumns($this->model->getColumnDataTable());
    }

    public function index()
    {
        $datos["table_name"]        = $this->name_table;
        $datos["pathController"]    = $this->path_controller;
        $datos["prefix"]            = "";
        $datos["version"]           = rand();
        $datos["modulo"]            = $this->modulo;
        $datos["botones"]           = ["new" => 1, "edit" => 1];

        $datos["tabla_grid"]        = $this->dataTableServer->createTable(false);
        $datos["script_grid"]       = $this->dataTableServer->createScript();
        $datos["colegiados"]       = Colegiado::get();

        return view("{$this->path_controller}.index", $datos);
    }
    public function grilla()
    {
        $objeto = Cuota::with(["colegiado"])->get();
        return  DataTables::of($objeto)
            ->addIndexColumn()
            ->make(true);
    }
    public function getData(Request $request)
    {
        $codcolegiado = $request->input("codcolegiado");
        $obj = Cuota::with(['colegiado.persona_natural'])
            ->where('codcolegiado', $codcolegiado)
            ->first();

        if (!$obj) {
            $obj = Colegiado::with(['persona_natural'])
                ->where('codcolegiado', $codcolegiado)
                ->first();

            return response()->json(['status' => false, 'data' => $obj]);
        }
        return response()->json(['status' => true, 'data' => $obj]);
    }
    public function saveData(Request $request)
    {
        $obj = Cuota::where('codcolegiado',$request->input("id"))->first();
        if(!$obj){
            $obj = new Cuota();
            $obj->codcolegiado = $request->input("id");
            $obj->mes = $request->input("mes");
            $obj->anio = $request->input("anio");
            $obj->monto = $request->input("monto");
            $obj->save();

        }else{
            return response()->json(['status' => false, 'data' => $obj]);
        }
        
        return response()->json(['status' => true, 'data' => $obj]);
       
    }
    public function colegiado($id, $type, $total)
    {
        return response()->json((new CuotasColegiado($id))->calculoCuotas($type, $total));
    }
    public function habilitado($id)
    {
        return response()->json((new CuotasColegiado($id))->calculoDeudas());
    }
}
