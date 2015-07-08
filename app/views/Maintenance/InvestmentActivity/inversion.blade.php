<select>
	@foreach( $inversiones as $inversion )
		@if ( isset( $val ) && $inversion->id == $val )
			<option value="{{$inversion->id}}" selected style="background-color:#A9E2F3">{{$inversion->nombre}}</option>
		@else
			<option value="{{$inversion->id}}">{{$inversion->nombre}}</option>
		@endif
	@endforeach
</select>