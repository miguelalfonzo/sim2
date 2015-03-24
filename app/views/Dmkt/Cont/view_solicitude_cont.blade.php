@extends('template.main')
@section('content')
    <div class="content">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Ver Solicitud Contabilidad</h3><strong class="user">Usuario : {{Auth::user()->username}}</strong>
            </div>
            <div class="panel-body">
                <form id="form_enable_deposit" class="" method="post" action="{{URL::to('enable-deposit')}}">

                    {{Form::token()}}
                    <div class="form-group col-sm-6 col-md-4">
                        <label class="col-sm-8 col-md-8 control-label" for="textinput">Código de Solicitud</label>
                        <div class="col-sm-12 col-md-12">
                            <input id="textinput" name="idsolicitud" type="text" value="{{$solicitude->idsolicitud}}" class="form-control input-md" readonly>
                            <input type="hidden" id="token" value="{{$solicitude->token}}">
                        </div>
                    </div>
                    <div class="form-group col-sm-6 col-md-4">
                        <label class="col-sm-8 col-md-8 control-label" for="textinput">Tipo de Solicitud</label>
                        <div class="col-sm-12 col-md-12">
                            <input id="textinput" name="textinput" type="text" placeholder="" value="{{$solicitude->typesolicitude->nombre}}" readonly class="form-control input-md">
                            <input type="hidden" id="token" value="{{$solicitude->token}}">
                        </div>
                    </div>
                    <div class="form-group col-sm-6 col-md-4">
                        <label class="col-sm-8 col-md-8 control-label" for="textinput">Tipo de Pago</label>
                        <div class="col-sm-12 col-md-12">
                            <input id="textinput" name="textinput" type="text" placeholder="" value="{{$solicitude->typePayment->nombre}}" readonly class="form-control input-md">
                        </div>
                    </div>
                    <div class="form-group col-sm-6 col-md-4">
                        <label class="col-sm-8 col-md-8 control-label" for="textinput">Nombre Solicitud</label>
                        <div class="col-sm-12 col-md-12">
                            <input id="textinput" type="text" value="{{$solicitude->titulo}}"class="form-control input-md" readonly>
                        </div>
                    </div>
                    <div class="form-group col-sm-6 col-md-4">
                        <label class="col-sm-8 col-md-8 control-label" for="textinput">Monto Solicitado</label>
                        <div class="col-sm-12 col-md-12">
                            @if($solicitude->estado == 2)
                            <div class="input-group">
                                <span class="input-group-addon">{{$solicitude->typemoney->simbolo}}</span>
                                <input id="idamount" type="text" value="{{$solicitude->monto}}" class="form-control input-md">
                            </div>
                            @else
                            <div class="input-group">
                                <span class="input-group-addon">{{$solicitude->typemoney->simbolo}}</span>
                                <input id="idamount" type="text" value="{{$solicitude->monto}}" class="form-control input-md" readonly>
                            </div>
                            @endif
                        </div>
                    </div>


                    <div class="form-group col-sm-6 col-md-4">
                        <label class="col-sm-8 col-md-8 control-label" for="selectbasic">Fondo</label>
                        <div class="col-sm-12 col-md-12">
                            <input id="textinput" type="text" value="{{$solicitude->subtype->nombre_mkt}}" class="form-control input-md" readonly>
                        </div>
                    </div>
                    <div class="form-group col-sm-6 col-md-4">
                        <label class="col-sm-8 col-md-8 control-label" for="textinput">Fecha de Entrega</label>
                        <div class="col-sm-12 col-md-12">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                <input id="date" type="text" value="{{ date_format(date_create($solicitude->fecha_entrega), 'd/m/Y' )}}" class="form-control" maxlength="10" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-sm-6 col-md-4 col-lg-4">
                        <label class="col-sm-8 col-md-8 col-lg-8 control-label" for="textinput">Solicitante</label>
                        <div class="col-sm-12 col-md-12 col-lg-12">
                            <div class="input-group">
                                @if(!is_null($solicitude->iduser))
                                    @if($solicitude->user->type == 'R')
                                    <span class="input-group-addon">Representante Medico</span>
                                    <input id="textinput" type="text" value="{{$solicitude->user->rm->nombres.' '.$solicitude->user->rm->apellidos}}" class="form-control input-md" readonly>
                                    @elseif($solicitude->user->type == 'S')
                                    <span class="input-group-addon">Supervisor</span>
                                    <input id="textinput" type="text" value="{{$solicitude->user->sup->nombres.' '.$solicitude->user->sup->apellidos}}" class="form-control input-md" readonly>
                                    @else
                                    <span class="input-group-addon">$solicitude->user->type</span>
                                    <input id="textinput" type="text" value="Usuario no autorizado" class="form-control input-md" readonly>
                                    @endif
                                @else
                                    <span class="input-group-addon">Inexistente</span>
                                    <input id="textinput" type="text" value="No existe el usuario solicitante" class="form-control input-md" readonly> 
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    @include('template.obs')

                    <div class="col-sm-18 col-md-12">
                    <div class="form-group col-sm-9 col-md-6">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Productos</h3>
                                </div>
                                <div class="panel-body">
                                    <?php $t=0;?>
                                    @foreach($solicitude->families as $family)
                                    <div class="form-group col-sm-12 col-md-12" style="padding: 0">
                                        <div class="col-sm-8 col-md-8">
                                            <input id="textinput" type="text" value="{{$family->marca->descripcion}}" class="form-control input-md" readonly>

                                        </div>
                                        <div class="col-sm-4 col-md-4" style="padding: 0">
                                            <div class="input-group">
                                                <span class="input-group-addon">{{$solicitude->typemoney->simbolo}}</span>
                                                <input id="" readonly type="text" class="form-control input-md amount_families" value="{{isset($family->monto_asignado)? $family->monto_asignado : round($solicitude->monto/count($solicitude->families),2)}}">
                                            </div>
                                        </div>
                                    </div>
                                    <?php $t = $t + round($solicitude->monto/count($solicitude->families),2)?>
                                    @endforeach
                                    <div class="form-group col-sm-12 col-md-12">
                                        <span id="amount_error_families"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @include('template.list_clients')
                </div                                                                                                                                                                                                                                                                                 >


                    <div class="col-sm-12 col-md-12" style="margin-top: 10px">
                        <div class="form-group col-sm-12 col-md-12">
                            <label class="col-sm-8 col-md-8 control-label" for="textarea">Descripcion de la Solicitud</label>
                            <div class="col-sm-12 col-md-12">
                                <textarea class="form-control" id="textarea" readonly>{{$solicitude->descripcion}}</textarea>
                            </div>
                        </div>
                    </div>
                    <!-- Cálculos Retención Contabilidad -->
                    @if($solicitude->asiento != 1 && $solicitude->estado == APROBADO)
                        <?php $i=0;?>
                        @foreach($typeRetention as $retention)
                            <div class="form-group col-sm-6 col-md-4">
                                <label class="col-sm-8 col-md-8 control-label" for="textinput">{{mb_convert_case($retention->descripcion." ".$retention->porcentaje, MB_CASE_TITLE, 'UTF-8')}}</label>
                                <div class="col-sm-12 col-md-12">
                                    <div class="input-group">
                                        <span class="input-group-addon">{{$solicitude->typemoney->simbolo}}</span>
                                        <input id="ret{{$i}}" name="ret{{$i}}" type="text" class="form-control input-md ret">
                                    </div>
                                </div>
                            </div> 
                            <?php $i++;?>
                        @endforeach
                    @endif
                    <!-- Button (Double) -->
                    <div class="form-group col-sm-12 col-md-12" style="margin-top: 20px">
                        <div class="col-sm-12 col-md-12" style="text-align: center">
                            @if($solicitude->asiento != 1 && $solicitude->estado == APROBADO)
                                <a id="enable-deposit" href="#" class="btn btn-success" style="margin-right: 1em;">Habilitar Depósito</a>
                            @endif
                            <a id="button2id" href="{{URL::to('show_user')}}" name="button2id" class="btn btn-primary">Cancelar</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@stop