@extends('template.main')
@section('content')
<div class="content">
    <input type="hidden" id="state_view" value="{{isset($state) ? $state : PENDIENTE}}">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Nueva Solicitud</h3>
            @if(isset($solicitude) && $solicitude->blocked == 1)
                <h4 class="" style="color: darkred">LA SOLICITUD ESTA SIENDO EVALUADA</h4>
            @endif
            <small style="float: right; margin-top: -10px">
                <strong>Usuario : 
                @if( Auth::user()->type == REP_MED ) 
                    {{Auth::user()->rm->full_name}}
                @else
                    {{Auth::user()->sup->full_name}} 
                @endif
                </strong>
            </small>
        </div>
        <div class="panel-body">
            <form id="form-register-solicitude" class="" method="post" 
            action="{{isset($solicitude) ? 'editar-solicitud' : 'registrar-solicitud' }}" enctype="multipart/form-data">
            {{Form::token()}}
            @if(isset($solicitude))
                <input value="{{$solicitude->id}}" name="idsolicitude" type="hidden">
            @endif
            <input id="typeUser" type="hidden" value="{{Auth::user()->type}}">
            <div class="form-group col-sm-6 col-md-4">
                <label class="col-sm-8 col-md-8 control-label" for="textinput">Nombre Solicitud</label>
                <div class="col-sm-12 col-md-12">
                    <input id="idtitle" class="form-control input-md" name="titulo" type="text" placeholder=""
                    value="{{isset($solicitude->titulo)? $solicitude->titulo : null }}">
                </div>
            </div>
            

            <div class="form-group col-sm-6 col-md-4">
                <label class="col-sm-8 col-md-8 control-label" for="textinput">Tipo Solicitud</label>
                <div class="col-sm-12 col-md-12">
                    <select class="form-control selecttypesolicitude" name="type_solicitude">
                        @foreach($typesolicituds as $type)
                            @if(isset($solicitude) && $solicitude->detalle->idmotivo == $type->id)
                                <option selected value="{{$type->id}}">{{$type->nombre}}</option>
                            @else
                                <option value="{{$type->id}}">{{$type->nombre}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- LABEL -->
            @include('Dmkt.Register.label')
            
            <!-- TIPO DE PAGO -->
            <div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
                <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12 control-label" for="textinput">Tipo de Pago</label>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <select name="pago" class="form-control selectTypePayment">
                        @foreach($typePayments as $type)
                            @if(isset($solicitude) && $solicitude->detalle->idpago == $type->id)
                                <option selected value="{{$type->id}}">{{$type->nombre}}</option>
                            @else
                                <option value="{{$type->id}}">{{$type->nombre}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4" id="div_ruc">
                <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12 control-label" for="textinput">Ruc</label>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <input id="ruc" class="form-control input-md" maxlength="11" name="ruc" type="text"
                           value="{{isset($detalle->num_ruc) ? $detalle->num_ruc : null }}">
                </div>
            </div>
            <div id="div_number_account" class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
                <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12 control-label" for="textinput">Nº de Cuenta</label>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <input id="number_account" class="form-control input-md" name="number_account" type="text"
                           value="{{isset($detalle->num_cuenta) ? $detalle->num_cuenta : null }}">
                </div>
            </div>
            <div class="form-group col-sm-6 col-md-4">
                <label class="col-sm-8 col-md-8 control-label" for="textinput">Monto Solicitado</label>
                <div class="col-sm-12 col-md-12">
                    <input id="idestimate" class="form-control input-md" name="monto" type="text" placeholder=""
                           value="{{isset($solicitude->detalle->detalle) ? json_decode($solicitude->detalle->detalle)->monto_solicitado : null }}">
                </div>
            </div>

            <!-- Moneda -->
            @include('template.Change.moneda')
            
            <div class="solicitude_monto form-group col-sm-6 col-md-4">
                <label class="col-sm-8 col-md-8 control-label" for="textinput">Monto Factura</label>
                <div class="col-sm-12 col-md-12">
                    <input id="amountfac" class="form-control input-md" name="amount_fac" type="text"
                    value="{{isset($detalle->monto_factura) ? $detalle->monto_factura : null }}">
                </div>
            </div>
            
            <!-- Factura Imagen -->
            @include('template.Change.image')
        
            <div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
                <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12 control-label" for="textinput">Fecha de Entrega</label>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="input-group date">
                        <span class="input-group-addon">
                            <i class="glyphicon glyphicon-calendar"></i>
                        </span>
                        <input id="delivery_date" type="text" name="fecha" class="form-control" maxlength="10" readonly
                        value="{{ isset( $solicitude ) ? $detalle->fecha_entrega : null }}">
                    </div>
                </div>
            </div>
            
            <div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
                <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12 control-label" for="textinput">Cliente</label>
                <ul id="listclient" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    @if(isset($solicitude))
                        @foreach($solicitude->clients as $client)
                            <li>
                                <div style="position: relative" class="has-success has-feedback">
                                        @if($client->from_table == TB_DOCTOR)
                                            <input type='text' id="idclient0" name="clientes[]" type="text" style="margin-bottom: 10px"
                                               class="form-control input-md project input-client cliente-seeker" pk="{{$client->idcliente}}"
                                               value="DOCTOR: {{$client->doctors->pefnrodoc1.'-'.$client->doctors->pefnombres.' '.$client->doctors->pefpaterno.' '.$client->doctors->pefmaterno}}"
                                               data-valor="all" table="{{$client->from_table}}" disabled>
                                        @elseif($client->from_table == TB_INSTITUTE)
                                            <input type='text' id="idclient0" name="clientes[]" type="text" style="margin-bottom: 10px"
                                               class="form-control input-md project input-client cliente-seeker" pk="{{$client->idcliente}}"
                                               value="CENTRO: {{$client->institutes->pejnrodoc.'-'.$client->instituteS->pejrazon}}"
                                               data-valor="all" table="{{$client->from_table}}" disabled>
                                        @else
                                            <input id="idclient0" name="clientes[]" type="text" style="margin-bottom: 10px"
                                               class="form-control input-md project input-client cliente-seeker" pk="{{$client->client->clcodigo}}"
                                               value='{{isset($client->client->clnombre) ? $client->client->clcodigo.' - '.$client->client->clnombre : null }}'
                                               data-valor="all" table="VTA.CLIENTES" disabled>
                                        @endif
                                    <button type='button' class='btn-delete-client' style="z-index: 2">
                                        <span class='glyphicon glyphicon-remove'></span>
                                    </button>
                                </div>
                            </li>
                        @endforeach
                    @else
                        <li>
                            <div style="position: relative" class="">
                                    <input id="idclient0" name="clientes[]" type="text" placeholder="" style="margin-bottom: 10px"
                                           class="form-control input-md input-client cliente-seeker" data-valor=""
                                           value="{{isset($client->clnombre) ? $client->clcodigo.' - '.$client->clnombre : null }}">
                                <button type='button' class='btn-delete-client' style="display: none; z-index: 2">
                                    <span class='glyphicon glyphicon-remove'></span>
                                </button>
                            </div>
                        </li>
                    @endif
                </ul>
                <span class="col-sm-10 col-md-10 clients_repeat" style=""></span>
                <button type="button" class="btn btn-default" id="btn-add-client">Agregar Otro Cliente</button>
            </div>

            <!-- Productos -->
            @include('template.Change.productos')
                
            <!-- Ver Comprobante -->
            @include('template.Change.comprobante')
           
            <div class="col-sm-12 col-md-12" style="margin-top: 10px">
                <div class="form-group ">
                    <label class="col-sm-8 col-md-8 control-label" for="textarea">Descripcion de la Solicitud</label>
                    <div class="col-sm-12 col-md-12">
                        <textarea class="form-control" id="iddescriptionsolicitude" name="descripcion">{{isset($solicitude->descripcion) ? $solicitude->descripcion : null}}</textarea>
                    </div>
                </div>
            </div>

            <!-- Button (Double) -->
            <div class="form-group col-sm-12 col-md-12" style="margin-top: 20px">
                <div class="col-sm-12 col-md-12" style="text-align: center">
                    @if(isset($solicitude))
                        @if($solicitude->blocked == 0)
                            <button id="button1id" name="button1id" class="btn btn-primary">
                                {{isset($solicitude) ? 'Actualizar' : 'Crear'}}
                            </button>
                            <a id="button2id" href="{{URL::to('show_user')}}" name="button2id" class="btn btn-primary">Cancelar</a>
                        @else
                            <a id="button2id" href="{{URL::to('show_user')}}" name="button2id" class="btn btn-primary">Cancelar</a>
                        @endif
                    @else
                        <button id="button1id" name="button1id" class="btn btn-primary register_solicitude">
                            {{isset($solicitude) ? 'Actualizar' : 'Crear'}}
                        </button>
                        <a id="button2id" href="{{URL::to('show_user')}}" name="button2id" class="btn btn-primary">Cancelar</a>
                    @endif
                </div>
            </div>
        </form>
        </div>
    </div>
</div>
@stop
