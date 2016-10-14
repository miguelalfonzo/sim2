<select style="width:100%">
	@foreach( $Fondos_Contable as $Fondo_Contable )
		<option value="{{ $Fondo_Contable->id }}">{{$Fondo_Contable->nombre }}</option>
	@endforeach
</select>