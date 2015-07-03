@if ( ( ! is_null( $solicitud->anotacion ) || ! is_null( $solicitud->observacion ) ) || $politicStatus )
    <div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
        @if ( is_null( $solicitud->observacion ) )
            <label class="control-label" for="anotacion">Anotación</label>
        @else
            <label class="control-label" for="observacion">Onservación</label>
        @endif
        <div>
            @if ( is_null( $solicitud->observacion ) )
                @if ( $politicStatus )
                    <textarea class="form-control" name="anotacion" maxlength="200">{{ $solicitud->anotacion }}</textarea>
                @else
                    <textarea class="form-control" name="anotacion" maxlength="200"  disabled>{{ $solicitud->anotacion }}</textarea>
                @endif
            @else
                <textarea class="form-control" name="anotacion" maxlength="200"  disabled>{{ $solicitud->observacion }}</textarea>
            @endif        
        </div>
    </div>
@endif