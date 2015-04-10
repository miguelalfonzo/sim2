<!--    Type Solicitude  -->
<div class="form-group col-sm-6 col-md-4 col-lg-4">
    <label class="col-sm-8 col-md-8 col-lg-4 control-label" for="textinput">Tipo Solicitud</label>

    <div class="col-sm-12 col-md-12">
        <input id="textinput" name="textinput" type="text" class="form-control input-md"
               value="{{$solicitude->detalle->typeReason->nombre}}" readonly>

    </div>
</div>

<!--    Type Payment -->

<div class="form-group col-sm-6 col-md-4" >

    <label class="col-sm-8 col-md-8 control-label" for="textinput">Tipo de Pago</label>

    <div class="col-sm-12 col-md-12">
        <select name="type_payment" class="form-control selectTypePayment" disabled>
            <option selected value="{{$solicitude->detalle->typePayment->id}}">{{$solicitude->detalle->typePayment->nombre}}</option>
        </select>
    </div>
</div>

<!-- Account Number -->
@if ( $solicitude->typeSolicitude->code == SOLIC && $solicitude->detalle->idpago == PAGO_DEPOSITO )
    <div class="form-group col-sm-6 col-md-4">
        <label class="col-sm-8 col-md-8 control-label" for="textinput">Nº de Cuenta</label>
        <div class="col-sm-12 col-md-12">
            <input id="number_account" name="number_account" class="form-control input-md" 
            value="{{$detalle->num_cuenta}}" readonly>
        </div>
    </div>
@elseif( $solicitude->typeSolicitude->code == SOLIC && $solicitude->detalle->idpago == PAGO_CHEQUE )
    <!-- Ruc -->
    <div class="form-group col-sm-6 col-md-4">
        <label class="col-sm-8 col-md-8 control-label" for="textinput">Ruc</label>

        <div class="col-sm-12 col-md-12">
            <input id="ruc" name="ruc" type="text" class="form-control input-md"
            value="{{ $detalle->num_ruc }}" maxlength="11" readonly>
        </div>
    </div>
@endif
@if( $solicitude->typeSolicitude->code == SOLIC && $solicitude->detalle->idmotivo == REASON_REGALOS )
    <!--  Amount Factura -->
    <div class="form-group col-sm-6 col-md-4 col-lg-4">
        <label class="col-sm-8 col-md-8 col-lg-8 control-label" for="textinput">
            Monto Factura
        </label>
        <div class="col-sm-12 col-md-12 col-lg-12">
            <input id="textinput" class="form-control input-md" name="textinput" type="text"
            value="{{$detalle->monto_factura}}" readonly>
        </div>
    </div>
@endif

<!--    Name Solicitude  -->
<div class="form-group col-sm-6 col-md-4 col-lg-4">

    <label class="col-sm-8 col-md-8 col-lg-8 control-label" for="textinput">Nombre Solicitud</label>

    <div class="col-sm-12 col-md-12 col-lg-12">
        <div class="input-group">
            <span class="input-group-addon">{{$solicitude->etiqueta->nombre}}</span>
            <input id="textinput" name="textinput" class="form-control input-md" type="text"
                   value="{{$solicitude->titulo}}" readonly>
        </div>
    </div>
</div>

<!--  Amount Solicitude -->
@include('template.monto')

@if ( Auth::user()->type == TESORERIA )
    <!--  Retencion      --> 
    @if ( !is_null($solicitude->detalle->idretencion) )
        <div class="form-group col-sm-6 col-md-4 col-lg-4">
            <label class="col-sm-8 col-md-8 control-label" for="textinput">
                {{$solicitude->detalle->typeRetention->descripcion}}
            </label>
            <div class="col-sm-12 col-md-12 col-lg-12">
                <div class="input-group">
                    <span class="input-group-addon">{{$solicitude->detalle->typemoney->simbolo}}</span>
                    <input type="text" value="{{$detalle->monto_retencion}}" class="form-control input-md" readonly>
                </div>
            </div>
        </div>
        <div class="form-group col-sm-6 col-md-4 col-lg-4">
            <label class="col-sm-8 col-md-8 control-label" for="textinput">
                A Depositar
            </label>
            <div class="col-sm-12 col-md-12 col-lg-12">
                <div class="input-group">
                    <span class="input-group-addon">{{$solicitude->detalle->typemoney->simbolo}}</span>
                    <input type="text" value="{{($detalle->monto_aprobado - $detalle->monto_retencion)}}" class="form-control input-md" readonly>
                </div>
            </div>
        </div>
    @else
        <div class="form-group col-sm-6 col-md-4 col-lg-4">
            <label class="col-sm-8 col-md-8 control-label" for="textinput">
                A Depositar
            </label>
            <div class="col-sm-12 col-md-12 col-lg-12">
                <div class="input-group">
                    <span class="input-group-addon">{{$solicitude->detalle->typemoney->simbolo}}</span>
                    <input type="text" value="{{$detalle->monto_aprobado}}" class="form-control input-md" readonly>
                </div>
            </div>
        </div>
    @endif
@endif


<!-- Date Delivery -->
<div class="form-group col-sm-6 col-md-4 col-lg-4">
    <label class="col-sm-8 col-md-8 col-lg-8 control-label" for="textinput">Fecha de Entrega</label>


    <div class="col-sm-12 col-md-12 col-lg-12">

        <div class="input-group date">
            <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
            <input id="date" type="text" class="form-control" maxlength="10" placeholder=""
            value="{{ date_format(date_create($detalle->fecha_entrega), 'd/m/Y' )}}" disabled>
        </div>

    </div>
</div>

<!-- Date Created -->
<div class="form-group col-sm-6 col-md-4 col-lg-4">
    <label class="col-sm-8 col-md-8 col-lg-8 control-label" for="selectbasic">Fecha de Creacion</label>

    <div class="col-sm-12 col-md-12 col-lg-12">
        <div class="input-group date">
            <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
            <input id="date" type="text" class="form-control" maxlength="10" placeholder=""
                   value="{{ date_format(date_create($solicitude->created_at), 'd/m/Y' )}}" disabled>

        </div>
    </div>
</div>

<!-- Solicitante -->
@include('template.solicitante')

@if ( isset($fondos) || !is_null($solicitude->detalle->idfondo) )
    <div class="form-group col-sm-6 col-md-4">
        <label class="col-sm-8 col-md-8 control-label" for="textinput">Fondo</label>
        <div class="col-sm-12 col-md-12">
            @if($solicitude->idestado == PENDIENTE || $solicitude->idestado == DERIVADO)
                <select id="sub_type_activity" name="idfondo" class="form-control">
            @else
                <select id="sub_type_activity" name="idfondo" class="form-control" disabled>
            @endif
                @if ( isset($fondos) )
                    @foreach($fondos as $sub)
                        @if( $sub->idusertype == SUP )
                            <option value="{{$sub->id}}" style="background:#A9F5F2" selected>
                                {{$sub->nombre}} -> {{$sub->saldo}}
                            </option>
                        @elseif ( $sub->id == $solicitude->detalle->idfondo )
                            <option value="{{$sub->id}}" style="background:#A9F5F2" selected>
                                {{$sub->nombre}} -> {{$sub->saldo}}
                            </option>
                        @else
                            <option value="{{$sub->id}}">
                                {{$sub->nombre}} -> {{$sub->saldo}}
                            </option>                          
                        @endif
                    @endforeach
                @else
                    <option value="{{$solicitude->detalle->idfondo}}" selected>
                        {{$solicitude->detalle->fondo->nombre}} -> {{$solicitude->detalle->fondo->saldo}}
                    </option>
                @endif    
            </select>
        </div>
    </div>
@endif

<!-- Observation-->
@include('template.obs')
@if( $solicitude->typeSolicitude->code == SOLIC && $solicitude->detalle->idmotivo == REASON_REGALOS )
    <div class="form-group col-sm-6 col-md-4 col-lg-4">
        <label class="col-sm-8 col-md-8 col-lg-8 control-label">&nbsp;</label>
        <div class="col-sm-12 col-md-12 col-lg-12">
            <a class="btn btn-primary btn-md" data-toggle="modal" data-target="#myFac">
                Ver Comprobante
            </a>
        </div>
    </div>

    <!-- Modal -->
    @if( Auth::user()->type == TESORERIA && $solicitude->idestado == DEPOSITO_HABILITADO )
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title" id="myModalLabel">Registro del Depósito</h4>
                    </div>
                    <div class="modal-body">
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


<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding: 0">
    <!-- Products -->
    @include('template.view_products')

    <!-- Clients -->
    @include('template.list_clients')
</div>

<!-- Description Solicitude -->
<div class="col-sm-12 col-md-12 col-lg-12" style="margin-top: 10px">
    <div class="form-group col-sm-12 col-md-12 col-lg-12">
        <label class="col-sm-8 col-md-8 col-lg-8 control-label" for="textarea">
            Descripcion de la Solicitud
        </label>
        <div class="col-sm-12 col-md-12 col-lg-12">
            <textarea class="form-control" id="textarea" name="textarea" readonly>{{$solicitude->descripcion}}</textarea>
        </div>
    </div>
</div>