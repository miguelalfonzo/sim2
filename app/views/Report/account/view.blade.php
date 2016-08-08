@extends('template.main')
@section('solicitude')
    <div class="page-header">
        <h3>Reporte {{ str_replace( '_' , ' ' , $type ) }}</h3>
        <div class="col-xs-6 col-md-6 col-sm-4 col-lg-4 form-group">
        
        </div>
        <div class="form-group">
            <button type="button" id="report-export" class="btn btn-primary btn-md ladda-button" data-style="zoom-in">Exportar</button>
            <button type="button" id="search-report" class="btn btn-primary btn-md ladda-button" data-style="zoom-in">
                <span class="glyphicon glyphicon-search"></span>
            </button>
        </div>
    </div>
    <div class="container-fluid">
        <table id="table_reporte_{{ $type }}" class="table table-striped table-hover table-bordered text-center" cellspacing="0" width="100%">
        </table>
    </div>
    <script>
        $( document ).ready( function()
        {   
            GBREPORTS.changeDateRange( 'M' );
        });
        
        function columnDataTable( response )
        {
            var dataTable = $( '#table_contabilidad' ).DataTable(
            {
                columns         : response.columns,
                data            : response.Data ,
                dom             : "<'row'<'col-xs-6'><'col-xs-6 pull-right'f>r>t<'row'<'col-xs-6'i><'col-xs-6'p>>",
                destroy         : true,
                pageLength      : 10,
                stateSave       : true,
                scrollX         : true,
                language        :
                {
                    search       : 'Buscar',
                    zeroRecords  : 'No hay ' + 'registros' ,
                    infoEmpty    : 'No ha encontrado ' + 'registros' +' disponibles',
                    info         : 'Mostrando _END_ de _TOTAL_ ' + 'registros' ,
                    lengthMenu   : "Mostrando _MENU_ registros por página",
                    infoEmpty    : "No ha encontrado información disponible",
                    infoFiltered : "(filtrado de _MAX_ regitros en total)",
                    paginate     : 
                    {
                        sPrevious : 'Anterior',
                        sNext     : 'Siguiente'
                    }
                }
            });
        }
    
        function getReportData()
        {
            var eType = $( '#report-type' );
            if( eType.length !== 0 )
            {
                var spin = Ladda.create( $( '#search-report' )[ 0 ] );
                eType    = eType.val();
                spin.start();
                $.ajax(
                {
                    type : 'post' ,
                    url  : server + 'report/cont/data' ,
                    data : 
                    {
                        _token   : GBREPORTS.token,
                        type     : eType,
                        category : $( '#fund-category' ).val()
                    }
                }).done( function( response )
                {
                    spin.stop();
                    name = '#table_reporte_' + eType;
                    var element = $( name );
                    columnDataTable( response );
                }).fail( function( statusCode , errorThrow )
                {
                    spin.stop();
                    ajaxError( statusCode , errorThrow );
                });
            }
        }

        $( '#search-report' ).on( 'click' , function()
        {
            getReportData();
        });

        $( '#report-export' ).on( 'click' , function()
        {
            var url = 'report/export-';
            url += $( '#report-type' ).val() + '-';
            url += $( '#fund-category' ).val();
            window.location.href = server + url;
        });

        $(document).on( 'ready' , function()
        {
            getReportData();
        })
    </script>
@stop