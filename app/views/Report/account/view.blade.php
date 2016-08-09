@extends('template.main')
@section('solicitude')
    <div class="page-header">
        <input type="hidden" id="report-type" value="{{ $type }}">
        <h3>Reporte de Montos</h3>
        <div class="row">
        <div class="col-xs-12 col-md-12 col-sm-3 col-lg-3 form-group">
            <label>Colaborador</label>
            <input type="text" class="form-control">
        </div>
        <div class="col-xs-12 col-md-12 col-sm-3 col-lg-3 form-group">
            <label>Cuenta</label>
            <input type="text" class="form-control">
        </div>
        <div class="col-xs-12 col-md-12 col-sm-3 col-lg-3 form-group">
            <label># Solicitud</label>
            <input type="text" class="form-control">
        </div>
        <div class="col-xs-12 col-md-12 col-sm-3 col-lg-3 form-group">
            <button type="button" id="report-export" class="btn btn-primary btn-md ladda-button" data-style="zoom-in">Exportar</button>
            <button type="button" id="search-report" class="btn btn-primary btn-md ladda-button" data-style="zoom-in">
                <span class="glyphicon glyphicon-search"></span>
            </button>
        </div>
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
            getReportData();
        });
        
        function columnDataTable( response )
        {
            var dataTable = $( '#table_reporte_' +  '{{ $type }}' ).DataTable(
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
                    if( response.Status == ok )
                    {
                        spin.stop();
                        name = '#table_reporte_' + eType;
                        var element = $( name );
                        columnDataTable( response );
                    }
                    else
                    {
                        spin.stop();
                        bootboxMessage( response );
                    }
                }).fail( function( statusCode , errorThrow )
                {
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
            var url = 'report/cont/export-';
            url += $( '#report-type' ).val() + '-';
            url += $( '#fund-category' ).val();
            window.location.href = server + url;
        });
    </script>
@stop