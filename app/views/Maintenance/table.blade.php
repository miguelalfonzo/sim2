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
                    <td class="{{{ isset( $column->class ) ? $column->class : $column->key }}}" editable="{{$column->editable}}">
                        @if( isset( $column->relation ) )
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
                </td>
            </tr>
        @endforeach
    </tbody>
</table>