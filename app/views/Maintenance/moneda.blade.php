<select>
	@foreach( $monedas as $moneda )
		@if ( isset( $val ) && $moneda->simbolo == $val )
			<option value="{{$moneda->id}}" selected style="background-color:#A9E2F3">{{$moneda->simbolo}}</option>
		@else
			<option value="{{$moneda->id}}">{{$moneda->simbolo}}</option>
		@endif
	@endforeach
</select>