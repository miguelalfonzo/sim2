@if ( isset( $solicitud) )
    <option value=0 disabled>SELECCIONE LA ACTIVIDAD</option>
@else
    <option value=0 disabled selected>SELECCIONE LA ACTIVIDAD</option>
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