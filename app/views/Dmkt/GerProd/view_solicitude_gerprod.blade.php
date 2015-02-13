@extends('template.main')
@section('content')

<div class="content">

<div class="panel panel-default">
<div class="panel-heading">


    <h3 class="panel-title">Ver Solicitud Gerente Producto</h3>


    @if($block == true)

    <h4 class="" style="color: darkred">LA SOLICITUD ESTA SIENDO EVALUADA</h4>

    @endif
    <small style="float: right; margin-top: -10px"><strong>Usuario : {{Auth::user()->Gerprod->descripcion}}</strong>
    </small>
</div>
<div class="panel-body">
<form id="form_make_activity" class="" method="post" action="">
{{Form::token()}}
<input id="textinput" name="idsolicitude" type="hidden" placeholder=""
       value="{{$solicitude->idsolicitud}}">

<div class="form-group col-sm-6 col-md-4 col-lg-4">

    <label class="col-sm-8 col-md-8 col-lg-8 control-label" for="textinput">Tipo Solicitud</label>

    <div class="col-sm-12 col-md-12 col-lg-12">
        <input id="textinput" name="textinput" type="text" placeholder=""
               value="{{$solicitude->typesolicitude->nombre}}" readonly
               class="form-control input-md">

    </div>
</div>

<div class="form-group col-sm-6 col-md-4 col-lg-4">

    <label class="col-sm-8 col-md-8 col-lg-8 control-label" for="textinput">Nombre Solicitud</label>

    <div class="col-sm-12 col-md-12 col-lg-12">
        <input id="textinput" name="titulo" type="text" placeholder=""
               value="{{$solicitude->titulo}}" readonly
               class="form-control input-md">

    </div>
</div>

<!-- Type Payment -->
<div class="form-group col-sm-6 col-md-4">

    <label class="col-sm-8 col-md-8 control-label" for="textinput">Tipo de Pago</label>

    <div class="col-sm-12 col-md-12">
        <select id="" name="type_payment" class="form-control selectTypePayment" disabled>
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
<!-- Ruc -->
<div class="form-group col-sm-6 col-md-4" id="div_ruc">
    <label class="col-sm-8 col-md-8 control-label" for="textinput">Ruc</label>

    <div class="col-sm-12 col-md-12">
        <input id="ruc" name="ruc" type="text" placeholder=""
               value="{{isset($solicitude->numruc) ? $solicitude->numruc : null }}"
               class="form-control input-md" maxlength="11" readonly>

    </div>
</div>
<!-- Account Number -->
<div class="form-group col-sm-6 col-md-4" id="div_number_account">

    <label class="col-sm-8 col-md-8 control-label" for="textinput">Nº de Cuenta</label>

    <div class="col-sm-12 col-md-12">
        <input id="number_account" name="number_account" type="text" placeholder=""
               value="{{isset($solicitude->numcuenta) ? $solicitude->numcuenta : null }}"
               class="form-control input-md" readonly>

    </div>
</div>


<div class="form-group col-sm-6 col-md-4 col-lg-4">
    <label class="col-sm-8 col-md-8 col-lg-8 control-label" for="textinput">Monto Solicitado</label>

    <div class="col-sm-12 col-md-12 col-lg-12">
        @if($solicitude->estado == PENDIENTE)
        <div class="input-group">
            <span class="input-group-addon">{{$solicitude->typemoney->simbolo}}</span>
            <input id="idamount" name="monto" type="text" placeholder="" value="{{$solicitude->monto}}"
                   class="form-control input-md">
        </div>

        @else
        <div class="input-group">
            <span class="input-group-addon">{{$solicitude->typemoney->simbolo}}</span>
            <input id="idamount" name="monto" type="text" placeholder="" value="{{$solicitude->monto}}"
                   class="form-control input-md" readonly>
        </div>

        @endif

    </div>
</div>
@if(isset($solicitude->monto_factura))
<div class="form-group col-sm-6 col-md-4 col-lg-4">

    <label class="col-sm-8 col-md-8 col-lg-8 control-label" for="textinput">Monto Factura</label>

    <div class="col-sm-12 col-md-12 col-lg-12">
        <input id="textinput" name="amount_fac" type="text" placeholder=""
               value="{{$solicitude->monto_factura}}" readonly
               class="form-control input-md">

    </div>
</div>
@endif

<div class="form-group col-sm-6 col-md-4">

    <label class="col-sm-8 col-md-8 control-label" for="textinput">Fondo</label>

    <div class="col-sm-12 col-md-12">

        @if(isset($solicitude))

            @if($solicitude->estado != PENDIENTE)

                    <input id="textinput" name="amount_fac" type="text" placeholder=""
                           value="{{$solicitude->fondo->nombre_mkt}}" readonly
                           class="form-control input-md">
            @else
              <select id="sub_type_activity" name="sub_type_activity" class="form-control">
                        @foreach($fondos as $sub)
                            @if($sub->idfondo != 31 && $sub->idfondo !=1)
                                @if($sub->idfondo == $solicitude->idfondo)
                                <option selected value="{{$sub->idfondo}}">{{$sub->nombre_mkt}} -> {{$sub->saldo}}</option>
                                @else
                                <option value="{{$sub->idfondo}}">{{$sub->nombre_mkt}} -> {{$sub->saldo}}</option>
                                @endif
                            @endif
                        @endforeach
                    </select>
            @endif

        @else
        <select id="sub_type_activity" name="sub_type_activity" class="form-control">
            @foreach($fondos as $sub)
                @if($sub->idfondo != 31 && $sub->idfondo !=1)
                    <option value="{{$sub->idfondo}}">{{$sub->nombre_mkt}} -> {{$sub->saldo}}</option>
                @endif
            @endforeach
        </select>
        @endif

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
                   class="form-control" maxlength="10" readonly placeholder="">
        </div>

    </div>
</div>

<!--------------------------   Solicitante    ------------------------->
<div class="form-group col-sm-6 col-md-4 col-lg-4">

    <label class="col-sm-8 col-md-8 col-lg-8 control-label" for="textinput">Solicitante</label>

    <div class="col-sm-12 col-md-12 col-lg-12">
        <div class="input-group">
            @if($solicitude->user->type == 'R')
            <span class="input-group-addon">R</span>
            <input id="textinput" name="titulo" type="text" placeholder=""
                   value="{{$solicitude->user->rm->nombres}}" readonly
                   class="form-control input-md">
            @else
            <span class="input-group-addon">S</span>
            <input id="textinput" name="titulo" type="text" placeholder=""
                   value="{{$solicitude->user->sup->nombres}}" readonly
                   class="form-control input-md">
            @endif
        </div>

    </div>
</div>
<!--
<div class="form-group col-sm-6 col-md-4 col-lg-4">

    <label class="col-sm-8 col-md-8 col-lg-8 control-label" for="textinput">Responsable</label>

    <div class="col-sm-12 col-md-12 col-lg-12">
        <div class="input-group">
            <select  class="form-control">
             @if($solicitude->user->type == 'R')
                <option value="{{$solicitude->user->id}}">{{$solicitude->user->rm->nombres}}</option>
             @else
                <option value="{{$solicitude->user->id}}">{{$solicitude->user->sup->nombres}}</option>
             @endif
            <option value="25">Asistente de Gerencia</option>
            </select>

        </div>

    </div>
</div>-->
<div class="form-group col-sm-6 col-md-4 col-lg-4">

    <label class="col-sm-8 col-md-8 ol-lg-8 control-label" for="textinput">Observacion</label>

    <div class="col-sm-12 col-md-12 col-lg-12">
        @if($solicitude->estado == PENDIENTE)
        <textarea id="textinput" name="observacion" placeholder=""
                  class="form-control"></textarea>
        @else
        <textarea id="textinput" name="observacion" placeholder=""
                  class="form-control" disabled>{{$solicitude->observacion}}</textarea>
        @endif
    </div>
</div>

@if(isset($solicitude) && $solicitude->idtiposolicitud == 2)
<div class="form-group col-sm-6 col-md-4 col-lg-4">
    <label class="col-sm-8 col-md-8 ol-lg-8 control-label" for="textinput">&nbsp;</label>

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
                <img class="img-responsive" src="{{URL::to('img').'/reembolso/'.$solicitude->image}}">
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
    <div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">

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

                            <input id="textinput" name="textinput" type="text" placeholder=""
                                   value="{{$family->marca->descripcion}}" readonly
                                   class="form-control input-md">

                        </div>
                        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4" style="padding: 0">
                            <div class="input-group">
                                <span class="input-group-addon">{{$solicitude->typemoney->simbolo}}</span>
                                @if($solicitude->estado == PENDIENTE)

                                <input id="" name="amount_assigned[]" type="text"
                                       class="form-control input-md amount_families"
                                       value="{{isset($family->monto_asignado)? $family->monto_asignado : round($solicitude->monto/count($solicitude->families),2)}}">
                                @else
                                <input disabled id="" name="amount_assigned[]" type="text"
                                       class="form-control input-md amount_families"
                                       value="{{isset($family->monto_asignado)? $family->monto_asignado : round($solicitude->monto/count($solicitude->families),2)}}">

                                @endif
                            </div>
                        </div>

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
    <div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">

        <div style="padding: 0 15px">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Clientes</h3>
                </div>
                <div class="panel-body">

                    @foreach($solicitude->clients as $client)
                    <div class="form-group" style="">


                        <div class="">
                            <input id="textinput" name="textinput" type="text" placeholder=""
                                   value="{{$client->client->clnombre}}" readonly
                                   class="form-control input-md">

                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-sm-12 col-md-12 col-lg-12" style="margin-top: 10px">
    <div class="form-group col-sm-12 col-md-12 col-lg-12">
        <label class="col-sm-8 col-md-8 control-label" for="textarea">Descripcion de la
            Solicitud</label>

        <div class="col-sm-12 col-md-12 col-lg-12">
            <textarea class="form-control" id="textarea" name="textarea"
                      readonly>{{$solicitude->descripcion}}</textarea>
        </div>
    </div>
</div>


<!-- Button (Double) -->
<div class="form-group col-sm-12 col-md-12 col-lg-12" style="margin-top: 20px">

    <div class="col-sm-12 col-md-12 col-lg-12" style="text-align: center">
        @if($solicitude->estado == PENDIENTE)
        @if($block == false)
        <a id="test" name="button1id"
           class="btn btn-primary accepted_solicitude_gerprod">Aceptar</a>
        <a id="deny_solicitude_gerprod" name="button1id" class="btn btn-primary deny_solicitude_gerprod">Rechazar
        </a>
        <a id="button2id" href="{{URL::to('cancelar-solicitud-gerprod').'/'.$solicitude->token}}" name="button2id"
           class="btn btn-primary">Cancelar</a>
        @else
        <a id="button2id" href="{{URL::to('show_gerprod')}}" name="button2id"
           class="btn btn-primary">Cancelar</a>

        @endif


        @else
        <a id="button2id" href="{{URL::to('show_gerprod')}}" name="button2id"
           class="btn btn-primary">Cancelar</a>
        @endif
    </div>
</div>
</form>
</div>
</div>
</div>

@stop
