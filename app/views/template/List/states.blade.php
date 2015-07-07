<td style="text-align: center">
    @if ( $solicitud->id_estado == DERIVADO && Auth::user()->type == SUP )
        <span class="label" style='margin-left:2px ;background-color: {{$solicitud->state->rangeState->color}}'>
            {{ESTADO_DERIVADO}}
        </span>
    @elseif ( $solicitud->id_estado == ACEPTADO && Auth::user()->type == GER_COM )
        <span class="label" style='margin-left:2px ;background-color: {{$solicitud->state->rangeState->color}}'>
            {{ESTADO_ACEPTADO}}
        </span>
    @elseif ( Auth::user()->type == TESORERIA )
        @if ( $solicitud->id_estado == DEPOSITO_HABILITADO )
            <span class="label" style="margin-left:2px ;background-color:{{$solicitud->state->rangeState->color}}">
                POR DEPOSITAR
            </span>
        @elseif ( $solicitud->id_estado == DEPOSITADO )
            <span class="label" style="margin-left:2px ;background-color:{{$solicitud->state->rangeState->color}}">
                {{$solicitud->state->nombre}}
            </span>
        @endif
    @elseif ( $solicitud->id_estado == GASTO_HABILITADO )
        <span class="label" style="margin-left:2px ;background-color:{{$solicitud->state->rangeState->color}}">
            POR REGISTRAR
        </span>
    @else
        <span class="label" style="margin-left:2px ;background-color:{{$solicitud->state->rangeState->color}}">
            {{$solicitud->state->nombre}}
        </span>
    @endif
</td>