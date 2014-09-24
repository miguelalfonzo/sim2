@extends('template.main')
@section('content')
    <div class="content">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Ver Solicitud Tesorería</h3>
            </div>
            <div class="panel-body">
                <div class="form-group col-sm-6 col-md-4">
                    <label class="col-sm-8 col-md-8 control-label" for="textinput">Código de Solicitud</label>
                    <div class="col-sm-12 col-md-12">
                        <input id="textinput" name="idsolicitud" type="text" value="{{$solicitude->idsolicitud}}" class="form-control input-md" readonly>
                        <input type="hidden" id="token" value="{{$solicitude->token}}">
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
                <div class="form-group col-sm-12 col-md-4">
                    <div class=col-md-12>
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
                <div class="form-group col-sm-6 col-md-4">
                    <label class="col-sm-8 col-md-8 control-label" for="selectbasic">Fondo</label>
                    <div class="col-sm-12 col-md-12">
                        <input id="textinput" type="text" value="{{$solicitude->subtype->nombre}}" class="form-control input-md" readonly>
                    </div>
                </div>
                <div class="form-group col-sm-6 col-md-4">
                    <label class="col-sm-8 col-md-8 control-label" for="textinput">Fecha de Entrega</label>
                    <div class="col-sm-12 col-md-12">
                        <div class="input-group date">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                            <input id="date" type="text" value="{{ date_format(date_create($solicitude->fecha_entrega), 'd/m/Y' )}}" class="form-control" maxlength="10" readonly>
                        </div>
                    </div>
                </div>
                <div class="form-group col-sm-6 col-md-4">
                    <div class=col-md-12>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">Clientes</h3>
                            </div>
                            <div class="panel-body">
                                @foreach($solicitude->clients as $client)
                                <div class="form-group" style="padding: 0 10px">
                                    <div class="">
                                        <input id="textinput" type="text" value="{{$client->client->clnombre}}" class="form-control input-md" readonly>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group col-sm-6 col-md-4">
                    <label class="col-sm-8 col-md-8 control-label" for="textinput">Observacion</label>
                    <div class="col-sm-12 col-md-12">
                        @if($solicitude->estado == 2)
                        <textarea id="textinput" class="form-control"></textarea>
                        @else
                        <textarea id="textinput" class="form-control" disabled></textarea>
                        @endif
                    </div>
                </div>
                <div class="form-group col-sm-6 col-md-4 col-lg-4">
                    <label class="col-sm-8 col-md-8 col-lg-8 control-label" for="textinput">Solicitante</label>
                    <div class="col-sm-12 col-md-12 col-lg-12">
                        <div class="input-group">
                            @if($solicitude->user->type == 'R')
                            <span class="input-group-addon">R</span>
                            <input id="textinput" type="text" value="{{$solicitude->user->rm->nombres}}" class="form-control input-md" readonly>
                            @else
                            <span class="input-group-addon">S</span>
                            <input id="textinput" type="text" value="{{$solicitude->user->sup->nombres}}" class="form-control input-md" readonly>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-12" style="margin-top: 10px">
                    <div class="form-group col-sm-12 col-md-12">
                        <label class="col-sm-8 col-md-8 control-label" for="textarea">Descripcion de la Solicitud</label>
                        <div class="col-sm-12 col-md-12">
                            <textarea class="form-control" id="textarea" readonly>{{$solicitude->descripcion}}</textarea>
                        </div>
                    </div>
                </div>
                <!-- Button (Double) -->
                <div class="form-group col-sm-12 col-md-12" style="margin-top: 20px">
                    <div class="col-sm-12 col-md-12" style="text-align: center">
                        <button class="btn btn-success" data-toggle="modal" data-target="#myModal">Registrar Depósito</button>
                        <a id="button2id" href="{{URL::to('show_tes')}}" name="button2id" class="btn btn-primary">Cancelar</a>
                    </div>
                </div>
                <!-- Modal -->
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
                                <a id="register-deposit" href="#" class="btn btn-success" style="margin-right: 1em;">Confirmar Operación</a>
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop