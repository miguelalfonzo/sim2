<div class='form-group col-xs-12 col-sm-6 col-md-4 col-lg-4'>
    <label class='col-xs-12 col-sm-12 col-md-12 col-lg-12 control-label' for="monto">Monto Solicitado</label>
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <input class="form-control input-md" name="monto" type="text"
        value="{{ isset( $detalle->monto_solicitado ) ? $detalle->monto_solicitado : null }}">
    </div>
</div>