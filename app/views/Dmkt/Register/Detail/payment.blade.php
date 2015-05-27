<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
    <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12 control-label">Tipo de Entrega</label>
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <select name="pago" class="form-control">
            @foreach( $payments as $payment )
                @if ( $payment->id != 3 )
                    @if( isset( $solicitud ) && $solicitud->detalle->id_pago == $payment->id )
                        <option value="{{$payment->id}}" selected>{{$payment->nombre}}</option>
                    @else
                        <option value="{{$payment->id}}">{{$payment->nombre}}</option>
                    @endif
                @endif
            @endforeach
        </select>
    </div>
</div>