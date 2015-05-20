<td class="text-center">
    @if ( $solicitud->id_estado != PENDIENTE )
        @if( count( $solicitud->histories ) != 0 )
            @if (is_object($solicitud->histories[0]->user ) )
                @if ($solicitud->histories[0]->user->type == REP_MED)
                    {{ $solicitud->histories[0]->user->rm->full_name }}
                @elseif ($solicitud->histories[0]->user->type == SUP)
                    {{ $solicitud->histories[0]->user->sup->full_name }}
                @elseif ($solicitud->histories[0]->user->type == GER_PROD)
                    {{ ucwords(strtolower($solicitud->histories[0]->user->gerProd->full_name))}}
                @elseif ( in_array($solicitud->histories[0]->user->type, array(GER_COM,CONT,TESORERIA,ASIS_GER) ))
                    {{ $solicitud->histories[0]->user->person->full_name }}
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