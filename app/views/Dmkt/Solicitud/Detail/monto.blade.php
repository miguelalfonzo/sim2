<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
    <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12 control-label" for="amount">
        Monto
    </label>
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="input-group">
            <span class="input-group-addon" id="type-money">
                {{$solicitud->detalle->typeMoney->simbolo}}
            </span>
            @if ( $solicitud->idtiposolicitud == SOL_REP )
                @if ( $solicitud->idestado == ACEPTADO )
                    @if ( Auth::user()->type == GER_COM && $solicitud->idestado == ACEPTADO )
                        <input id="amount" value="{{$detalle->monto_aceptado}}"
                        class="form-control input-md" name="monto" type="text">
                    @else
                        <input id="amount" value="{{$detalle->monto_aceptado}}"
                        class="form-control input-md" name="monto" type="text" readonly>
                    @endif
                @elseif ( in_array( $solicitud->idestado , array( PENDIENTE, DERIVADO ) ) )
                    @if ( Auth::user()->type == SUP && $solicitud->idestado == PENDIENTE )
                        <input id="amount" value="{{$detalle->monto_solicitado}}"
                        class="form-control input-md" name="monto" type="text">
                    @elseif ( Auth::user()->type == GER_PROD && $solicitud->idestado == DERIVADO )
                        <input id="amount" value="{{$detalle->monto_solicitado}}"
                        class="form-control input-md" name="monto" type="text">
                    @else
                        <input id="amount" value="{{$detalle->monto_solicitado}}"
                        class="form-control input-md" name="monto" type="text" readonly>
                    @endif
                @elseif ( in_array($solicitud->idestado , array( RECHAZADO , CANCELADO ) ) )
                    @if (isset($detalle->monto_aceptado) )
                        <input id="amount" value="{{$detalle->monto_aceptado}}"
                        class="form-control input-md" name="monto" type="text" readonly>
                    @elseif ( isset($detalle->monto_solicitado) )
                        <input id="amount" value="{{$detalle->monto_solicitado}}"
                        class="form-control input-md" name="monto" type="text" readonly>
                    @endif
                @else
                    <input id="amount" value="{{$detalle->monto_aprobado}}"
                    class="form-control input-md" name="monto" type="text" readonly>
                @endif
            @elseif( $solicitud->idtiposolicitud == SOL_INST )
                <input value="{{$detalle->monto_aprobado}}"
                class="form-control input-md" type="text" readonly>
            @endif   
        </div>
    </div>
</div>