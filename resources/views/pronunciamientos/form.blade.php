<form class="" id="form-{{$pathController}}" style="display:none;">
    <input type="hidden" name="cod{{$table_name}}" id="cod{{$table_name.$prefix}}">
    <input type="hidden" name="imagen" id="imagen{{$prefix}}">
    <input type="hidden" name="imagen_flayer" id="imagen_flayer{{$prefix}}">

    <div class="card card-primary card-outline card-tabs" style="margin-bottom: 0rem !important;">
        <div class="card-header p-0 pt-1 border-bottom-0">
            <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="custom-tabs-01-tab" data-toggle="pill" href="#custom-tabs-01" role="tab" aria-selected="true">Pronunciamiento</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-02-tab" data-toggle="pill" href="#custom-tabs-02" role="tab" aria-selected="false">Flayer</a>
                </li>
            </ul>
        </div>

        <div class="card-body" style="padding: 0rem 1.25rem 0rem !important;">
            <div class="tab-content" id="custom-tabs-three-tabContent">
                <div class="tab-pane fade active show" id="custom-tabs-01" role="tabpanel">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label class="required">Titulo</label>
                            <input type="text" name="titulo" id="titulo{{$prefix}}" class="form-control input-xs" placeholder="Difución del articulo 44° del colegio Medico Veterinario">
                            <span class="titulo error_text_o_o d-none" role="alert"></span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-2">
                            <label class="required">Fecha</label>
                            <input type="date" name="fecha" id="fecha{{$prefix}}" class="form-control input-xs" required >
                            <span class="fecha error_text_o_o d-none" role="alert"></span>
                        </div>

                        <div class="col-md-2">
                            <label class="required">Inicio Publicación</label>
                            <input type="date" name="fecha_publicacion_inicio" id="fecha_publicacion_inicio{{$prefix}}" class="form-control input-xs" required >
                            <span class="fecha_publicacion_inicio error_text_o_o d-none" role="alert"></span>
                        </div>

                        <div class="col-md-2">
                            <label class="required">Fin Publicación</label>
                            <input type="date" name="fecha_publicacion_fin" id="fecha_publicacion_fin{{$prefix}}" class="form-control input-xs" required >
                            <span class="fecha_publicacion_fin error_text_o_o d-none" role="alert"></span>
                        </div>

                        <div class="col-md-5">
                            <label>Presentación principal (Ancho {{$dimension_imagen["ancho"]}} x Alto {{$dimension_imagen["alto"]}}px)</label>
                            <div class="custom-file" style="height:24px">
                                <input type="file" class="custom-file-input" accept="image/jpg, image/png, image/jpeg" onchange="cargar_imagen(this, 'foto_prev')" id="file_archivo{{$prefix}}" name="file_archivo">
                                <label class="custom-file-label custom-file-label-xs" for="file">Formatos válidos {{implode(", ", $formato_valido)}}</label>
                            </div>
                            <span class="file_archivo error_text_o_o d-none" role="alert"></span>
                        </div>

                        <div class="col-md-1">
                            <center class="mt-2">
                                <img class="img-thumbnail load_photo" style="" id="foto_prev{{$prefix}}" src="{{$default_imagen}}">
                            </center>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-12">
                            <label class="">Reseña del pronunciamiento</label>
                            <textarea type="text" name="descripcion" id="descripcion{{$prefix}}" class="form-control input-sm" required></textarea>
                            <span class="descripcion error_text_o_o d-none" role="alert"></span>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="custom-tabs-02" role="tabpanel" >
                    <div class="form-group row">
                        <div class="alert mb-0 mt-1 alert-info p-2" style="font-size:13px;">
                            En este módulo podrá subir las imágenes del flayer para los pronunciamientos. Ten en cuenta las dimensiones de las imágenes (Ancho: {{$dimension_flayer["ancho"]}}px y Alto: {{$dimension_flayer["alto"]}}px.)
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-6">
                            <label class="required">Imagen Flayer</label>
                            <div id="dropzone{{$prefix}}" class="dropzone dropzone-previews"></div>
                            <span class="file error_text_o_o d-none" role="alert"></span>
                        </div>

                        <div class="col-md-6">
                            <label class="required">Vista previa de la imagen</label>
                            <div class="row form-group">
                                <div class="col-md-12">
                                    <div id="vista_previa_llena">
                                        <center><img id="prev_imagen{{$prefix}}" style="width:35%; background: #c0c0c0;" src="{{$default_flayer}}" class="img-thumbnail"></img></center>
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

@foreach ($extend_pronunciaminto as $form=>$params)
    @include($form, $params)
@endforeach

<script>
    var _name_module_{{$table_name}}        = "{{$modulo}}";
    var _name_tabla_{{$table_name}}         = "{{$table_name}}";
    var _prefix_{{$table_name}}             = "{{$prefix}}";
    var _path_controller_{{$table_name}}    = "{{$pathController}}";
    var _default_imagen_{{$table_name}}     = "{{$default_imagen}}";
    var _default_flayer_{{$table_name}}     = "{{$default_flayer}}";
</script>
