<form class="needs-validation" id="form-{{$pathController}}" style="display:none">
    <input type="hidden" name="cod{{$table_name}}" id="cod{{$table_name.$prefix}}">
    <input type="hidden" name="archivo" id="archivo{{$prefix}}">

    <div class="form-group row">
        <div class="col-md-10">
            <label class="required">Titulo</label>
            <input type="text" name="titulo" id="titulo{{$prefix}}" class="form-control input-xs" required placeholder="REGLAMENTO ELECTORAL">
            <span class="titulo error_text_o_o d-none" role="alert"></span>
        </div>

        <div class="col-md-2">
            <label class="required">Orden</label>
            <input type="number" name="orden" id="orden{{$prefix}}" class="form-control input-xs" required placeholder="1">
            <span class="orden error_text_o_o d-none" role="alert"></span>
        </div>
    </div>

    <div class="form-group row">
        <div class="col-md-12">
            <label>Cargar documento normativo</label>
            <div class="custom-file" style="height:24px">
                <input type="file" class="custom-file-input" accept=".pdf" onchange="cargar_archivo(this)" id="file{{$prefix}}" name="file">
                <label class="custom-file-label custom-file-label-xs" for="file">Formato {{implode(", ", $formato_valido)}}</label>
            </div>

            <span class="file error_text_o_o d-none" role="alert"></span>
            <span class="file_upload mt-3" style="" role="alert"></span>
        </div>
    </div>
</form>

@foreach ($extend_documentos_normativos as $form=>$params)
    @include($form, $params)
@endforeach

<script>
    var _name_tabla_{{$table_name}}       = "{{$table_name}}";
    var _path_controller_{{$table_name}}  = "{{$pathController}}";
    var _name_module_{{$table_name}}      = "{{$modulo}}";
    var _prefix_{{$table_name}}           = "{{$prefix}}";
</script>
