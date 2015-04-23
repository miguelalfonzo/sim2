@extends('template.main')
@section('content')
<div class="content">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Solicitud</h3>
            @if($solicitud->status == BLOCKED )
                <h4 class="" style="color: darkred">LA SOLICITUD ESTA SIENDO EVALUADA</h4>
            @endif
            <small style="float: right; margin-top: -10px">
                @if ( Auth::user()->type == REP_MED )
                    <strong>Usuario : {{Auth::user()->rm->full_name}}</strong>
                @elseif ( Auth::user()->type == SUP )
                    <strong>Usuario : {{Auth::user()->sup->full_name}}</strong>
                @elseif ( Auth::user()->type == GER_PROD )
                     <strong>Usuario : {{Auth::user()->Gerprod->descripcion}}</strong>
                @else
                    <strong>Usuario : {{Auth::user()->person->nombres.' '.Auth::user()->person->apellidos}}</strong>
                @endif
            </small>
        </div>
        <div class="panel-body">
            @include('Dmkt.Solicitud.Section.aside')   
            <form id="form_make_activity" method="post">
                {{Form::token()}}
                <input name="idsolicitude" type="hidden" value="{{$solicitud->id}}">
                <input name="token" type="hidden" value="{{$solicitud->token}}">
                <section id="collapseOne" class="row reg-expense collapse in">
                    @if ( $solicitud->idtiposolicitud == SOL_REP )
                        @include('Dmkt.Solicitud.Representante.detail')
                    @elseif ( $solicitud->idtiposolicitud == SOL_INST )
                        @include('Dmkt.Solicitud.Institucional.detail')
                    @endif
                </section>
                
                <!-- Asiento de Anticipo -->
                @if ( Auth::user()->type == CONT && $solicitud->idestado == DEPOSITADO )
                    @include('template.Seat.advance_table')
                @endif

                <!-- Registro de Gasto -->
                @include('Dmkt.Solicitud.Section.gasto')
                
                <!-- Button (Double) -->
                @include('Dmkt.Solicitud.Detail.buttons')
            </form>
        </div>
    </div>
</div>
@stop