<form class="" id="form-{{ $pathController }}" style="display:none;">
    <input type="hidden" name="cod{{ $table_name }}" id="cod{{ $table_name . $prefix }}">
    <input type="hidden" name="imagen" id="imagen{{$prefix}}">

    <div class="card card-primary card-outline card-tabs" style="margin-bottom: 0rem !important;">
        <div class="card-body" style="padding: 20px 10px !important;">
            <div class="tab-content" id="custom-tabs-three-tabContent">
                <div class="tab-pane fade active show" id="custom-tabs-01" role="tabpanel">
                    <div class="form-group row">
                        <div class="col-md-9">
                            <label class="required">Título </label>
                            <input type="text" name="titulo" id="titulo{{ $prefix }}"
                                class="form-control pt-2 pb-2" required placeholder="Día del padre">
                            <span class="titulo error_text_o_o d-none" role="alert"></span>
                        </div>
                        <div class="col-md-3">
                            <label class="required">Fecha </label>
                            <input type="date" name="fecha" id="fecha{{ $prefix }}"
                                class="form-control pt-3 pb-3" required>
                            <span class="fecha error_text_o_o d-none" role="alert"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label class="">Descripción</label>
                            <textarea name="descripcion" rows="2" id="descripcion{{$prefix}}" class="form-control pt-2 pb-2" placeholder="Festivo para celebrar el día del padre"></textarea>
                            <span class="descripcion error_text_o_o d-none" role="alert"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-8">
                            <label>Cargar Flayer (Ancho:{{$dimension_flayer["ancho"]}}px, Alto:{{$dimension_flayer["alto"]}}px)</label>
                            <div class="custom-file" style="height:25px">
                                <input type="file" class="custom-file-input border" accept="image/jpg, image/png, image/jpeg" onchange="cargar_flayer(this, 'flayer_prev')" id="file_flayer{{$prefix}}" name="file_flayer">
                                <label class="custom-file-label custom-file-label-xs" for="file">{{implode(", ", $formato_valido)}}</label>
                            </div>

                            <span class="imagen error_text_o_o d-none" role="alert"></span>
                        </div>

                        <div class="col-md-4">
                            <center class="mb-0">
                                <img class="img-thumbnail load_photo" style="" id="flayer_prev{{$prefix}}" src="{{$default_flayer}}">
                            </center>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
  var _name_module_{{$table_name}}        = "{{$modulo}}";
    var _name_tabla_{{$table_name}}         = "{{$table_name}}";
    var _prefix_{{$table_name}}             = "{{$prefix}}";
    var _path_controller_{{$table_name}}    = "{{$pathController}}";
    var _default_flayer_{{$table_name}}     = "{{$default_flayer}}";
    var _default_plantilla_{{$table_name}}  = "{{$default_plantilla}}";
</script>