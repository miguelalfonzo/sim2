@extends('template.main')
@section('content')
<style>
    .form-horizontal .control-label {
        text-align: left;
    }
</style>
<div class="content">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Solicitud</h3>
            @if($solicitude->status == BLOCKED )
                <h4 class="" style="color: darkred">LA SOLICITUD ESTA SIENDO EVALUADA</h4>
            @endif
            <small style="float: right; margin-top: -10px">
                @if ( Auth::user()->type == REP_MED )
                    <strong>Usuario : {{Auth::user()->Rm->nombres}}</strong>
                @elseif ( Auth::user()->type == SUP )
                    <strong>Usuario : {{Auth::user()->Sup->nombres}}</strong>
                @elseif ( Auth::user()->type == GER_PROD )
                     <strong>Usuario : {{Auth::user()->Gerprod->descripcion}}</strong>
                @endif
            </small>
        </div>
        <div class="panel-body">
            <aside class="row reg-expense" style="margin-bottom: 0.5em;">
                <div class="col-xs-12 col-sm-6 col-md-4">
                    <a id="detail_solicitude">
                        <span>Ocultar</span>
                        <span class="glyphicon glyphicon-chevron-up"></span>
                    </a>
                </div>
            </aside>
                
            <form id="form_make_activity" method="post">
                {{Form::token()}}
                <input name="idsolicitude" type="hidden" value="{{$solicitude->id}}">
                <input name="token" type="hidden" value="{{$solicitude->token}}">
                <section id="collapseOne" class="row reg-expense collapse in">
            
                @include('template.detail_solicitude')

                </section>

                @if ( Auth::user()->type == CONT && $solicitude->idestado == DEPOSITADO )
                    @include('template.Seat.advance_table')
                @endif

                <!-- Button (Double) -->
                @include('template.Details.buttons')

            </form>
        </div>
    </div>
</div>
@stop