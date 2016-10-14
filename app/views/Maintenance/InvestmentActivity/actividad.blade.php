<select style="width:100%">
	@foreach( $actividades as $actividad )
		<option value="{{$actividad->id}}">{{$actividad->nombre}}</option>
	@endforeach
</select>