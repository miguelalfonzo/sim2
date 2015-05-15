<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
    <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12 control-label">Moneda</label>
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <select name="moneda" class="form-control">
        	@foreach( $currencies as $currency )
            	@if ( isset( $solicitud ) && $solicitud->detalle->idmoneda == $currency->idtipomoneda )
            		<option value="{{$currency->id}}" selected>{{$currency->descripcion}}</option>
            	@else
            		<option value="{{$currency->id}}">{{$currency->descripcion}}</option>
            	@endif
            @endforeach	
        </select>
    </div>
</div>