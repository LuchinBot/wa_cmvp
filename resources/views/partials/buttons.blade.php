@foreach($botones as $val)
    <button id="{{$val->id_name}}"
        data-toggle="tooltip"
        data-controller="{{$pathController}}"
        data-placement="right"
        data-original-title="{{$val->descripcion}}"
        title="{{$val->descripcion}}"
        type="button"
        style="font-size: 1.2rem;font-weight: bolder;margin-bottom: 0.2rem;"
        class="btn btn-{{$val->clase_name}} mt-2 btn-lg btn-icon rounded-circle hover-effect-dot waves-effect waves-themed">
        <i class="fa {{$val->icono}}" style="font-size: 1.2rem;font-weight: bolder;"></i>
    </button>
    </br>
@endforeach
