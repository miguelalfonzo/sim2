@if  ( in_array( $solicitud->id_estado , array( PENDIENTE , DERIVADO , ACEPTADO , APROBADO ) )
    && $solicitud->aprovalPolicy( $solicitud->histories->count() )->tipo_usuario === Auth::user()->type
    && in_array( Auth::user()->id , $solicitud->gerente->lists( 'id_gerprod' ) ) )
	<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
	    <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12 control-label" for="observacion">Observacion</label>
	    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
	        <textarea class="form-control" name="observacion" maxlength="200">{{ $solicitud->observacion }}</textarea>
	    </div>
	</div>
@else
    <div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
        <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12 control-label" for="observacion">Observacion</label>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <textarea class="form-control" name="observacion" maxlength="200"  disabled>{{ $solicitud->observacion }}</textarea>
        </div>
    </div>
@endif