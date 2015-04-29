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
    @elseif ( $solicitude->idestado == GASTO_HABILITADO )
        <span class="label" style="margin-left:2px ;background-color:{{$solicitude->state->rangeState->color}}">
            POR REGISTRAR
        </span>
    @else
        <span class="label" style="margin-left:2px ;background-color:{{$solicitude->state->rangeState->color}}">
            {{$solicitude->state->nombre}}
        </span>
    @endif
</td> 