@extends('template.main')
@section('solicitude')
    <div class="page-header">
        <h3>Reporte {{ str_replace( '_' , ' ' , $type ) }}</h3>
        <div class="col-xs-6 col-md-6 col-sm-6 col-lg-6 form-group">
            <select class="form-control input-lg">
                <option value="0">TODOS</option>
                @foreach( $funds as $fund )
                    <option value="{{ $fund->id }}">{{ $fund->descripcion }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <button type="button" class="btn btn-primary btn-lg">Exportar</button>
            <button type="button" class="btn btn-primary btn-lg">
                <span class="glyphicon glyphicon-search"></span>
            </button>
        </div>
        <input type="hidden" id="report-type" value="{{ $type }}">
    </div>
    <div id="reporte_{{ $type }}" class="container-fluid">
        <table id="table_reporte_{{ $type }}" class="table table-striped table-hover table-bordered text-center" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Familia</th>
                    <th>Saldo S/.</th>
                    <th>Retencion S/.</th>
                    <th>Saldo Disponible S/.</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th></th>
                    <th>Total</th>
                    <th></th>
                    <th></th>
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
                iDisplayLength  : 20 ,
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
                    this.api().column( '.sum-saldo' , { search: 'applied' } ).every( function () 
                    {
                        var sum  =  this.data().reduce( function ( a , b ) 
                                    {
                                        return ( Number( a ) + Number( b ) ).toFixed( 2 );
                                    });
                        $( this.footer() ).html( sum );
                    });

                    this.api().column( '.sum-retencion' , { search: 'applied' } ).every( function () 
                    {
                        var sum  =  this.data().reduce( function ( a , b ) 
                                    {
                                        return Number( a ) + Number( b );
                                    });
                        $( this.footer() ).html( sum );
                    });

                    this.api().column( '.sum-saldo-disponible' , { search: 'applied' } ).every( function () 
                    {
                        var sum  =  this.data().reduce( function ( a , b ) 
                                    {
                                        return ( Number( a ) + Number( b ) ).toFixed( 2 );
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