@extends('adminlte::page')

@section('content_header')<h1>{{ $modulo }}</h1>@stop

@section('content')
    <div class="container-fluid" id="iframe_module">
        <div class="row">
            <div class="col-md-4">
                <div class="card mb-2">
                    <div class="card-header bg-dark" style="">
                        <h3 class="card-title">Filtro</h3>
                    </div>
                    <form id="filtro-form" method="POST" onsubmit="return false" class="mb-0">
                        <input type="hidden" name="id_ubigeo" id="id_ubigeo">
                        <div class="card-body" style="">
                            <div class="form-group mb-3 row">
                                <div class="col-md-12">
                                    <div class="alert alert-info text-justify p-3 mb-0" style="font-size: 14px;">
                                        <p>Seleccione al colegiado de la lista y haga clic en el botón <strong>"Agregar"</strong> para proceder.</p>
                                        <p><strong>Nota:</strong> <i>Solo podrá agregar una nueva cuota si el colegiado no ha pagado o registrado su última cuota.</i></p>
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label class="required" for="">Colegiado </label>
                                    <select class="form-control" name="codcolegiado"
                                        id="codcolegiado{{ $prefix }}">
                                        @foreach ($colegiados as $colegiado)
                                            <option value="{{ $colegiado->codcolegiado }}">
                                                {{ $colegiado->persona_natural->numero_documento_identidad }} -
                                                {{ $colegiado->persona_natural->nombre_completo }}</option>
                                        @endforeach
                                    </select>
                                    <span class="codcolegiado error_text_o_o d-none" role="alert"></span>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="button" id="btn_buscar" class="btn btn-xs btn-block btn-primary">Agregar</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-md-8">
                <div class="row">
                    <div class="card" style="width:100%">
                        <div class="card-header bg-dark" style="">
                            <h3 class="card-title">Resultado</h3>
                        </div>

                        <div class="card-body">
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <div style="height: 390px; overflow-y:auto;">
                                        <table class="table mb-0" id="table_result">
                                            <thead>
                                                <tr>
                                                    <th width="45%" class="text-left">Colegiado</th>
                                                    <th width="15%" class="text-center">Mes</th>
                                                    <th width="15%" class="text-center">Año</th>
                                                    <th width="15%" class="text-center">Monto</th>
                                                    <th width="10%" class="text-center">Acción</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        #table_result.table th,
        #table_result.table td {
            padding: .35rem;
        }

        .select2-container--default .select2-selection--single {
            height: 25px !important;
            padding: 2px 5px;
            font-size: 12px;
            line-height: 1.5;
            border-radius: 3px;
        }

        .select2-results {
            font-size: 11.0px;
            padding: 2px 5px;
        }
    </style>
@stop

@routes

@section('plugins.Select2', true)

@section('js')
    <script>
        var default_ubigeo = "17742";
        var _name_module = "{{ $modulo }}";
        var _path_controller = "{{ $pathController }}";
    </script>
    <script src="{{ asset("js/form/$pathController/index.js?v$version") }}"></script>
@stop
