@if ( ( ! is_null( $solicitud->anotacion ) || ! is_null( $solicitud->observacion ) ) || $politicStatus )
    <div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12">
        @if ( is_null( $solicitud->observacion ) )
            <label class="control-label" for="anotacion"><strong>Anotación</strong></label>
        @else
            <label class="control-label" for="observacion"><strong>Observación</strong></label>
        @endif
        <div>
            @if ( is_null( $solicitud->observacion ) )
                @if ( isset( $politicStatus ) && $politicStatus )
                    <textarea class="form-control" name="anotacion" maxlength="200">{{ $solicitud->anotacion }}</textarea>
                @else
                    <textarea class="form-control" name="anotacion" maxlength="200" disabled style="resize:both">{{ $solicitud->anotacion }}</textarea>
                @endif
            @else
                <textarea class="form-control" name="anotacion" maxlength="200" disabled style="resize:both">{{ $solicitud->observacion }}</textarea>
            @endif        
        </div>
    </div>
@endif