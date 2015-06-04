<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
    <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12 control-label">Tipo de Actividad</label>
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <select class="form-control" name="actividad">
            @if ( isset( $solicitud) )
                <option value="" disabled style="display:none">SELECCIONE LA ACTIVIDAD</option>
            @else
                <option value="" disabled selected style="display:none">SELECCIONE LA ACTIVIDAD</option>
            @endif
            @foreach( $activities as $activity )
                @if ( isset( $solicitud ) )
                    @if ( $solicitud->id_actividad == $activity->id )
                        <option selected value="{{ $activity->id }}" >{{ $activity->nombre }}</option>
                    @else
                        <option value="{{ $activity->id }}" style="display:none">{{ $activity->nombre }}</option>
                    @endif
                @else
                    <option value="{{ $activity->id }}">{{ $activity->nombre }}</option>
                @endif
            @endforeach
        </select>
    </div>
</div>