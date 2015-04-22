@extends('template.main')
@section('solicitude')
	<div class="content">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><strong>Generar Asiento de Gasto</strong></h3><strong class="user">Usuario : {{Auth::user()->username}}</strong>
			</div>
			<div class="panel-body">
				<section class="row reg-expense" style="margin:0">
					<div class="col-xs-12 col-sm-6 col-md-4">
						<div class="form-expense">
							<label>Código de la Solicitud</label>
							<input id="idsolicitud" type="text" class="form-control" value="{{$solicitude->idsolicitud}}" disabled>
							{{Form::token()}}
						</div>
					</div>
					<div class="col-xs-12 col-sm-6 col-md-4">
						<div class="form-expense">
							<label>Nombre de la Solicitud</label>
							<input type="text" class="form-control" value="{{mb_convert_case($solicitude->titulo,MB_CASE_TITLE,'UTF-8')}}" disabled>
						</div>
					</div>
					<div class="col-xs-12 col-sm-6 col-md-4">
						<div class="form-expense">
							<div class="form-expense">
							<label>Nombre del Solicitante</label>
							<div class="input-group">
		                        @if($solicitude->createdBy->type == 'R')
		                        <span class="input-group-addon">R</span>
		                        <input id="textinput" name="titulo" type="text" placeholder=""
		                               value="{{mb_convert_case($solicitude->createdBy->rm->nombres.' '.$solicitude->createdBy->rm->apellidos,MB_CASE_TITLE,'UTF-8')}}" disabled
		                               class="form-control">
		                        @else
		                        <span class="input-group-addon">S</span>
		                        <input id="textinput" name="titulo" type="text" placeholder=""
		                               value="{{mb_convert_case($solicitude->createdBy->sup->nombres.' '.$solicitude->createdBy->sup->apellidos,MB_CASE_TITLE,'UTF-8')}}" disabled
		                               class="form-control">
		                        @endif
		                    </div>							
						</div>
						</div>
					</div>
					<div class="col-xs-12 col-sm-6 col-md-4">
						<div class="form-expense">
							<label>Fondo</label>
							<input type="text" class="form-control" value="{{mb_convert_case($solicitude->detalle->fondo->nombre, MB_CASE_TITLE, 'UTF-8')}}" disabled>
						</div>
					</div>
					<div class="col-xs-12 col-sm-6 col-md-4">
						<div class="form-expense">
							<label>Monto de la Solicitud</label>
							<div class="input-group">
						    	<div id="type-money" class="input-group-addon">{{$solicitude->detalle->typeMoney->simbolo}}</div>
						      	<input id="deposit" class="form-control" type="text" value="{{$solicitude->monto}}" disabled>
						    </div>
						</div>
					</div>
					<div class="col-xs-12 col-sm-6 col-md-4">
						<div class="form-expense">
							<label>Autorizado por</label>
							<div class="input-group">
		                        @if($solicitude->acceptHist->updatedBy->type == 'S')
		                        <span class="input-group-addon">S</span>
		                        <input id="textinput" name="titulo" type="text" placeholder=""
		                               value="{{mb_convert_case($solicitude->acceptHist->updatedBy->sup->nombres,MB_CASE_TITLE,'UTF-8')}}" disabled
		                               class="form-control">
		                        @else
		                        <span class="input-group-addon">G</span>
		                        <input id="textinput" name="titulo" type="text" placeholder=""
		                               value="{{mb_convert_case($solicitude->aproved->gerprod->descripcion,MB_CASE_TITLE,'UTF-8')}}" disabled
		                               class="form-control">
		                        @endif
		                    </div>							
						</div>
					</div>
					<div class="col-xs-12 col-sm-6 col-md-4">
						<div class="form-expense">
							<label>Número de Depósito</label>
							<input type="text" class="form-control" value="{{$solicitude->detalle->iddeposito}}" disabled>
						</div>
					</div>
					<div class="col-xs-12 col-sm-6 col-md-4">
						<div class="form-expense">
							<label>Fecha de Depósito</label>
							<input type="text" class="form-control" value="{{date_format(date_create($solicitude->detalle->deposit->created_at), 'd/m/Y')}}" disabled>
						</div>
					</div>
			</div>
			<section class="row reg-expense" style="margin:0">
				<div class="col-xs-12 col-sm-12 col-md-12">
					<div class="form-expense">
						<div class="table-responsive">
							<table id="table-expense" class="table table-bordered">
								<thead>
									<tr>
										<th>Comprobante</th>
										<th>RUC</th>
										<th>Razón Social</th>
										<th>Nro. Comprobante</th>
										<th>Fecha</th>
										<th>Monto Total</th>
									</tr>
								</thead>
								<tbody>
									@if(isset($expense))
										@foreach($expense as $val)
											<tr>
												<th class="proof-type">{{mb_convert_case($val->idProofType->descripcion,MB_CASE_TITLE,'UTF-8')}}</th>
												<th class="ruc">{{$val->ruc}}</th>
												<th class="razon">{{$val->razon}}</th>
												<th class="voucher_number">{{$val->num_prefijo.'-'.$val->num_serie}}</th>
												<th class="date_movement">{{date('d/m/Y',strtotime($val->fecha_movimiento))}}</th>
												<th class="total"><span class="type_moeny">{{$solicitude->detalle->typeMoney->simbolo}}&nbsp;<span class="total_expense">{{(real)$val->monto}}</span></th>
											</tr>	
										@endforeach
									@endif
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</section>
			<section class="row reg-expense align-center" style="margin:1.5em 0">
				<div class="col-xs-12 col-sm-12 col-md-12">
					<a href="#" id="seat-expense" class="btn btn-success" style="margin:-2em 2em .5em 0">Generar Asiento Gasto</a>
					<a href="#" id="cancel-seat-cont" class="btn btn-danger" style="margin:-2em 2em .5em 0">Cancelar</a>
				</div>
			</section>
		</div>
	</div>
@stop