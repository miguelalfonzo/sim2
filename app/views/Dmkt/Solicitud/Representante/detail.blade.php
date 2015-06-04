<section class="row" style="padding:0.3em 1em">

    <!-- MOTIVO  -->
    @include( 'Dmkt.Solicitud.Detail.reason')

    <!-- INVERSION -->
    @include( 'Dmkt.Solicitud.Detail.investment')

    <!--TITULO  -->
    @include('Dmkt.Solicitud.Detail.titulo')

    <!-- MONTO -->
    @include('Dmkt.Solicitud.Detail.monto')

    <!-- TIPO DE PAGO -->
    <div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4" >
        <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12 control-label">Tipo de Entrega</label>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <input class="form-control" value="{{$solicitud->detalle->typePayment->nombre}}" disabled>
        </div>
    </div>

    <!-- PAGO CHEQUE : RUC -->
    @if( $solicitud->detalle->id_pago == PAGO_CHEQUE )
        <!-- Ruc -->
        <div class="form-group col-sm-6 col-md-4">
            <label class="col-sm-8 col-md-8 control-label">Ruc</label>
            <div class="col-sm-12 col-md-12">
                <input type="text" class="form-control input-md"
                value="{{ $detalle->num_ruc }}" maxlength="11" readonly>
            </div>
        </div>
    @endif

    <!-- FECHA DE ENTREGA -->
    <div class="form-group col-sm-6 col-md-4 col-lg-4">
        <label class="col-sm-8 col-md-8 col-lg-8 control-label">Fecha de Entrega</label>
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

    <!-- FECHA CREACION -->
    <div class="form-group col-sm-6 col-md-4 col-lg-4">
        <label class="col-sm-8 col-md-8 col-lg-8 control-label">Fecha de Creación</label>
        <div class="col-sm-12 col-md-12 col-lg-12">
            <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                <input type="text" class="form-control" maxlength="10" disabled
                value="{{ date_format( date_create( $solicitud->created_at ) , 'd/m/Y' ) }}">

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
    @include('Dmkt.Solicitud.Detail.anotation')


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
            <label class="col-sm-8 col-md-8 col-lg-8 control-label">
                Descripcion de la Solicitud
            </label>
            <div class="col-sm-12 col-md-12 col-lg-12">
                <textarea class="form-control" readonly>{{$solicitud->descripcion}}</textarea>
            </div>
        </div>
    </div>
    </section>