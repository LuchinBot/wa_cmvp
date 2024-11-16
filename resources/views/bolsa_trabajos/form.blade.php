<form class="needs-validation" id="form-{{$pathController}}" style="display:none">
    <input type="hidden" name="cod{{$table_name}}" id="cod{{$table_name.$prefix}}">
    <div class="form-group row">
        <div class="col-md-12">
            <label class="required">Nombre Institucion</label>
            <input type="text" name="nombre_institucion" id="nombre_institucion{{$prefix}}" class="form-control input-sm" required placeholder="HOSPITAL REZOLA CAÑETE">
            <span class="nombre_institucion error_text_o_o d-none" role="alert"></span>
        </div>
    </div>

    <div class="form-group row">
        <div class="col-md-12">
            <label class="required">Url referencial</label>
            <textarea name="url_referencial" id="url_referencial{{$prefix}}" class="form-control input-sm" required placeholder="https://www.convocatoriasdetrabajo.com/oportunidad-laboral-001-medico-veterinario-hospital-rezola-canete-lima-437970.html"></textarea>
            <span class="url_referencial error_text_o_o d-none" role="alert"></span>
        </div>
    </div>

    <div class="form-group row">
        <div class="col-md-4">
            <label class="required">Inicio</label>
            <input type="date" name="fecha_inicio" id="fecha_inicio{{$prefix}}" class="form-control input-sm" required >
            <span class="fecha_inicio error_text_o_o d-none" role="alert"></span>
        </div>

        <div class="col-md-4">
            <label class="required">Fin</label>
            <input type="date" name="fecha_fin" id="fecha_fin{{$prefix}}" class="form-control input-sm" required >
            <span class="fecha_fin error_text_o_o d-none" role="alert"></span>
        </div>
    </div>
</form>

@foreach ($extend_bolsa_trabajos as $form=>$params)
    @include($form, $params)
@endforeach

<script>
    var _name_tabla_{{$table_name}}       = "{{$table_name}}";
    var _path_controller_{{$table_name}}  = "{{$pathController}}";
    var _name_module_{{$table_name}}      = "{{$modulo}}";
    var _prefix_{{$table_name}}           = "{{$prefix}}";
</script>
