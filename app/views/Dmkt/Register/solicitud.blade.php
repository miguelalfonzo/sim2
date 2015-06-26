@extends('template.main')
@section('solicitude')
<div class="content">
    <input type="hidden" id="state_view" value="{{isset($state) ? $state : PENDIENTE}}">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Nueva Solicitud</h3>
            @if(isset($solicitude) && $solicitude->blocked == 1)
                <h4 class="" style="color: darkred">LA SOLICITUD ESTA SIENDO EVALUADA</h4>
            @endif
            <small style="float: right; margin-top: -10px">
                <strong>Usuario : 
                @if ( Auth::user()->type == REP_MED ) 
                    {{Auth::user()->rm->full_name}}
                @elseif ( Auth::user()->type == SUP )
                    {{Auth::user()->sup->full_name}}
                @elseif ( Auth::user()->type == GER_PROD )
                    {{Auth::user()->gerProd->full_name}} 
                @endif
                </strong>
            </small>
        </div>
        <div class="panel-body">
            @include('Dmkt.Register.detail')
        </div>
    </div>
</div>
@stop
