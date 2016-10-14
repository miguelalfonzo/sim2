<select>
	@foreach( $Tipos_Documento as $Tipo_Documento )
		<option value="{{$Tipo_Documento->id}}">{{$Tipo_Documento->codigo}}</option>
	@endforeach
</select>