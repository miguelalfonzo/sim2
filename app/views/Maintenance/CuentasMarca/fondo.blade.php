<select>
	@foreach( $fondos as $fondo )
		@if ( isset( $val ) && $fondo->nombre == $val )
			<option value="{{$fondo->id}}" selected style="background-color:#A9E2F3">{{$fondo->nombre}}</option>
		@else
			<option value="{{$fondo->id}}">{{$fondo->nombre}}</option>
		@endif
	@endforeach
</select>