@extends('template.main')
@section('solicitude')
	<div class="content">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">
					<strong>Generar Asiento de Anticipo</strong>
				</h3>
				<strong class="user">Usuario: {{Auth::user()->username}}</strong>
			</div>
			<div class="panel-body">
				<aside class="row reg-expense" style="margin-bottom: 0.5em;">
					<div class="col-xs-12 col-sm-6 col-md-4">
						<a>
							<span id="text_solicitude">Ocultar Detalle de Solicitud</span>
							<span class="glyphicon glyphicon-chevron-up"></span>
						</a>
					</div>
				</aside>
				<section id="collapseOne" class="row reg-expense collapse in" style="margin:0">
					@if ($type == SOLIC)
						<div class="col-xs-12 col-sm-6 col-md-4">
							<div class="form-expense">
								<label>Nombre de la Solicitud</label>
								<input type="text" class="form-control" value="{{mb_convert_case($solicitude->titulo,MB_CASE_TITLE,'UTF-8')}}" disabled>
							</div>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-4">
							<div class="form-expense">
								<label>Nombre del Solicitante</label>
								<div class="input-group">
			                        @if($solicitude->createdBy->type == REP_MED)
			                        <span class="input-group-addon">R</span>
			                        <input id="textinput" name="titulo" type="text" placeholder=""
			                               value="{{mb_convert_case($solicitude->createdBy->rm->nombres.' '.$solicitude->createdBy->rm->apellidos,MB_CASE_TITLE,'UTF-8')}}" disabled
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
								<label>Fondo</label>
								</div>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-4">
							<div class="form-expense">
								<label>Monto de la Solicitud</label>
								<div class="input-group">
							    	<input id="deposit" class="form-control" type="text" value="{{$solicitude->monto}}" disabled>
							    </div>
							</div>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-4">
							<div class="form-expense">
								<label>Autorizado por</label>
								<div class="input-group">
			                        </div>							
							</div>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-4">
							<div class="form-expense">
								<label>Número de Depósito</label>
								<input type="text" class="form-control" value="{{$solicitude->iddeposito}}" disabled>
							</div>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-4">
							<div class="form-expense">
								<label>Fecha de Depósito</label>
							</div>
						</div>
					@endif
				</section>
				<hr>
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
										</tr>
									</thead>
									<tbody>
											<tr>
												<th class="name_account">{{$solicitude->subtype->nombre_mkt}}</th>
												<th class="number_account">{{$solicitude->subtype->cuenta_mkt}}</th>
												<th class="date_ini">{{$date['toDay']}}</th>
												<th class="dc">D</th>
												<th class="total">3000</th>
												<th class="leyenda">
													@if($solicitude->aproved->type == SUP)
														{{$solicitude->aproved->sup->nombres.' '.$solicitude->aproved->sup->apellidos.' - '.ucwords($solicitude->titulo).' - '.$clientes}}
													@else
														{{$solicitude->aproved->gerprod->descripcion.' - '.ucwords($solicitude->titulo).' - '.$clientes}}
													@endif
												</th>
											</tr>
											<tr>
												<th class="name_account">{{$bancos->nombre}}</th>
												<th class="number_account">{{$bancos->num_cuenta}}</th>
												<th class="date_ini">{{$date['toDay']}}</th>
												<th class="dc">C</th>
												<th class="total">{{ $solicitude }}</th>
												<th class="leyenda">
													@if($solicitude->aproved->type == SUP)
														{{$solicitude->aproved->sup->nombres.' '.$solicitude->aproved->sup->apellidos.' - '.ucwords($solicitude->titulo).' - '.$clientes}}
													@else
														{{$solicitude->aproved->gerprod->descripcion.' '.ucwords($solicitude->titulo).' - '.$clientes}}
													@endif
												</th>
											</tr>
										
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