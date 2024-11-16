<form class="" id="form-{{$pathController}}" style="display:none;" enctype="multipart/form-data">
    <input type="hidden" name="cod{{$table_name}}" id="cod{{$table_name.$prefix}}">
    <input type="hidden" name="logo" id="logo{{$prefix}}">
    <input type="hidden" name="id_ubigeo" id="id_ubigeo{{$prefix}}">
    <input type="hidden" name="imagen_objetivo" id="imagen_objetivo{{$prefix}}">
    <input type="hidden" name="imagen_consejo" id="imagen_consejo{{$prefix}}">
    <input type="hidden" name="imagen_asamblea" id="imagen_asamblea{{$prefix}}">

    <div class="card card-primary card-outline card-tabs" style="margin-bottom: 0rem !important;">
        <div class="card-header p-0 pt-1 border-bottom-0">
            <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="custom-tabs-01-tab" data-toggle="pill" href="#custom-tabs-01" role="tab" aria-selected="true">Principal</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-02-tab" data-toggle="pill" href="#custom-tabs-02" role="tab" aria-selected="false">Objetivo</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-03-tab" data-toggle="pill" href="#custom-tabs-03" role="tab" aria-selected="false">Historia</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-04-tab" data-toggle="pill" href="#custom-tabs-04" role="tab" aria-selected="false">Misión</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-05-tab" data-toggle="pill" href="#custom-tabs-05" role="tab" aria-selected="false">Visión</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-06-tab" data-toggle="pill" href="#custom-tabs-06" role="tab" aria-selected="false">Consejo Depart.</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-06-tab" data-toggle="pill" href="#custom-tabs-07" role="tab" aria-selected="false">Asamblea Nacional</a>
                </li>
            </ul>
        </div>

        <div class="card-body" style="padding: 0rem 1.25rem 0rem !important;">
            <div class="tab-content" id="custom-tabs-three-tabContent">
                <div class="tab-pane fade active show" id="custom-tabs-01" role="tabpanel">
                    <div class="form-group row">
                        <div class="col-md-2">
                            <label class="required">RUC</label>
                            <input type="text" name="ruc" id="ruc{{$prefix}}" class="form-control input-xs" required placeholder="20450284883">
                            <span class="ruc error_text_o_o d-none" role="alert"></span>
                        </div>

                        <div class="col-md-8">
                            <label class="required">Empresa</label>
                            <div class="input-group input-group-xs">
                                <input type="text" name="razon_social" id="razon_social{{$prefix}}" class="form-control input-xs" required placeholder="COLEGIO MEDICO VETERINARIO SAN MARTIN">
                                <span class="input-group-text" id="basic-addon1">
                                    <i class="fa fa-building" aria-hidden="true"></i>
                                </span>
                            </div>
                            <span class="razon_social error_text_o_o d-none" role="alert"></span>
                        </div>

                        <div class="col-md-2">
                            <label class="required">Abreviatura</label>
                            <input type="text" name="abreviatura" id="abreviatura{{$prefix}}" class="form-control input-xs" required placeholder="CMVSM">
                            <span class="abreviatura error_text_o_o d-none" role="alert"></span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-3">
                            <label class="">Email</label>
                            <div class="input-group input-group-xs">
                                <input type="text" name="email" id="email{{$prefix}}" class="form-control input-xs" placeholder="ejemplo@ejemplo.gob.pe">
                                <span class="input-group-text">
                                    <i class="fa fa-envelope" aria-hidden="true"></i>
                                </span>
                            </div>
                            <span class="email error_text_o_o d-none" role="alert"></span>
                        </div>

                        <div class="col-md-2">
                            <label class="">Teléfono</label>
                            <div class="input-group input-group-xs">
                                <input type="text" name="telefonos" id="telefonos{{$prefix}}" class="form-control input-xs" placeholder="042-525252">
                                <span class="input-group-text" >
                                    <i class="fa fa-phone" aria-hidden="true"></i>
                                </span>
                            </div>
                            <span class="email error_text_o_o d-none" role="alert"></span>
                        </div>

                        <div class="col-md-2">
                            <label>Cargar ({{$dimension_logo["ancho"]}}x{{$dimension_logo["alto"]}}px)</label>
                            <div class="custom-file" style="height:2px">
                                <input type="file" class="custom-file-input" accept="image/jpg, image/png, image/jpeg" onchange="cargar_imagen(this, 'foto_prev')" id="file{{$prefix}}" name="file">
                                <label class="custom-file-label custom-file-label-xs" for="file">{{implode(",", $formato_valido)}}</label>
                            </div>
                            <span class="file error_text_o_o d-none" role="alert"></span>
                        </div>

                        <div class="col-md-1">
                            <center>
                                <img class="img-thumbnail load_photo" style="" id="foto_prev{{$prefix}}" src="{{$default_logo}}">
                            </center>
                        </div>

                        <div class="col-md-4">
                            <label class="">Horario Atención</label>
                            <div class="input-group input-group-xs">
                                <input type="text" name="horario_atencion" id="horario_atencion{{$prefix}}" class="form-control input-xs" placeholder="De lunes a viernes">
                                <span class="input-group-text" >
                                    <i class="fa fa-clock" aria-hidden="true"></i>
                                </span>
                            </div>
                            <span class="email error_text_o_o d-none" role="alert"></span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-6">
                            <label class="required">Dirección</label>
                            <div class="input-group input-group-xs">
                                <input type="text" name="direccion" id="direccion{{$prefix}}" class="form-control input-xs" required placeholder="Jr. San Martin">
                                <span class="input-group-text" id="basic-addon1">
                                    <i class="fa fa-map" aria-hidden="true"></i>
                                </span>
                            </div>
                            <span class="direccion error_text_o_o d-none" role="alert"></span>
                        </div>

                        <div class="col-md-6">
                            <label class= '' for="ubigeo{{$prefix}}">Ubigeo</label>
                            <div class="input-group input-group-xs">
                                <input type="text" id="ubigeo_descr{{$prefix}}" class="form-control input-xs" readonly placeholder="Departamento - Provincia - Distrito">
                                <div class="input-group-prepend">
                                    <button class="btn btn-xs btn-primary" id="btn_ubigeo{{$prefix}}" type="button">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </div>
                            </div>
                            <span class="ubigeo{{$prefix}} error_text_o_o d-none" role="alert"></span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-2">
                            <label class="">Latitud</label>
                            <input type="text" name="latitud" id="latitud{{$prefix}}" class="form-control input-xs" placeholder="-6.495718">
                            <span class="latitud error_text_o_o d-none" role="alert"></span>
                        </div>

                        <div class="col-md-2">
                            <label class="">Longitud</label>
                            <input type="text" name="longitud" id="longitud{{$prefix}}" class="form-control input-xs" placeholder="-76.356098">
                            <span class="longitud error_text_o_o d-none" role="alert"></span>
                        </div>

                        <div class="col-md-4">
                            <label class="">Página web</label>
                            <div class="input-group input-group-xs">
                                <input type="text" name="pagina_web" id="pagina_web{{$prefix}}" class="form-control input-xs" placeholder="https://www.google.com">
                                <span class="input-group-text">
                                    <i class="fa fa-globe" aria-hidden="true"></i>
                                </span>
                            </div>
                            <span class="pagina_web error_text_o_o d-none" role="alert"></span>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="custom-tabs-02" role="tabpanel" >
                    <div class="form-group row">
                        <div class="col-md-8">
                            <label class="">Objetivo</label>
                            <textarea type="text" name="objetivo" id="objetivo{{$prefix}}" class="form-control input-sm" required></textarea>
                        </div>

                        <div class="col-md-4">
                            <label>Cargar Imagen (Ancho:{{$dimension_objetivo["ancho"]}}px, Alto:{{$dimension_objetivo["alto"]}}px)</label>
                            <div class="custom-file" style="">
                                <input type="file" class="custom-file-input" accept="image/jpg, image/png, image/jpeg" onchange="cargar_imagen(this, 'objetivo_prev')" id="file_objetivo{{$prefix}}" name="file_objetivo">
                                <label class="custom-file-label custom-file-label-xs" for="file">{{implode(", ", $formato_valido)}}</label>
                            </div>
                            <center>
                                <img class="img-thumbnail load_photo" style="" id="objetivo_prev{{$prefix}}" src="{{$default_obj}}">
                            </center>
                            <span class="file error_text_o_o d-none" role="alert"></span>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="custom-tabs-03" role="tabpanel" >
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label class="">Historia</label>
                            <textarea type="text" name="historia" id="historia{{$prefix}}" class="form-control input-sm" required></textarea>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="custom-tabs-04" role="tabpanel" >
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label class="">Misión</label>
                            <textarea type="text" name="mision" id="mision{{$prefix}}" class="form-control input-sm" required></textarea>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="custom-tabs-05" role="tabpanel" >
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label class="">Visión</label>
                            <textarea type="text" name="vision" id="vision{{$prefix}}" class="form-control input-sm" required></textarea>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="custom-tabs-06" role="tabpanel" >
                    <div class="form-group row">
                        <div class="col-md-8">
                            <label class="">Descripción Consejo Departamental</label>
                            <textarea type="text" name="descripcion_consejo" id="descripcion_consejo{{$prefix}}" class="form-control input-sm" required></textarea>
                        </div>

                        <div class="col-md-4">
                            <label>Cargar Imagen (Ancho:{{$dimension_consejo_d["ancho"]}}px, Alto:{{$dimension_consejo_d["alto"]}}px)</label>
                            <div class="custom-file" style="">
                                <input type="file" class="custom-file-input" accept="image/jpg, image/png, image/jpeg" onchange="cargar_imagen(this, 'consejo_prev')" id="file_consejo{{$prefix}}" name="file_consejo">
                                <label class="custom-file-label custom-file-label-xs" for="file">{{implode(", ", $formato_valido)}}</label>
                            </div>
                            <center>
                                <img class="img-thumbnail load_photo" style="" id="consejo_prev{{$prefix}}" src="{{$default_obj}}">
                            </center>
                            <span class="file_consejo error_text_o_o d-none" role="alert"></span>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="custom-tabs-07" role="tabpanel" >
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label class="required">Imagen Asamblea Nacional</label>
                            <div id="dropzone" class="dropzone dropzone-previews"></div>
                            <span class="file error_text_o_o d-none" role="alert"></span>
                        </div>

                        <div class="col-md-6">
                            <label class="required">Vista previa de la imagen (Ancho:{{$dimension_asamblea["ancho"]}}px, Alto:{{$dimension_asamblea["alto"]}}px)</label>
                            <div class="row form-group">
                                <div class="col-md-12">
                                    <div id="vista_previa_llena">
                                        <center><img id="asamblea_prev{{$prefix}}" style="width:100%; height:150px; background: #c0c0c0;" src="{{$default_asamblea}}" class="img-thumbnail"></img></center>
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

@foreach ($extend_empresas as $form=>$params)
    @include($form, $params)
@endforeach

<script>
    var default_ubigeo                  = "17742";
    var _name_tabla_{{$table_name}}     = "{{$table_name}}";
    var _path_controller_{{$table_name}}= "{{$pathController}}";
    var _name_module_{{$table_name}}    = "{{$modulo}}";
    var _prefix_{{$table_name}}         = "{{$prefix}}";
    var _default_logo_{{$table_name}}   = "{{$default_logo}}";
    var _default_obj_{{$table_name}}    = "{{$default_obj}}";
    var _default_asam_{{$table_name}}   = "{{$default_asamblea}}";
    var _default_cmvsm_{{$table_name}}  = "{{$default_cmvsm}}";
</script>
