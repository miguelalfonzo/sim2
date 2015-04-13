@if( isset($detalle->tcc) && isset($detalle->tcv) )    
    <div class="form-group col-sm-6 col-md-4 col-lg-4">
        <label class="col-sm-8 col-md-8 control-label">
            Tasa de Cambio (S/. por $)
        </label>
        <div class="col-sm-12 col-md-12 col-lg-12">
            <div class="input-group">
                <span class="input-group-addon">COMPRA</span>
                <input type="text" value="{{$detalle->tcc}}" class="form-control input-md" readonly>
                <span class="input-group-addon">VENTA</span>
                <input type="text" value="{{$detalle->tcv}}" class="form-control input-md" readonly>
            </div>
        </div>
    </div>
@endif