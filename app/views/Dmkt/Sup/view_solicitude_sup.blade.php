@extends('template.main')
@section('content')
<div class="content">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Ver Solicitud Supervisor</h3>
            @if($solicitude->blocked == 1 && $solicitude->user->type === SUP)
            <h4 class="" style="color: darkred">LA SOLICITUD ESTA SIENDO EVALUADA</h4>
            @endif
            <small style="float: right; margin-top: -10px"><strong>Usuario : {{Auth::user()->Sup->nombres}}</strong></small>
        </div>
        <div class="panel-body">
            <form id="form_make_activity" class="" method="post">
                {{Form::token()}}
                <input id="textinput" name="idsolicitude" type="hidden" placeholder="" value="{{$solicitude->id}}">
                <div class="form-group col-sm-6 col-md-4 col-lg-4">
                    <label class="col-sm-8 col-md-8 col-lg-8 control-label" for="textinput">Tipo Solicitud</label>
                    <div class="col-sm-12 col-md-12 col-lg-12">
                        <input id="textinput" name="textinput" type="text" placeholder="" value="{{$solicitude->typesolicitude->nombre}}" readonly
                        class="form-control input-md">
                    </div>
                </div>
                <div class="form-group col-sm-6 col-md-4 col-lg-4">
                    <label class="col-sm-8 col-md-8 col-lg-8 control-label" for="textinput">Nombre Solicitud</label>
                    <div class="col-sm-12 col-md-12 col-lg-12">
                        <input id="textinput" name="titulo" type="text" placeholder="" value="{{$solicitude->titulo}}" readonly
                        class="form-control input-md">
                    </div>
                </div>
    <!-- Type Payment -->
                <div class="form-group col-sm-6 col-md-4" >
                    <label class="col-sm-8 col-md-8 control-label" for="textinput">Tipo de Pago</label>
                    <div class="col-sm-12 col-md-12">
                        <select id="" name="type_payment" class="form-control selectTypePayment" disabled>
                            @if(isset($solicitude->detalle->idpago))
                                <option selected value="{{$solicitude->detalle->typePayment->idtipopago}}">{{$solicitude->detalle->typePayment->nombre}}</option>
                            @else
                                <option selected value="0">No Especificado</option>
                            @endif
                        </select>
                    </div>
                </div>
    <!-- Ruc -->
                <div class="form-group col-sm-6 col-md-4" id="div_ruc">
                    <label class="col-sm-8 col-md-8 control-label" for="textinput">Ruc</label>
                    <div class="col-sm-12 col-md-12">
                        <input id="ruc" name="ruc" type="text" placeholder="" value="{{isset($solicitude->numruc) ? $solicitude->numruc : null }}" class="form-control input-md" maxlength="11" readonly>
                    </div>
                </div>
    <!-- Account Number -->
                <div class="form-group col-sm-6 col-md-4" id="div_number_account">
                    <label class="col-sm-8 col-md-8 control-label" for="textinput">Nº de Cuenta</label>
                    <div class="col-sm-12 col-md-12">
                        <input id="number_account" name="number_account" type="text" placeholder="" value="{{isset($solicitude->numcuenta) ? $solicitude->numcuenta : null }}"
                        class="form-control input-md" readonly>
                    </div>
                </div>
                <div class="form-group col-sm-6 col-md-4 col-lg-4">
                    <label class="col-sm-8 col-md-8 col-lg-8 control-label" for="textinput">Monto Solicitado</label>
                    <div class="col-sm-12 col-md-12 col-lg-12">
                        @if($solicitude->idestado == PENDIENTE)
                        <div class="input-group">
                            <span class="input-group-addon">{{$solicitude->detalle->typemoney->simbolo}}</span>
                            <input id="idamount" name="monto" type="text" value="{{$detalle->monto_solicitado}}"
                                   class="form-control input-md">
                        </div>
                        @else
                        <div class="input-group">
                            <span class="input-group-addon">{{$solicitude->detalle->typeMoney->simbolo}}</span>
                            <input id="idamount" name="monto" type="text" value="{{$detalle->monto_solicitado}}"
                                   class="form-control input-md" readonly>
                        </div>
                        @endif
                    </div>
                </div>
                @if(isset($solicitude->monto_factura))
                <div class="form-group col-sm-6 col-md-4 col-lg-4">
                    <label class="col-sm-8 col-md-8 col-lg-8 control-label" for="textinput">Monto Factura</label>
                    <div class="col-sm-12 col-md-12 col-lg-12">
                        <div class="input-group">
                            <span class="input-group-addon">{{$solicitude->typemoney->simbolo}}</span>
                            <input id="textinput" name="amount_fac" type="text" placeholder=""
                               value="{{$solicitude->monto_factura}}" readonly
                               class="form-control input-md">
                        </div>
                    </div>
                </div>
                @endif
                <div class="form-group col-sm-6 col-md-4">
                    <label class="col-sm-8 col-md-8 control-label" for="textinput">Fondo</label>
                    <div class="col-sm-12 col-md-12">
                        @if($solicitude->idestado == PENDIENTE)
                            <select id="sub_type_activity" name="idfondo" class="form-control">
                        @else
                            <select id="sub_type_activity" name="idfondo" class="form-control" disabled>
                        @endif
                            @foreach($fondos as $sub)
                                @if($sub->idfondo == FONDO_SUPERVISOR)
                                    <option value="{{$sub->id}}" selected>{{$sub->nombre}} -> {{$sub->saldo}}</option>
                                @else
                                    <option value="{{$sub->id}}">{{$sub->nombre}} -> {{$sub->saldo}}</option>                          
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group col-sm-6 col-md-4 col-lg-4">
                    <label class="col-sm-8 col-md-8 col-lg-8 control-label" for="selectbasic">Fecha de Creacion</label>
                    <div class="col-sm-12 col-md-12 col-lg-12">
                        <div class="input-group date">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                            <input id="date" type="text" class="form-control" maxlength="10" disabled placeholder=""
                                   value="{{ date_format(date_create($solicitude->created_at), 'd/m/Y' )}}">
                        </div>
                    </div>
                </div>
                <div class="form-group col-sm-6 col-md-4 col-lg-4">
                    <label class="col-sm-8 col-md-8 control-label" for="textinput">Fecha de Entrega</label>
                    <div class="col-sm-12 col-md-12">
                        <div class="input-group date">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                            <input id="date" type="text" name="delivery_date"
                                   value="{{ date_format(date_create($solicitude->fecha_entrega), 'd/m/Y' )}}"
                                   class="form-control" maxlength="10" disabled placeholder="">
                        </div>
                    </div>
                </div>
                @if($solicitude->createdBy->type == REP_MED)
                    <div class="form-group col-sm-6 col-md-4 col-lg-4">
                        <label class="col-sm-8 col-md-8 col-lg-8 control-label" for="textinput">Solicitante</label>
                        <div class="col-sm-12 col-md-12 col-lg-12">
                            <div class="input-group date">
                                <span class="input-group-addon">Representante</span>
                                <input id="textinput" name="titulo" type="text" value="{{$solicitude->createdBy->rm->nombres.' '.$solicitude->createdBy->rm->apellidos}}" disabled
                                   class="form-control input-md">
                            </div>
                        </div>
                    </div>
                @elseif($solicitude->createdBy->type == SUP)
                    <div class="form-group col-sm-6 col-md-4 col-lg-4">
                        <label class="col-sm-8 col-md-8 col-lg-8 control-label" for="textinput">Solicitante</label>
                        <div class="col-sm-12 col-md-12 col-lg-12">
                            <div class="input-group date">
                                <span class="input-group-addon">Supervisor</span>
                                <input id="textinput" name="titulo" type="text" value="{{$solicitude->createdBy->Sup->nombres.' '.$solicitude->createdBy->Sup->apellidos}}" disabled
                                   class="form-control input-md">
                            </div>
                        </div>
                    </div>
                @else
                    <div class="form-group col-sm-6 col-md-4 col-lg-4">
                        <label class="col-sm-8 col-md-8 col-lg-8 control-label" for="textinput">Solicitante</label>
                        <div class="col-sm-12 col-md-12 col-lg-12">
                            <div class="input-group date">
                                <span class="input-group-addon">Rol No Definido</span>
                                <input id="textinput" name="titulo" type="text" value="{{$solicitude->createdBy->email}}" disabled
                                   class="form-control input-md">
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Observation-->
                @include('template.obs')

                @if(isset($solicitude) && $solicitude->idtiposolicitud == 2)
                <div class="form-group col-sm-6 col-md-4 col-lg-4">
                    <label class="col-sm-8 col-md-8 col-lg-8 control-label" for="textinput">&nbsp;</label>
                    <div class="col-sm-12 col-md-12 col-lg-12">
                        <a class="btn btn-primary btn-md" data-toggle="modal" data-target="#myFac">
                            Ver Comprobante
                        </a>
                    </div>
                </div>
                <div class="modal fade" id="myFac" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                                        class="sr-only">Close</span></button>
                                <h4 class="modal-title" id="myModalLabel">Comprobante</h4>
                            </div>
                            <div class="modal-body">
                                @if (empty($solicitude->image))
                                    <h3>No se ingreso una imagen</h3>
                                @elseif (!file_exists(public_path().'/'.IMAGE_PATH.$solicitude->image))
                                    <h3>No se encontro la imagen en el sistema</h3>
                                @else
                                    <img class="img-responsive" src="{{asset(IMAGE_PATH.$solicitude->image)}}">
                                @endif
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="demoLightbox" class="lightbox hide fade" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class='lightbox-content'>
                        <img src="{{URL::to('/')}}/img/reembolso/{{$solicitude->image}}">
                        <div class="lightbox-caption"><p>Your caption here</p></div>
                    </div>
                </div>
                @endif
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6" >
                        <div style="padding: 0 15px">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Productos</h3>
                                </div>
                                <div class="panel-body">
                                    <?php $t = 0; ?>
                                    @foreach($solicitude->families as $family)
                                    <div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding: 0">
                                        <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                                            <input id="textinput" name="family" type="text"
                                                   value="{{$family->marca->descripcion}}" readonly
                                                   class="form-control input-md">
                                        </div>
                                        @if($solicitude->detalle->idaccepted == null )
                                        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4" style="padding: 0">
                                            <div class="input-group">
                                                <span class="input-group-addon">{{$solicitude->detalle->typemoney->simbolo}}</span>
                                                @if($solicitude->idestado == PENDIENTE)
                                                    <input id="" name="amount_assigned[]" type="text"
                                                           class="form-control input-md amount_families"
                                                           value="{{isset($family->monto_asignado)? $family->monto_asignado : round($detalle->monto_solicitado/count($solicitude->families),2)}}">
                                                @else
                                                    <input disabled id="" name="amount_assigned[]" type="text"
                                                           class="form-control input-md amount_families"
                                                           value="{{isset($family->monto_asignado)? $family->monto_asignado : round($detalle->monto_solicitado/count($solicitude->families),2)}}">
                                                @endif
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                    <?php $t = $t + round($solicitude->monto / count($solicitude->families), 2) ?>
                                    @endforeach
                                    <div class="form-group col-sm-12 col-md-12 col-lg-12">
                                        <span id="amount_error_families"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @include('template.list_clients')
                </div>
                <input type="hidden" id="_token" value="{{csrf_token()}}">
                <div class="col-sm-12 col-md-12 col-lg-12" style="margin-top: 10px">
                    <div class="form-group col-sm-12 col-md-12 col-lg-12">
                        <label class="col-sm-8 col-md-8 control-label" for="textarea">Descripcion de la Solicitud</label>
                        <div class="col-sm-12 col-md-12 col-lg-12">
                            <textarea class="form-control" id="textarea" name="textarea" readonly>{{$solicitude->descripcion}}</textarea>
                        </div>
                    </div>
                </div>
    <!-- Button (Double) -->
                <div class="form-group col-sm-12 col-md-12 col-lg-12" style="margin-top: 20px">
                    <div class="col-sm-12 col-md-12 col-lg-12" style="text-align: center"> 
                        @if( $solicitude->idestado == PENDIENTE )
                            <a class="btn btn-primary" id="search_responsable">
                                Aceptar
                            </a>
                            <a id="deny_solicitude" name="button1id" class="btn btn-primary deny_solicitude">
                                Rechazar
                            </a>
                        @endif
                        <a id="button2id" href="{{URL::to('desbloquear-solicitud-sup').'/'.$solicitude->token}}" name="button2id" class="btn btn-primary">
                            Cancelar
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@if(isset($Status))
    <script type="text/javascript">
        @if($Status <> ok)
            $(document).ready(function() 
            {
                bootbox.alert('<h4 style="color: red">{{$Description}}</h4>');
            });
        @endif
    </script>
@endif
        </div>
    </div>
</div>
@stop