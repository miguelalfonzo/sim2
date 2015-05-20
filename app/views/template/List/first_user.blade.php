<td class="text-center">
    @if ( $solicitud->createdBy->type == REP_MED )
        {{ $solicitud->createdBy->rm->full_name}}
    @elseif ( $solicitud->createdBy->type == SUP )
        {{$solicitud->createdBy->sup->full_name}}
    @elseif ( $solicitud->createdBy->type == GER_PROD )
        {{$solicitud->createdBy->gerProd->full_name}}
    @elseif ( $solicitud->createdBy->type == ASIS_GER )
        {{$solicitud->createdBy->person->full_name}}
    @else
        No Registrado
    @endif
</td>