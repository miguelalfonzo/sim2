<div class="modal fade" id="massive-deposit-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Registro del Depósito - Masivo</h4>
            </div>
            <div class="modal-body">
                <div class="form-group" style="display:none">
                    <label>Bancos</label>
                    <select id="massive-bank-account" class="form-control">
                        @foreach ( $banks as $bank )
                            @if( $bank->typeMoney->simbolo == 'S/.' )
                                <option value="{{ $bank->num_cuenta }}" selected>
                                    {{ $bank->typeMoney->simbolo . '-' . $bank->bagoAccount->ctanombrecta }}
                                </option>
                            {{-- 
                            @else
                                <option value="{{ $bank->num_cuenta }}">
                                    {{ $bank->typeMoney->simbolo . '-' . $bank->bagoAccount->ctanombrecta }}
                                </option>
                            --}}
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Busqueda ( Presionar Enter )</label>
                    <input type="text" id="filter-solicitud-id" class="form-control" style="font-weight:bold">
                </div>
                <div style="max-height:300px;overflow-y:scroll">
                	<table id="massive-deposit-table" class="table table-striped table-hover table-bordered table-condensed" cellspacing="0" width="100%">
                		<thead>
                			<tr>
                                <th>N°</th>
    		            		<th># Solicitud</th>
    		            		<th>N° Operacion</th>
                			</tr>
                		</thead>
                		<tbody>
                            @foreach( $depositIds as $key => $depositId )
                    			<tr>
                                    <td><b>{{ $key + 1 }}</b></td>
                    				<td class="deposit-solicitud-cell"><b>{{ $depositId->id }}</b></td>
                    				<td class="deposit-operacion-cell"><b><input type="text" class="form-control" autocomplete="off"></b></td>
                                    <input type="hidden" class="deposit-solicitud-token" value="{{ $depositId->token }}"> 
                    			</tr>
                            @endforeach
                		</tbody>
                	</table>
                </div>
            </div>
            <div class="modal-footer">
        	    <button type="button" id="register-deposit-massive" class="btn btn-success ladda-button" data-style="zoom-in">Confirmar</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<script>
    /*$( '#deposit-add' ).click( function()
    {
        var newTr = '<tr>' +
                        '<td class="deposit-solicitud-cell"><input type="text" class="form-control"></td>' +
                        '<td class="deposit-operacion-cell"><input type="text" class="form-control"></td>' +
                    '</tr>';
        $( '#massive-deposit-table' ).append( newTr );
    });*/

    $( '#register-deposit-massive' ).click( function()
    {
        var spin = Ladda.create( this );
        var solicituds = [];
        var indexs     = [];
        var depositTableTrs = $( '#massive-deposit-table tbody tr' );
        var depositTableTr;
        var depositTableTd;
        var solicitudCount = depositTableTrs.length;
        var solicitud;
        var depositNumber;
        for( var i = 0 ; i < solicitudCount ; i++ )
        {
            depositTableTr    = depositTableTrs.eq( i );
            depositTableTds   = depositTableTr.find( 'td' );
            depositNumberCell = depositTableTds.eq( 2 ).find( 'input' );
            if( depositNumberCell.length !== 0 )
            {
                depositNumber = depositNumberCell.val().trim();
                if( depositNumber != '' )
                {
                    solicitud = 
                    { 
                        id        : depositTableTds.eq( 1 ).text(),
                        token     : depositTableTr.find( '.deposit-solicitud-token' ).val(),
                        operacion : depositNumber
                    };
                    solicituds.push( solicitud );
                    indexs.push( i );
                }
            }
        }
        if( solicituds.length == 0 )
        {
            //spin.stop();
            bootbox.alert( '<h4 class="text-warning"><b>No ingreso al menos un numero de operacion</b></h4>' );
        }
        else
        {
            bootbox.confirm( '<h4 class="text-info"><b>¿ Esta seguro de registrar los depositos ?</b></h4>' , function( result )
            {
                var button = this.find( 'button[ data-bb-handler=confirm ]' )[ 0 ];
                button.disabled = true;

                if( result )
                {
                    spin.start();
        
                    $.ajax(
                    {
                        url  : 'massive-solicitud-deposit',
                        type : 'POST',
                        data : 
                        {
                            _token : GBREPORTS.token,
                            cuenta : $( '#massive-bank-account' ).val(),
                            data   : solicituds
                        }
                    }).fail( function( statusCode , errorThrow )
                    {
                        ajaxError( statusCode , errorThrow );
                    }).done( function( response )
                    {
                        if( response.Status === ok )
                        {
                            //bootboxMessage( response );
                            var rowStatus;
                            for( var i = 0 ; i < indexs.length ; i++ )
                            {
                                depositTableTr = depositTableTrs.eq( indexs[ i ] );
                                depositTableTds = depositTableTr.find( 'td' );
                                id = depositTableTds.eq( 1 ).text();
                                rowStatus = response[ data ][ id ];
                                if( rowStatus.Status === ok )
                                {
                                    depositTableTr.addClass( 'success' ).attr( 'title' , 'Ok' );
                                    depositTableTds.eq( 2 ).find( 'b' ).text( rowStatus.operacion );
                                }
                                else
                                {
                                    depositTableTr.addClass( 'danger' ).attr( 'title' , rowStatus.Description );
                                }
                            }
                            spin.stop();
                            window.location.href = 'deposit-export';
                            getSolicitudList();
                        }
                        else if( response.Status === warning )
                        {
                            //bootboxMessage( response );
                            var rowStatus;
                            for( var i = 0 ; i < indexs.length ; i++ )
                            {
                                depositTableTr = depositTableTrs.eq( indexs[ i ] );
                                depositTableTds = depositTableTr.find( 'td' );
                                id = depositTableTds.eq( 1 ).text();
                                rowStatus = response[ data ][ id ];
                                if( rowStatus.Status === ok )
                                {
                                    depositTableTr.addClass( 'success' ).attr( 'title' , 'Ok' );
                                    depositTableTds.eq( 2 ).find( 'b' ).text( rowStatus.operacion );
                                }
                                else
                                {
                                    depositTableTr.addClass( 'danger' ).attr( 'title' , rowStatus.Description );
                                }
                            }
                            spin.stop();
                            window.location.href = 'deposit-export';
                            getSolicitudList();
                        }
                        else
                        {
                            spin.stop();
                            bootboxMessage( response );
                        }
                    });
                }
            });
        }
    });

    $( '#filter-solicitud-id' ).on( 'keypress' , function( event )
    {
        if( event.keyCode === 13 )
        {
            $( '#massive-deposit-table tbody tr' ).hide();
            $( '#massive-deposit-table tbody tr:contains(' + this.value + ')' ).show();
        }
    });

    /*$( document ).off( 'keypress' , '.deposit-solicitud-cell' );
    $( document ).on( 'keypress' , '.deposit-solicitud-cell' , function( event )
    {
        var cell = $( this );
        var e = event;
        var rowIndex = cell.closest( 'tr' ).index();
        var columIndex = cell.closest( 'td' ).index();
        var tableBody = $( '#massive-deposit-table tbody' );
        
        if( e.keyCode === 38 )
        {
           var input =  tableBody.find( 'tr' ).eq( rowIndex - 1 ).find( 'td' ).eq( columIndex ).find( 'input' );
           input.focus();
        }
        else if( e.keyCode === 39 )
        {
           var input =  tableBody.find( 'tr' ).eq( rowIndex ).find( 'td' ).eq( columIndex + 1 ).find( 'input' );
           input.focus();
        }
        else if( e.keyCode === 40 )
        {
           var input =  tableBody.find( 'tr' ).eq( rowIndex + 1 ).find( 'td' ).eq( columIndex ).find( 'input' );
           input.focus();
        }
        else if( e.keyCode === 13 )
        {
            var newTr = '<tr>' +
                        '<td class="deposit-solicitud-cell" ><input type="text" class="form-control"></td>' +
                        '<td class="deposit-operacion-cell"><input type="text" class="form-control"></td>' +
                    '</tr>';
            var tableBodyTr =  tableBody.find( 'tr' ).eq( rowIndex );
            tableBodyTr.after( newTr );
        }
    });*/
    $( document ).off( 'keypress' , '.deposit-operacion-cell' );
    $( document ).on( 'keypress' , '.deposit-operacion-cell' , function( event )
    {
        var cell = $( this );
        var e = event;
        var rowIndex = cell.closest( 'tr' ).index();
        var columIndex = cell.closest( 'td' ).index();
        var tableBody = $( '#massive-deposit-table tbody' );

        if( e.keyCode === 38 )
        {
           var input =  tableBody.find( 'tr' ).eq( rowIndex - 1 ).find( 'td' ).eq( columIndex ).find( 'input' );
           input.focus();
        }
        /*else if( e.keyCode === 37 )
        {
           var input =  tableBody.find( 'tr' ).eq( rowIndex ).find( 'td' ).eq( columIndex - 1 ).find( 'input' );
           input.focus();
        }*/
        else if( e.keyCode === 40 )
        {
           var input =  tableBody.find( 'tr' ).eq( rowIndex + 1 ).find( 'td' ).eq( columIndex ).find( 'input' );
           input.focus();
        }
       /* else if( e.keyCode === 13 )
        {
           var newTr = '<tr>' +
                        '<td class="deposit-solicitud-cell"><input type="text" class="form-control"></td>' +
                        '<td class="deposit-operacion-cell"><input type="text" class="form-control"></td>' +
                    '</tr>';
            var tableBodyTr =  tableBody.find( 'tr' ).eq( rowIndex );
            tableBodyTr.after( newTr );
        }*/
    });
</script>