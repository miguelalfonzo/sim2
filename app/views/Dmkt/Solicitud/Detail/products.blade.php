<div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
    <div style="padding: 0 15px">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Productos</h3>
            </div>
            <div class="panel-body">
                @foreach( $solicitud->products as $product )
                    <div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12"  style="padding: 0">
                        <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                            <input type="text" class="form-control input-md"
                            value="{{{ is_null( $product->marca ) ? '' : $product->marca->descripcion}}}" readonly>
                        </div>
                        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4" style="padding: 0">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    {{ $detalle->typemoney->simbolo }}
                                </span>
                                @if ( $politicStatus )
                                    <input name="monto_producto[]" type="text"
                                    class="form-control input-md amount_families"
                                    value="{{ isset( $product->monto_asignado ) ? $product->monto_asignado : 
                                    round( $detalle->monto_actual / count( $solicitud->products ) , 2 ) }}">
                                @else
                                    <input disabled name="monto_producto[]" type="text"
                                    class="form-control input-md amount_families"
                                    value="{{ isset( $product->monto_asignado ) ? $product->monto_asignado :
                                    round( $detalle->monto_actual / count( $solicitud->products ) , 2 ) }}">
                                @endif
                            </div>
                            <input type="hidden" name="producto[]" value="{{ $product->id }}">
                        </div>
                    </div>
                @endforeach
                <div class="form-group col-sm-12 col-md-12 col-lg-12">
                    <span id="amount_error_families"></span>
                </div>
            </div>
        </div>
    </div>
</div>