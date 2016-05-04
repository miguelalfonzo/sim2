@extends('template.main')
@section('solicitude')
    <div class="page-header">
        <h3>Reporte {{ str_replace( '_' , ' ' , $type ) }}</h3>
        <input type="hidden" id="report-type" value="{{ $type }}">
    </div>
    <div id="reporte_{{ $type }}" class="container-fluid">
        <table id="table_reporte_{{ $type }}" class="table table-striped table-hover table-bordered text-center" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Saldo</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>Total</th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
    </div>
    <script>

        function columnDataTable( element , data , columns ,  message )
        {
            if( $.fn.dataTable.isDataTable( element ) )
            {
                element.DataTable().clear().destroy();
            }
            dataTable = element.DataTable(
            {
                bDestroy        :true ,
                scrollX         : 99,
                columns         : columns,
                data            : data ,
                dom             : "<'row'<'col-xs-6'><'col-xs-6 pull-right'f>r>t<'row'<'col-xs-6'i><'col-xs-6'p>>",
                stateSave       : true,
                bScrollCollapse : true,
                iDisplayLength  : 10 ,
                language        :
                {
                    search       : 'Buscar',
                    zeroRecords  : 'No hay ' + message ,
                    infoEmpty    : 'No ha encontrado ' + message +' disponible',
                    info         : 'Mostrando _END_ de _TOTAL_ ' + message ,
                    lengthMenu   : "Mostrando _MENU_ registros por página",
                    infoEmpty    : "No ha encontrado información disponible",
                    infoFiltered : "(filtrado de _MAX_ regitros en total)",
                    paginate     : 
                    {
                        sPrevious : 'Anterior',
                        sNext     : 'Siguiente'
                    }
                },
                footerCallback: function() 
                {
                    this.api().column( '.sum' , { page: 'current'} ).every( function () 
                    {
                        var sum  =  this.data().reduce( function ( a , b ) 
                                    {
                                        return Number( a ) + Number( b );
                                    });
                        $( this.footer() ).html( sum );
                    });
                }
            });

            /*dataTable.column( '.sum' ).every( function () 
            {
                var sum  =  this.data().reduce( function ( a , b ) 
                            {
                                console.log( a );
                                console.log( b );
                                return Number( a ) + Number( b );
                            });
                $( this.footer() ).html( sum );
            });*/
    
            //yadcf.init( dataTable , [ { column_number : 0 } , { column_number : 1 }  , { column_number : 2 } , { column_number : 3 } , { column_number : 4 } , { column_number : 5 }] );
        
        }
    
        function getReportData()
        {
            var eType = $( '#report-type' );
            if( eType.length !== 0 )
            {
                eType = eType.val();
                $.ajax(
                {
                    type : 'post' ,
                    url  : server + 'report/data' ,
                    data : 
                    {
                        _token : GBREPORTS.token,
                        type   : eType
                    }
                }).done( function( response )
                {
                    name = '#table_reporte_' + eType;
                    var element = $( name );
                    columnDataTable( element , response.Data , response.columns , response.message );
                });
            }
        }

        $(document).on( 'ready' , function()
        {
            getReportData();
        })
    </script>
@stop