<td class="text-center">
    @if ( $solicitude->createdBy->type == REP_MED )
        {{ $solicitude->createdBy->rm->full_name}}
    @elseif ( $solicitude->createdBy->type == SUP )
        {{$solicitude->createdBy->sup->full_name}}
    @elseif ( $solicitude->createdBy->type == GER_PROD )
        {{$solicitude->createdBy->gerProd->full_name}}
    @elseif ( $solicitude->createdBy->type == ASIS_GER )
        {{$solicitude->createdBy->person->full_name}}
    @else
        No Registrado
    @endif
</td>