<select>
	@foreach( $tipo_cliente as $clientType )
		@if ( isset( $val ) && $clientType->id == $val )
			<option value="{{$clientType->id}}" selected style="background-color:#A9E2F3">{{$clientType->descripcion}}</option>
		@else
			<option value="{{$clientType->id}}">{{$clientType->descripcion}}</option>
		@endif
	@endforeach
</select>