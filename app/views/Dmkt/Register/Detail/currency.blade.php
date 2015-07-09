<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
    <label class="control-label">Moneda</label>
    <div>
        <select name="moneda" class="form-control">
        	@foreach( $currencies as $currency )
            	@if ( isset( $solicitud ) && $solicitud->detalle->id_moneda == $currency->id )
            		<option value="{{$currency->id}}" selected>{{$currency->descripcion}}</option>
            	@else
            		<option value="{{$currency->id}}">{{$currency->descripcion}}</option>
            	@endif
            @endforeach	
        </select>
    </div>
</div>