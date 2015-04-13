<td class="text-center">
    @if(count($solicitude->histories) != 0)
        @if (is_object($solicitude->histories[0]->user))    
            {{$solicitude->histories[0]->updated_at}}
        @else
            -
        @endif
    @endif
</td>