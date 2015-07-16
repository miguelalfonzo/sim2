@extends('template.main')
@section('solicitude')
<div class="page-header">
  <h3> {{ $titulo }}</h3>
</div>
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
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
<div>
    @if ( $titulo == 'Mantenimiento de Parametros' )
        <input class="btn btn-primary maintenance-add" type="button" case="{{$type}}" value="Agregar" style="display:none">
    @else
        <input class="btn btn-primary maintenance-add" type="button" case="{{$type}}" value="Agregar">
    @endif
</div>
<script>
    $(document).on('ready', function(){
        dataTable('table_{{$type}}', null, 'registros')
    })
</script>
@stop