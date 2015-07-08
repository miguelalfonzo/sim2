<div class='form-group col-xs-12 col-sm-6 col-md-4 col-lg-4'>
    <label class='control-label' for="monto">Monto Solicitado</label>
    <div>
        <input class="form-control input-md" name="monto" type="text"
        value="{{ isset( $detalle ) ? $detalle->monto_actual : null }}">
    </div>
</div>