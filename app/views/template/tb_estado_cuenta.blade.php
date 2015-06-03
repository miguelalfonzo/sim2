<div class="tab-pane fade" id="estado-cuenta">
    <div class="panel panel-default" style="margin-top: 10px">
        <div class="panel-body table_movimientos">
        	<div class="form-group col-xs-6 col-sm-3 col-md-3">
        		<div class="input-group">
        			<input type="text" readonly class="form-control date_month" data-type="estado-cuenta" style="background-color:#FFF" readonly>
                    <span class="input-group-addon">
                        <i class="glyphicon glyphicon-calendar"></i>
                    </span>
        		</div>
            </div>
        </div>
        @if ( Auth::user()->type == TESORERIA )
            <div class="input-group">
                <span class="input-group-addon">S/.</span>
                <input type="text" class="estado-cuenta-deposito form-control input-md" readonly>
                <span class="input-group-addon">$</span>
                <input type="text" class="estado-cuenta-deposito form-control input-md" readonly>
            </div>
        @endif
    </div>  
</div>