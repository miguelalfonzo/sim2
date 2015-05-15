@if ( !isset( $solicitude ) || ( isset( $solicitude ) &&  $solicitude->idtiposolicitud == SOL_REP && $solicitude->detalle->idmotivo != REASON_REGALO ) )
    <div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
        <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12 control-label" for="file">Factura
            <small>(solo imagenes)</small>
        </label>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="input-group">
                <span class="input-group-btn">
                     <span class="btn btn-primary btn-file" style="font-size:13px">
                         <i class="glyphicon glyphicon-folder-open"></i>
                         <input type="file" name="factura">
                     </span>
                </span>
                <input type="text" class="form-control" readonly>
            </div>
        </div>
    </div>
@endif