@extends('template.main')
@section('solicitude')
    <div class="page-header">
        <input type="hidden" id="report-type" value="{{ $type }}">
        <h3>Reporte de Montos</h3>
        <div class="row">
        <div class="col-xs-12 col-md-12 col-sm-4 col-lg-4 form-group">
            <label>Colaborador</label>
            <div class="scrollable-dropdown-menu">
                <input type="text" id="responsible-seeker" class="form-control" style="display:inline">    
                <a id="edit-responsible" class="glyphicon glyphicon-pencil pencil-seeker" href="#"></a>
            </div>
        </div>
        <div class="col-xs-12 col-md-12 col-sm-4 col-lg-4 form-group">
            <label>Cuenta</label>
            <input type="text" id="num-cuenta" class="form-control">
        </div>
        <div class="col-xs-12 col-md-12 col-sm-4 col-lg-4 form-group">
            <label># Solicitud</label>
            <input type="text" id="solicitud-id" class="form-control">
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
            var rType = $( '#report-type' );
            if( rType.length !== 0 )
            {
                var colaborador = $( '#responsible-seeker' ).attr( 'data-cod' );
                if( typeof colaborador == 'undefined' || colaborador.trim() == '' )
                {
                    colaborador = 0;
                }
                
                var num_cuenta = $( '#num-cuenta' ).val().trim();
                if( num_cuenta == '' )
                {
                    num_cuenta = 0;
                }

                var solicitud_id = $( '#solicitud-id' ).val().trim();
                if( solicitud_id == '' )
                {
                    solicitud_id = 0;
                }
                
                var spin = Ladda.create( $( '#search-report' )[ 0 ] );
                rType    = rType.val();
                spin.start();
                $.ajax(
                {
                    type : 'post' ,
                    url  : server + 'report/cont/data' ,
                    data : 
                    {
                        _token       : GBREPORTS.token,
                        type         : rType,
                        fecha_inicio : $( '#drp_menubar' ).data( 'daterangepicker' ).startDate.format( "L" ),
                        fecha_final  : $( '#drp_menubar' ).data( 'daterangepicker' ).endDate.format( "L" ),
                        colaborador  : colaborador,
                        num_cuenta   : num_cuenta,
                        solicitud_id : solicitud_id
                    }
                }).done( function( response )
                {
                    if( response.Status == ok )
                    {
                        spin.stop();
                        name = '#table_reporte_' + rType;
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
            var spin = Ladda.create( this );
            spin.start();
            var url = 'report/cont/export';
            var rType = $( '#report-type' ).val();
            var colaborador = $( '#responsible-seeker' ).attr( 'data-cod' );
            if( typeof colaborador == 'undefined' || colaborador.trim() == '' )
            {
                colaborador = 0;
            }
            
            var num_cuenta = $( '#num-cuenta' ).val().trim();
            if( num_cuenta == '' )
            {
                num_cuenta = 0;
            }

            var solicitud_id = $( '#solicitud-id' ).val().trim();
            if( solicitud_id == '' )
            {
                solicitud_id = 0;
            }

            var data = 
            {
                _token       : GBREPORTS.token,
                type         : rType,
                fecha_inicio : $( '#drp_menubar' ).data( 'daterangepicker' ).startDate.format( "L" ),
                fecha_final  : $( '#drp_menubar' ).data( 'daterangepicker' ).endDate.format( "L" ),
                colaborador  : colaborador,
                num_cuenta   : num_cuenta,
                solicitud_id : solicitud_id
            };

            customAjax( 'POST' , server + url , data ).done( function( response )
            {
                if( response.Status == ok )
                {
                    spin.stop();
                    window.location.href = server + url + '/' + response.title;
                }
                else
                {
                    spin.stop();
                    bootboxMessage( response );
                }
            });
        });
    </script>
@stop