@extends('template.main')
@section('content')
    <div class="content">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Ver Solicitud Asistente de Gerencia</h3><strong class="user">Usuario : {{Auth::user()->username}}</strong>
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

                 <div class="form-group col-sm-6 col-md-4">
                    <label class="col-sm-8 col-md-8 control-label" for="textinput">Fecha de Entrega</label>
                    <div class="col-sm-12 col-md-12">
                        <div class="input-group date">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                            <input id="date" type="text" value="{{ date_format(date_create($solicitude->fecha_entrega), 'd/m/Y' )}}" class="form-control" maxlength="10" disabled>
                        </div>
                    </div>
                </div>

                <div class="form-group col-sm-6 col-md-4">
                    <label class="col-sm-8 col-md-8 control-label" for="textinput">Observacion</label>
                    <div class="col-sm-12 col-md-12">
                        @if($solicitude->estado == 2)
                        <textarea id="textinput" class="form-control"></textarea>
                        @else
                        <textarea id="textinput" class="form-control" disabled>{{ $solicitude->observacion }}</textarea>
                        @endif
                    </div>
                </div>

                <div class="form-group col-sm-12 col-md-6">
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
                
                
                <div class="col-sm-12 col-md-12" style="margin-top: 10px">
                    <div class="form-group col-sm-12 col-md-12">
                        <label class="col-sm-8 col-md-8 control-label" for="textarea">Descripcion de la Solicitud</label>
                        <div class="col-sm-12 col-md-12">
                            <textarea class="form-control" id="textarea" readonly>{{$solicitude->descripcion}}</textarea>
                        </div>
                    </div>
                </div>
                
                <div class="form-group col-sm-12 col-md-12" style="margin-top: 20px">
                    <div class="col-sm-12 col-md-12" style="text-align: center">
                        <a id="button2id" href="{{URL::to('registrar-fondo')}}" name="button2id" class="btn btn-primary">Cancelar</a>
                    </div>
                </div>
        
            </div>
        </div>
    </div>
@stop