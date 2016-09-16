<table class="table table-hover table-bordered table-condensed dataTable" id="table_{{$type}}" style="width: 100%">
    <thead>
        <tr>
            @foreach( $columns as $column )
                <th>{{$column->name}}</th>
            @endforeach
            @if( $options )
                <th>Edición</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @foreach ( $records as $record )
            <tr row-id="{{$record->id}}" type="{{$type}}">
                @foreach( $columns as $column )
                    <td class="{{{ $column->class or $column->key }}} text-center" data-key="{{ $column->key }}" editable="{{ $column->editable }}">
                        @if( isset( $column->relation ) && ! is_null( $record->{ $column->relation } ) )
                            {{ $record->{ $column->relation }->{ $column->relationKey } }}
                        @else
                            {{$record->{ $column->key } }}
                        @endif
                    </td>
                @endforeach
                @if( $options )
                    <td editable=2 style="text-align:center">
                        <button type="button" class="btn btn-info btn-xs maintenance-edit">
                            <span class="glyphicon glyphicon-pencil"></span>
                        </button>
                        @if( $disabled )
                            @if ( is_null( $record->deleted_at ) )
                                <button type="button" class="btn btn-danger btn-xs maintenance-disable">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </button>
                            @else
                                <button type="button" class="btn btn-success btn-xs maintenance-enable">
                                    <span class="glyphicon glyphicon-ok"></span>
                                </button>
                            @endif
                        @endif
                    </td>
                @endif
            </tr>
        @endforeach
    </tbody>
</table>