<select>
	@foreach( $actividades as $actividad )
		@if ( isset( $val ) && $actividad->id == $val )
			<option value="{{$actividad->id}}" selected style="background-color:#A9E2F3">{{$actividad->nombre}}</option>
		@else
			<option value="{{$actividad->id}}">{{$actividad->nombre}}</option>
		@endif
	@endforeach
</select>