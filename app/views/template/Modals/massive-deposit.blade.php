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
                <label>Busqueda ( Presionar Enter )</label>
                <input type="text" id="filter-solicitud-id" class="form-control">
                <div style="max-height:400px;overflow-y:scroll">
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
                                    <td>{{ $key + 1 }}</td>
                    				<td class="deposit-solicitud-cell">{{ $depositId->id }}</td>
                    				<td class="deposit-operacion-cell"><input type="text" class="form-control"></td>
                    			</tr>
                            @endforeach
                		<tbody>
                	</table>
                </div>
            </div>
            <div class="modal-footer">
        	    <a href="#" class="btn btn-success register-deposit-massive" data-deposit="S" style="margin-right: 1em;">Confirmar</a>
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

    $( '#filter-solicitud-id' ).on( 'keypress' , function( event )
    {
        if( event.keyCode === 13 )
        {
            $( '#massive-deposit-table tbody tr' ).hide();
            $( '#massive-deposit-table tbody tr:contains(' + this.value + ')' ).show();
        }
    });

    $( document ).off( 'keypress' , '.deposit-solicitud-cell' );
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
        /*else if( e.keyCode === 39 )
        {
           var input =  tableBody.find( 'tr' ).eq( rowIndex ).find( 'td' ).eq( columIndex + 1 ).find( 'input' );
           input.focus();
        }*/
        else if( e.keyCode === 40 )
        {
           var input =  tableBody.find( 'tr' ).eq( rowIndex + 1 ).find( 'td' ).eq( columIndex ).find( 'input' );
           input.focus();
        }
        /*else if( e.keyCode === 13 )
        {
            var newTr = '<tr>' +
                        '<td class="deposit-solicitud-cell" ><input type="text" class="form-control"></td>' +
                        '<td class="deposit-operacion-cell"><input type="text" class="form-control"></td>' +
                    '</tr>';
            var tableBodyTr =  tableBody.find( 'tr' ).eq( rowIndex );
            tableBodyTr.after( newTr );
        }*/
    });
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