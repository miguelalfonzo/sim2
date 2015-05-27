<td class="text-center">
    @if( $solicitud->histories->count() != 0 )
        {{ $solicitud->lastHistory->updated_at }}
    @else
        -
    @endif
</td>