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
                @if(Auth::user()->type == 'R') 
                    {{Auth::user()->rm->nombres.' '.Auth::user()->rm->apellidos}}
                @else 
                    {{Auth::user()->Sup->nombres.' '.Auth::user()->Sup->apellidos}} 
                @endif
                </strong>
            </small>
        </div>
        <div class="panel-body">
            <form id="form-register-solicitude" class="" method="post" 
            action="{{isset($solicitude) ? 'editar-solicitud' : 'registrar-solicitud' }}" enctype="multipart/form-data">
            {{Form::token()}}
            @if(isset($solicitude))
                <input value="{{$solicitude->idsolicitud}}" name="idsolicitude" type="hidden">
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
                            @if(isset($solicitude) && $solicitude->idtiposolicitud == $type->idtiposolicitud)
                                <option selected value="{{$type->idtiposolicitud}}">{{$type->nombre}}</option>
                            @else
                                <option value="{{$type->idtiposolicitud}}">{{$type->nombre}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
            <!--  -->
            <div class="form-group col-sm-6 col-md-4">
                <label class="col-sm-8 col-md-8 control-label" for="textinput">Pruebas</label>
                <div class="col-sm-12 col-md-12">
                    <input id="idtitle" class="form-control input-md cliente-seeker" name="titulo" type="text" placeholder=""
                    value="{{isset($solicitude->titulo)? $solicitude->titulo : null }}">
                </div>
            </div>
            <!-- -->
            <div class="form-group col-sm-6 col-md-4">
                <label class="col-sm-8 col-md-8 control-label" for="textinput">Tipo de Pago</label>
                <div class="col-sm-12 col-md-12">
                    <select id="" name="type_payment" class="form-control selectTypePayment">
                        @foreach($typePayments as $type)
                            @if(isset($solicitude) && $solicitude->idtipopago == $type->idtipopago)
                                <option selected value="{{$type->idtipopago}}">{{$type->nombre}}</option>
                            @else
                                <option value="{{$type->idtipopago}}">{{$type->nombre}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group col-sm-6 col-md-4" id="div_ruc">
                <label class="col-sm-8 col-md-8 control-label" for="textinput">Ruc</label>
                <div class="col-sm-12 col-md-12">
                    <input id="ruc" class="form-control input-md" maxlength="11" name="ruc" type="text" placeholder=""
                           value="{{isset($solicitude->numruc) ? $solicitude->numruc : null }}">
                </div>
            </div>
            <div id="div_number_account" class="form-group col-sm-6 col-md-4">
                <label class="col-sm-8 col-md-8 control-label" for="textinput">Nº de Cuenta</label>
                <div class="col-sm-12 col-md-12">
                    <input id="number_account" class="form-control input-md" name="number_account" type="text" placeholder=""
                           value="{{isset($solicitude->numcuenta) ? $solicitude->numcuenta : null }}">
                </div>
            </div>
            <div class="form-group col-sm-6 col-md-4">
                <label class="col-sm-8 col-md-8 control-label" for="textinput">Monto Solicitado</label>
                <div class="col-sm-12 col-md-12">
                    <input id="idestimate" class="form-control input-md" name="monto" type="text" placeholder=""
                           value="{{isset($solicitude->monto) ? $solicitude->monto : null }}">
                </div>
            </div>
            <div class="form-group col-sm-6 col-md-4">
                <label class="col-sm-8 col-md-8 control-label" for="textinput">Moneda</label>
                <div class="col-sm-12 col-md-12">
                    <select id="money" name="money" class="form-control">
                        <option value="1">Soles</option>
                        <option value="2">Dolares</option>
                    </select>
                </div>
            </div>
            <div class="solicitude_monto form-group col-sm-6 col-md-4">
                <label class="col-sm-8 col-md-8 control-label" for="textinput">Monto Factura</label>
                <div class="col-sm-12 col-md-12">
                    <input id="amountfac" class="form-control input-md" name="amount_fac" type="text" placeholder=""
                           value="{{isset($solicitude->monto_factura) ? $solicitude->monto_factura : null }}">
                </div>
            </div>
            @if (!isset($solicitude))
                <div class="solicitude_factura form-group col-sm-6 col-md-4">
                    <label class="col-sm-12 col-md-12 control-label" for="textinput">Factura
                        <small>(solo imagenes)</small>
                    </label>
                    <div class="col-sm-12 col-md-12" style="">
                        <div class="input-group">
                            <span class="input-group-btn">
                                 <span class="btn btn-primary btn-file">
                                     <i class="glyphicon glyphicon-folder-open"></i>
                                     <input type="file" multiple="" name="file">
                                 </span>
                            </span>
                            <input type="text" id="input-file-factura"  class="form-control" readonly="" required="">
                        </div>
                    </div>
                </div>
            @endif
            <div class="form-group col-sm-6 col-md-4">
                <label class="col-sm-8 col-md-8 control-label" for="textinput">Fecha de Entrega</label>
                <div class="col-sm-12 col-md-12">
                    <div class="input-group date">
                        <span class="input-group-addon">
                            <i class="glyphicon glyphicon-calendar"></i>
                        </span>
                        <input id="delivery_date" type="text" name="delivery_date" class="form-control" maxlength="10" placeholder="" readonly
                               value="{{isset($solicitude)? date_format(date_create($solicitude->fecha_entrega), 'd/m/Y' ) : null }}">
                    </div>
                </div>
            </div>
            <!-- <div class="form-group col-sm-12 col-md-8" style="padding: 0"> -->
                <div class="form-group col-sm-6 col-md-4 ">
                    <label class="col-sm-8 col-md-8 control-label" for="textinput">Cliente</label>
                    <ul id="listclient" class="col-sm-12 col-md-12">
                        @if(isset($clients))
                            @foreach($clients as $client)
                                <li>
                                    <div style="position: relative" class="has-success has-feedback">
                                        <input id="idclient1" name="clients[]" type="text" placeholder="" style="margin-bottom: 10px"
                                               class="form-control input-md project input-client" data-valor="{{$client->clcodigo}}"
                                               value="{{isset($client->clnombre) ? $client->clcodigo.' - '.$client->clnombre : null }}">
                                        <button type='button' class='btn-delete-client' style="z-index: 2">
                                            <span class='glyphicon glyphicon-remove'></span>
                                        </button>
                                    </div>
                                </li>
                            @endforeach
                        @else
                            <li>
                                <div style="position: relative" class="">
                                    <input id="idclient1" name="clients[]" type="text" placeholder="" style="margin-bottom: 10px"
                                           class="form-control input-md input-client" data-valor=""
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
                <div class="form-group col-sm-6 col-md-4">
                    <label class="col-sm-8 col-md-8 control-label" for="selectfamily">Familia</label>
                    <ul id="listfamily" class="col-sm-12 col-md-12" style="">
                        @if(isset($families2))
                            @foreach($families2 as $family2)
                                <li>
                                    <div class="" style="position: relative">
                                        <select id="selectfamily" name="families[]" class="form-control selectfamily" style="margin-bottom:10px ">
                                            @foreach($families as $family)
                                                @if($family->id == $family2->id)
                                                    <option value="{{$family->id}}" selected>{{$family->descripcion}}</option>
                                                @else
                                                    <option value="{{$family->id}}">{{$family->descripcion}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <button type='button' class='btn-delete-family' style="">
                                            <span class='glyphicon glyphicon-remove'></span>
                                        </button>
                                    </div>
                                </li>
                            @endforeach
                        @else
                            <li>
                                <div class="" style="position: relative">
                                    <select id="selectfamily" name="families[]" class="form-control selectfamily" style="margin-bottom:10px">
                                        @foreach($families as $family)
                                            <option value="{{$family->id}}">{{$family->descripcion}}</option>
                                        @endforeach
                                    </select>
                                    <button type='button' class='btn-delete-family' style="display: none">
                                        <span class='glyphicon glyphicon-remove'></span>
                                    </button>
                                </div>
                            </li>
                        @endif
                    </ul>
                    <span class="col-sm-10 col-md-10 families_repeat" style="margin-bottom: 10px ; margin-top: -10px"></span>
                    <button type="button" class="btn btn-default" id="btn-add-family">Agregar Otra Familia</button>
                </div>
            <!-- </div> -->
            @if(isset($solicitude) && $solicitude->idtiposolicitud == 2)
                <div class="form-group col-sm-6 col-md-4">
                    <div class="col-sm-12 col-md-12">
                        <label class="col-sm-8 col-md-8 control-label" for="textinput"> </label>
                        <a class="btn btn-primary btn-md" data-toggle="modal" data-target="#myFac">
                            Ver Comprobante
                        </a>
                    </div>
                </div>
                <div class="modal fade" id="myFac" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">
                                    <span aria-hidden="true">&times;</span>
                                    <span class="sr-only">Close</span>
                                </button>
                                <h4 class="modal-title" id="myModalLabel">Comprobante</h4>
                            </div>
                            <div class="modal-body">
                                @if (empty($solicitude->image))
                                    <h3>No se ingreso una imagen</h3>
                                @elseif (!file_exists(public_path().'/'.IMAGE_PATH.$solicitude->image))
                                    <h3>No se encontro la imagen en el sistema</h3>
                                @else
                                    <img id="imgSalida" class="img-responsive" src="{{asset(IMAGE_PATH.$solicitude->image)}}">
                                @endif 
                            </div>
                            <div class="modal-footer">
                                <div class="solicitude_factura form-group col-sm-10 col-md-10" style="padding-right: 30px">
                                    <label class="col-sm-6 col-md-4 control-label" for="textinput">Subir Otra Factura</label>
                                    <div class="col-sm-5 col-md-7">
                                        <input id="isSetImage" type="hidden" value="{{$solicitude->image}}">
                                        <input type="file" id="input-file-factura" name="file" class="form-control" style="padding:1px">
                                    </div>
                                </div>
                                <div class="form-group col-sm-1 col-md-1">
                                    <button type="button" class="btn btn-primary" data-dismiss="modal">Aceptar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <div class="col-sm-12 col-md-12" style="margin-top: 10px">
                <div class="form-group ">
                    <label class="col-sm-8 col-md-8 control-label" for="textarea">Descripcion de la Solicitud</label>
                    <div class="col-sm-12 col-md-12">
                        <textarea class="form-control" id="iddescriptionsolicitude" name="description">{{isset($solicitude->descripcion) ? $solicitude->descripcion : null}}</textarea>
                    </div>
                </div>
            </div>
            <!-- Button (Double) -->
            <div class="form-group col-sm-12 col-md-12" style="margin-top: 20px">
                <div class="col-sm-12 col-md-12" style="text-align: center">
                    @if(isset($solicitude))
                        @if($solicitude->blocked == 0)
                            <button id="button1id" name="button1id" class="btn btn-primary register_solicitude">
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
