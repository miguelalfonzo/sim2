<td class="text-center">
    @if(count($solicitud->histories) != 0)
        @if (is_object($solicitud->histories[0]->user))    
            {{$solicitud->histories[0]->updated_at}}
        @else
            -
        @endif
    @endif
</td>