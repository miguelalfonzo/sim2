@extends('template.main')
@section('content')
    <div class="content">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Ver Solicitud Tesorería</h3><strong class="user">Usuario : {{Auth::user()->username}}</strong>
            </div>
            <div class="panel-body">
                <div class="form-group col-sm-6 col-md-4">
                    <label class="col-sm-8 col-md-8 control-label" for="textinput">Código de Solicitud</label>
                    <div class="col-sm-12 col-md-12">
                        <input id="textinput" name="idsolicitud" type="text" value="{{$solicitude->idsolicitud}}" class="form-control input-md" readonly>
                        <input type="hidden" id="token" value="{{$solicitude->token}}">
                        {{Form::token()}}
                    </div>
                </div>
                <div class="form-group col-sm-6 col-md-4">
                    <label class="col-sm-8 col-md-8 control-label" for="textinput">Nombre Solicitud</label>
                    <div class="col-sm-12 col-md-12">
                        <input id="textinput" type="text" value="{{$solicitude->titulo}}"class="form-control input-md" readonly>
                    </div>
                </div>
                <div class="form-group col-sm-6 col-md-4">
                    <label class="col-sm-8 col-md-8 control-label" for="selectbasic">Fondo</label>
                    <div class="col-sm-12 col-md-12">
                        <input id="textinput" type="text" value="{{$solicitude->subtype->nombre_mkt}}" class="form-control input-md" readonly>
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
                @if (isset($solicitude->retencion))
                    @if (!$solicitude->retencion == null)
                        <div class="form-group col-sm-6 col-md-4">
                            <label class="col-sm-8 col-md-8 control-label" for="textinput">Retenciones</label>
                            <div class="col-sm-12 col-md-12">
                                @if($solicitude->estado == 2)
                                <div class="input-group">
                                    <span class="input-group-addon">{{$solicitude->typemoney->simbolo}}</span>
                                    <input type="text" value="{{$solicitude->retencion}}" class="form-control input-md">
                                </div>
                                @else
                                <div class="input-group">
                                    <span class="input-group-addon">{{$solicitude->typemoney->simbolo}}</span>
                                    <input id="idamount" type="text" value="{{$solicitude->retencion}}" class="form-control input-md" disabled>
                                </div>
                                @endif
                            </div>
                        </div>
                    @endif
                @endif
                <div class="form-group col-sm-6 col-md-4">
                    <label class="col-sm-8 col-md-8 control-label" for="textinput">Monto a Depositar</label>
                    <div class="col-sm-12 col-md-12">
                        <div class="input-group">
                            <span class="input-group-addon">{{$solicitude->typemoney->simbolo}}</span>
                            @if($solicitude->estado == 2)
                                <input type="text" value="{{($solicitude->monto -$solicitude->retencion)}}" class="form-control input-md">
                            @else
                                <input type="text" disabled="true" value="{{($solicitude->monto -$solicitude->retencion)}}" class="form-control input-md">
                            @endif
                        </div>
                    </div>
                </div>

                
                
                <div class="form-group col-sm-6 col-md-4">
                    <label class="col-sm-8 col-md-8 control-label" for="textinput">Fecha de Entrega</label>
                    <div class="col-sm-12 col-md-12">
                        <div class="input-group date">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                            <input id="date" type="text" value="{{ date_format(date_create($solicitude->fecha_entrega), 'd/m/Y' )}}" class="form-control" maxlength="10" disabled>
                        </div>
                    </div>
                </div>
                @if (isset($solicitude->iduser))
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
                    </div>
                    <div class="col-sm-12 col-md-12" style="margin-top: 10px">
                        <div class="form-group col-sm-12 col-md-12">
                            <label class="col-sm-8 col-md-8 control-label" for="textarea">Descripcion de la Solicitud</label>
                            <div class="col-sm-12 col-md-12">
                                <textarea class="form-control" id="textarea" readonly>{{$solicitude->descripcion}}</textarea>
                            </div>
                        </div>
                    </div>
                @endif
                <!-- Button (Double) -->
                <div class="form-group col-sm-12 col-md-12" style="margin-top: 20px">
                    <div class="col-sm-12 col-md-12" style="text-align: center">
                        @if($solicitude->asiento == ENABLE_DEPOSIT && !is_null($solicitude->idresponse) && $solicitude->estado != DEPOSITADO )
                            <button class="btn btn-success" data-toggle="modal" data-target="#myModal" >Registrar Depósito</button>
                        @endif
                        <a id="button2id" href="{{URL::to('show_user')}}" name="button2id" class="btn btn-primary">Cancelar</a>
                    </div>
                </div>
                <!-- Modal -->
                @if($solicitude->asiento == ENABLE_DEPOSIT && !is_null($solicitude->idresponse) && $solicitude->estado != DEPOSITADO )
                <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                <h4 class="modal-title" id="myModalLabel">Registro del Depósito</h4>
                            </div>
                            <div class="modal-body">
                                <label for="op-number">Número de Operación, Transacción, Cheque:</label>
                                <input id="op-number" type="text" class="form-control">
                                <p id="message-op-number" style="margin-top:1em;color:#a94442;"></p>
                            </div>
                            <div class="modal-footer">
                                <!-- <a id="register-deposit" href="{{URL::to('depositar')}}/{{$solicitude->token}}" class="btn btn-success" style="margin-right: 1em;">Confirmar Operación</a> -->
                                <a id="" href="#" class="btn btn-success register-deposit" data-deposit = "solicitude" style="margin-right: 1em;">Confirmar Operación</a>
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
@stop