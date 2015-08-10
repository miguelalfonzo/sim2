<table class="table table-hover table-bordered table-condensed dataTable" id="table_{{$type}}" style="width: 100%">
    <thead>
        <tr>
            @foreach( $columns as $column )
                <th>{{$column->name}}</th>
            @endforeach
            <th>Edición</th>
        </tr>
    </thead>
    <tbody>
        @foreach ( $records as $record )
            <tr row-id="{{$record->id}}" type="{{$type}}">
                @foreach( $columns as $column )
                    <td class="{{{ $column->class or $column->key }}}" editable="{{$column->editable}}">
                        @if( isset( $column->relation ) && ! is_null( $record->{ $column->relation } ) )
                            {{$record->{$column->relation}->{$column->key} }}
                        @else
                            {{$record->{$column->key} }}
                        @endif
                    </td>
                @endforeach
                <td editable=2 style="text-align:center">
                    <a class="maintenance-edit" href="#">
                        <span class="glyphicon glyphicon-pencil"></span>
                    </a>
                    @if ( is_null( $record->deleted_at ) )
                        <a class="maintenance-disable" href="#">
                            <span class="glyphicon glyphicon-remove red"></span>
                        </a>
                    @else
                        <a class="maintenance-enable" href="#">
                            <span class="glyphicon glyphicon-ok green"></span>
                        </a>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>