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
            <form id="form_make_activity" method="post">
                {{Form::token()}}
                <input name="idsolicitude" type="hidden" value="{{$solicitude->id}}">
                <input name="token" type="hidden" value="{{$solicitude->token}}">
                @include('template.detail_solicitude')

                <!-- RETENCION -->
                @if( Auth::user()->type == CONT && $solicitude->idestado == APROBADO )
                <div class="col-sm-12 col-md-12">
                    <label class="col-sm-12 col-md-12 control-label" for="textinput">Retencion</label>
                    <div class="col-sm-3 col-md-3">
                        <select name="retencion" class="form-control">
                        @foreach($typeRetention as $retention)
                            <option value="{{$retention->idtiporetencion}}">
                                {{$retention->descripcion}}
                            </option>
                        @endforeach
                        </select>
                    </div>
                    <div class="form-group col-sm-3 col-md-3">
                        <div class="input-group">
                            <span class="input-group-addon">{{$solicitude->detalle->typemoney->simbolo}}</span>
                            <input name="monto_retencion" type="text" class="form-control input-md ret">
                        </div>
                    </div>
                </div> 
                @endif
                
                <!-- Button (Double) -->
                <div class="form-group col-sm-12 col-md-12 col-lg-12" style="margin-top: 20px">
                    <div class="col-sm-12 col-md-12 col-lg-12" style="text-align: center">
                        @if ( Auth::user()->type == SUP )
                            @if( $solicitude->idestado == PENDIENTE )
                                <a class="btn btn-primary" id="search_responsable">
                                    Aceptar
                                </a>
                                <a id="deny_solicitude" name="button1id" class="btn btn-primary deny_solicitude">
                                    Rechazar
                                </a>
                            @endif
                        @elseif ( Auth::user()->type == GER_PROD )
                            @if($solicitude->idestado == DERIVADO)
                                <a id="search_responsable" class="btn btn-primary">
                                    Aceptar
                                </a>
                                <a id="deny_solicitude" name="button1id" class="btn btn-primary deny_solicitude_gerprod">
                                    Rechazar
                                </a>
                            @endif
                        @elseif ( Auth::user()->type == GER_COM )
                            @if($solicitude->idestado == ACEPTADO)
                                <a name="button1id" data-token ="{{$solicitude->token}}" class="btn btn-primary approved_solicitude">
                                    Aprobar
                                </a>
                                <a id="deny_solicitude" name="button1id" class="btn btn-primary deny_solicitude">
                                    Rechazar
                                </a>
                            @endif
                        @elseif ( Auth::user()->type == CONT )
                            @if($solicitude->idestado == APROBADO)
                                <a id="enable-deposit" class="btn btn-success" style="margin-right: 1em;">Habilitar Depósito</a>
                            @endif
                        @elseif ( Auth::user()->type == TESORERIA )
                            <button class="btn btn-success" data-toggle="modal" data-target="#myModal" >Registrar Depósito</button>
                        @endif
                        <a id="button2id" href="{{URL::to('show_user')}}" name="button2id" class="btn btn-primary">
                            Cancelar
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@stop