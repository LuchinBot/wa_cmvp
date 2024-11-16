@extends('adminlte::page')

@section('content_header')<h1>{{$modulo}}</h1>@stop

@section('content')
    <div class="preloader flex-column justify-content-center align-items-center">
        <img class="animation__wobble" src="{{url('app/img/empresa/pngwing.png')}}" alt="AdminLTELogo" height="60" width="60">
    </div>

    <div class="row">
        <div class="@if(count($botones)>0)col-lg-1 col-sm-1 @endif">
            <div class="wid-id-0">
                <div class="widget-body text-center">
                    @include('partials.buttons',['botones'=>$botones, "controller"=>$pathController])
                </div>
            </div>
        </div>

        <div class="@if(count($botones)>0) col-lg-11 col-sm-11 @else col-lg-12 col-sm-12 @endif">
            <div class="card">
                <div class="card-body">
                    {!!$tabla_grid!!}
                </div>
            </div>
        </div>
    </div>

    @include("seguridad.{$pathController}.form")
@stop

@routes

@section('plugins.Datatables', true)

@section('js')
<script type="text/javascript">
    {!!$script_grid !!}
</script>

@foreach ($extend_modulos as $form=>$params)
<script src='{{asset("js/form/{$params['pathController']}/form.js")}}'></script>
@endforeach

<script src="{{asset("js/form/$pathController/index.js")}}"></script>
<script src="{{asset("js/form/$pathController/form.js")}}"></script>
@stop
