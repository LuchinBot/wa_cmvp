<form  novalidate id="form-{{$pathController}}" >
    <input type="hidden" name="cod{{$table_name}}" id="cod{{$table_name.$prefix}}">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-12 form-group">
                <label class= 'required' for="descripcion{{$prefix}}">Sexo</label>
                <input type="text" name="descripcion" id="descripcion{{$prefix}}" class="form-control input-sm" required placeholder="MASCULINO">
                <p class="descripcion{{$prefix}} error_text_o_o d-none" role="alert"></p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 form-group">
                <label class= 'required' for="simbolo{{$prefix}}">Simbolo</label>
                <input type="text" name="simbolo" id="simbolo{{$prefix}}" class="form-control" required placeholder="M">
                <p class="simbolo{{$prefix}} error_text_o_o d-none" role="alert"></p>
            </div>

            <div class="col-md-6 form-group">
                <label class= 'required' for="id_api{{$prefix}}">ID API</label>
                <input type="text" name="id_api" id="id_api{{$prefix}}" class="form-control" required placeholder="M">
                <p class="id_api{{$prefix}} error_text_o_o d-none" role="alert"></p>
            </div>
        </div>
    </div>
</form>

@foreach ($extend_sexos as $form=>$params)
    @include($form, $params)
@endforeach

<script>
    var _name_tabla_{{$table_name}}       = "{{$table_name}}";
    var _path_controller_{{$table_name}}  = "{{$pathController}}";
    var _name_module_{{$table_name}}      = "{{$modulo}}";
    var _prefix_{{$table_name}}           = "{{$prefix}}";
</script>
