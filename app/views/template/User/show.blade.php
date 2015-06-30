@extends('template.main')
@section('solicitude')
    
                
    @include('template.User.menu')
    @include('template.Modals.temporal_user')
    @if( Auth::user()->type == TESORERIA )
        @include('template.Modals.deposit')
    @endif

    <div>
        <a id="show_leyenda" style="margin-left: 15px" href="#">Ver leyenda</a>
        <a id="hide_leyenda" style="margin-left: 15px;display: none" href="#">Ocultar leyenda</a>
    </div>

@include('template.leyenda')
@stop