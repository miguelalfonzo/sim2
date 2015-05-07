<td class="text-center">
    @if(count($solicitude->histories) != 0)
        @if (is_object($solicitude->histories[0]->user))
            @if ($solicitude->histories[0]->user->type == REP_MED)
                {{ $solicitude->histories[0]->user->rm->full_name }}
            @elseif ($solicitude->histories[0]->user->type == SUP)
                {{ $solicitude->histories[0]->user->sup->full_name }}
            @elseif ($solicitude->histories[0]->user->type == GER_PROD)
                {{ ucwords(strtolower($solicitude->histories[0]->user->gerProd->descripcion))}}
            @elseif ( in_array($solicitude->histories[0]->user->type, array(GER_COM,CONT,TESORERIA,ASIS_GER) ))
                {{ $solicitude->histories[0]->user->person->full_name }}
            @else
                Usuario no autorizado
            @endif
        @else
            -
        @endif
    @endif
</td>