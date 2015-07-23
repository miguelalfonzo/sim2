@if ( $solicitud->idtiposolicitud == SOL_REP && ! in_array( NULL , $solicitud->products()->lists( 'id_fondo_marketing') ) )
    <div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
        <label class="control-label">Fondo Marketing</label>
        <input class="form-control" value="{{$solicitud->products[ 0 ]->thisSubFondo->subCategoria->descripcion }}" disabled>
    </div>
@elseif ( $solicitud->idtiposolicitud == SOL_INST && ! is_null( $solicitud->detalle->id_fondo ) )
    <div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
        <label class="control-label">Fondo Marketing</label>
        <input class="form-control" value="{{$solicitud->detalle->thisSubFondo->subCategoria->descripcion }}" disabled>
    </div>
@endif