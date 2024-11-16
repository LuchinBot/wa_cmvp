<form class="" id="form-{{$pathController}}" style="display:none;">
    <input type="hidden" name="cod{{$table_name}}" id="cod{{$table_name.$prefix}}">
    <input type="hidden" name="imagen" id="imagen{{$prefix}}">

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
                        <div class="col-md-6">
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label class="required">Titulo</label>
                                    <textarea name="titulo" id="titulo{{$prefix}}" class="form-control input-sm" required placeholder="Celebración por el día del trabajador, este 2024"></textarea>
                                    <span class="titulo error_text_o_o d-none" role="alert"></span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label class="required">Fecha</label>
                                    <input type="date" name="fecha" id="fecha{{$prefix}}" class="form-control input-sm" required >
                                    <span class="fecha error_text_o_o d-none" role="alert"></span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label class="">Cargar Imagen de <b id="bien_patrimonial_ref"></b></label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input " accept="image/jpg, image/png, image/jpeg" onchange="cargar_imagen(this)" id="file{{$prefix}}">
                                        <label class="custom-file-label custom-file-label-sm" for="file">Cargar Imagen</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label>Galeria</label>
                                    <ul class="list-group" id="lista_galeria" style="height: 165px;margin: 0;padding: 0;overflow-y: scroll; border:1px solid #ccc"></ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

@foreach ($extend_actividad as $form=>$params)
    @include($form, $params)
@endforeach

<style>
    #table_actividades.table th, #table_actividades.table td{
        padding: .35rem;
    }
</style>

<script>
    var _name_module_{{$table_name}}        = "{{$modulo}}";
    var _name_tabla_{{$table_name}}         = "{{$table_name}}";
    var _prefix_{{$table_name}}             = "{{$prefix}}";
    var _path_controller_{{$table_name}}    = "{{$pathController}}";
</script>
