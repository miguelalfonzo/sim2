<section class="row reg-expense" style="margin:0">
	<div class="col-xs-12 col-sm-12 col-md-12">
		<div class="form-expense">
			<div class="table-responsive">
				<table id="table-seat-solicitude" class="table table-bordered">
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
							<td class="name_account">
								@if( $solicitud->idtiposolicitud == SOL_REP )
									{{ $solicitud->products[ 0 ]->thisSubFondo->subCategoria->accountFondo->nombre }}
								@else
									{{ $solicitud->detalle->thisSubFondo->subCategoria->accountFondo->nombre }}
								@endif
							</td>
							<td class="number_account">
								@if( $solicitud->idtiposolicitud == SOL_REP )
									{{ $solicitud->products[ 0 ]->thisSubFondo->subCategoria->accountFondo->num_cuenta }}</td>
								@else
									{{ $solicitud->detalle->thisSubFondo->subCategoria->accountFondo->num_cuenta }}
								@endif
							<td class="date_ini">{{ $solicitud->detalle->deposit->updated_at }}</td>
							<td class="dc">D</td>
							<td>S/.</td>
							<td class="total">
								@if ( $solicitud->detalle->id_moneda == DOLARES )
									{{ round( $detalle->monto_actual * $detalle->tcc , 2 , PHP_ROUND_HALF_DOWN ) }}
								@elseif ( $solicitud->detalle->id_moneda == SOLES )
									{{ $detalle->monto_actual }}
								@else
									100
								@endif
							</td>
							<td class="leyenda">{{ $lv }}</td>
						</tr>
						<tr>
							<td class="name_account">{{ $solicitud->detalle->deposit->bagoAccount->ctanombrecta }}</td>
							<td class="number_account">{{ $solicitud->detalle->deposit->num_cuenta }}</td>
							<td class="date_ini">{{ $solicitud->detalle->deposit->updated_at }}</td>
							<td class="dc">C</td>
							<td>S/.</td>
							<td class="total">
								@if ( $solicitud->detalle->deposit->account->idtipomoneda == DOLARES )
									{{ round( $solicitud->detalle->deposit->total * $detalle->tcv , 2 , PHP_ROUND_HALF_DOWN ) }}
								@elseif ( $solicitud->detalle->deposit->account->idtipomoneda == SOLES )
									{{ $solicitud->detalle->deposit->total }}
								@endif
							</td>
							<td class="leyenda">{{$lv}}</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</section>