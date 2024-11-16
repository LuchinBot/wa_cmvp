<form class="" id="form-{{$pathController}}" style="display:none;">
    <input type="hidden" name="cod{{$table_name}}" id="cod{{$table_name.$prefix}}">
    <input type="hidden" name="imagen_flayer" id="imagen_flayer{{$prefix}}">
    <input type="hidden" name="plantilla_certificado" id="plantilla_certificado{{$prefix}}">
    <input type="hidden" name="curriculum_vitae" id="curriculum_vitae{{$prefix}}">

    <input type="hidden" name="codpersona_natural" id="codpersona_natural{{$prefix}}">
    <input type="hidden" name="codpersona_natural_vicedecano" id="codpersona_natural_vicedecano{{$prefix}}">
    <input type="hidden" name="codpersona_natural_director" id="codpersona_natural_director{{$prefix}}">


    <div class="card card-primary card-outline card-tabs" style="margin-bottom: 0rem !important;">
        <div class="card-header p-0 pt-1 border-bottom-0">
            <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="custom-tabs-01-tab" data-toggle="pill" href="#custom-tabs-01" role="tab" aria-selected="true">Principal</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-02-tab" data-toggle="pill" href="#custom-tabs-02" role="tab" aria-selected="true">Especialidad</a>
                </li>
            </ul>
        </div>

        <div class="card-body" style="padding: 0rem 1.25rem 0rem !important;">
            <div class="tab-content" id="custom-tabs-three-tabContent">
                <div class="tab-pane fade active show" id="custom-tabs-01" role="tabpanel">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label class="required">Documento de Identidad</label>
                            <div class="input-group input-group-xs">
                                <select class="form-control input-xs" id="codtipo_doc_ident{{$prefix}}" >
                                    @foreach ($tipo_doc_identidad as $item)
                                    <option value="{{$item->codtipo_documento_identidad}}">{{$item->abreviatura}}</option>
                                    @endforeach
                                </select>
                                <input type="text" id="nro_doc_colegiado{{$prefix}}" class="form-control input-xs" maxlength="8" required placeholder="46900000">
                                <div class="input-group-prepend">
                                    <button class="btn btn-xs btn-primary" title="Buscar" id="btn-search-persona_natural{{$prefix}}" type="button">
                                        <i class="fa fa-search"></i>
                                    </button>
                                    <button class="btn btn-xs btn-default" title="Editar datos" id="btn-edit-persona_natural{{$prefix}}" type="button">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button class="btn btn-xs btn-default" title="No existe? Registrar aquí" id="btn-register-persona_natural{{$prefix}}" type="button">
                                        <i class="fa fa-file"></i>
                                    </button>
                                </div>
                            </div>
                            <span class="codtipo_documento_identidad numero_documento_identidad error_text_o_o d-none" role="alert"></span>
                        </div>

                        <div class="col-md-6">
                            <label class="required">Colegiado</label>
                            <input type="text" id="nombres_colegiado{{$prefix}}" readonly class="form-control input-xs" required placeholder="Jesus Franklin Medina Perez">
                            <span class="codpersona_natural error_text_o_o d-none" role="alert"></span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-3">
                            <label class="required">Nro Colegiatura</label>
                            <input type="text" name="numero_colegiatura" id="numero_colegiatura{{$prefix}}" class="form-control input-xs" required placeholder="270000" maxlength="6">
                            <span class="numero_colegiatura error_text_o_o d-none" role="alert"></span>
                        </div>

                        <div class="col-md-3">
                            <label class="">F. colegiatura</label>
                            <input type="date" name="fecha_colegiatura" id="fecha_colegiatura{{$prefix}}" class="form-control input-xs" required >
                            <span class="fecha_colegiatura error_text_o_o d-none" role="alert"></span>
                        </div>

                        <div class="col-md-4">
                            <label>Cargar CV</label>
                            <div class="custom-file" style="height:24px">
                                <input type="file" class="custom-file-input" accept=".pdf" onchange="cargar_archivo(this)" id="file{{$prefix}}" name="file">
                                <label class="custom-file-label custom-file-label-xs" for="file">Formato {{implode(", ", $formato_valido)}}</label>
                            </div>

                            <span class="file error_text_o_o d-none" role="alert"></span>
                            <span class="file_upload mt-3" style="font-size: 13px;" role="alert"></span>
                        </div>

                        <div class="col-md-2">
                            <label class="">Estado</label>
                            <div>
                                <span class="badge bg-success">Habilitado</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="custom-tabs-02" role="tabpanel">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label class="">Especialidad</label>
                            <div class="input-group input-group-xs">
                                <select class="form-control input-xs" id="codespecialidad_add" >
                                    <option value="">[SELECCIONE]</option>
                                    @foreach ($especialidades as $especialidad)
                                    <option value="{{$especialidad->codespecialidad}}">{{$especialidad->descripcion}}</option>
                                    @endforeach
                                </select>
                                <div class="input-group-prepend">
                                    <button class="btn btn-xs btn-primary" title="Agregar" id="btn-agregar-especialidad{{$prefix}}" type="button">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-12">
                            <div style="height: 180px; overflow-y:auto;">
                                <table class="table mb-0" id="table_especialidad">
                                    <thead>
                                        <tr>
                                            <th width="05%">#</th>
                                            <th width="90%">Especialidad</th>
                                            <th width="05%">#</th>
                                        </tr>
                                    </thead>

                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

@foreach ($extend_colegiados as $form=>$params)
    @include($form, $params)
@endforeach

<style>
    #table_especialidad.table th, #table_especialidad.table td{
        padding: .35rem;
    }
</style>

<script>
    var _name_module_{{$table_name}}        = "{{$modulo}}";
    var _name_tabla_{{$table_name}}         = "{{$table_name}}";
    var _prefix_{{$table_name}}             = "{{$prefix}}";
    var _path_controller_{{$table_name}}    = "{{$pathController}}";
    var _default_cv_{{$table_name}}         = "{{$default_cv}}";
</script>
