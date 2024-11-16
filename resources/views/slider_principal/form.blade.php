<form class="" id="form-{{$pathController}}" style="display:none;">
    <input type="hidden" name="cod{{$table_name}}" id="cod{{$table_name.$prefix}}">
    <input type="hidden" name="imagen" id="imagen{{$prefix}}">

    <div class="card card-primary card-outline card-tabs" style="margin-bottom: 0rem !important;">
        <div class="card-body" style="padding: 0rem 1.25rem 0rem !important;">
            <div class="tab-content" id="custom-tabs-three-tabContent">
                <div class="form-group row">
                    <div class="alert mb-0 mt-1 alert-info p-2" style="font-size:13px;">
                        En este módulo podrá subir las imágenes del slider principal. Ten en cuenta las dimensiones de las imágenes (Ancho: {{$dimensiones["ancho"]}}px y Alto: {{$dimensiones["alto"]}}px.)
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-2">
                        <label class="required">Empresa</label>
                        <select class="form-control input-xs" id="codempresa" name="codempresa">
                            @foreach ($empresa as $val)
                            <option value="{{$val->codempresa}}">{{$val->abreviatura}}</option>
                            @endforeach
                        </select>
                        <span class="codempresa error_text_o_o d-none" role="alert"></span>
                    </div>
                    <div class="col-md-6">
                        <label class="required">Titulo</label>
                        <input type="text" name="titulo" id="titulo{{$prefix}}" class="form-control input-xs" placeholder="Ingrese el titulo">
                        <span class="titulo error_text_o_o d-none" role="alert"></span>
                    </div>
                    <div class="col-md-3">
                        <label class="required">Sub Titulo</label>
                        <input type="text" name="subtitulo" id="subtitulo{{$prefix}}" class="form-control input-xs" placeholder="Ingrese el subtitulo">
                        <span class="subtitulo error_text_o_o d-none" role="alert"></span>
                    </div>

                    <div class="col-md-1">
                        <label class="required">Orden</label>
                        <input type="number" name="orden" id="orden{{$prefix}}" class="form-control input-xs" required placeholder="1">
                        <span class="orden error_text_o_o d-none" role="alert"></span>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-6">
                        <label class="required">Imagen Slider Principal</label>
                        <div id="dropzone" class="dropzone dropzone-previews"></div>
                        <span class="file error_text_o_o d-none" role="alert"></span>
                    </div>

                    <div class="col-md-6">
                        <label class="required">Vista previa de la imagen</label>
                        <div class="row form-group">
                            <div class="col-md-12">
                                <div id="vista_previa_llena">
                                    <center><img id="prev_imagen{{$prefix}}" style="width:100%; height:150px; background: #c0c0c0;" src="{{$default_imagen}}" class="img-thumbnail"></img></center>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

@foreach ($extend_slider as $form=>$params)
    @include($form, $params)
@endforeach

<script>
    var _name_module_{{$table_name}}        = "{{$modulo}}";
    var _name_tabla_{{$table_name}}         = "{{$table_name}}";
    var _prefix_{{$table_name}}             = "{{$table_name}}";
    var _path_controller_{{$table_name}}    = "{{$pathController}}";
    var _default_imagen_{{$table_name}}     = "{{$default_imagen}}";
</script>
