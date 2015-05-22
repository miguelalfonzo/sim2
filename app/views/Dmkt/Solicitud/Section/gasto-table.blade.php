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
			<th class="proof-type">{{mb_convert_case($val->proof->descripcion,MB_CASE_TITLE,'UTF-8')}}</th>
			<th class="ruc">{{$val->ruc}}</th>
			<th class="razon">{{$val->razon}}</th>
			<th class="voucher_number">{{$val->num_prefijo.'-'.$val->num_serie}}</th>
			<th class="date_movement">{{date('d/m/Y',strtotime($val->fecha_movimiento))}}</th>
			<th class="total">
				<span class="type_money">{{$solicitud->detalle->typemoney->simbolo}}</span>
				<span class="total_expense">{{(real)$val->monto}}</span>
			</th>
			@if ( $solicitud->id_estado == GASTO_HABILITADO || Auth::user()->type == CONT )
				<th>
					<a href="#" class="edit-expense">
						<span class="glyphicon glyphicon-pencil"></span>
					</a>
				</th>
				<th>
					<a href="#" class="delete-expense">
						<span class="glyphicon glyphicon-remove"></span>
					</a>
				</th>
			@endif
		</tr>	
	@endforeach
</tbody>