@extends('template.main')
@section('solicitude')            
    @include('template.User.menu')
    @include('template.Modals.temporal_user')
    @if( Auth::user()->type == TESORERIA )
        @include('template.Modals.deposit')
        @include( 'template.Modals.massive-deposit' )
    @endif
	<button id="show_leyenda" type="button" class="btn btn-link">Ver leyenda</button>
@stop