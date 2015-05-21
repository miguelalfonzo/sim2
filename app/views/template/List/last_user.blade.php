<td class="text-center">
    @if ( $solicitud->id_estado != PENDIENTE )
        @if( $solicitud->lastHistory->count() != 0 )
            @if (is_object($solicitud->lastHistory->user ) )
                @if ($solicitud->lastHistory->user->type == REP_MED)
                    {{ $solicitud->lastHistory->user->rm->full_name }}
                @elseif ($solicitud->lastHistory->user->type == SUP)
                    {{ $solicitud->lastHistory->user->sup->full_name }}
                @elseif ($solicitud->lastHistory->user->type == GER_PROD)
                    {{ ucwords(strtolower($solicitud->lastHistory->user->gerProd->full_name))}}
                @elseif ( $solicitud->lastHistory->user->simApp->count() != 0 )
                    {{ $solicitud->lastHistory->user->person->full_name }}
                @else
                    Usuario no autorizado
                @endif
            @else
                -
            @endif
        @endif
    @else
        -
    @endif
</td>