<tr type="{{ $type }}">
    @foreach( $records as $record )
        @if( $record->editable == 0 )
            <td class="{{{ $record->class or $record->key }}}" style="text-align:center" disabled></td>
        @else
            <td class="{{{ $record->class or $record->key }}}" save=1 style="text-align:center">
                <input type="text" class="form-control" style="width:100%">
            </td>
        @endif 
    @endforeach
    <td style="text-align:center">
        <button type="button" class="btn btn-success btn-xs maintenance-save">
            <span class="glyphicon glyphicon-floppy-disk"></span>
        </button>
        <button type="button" class="btn btn-danger btn-xs maintenance-remove">
            <span class="glyphicon glyphicon-remove"></span>
        </button>
    </td>
</tr>