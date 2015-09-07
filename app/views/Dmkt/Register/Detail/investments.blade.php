@if ( isset( $solicitud ) )
    <option value=0 disabled style="display:none">SELECCIONE LA INVERSION</option>
@else
    <option value=0 disabled selected>SELECCIONE LA INVERSION</option>
@endif
@foreach( $investments as $investment )
    @if( isset( $solicitud ) )
        @if ( $solicitud->id_inversion == $investment->id )
            <option selected value="{{$investment->id}}">{{$investment->nombre}}</option>
        @else
            <option value="{{$investment->id}}" style="display:none">{{$investment->nombre}}</option>
        @endif
    @else
        <option value="{{$investment->id}}">{{$investment->nombre}}</option>
    @endif
@endforeach