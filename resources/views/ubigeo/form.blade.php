
<form id="modal-ubigeo{{$prefix}}" class="modal-ubigeo" data-prefix="{{$prefix}}" novalidate referencia="form-{{$pathController}}" style="display: none;">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-12 form-group">
                <label class= 'required' for="descripcion">Departamento</label>
                <select class="custom-select idubigeo_temp_modal input-xs" data-name="departamento" data-reload="provincia">
                    @foreach ($departamento as $val)
                    <option value="{{$val->id_ubigeo}}" data-dpto="{{$val->cod_dpto}}" data-prov="{{$val->cod_prov}}" >{{$val->nombre}}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="row">
            <div class="col-md-12 form-group">
                <label class= 'required' for="descripcion">Provincia</label>
                <select class="custom-select idubigeo_temp_modal input-xs" data-name="provincia" data-reload="distrito"></select>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="row">
            <div class="col-md-12 form-group">
                <label class= 'required' for="descripcion">Distrito</label>
                <select class="custom-select idubigeo_temp_modal input-xs" data-name="distrito" data-reload=""></select>
            </div>
        </div>
    </div>
</form>
