<thead>
	<tr>
		<th>Comprobante</th>
		<th>RUC</th>
		<th>Razón Social</th>
		<th>Nro. Comprobante</th>
		<th>Fecha</th>
		<th>Monto Total</th>
		@if ( $solicitud->id_estado == GASTO_HABILITADO || Auth::user()->type == CONT )
			<th>Editar</th>
			<th>Eliminar</th>
		@endif
	</tr>
</thead>
<tbody>
	@foreach($expense as $val)
		<tr data-id="{{$val->id}}">
			<td class="proof-type text-center">{{mb_convert_case($val->proof->descripcion,MB_CASE_TITLE,'UTF-8')}}</td>
			<td class="ruc text-center">{{$val->ruc}}</td>
			<td class="razon text-center">{{$val->razon}}</td>
			<td class="voucher_number text-center">{{$val->num_prefijo.'-'.$val->num_serie}}</td>
			<td class="date_movement text-center">{{date('d/m/Y',strtotime($val->fecha_movimiento))}}</td>
			<td class="total text-center">
				<span class="type_money">{{$solicitud->detalle->typemoney->simbolo}}</span>
				<span class="total_expense">{{(real)$val->monto}}</span>
			</td>
			@if ( $solicitud->id_estado == GASTO_HABILITADO || Auth::user()->type == CONT )
				<td class="text-center">
					<a href="#" class="edit-expense">
						<span class="glyphicon glyphicon-pencil "></span>
					</a>
				</td>
				<td class="text-center">
					<a href="#" class="delete-expense">
						<span class="glyphicon glyphicon-remove"></span>
					</a>
				</td>
			@endif
		</tr>	
	@endforeach
</tbody>