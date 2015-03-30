<td class="text-center">
    @if(count($solicitude->histories) != 0)
        @if (is_object($solicitude->histories[0]->user))    
            @if ($solicitude->histories[0]->user->type == REP_MED)
                {{ucwords(strtolower($solicitude->histories[0]->user->Rm->nombres.' '.$solicitude->histories[0]->user->Rm->apellidos))}}
            @elseif ($solicitude->histories[0]->user->type == SUP)
                {{ucwords(strtolower($solicitude->histories[0]->user->Sup->nombres.' '.$solicitude->histories[0]->user->Sup->apellidos))}}
            @elseif ($solicitude->histories[0]->user->type == GER_PROD)
                {{ucwords(strtolower($solicitude->histories[0]->user->GerProd->descripcion))}}
            @elseif ( in_array($solicitude->histories[0]->user->type, array(GER_COM,CONT,TESORERIA,ASIS_GER) ))
                {{ucwords(strtolower($solicitude->histories[0]->user->person->nombres.' '.$solicitude->histories[0]->user->person->apellidos))}}
            @else
                Usuario no autorizado
            @endif
        @else
            -
        @endif
    @endif
</td>