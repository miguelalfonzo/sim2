<select style="width:100%">
	@foreach( $inversiones as $inversion )
		<option value="{{$inversion->id}}">{{$inversion->nombre}}</option>
	@endforeach
</select>