<select>
	@foreach( $docs as $doc )
		@if ( isset( $val ) && $doc->codigo == $val )
			<option value="{{$doc->id}}" selected style="background-color:#A9E2F3">{{$doc->codigo}}</option>
		@else
			<option value="{{$doc->id}}">{{$doc->codigo}}</option>
		@endif
	@endforeach
</select>