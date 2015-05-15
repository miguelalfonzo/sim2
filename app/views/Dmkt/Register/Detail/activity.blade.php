<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
    <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12 control-label">Tipo de Actividad</label>
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <select class="form-control" name="actividad">
            <option value="" disabled selected>SELECCIONE LA ACTIVIDAD</option>
            @foreach( $activities as $activity )
                @if ( isset( $solicitud ) && $solicitud->idactividad == $activity->id )
                    <option value="{{ $activity->id }}" image="{{ $activity->imagen }}" selected>{{ $activity->nombre }}</option>
                @else
                    <option value="{{ $activity->id }}" image="{{ $activity->imagen }}">{{ $activity->nombre }}</option>
                @endif
            @endforeach
        </select>
    </div>
</div>