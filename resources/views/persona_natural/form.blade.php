<form class="needs-validation was-validated" id="form-{{$pathController}}" data-version-form="{{$version}}" style="display:none" >
    <input type="hidden" name="cod{{$table_name}}" id="cod{{$table_name.$prefix}}" class="form-system">
    <input type="hidden" name="id_ubigeo" id="id_ubigeo{{$prefix}}" class="form-system">
    <input type="hidden" name="foto" id="foto{{$prefix}}" class="form-system">

    <div class="row">
        <div class="col-md-3 form-group">
            <label class='required'>Documento Identidad</label>
            <div class="input-group input-group-sm">
                <div class="input-group-prepend">
                    <select style="width:75px;" class="form-control input-sm" id="codtipo_documento_identidad{{$prefix}}" required name="codtipo_documento_identidad">
                        @foreach ($tipo_doc_identidad as $item)
                        <option value="{{$item->codtipo_documento_identidad}}" data-length="{{$item->longitud}}" >{{$item->abreviatura??$item->descripcion}}</option>
                        @endforeach
                    </select>
                </div>
                <input type="text" name="numero_documento_identidad" id="numero_documento_identidad{{$prefix}}" class="form-control input-sm" required placeholder="01000001">

                <div class="input-group-prepend">
                    <button class="btn btn-primary" id="buscar_persona{{$prefix}}" type="button">
                        <i class="fa fa-search"></i>
                    </button>
                </div>
            </div>
            <span class="numero_documento_identidad error_text_o_o d-none" role="alert"></span>
            <span class="codtipo_documento_identidad error_text_o_o d-none" role="alert"></span>
        </div>

        <div class="col-md-3 form-group">
            <label class= 'required'>Ap. Paterno</label>
            <input type="text" name="apellido_paterno" id="apellido_paterno{{$prefix}}" class="form-control input-sm" required placeholder="Ap. paterno">
            <span class="apellido_paterno error_text_o_o d-none" role="alert"></span>
        </div>

        <div class="col-md-3 form-group">
            <label class= 'required'>Ap. Materno</label>
            <input type="text" name="apellido_materno" id="apellido_materno{{$prefix}}" class="form-control input-sm" required placeholder="Ap. materno">
            <span class="apellido_paterno error_text_o_o d-none" role="alert"></span>
        </div>

        <div class="col-md-3 form-group">
            <label class='required'>Nombres</label>
            <input type="text" name="nombres" id="nombres{{$prefix}}" class="form-control input-sm" required placeholder="Ingrese sus nombres">
            <span class="nombres error_text_o_o d-none" role="alert"></span>
        </div>
    </div>

    <div class="row">
        <div class="col-md-2 form-group">
            <label class= '' for="fecha_nacimiento{{$prefix}}">Fecha Nacim.</label>
            <input type="date" name="fecha_nacimiento" id="fecha_nacimiento{{$prefix}}" max="{{date('Y-m-d')}}" class="form-control input-sm">
            <span class="fecha_nacimiento error_text_o_o d-none" role="alert"></span>
        </div>

        <div class="col-md-2 form-group">
            <label class= '' for="codsexo{{$prefix}}">Sexo</label>
            <select class="form-control input-sm" id="codsexo{{$prefix}}" name="codsexo">
                <option value="">[Seleccione]</option>
                @foreach($sexo as $value)
                <option value="{{$value->codsexo}}" data-key-api="{{$value->id_api}}">
                    {{$value->descripcion}}
                </option>
                @endforeach
            </select>
            <span class="codsexo error_text_o_o d-none" role="alert"></span>
        </div>

        <div class="col-md-2 form-group">
            <label class= '' for="codestado_civil{{$prefix}}">Estado Civil</label>
            <select class="form-control input-sm" id="codestado_civil{{$prefix}}" name="codestado_civil">
                <option value="">[Seleccione]</option>
                @foreach($estado_civil as $value)
                <option value="{{$value->codestado_civil}}">
                    {{$value->descripcion}}
                </option>
                @endforeach
            </select>
            <span class="codestado_civil error_text_o_o d-none" role="alert"></span>
        </div>

        <div class="col-md-3 form-group">
            <label class= '' for="telefono{{$prefix}}">Telefono Móvil</label>
            <div class="input-group input-group-sm">
                <div class="input-group-prepend">
                    <span class="input-group-text">+51</span>
                </div>
                <input type="text" name="telefono" id="telefono{{$prefix}}" class="form-control input-sm" maxlength="9" placeholder="942000001">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-1x fa-phone"></i></span>
                </div>
            </div>
            <span class="telefono error_text_o_o d-none" role="alert"></span>
        </div>

        <div class="col-md-3 form-group">
            <label class= '' for="email{{$prefix}}">E-mail</label>
            <div class="input-group input-group-sm">
                <input type="email" name="email" id="email{{$prefix}}" class="form-control" placeholder="example@claro.pe">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-at"></i></span>
                </div>
            </div>
            <span class="email error_text_o_o d-none" role="alert"></span>
        </div>
    </div>

    <div class="row">
        <div class="col-2 form-group">
            <label>Foto</label>
            <div class="custom-file">
                <input type="file" class="custom-file-input custom-file-input-sm" accept="image/jpg, image/png, image/jpeg" onchange="cargar_foto_persona(this, 'foto_prev')" id="file{{$prefix}}" name="file"">
                <label class="custom-file-label custom-file-label-sm" id="custom-foto{{$prefix}}" for="file">Cargar Foto</label>
            </div>
        </div>

        <div class="col-1 form-group">
            <center>
                <img class="img-thumbnail load_photo" style="width:60px; height:60px;" id="foto_prev{{$prefix}}" src="{{$default_photo}}">
            </center>
        </div>

        <div class="col-md-4 form-group">
            <label class= '' for="ubigeo{{$prefix}}">Ubigeo</label>
            <div class="input-group input-group-sm">
                <input type="text" id="ubigeo_descr{{$prefix}}" class="form-control input-sm" readonly placeholder="Departamento - Provincia - Distrito">
                <div class="input-group-prepend">
                    <button class="btn btn-primary" id="btn_ubigeo{{$prefix}}" type="button">
                        <i class="fa fa-search"></i>
                    </button>
                </div>
            </div>
            <span class="ubigeo{{$prefix}} error_text_o_o d-none" role="alert"></span>
        </div>

        <div class="col-md-5 form-group">
            <label class= '' for="direccion{{$prefix}}">Direccion</label>
            <div class="input-group input-group-sm ">
                <input type="text" name="direccion" id="direccion{{$prefix}}" class="form-control input-sm" placeholder="Jr. La galaxia">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-map-marker-alt"></i></span>
                </div>
            </div>
            <span class="direccion{{$prefix}} error_text_o_o d-none" role="alert"></span>
        </div>
    </div>
</form>

@foreach ($extend_persona_natural as $form=>$params)
    @include($form, $params)
@endforeach

<script>
    var default_ubigeo = "17742";
    var _name_tabla_{{$table_name}}             = "{{$table_name}}";
    var _path_controller_{{$table_name}}        = "{{$pathController}}";
    var _name_module_{{$table_name}}            = "{{$modulo}}";
    var _prefix_{{$table_name}}                 = "{{$prefix}}";
    var _path_photo                             = "{{$path_photo}}";
    var _default_photo                          = "{{$default_photo}}";
</script>
