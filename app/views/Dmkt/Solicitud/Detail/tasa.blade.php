@if( ! is_null( $detalle->id_deposito ) )    
    <div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
        <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12 control-label">
            Tasa de Cambio
        </label>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="input-group">
                <span class="input-group-addon">COMPRA</span>
                <input type="text" value="{{$detalle->tcc}}" class="form-control input-md" readonly>
                <span class="input-group-addon">VENTA</span>
                <input type="text" value="{{$detalle->tcv}}" class="form-control input-md" readonly>
            </div>
        </div>
    </div>
@endif