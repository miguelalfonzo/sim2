<div class="form-group col-sm-6 col-md-4">
    <label class="col-sm-8 col-md-8 control-label" for="textinput">Moneda</label>
    <div class="col-sm-12 col-md-12">
        <select id="money" name="money" class="form-control">
        	@foreach( $typesMoney as $money )
            	@if ( isset( $solicitude ) && $solicitude->detalle->idmoneda == $money->idtipomoneda )
            		<option value="{{$money->idtipomoneda}}" selected>{{$money->descripcion}}</option>
            	@else
            		<option value="{{$money->idtipomoneda}}">{{$money->descripcion}}</option>
            	@endif
            @endforeach	
        </select>
    </div>
</div>