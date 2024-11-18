<form class="needs-validation" id="form-{{$pathController}}" style="display:none">
    <input type="hidden" name="cod{{$table_name}}" id="cod{{$table_name.$prefix}}">
    <div class="form-group row">
        <div class="col-md-12">
            <label class="required">Perfil</label>
            <input type="text" name="descripcion" id="descripcion{{$prefix}}" class="form-control input-sm" required placeholder="SOPORTE">
            <span class="descripcion error_text_o_o d-none" role="alert"></span>
        </div>
    </div>
</form>

@foreach ($extend_perfiles as $form=>$params)
    @include($form, $params)
@endforeach

<script>
    var _name_tabla_{{$table_name}}       = "{{$table_name}}";
    var _path_controller_{{$table_name}}  = "{{$pathController}}";
    var _name_module_{{$table_name}}      = "{{$modulo}}";
    var _prefix_{{$table_name}}           = "{{$prefix}}";
</script>