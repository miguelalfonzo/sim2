<td style="text-align: center">
    <span class="label" style="background-color:{{$solicitude->state->rangeState->color}}">
    	{{$solicitude->state->rangeState->nombre}}
    </span>
    @if($solicitude->state->idestado == DERIVADO && Auth::user()->type != GER_PROD )
        <span class="label" style='margin-left:2px ;background-color: {{$solicitude->state->rangeState->color}}'>
        	{{ESTADO_D}}
        </span>
    @elseif($solicitude->retencion != null && $solicitude->state->rangeState->id == R_REVISADO && ( Auth::user()->type == CONT || Auth::user()->type == TESORERIA ) )
        <span class="label" style="margin-left:2px ;background-color:{{$solicitude->state->rangeState->color}}">
        	{{ESTADO_R}}
        </span>        
    @endif
</td> 