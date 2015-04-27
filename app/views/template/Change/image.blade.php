@if ( !isset( $solicitude ) || ( isset( $solicitude ) &&  $solicitude->typeSolicitude->code == SOLIC && $solicitude->detalle->idmotivo != REASON_REGALO ) )
    <div id="comprobante" class="form-group col-sm-6 col-md-4">
        <label class="col-sm-12 col-md-12 control-label" for="textinput">Factura
            <small>(solo imagenes)</small>
        </label>
        <div class="col-sm-12 col-md-12">
            <div class="input-group">
                <span class="input-group-btn">
                     <span class="btn btn-primary btn-file">
                         <i class="glyphicon glyphicon-folder-open"></i>
                         <input type="file" multiple="" name="file">
                     </span>
                </span>
                <input type="text" id="input-file-factura" class="form-control" readonly>
            </div>
        </div>
    </div>
@endif