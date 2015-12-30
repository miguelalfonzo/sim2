<td class="text-center">
	@if ( $solicitud->id_estado != PENDIENTE && $solicitud->histories->count() != 0 )
	    {{ $solicitud->lastHistory->updated_at }}
    @else
        -
    @endif
</td>