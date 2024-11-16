<form class="" id="form-{{ $pathController }}" style="display:none;">
    <input type="hidden" name="cod{{ $table_name }}" id="cod{{ $table_name . $prefix }}">

    <div class="card card-primary card-outline card-tabs" style="margin-bottom: 0rem !important;">
        <div class="card-body" style="padding: 20px 10px !important;">
            <div class="tab-content" id="custom-tabs-three-tabContent">
                <div class="tab-pane fade active show" id="custom-tabs-01" role="tabpanel">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label class="required">Tipo de caja </label>
                            <div class="input-group input-group-xs">
                                <select class="form-control pt-2 pb-2" name="idtipo_caja" id="idtipo_caja{{ $prefix }}">
                                    @foreach ($tipo_caja as $item)
                                        <option value="{{ $item->idtipo_caja }}">{{ $item->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="required">Monto de apertura </label>
                            <input type="number" name="monto_apertura" id="monto_apertura{{ $prefix }}"
                                class="form-control pt-2 pb-2" required placeholder="270000" maxlength="6">
                            <span class="monto_apertura error_text_o_o d-none" role="alert"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<style>
    #table_especialidad.table th,
    #table_especialidad.table td {
        padding: .35rem;
    }
</style>

<script>
    var _name_module_{{ $table_name }} = "{{ $modulo }}";
    var _name_tabla_{{ $table_name }} = "{{ $table_name }}";
    var _prefix_{{ $table_name }} = "{{ $prefix }}";
    var _path_controller_{{ $table_name }} = "{{ $pathController }}";
</script>
