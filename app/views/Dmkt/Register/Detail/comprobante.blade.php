 @if( isset($solicitude) && (  $solicitude->idtiposolicitud == SOL_REP && $solicitude->detalle->idmotivo == REASON_REGALO )  )
    <div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12 control-label"> </label>
            <a class="btn btn-primary btn-md" data-toggle="modal" data-target="#myFac">
                Ver Comprobante
            </a>
        </div>
    </div>
    <div class="modal fade" id="myFac" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                        <span class="sr-only">Close</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel">Comprobante</h4>
                </div>
                <div class="modal-body">
                    @if (!isset($detalle->image))
                        <h3>No se ingreso una imagen</h3>
                    @elseif (!file_exists(public_path().'/'.IMAGE_PATH.$detalle->image))
                        <h3>No se encontro la imagen en el sistema</h3>
                    @else
                        <img class="img-responsive" src="{{asset(IMAGE_PATH.$detalle->image)}}">
                    @endif 
                </div>
                <div class="modal-footer">
                    <div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding-right: 30px">
                        <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12 control-label" for="file">Subir Otra Factura</label>
                        <div class="col-xs-10 col-sm-8 col-md-6 col-lg-6">
                            @if (isset($detalle->image))
                                <input type="hidden" value="{{$detalle->image}}">
                            @else
                                <input type="hidden" value="">
                            @endif
                            <input type="file" name="factura" class="form-control" style="padding:1px">
                        </div>
                    </div>
                    <div class="form-group col-sm-2 col-md-2">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Aceptar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif