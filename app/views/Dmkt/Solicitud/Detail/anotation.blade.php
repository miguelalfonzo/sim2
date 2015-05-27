@if ( ! is_null( $solicitud->anotacion ) || ! is_null( $solicitud->observacion ) )
    <div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
        <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12 control-label" for="observacion">Anotación</label>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            @if ( ! is_null( $solicitud->anotacion ) )
                @if ( in_array( $solicitud->id_estado , array( PENDIENTE , DERIVADO , ACEPTADO ) )
                    && in_array( $solicitud->aprovalPolicy( $solicitud->histories->count() )->tipo_usuario , array( Auth::user()->type , Auth::user()->tempType() ) )
                    && ( array_intersect ( array( Auth::user()->id , Auth::user()->tempId() ) , $solicitud->managerEdit->lists( 'id_gerprod' ) ) ) )
                    <textarea class="form-control" name="observacion" maxlength="200">{{ $solicitud->anotacion }}</textarea>
                @else
                    <textarea class="form-control" name="observacion" maxlength="200"  disabled>{{ $solicitud->anotacion }}</textarea>
                @endif
            @else
                <textarea class="form-control" name="observacion" maxlength="200"  disabled>{{ $solicitud->observacion }}</textarea>
            @endif        
        </div>
    </div>
@endif