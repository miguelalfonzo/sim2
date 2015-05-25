<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
    <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12 control-label" for="amount">
        Monto
    </label>
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="input-group">
            <span class="input-group-addon" id="type-money">
                {{$solicitud->detalle->typeMoney->simbolo}}
            </span>
            @if ( in_array( $solicitud->id_estado , array( PENDIENTE , DERIVADO , ACEPTADO ) )
                && in_array( $solicitud->aprovalPolicy( $solicitud->histories->count() )->tipo_usuario , array( Auth::user()->type , Auth::user()->tempType() ) )
                && ( array_intersect ( array( Auth::user()->id , Auth::user()->tempId() ) , $solicitud->managerEdit->lists( 'id_gerprod' ) ) ) )
                <input id="amount" value="{{$detalle->monto_actual}}"
                class="form-control input-md" name="monto" type="text">
            @else
                <input id="amount" value="{{$detalle->monto_actual}}"
                class="form-control input-md" name="monto" type="text" readonly>
            @endif
        </div>
    </div>
</div>