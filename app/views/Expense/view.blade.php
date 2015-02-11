@extends ('template.main')
@section ('content')
	<div class="content">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title"><strong>Gasto Registrado</strong><strong class="user" style="margin-top:0">Usuario : {{Auth::user()->username}}</strong></h3>
		</div>
		<div class="panel-body">
			<section class="row reg-expense" style="margin:0">
				<div class="col-xs-12 col-sm-6 col-md-4">
					<div class="form-expense">
						<label>Solicitud</label>
						<input type="text" class="form-control" value="{{mb_convert_case($solicitude->titulo, MB_CASE_TITLE, 'UTF-8')}}" disabled>
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
						<label>Código de Depósito</label>
						<input type="text" class="form-control" value="101" disabled>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-4">
					<div class="form-expense">
						<label>Autorizado por</label>
						<input type="text" class="form-control" value="Lucy Alfaro" disabled>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-4">
					<div class="form-expense">
						<label>Monto Depositado</label>
						<div class="input-group">
					    	<div id="type-money" class="input-group-addon">{{$solicitude->typemoney->simbolo}}</div>
					      	<input id="deposit" class="form-control" type="text" value="{{$solicitude->monto}}" disabled>
					    </div>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-4">
					<div class="form-expense">
						<label>Monto Restante</label>
						<div class="input-group">
					    	<div class="input-group-addon">{{$solicitude->typemoney->simbolo}}</div>
					      	<input id="balance" class="form-control" type="text" value="0" disabled>
					    </div>
					</div>
				</div>
			</section>
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
												<th class="total"><span class="type_moeny">{{$solicitude->typemoney->simbolo}}&nbsp;<span class="total_expense">{{$val->monto}}</span></th>
											</tr>	
										@endforeach
									@endif
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</section>
			<section class="row reg-expense align-center" style="margin-top:2em">
				<div class="col-xs-12 col-sm-12 col-md-12">
					<a href="#" id="cancel-expense" class="btn btn-danger" style="margin:-2em 2em .5em 0">Cancelar</a>
				</div>
			</section>
		</div>
	</div>
</div>
@stop