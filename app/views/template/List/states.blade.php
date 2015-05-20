<td style="text-align: center">
    @if ( $solicitud->idestado == DERIVADO && Auth::user()->type == SUP )
        <span class="label" style='margin-left:2px ;background-color: {{$solicitud->state->rangeState->color}}'>
            {{ESTADO_DERIVADO}}
        </span>
    @elseif ( $solicitud->idestado == ACEPTADO && Auth::user()->type == GER_COM )
        <span class="label" style='margin-left:2px ;background-color: {{$solicitud->state->rangeState->color}}'>
            {{ESTADO_ACEPTADO}}
        </span>
    @elseif ( Auth::user()->type == TESORERIA )
        @if ( $solicitud->idestado == DEPOSITO_HABILITADO )
            <span class="label" style="margin-left:2px ;background-color:{{$solicitud->state->rangeState->color}}">
                POR DEPOSITAR
            </span>
        @elseif ( $solicitud->idestado == DEPOSITADO )
            <span class="label" style="margin-left:2px ;background-color:{{$solicitud->state->rangeState->color}}">
                {{$solicitud->state->nombre}}
            </span>
        @endif
    @elseif ( $solicitud->idestado == GASTO_HABILITADO )
        <span class="label" style="margin-left:2px ;background-color:{{$solicitud->state->rangeState->color}}">
            POR REGISTRAR
        </span>
    @else
        <span class="label" style="margin-left:2px ;background-color:{{$solicitud->state->rangeState->color}}">
            {{$solicitud->state->nombre}}
        </span>
    @endif
</td>