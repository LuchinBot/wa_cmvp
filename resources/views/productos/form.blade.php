<form class="" id="form-{{ $pathController }}" style="display:none;">
    <input type="hidden" name="cod{{ $table_name }}" id="cod{{ $table_name . $prefix }}">

    <div class="card card-primary card-outline card-tabs" style="margin-bottom: 0rem !important;">
        <div class="card-body" style="padding: 20px 10px !important;">
            <div class="tab-content" id="custom-tabs-three-tabContent">
                <div class="tab-pane fade active show" id="custom-tabs-01" role="tabpanel">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label class="required">Descripción </label>
                            <input type="text" name="descripcion" id="descripcion{{ $prefix }}"
                                class="form-control pt-2 pb-2 input-xs" required placeholder="Certificado de vacunación">
                            <span class="descripcion error_text_o_o d-none" role="alert"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label class="">Nota</label>
                            <textarea name="nota" rows="2" id="nota{{$prefix}}" class="form-control pt-2 pb-2 input-xs" placeholder=""></textarea>
                            <span class="nota error_text_o_o d-none" role="alert"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label class="required" aria-label="checkbox">¿Controla Stock? </label>
                            <div class="onoffswitch">
                                <input type="checkbox" name="controla_stock" id="controla_stock{{$prefix}}" class="onoffswitch-checkbox" />
                                <label class="onoffswitch-label" for="controla_stock{{$prefix}}">
                                    <span class="onoffswitch-inner"></span>
                                    <span class="onoffswitch-switch"></span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="required" aria-label="checkbox">Precio </label>
                            <input type="number" name="precio" id="precio{{ $prefix }}"
                                class="form-control pt-2 pb-2 input-xs" required placeholder="125.00" maxlength="6">
                            <span class="precio error_text_o_o d-none" role="alert"></span>
                        </div>
                        <div class="col-md-4">
                            <label  data-toggle="tooltip" data-placement="top" title="Solo informativo"><span class="badge badge-pill badge-primary">Stock <i class="fa fa-info-circle"></i></span></label>
                            <input type="number" id="stock{{ $prefix }}" readonly
                                class="form-control pt-2 pb-2 input-xs" required placeholder="20" maxlength="6">
                            <span class="stock error_text_o_o d-none" role="alert"></span>
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
