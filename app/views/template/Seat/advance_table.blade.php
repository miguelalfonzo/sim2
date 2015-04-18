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
							<td class="date_ini">{{$date['toDay']}}</td>
							<td class="dc">D</td>
							<td>{{$solicitud->detalle->fondo->typeMoney->simbolo}}</td>
							<td class="total">
								@if ( $solicitud->detalle->idmoneda != $solicitud->detalle->fondo->idtipomoneda )
									@if ( $solicitud->detalle->idmoneda == SOLES )
										{{$detalle->monto_aprobado/$detalle->tcv}}
									@elseif	( $solicitud->detalle->idmoneda == DOLARES )
										{{ $detalle->monto_aprobado*$detalle->tcc}}
									@endif
								@elseif ( $solicitud->detalle->idmoneda == $solicitud->detalle->fondo->idtipomoneda )
									{{ $detalle->monto_aprobado }}
								@endif  
							</td>
							<td class="leyenda">{{$lv}}</td>
						</tr>
						<tr>
							<td class="name_account">{{$solicitud->detalle->deposit->account->nombre}}</td>
							<td class="number_account">{{$solicitud->detalle->deposit->account->num_cuenta}}</td>
							<td class="date_ini">{{$date['toDay']}}</td>
							<td class="dc">C</td>
							<td>{{ $solicitud->detalle->deposit->account->typeMoney->simbolo }}</td>
							<td class="total">{{ $solicitud->detalle->deposit->total }}</td>
							<td class="leyenda">{{$lv}}</td>
						</tr>
						@if(!is_null($solicitud->detalle->idretencion))
							<tr>
								<td class="name_account">{{$solicitud->detalle->typeRetention->descripcion}}</td>
								<td class="number_account">{{$solicitud->detalle->typeRetention->account->num_cuenta}}</td>
								<td class="date_ini">{{$date['toDay']}}</td>
								<td class="dc">C</td>
								<td> {{ $solicitud->detalle->typeRetention->account->typeMoney->simbolo }}
								<td class="total">
									{{$detalle->monto_retencion}}
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