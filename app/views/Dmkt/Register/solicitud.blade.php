@extends('template.main')
@section('solicitude')
<div class="content">
    <input type="hidden" id="state_view" value="{{isset($state) ? $state : PENDIENTE}}">
    <div class="page-header" style="margin-top: 0;">
        @if( isset( $solicitud ) )
        <h3>Editar Solicitud</h3>
        @else
        <h3>Nueva Solicitud @if(isset($solicitude) && $solicitude->blocked == 1)<small>LA SOLICITUD ESTA SIENDO EVALUADA</small>@endif</h3>
        @endif
    </div>
        @include('Dmkt.Register.detail')
</div>
@stop
