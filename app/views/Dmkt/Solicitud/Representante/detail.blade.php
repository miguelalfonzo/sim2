<!--    Name Solicitude  -->
@include('Dmkt.Solicitud.Detail.titulo')

<!--    Type Solicitude  -->
<div class="form-group col-sm-6 col-md-4 col-lg-4">
    <label class="col-sm-8 col-md-8 col-lg-4 control-label" for="reason">Tipo Solicitud</label>
    <div class="col-sm-12 col-md-12">
        <input id="reason" type="text" class="form-control input-md"
        value="{{$solicitud->detalle->typeReason->nombre}}" readonly>
    </div>
</div>

<!--    Type Payment -->
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
@if ( $solicitud->typeSolicitude->code == SOLIC && $solicitud->detalle->idpago == PAGO_DEPOSITO )
    <div class="form-group col-sm-6 col-md-4">
        <label class="col-sm-8 col-md-8 control-label" for="cuenta">Nº de Cuenta</label>
        <div class="col-sm-12 col-md-12">
            <input id="cuenta" class="form-control input-md" 
            value="{{$detalle->num_cuenta}}" readonly>
        </div>
    </div>
@elseif( $solicitud->typeSolicitude->code == SOLIC && $solicitud->detalle->idpago == PAGO_CHEQUE )
    <!-- Ruc -->
    <div class="form-group col-sm-6 col-md-4">
        <label class="col-sm-8 col-md-8 control-label" for="ruc">Ruc</label>
        <div class="col-sm-12 col-md-12">
            <input id="ruc" type="text" class="form-control input-md"
            value="{{ $detalle->num_ruc }}" maxlength="11" readonly>
        </div>
    </div>
@endif

@if( $solicitud->typeSolicitude->code == SOLIC && $solicitud->detalle->idmotivo == REASON_REGALO )
    <!--  Amount Factura -->
    <div class="form-group col-sm-6 col-md-4 col-lg-4">
        <label class="col-sm-8 col-md-8 col-lg-8 control-label" for="factura">
            Monto Factura
        </label>
        <div class="col-sm-12 col-md-12 col-lg-12">
            <div class="input-group">
                <span class="input-group-addon">{{$solicitud->detalle->typeMoney->simbolo}}</span>
                <input id="factura" class="form-control input-md" type="text"
                value="{{$detalle->monto_factura}}" readonly>
            </div>
        </div>
    </div>
@endif

<!--  Amount Solicitude -->
@include('Dmkt.Solicitud.Detail.monto')

<!--  Retencion      --> 
@if ( !is_null( $solicitud->detalle->idretencion ) )
    <div class="form-group col-sm-6 col-md-4 col-lg-4">
        <label class="col-sm-8 col-md-8 control-label" for="retencion">
            {{$solicitud->detalle->typeRetention->descripcion}}
        </label>
        <div class="col-sm-12 col-md-12 col-lg-12">
            <div class="input-group">
                <span class="input-group-addon">{{$solicitud->detalle->typeRetention->account->typemoney->simbolo}}</span>
                <input id="retencion" type="text" value="{{$detalle->monto_retencion}}" class="form-control input-md" readonly>
            </div>
        </div>
    </div>
@endif

<!-- A Depositar -->
@if ( Auth::user()->type == TESORERIA && $solicitud->idestado == DEPOSITO_HABILITADO )
    <div class="form-group col-xs-4 col-sm-4 col-md-4 col-lg-4">
        <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12 control-label" for="depositar">
            A Depositar
        </label>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="input-group">
                <span class="input-group-addon">{{$solicitud->detalle->typemoney->simbolo}}</span>
                <input id="depositar" type="text" value="{{($deposito)}}" class="form-control input-md" readonly>
            </div>
        </div>
    </div>
@endif

<!-- Date Delivery -->
<div class="form-group col-sm-6 col-md-4 col-lg-4">
    <label class="col-sm-8 col-md-8 col-lg-8 control-label" for="date">Fecha de Entrega</label>
    <div class="col-sm-12 col-md-12 col-lg-12">
        <div class="input-group date">
            <span class="input-group-addon">
                <i class="glyphicon glyphicon-calendar"></i>
            </span>
            <input id="date" type="text" class="form-control" maxlength="10" disabled
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
            <input id="date" type="text" class="form-control" maxlength="10" placeholder=""
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

@if(!is_null($solicitud->detalle->iddeposito) )    
    <div class="form-group col-sm-6 col-md-4 col-lg-4">
        <label class="col-sm-8 col-md-8 control-label" for="depositado">
            Depositado
        </label>
        <div class="col-sm-12 col-md-12 col-lg-12">
            <div class="input-group">
                <span class="input-group-addon">{{$solicitud->detalle->deposit->account->typeMoney->simbolo}}</span>
                <input id="depositado" type="text" value="{{$solicitud->detalle->deposit->total}}" class="form-control input-md" readonly>
            </div>
        </div>
    </div>
@endif

@include('Dmkt.Solicitud.Detail.tasa')

<!-- Observation-->
@include('Dmkt.Solicitud.Detail.observacion')


@if( $solicitud->typeSolicitude->code == SOLIC && $solicitud->detalle->idmotivo == REASON_REGALO )
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


<!-- Modal -->
@if( Auth::user()->type == TESORERIA && $solicitud->idestado == DEPOSITO_HABILITADO )
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                        <span class="sr-only">Close</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel">Registro del Depósito</h4>
                </div>
                <div class="modal-body">
                    <label>Bancos</label>
                    <select id="bank_account" name="bank_account" class="form-control">
                        @foreach ( $banks as $bank )
                            <option value="{{$bank->id}}">
                                {{$bank->typeMoney->simbolo.'-'.$bank->nombre}}
                            </option>
                        @endforeach
                    </select>
                    <br>
                    <label for="op-number">Número de Operación, Transacción, Cheque:</label>
                    <input id="op-number" type="text" class="form-control">
                    <p id="message-op-number" style="margin-top:1em;color:#a94442;"></p>
                </div>
                <div class="modal-footer">
                    <a id="" href="#" class="btn btn-success register-deposit" data-deposit="S" style="margin-right: 1em;">Confirmar Operación</a>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
@endif

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

<!-- RETENCION -->
@include('Dmkt.Solicitud.Detail.retencion')