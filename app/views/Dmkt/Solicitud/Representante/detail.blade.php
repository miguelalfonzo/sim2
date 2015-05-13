<section class="row" style="padding:0.3em 1em">
<!--    Name Solicitude  -->
@include('Dmkt.Solicitud.Detail.titulo')

<!-- MOTIVO  -->
<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
    <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12 control-label" for="reason">Motivo</label>
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <input id="reason" type="text" class="form-control input-md"
        value="{{$solicitud->detalle->reason->nombre}}" readonly>
    </div>
</div>

<!-- Type Payment -->
<div class="form-group col-sm-6 col-md-4" >
    <label class="col-sm-8 col-md-8 control-label">Tipo de Pago</label>
    <div class="col-sm-12 col-md-12">
        <select class="form-control selectTypePayment" disabled>
            <option selected value="{{$solicitud->detalle->typePayment->id}}">
                {{$solicitud->detalle->typePayment->nombre}}
            </option>
        </select>
    </div>
</div>

<!-- Account Number -->
@if ( $solicitud->detalle->idpago == PAGO_DEPOSITO )
    <div class="form-group col-sm-6 col-md-4">
        <label class="col-sm-8 col-md-8 control-label" for="cuenta">Nº de Cuenta</label>
        <div class="col-sm-12 col-md-12">
            <input id="cuenta" class="form-control input-md" 
            value="{{ $detalle->num_cuenta }}" readonly>
        </div>
    </div>
@elseif( $solicitud->detalle->idpago == PAGO_CHEQUE )
    <!-- Ruc -->
    <div class="form-group col-sm-6 col-md-4">
        <label class="col-sm-8 col-md-8 control-label" for="ruc">Ruc</label>
        <div class="col-sm-12 col-md-12">
            <input type="text" class="form-control input-md"
            value="{{ $detalle->num_ruc }}" maxlength="11" readonly>
        </div>
    </div>
@endif

<!--  Amount Factura -->
@if( $solicitud->detalle->idmotivo == REASON_REGALO )
    <div class="form-group col-sm-6 col-md-4 col-lg-4">
        <label class="col-sm-8 col-md-8 col-lg-8 control-label" for="factura">
            Monto Factura
        </label>
        <div class="col-sm-12 col-md-12 col-lg-12">
            <div class="input-group">
                <span class="input-group-addon">{{$solicitud->detalle->typeMoney->simbolo}}</span>
                <input id="factura" class="form-control input-md" type="text"
                value="{{ $detalle->monto_factura }}" readonly>
            </div>
        </div>
    </div>
@endif

<!--  Amount Solicitude -->
@include('Dmkt.Solicitud.Detail.monto')

<!-- Date Delivery -->
<div class="form-group col-sm-6 col-md-4 col-lg-4">
    <label class="col-sm-8 col-md-8 col-lg-8 control-label" for="date">Fecha de Entrega</label>
    <div class="col-sm-12 col-md-12 col-lg-12">
        <div class="input-group date">
            <span class="input-group-addon">
                <i class="glyphicon glyphicon-calendar"></i>
            </span>
            <input type="text" class="form-control" maxlength="10" disabled
            value="{{$detalle->fecha_entrega}}">
        </div>
    </div>
</div>

<!-- Date Created -->
<div class="form-group col-sm-6 col-md-4 col-lg-4">
    <label class="col-sm-8 col-md-8 col-lg-8 control-label" for="selectbasic">Fecha de Creacion</label>

    <div class="col-sm-12 col-md-12 col-lg-12">
        <div class="input-group">
            <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
            <input type="text" class="form-control" maxlength="10" placeholder=""
                   value="{{ date_format(date_create($solicitud->created_at), 'd/m/Y' )}}" disabled>

        </div>
    </div>
</div>

<!-- Solicitante -->
@include('Dmkt.Solicitud.Detail.solicitante')

<!-- Asignado a -->
@include('Dmkt.Solicitud.Detail.asignado')

<!-- Aceptador Por -->
@include('Dmkt.Solicitud.Detail.accepted')

<!-- Fondos -->
@include('Dmkt.Solicitud.Detail.fondo')

<!-- Depositado -->
@include('Dmkt.Solicitud.Detail.depositado')

<!-- Tasa de Cambio del Dia del Deposito -->
@include('Dmkt.Solicitud.Detail.tasa')

<!-- Observation-->
@include('Dmkt.Solicitud.Detail.observacion')


@if( $solicitud->actividad->imagen == 1 )
    <div class="form-group col-sm-6 col-md-4 col-lg-4">
        <label class="col-sm-8 col-md-8 col-lg-8 control-label">&nbsp;</label>
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
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                        <span>Close</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel">Comprobante</h4>
                </div>
                <div class="modal-body">
                    @if (empty($detalle->image))
                        <h3>No se ingreso una imagen</h3>
                    @elseif (!file_exists(public_path().'/'.IMAGE_PATH.$detalle->image))
                        <h3>No se encontro la imagen en el sistema</h3>
                    @else
                        <img class="img-responsive" src="{{asset(IMAGE_PATH.$detalle->image)}}">
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
            <img src="{{URL::to('/')}}/img/reembolso/{{$detalle->image}}">
            <div class="lightbox-caption">
                <p>Your caption here</p>
            </div>
        </div>
    </div>
@endif


<!-- Modal Deposito -->
@include('template.Modals.deposit-min')

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding: 0">
    <!-- Products -->
    @include('Dmkt.Solicitud.Detail.products')

    <!-- Clients -->
    @include('Dmkt.Solicitud.Detail.clients')
</div>

<!-- Description Solicitude -->
<div class="col-sm-12 col-md-12 col-lg-12" style="margin-top: 10px">
    <div class="form-group col-sm-12 col-md-12 col-lg-12">
        <label class="col-sm-8 col-md-8 col-lg-8 control-label" for="textarea">
            Descripcion de la Solicitud
        </label>
        <div class="col-sm-12 col-md-12 col-lg-12">
            <textarea class="form-control" id="textarea" name="textarea" readonly>{{$solicitud->descripcion}}</textarea>
        </div>
    </div>
</div>
</section>