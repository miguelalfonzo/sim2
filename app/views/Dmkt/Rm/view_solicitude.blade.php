@extends('template.main')
@section('content')
<style>
    .form-horizontal .control-label {
        text-align: left;
    }
</style>

<div class="content">

<div class="panel panel-default">
<div class="panel-heading"><h3 class="panel-title">Solicitud</h3>
    @if($solicitude->blocked == 1)
    <h4 class="" style="color: darkred">LA SOLICITUD ESTA SIENDO EVALUADA</h4>
    @endif
    <small style="float: right; margin-top: -10px"><strong>Usuario : {{Auth::user()->Rm->nombres}}</strong></small></div>
<div class="panel-body">

<!--    Type Solicitude  -->
<div class="form-group col-sm-6 col-md-4 col-lg-4">

    <label class="col-sm-8 col-md-8 col-lg-4 control-label" for="textinput">Tipo Solicitud</label>

    <div class="col-sm-12 col-md-12">
        <input id="textinput" name="textinput" type="text" placeholder=""
               value="{{$solicitude->typesolicitude->nombre}}" readonly
               class="form-control input-md">

    </div>
</div>

<!--    Type Payment -->

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


<!--    Name Solicitude  -->
<div class="form-group col-sm-6 col-md-4 col-lg-4">

    <label class="col-sm-8 col-md-8 col-lg-8 control-label" for="textinput">Nombre Solicitud</label>

    <div class="col-sm-12 col-md-12 col-lg-12">
        <input id="textinput" name="textinput" type="text" placeholder=""
               value="{{$solicitude->titulo}}" readonly
               class="form-control input-md">

    </div>
</div>
<!--  Amount Solicitude -->
<div class="form-group col-sm-6 col-md-4 col-lg-4">
    <label class="col-sm-8 col-md-8 col-lg-8 control-label" for="textinput">Monto Solicitado</label>

    <div class="col-sm-12 col-md-12 col-lg-12">
        <div class="input-group">
            <span class="input-group-addon">{{$solicitude->typemoney->simbolo}}</span>
            <input id="textinput" name="textinput" type="text" placeholder="" value="{{$solicitude->monto}}"
                   readonly
                   class="form-control input-md">
        </div>
    </div>
</div>

<!--  Amount Factura -->
@if(isset($solicitude->monto_factura))
<div class="form-group col-sm-6 col-md-4 col-lg-4">
    <label class="col-sm-8 col-md-8 col-lg-8 control-label" for="textinput">Monto Factura</label>

    <div class="col-sm-12 col-md-12 col-lg-12">
        <input id="textinput" name="textinput" type="text" placeholder="" value="{{$solicitude->monto_factura}}"
               readonly
               class="form-control input-md">

    </div>
</div>
@endif

<!-- Date Delivery -->
<div class="form-group col-sm-6 col-md-4 col-lg-4">
    <label class="col-sm-8 col-md-8 col-lg-8 control-label" for="textinput">Fecha de Entrega</label>


    <div class="col-sm-12 col-md-12 col-lg-12">

        <div class="input-group date">
            <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
            <input id="date" type="text" class="form-control" maxlength="10" disabled placeholder=""
                   value="{{ date_format(date_create($solicitude->fecha_entrega), 'd/m/Y' )}}">
        </div>

    </div>
</div>

<!-- Date Created -->
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

<!-- Observation-->
<div class="form-group col-sm-6 col-md-4 col-lg-4">
    <label class="col-sm-8 col-md-8 col-lg-8 control-label" for="selectbasic">Observacion</label>

    <div class="col-sm-12 col-md-12 col-lg-12">
        @if($solicitude->estado == PENDIENTE)
        <textarea id="textinput" name="observacion" placeholder="" disabled
                  class="form-control"></textarea>
        @else
        <textarea id="textinput" name="observacion" placeholder=""
                  class="form-control" disabled>{{$solicitude->observacion}}</textarea>
        @endif
    </div>
</div>


@if(isset($solicitude) && $solicitude->idtiposolicitud == ACEPTADO)
<div class="form-group col-sm-6 col-md-4 col-lg-4">
    <label class="col-sm-8 col-md-8 col-lg-8 control-label" for="selectbasic">&nbsp;</label>
    <div class="col-sm-12 col-md-12 col-lg-12">
        <button class="btn btn-primary btn-md" data-toggle="modal" data-target="#myFac">
            Ver Comprobante
        </button>
    </div>
</div>
<div class="modal fade" id="myFac" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel">Comprobante</h4>
            </div>
            <div class="modal-body">
                @if (empty($solicitude->image))
                    <h3>No se ingreso una imagen</h3>
                @elseif (!file_exists(URL::to('img').'/reembolso/'.$solicitude->image))
                    <h3>No se encontro la imagen en el sistema</h3>
                @else
                    <img class="img-responsive" src="{{URL::to('img').'/reembolso/'.$solicitude->image}}">
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<div id="demoLightbox" class="lightbox hide fade"  tabindex="-1" role="dialog" aria-hidden="true">
    <div class='lightbox-content'>
        <img src="{{URL::to('/')}}/img/reembolso/{{$solicitude->image}}">
        <div class="lightbox-caption"><p>Your caption here</p></div>
    </div>
</div>
@endif


<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding: 0">
    <!-- Products -->
    <div class="form-group col-sm-12 col-md-6 col-lg-6">

        <div style="padding-left: 15px">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Productos</h3>
                </div>
                <div class="panel-body">

                    @foreach($solicitude->families as $family)
                    <div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12"  style="padding: 0">

                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                            <input id="textinput" name="textinput" type="text" placeholder=""
                                   value="{{$family->marca->descripcion}}" readonly
                                   class="form-control input-md">

                        </div>

                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Clients -->
    <div class="form-group col-sm-12 col-md-6 col-lg-6">

        <div style="padding:0 15px" >
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Clientes</h3>
                </div>
                <div class="panel-body">
                    @foreach($solicitude->clients as $client)
                    @if(!is_null($client->idcliente))
                    <div class="form-group ">
                        <div class="">
                            <input id="textinput" name="textinput" type="text" placeholder=""
                                   value="{{$client->client->clnombre}}" readonly
                                   class="form-control input-md ">
                        </div>
                    </div>
                    @else
                    <div class="form-group ">
                        <div class="">
                            <input id="textinput" name="textinput" type="text" placeholder=""
                                   value="No hay cliente asignado" readonly
                                   class="form-control input-md ">
                        </div>
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Description Solicitude -->
<div class="col-sm-12 col-md-12 col-lg-12" style="margin-top: 10px">
    <div class="form-group col-sm-12 col-md-12 col-lg-12">
        <label class="col-sm-8 col-md-8 col-lg-8 control-label" for="textarea">Descripcion de la
            Solicitud</label>

        <div class="col-sm-12 col-md-12 col-lg-12">
            <textarea class="form-control" id="textarea" name="textarea" readonly>{{$solicitude->descripcion}}</textarea>
        </div>
    </div>
</div>
<!-- Button (Double) -->
<div class="form-group col-sm-12 col-md-12 col-lg-12" style="margin-top: 20px">


    <div class="col-sm-12 col-md-12 col-lg-12" style="text-align: center">
        <a id="button2id" href="{{URL::to('show_rm')}}" name="button2id"
           class="btn btn-primary">Cancelar</a>
    </div>
</div>


</div>
</div>
</div>
@stop
<script>


</script>