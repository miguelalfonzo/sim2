@extends('template.main')
@section('solicitude')
    <h3>Movimientos por Solicitud</h3>
    <div id="movimientos"></div>
    @if ( Auth::user()->type == TESORERIA )
        <div class="input-group">
            <span class="input-group-addon">S/.</span>
            <input type="text" class="estado-cuenta-deposito form-control input-md" readonly>
            <span class="input-group-addon">$</span>
            <input type="text" class="estado-cuenta-deposito form-control input-md" readonly>
        </div>
    @endif
    <script>
    $( document ).ready( function()
    {    
        GBREPORTS.changeDateRange('M');
        listTable( 'movimientos' , null );
    });
    </script>
@stop