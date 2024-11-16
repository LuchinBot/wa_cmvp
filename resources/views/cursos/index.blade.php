@extends('adminlte::page')

@section('content_header')
    <h1>{{$modulo}}</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-1 col-sm-1">
            <div class="" id="wid-id-0">
                <div>
                    <div class="widget-body text-center">
                        @include('partials.buttons_statico',['botones'=>$botones, "controller"=>$pathController])
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-11 col-sm-11">
            <div class="card">
                <div class="card-body">
                    {!!$tabla_grid!!}
                </div>
            </div>
        </div>
    </div>

    @include("{$pathController}.form")
@stop

@routes

@section('plugins.Datatables', true)
@section('plugins.Dropzone', true)
@section('plugins.Summernote', true)

@section('js')
    <script type="text/javascript">
        {!!$script_grid !!}
    </script>

    @foreach ($extend_cursos as $form=>$params)
    <script src='{{asset("js/form/{$params['pathController']}/form.js?{$params['version']}")}}'></script>
    @endforeach

    <script src="{{asset("js/form/$pathController/index.js")}}"></script>
    <script src="{{asset("js/form/$pathController/form.js")}}"></script>
@stop
