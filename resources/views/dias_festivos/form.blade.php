<form class="" id="form-{{ $pathController }}" style="display:none;">
    <input type="hidden" name="cod{{ $table_name }}" id="cod{{ $table_name . $prefix }}">

    <div class="card card-primary card-outline card-tabs" style="margin-bottom: 0rem !important;">
        <div class="card-body" style="padding: 20px 10px !important;">
            <div class="tab-content" id="custom-tabs-three-tabContent">
                <div class="tab-pane fade active show" id="custom-tabs-01" role="tabpanel">
                    <div class="form-group row">
                        <div class="col-md-9">
                            <label class="required">Título </label>
                            <input type="text" name="titulo" id="titulo{{ $prefix }}"
                                class="form-control pt-2 pb-2 input-xs" required placeholder="Día del padre">
                            <span class="titulo error_text_o_o d-none" role="alert"></span>
                        </div>
                        <div class="col-md-3">
                            <label class="required">Fecha </label>
                            <input type="date" name="fecha" id="fecha{{ $prefix }}"
                                class="form-control pt-2 pb-2 input-xs" required>
                            <span class="fecha error_text_o_o d-none" role="alert"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label class="">Descripción</label>
                            <textarea name="descripcion" rows="2" id="descripcion{{$prefix}}" class="form-control pt-2 pb-2 input-xs" placeholder="Festivo para celebrar el día del padre"></textarea>
                            <span class="descripcion error_text_o_o d-none" role="alert"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    var _name_module_{{ $table_name }} = "{{ $modulo }}";
    var _name_tabla_{{ $table_name }} = "{{ $table_name }}";
    var _prefix_{{ $table_name }} = "{{ $prefix }}";
    var _path_controller_{{ $table_name }} = "{{ $pathController }}";
</script>
