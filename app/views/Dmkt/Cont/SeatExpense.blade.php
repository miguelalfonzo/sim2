@extends('template.main')
@section('solicitude')
<div class="content">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><strong>Generar Asiento de Solicitud</strong></h3><strong class="user">Usuario : {{Auth::user()->username}}</strong>
			</div>
			<div class="panel-body">
				<section class="row reg-expense" style="margin:0">
					<div class="col-xs-12 col-sm-6 col-md-4">
						<div class="form-expense">
							<label>Solicitud</label>
							<input id="idsolicitud" type="text" class="form-control" rel="{{$solicitude->idsolicitud}}" value="{{$solicitude->idsolicitud}} - {{mb_convert_case($solicitude->titulo,MB_CASE_TITLE,'UTF-8')}}" disabled>
						</div>
					</div>
					<div class="col-xs-12 col-sm-6 col-md-4">
						<div class="form-expense">
							<label>Fondo</label>
							<input type="text" class="form-control" value="{{mb_convert_case($solicitude->subtype->nombre_mkt, MB_CASE_TITLE, 'UTF-8')}}" disabled>
						</div>
					</div>
					<div class="col-xs-12 col-sm-6 col-md-4">
						<div class="form-expense">
							<label>Monto de la Solicitud</label>
							<div class="input-group">
						    	<div id="type-money" class="input-group-addon">{{$solicitude->typemoney->simbolo}}</div>
						      	<input id="deposit" class="form-control" type="text" value="{{$solicitude->monto}}" disabled>
						    </div>
						</div>
					</div>
					<div class="col-xs-12 col-sm-6 col-md-4">
						<div class="form-expense">
							<label>Nombre del Solicitante</label>
							<div class="input-group">
		                        @if($solicitude->user->type == 'R')
		                        <span class="input-group-addon">R</span>
		                        <input id="textinput" name="titulo" type="text" placeholder=""
		                               value="{{mb_convert_case($solicitude->user->rm->nombres.' '.$solicitude->user->rm->apellidos,MB_CASE_TITLE,'UTF-8')}}" disabled
		                               class="form-control">
		                        @else
		                        <span class="input-group-addon">S</span>
		                        <input id="textinput" name="titulo" type="text" placeholder=""
		                               value="{{mb_convert_case($solicitude->user->sup->nombres.' '.$solicitude->user->sup->apellidos,MB_CASE_TITLE,'UTF-8')}}" disabled
		                               class="form-control">
		                        @endif
		                    </div>							
						</div>
					</div>
					<div class="col-xs-12 col-sm-6 col-md-4">
						<div class="form-expense">
							<label>Autorizado por</label>
							<div class="input-group">
		                        @if($solicitude->aproved->type == 'S')
		                        <span class="input-group-addon">S</span>
		                        <input id="textinput" name="titulo" type="text" placeholder=""
		                               value="{{mb_convert_case($solicitude->aproved->sup->nombres.' '.$solicitude->aproved->sup->apellidos,MB_CASE_TITLE,'UTF-8')}}" disabled
		                               class="form-control">
		                        @else
		                        <span class="input-group-addon">G</span>
		                        <input id="textinput" name="titulo" type="text" placeholder=""
		                               value="{{mb_convert_case($solicitude->aproved->gerprod->descripcion,MB_CASE_TITLE,'UTF-8')}}" disabled class="form-control">
		                        @endif
		                    </div>							
						</div>
					</div>
					<div class="col-xs-12 col-sm-6 col-md-4" style="display:none;">
						<div class="form-expense">
							<label>Número de Depósito</label>
							<input type="text" class="form-control" value="{{$solicitude->iddeposito}}" disabled>
						</div>
					</div>
					<div class="col-xs-12 col-sm-6 col-md-4">
						<div class="form-expense">
							<label>Fecha de Depósito</label>
							<input type="text" class="form-control" value="{{date_format(date_create($solicitude->deposit->created_at), 'd/m/Y')}}" disabled>
						</div>
					</div>
				</section>
				<hr>

				<section class="row expense_items" style="margin:0">
					<div class="col-xs-12 col-sm-12 col-md-12">
						<div class="form-expense">
							<div class="table-responsive">
								<table id="table-expense_items" class="table table-bordered">
									<thead>
										<tr>
											<th>N° Gasto</th>
										    <th>Comprobante</th>
										    <th>RUC</th>
										    <th>Razon Social</th>
										    <th>N° Documento</th>
										    <th>Descripcion</th>
										    <th>Sub Total</th>
										    <th>imp_serv</th>
										    <th>IGV</th>
										    <th>Total</th>
										    <th>fecha_movimiento</th>
										    <th>idigv</th>
										    <th>tipo</th>
										</tr>
									</thead>
									<tbody>
									@foreach($expenseItem as $expenseValue)
										<tr>
											<td rowspan="{{ $expenseValue->count }}">{{ $expenseValue->idgasto }}</td>
										    @foreach($typeProof as $val)
										    	@if($expenseValue->idcomprobante == $val->idcomprobante)
										    <td rowspan="{{ $expenseValue->count }}" data-id="{{ $val->idcomprobante }}">{{ $val->descripcion }}</td>
										    	@endif
											@endforeach
										    <td rowspan="{{ $expenseValue->count }}">{{ $expenseValue->num_prefijo }}-{{ $expenseValue->num_serie }}</td>
										    <td rowspan="{{ $expenseValue->count }}">{{ $expenseValue->ruc }}</td>
										    <td rowspan="{{ $expenseValue->count }}">{{ $expenseValue->razon }}</td>
										    <td rowspan="{{ $expenseValue->count }}">{{ $expenseValue->descripcion }}</td>
										    <td rowspan="{{ $expenseValue->count }}">{{ $expenseValue->sub_tot }}</td>
										    <td rowspan="{{ $expenseValue->count }}">{{ $expenseValue->imp_serv }}</td>
										    <td rowspan="{{ $expenseValue->count }}">{{ $expenseValue->igv }}</td>
										    <td rowspan="{{ $expenseValue->count }}">{{ $expenseValue->monto }}</td>
										    <td rowspan="{{ $expenseValue->count }}">{{ $expenseValue->fecha_movimiento }}</td>
										    <td rowspan="{{ $expenseValue->count }}">{{ $expenseValue->idigv }}</td>
										    <td rowspan="{{ $expenseValue->count }}">{{ $expenseValue->tipo }}</td>
										</tr>
									@endforeach

									</tbody>
								</table>
							</div>
						</div>
					</div>
				</section>
				
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
											<th>Importe</th>
											<th>Leyenda Variable</th>
											<th>Editar</th>
											<th>Eliminar</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<th class="name_account">Fondo</th>
											<th class="number_account">{{$solicitude->subtype->cuenta_mkt}}</th>
											<th class="date_ini">{{$date['toDay']}}</th>
											<th class="dc">D</th>
											<th class="total">{{$solicitude->monto}}</th>
											<th class="leyenda">
												@if($solicitude->aproved->type == 'S')
													{{$solicitude->aproved->sup->nombres.' '.$solicitude->aproved->sup->apellidos.' '.$solicitude->titulo.' '.$clientes}}
												@else
													{{$solicitude->aproved->gerprod->descripcion.' '.$solicitude->titulo.' '.$clientes}}
												@endif
											</th>
											<th><a class="edit-seat-solicitude" href="#"><span class="glyphicon glyphicon-pencil"></span></a></th>
											<th><a class="delete-seat-solicitude" href="#"><span class="glyphicon glyphicon-remove"></span></a></th>
										</tr>
										<tr>
											<th class="name_account">Bancos</th>
											<th class="number_account">{{$solicitude->subtype->cuenta_cont}}</th>
											<th class="date_ini">{{$date['toDay']}}</th>
											<th class="dc">C</th>
											<th class="total">{{$solicitude->monto}}</th>
											<th class="leyenda">
												@if($solicitude->aproved->type == 'S')
													{{$solicitude->aproved->sup->nombres.' '.$solicitude->aproved->sup->apellidos.' '.$solicitude->titulo.$clientes}}
												@else
													{{$solicitude->aproved->gerprod->descripcion.' '.$solicitude->titulo.' '.$clientes}}
												@endif
											</th>
											<th><a class="edit-seat-solicitude" href="#"><span class="glyphicon glyphicon-pencil"></span></a></th>
											<th><a class="delete-seat-solicitude" href="#"><span class="glyphicon glyphicon-remove"></span></a></th>
										</tr>
										@if(isset($solicitude->retencion))
											<tr>
												<th class="name_account">{{mb_convert_case($solicitude->typeRetention->descripcion,MB_CASE_TITLE,'UTF-8')}}</th>
												<th class="number_account">{{$solicitude->typeRetention->cta_contable}}</th>
												<th class="date_ini">{{$date['toDay']}}</th>
												<th class="dc">D</th>
												<th class="total">{{$solicitude->retencion}}</th>
												<th class="leyenda">
													@if($solicitude->aproved->type == 'S')
														{{$solicitude->aproved->sup->nombres.' '.$solicitude->aproved->sup->apellidos.' '.$solicitude->titulo.' '.$clientes}}
													@else
														{{$solicitude->aproved->gerprod->descripcion.' '.$solicitude->titulo.' '.$clientes}}
													@endif
												</th>
												<th><a class="edit-seat-solicitude" href="#"><span class="glyphicon glyphicon-pencil"></span></a></th>
												<th><a class="delete-seat-solicitude" href="#"><span class="glyphicon glyphicon-remove"></span></a></th>
											</tr>
										@endif
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</section>
				<section class="row reg-expense align-center" style="margin:1.5em 0">
					<div class="col-xs-12 col-sm-12 col-md-12">
						<a id="seat-solicitude" class="btn btn-success" style="margin:-2em 2em .5em 0">Generar Asiento Solicitud</a>
						<a id="cancel-seat-cont" href="#" class="btn btn-danger" style="margin:-2em 2em .5em 0">Atras</a>
					</div>
				</section>
			</div>
		</div>
	</div>
@stop