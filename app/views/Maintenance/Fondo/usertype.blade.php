<select>
	@foreach( $datos as $userType )
		@if ( isset( $val ) && $userType->descripcion == $val )
			<option value="{{$userType->codigo}}" selected style="background-color:#A9E2F3">{{$userType->descripcion}}</option>
		@else
			<option value="{{$userType->codigo}}">{{$userType->descripcion}}</option>
		@endif
	@endforeach
</select>