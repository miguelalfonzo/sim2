<div class="form-group col-sm-6 col-md-4">
    <label class="col-sm-8 col-md-8 control-label" for="textinput">Moneda</label>
    <div class="col-sm-12 col-md-12">
        <select id="money" name="moneda" class="form-control">
        	@foreach( $currencies as $currency )
            	@if ( isset( $solicitud ) && $solicitud->detalle->idmoneda == $money->idtipomoneda )
            		<option value="{{$currency->id}}" selected>{{$currency->descripcion}}</option>
            	@else
            		<option value="{{$currency->id}}">{{$currency->descripcion}}</option>
            	@endif
            @endforeach	
        </select>
    </div>
</div>