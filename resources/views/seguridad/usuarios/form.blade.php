<form class="needs-validation" id="form-{{$pathController}}" style="display:none">
    <input type="hidden" name="cod{{$table_name}}" id="cod{{$table_name.$prefix}}">
    <input type="hidden" name="codpersona" id="codpersona{{$prefix}}">
    <input type="hidden" name="avatar" id="avatar{{$prefix}}">
    <div class="form-group">
        <div class="row">
            <div class="col-9">
                <label class="required">Buscar Persona Natural (Representante)</label>
                <div class="input-group input-group-sm" >
                    <input type="text" class="form-control input-sm text-uppercase"placeholder="Apellidos, Nombres, DNI" id="persona_natural{{$prefix}}" >
                    <button type="button" class="btn btn-default" id="btn-registrar-persona{{$prefix}}" data-toggle="tooltip" title="No existe? registrar aqui" aria-expanded="false">
                        <i class="fa fa-window-restore fa-fw"></i>
                    </button>

                    <button type="button" class="btn btn-default" id="btn-editar-persona{{$prefix}}" data-toggle="tooltip" title="Editar" aria-expanded="false">
                        <i class="fa fa-edit fa-fw"></i>
                    </button>
                </div>
                <span class="codpersona error_text_o_o d-none" role="alert"></span>
            </div>

            <div class="col-3">
                <label class="required" >Perfil</label>
                <select name="codperfil" id="codperfil{{$prefix}}" class="form-control input-sm">
                    <option value="">SELECCIONE</option>
                    @foreach($perfil as $value)
                        <option value="{{$value->codperfil}}">{{$value->descripcion}}</option>
                    @endforeach
                </select>
                <span class="codperfil error_text_o_o d-none" role="alert"></span>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <div class="col-4">
                <label class="required">Usuario</label>
                <div class="input-group input-group-sm">
                    <input type="text" id="usuario{{$prefix}}" name="usuario" class="form-control input-sm" required placeholder="SOPORTE">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="fa fa-user" ></i>
                        </span>
                    </div>
                </div>
                <span class="usuario error_text_o_o d-none" role="alert"></span>
            </div>

            <div class="col-4">
                <label class="required">Clave</label>
                <div class="input-group input-group-sm">
                    <input type="password" id="password{{$prefix}}" name="password" class="form-control input-sm" required placeholder="***">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa fa-lock" ></i></span>
                    </div>
                </div>
                <span class="password error_text_o_o d-none" role="alert"></span>
            </div>

            <div class="col-2">
                <label>Avatar</label>
                <div class="custom-file">
                    <input type="file" class="custom-file-input custom-file-input-sm" accept="image/jpg, image/png, image/jpeg" onchange="cargar_avatar(this, 'foto_prev')" id="file{{$prefix}}" name="file"">
                    <label class="custom-file-label custom-file-label-sm" for="file">Cargar Avatar</label>
                </div>
            </div>

            <div class="col-2">
                <center>
                    <img class="img-thumbnail load_photo" style="width:60px; height:60px;" id="foto_prev{{$prefix}}" src="{{url($path_photo.$default_photo)}}">
                </center>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <div class="col-3">
                <label class="">Super user?</label>
                <div class="onoffswitch">
                    <input type="checkbox" id="es_superusuario{{$prefix}}" class="onoffswitch-checkbox" />
                    <label class="onoffswitch-label" for="es_superusuario{{$prefix}}">
                        <span class="onoffswitch-inner"></span>
                        <span class="onoffswitch-switch"></span>
                    </label>
                </div>
            </div>

            <div class="col-3">
                <label class="" style="">Dar de baja?</label>
                <div class="onoffswitch">
                    <input type="checkbox" id="baja{{$prefix}}" class="onoffswitch-checkbox" />
                    <label class="onoffswitch-label" for="baja{{$prefix}}">
                        <span class="onoffswitch-inner"></span>
                        <span class="onoffswitch-switch"></span>
                    </label>
                </div>
            </div>
        </div>
    </div>
</form>

@foreach ($extend_usuarios as $form=>$params)
    @include($form, $params)
@endforeach

<style>
    select.form-control.is-invalid {
        padding-right: 0rem !important;
    }
</style>
<script>
    var _name_tabla_{{$table_name}}         = "{{$table_name}}";
    var _path_controller_{{$table_name}}    = "{{$pathController}}";
    var _name_module_{{$table_name}}        = "{{$modulo}}";
    var _prefix_{{$table_name}}             = "{{$prefix}}";
    var _path_photo                         = "{{$path_photo}}";
    var _default_photo                      = "{{$default_photo}}";
</script>
