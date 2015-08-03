@if ( $solicitud->id_estado == DEVOLUCION )
	<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4 has-warning">
	    <label class="control-label">Monto a Devolver</label>
	    <div class="input-group">
	    	<span class="input-group-addon">{{$solicitud->detalle->typeMoney->simbolo}}</span>
	        <input type="text" class="form-control input-md" name="monto-devolucion"
	        value="{{ $solicitud->detalle->monto_aprobado - $solicitud->expenses->sum( 'monto' ) }}" readonly>
	    </div>
	</div>
@endif