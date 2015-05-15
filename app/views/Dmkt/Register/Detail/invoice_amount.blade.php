<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
    <label class="col-xs-12 col'-sm-12 col-md-12 col-lg-12 control-label" for="monto_factura">Monto Factura</label>
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <input class="form-control input-md" name="monto_factura" type="text"
        value="{{isset($detalle->monto_factura) ? $detalle->monto_factura : null }}">
    </div>
</div>