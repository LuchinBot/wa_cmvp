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
                                <select class="form-control pt-2 pb-2" name="idtipo_caja"
                                    id="idtipo_caja{{ $prefix }}">
                                    @foreach ($tipo_caja as $item)
                                        <option value="{{ $item->idtipo_caja }}">{{ $item->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="required">Fecha </label>
                            <div class="input-group input-group-xs">
                                <input type="date" class="form-control pt-2 pb-2" value="{{ date('Y-m-d') }}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label class="">Producto</label>
                            <div class="input-group input-group-xs">
                                <select class="form-control input-xs" id="idproducto_add" >
                                    <option value="">[SELECCIONE]</option>
                                    @foreach ($productos as $producto)
                                    <option value="{{$producto->idproducto}}">{{$producto->descripcion}}</option>
                                    @endforeach
                                </select>
                                <div class="input-group-prepend">
                                    <button class="btn btn-xs btn-primary" title="Agregar" id="btn-agregar-producto{{$prefix}}" type="button">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-12">
                            <div style="height: 180px; overflow-y:auto;">
                                <table class="table mb-0" id="table_producto">
                                    <thead>
                                        <tr>
                                            <th width="05%">#</th>
                                            <th width="90%">producto</th>
                                            <th width="05%">#</th>
                                        </tr>
                                    </thead>

                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<style>
    #table_producto.table th,
    #table_producto.table td {
        padding: .35rem;
    }
</style>

<script>
    var _name_module_{{ $table_name }} = "{{ $modulo }}";
    var _name_tabla_{{ $table_name }} = "{{ $table_name }}";
    var _prefix_{{ $table_name }} = "{{ $prefix }}";
    var _path_controller_{{ $table_name }} = "{{ $pathController }}";
</script>
