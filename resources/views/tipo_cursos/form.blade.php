<form style="display: none;" id="form-{{$pathController}}" >
    <input type="hidden" name="cod{{$table_name}}" id="cod{{$table_name.$prefix}}">
    <div class="form-group row">
        <div class="col-md-12">
            <label class="required">Tipo de curso</label>
            <input type="text" name="descripcion" id="descripcion{{$prefix}}" class="form-control input-sm" required placeholder="Teórico - Práctico">
            <span class="descripcion error_text_o_o d-none" role="alert"></span>
        </div>
    </div>

    <div class="form-group row">
        <div class="col-md-12">
            <label class="required">Abreviatura</label>
            <input type="text" name="abreviatura" id="abreviatura{{$prefix}}" class="form-control input-sm" required placeholder="Teórico - Práctico">
            <span class="abreviatura error_text_o_o d-none" role="alert"></span>
        </div>
    </div>
</form>

<script>
    var _name_tabla_{{$table_name}}       = "{{$table_name}}";
    var _path_controller_{{$table_name}}  = "{{$pathController}}";
    var _name_module_{{$table_name}}      = "{{$modulo}}";
    var _prefix_{{$table_name}}           = "{{$prefix}}";
</script>
