@extends('template.main')
@section('solicitude')
    
                
    @include('template.User.menu')
    <!-- <div class="clearfix"></div> -->
    <!-- <div class="container-fluid table_solicitudes" > -->
    <!-- </div> -->
    @include('template.Modals.temporal_user')
    @if( Auth::user()->type == TESORERIA )
        @include('template.Modals.deposit')
    @endif
	<button id="show_leyenda" type="button" class="btn btn-link">Ver leyenda</button>
@stop