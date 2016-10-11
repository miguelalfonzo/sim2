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
            GBREPORTS.changeDateRange('M');
            $( '#fondoMkt' ).on( 'change' , function()
            {
                getSubCategoryHistory( $( this ) );
            });
        });

        function getSubCategoryHistory( option )
        {
            data = 
            {
                _token          : GBREPORTS.token,
                id_subcategoria : $( option ).val(),
                start           : $('#drp_menubar').data('daterangepicker').startDate.startOf( 'month' ).format("YYYY/MM/DD"),
                end             : $('#drp_menubar').data('daterangepicker').endDate.endOf( 'month' ).format("YYYY/MM/DD")
            };

            $( '#loading' ).show( 'slow' );
            $.post( server + 'fondo-subcategoria-history' , data )
            .fail( function( statusCode , errorThrow )
            {
                $( '#loading' ).hide( 'slow' );
                ajaxError( statusCode , errorThrow );
            }).done( function ( response )
            {
                $( '#loading' ).hide( 'slow' );
                if( response.Status == 'Ok' )
                    dataTable( 'fondo_mkt_history' , response.Data.View , 'registros' )
                else
                    bootbox.alert( '<h4 class="red">' + response.Status + ' : ' + response.Description + '</h4>' );
            });
        }
    </script>
    </script>
@stop