@extends('template.main')
@section('content')

<div class="content">

<div class="panel panel-default">
<div class="panel-heading">
    <h3 class="panel-title">Ver Solicitud Supervisor</h3>
    @if($solicitude->blocked == 1 && $solicitude->user->type === 'S')
    <h4 class="" style="color: darkred">LA SOLICITUD ESTA SIENDO EVALUADA</h4>
    @endif
    <small style="float: right; margin-top: -10px"><strong>Usuario : {{Auth::user()->Sup->nombres}}</strong></small>
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
<div class="form-group col-sm-6 col-md-4" >

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
        <select id="sub_type_activity" name="sub_type_activity" class="form-control">
            @foreach($fondos as $sub)
                @if(isset($solicitude->idfondo) && $sub->idfondo == $solicitude->subtype->idfondo)
                    <option selected value="{{$sub->idfondo}}">{{$sub->nombre}}</option>
                @else
                    @if($sub->idfondo == 1)
                    <option value="{{$sub->idfondo}}">{{$sub->nombre}}</option>
                    @endif
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
                   class="form-control" maxlength="10" readonly placeholder="">
        </div>

    </div>
</div>

@if($solicitude->user->type == 'R')
<div class="form-group col-sm-6 col-md-4 col-lg-4">

    <label class="col-sm-8 col-md-8 col-lg-8 control-label" for="textinput">Solicitante</label>

    <div class="col-sm-12 col-md-12 col-lg-12">

        <input id="textinput" name="titulo" type="text" placeholder=""
               value="{{$solicitude->user->rm->nombres}}" readonly
               class="form-control input-md">

    </div>
</div>
@endif
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

                        <input id="textinput" name="textinput" type="text" placeholder=""
                               value="{{$family->marca->descripcion}}" readonly
                               class="form-control input-md">

                    </div>

                    @if($solicitude->idaproved == null || $solicitude->idaproved == Auth::user()->id)
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
<input type="hidden" id="_token" value="{{csrf_token()}}">
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
        @if($solicitude->estado == PENDIENTE && $solicitude->derived == 0 && $solicitude->user->type == 'R')
            <a href="{{URL::to('aceptar_solicitud')}}" id="test" name="button1id"
               class="btn btn-primary accepted_solicitude_sup">Aceptar
            </a>
            <a class="btn btn-primary" data-toggle="modal" data-target="#myModal">
                Derivar
            </a>
            <a id="deny_solicitude" name="button1id" class="btn btn-primary deny_solicitude">Rechazar
            </a>

            <a id="button2id" href="{{URL::to('desbloquear-solicitud-sup').'/'.$solicitude->token}}" name="button2id" class="btn btn-primary">Cancelar</a>
        @else

            @if($solicitude->derived == 0 && $solicitude->estado != ACEPTADO && $solicitude->estado != RECHAZADO && $solicitude->estado != CANCELADO)
                <a href="{{URL::to('aceptar_solicitud')}}" id="test" name="button1id"
                   class="btn btn-primary accepted_solicitude_sup">Aceptar
                </a>
            @endif
                <a id="button2id" href="{{URL::to('show_sup')}}" name="button2id"
                   class="btn btn-primary">Cancelar</a>
        @endif
    </div>
</div>
</form>
</div>
</div>
</div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                        class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel">La solicitud será derivado a :</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal">
                    <fieldset>
                        {{Form::token()}}
                        <!-- Form Name -->


                        <!-- Select Basic -->
                        <div class="form-group">
                            <label class="col-md-4 col-lg-4 control-label" for="selectbasic"></label>

                            <div class="col-md-5 col-lg-5">
                                @foreach($solicitude->families as $v)
                                <?php $gerentes[] = $v->marca->manager->descripcion ?>
                                @endforeach
                                <?php  $gerentes = array_unique($gerentes) ?>

                               <ul>
                                   @foreach($gerentes as  $gerente)
                                   <li>{{ $gerente }}</li>
                                   @endforeach
                                </ul>
                            </div>
                        </div>
                    </fieldset>
                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                <a href="{{URL::to('derivar-solicitud').'/'.$solicitude->token}}" type="button" class="btn btn-primary">Derivar</a>
            </div>
        </div>
    </div>
</div>
@stop
