@extends ('template.main')
@section ('content')
<div class="content">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title"><strong>Registro de Gastos</strong></h3>
		</div>
		<div class="panel-body">
			<section class="row reg-expense" style="margin:0">
				<div class="col-xs-12 col-sm-6 col-md-4">
					<div class="form-expense">
						<label>Solicitud</label>
						<input type="text" class="form-control" value="Obsequios por Fiestas Patrias" disabled>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-4">
					<div class="form-expense">
						<label>Fondo</label>
						<input type="text" class="form-control" value="Inversión Marketing a Médicos" disabled>
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
					    	<div class="input-group-addon">S/.</div>
					      	<input id="deposit" class="form-control" type="text" value="200" disabled>
					    </div>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-4">
					<div class="form-expense">
						<label>Monto Restante</label>
						<div class="input-group">
					    	<div class="input-group-addon">S/.</div>
					      	<input id="balance" class="form-control" type="text" value="34" disabled>
					    </div>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-4">
					<div class="form-expense">
						<label>Tipo de Comprobante</label>
						<select name="" id="proof-type" class="form-control">
							@foreach($data['activity']['typeProof'] as $value)
								<option value="{{$value['cod']}}">{{$value['descripcion']}}</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-4">
					<div class="form-expense">
						<label>RUC</label>
						<input id="ruc" type="text" class="form-control" maxlength="11">
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-4">
					<div class="form-expense">
						<label>Razón Social</label>
						<button id="razon" type="button" class="form-control ladda-button" data-style="expand-left" data-spinner-color="#5c5c5c" value=0 data-edit=0 readonly>Buscando Razon Social ...
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-4">
					<div class="form-expense">
						<label class="inline-block">Número de Comprobante</label>
						<div class="input-group">
							<input id="number-proof-one" type="text" class="form-control">
							<div class="input-group-addon">-</div>
					      	<input id="number-proof-two" class="form-control" type="text">
						</div>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-4">
					<div class="form-expense">
						<label>Fecha de Movimiento</label>
						<div class="input-group date">
							<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
							<input id="date" type="text" class="form-control" maxlength="10" value="{{$data['date']['toDay']}}" readonly>
							<input id="last-date" type="hidden" value="{{$data['date']['lastDay']}}">
						</div>
					</div>
				</div>
			</section>
			<section class="row reg-expense" style="margin:0">
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
											<th>Descripción</th>
											<th class="w-type-expense">Tipo de Gasto</th>
											<th class="w-total-item">Importe</th>
											<th>Eliminar</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<th class="quantity"><input type="text" class="form-control" value=1></th>
											<th class="description"><input type="text" class="form-control" value="Pizza Mediana"></th>
											<th>
												<select class="form-control type-expense">
													<option value="">Movilidad</option>
													<option value="">Viajes</option>
													<option value="">Comida</option>
													<option value="">Viáticos</option>
													<option value="">Otros Gastos</option>
												</select>
											</th>
											<th class="total-item"><input type="text" class="form-control" value="29"></th>
											<th><a class="delete-item" href="#"><span class="glyphicon glyphicon-remove"></span></a></th>
										</tr>
										<tr>
											<th class="quantity"><input type="text" class="form-control" value=3></th>
											<th class="description"><input type="text" class="form-control" value="Vinos"></th>
											<th>
												<select class="form-control type-expense">
													<option value="">Movilidad</option>
													<option value="">Viajes</option>
													<option value="">Comida</option>
													<option value="">Viaticos</option>
													<option value="">Otros Gastos</option>
												</select>
											</th>
											<th class="total-item"><input type="text" class="form-control" value="21"></th>
											<th><a class="delete-item" href="#"><span class="glyphicon glyphicon-remove"></span></a></th>
										</tr>
										<tr>
											<th class="quantity"><input type="text" class="form-control" value=1></th>
											<th class="description"><input type="text" class="form-control" value="Agua"></th>
											<th>
												<select class="form-control type-expense">
													<option value="">Movilidad</option>
													<option value="">Viajes</option>
													<option value="">Comida</option>
													<option value="">Viaticos</option>
													<option value="">Otros Gastos</option>
												</select>
											</th>
											<th class="total-item"><input type="text" class="form-control" value="3"></th>
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
			<section class="row reg-expense tot-document" style="margin:-1em 0">
				<div class="col-xs-12 col-sm-6 col-md-4">
					<div class="form-expense">
						<label>Sub Total</label>
						<div class="input-group">
					    	<div class="input-group-addon">S/.</div>
					      	<input id="sub-tot" class="form-control" type="text" value="41.41" disabled>
					    </div>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-4" style="display:none">
					<div class="form-expense">
						<label>Impuesto por Servicio</label>
						<div class="input-group">
					    	<div class="input-group-addon">S/.</div>
					      	<input id="imp-ser" class="form-control" type="text" value="4.14" disabled>
					    </div>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-4">
					<div class="form-expense">
						<label>IGV</label>
						<div class="input-group">
					    	<div class="input-group-addon">S/.</div>
					      	<input id="igv" class="form-control" type="text" value="7.45" disabled>
					    </div>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-4">
					<div class="form-expense">
						<label>Monto Total</label>
						<div class="input-group">
					    	<div class="input-group-addon">S/.</div>
					      	<input id="total-expense" class="form-control" type="text" value="53" disabled>
					    </div>
					</div>
				</div>
			</section>
			<section class="row reg-expense" style="margin:0">
				<div class="col-xs-12 col-sm-12 col-md-12">
					<div class="form-expense btn-save-expense">
						<button id="save-expense" type="button" class="btn btn-primary">Registrar</button>
						<div class="message-expense">
							<span></span>&nbsp;&nbsp;Hola Hola
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
										<!-- <th>RUC</th> -->
										<th>Razón Social</th>
										<th>Nro. Comprobante</th>
										<th>Fecha</th>
										<th>Monto Total</th>
										<th>Editar</th>
										<th>Eliminar</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<th class="proof-type">Boleta</th>
										<!-- <th class="ruc">10429709844</th> -->
										<th class="razon">MENDOZA BARREDA WALTER ISRAEL</th>
										<th class="voucher_number">002-002335</th>
										<th class="date_movement">18/07/2014</th>
										<th class="total"><span>S/.</span>&nbsp;95</th>
										<th><a class="edit-expense" href="#"><span class="glyphicon glyphicon-pencil"></span></a></th>
										<th><a class="delete-expense" href="#"><span class="glyphicon glyphicon-remove"></span></a></a></th>
									</tr>
									<tr>
										<th class="proof-type">Factura</th>
										<!-- <th class="ruc">10238174835</th> -->
										<th class="razon">AEDO ANTEZANA RUTH IDALIA</th>
										<th class="voucher_number">001-02192</th>
										<th class="date_movement">18/07/2014</th>
										<th class="total"><span>S/.</span>&nbsp;71</th>
										<th><a class="edit-expense" href="#"><span class="glyphicon glyphicon-pencil"></span></a></th>
										<th><a class="delete-expense" href="#"><span class="glyphicon glyphicon-remove"></span></a></a></th>
									</tr>
								</tbody>
							</table>
						</div>
						<input id="tot-edit-hidden" type="hidden" value="">
					</div>
				</div>
			</section>
			<section class="row reg-expense align-center" style="margin-top:2em">
				<div class="col-xs-12 col-sm-12 col-md-12">
					<button type="button" class="btn btn-success inline" style="margin:-2em 2em .5em 0">Terminar</button>
					<button id="cancel-expense" type="button" class="btn btn-danger inline" style="margin:-2em 0em .5em 0">Cancelar</button>
				</div>
			</section>
		</div>
	</div>
</div>
@stop
