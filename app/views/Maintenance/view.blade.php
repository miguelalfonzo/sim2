@extends('template.main')
@section('solicitude')
    <div class="page-header">
      <h3> {{ $titulo }}</h3>
    </div>
    <div id="{{ $type }}">
        @include( 'Maintenance.table')
    </div>
    <div>
        @if ( $add )
            <input class="btn btn-primary maintenance-add" type="button" case="{{ $type }}" value="Agregar">
        @else
            <input class="btn btn-primary maintenance-add" type="button" case="{{ $type }}" value="Agregar" style="display:none">
        @endif
    </div>
    <script>
        $(document).on( 'ready' , function(){
            dataTable( '{{ $type }}' , null , 'registros' )
        })
    </script>
@stop