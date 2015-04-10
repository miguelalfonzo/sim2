<div class="form-group col-sm-6 col-md-4 col-lg-4">
    <label class="col-sm-8 col-md-8 col-lg-8 control-label" for="amount">
        Monto
    </label>
    <div class="col-sm-12 col-md-12 col-lg-12">
        <div class="input-group">
            <span class="input-group-addon">
                {{$solicitude->detalle->typeMoney->simbolo}}
            </span>
            @if ( $solicitude->idestado == ACEPTADO )
                @if ( Auth::user()->type == GER_COM && $solicitude->idestado == ACEPTADO )
                    <input id="amount" value="{{$detalle->monto_aceptado}}"
                    class="form-control input-md" name="monto" type="text">
                @else
                    <input id="amount" value="{{$detalle->monto_aceptado}}"
                    class="form-control input-md" name="monto" type="text" readonly>
                @endif
            @elseif ( in_array( $solicitude->idestado , array( PENDIENTE, DERIVADO ) ) )
                @if ( Auth::user()->type == SUP && $solicitude->idestado == PENDIENTE )
                    <input id="amount" value="{{$detalle->monto_solicitado}}"
                    class="form-control input-md" name="monto" type="text">
                @elseif ( Auth::user()->type == GER_PROD && $solicitude->idestado == DERIVADO )
                    <input id="amount" value="{{$detalle->monto_solicitado}}"
                    class="form-control input-md" name="monto" type="text">
                @else
                    <input id="amount" value="{{$detalle->monto_solicitado}}"
                    class="form-control input-md" name="monto" type="text" readonly>
                @endif
            @elseif ( in_array($solicitude->idestado , array( RECHAZADO , CANCELADO ) ) )
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
        </div>
    </div>
</div>