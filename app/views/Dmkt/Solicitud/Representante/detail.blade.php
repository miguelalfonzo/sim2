<section class="row" style="padding:0.3em 1em">
    <!-- TITULO -->
    <div class="page-header">
        <h2>{{$solicitud->titulo}} <span class="label label-default">{{$solicitud->activity->nombre}}</span></h2>
    </div>
    
    <!-- MOTIVO  -->
    <div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
        <label class="control-label">Motivo</label>
        <input type="text" class="form-control" value="{{$detalle->reason->nombre}}" readonly>
    </div>

    <!-- INVERSION -->
    <div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
        <label class="control-label">Tipo de Inversión</label>
        <input type="text" class="form-control input-md" value="{{$solicitud->investment->nombre}}" readonly>
    </div>

    <!-- MONTO -->
    @include('Dmkt.Solicitud.Detail.monto')

    <!-- TIPO DE PAGO -->
    <div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4" >
        <label class="control-label">Tipo de Entrega</label>
        <input class="form-control" value="{{$solicitud->detalle->typePayment->nombre}}" disabled>
    </div>

    <!-- PAGO CHEQUE : RUC -->
    @if( $solicitud->detalle->id_pago == PAGO_CHEQUE )
        <!-- Ruc -->
        <div class="form-group col-sm-6 col-md-4">
            <label class="control-label">Ruc</label>
            <input type="text" class="form-control input-md" value="{{ $detalle->num_ruc }}" maxlength="11" readonly>
        </div>
    @endif

    <!-- FECHA DE CREACION / FECHA DE ENTREGA -->
    @include('Dmkt.Solicitud.Detail.fecha')

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
    @include('Dmkt.Solicitud.Detail.anotation')

    <div class="clearfix"></div>
    
    <!-- PRODUCTOS -->
    @include('Dmkt.Solicitud.Detail.products')

    <!-- CLIENTES -->
    @include('Dmkt.Solicitud.Detail.clients')
        
    <div class="form-group col-sm-12 col-md-12 col-lg-12">
        <span id="amount_error_families"></span>
    </div>
    
    <!-- Description Solicitude -->
    <div class="form-group col-sm-12 col-md-12 col-lg-12">
        <label class="control-label">
            Descripcion de la Solicitud
        </label>
        <textarea class="form-control col-sm-12 col-md-12 col-lg-12" readonly>{{$solicitud->descripcion}}</textarea>
    </div>
</section>