@extends('template.main')
@section('solicitude')
<div class="content">
        <!-- <small style="float: right; margin-top: 10px; padding-right: 20px;">
            @if ( Auth::user()->type == REP_MED )
                <strong>Usuario : {{Auth::user()->rm->full_name}}</strong>
            @elseif ( Auth::user()->type == SUP )
                <strong>Usuario : {{Auth::user()->sup->full_name}}</strong>
            @elseif ( Auth::user()->type == GER_PROD )
                 <strong>Usuario : {{Auth::user()->gerProd->full_name}}</strong>
            @else
                <strong>Usuario : {{Auth::user()->person->full_name}}</strong>
            @endif
        </small> -->
        <ul class="nav nav-tabs" role="tablist">
            <li class="active">
                <a href="#solicitud" role="tab" data-toggle="tab">
                    <icon class="fa fa-home"></icon>
                    Solicitud
                </a>
            </li>
            @if ( ( $solicitud->id_user_assign == Auth::user()->id  && ! is_null( $solicitud->expenseHistory ) ) || ( Auth::user()->type == CONT && $solicitud->id_estado == REGISTRADO )  )
                <li>
                    <a href="#document" role="tab" data-toggle="tab">
                        <icon class="fa fa-home"></icon>
                        Documentos
                    </a>
                </li>
            @endif
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade active in" id="solicitud" style="margin-top:20px">
                @if ( $solicitud->status == BLOCKED )
                    <h4 style="color:darkred; padding-left:10px; margin-top:20px; margin-bottom:20px">LA SOLICITUD ESTA SIENDO EVALUADA</h4>
                @endif
                <form id="form_make_activity" method="post">
                    {{Form::token()}}
                    <input name="idsolicitud" type="hidden" value="{{$solicitud->id}}">
                    <input name="token" type="hidden" value="{{$solicitud->token}}">
                    <!-- DETALLE DE LA SOLICITUD -->
                    @if ( $solicitud->idtiposolicitud == SOL_REP )
                        @include('Dmkt.Solicitud.Representante.detail')
                    @elseif ( $solicitud->idtiposolicitud == SOL_INST )
                        @include('Dmkt.Solicitud.Institucional.detail')
                    @endif
                    <!-- ASIENTO DE ANTICIPO -->
                    @if ( Auth::user()->type == CONT && $solicitud->id_estado == DEPOSITADO )
                        @include('template.Seat.advance_table')
                    @endif
                    <!-- Modal Deposito -->
                    @include('template.Modals.deposit-min')
                </form>
            </div>
            <div class="tab-pane fade" id="document" style="margin-top:20px; margin-bottom:20px">
                @include('Dmkt.Solicitud.Section.gasto')
            </div>
            <!-- Button (Double) -->
            @include('Dmkt.Solicitud.Detail.buttons')
        </div>
    
</div>
@stop