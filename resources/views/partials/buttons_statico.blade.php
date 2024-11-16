@if(isset($botones['new']) && $botones['new']==1)
<button id="btn-new" data-toggle="tooltip" data-controller="{{$controller}}" data-placement="right" data-original-title="Nuevo" title="" type="button" style="font-size: 1.2rem;font-weight: bolder;margin-bottom: 0.2rem;" class="btn btn-default mt-2 btn-lg btn-icon rounded-circle hover-effect-dot waves-effect waves-themed">
    <i class="fa fa-file" style="font-size: 1.2rem;font-weight: bolder;"></i>
</button><br>
@endif

@if(isset($botones['edit']) && $botones['edit']==1)
<button id="btn-edit" data-toggle="tooltip" data-controller="{{$controller}}" data-placement="right" data-original-title="Editar" type="button" style="font-size: 1.2rem;font-weight: bolder;margin-bottom: 0.2rem;" class="btn btn-info mt-2 btn-lg btn-icon rounded-circle hover-effect-dot waves-effect waves-themed">
    <i class="fas fa-edit"></i>
</button>
<br>
@endif

@if(isset($botones['delete']) && $botones['delete']==1)
<button id="btn-delete" data-toggle="tooltip" data-controller="{{$controller}}" data-placement="right" data-original-title="Eliminar" type="button" style="font-size: 1.2rem;font-weight: bolder;margin-bottom: 0.2rem;" class="btn btn-danger mt-2 btn-lg btn-icon rounded-circle hover-effect-dot waves-effect waves-themed">
    <i class="fas fa-trash"></i>
</button>
<br>
@endif

@if(isset($botones['print']) && $botones['print']==1)
<button id="btn-print" data-toggle="tooltip" data-controller="{{$controller}}" data-placement="right" data-original-title="Imprimir" title="" type="button" style="font-size: 1.2rem;font-weight: bolder;margin-bottom: 0.2rem;" class="btn btn-dark mt-2 btn-lg btn-icon rounded-circle hover-effect-dot waves-effect waves-themed">
    <i class="fa fa-print" style="font-size: 1.2rem;font-weight: bolder;"></i>
</button>
<br>
@endif

@if(isset($botones['export_excel']) && $botones['export_excel']==1)
<button id="btn-export" data-toggle="tooltip" data-controller="{{$controller}}" data-placement="right" data-original-title="Exportar" title="" type="button" style="font-size: 1.2rem;font-weight: bolder;margin-bottom: 0.2rem;" class="btn btn-success mt-2 btn-lg btn-icon rounded-circle hover-effect-dot waves-effect waves-themed">
    <i class="fa fa-file-excel" style="font-size: 1.2rem;font-weight: bolder;"></i>
</button>
<br>
@endif

@if(isset($botones['export_list']) && $botones['export_list']==1)
<button id="btn-export-list" data-toggle="tooltip" data-controller="{{$controller}}" data-placement="right" data-original-title="Exportar" title="" type="button" style="font-size: 1.2rem;font-weight: bolder;margin-bottom: 0.2rem;" class="btn btn-success mt-2 btn-lg btn-icon rounded-circle hover-effect-dot waves-effect waves-themed">
    <i class="fa fa-file-invoice" style="font-size: 1.2rem;font-weight: bolder;"></i>
</button>
<br>
@endif
