@extends ('template.main')
@section ('content')
<div class="content">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title"><strong>Registro de Gastos Fondo Institucional</strong></h3><strong class="user">Usuario : {{Auth::user()->Rm->nombres}}</strong>
		</div>
		<div class="panel-body">
			<section class="row reg-expense" style="margin:0">
				<div class="col-xs-12 col-sm-6 col-md-4">
					<div class="form-expense">
						<label>Rep. Medico</label>
						<input type="text" class="form-control" value="{{mb_convert_case($fondo->repmed, MB_CASE_TITLE, 'UTF-8')}}" disabled>
						<input type="hidden" id="idfondo" value="{{$fondo->idfondo}}">
						{{Form::token()}}
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-4">
					<div class="form-expense">
						<label>Supervisor</label>
						<input type="text" class="form-control" value="{{mb_convert_case($fondo->supervisor, MB_CASE_TITLE, 'UTF-8')}}" disabled>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-4">
					<div class="form-expense">
						<label>Código de Depósito</label>
						<input type="text" class="form-control" value="{{$fondo->deposit->num_transferencia}}" disabled>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-4">
					<div class="form-expense">
						<label>Nº de Cuenta</label>
						<input type="text" class="form-control" value="{{$fondo->cuenta}}" disabled>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-4">
					<div class="form-expense">
						<label>Monto Depositado</label>
						<div class="input-group">
					    	<div id="type-money" class="input-group-addon">S/.</div>
					      	<input id="deposit" class="form-control" type="text" value="{{$fondo->total}}" disabled>
					    </div>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-4">
					<div class="form-expense">
						<label>Monto Restante</label>
						<div class="input-group">
					    	<div class="input-group-addon">S/.</div>
					      	<input id="balance" class="form-control" type="text" value="{{$balance}}" disabled>
					    </div>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-4">
					<div class="form-expense">
						<label>Tipo de Comprobante</label>
						<select name="" id="proof-type" class="form-control">
							@foreach($typeProof as $val)
								<option value="{{$val->idcomprobante}}">{{mb_convert_case($val->descripcion,MB_CASE_TITLE,'utf-8')}}</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-4">
					<div class="form-expense">
						<label>RUC</label>
						<div class="input-group">
							<input id="ruc" type="text" class="form-control" maxlength="11">
							<div class="input-group-addon search-ruc" data-sol="1"><span class="glyphicon glyphicon-search"></span></div>
							<input id="ruc-hide" type="hidden">
						</div>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-4">
					<div class="form-expense">
						<label>Razón Social</label>
						<button id="razon" type="button" class="form-control ladda-button" data-style="expand-left" data-spinner-color="#5c5c5c" value=0 data-edit=0 readonly>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-4">
					<div class="form-expense">
						<label class="inline-block">Número de Comprobante</label>
						<div class="input-group">
							<input id="number-prefix" type="text" class="form-control">
							<div class="input-group-addon">-</div>
					      	<input id="number-serie" class="form-control" type="text">
						</div>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-4">
					<div class="form-expense">
						<label>Fecha de Movimiento</label>
						<div class="input-group date">
							<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
							<input id="date" type="text" class="form-control" maxlength="10" value="{{$date['toDay']}}" readonly>
							<input id="last-date" type="hidden" value="{{$date['lastDay']}}">
						</div>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-4">
					<div class="form-expense">
						<label>Descripción del Gasto</label>
						<input id="desc-expense" type="text" class="form-control">
					</div>
				</div>
			</section>
			<section class="row reg-expense detail-expense" style="margin:0">
				<div style="padding:0 15px">
					<div class="panel panel-info">
						<div class="panel-heading">
							<span class="text-left">Número de Items</span>
						</div>
						<div class="panel-body">
							<div class="table-responsive">
								<table id="table-items" class="table table-bordered">
									<thead>
										<tr>
											<th class="w-quantity">Cantidad</th>
											<th class="w-desc-item">Descripción</th>
											<!-- <th class="w-type-expense">Tipo de Gasto</th> -->
											<th class="w-total-item">Valor de Venta</th>
											<th>Eliminar</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<th class="quantity"><input type="text" class="form-control" maxlength="4"></th>
											<th class="description"><input type="text" class="form-control"></th>
											<!-- <th>
												<select class="form-control type-expense">
													@foreach($typeExpense as $val)
														<option value="{{$val->idtipogasto}}">{{mb_convert_case($val->descripcion,MB_CASE_TITLE, 'UTF-8')}}</option>
													@endforeach
												</select>
											</th> -->
											<th class="total-item">
												<div class="input-group">
											    	<div class="input-group-addon">S/.</div>
											      	<input class="form-control" type="text" maxlength="8">
											    </div>
											</th>
											<th><a class="delete-item" href="#"><span class="glyphicon glyphicon-remove"></span></a></th>
										</tr>

									</tbody>
								</table>
								<aside class="col-xs-12 col-sm-6 col-md-4" style="padding:0;">
									<button id="add-item" type="button" class="btn btn-dafault">Agregar Item</button>
								</aside>
							</div>
						</div>
					</div>
				</div>
			</section>
			<section class="row reg-expense detail-expense" style="margin:0">
				<div class="col-xs-12 col-sm-6 col-md-4 tot-document">
					<div class="form-expense">
						<label>Sub Total</label>
						<div class="input-group">
					    	<div class="input-group-addon">S/.</div>
					      	<input id="sub-tot" class="form-control" type="text" value=0>
					    </div>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-4 tot-document">
					<div class="form-expense">
						<label>Impuesto por Servicio</label>
						<div class="input-group">
					    	<div class="input-group-addon">S/.</div>
					      	<input id="imp-ser" class="form-control" type="text" value=0>
					    </div>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-4 tot-document">
					<div class="form-expense">
						<label>IGV</label>
						<div class="input-group">
					    	<div class="input-group-addon">S/.</div>
					      	<input id="igv" class="form-control" type="text" value=0>
					    </div>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-4">
					<div class="form-expense">
						<label>Monto Total</label>
						<div class="input-group">
					    	<div class="input-group-addon">S/.</div>
					      	<input id="total-expense" class="form-control" type="text" disabled>
					    </div>
					</div>
				</div>
			</section>
			<section class="row reg-expense detail-expense" style="margin:0">
				<div class="col-xs-12 col-sm-12 col-md-12">
					<div class="form-expense">
						<button id="save-expense" data-sol="1" data-type="F" type="button" class="btn btn-primary">Registrar</button>
						<div class="inline"><p class="inline message-expense"></p></div>
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
										<th>Editar</th>
										<th>Eliminar</th>
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
												<th class="total"><span class="type_moeny">S/.&nbsp;<span class="total_expense">{{$val->monto}}</span></th>
												<th><a class="edit-expense" data-sol="1" href="#"><span class="glyphicon glyphicon-pencil"></span></a></th>
												<th><a class="delete-expense" href="#"><span class="glyphicon glyphicon-remove"></span></a></a></th>
											</tr>
										@endforeach
									@endif
								</tbody>
							</table>
						</div>
						<input id="tot-edit-hidden" type="hidden" value="">
					</div>
				</div>
			</section>
			<section class="row reg-expense align-center" style="margin-top:2em">
				<div class="col-xs-12 col-sm-12 col-md-12">
					<a href="#" id="finish-expense" class="btn btn-success" data-type="F" data-idfondo ="{{$fondo->idfondo}}" style="margin:-2em 2em .5em 0">Terminar</a>
					<a href="#" id="cancel-expense" class="btn btn-danger" style="margin:-2em 2em .5em 0">Cancelar</a>
				</div>
			</section>
		</div>
	</div>
</div>
@stop