<select style="width:100%">
	@foreach( $Tipo_Instancias_Aprobacion as $Tipo_Instancia_Aprobacion )
		<option value="{{ $Tipo_Instancia_Aprobacion->id }}">{{$Tipo_Instancia_Aprobacion->descripcion }}</option>
	@endforeach
</select>