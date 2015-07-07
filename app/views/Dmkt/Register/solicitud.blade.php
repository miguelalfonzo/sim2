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
    
            <!-- <small style="float: right; margin-top: -10px">
                <strong>Usuario : 
                @if ( Auth::user()->type == REP_MED ) 
                    {{Auth::user()->rm->full_name}}
                @elseif ( Auth::user()->type == SUP )
                    {{Auth::user()->sup->full_name}}
                @elseif ( Auth::user()->type == GER_PROD )
                    {{Auth::user()->gerProd->full_name}} 
                @endif
                </strong>
            </small> -->
        <!-- </div>
        <div class="panel-body"> -->
    @include('Dmkt.Register.detail')
        <!-- </div> -->
    </div>
</div>
@stop
