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
							<td class="name_account">{{$solicitud->detalle->fondo->account->nombre}}</td>
							<td class="number_account">{{$solicitud->detalle->fondo->account->num_cuenta}}</td>
							<td class="date_ini">{{ $solicitud->detalle->deposit->updated_at }}</td>
							<td class="dc">D</td>
							<td>S/.</td>
							<td class="total">
								@if ( $solicitud->detalle->idmoneda == DOLARES )
									{{ round( $detalle->monto_aprobado * $detalle->tcc , 2 , PHP_ROUND_HALF_DOWN ) }}
								@elseif ( $solicitud->detalle->idmoneda == SOLES )
									{{ $detalle->monto_aprobado }}
								@endif
							</td>
							<td class="leyenda">{{$lv}}</td>
						</tr>
						<tr>
							<td class="name_account">{{ $solicitud->detalle->deposit->account->nombre }}</td>
							<td class="number_account">{{$solicitud->detalle->deposit->account->num_cuenta}}</td>
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
						@if(!is_null($solicitud->detalle->idretencion))
							<tr>
								<td class="name_account">{{$solicitud->detalle->typeRetention->descripcion}}</td>
								<td class="number_account">{{$solicitud->detalle->typeRetention->account->num_cuenta}}</td>
								<td class="date_ini">{{ $solicitud->detalle->deposit->updated_at }}</td>
								<td class="dc">C</td>
								<td>S/.</td> 
								<td class="total">
									@if ( $solicitud->detalle->typeRetention->account->idtipomoneda == DOLARES )
										{{ round( $detalle->monto_retencion * $detalle->tcv , 2 , PHP_ROUND_HALF_DOWN ) }}
									@elseif ( $solicitud->detalle->typeRetention->account->idtipomoneda == SOLES )
										{{ $detalle->monto_retencion }}
									@endif 
								</td>
								<td class="leyenda">{{$lv}}</td>
							</tr>
						@endif
					</tbody>
				</table>
			</div>
		</div>
	</div>
</section>