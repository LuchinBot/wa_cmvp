<form class="needs-validation was-validated" id="form-{{$table_name}}" style="display:none">
    <input type="hidden" name="cod{{$table_name}}" id="cod{{$table_name.$prefix}}">
    <div class="form-group row">
        <div class="col-md-8">
            <div class="form-group row">
                <div class="col-md-6">
                    <label class="required">Sistema</label>
                    <select class="form-control input-sm" name="codsistema" id="codsistema{{$prefix}}" required>
                        <option value="">[SELECCIONE]</option>
                        @foreach($sistema as $value)
                            <option value="{{$value->codsistema}}">{{$value->sistema}}</option>
                        @endforeach
                    </select>
                    <span class="codsistema error_text_o_o d-none" role="alert"></span>
                </div>

                <div class="col-md-6">
                    <label class="">Modulo Padre</label>
                    <select class="form-control input-sm" name="codpadre" id="codpadre{{$prefix}}">
                        <option value="">[SELECCIONE]</option>
                    </select>
                    <span class="codpadre error_text_o_o d-none" role="alert"></span>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-6">
                    <label class="required">Modulo</label>
                    <input type="text" name="descripcion" id="descripcion{{$prefix}}" class="form-control input-sm" required placeholder="PERFILES">
                    <span class="descripcion error_text_o_o d-none" role="alert"></span>
                </div>

                <div class="col-md-6">
                    <label class="required">Abreviatura</label>
                    <input type="text" name="abreviatura" id="abreviatura{{$prefix}}" class="form-control input-sm" required placeholder="PERFIL">
                    <span class="abreviatura error_text_o_o d-none" role="alert"></span>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-6">
                    <label class="required">Url</label>
                    <input type="text" name="url" id="url{{$prefix}}" class="form-control input-sm" required placeholder="#">
                    <span class="url error_text_o_o d-none" role="alert"></span>
                </div>

                <div class="col-md-6">
                    <label class="required">Icono</label>
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="icono_preview"><i class="fal fs-xl" ></i></span>
                        </div>
                        <input type="text" class="form-control" aria-label="" id="icon{{$prefix}}" name="icon" required>
                        <div class="input-group-append" >
                            <button class="btn btn-sm btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                            <div class="dropdown-menu dropdown-menu-right" style="max-height: 200px; overflow-x:auto;">
                                @foreach($iconos as $icon)
                                    <a href="javascript:void(0)" class="select_icon dropdown-item" id="select_icon{{$prefix}}" data-modal="modal-boton" data-icono="fa-{{$icon}}"><i class="fa fa-{{$icon}}"></i> fa-{{$icon}}</a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <span class="icon error_text_o_o d-none" role="alert"></span>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-2">
                    <label>Orden</label>
                    <input type="number" name="orden" min="1" id="orden{{$prefix}}" class="form-control input-sm" required placeholder="1">
                    <span class="orden error_text_o_o d-none" role="alert"></span>
                </div>

                <div class="col-md-2">
                    <label class="required">Activo</label>
                    <div class="onoffswitch">
                        <input type="checkbox" id="estado{{$prefix}}" checked class="onoffswitch-checkbox" />
                        <label class="onoffswitch-label" for="estado{{$prefix}}">
                            <span class="onoffswitch-inner"></span>
                            <span class="onoffswitch-switch"></span>
                        </label>
                    </div>
                    <span class="estado error_text_o_o d-none" role="alert"></span>
                </div>

                <div class="col-md-3">
                    <label class="required">Acceso rapido</label>
                    <div class="onoffswitch">
                        <input type="checkbox" id="acceso_directo{{$prefix}}" class="onoffswitch-checkbox" />
                        <label class="onoffswitch-label" for="acceso_directo{{$prefix}}">
                            <span class="onoffswitch-inner"></span>
                            <span class="onoffswitch-switch"></span>
                        </label>
                    </div>
                    <span class="acceso_directo error_text_o_o d-none" role="alert"></span>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group row">
                <div class="col-md-12">
                    <label>Botones de modulo</label>
                    <button type="button" class="btn btn-xs btn-block btn-default dropdown-toggle" title="Buscar DNI" data-toggle="dropdown" aria-expanded="false">
                        Añadir boton
                        <i class="fa fa-plus"></i>
                    </button>
                    <div class="dropdown-menu" x-placement="bottom-start" style="">
                        @foreach($boton as $v)
                        <a id='select_boton' data-key='{{$v->codboton}}' data-boton='{{$v->descripcion}}' data-clase='{{$v->clase_name}}' data-icono='{{$v->icono}}' class='dropdown-item' href='javascript:void(0)'>
                            <i class='fa {{$v->icono}} mr-2'></i>{{$v->descripcion}}
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-12">
                    <label>Botones</label>
                    <table id="tabla_detalle" class="table mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Boton</th>
                                <th>#</th>
                            </tr>
                        </thead>

                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group row">
        <div class="col-md-12">
            <ul class="list-group" id="list_errores"></ul>
        </div>
    </div>
</form>

@foreach ($extend_modulos as $form=>$params)
    @include($form, $params)
@endforeach

<script>
    var _name_tabla_{{$table_name}}       = "{{$table_name}}";
    var _path_controller_{{$table_name}}  = "{{$pathController}}";
    var _name_module_{{$table_name}}      = "{{$modulo}}";
    var _prefix_{{$table_name}}           = "{{$prefix}}";
</script>

<style>
    #tabla_detalle.table td{
        padding: 0.35rem;
    }
</style>
