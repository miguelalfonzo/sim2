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
							<td class="name_account">{{$solicitude->detalle->fondo->account->nombre}}</td>
							<td class="number_account">{{$solicitude->detalle->fondo->account->num_cuenta}}</td>
							<td class="date_ini">{{$date['toDay']}}</td>
							<td class="dc">D</td>
							<td>{{$solicitude->detalle->fondo->typeMoney->simbolo}}</td>
							<td class="total">
								@if ( $solicitude->detalle->idmoneda != $solicitude->detalle->fondo->idtipomoneda )
									@if ( $solicitude->detalle->idmoneda == SOLES )
										{{$detalle->monto_aprobado/$detalle->tcv}}
									@elseif	( $solicitude->detalle->idmoneda == DOLARES )
										{{ $detalle->monto_aprobado*$detalle->tcc}}
									@endif
								@elseif ( $solicitude->detalle->idmoneda == $solicitude->detalle->fondo->idtipomoneda )
									{{ $detalle->monto_aprobado }}
								@endif  
							</td>
							<td class="leyenda">{{$lv}}</td>
						</tr>
						<tr>
							<td class="name_account">{{$solicitude->detalle->deposit->account->nombre}}</td>
							<td class="number_account">{{$solicitude->detalle->deposit->account->num_cuenta}}</td>
							<td class="date_ini">{{$date['toDay']}}</td>
							<td class="dc">C</td>
							<td>{{ $solicitude->detalle->deposit->account->typeMoney->simbolo }}</td>
							<td class="total">{{ $solicitude->detalle->deposit->total }}</td>
							<td class="leyenda">{{$lv}}</td>
						</tr>
						@if(!is_null($solicitude->detalle->idretencion))
							<tr>
								<td class="name_account">{{$solicitude->detalle->typeRetention->descripcion}}</td>
								<td class="number_account">{{$solicitude->detalle->typeRetention->account->num_cuenta}}</td>
								<td class="date_ini">{{$date['toDay']}}</td>
								<td class="dc">C</td>
								<td> {{ $solicitude->detalle->typeRetention->account->typeMoney->simbolo }}
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