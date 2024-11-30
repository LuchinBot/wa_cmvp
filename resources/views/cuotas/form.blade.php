@extends('adminlte::page')

@section('content_header')
<h1>Cuotas</h1>
@stop

@section('content')
<div class="row border">
    <form class="justify-content-center border" id="form-cuotas">
        <div class="card card-primary card-outline card-tabs" style="margin-bottom: 0rem !important;">
            <div class="card-header p-0 pt-1 border-bottom-0">
                <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="custom-tabs-01-tab" data-toggle="pill" href="#custom-tabs-01" role="tab" aria-selected="true">Principal</a>
                    </li>
                </ul>
            </div>
            <div class="card-body" style="padding: 0rem 1.25rem 0rem !important;">
                <div class="tab-content" id="custom-tabs-three-tabContent">
                    <div class="tab-pane fade active show" id="custom-tabs-01" role="tabpanel">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="required">Colegiado</label>
                                <input type="text" name="colegiado" id="colegiado" readonly class="form-control input-xs">
                                <span class="colegiado error_text_o_o d-none" role="alert"></span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-4">
                                <label class="required">Icono</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="icono_preview"><i class="fal fs-xl"></i></span>
                                    </div>
                                    <input type="text" class="form-control input-xs" aria-label="" id="icono" name="icono" required>
                                    <div class="input-group-append">
                                        <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                                    </div>
                                </div>
                                <span class="icono error_text_o_o d-none" role="alert"></span>
                            </div>

                            <div class="col-md-6">
                                <label class="required">Derecho de pago</label>
                                <input type="text" name="derecho_pago" id="derecho_pago" class="form-control input-xs" required placeholder="Sin costo">
                            </div>
                            <div class="col-md-2">
                                <label class="required">Orden</label>
                                <input type="number" name="orden" id="orden" class="form-control input-xs" required placeholder="1">
                                <span class="orden error_text_o_o d-none" role="alert"></span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="required">Descripcion</label>
                                <textarea name="descripcion" rows="2" id="descripcion" class="form-control input-xs" placeholder=""></textarea>
                                <span class="descripcion error_text_o_o d-none" role="alert"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

</div>
@stop