@extends('template.main')
@section('content')

<div class="content">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Ver Solicitud Gerente Comercial</h3>
        </div>
        <div class="panel-body">
            <form id="form_make_activity" class="" method="post" action="">
            {{Form::token()}}
            <input type="hidden" value="{{$solicitude->token}}" name="token">
            <input id="textinput" name="idsolicitude" type="hidden" placeholder="" value="{{$solicitude->idsolicitud}}">
            <div class="form-group col-sm-6 col-md-4">
                <label class="col-sm-8 col-md-8 control-label" for="textinput">Tipo Solicitud</label>
                <div class="col-sm-12 col-md-12">
                    <input id="textinput" name="textinput" type="text" placeholder="" value="{{$solicitude->typesolicitude->nombre}}" readonly
                    class="form-control input-md">
                </div>
            </div>
            <div class="form-group col-sm-6 col-md-4">
                <label class="col-sm-8 col-md-8 control-label" for="textinput">Nombre Solicitud</label>
                <div class="col-sm-12 col-md-12">
                    <input id="textinput" name="titulo" type="text" placeholder="" value="{{$solicitude->titulo}}" readonly
                    class="form-control input-md">
                </div>
            </div>
            <div class="form-group col-sm-6 col-md-4">
                <label class="col-sm-8 col-md-8 control-label" for="textinput">Monto Solicitado</label>
                <div class="col-sm-12 col-md-12">
                    <div class="input-group">
                        <span class="input-group-addon">{{$solicitude->typemoney->simbolo}}</span>
                        <input id="idamount" name="monto" type="text" placeholder="" value="{{$solicitude->monto}}" class="form-control input-md" >
                    </div>
                </div>
            </div>
            <div class="form-group col-sm-6 col-md-4">
                <label class="col-sm-8 col-md-8 control-label" for="selectbasic">Fondo</label>
                <div class="col-sm-12 col-md-12">
                    <input id="textinput" name="textinput" type="text" placeholder="" value="{{$solicitude->subtype->nombre_mkt}}" readonly
                    class="form-control input-md">
                </div>
            </div>
            <div class="form-group col-sm-6 col-md-4">
                <label class="col-sm-8 col-md-8 control-label" for="textinput">Fecha de Entrega</label>
                <div class="col-sm-12 col-md-12">
                    <div class="input-group date">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                        <input id="date" type="text" name="delivery_date" value="{{ date_format(date_create($solicitude->fecha_entrega), 'd/m/Y' )}}"
                        class="form-control" maxlength="10" disabled placeholder="">
                    </div>
                </div>
            </div>
            <div class="form-group col-sm-6 col-md-4 col-lg-4">
                <label class="col-sm-8 col-md-8 col-lg-8 control-label" for="textinput">Solicitante</label>
                <div class="col-sm-12 col-md-12 col-lg-12">
                    <div class="input-group">
                        @if(isset($solicitude->user->type))
                            @if($solicitude->user->type == 'R')
                            <span class="input-group-addon">Representante Medico</span>
                            <input id="textinput" name="titulo" type="text" placeholder=""
                                   value="{{ucwords($solicitude->user->rm->nombres.' '.$solicitude->user->rm->apellidos)}}" readonly
                                   class="form-control input-md">
                            @elseif($solicitude->user->type == 'S')
                            <span class="input-group-addon">Supervisor</span>
                            <input id="textinput" name="titulo" type="text" placeholder=""
                                   value="{{ucwords($solicitude->user->sup->nombres.' '.$solicitude->user->sup->apellidos)}}" readonly
                                   class="form-control input-md">
                            @else
                            <span class="input-group-addon">{{$solicitude->user->type}}</span>
                            <input id="textinput" name="titulo" type="text" placeholder=""
                                   value="{{ucwords($solicitude->user->person->nombres.' '.$solicitude->user->person->apellidos)}}" readonly
                                class="form-control input-md">
                            @endif
                        @else
                            <span class="input-group-addon">No Registrado</span>
                            <input id="textinput" name="titulo" type="text" placeholder=""
                                   value="Usuario no registrado" readonly
                                   class="form-control input-md">
                        @endif
                    </div>
                </div>
            </div>
            <div class="form-group col-sm-6 col-md-4">
                <label class="col-sm-8 col-md-8 control-label" for="textinput">Observacion</label>
                <div class="col-sm-12 col-md-12">
                    @if($solicitude->estado == 2)
                    <textarea id="textinput" name="observacion" placeholder="" class="form-control"></textarea>
                    @else
                    <textarea id="textinput" name="observacion" placeholder="" class="form-control" disabled>{{ $solicitude->observacion }}</textarea>
                    @endif
                </div>
            </div>
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
                                    <input id="textinput" name="textinput" type="text" placeholder="" value="{{$family->marca->descripcion}}" readonly
                                    class="form-control input-md">
                                </div>
                                <div class="col-sm-4 col-md-4" style="padding: 0">
                                    <div class="input-group">
                                        <span class="input-group-addon">{{$solicitude->typemoney->simbolo}}</span>
                                        <input id="amount_assigned"  name="amount_assigned[]" type="text" class="form-control input-md amount_families" value="{{isset($family->monto_asignado)? $family->monto_asignado : round($solicitude->monto/count($solicitude->families),2)}}">
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
            
            @include('template/list_clients')
            </div>
            
            <div class="col-sm-12 col-md-12" style="margin-top: 10px">
                <div class="form-group col-sm-12 col-md-12">
                    <label class="col-sm-8 col-md-8 control-label" for="textarea">Descripcion de la
                        Solicitud</label>
                    <div class="col-sm-12 col-md-12">
                        <textarea class="form-control" id="textarea" name="textarea" readonly>{{$solicitude->descripcion}}</textarea>
                    </div>
                </div>
            </div>
            <!-- Button (Double) -->
            <div class="form-group col-sm-12 col-md-12" style="margin-top: 20px">
                <div class="col-sm-12 col-md-12" style="text-align: center">
                    @if($solicitude->estado == ACEPTADO)
                    <a id="" name="button1id" data-token ="{{$solicitude->token}}"
                       class="btn btn-primary approved_solicitude">Aprobar
                    </a>
                    <a id="deny_solicitude_gercom" name="button1id" class="btn btn-primary deny_solicitude">Rechazar
                    </a>
                    <a id="button2id" href="{{URL::to('cancelar-solicitud-gercom').'/'.$solicitude->token}}" name="button2id"
           class="btn btn-primary">Cancelar</a>
                    @else
                    <a id="button2id" href="{{URL::to('cancelar-solicitud-gercom').'/'.$solicitude->token}}" name="button2id"
                       class="btn btn-primary">Cancelar</a>
                    @endif
                </div>
            </div>
            </form>
        </div>
    </div>
</div> 
@stop
