<form class="needs-validation" id="form-{{$pathController}}" style="display:none">
    <input type="hidden" name="cod{{$table_name}}" id="cod{{$table_name.$prefix}}">
    <div class="form-group row">
        <div class="col-md-3">
            <label class="required">Inicio Periodo</label>
            <input type="date" name="fecha_periodo_inicio" id="fecha_periodo_inicio{{$prefix}}" class="form-control input-xs" required >
            <span class="fecha_periodo_inicio error_text_o_o d-none" role="alert"></span>
        </div>

        <div class="col-md-3">
            <label class="required">Fin Periodo</label>
            <input type="date" name="fecha_periodo_fin" id="fecha_periodo_fin{{$prefix}}" class="form-control input-xs" required >
            <span class="fecha_periodo_fin error_text_o_o d-none" role="alert"></span>
        </div>

        <div class="col-md-6">
            <label class="required">Descripcion</label>
            <input type="text" name="descripcion" id="descripcion{{$prefix}}" class="form-control input-xs" required placeholder="Periodo 2007-2029">
            <span class="descripcion error_text_o_o d-none" role="alert"></span>
        </div>
    </div>

    <div class="form-group row">
        <div class="col-md-9">
            <label for="">Integrante (Colegiado)</label>
            <div class="input-group input-group-xs">
                <input type="hidden" id="codcolegiado{{$prefix}}" >
                <input type="text" id="nombres_integrante{{$prefix}}" class="form-control input-xs" placeholder="Apellidos y Nombres">
            </div>
        </div>

        <div class="col-md-3">
            <label for="">DNI</label>
            <div class="input-group input-group-xs">
                <input type="text" id="nro_doc_identidad_integrante{{$prefix}}" readonly class="form-control input-xs" maxlength="8" placeholder="Escriba: DNI">
                <div class="input-group-prepend">
                    <button class="btn btn-xs btn-primary" title="Buscar" id="btn-add-integrante{{$prefix}}" type="button">
                        <i class="fa fa-plus"></i>
                    </button>
                </div>
            </div>

            <span class="numero_documento_identidad error_text_o_o d-none" role="alert"></span>
        </div>
    </div>

    <div class="form-group row">
        <div class="col-md-12">
            <table class="table mb-0" id="table_integrantes">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Integrante</th>
                        <th>Cargo</th>
                        <th>#</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</form>

<style>
    #table_integrantes.table th, #table_integrantes.table td{
        padding: .35rem;
        font-size: 13px;
    }
</style>

@foreach ($extend_juntas_directivas as $form=>$params)
    @include($form, $params)
@endforeach

<script>
    var _name_tabla_{{$table_name}}       = "{{$table_name}}";
    var _path_controller_{{$table_name}}  = "{{$pathController}}";
    var _name_module_{{$table_name}}      = "{{$modulo}}";
    var _prefix_{{$table_name}}           = "{{$prefix}}";
    var _select_cargos_{{$table_name}}    = {!!$cargos!!};
</script>
