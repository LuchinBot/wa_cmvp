<form class="needs-validation" id="form-{{$pathController}}" style="display:none">
    <input type="hidden" name="cod{{$table_name}}" id="cod{{$table_name.$prefix}}">
    <input type="hidden" name="imagen" id="imagen{{$prefix}}">

    <div class="card card-primary card-outline card-tabs" style="margin-bottom: 0rem !important;">
        <div class="card-header p-0 pt-1 border-bottom-0">
            <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="custom-tabs-01-tab" data-toggle="pill" href="#custom-tabs-01" role="tab" aria-selected="true">Principal</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-03-tab" data-toggle="pill" href="#custom-tabs-02" role="tab" aria-selected="false">Imagen</a>
                </li>
            </ul>
        </div>

        <div class="card-body" style="padding: 0rem 1.25rem 0rem !important;">
            <div class="tab-content" id="custom-tabs-three-tabContent">
                <div class="tab-pane fade active show" id="custom-tabs-01" role="tabpanel">
                    <div class="form-group row">
                        <div class="col-md-9">
                            <label class="required">Titulo</label>
                            <input type="text" name="titulo" id="titulo{{$prefix}}" class="form-control input-xs" required placeholder="Entrevista en el diario voces">
                            <span class="titulo error_text_o_o d-none" role="alert"></span>
                        </div>

                        <div class="col-md-3">
                            <label class="required">Fecha</label>
                            <input type="date" name="fecha" id="fecha{{$prefix}}" class="form-control input-xs" required >
                            <span class="fecha error_text_o_o d-none" role="alert"></span>
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

                <div class="tab-pane fade" id="custom-tabs-02" role="tabpanel">
                    <div class="form-group row">
                        <div class="alert mb-0 mt-1 alert-info p-2" style="font-size:13px;">
                            En este módulo podrá subir la imágen del evento. Ten en cuenta las dimensiones de las imágenes (Ancho: {{$dimensiones["ancho"]}}px y Alto: {{$dimensiones["alto"]}}px.)
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-6">
                            <label class="required">Imagen Evento</label>
                            <div id="dropzone" class="dropzone dropzone-previews"></div>
                            <span class="file error_text_o_o d-none" role="alert"></span>
                        </div>

                        <div class="col-md-6">
                            <label class="required">Vista previa de la imagen</label>
                            <div class="row form-group">
                                <div class="col-md-12">
                                    <div id="vista_previa_llena">
                                        <center><img id="prev_imagen{{$prefix}}" style="width:100%; height:150px; background: #c0c0c0;" src="{{$default_evento}}" class="img-thumbnail"></img></center>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

@foreach ($extend_eventos as $form=>$params)
    @include($form, $params)
@endforeach

<script>
    var _name_tabla_{{$table_name}}       = "{{$table_name}}";
    var _path_controller_{{$table_name}}  = "{{$pathController}}";
    var _name_module_{{$table_name}}      = "{{$modulo}}";
    var _prefix_{{$table_name}}           = "{{$prefix}}";
    var _default_imagen_{{$table_name}}   = "{{$default_evento}}";
</script>
