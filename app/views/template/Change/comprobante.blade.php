 @if( isset($solicitude) && (  $solicitude->typeSolicitude->code == SOLIC && $solicitude->detalle->idmotivo == REASON_REGALO )  )
    <div id="comprobante" class="form-group col-sm-6 col-md-4">
        <div class="col-sm-12 col-md-12">
            <label class="col-sm-8 col-md-8 control-label" for="textinput"> </label>
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
                        <img id="imgSalida" class="img-responsive" src="{{asset(IMAGE_PATH.$detalle->image)}}">
                    @endif 
                </div>
                <div class="modal-footer">
                    <div class="form-group col-sm-10 col-md-10" style="padding-right: 30px">
                        <label class="col-sm-6 col-md-4 control-label" for="textinput">Subir Otra Factura</label>
                        <div class="col-sm-5 col-md-7">
                            @if (isset($detalle->image))
                                <input id="isSetImage" type="hidden" value="{{$detalle->image}}">
                            @else
                                <input id="isSetImage" type="hidden" value="">
                            @endif
                            <input type="file" id="input-file-factura" name="file" class="form-control" style="padding:1px">
                        </div>
                    </div>
                    <div class="form-group col-sm-1 col-md-1">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Aceptar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif