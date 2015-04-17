<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
    <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12 control-label" for="textinput">Etiqueta</label>
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <select class="form-control selectetiqueta" name="etiqueta">
            @foreach($etiquetas as $etiqueta)
                @if ( isset( $solicitude ) )
                    @if ( $solicitude->idetiqueta == $etiqueta->id )
                        <option selected value="{{$etiqueta->id}}">{{ $etiqueta->nombre }}</option>
                    @else
                        <option value="{{$etiqueta->id}}">{{ $etiqueta->nombre }}</option>
                    @endif
                @else
                    <option value="{{$etiqueta->id}}">{{ $etiqueta->nombre }}</option>
                @endif
            @endforeach
        </select>
    </div>
</div>