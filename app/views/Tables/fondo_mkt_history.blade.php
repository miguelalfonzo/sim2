@extends('template.main')
@section('solicitude')
    <div class="page-header">
        <h3>Historial de los Fondos</h3>
    </div>
    <div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
        <select id="fondoMkt" class="form-control">
            <option value="0" selected disabled>Seleccione un Fondo</option>
            @foreach( $fondoSubCategories as $fondoSubCategory )
                <option value="{{ $fondoSubCategory->id }}">{{ $fondoSubCategory->descripcion }}</option>
            @endforeach
        </select>
    </div>
    <div id="fondo_mkt_history">
    </div>
    <script>
        $(document).on( 'ready' , function()
        {
            dataTable( 'fondo_mkt_history' , null , 'registros' )
            GBREPORTS.changeDateRange('M');
        });
    </script>
@stop