<td style="text-align: center">
    @if ( $solicitude->idestado == DERIVADO && Auth::user()->type == SUP )
        <span class="label" style='margin-left:2px ;background-color: {{$solicitude->state->rangeState->color}}'>
            {{ESTADO_DERIVADO}}
        </span>
    @elseif ( $solicitude->idestado == ACEPTADO && Auth::user()->type == GER_COM )
        <span class="label" style='margin-left:2px ;background-color: {{$solicitude->state->rangeState->color}}'>
            {{ESTADO_ACEPTADO}}
        </span>
    @elseif ( $solicitude->idestado == DEPOSITADO && Auth::user()->type == CONT )
        <span class="label" style='margin-left:2px ;background-color: {{$solicitude->state->rangeState->color}}'>
            {{ESTADO_DEPOSITADO}}
        </span>
    @elseif( !is_null( $solicitude->detalle->idretencion ) && Auth::user()->type == CONT && $solicitude->state->idstate == R_REVISADO )
        <span class="label" style="margin-left:2px ;background-color:{{$solicitude->state->rangeState->color}}">
        	{{ESTADO_RETENCION}}
        </span>
    @elseif ( Auth::user()->type == TESORERIA )
        @if ( $solicitude->idestado == DEPOSITO_HABILITADO )
            <span class="label" style="margin-left:2px ;background-color:{{$solicitude->state->rangeState->color}}">
                POR DEPOSITAR
            </span>
        @elseif ( $solicitude->idestado == DEPOSITADO )
            <span class="label" style="margin-left:2px ;background-color:{{$solicitude->state->rangeState->color}}">
                {{$solicitude->state->nombre}}
            </span>
        @endif
    @elseif ( ( Auth::user()->type == REP_MED  || Auth::user()->type == ASIS_GER ) && $solicitude->idestado == GASTO_HABILITADO )
        <span class="label" style="margin-left:2px ;background-color:{{$solicitude->state->rangeState->color}}">
            GASTO
        </span>
    @else
        <span class="label" style="margin-left:2px ;background-color:{{$solicitude->state->rangeState->color}}">
            {{$solicitude->state->rangeState->nombre}}
        </span>
    @endif
</td> 