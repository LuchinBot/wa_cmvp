@extends('adminlte::page')

@section('content')
<div class="row">
    <div class="col-md-3">
        <!-- Profile Image -->
        <div class="card card-primary card-outline">
            <div class="card-header p-2">
                <h3>Datos Personales</h3>
            </div>
            <div class="card-body box-profile">
                <form id="form-datos-personales" method="POST">
                    @csrf
                    <input name="codtipo_documento_identidad" type="hidden" value="{{$usuario->persona->codtipo_documento_identidad}}" >
                    <input name="numero_documento_identidad" type="hidden" value="{{$usuario->persona->numero_documento_identidad}}" >
                    <input name="apellido_paterno" type="hidden" value="{{$usuario->persona->apellido_paterno}}" >
                    <input name="apellido_materno" type="hidden" value="{{$usuario->persona->apellido_materno}}" >
                    <input name="nombres" type="hidden" value="{{$usuario->persona->nombres}}" >
                    <input name="foto" type="hidden" value="{{$usuario->persona->foto}}" >
                    <input name="codpersona" type="hidden" value="{{$usuario->codpersona}}" >
                    <div class="text-center">
                        <img class="profile-user-img img-fluid img-circle" src="{{url_shared("{$usuario->persona->getPathFoto()}/{$usuario->persona->getFoto()}")}}" alt="User profile picture">
                    </div>

                    <h4 class="profile-username text-center">{{$usuario->persona->nombres." ".$usuario->persona->apellido_paterno." ".$usuario->persona->apellido_materno}}</h4>
                    <!--
                    <p class="text-muted text-center">{{$usuario->perfil->descripcion}}</p>
                    -->

                    <div class="form-group mb-2">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="">Telefono Móvil</label>
                                <div class="input-group input-group-xs">
                                    <input type="text" name="telefono" id="telefono{{$prefix}}" value="{{$usuario->persona->telefono}}" class="form-control input-xs" maxlength="9" placeholder="942000001">
                                    <div class="input-group-text">
                                        <span class=""><i class="fa fa-1x fa-phone"></i></span>
                                    </div>
                                </div>
                                <span class="telefono error_text_o_o d-none" role="alert"></span>
                            </div>

                            <div class="col-md-6 mb-2">
                                <label for="">F. Nacimiento</label>
                                <div class="input-group input-group-xs">
                                    <input type="date" name="fecha_nacimiento" id="fecha_nacimiento{{$prefix}}" value="{{$usuario->persona->fecha_nacimiento}}" max="{{date('Y-m-d')}}" class="form-control input-sm">
                                </div>
                                <span class="fecha_nacimiento error_text_o_o d-none" role="alert"></span>
                            </div>
                        </div>

                    </div>

                    <div class="form-group mb-2">
                        <label for="">Correo Electronico</label>
                        <div class="input-group input-group-xs">
                            <input type="email" name="email" id="email{{$prefix}}" class="form-control input-xs" value="{{$usuario->persona->email}}" placeholder="example@claro.pe">
                            <div class="input-group-text">
                                <span><i class="fa fa-at"></i></span>
                            </div>
                            <span class="email error_text_o_o d-none" role="alert"></span>
                        </div>
                    </div>

                    <div class="form-group mb-2">
                        <label for="">Direccion</label>
                        <div class="input-group input-group-xs ">
                            <input type="text" name="direccion" id="direccion{{$prefix}}" value="{{$usuario->persona->direccion}}" class="form-control input-xs" placeholder="Jr. La galaxia">
                            <div class="input-group-text">
                                <span><i class="fa fa-map-marker-alt"></i></span>
                            </div>
                        </div>
                        <span class="direccion error_text_o_o d-none" role="alert"></span>
                    </div>

                    <a href="#" id="actualizar_datos" class="btn btn-xs btn-primary btn-block"><b>ACTUALIZAR DATOS</b></a>
                </form>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>

    <!-- /.col -->
    <div class="col-md-9">
        <div class="card card-primary card-outline">
            <div class="card-header p-2">
                <h3>Cuenta de usuario</h3>
            </div>

            <div class="card-body">
                <form id="form-datos-cuenta">
                    @csrf
                    <input name="codusuario" type="hidden" value="{{$usuario->codusuario}}" >
                    <input name="avatar" type="hidden" value="{{$usuario->avatar}}" >
                    <div class="form-group">
                        <div class="row">
                            <div class="col-3">
                                <label class="">Usuario</label>
                                <div class="input-group input-group-md">
                                    <input type="text" class="form-control input-md" value="{{$usuario->usuario}}" disabled required placeholder="SOPORTE">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fa fa-user" ></i>
                                        </span>
                                    </div>
                                </div>
                                <span class="usuario error_text_o_o d-none" role="alert"></span>
                            </div>

                            <div class="col-3">
                                <label class="">Perfil</label>
                                <div class="input-group input-group-md">
                                    <input type="text" class="form-control input-md" disabled value="{{$usuario->perfil->descripcion}}" required placeholder="SOPORTE">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fa fa-user" ></i>
                                        </span>
                                    </div>
                                </div>
                                <span class="codperfil error_text_o_o d-none" role="alert"></span>
                            </div>

                            <div class="col-3">
                                <label>Avatar</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input custom-file-input-md" accept="image/jpg, image/png, image/jpeg" onchange="cargar_avatar(this, 'foto_prev')" id="file{{$prefix}}" name="file"">
                                    <label class="custom-file-label custom-file-label-md" for="file">Cargar Avatar</label>
                                </div>
                            </div>

                            <div class="col-1">
                                <center>
                                    <img class="img-thumbnail load_photo" style="width:60px; height:60px;" id="foto_prev{{$prefix}}" src="{{url_shared("{$usuario->getPathAvatar()}/{$usuario->getAvatar()}")}}">
                                </center>
                            </div>

                            <div class="col-2">
                                <label class="">Actualizar clave?</label>
                                <div class="onoffswitch">
                                    <input type="checkbox" id="cambiar_clave{{$prefix}}" class="onoffswitch-checkbox" />
                                    <label class="onoffswitch-label" for="cambiar_clave{{$prefix}}">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <hr>
                        <div class="row">
                            <div class="col-3">
                                <label class="">Clave Actual</label>
                                <div class="input-group input-group-md">
                                    <input type="password" value="******" class="form-control input-md" disabled required placeholder="***">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-lock" ></i></span>
                                    </div>
                                </div>
                                <span class="password error_text_o_o d-none" role="alert"></span>
                            </div>

                            <div class="col-3">
                                <label class="">Nueva Clave</label>
                                <div class="input-group input-group-md">
                                    <input type="password" id="password_nuevo{{$prefix}}" readonly name="password_nuevo" class="form-control input-md" required placeholder="***">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-lock" ></i></span>
                                    </div>
                                </div>
                                <span class="password error_text_o_o d-none" role="alert"></span>
                            </div>

                            <div class="col-3">
                                <label class="">Repita Nueva Clave</label>
                                <div class="input-group input-group-md">
                                    <input type="password" id="password_re_nuevo{{$prefix}}" readonly name="password_re_nuevo" class="form-control input-md" required placeholder="***">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-lock" ></i></span>
                                    </div>
                                </div>
                                <span class="password error_text_o_o d-none" role="alert"></span>
                            </div>

                            <div class="col-3">
                                <label class="">&nbsp;</label>
                                <a href="#" id="actualizar_cuenta" class="btn btn-sm btn-primary btn-block"><b>ACTUALIZAR CUENTA</b></a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop
@routes
@section('js')
    <script>
        var _prefix_{{$table_name}}             = "{{$prefix}}";
    </script>
    <script src="{{asset("js/form/$pathController/perfil_usuario.js")}}"></script>
@stop
