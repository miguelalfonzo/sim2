<tr type="{{ $type }}">
    @foreach( $records as $record )
        @if ( $record->editable == 0 )
            <td class="{{{ $record->class or $record->key }}}" style="text-align:center" disabled></td>
        @else
            <td class="{{{ $record->class or $record->key }}}" save=1 style="text-align:center">
                <input type="text" style="width:100%">
            </td>
        @endif 
    @endforeach
    <td style="text-align:center">
        <a class="maintenance-save" href="#">
            <span class="glyphicon glyphicon-floppy-disk"></span>
        </a>
        <a class="maintenance-cancel" href="#">
            <span class="glyphicon glyphicon-remove"></span>
        </a>
    </td>
</tr>