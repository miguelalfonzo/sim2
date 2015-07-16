<div class="table-responsive">
	<table 	class="table table-bordered table-hover table-condensed tb_style">
		<thead>
			<tr>
				<th>Cuenta</th>
				<th>N° de Cuenta</th>
				<th>Fecha de Origen</th>
				<th>D/C</th>
				<th>Moneda</th>
				<th>Importe</th>
				<th>Leyenda Variable</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>{{$solicitud->detalle->fondo->nombre}}</td>
				<td>{{$solicitud->detalle->fondo->num_cuenta}}</td>
				<td>{{ $solicitud->detalle->deposit->updated_at }}</td>
				<td>D</td>
				<td>S/.</td>
				<td>
					@if ( $solicitud->detalle->id_moneda == DOLARES )
						{{ round( $detalle->monto_actual * $detalle->tcc , 2 , PHP_ROUND_HALF_DOWN ) }}
					@elseif ( $solicitud->detalle->id_moneda == SOLES )
						{{ $detalle->monto_actual }}
					@else
						-
					@endif
				</td>
				<td>{{{ $lv or $solicitud->advanceCreditEntry->leyenda }}}</td>
			</tr>
			<tr>
				<td>{{ $solicitud->detalle->deposit->bagoAccount->ctanombrecta }}</td>
				<td>{{ $solicitud->detalle->deposit->num_cuenta }}</td>
				<td>{{ $solicitud->detalle->deposit->updated_at }}</td>
				<td>C</td>
				<td>S/.</td>
				<td>
					@if ( $solicitud->detalle->deposit->account->idtipomoneda == DOLARES )
						{{ round( $solicitud->detalle->deposit->total * $detalle->tcv , 2 , PHP_ROUND_HALF_DOWN ) }}
					@elseif ( $solicitud->detalle->deposit->account->idtipomoneda == SOLES )
						{{ $solicitud->detalle->deposit->total }}
					@endif
				</td>
				<td>{{{ $lv or $solicitud->advanceDepositEntry->leyenda }}}</td>
			</tr>
		</tbody>
	</table>
</div>