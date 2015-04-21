<td style="text-align: center">
    <span class="label" style="background-color:{{$solicitude->state->rangeState->color}}">
    	{{$solicitude->state->rangeState->nombre}}
    </span>
    @if($solicitude->state->idestado == DERIVADO && Auth::user()->type != GER_PROD )
        <span class="label" style='margin-left:2px ;background-color: {{$solicitude->state->rangeState->color}}'>
            {{ESTADO_DERIVADO}}
        </span>
    @elseif ( $solicitude->idestado == DEPOSITADO && Auth::user()->type == CONT )
        <span class="label" style='margin-left:2px ;background-color: {{$solicitude->state->rangeState->color}}'>
            {{ESTADO_DEPOSITADO}}
        </span>
    @elseif( !is_null( $solicitude->detalle->idretencion ) && ( Auth::user()->type == CONT || Auth::user()->type == TESORERIA ) && $solicitude->state->idstate != R_GASTO )
        <span class="label" style="margin-left:2px ;background-color:{{$solicitude->state->rangeState->color}}">
        	{{ESTADO_R}}
        </span>        
    @endif
</td> 