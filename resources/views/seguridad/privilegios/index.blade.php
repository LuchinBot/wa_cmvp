@extends('adminlte::page')

@section('content_header')
    <h1>{{$modulo}}</h1>
@stop

@section('content')
    <div class="preloader flex-column justify-content-center align-items-center">
        <img class="animation__wobble" src="{{url('app/img/empresa/pngwing.png')}}" alt="AdminLTELogo" height="60" width="60">
    </div>

    <div class="row">
        <div class="col-lg-4 col-sm-4">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Filtrar Accesos Modulos</h3>
                </div>

                <form id="form-accesos">
                    <div class="card-body">
                        <div class="form-group">
                            <label>Sistema</label>
                            <select class="form-control input-sm" name="codsistema" id="codsistema{{$prefix}}" required>

                                <option value="">[SELECCIONE]</option>
                                @foreach($sistema as $value)
                                <option value="{{$value->codsistema}}">{{$value->sistema}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Perfil</label>
                            <select class="form-control input-sm" name="codperfil" id="codperfil{{$prefix}}" required>

                                <option value="">[SELECCIONE]</option>
                                <!---->
                                @foreach($perfil as $value)
                                <option value="{{$value->codperfil}}">{{$value->descripcion}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Empleados Asignados</label>
                            <select class="form-control empleados" multiple style="height: 160px"></select>
                        </div>
                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer">
                        <button type="button" id="guardar" class="btn btn-block btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-lg-8 col-sm-8">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Modulos</h3>
                    <span class="ml-2 badge bg-danger error_carga"></span>
                </div>

                <form>
                    <div class="card-body">
                        <div class="" id="tree" style="overflow-y:auto;height:380px;"></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@routes

@section('plugins.JsTree', true)

@section('js')
    <script>
        var _name_module        = "{{$modulo}}";
        var _name_tabla         = "{{$table_name}}";
        var _path_controller    = "{{$pathController}}";
    </script>

    <script src="{{asset("js/form/$pathController/index.js")}}"></script>
@stop
