<form class="" id="form-{{$pathController}}" style="display:none;">
    <input type="hidden" name="cod{{$table_name}}" id="cod{{$table_name.$prefix}}">
    <input type="hidden" name="imagen_flayer" id="imagen_flayer{{$prefix}}">
    <input type="hidden" name="plantilla_certificado" id="plantilla_certificado{{$prefix}}">
    <input type="hidden" name="codcolegiado_vicedecano" id="codcolegiado_vicedecano{{$prefix}}">
    <input type="hidden" name="codcolegiado_director" id="codcolegiado_director{{$prefix}}">


    <div class="card card-primary card-outline card-tabs" style="margin-bottom: 0rem !important;">
        <div class="card-header p-0 pt-1 border-bottom-0">
            <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="custom-tabs-01-tab" data-toggle="pill" href="#custom-tabs-01" role="tab" aria-selected="true">Principal</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-03-tab" data-toggle="pill" href="#custom-tabs-02" role="tab" aria-selected="false">Certificado</a>
                </li>
            </ul>
        </div>

        <div class="card-body" style="padding: 0rem 1.25rem 0rem !important;">
            <div class="tab-content" id="custom-tabs-three-tabContent">
                <div class="tab-pane fade active show" id="custom-tabs-01" role="tabpanel">
                    <div class="form-group row">
                        <div class="col-md-2">
                            <label class="required">Tipo</label>
                            <select class="form-control input-xs" id="codtipo_curso{{$prefix}}" name="codtipo_curso" >
                                <option value="">[SELECCIONE]</option>
                                @foreach ($tipo_cursos as $tipo_curso)
                                <option value="{{$tipo_curso->codtipo_curso}}">{{$tipo_curso->descripcion}}</option>
                                @endforeach
                            </select>
                            <span class="codtipo_curso error_text_o_o d-none" role="alert"></span>
                        </div>

                        <div class="col-md-10">
                            <label class="required">Titulo</label>
                            <input type="text" name="titulo" id="titulo{{$prefix}}" class="form-control input-xs" required placeholder="Introducción a la veterinaria forence">
                            <span class="titulo error_text_o_o d-none" role="alert"></span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-2">
                            <label class="required">Horas lectivas</label>
                            <input type="number" min="0" name="horas_lectivas" id="horas_lectivas{{$prefix}}" class="form-control input-xs" required placeholder="0">
                            <span class="horas_lectivas error_text_o_o d-none" role="alert"></span>
                        </div>

                        <div class="col-md-2">
                            <label class="">Inicio</label>
                            <input type="date" min="0" name="fecha_inicio" id="fecha_inicio{{$prefix}}" class="form-control input-xs" required placeholder="">
                            <span class="fecha_inicio error_text_o_o d-none" role="alert"></span>
                        </div>

                        <div class="col-md-2">
                            <label class="">Fin</label>
                            <input type="date" min="0" name="fecha_fin" id="fecha_fin{{$prefix}}" class="form-control input-xs" required placeholder="">
                            <span class="fecha_fin error_text_o_o d-none" role="alert"></span>
                        </div>

                        <div class="col-md-5">
                            <label>Cargar Flayer (Ancho:{{$dimension_flayer["ancho"]}}px, Alto:{{$dimension_flayer["alto"]}}px)</label>
                            <div class="custom-file" style="height:24px">
                                <input type="file" class="custom-file-input" accept="image/jpg, image/png, image/jpeg" onchange="cargar_flayer(this, 'flayer_prev')" id="file_flayer{{$prefix}}" name="file_flayer">
                                <label class="custom-file-label custom-file-label-xs" for="file">{{implode(", ", $formato_valido)}}</label>
                            </div>

                            <span class="file_flayer error_text_o_o d-none" role="alert"></span>
                        </div>

                        <div class="col-md-1">
                            <center class="mb-0">
                                <img class="img-thumbnail load_photo" style="" id="flayer_prev{{$prefix}}" src="{{$default_flayer}}">
                            </center>
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

                <div class="tab-pane fade" id="custom-tabs-02" role="tabpanel" >
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <div class="col-md-3">
                                    <label class="">Certificado?</label>
                                    <div class="onoffswitch">
                                        <input type="checkbox"  id="con_certificado{{$prefix}}" class="onoffswitch-checkbox" />
                                        <label class="onoffswitch-label" for="con_certificado{{$prefix}}">
                                            <span class="onoffswitch-inner"></span>
                                            <span class="onoffswitch-switch"></span>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-7">
                                    <label>Plantilla (Ancho:{{$dimension_certificado["ancho"]}}px, Alto:{{$dimension_certificado["alto"]}}px)</label>
                                    <div class="custom-file" style="height:24px">
                                        <input type="file" class="custom-file-input" accept="image/jpg, image/png, image/jpeg" onchange="cargar_plantilla(this, 'plantilla_prev')" id="file_plantilla{{$prefix}}" name="file_plantilla">
                                        <label class="custom-file-label custom-plantilla custom-file-label-xs" for="file">{{implode(", ", $formato_valido)}}</label>
                                    </div>

                                    <span class="file_plantilla error_text_o_o d-none" role="alert"></span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-12">
                                    <center class="">
                                        <img class="img-thumbnail load_photo" style="width:40%;" id="plantilla_prev{{$prefix}}" src="{{$default_plantilla}}">
                                    </center>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label class="">Vice decano (La búsqueda será sólo para colegiados)</label>
                                    <div class="input-group input-group-xs">
                                        <input type="text" id="nombres_vicedecano{{$prefix}}" class="form-control input-xs" required placeholder="Escriba: DNI | Nombres">
                                        <span class="input-group-text">
                                            <i class="fa fa-keyboard" aria-hidden="true"></i>
                                        </span>
                                    </div>
                                    <span class="codpersona_natural_vicedecano error_text_o_o d-none" role="alert"></span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label class="">Director (La búsqueda será sólo para colegiados)</label>
                                    <div class="input-group input-group-xs">
                                        <input type="text" id="nombres_director{{$prefix}}" class="form-control input-xs" required placeholder="Escriba: DNI | Nombres">
                                        <span class="input-group-text">
                                            <i class="fa fa-keyboard" aria-hidden="true"></i>
                                        </span>
                                    </div>
                                    <span class="codpersona_natural_director error_text_o_o d-none" role="alert"></span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group row">
                                <div class="col-md-4">
                                    <label for="">Buscar DNI</label>
                                    <div class="input-group input-group-xs">
                                        <input type="text" id="nro_doc_identidad_participante{{$prefix}}" class="form-control input-xs" maxlength="8" placeholder="Escriba: DNI">
                                        <div class="input-group-prepend">
                                            <button class="btn btn-xs btn-primary" title="Buscar" id="btn-search-participante{{$prefix}}" type="button">
                                                <i class="fa fa-search"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <span class="numero_documento_identidad error_text_o_o d-none" role="alert"></span>
                                </div>
                                <div class="col-md-8">
                                    <label for="">Participante</label>
                                    <div class="input-group input-group-xs">
                                        <input type="hidden" id="codparticipante{{$prefix}}" >
                                        <input type="text" id="nombres_participante{{$prefix}}" class="form-control input-xs" readonly placeholder="Apellidos y Nombres">
                                        <div class="input-group-prepend">
                                            <button class="btn btn-xs btn-primary" title="Buscar" id="btn-add-participante{{$prefix}}" type="button">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label for="">Lista de participantes</label>
                                    <div style="height: 160px; overflow-y:auto;">
                                        <table class="table mb-0" id="table_participantes">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>DNI</th>
                                                    <th>Participante</th>
                                                    <th>#</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <tr>
                                                    <td colspan="4" class='text-center'><i>No hay participantes</i></td>
                                                </tr>
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
</form>

@foreach ($extend_cursos as $form=>$params)
    @include($form, $params)
@endforeach

<style>
    #table_participantes.table th, #table_participantes.table td{
        padding: .35rem;
    }

    .readonly{
        background: #c0c0c0;
    }
</style>

<script>
    var _name_module_{{$table_name}}        = "{{$modulo}}";
    var _name_tabla_{{$table_name}}         = "{{$table_name}}";
    var _prefix_{{$table_name}}             = "{{$prefix}}";
    var _path_controller_{{$table_name}}    = "{{$pathController}}";
    var _default_flayer_{{$table_name}}     = "{{$default_flayer}}";
    var _default_plantilla_{{$table_name}}  = "{{$default_plantilla}}";
</script>
