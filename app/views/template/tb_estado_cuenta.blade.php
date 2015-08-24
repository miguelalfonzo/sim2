@extends('template.main')
@section('solicitude')
    <h3>Movimientos</h3>
    <div class="form-group col-sm-2 col-md-2">
        <select id="idState" name="idstate" class="form-control selectestatesolicitude">
            <option value="0" selected disabled>Seleccione el Fondo</option>
            @foreach( $fondosMkt as $fondoMkt )
                <option value="{{ $fondoMkt->id }}" selected disabled>{{ $fondoMkt->descripcion }}</option>
            @enforeach
        </select>
    </div>
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