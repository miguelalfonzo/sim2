@extends('template.main')
@section('content')
    <div class="content">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Ver Solicitud Contabilidad</h3>
            </div>
            <div class="panel-body">
                <form id="form_make_activity" class="" method="post" action="">
                    <div class="form-group col-sm-6 col-md-4">
                        <label class="col-sm-8 col-md-8 control-label" for="textinput">Tipo Solicitud</label>
                        <div class="col-sm-12 col-md-12">
                            <input id="textinput" name="textinput" type="text" value="{{$solicitude->typesolicitude->nombre}}" class="form-control input-md" readonly>
                            <input type="hidden" id="token" value="{{$solicitude->token}}">
                        </div>
                    </div>
                    <div class="form-group col-sm-6 col-md-4">
                        <label class="col-sm-8 col-md-8 control-label" for="textinput">Nombre Solicitud</label>
                        <div class="col-sm-12 col-md-12">
                            <input id="textinput" name="titulo" type="text" value="{{$solicitude->titulo}}"class="form-control input-md" readonly>
                        </div>
                    </div>
                    <div class="form-group col-sm-6 col-md-4">
                        <label class="col-sm-8 col-md-8 control-label" for="textinput">Monto Solicitado</label>
                        <div class="col-sm-12 col-md-12">
                            @if($solicitude->estado == 2)
                            <div class="input-group">
                                <span class="input-group-addon">{{$solicitude->typemoney->simbolo}}</span>
                                <input id="idamount" name="monto" type="text" value="{{$solicitude->monto}}" class="form-control input-md">
                            </div>
                            @else
                            <div class="input-group">
                                <span class="input-group-addon">{{$solicitude->typemoney->simbolo}}</span>
                                <input id="idamount" name="monto" type="text" value="{{$solicitude->monto}}" class="form-control input-md" readonly>
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
                                            <input id="textinput" name="textinput" type="text" value="{{$family->marca->descripcion}}" class="form-control input-md" readonly>

                                        </div>
                                        <div class="col-sm-4 col-md-4" style="padding: 0">
                                            <div class="input-group">
                                                <span class="input-group-addon">{{$solicitude->typemoney->simbolo}}</span>
                                                <input id="" readonly name="amount_assigned[]" type="text" class="form-control input-md amount_families" value="{{isset($family->monto_asignado)? $family->monto_asignado : round($solicitude->monto/count($solicitude->families),2)}}">
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
                            <input id="textinput" name="textinput" type="text" value="{{$solicitude->subtype->nombre}}" class="form-control input-md" readonly>
                        </div>
                    </div>
                    <div class="form-group col-sm-6 col-md-4">
                        <label class="col-sm-8 col-md-8 control-label" for="textinput">Fecha de Entrega</label>
                        <div class="col-sm-12 col-md-12">
                            <div class="input-group date">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                <input id="date" type="text" name="delivery_date" value="{{ date_format(date_create($solicitude->fecha_entrega), 'd/m/Y' )}}" class="form-control" maxlength="10" readonly>
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
                                            <input id="textinput" name="textinput" type="text" value="{{$client->client->clnombre}}" class="form-control input-md" readonly>
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
                            <textarea id="textinput" name="observacion" class="form-control"></textarea>
                            @else
                            <textarea id="textinput" name="observacion" class="form-control" disabled></textarea>
                            @endif
                        </div>
                    </div>
                    <div class="form-group col-sm-6 col-md-4 col-lg-4">
                        <label class="col-sm-8 col-md-8 col-lg-8 control-label" for="textinput">Solicitante</label>
                        <div class="col-sm-12 col-md-12 col-lg-12">
                            <div class="input-group">
                                @if($solicitude->user->type == 'R')
                                <span class="input-group-addon">R</span>
                                <input id="textinput" name="titulo" type="text" value="{{$solicitude->user->rm->nombres}}" class="form-control input-md" readonly>
                                @else
                                <span class="input-group-addon">S</span>
                                <input id="textinput" name="titulo" type="text" value="{{$solicitude->user->sup->nombres}}" class="form-control input-md" readonly>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-12" style="margin-top: 10px">
                        <div class="form-group col-sm-12 col-md-12">
                            <label class="col-sm-8 col-md-8 control-label" for="textarea">Descripcion de la Solicitud</label>
                            <div class="col-sm-12 col-md-12">
                                <textarea class="form-control" id="textarea" name="textarea" readonly>{{$solicitude->descripcion}}</textarea>
                            </div>
                        </div>
                    </div>
                    <!-- Cálculos Contabilidad -->
                    <div class="form-group col-sm-6 col-md-4">
                        <label class="col-sm-8 col-md-8 control-label" for="textinput">Retención 6%</label>
                        <div class="col-sm-12 col-md-12">
                            <div class="input-group">
                                <span class="input-group-addon">{{$solicitude->typemoney->simbolo}}</span>
                                <input id="ret1" type="text" class="form-control input-md">
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-sm-6 col-md-4">
                        <label class="col-sm-8 col-md-8 control-label" for="textinput">Detracción 0%</label>
                        <div class="col-sm-12 col-md-12">
                            <div class="input-group">
                                <span class="input-group-addon">{{$solicitude->typemoney->simbolo}}</span>
                                <input id="ret2" type="text" class="form-control input-md">
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-sm-6 col-md-4">
                        <label class="col-sm-8 col-md-8 control-label" for="textinput">Retención 10%</label>
                        <div class="col-sm-12 col-md-12">
                            <div class="input-group">
                                <span class="input-group-addon">{{$solicitude->typemoney->simbolo}}</span>
                                <input id="ret3" type="text" class="form-control input-md">
                            </div>
                        </div>
                    </div>
                    <!-- Button (Double) -->
                    <div class="form-group col-sm-12 col-md-12" style="margin-top: 20px">
                        <div class="col-sm-12 col-md-12" style="text-align: center">
                            <a id="enable-deposit" href="#" class="btn btn-success">Habilitar Deposito</a>
                            <a id="button2id" href="{{URL::to('show_cont')}}" name="button2id" class="btn btn-primary">Cancelar</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@stop