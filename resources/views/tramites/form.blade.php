<form class="" id="form-{{$pathController}}" style="display:none;">
    <input type="hidden" name="cod{{$table_name}}" id="cod{{$table_name.$prefix}}">
    <input type="hidden" name="imagen" id="imagen{{$prefix}}">

    <div class="card card-primary card-outline card-tabs" style="margin-bottom: 0rem !important;">
        <div class="card-header p-0 pt-1 border-bottom-0">
            <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="custom-tabs-01-tab" data-toggle="pill" href="#custom-tabs-01" role="tab" aria-selected="true">Principal</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-02-tab" data-toggle="pill" href="#custom-tabs-02" role="tab" aria-selected="false">Requisitos</a>
                </li>
            </ul>
        </div>

        <div class="card-body" style="padding: 0rem 1.25rem 0rem !important;">
            <div class="tab-content" id="custom-tabs-three-tabContent">
                <div class="tab-pane fade active show" id="custom-tabs-01" role="tabpanel">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label class="required">Titulo</label>
                            <input type="text" name="titulo" id="titulo{{$prefix}}" class="form-control input-xs" required placeholder="Nueva Colegiatura">
                            <span class="titulo error_text_o_o d-none" role="alert"></span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-4">
                            <label class="required">Icono</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="icono_preview"><i class="fal fs-xl" ></i></span>
                                </div>
                                <input type="text" class="form-control input-xs" aria-label="" id="icono{{$prefix}}" name="icono" required>
                                <div class="input-group-append" >
                                    <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                                    <div class="dropdown-menu dropdown-menu-right" style="max-height: 200px; overflow-x:auto;">
                                        @foreach($iconos as $icon)
                                            <a href="javascript:void(0)" class="select_icon dropdown-item" id="select_icon{{$prefix}}" data-modal="modal-boton" data-icono="fa-{{$icon}}"><i class="fa fa-{{$icon}}"></i> fa-{{$icon}}</a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <span class="icono{{$prefix}} error_text_o_o d-none" role="alert"></span>
                        </div>

                        <div class="col-md-6">
                            <label class="required">Derecho de pago</label>
                            <input type="text" name="derecho_pago" id="derecho_pago{{$prefix}}" class="form-control input-xs" required placeholder="Sin costo">
                        </div>
                        <div class="col-md-2">
                            <label class="required">Orden</label>
                            <input type="number" name="orden" id="orden{{$prefix}}" class="form-control input-xs" required placeholder="1">
                            <span class="orden error_text_o_o d-none" role="alert"></span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-12">
                            <label class="required">Descripcion</label>
                            <textarea name="descripcion" rows="2" id="descripcion{{$prefix}}" class="form-control input-xs" placeholder=""></textarea>
                            <span class="descripcion error_text_o_o d-none" role="alert"></span>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="custom-tabs-02" role="tabpanel" >
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label class="">Requisito</label>
                            <div class="input-group input-group-xs">
                                <select class="form-control input-xs" id="codrequisito_add" >
                                    <option value="">[SELECCIONE]</option>
                                    @foreach ($requisitos as $requisito)
                                    <option value="{{$requisito->codrequisito}}">{{$requisito->descripcion}}</option>
                                    @endforeach
                                </select>
                                <div class="input-group-prepend">
                                    <button class="btn btn-xs btn-default mr-2" title="No existe? Registra aquí" id="btn-registrar-requisito{{$prefix}}" type="button">
                                        <i class="fa fa-file"></i>
                                    </button>
                                </div>
                                <div class="input-group-prepend">
                                    <button class="btn btn-xs btn-primary" title="Agregar" id="btn-agregar-requisito{{$prefix}}" type="button">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-12">
                            <div style="height: 180px; overflow-y:auto;">
                                <table class="table mb-0" id="table_requisitos">
                                    <thead>
                                        <tr>
                                            <th width="05%">#</th>
                                            <th width="50%">Requisito</th>
                                            <th width="40%">Archivo <i class="fa fa-info-circle" title="Si el requisito tiene un formato, súbalo"></i></th>
                                            <th width="05%">#</th>
                                        </tr>
                                    </thead>

                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<form class="" id="form-nota" style="display:none;">
    <div class="form-group row">
        <div class="col-md-12">
            <div class="alert mb-0 alert-info p-2 text-justify" style="font-size:13px;">
                Añada una nota al requisito <b id="requisito_seleccionado"></b> del tramite
            </div>
        </div>
    </div>

    <div class="form-group row">
        <div class="col-md-12">
            <label class="">Nota</label>
            <textarea id="observacion{{$prefix}}" class="form-control input-xs" required placeholder="Escriba su nota"></textarea>
        </div>
    </div>
</form>

@foreach ($extend_tramites as $form=>$params)
    @include($form, $params)
@endforeach

<style>
    #table_requisitos.table th, #table_requisitos.table td{
        padding: .35rem;
    }
</style>

<script>
    var _name_module_{{$table_name}}        = "{{$modulo}}";
    var _name_tabla_{{$table_name}}         = "{{$table_name}}";
    var _prefix_{{$table_name}}             = "{{$prefix}}";
    var _path_controller_{{$table_name}}    = "{{$pathController}}";
</script>
