@if ( $solicitud->idtiposolicitud == SOL_REP && ! in_array( NULL , $solicitud->products()->lists( 'id_fondo_marketing') ) )
    <div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
        <label class="control-label">Fondo</label>
        <input class="form-control" value="{{$solicitud->products[ 0 ]->thisSubFondo->subCategoria->accountFondo->nombre}}" disabled>
    </div>
@elseif ( $solicitud->idtiposolicitud == SOL_INST && ! is_null( $solicitud->detalle->id_fondo ) )
    <div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
        <label class="control-label">Fondo</label>
        <input class="form-control" value="{{$solicitud->detalle->thisSubFondo->subCategoria->accountFondo->nombre}}" disabled>
    </div>
@endif