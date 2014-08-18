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
						<label>Nombre de Actividad</label>
						<input type="text" class="form-control" value="Refrigeradora para dueño" readonly>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-4">
					<div class="form-expense">
						<label>Tipo de Actividad</label>
						<input type="text" class="form-control" value="Campaña" readonly>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-4">
					<div class="form-expense">
						<label>Código de Depósito</label>
						<input type="text" class="form-control" value="001" readonly>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-4">
					<div class="form-expense">
						<label>Monto Depositado</label>
						<input id="deposit" type="text" class="form-control" value="S/500.00" readonly>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-4">
					<div class="form-expense">
						<label>Tipo de Comprobante</label>
						<select name="" id="type_voucher" class="form-control">
							<option value="001">Boleta</option>
							<option value="002">Factura</option>
							<option value="003">Recibos por honorarios</option>
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
						<input id="number_voucher" type="text" class="form-control inline-block">
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-4">
					<div class="form-expense">
						<label>Fecha de Movimiento</label>
						<div class="input-group date">
							<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
							<input id="date" type="text" class="form-control" maxlength="10" readonly>
						</div>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-4">
					<div class="form-expense">
						<label class="inline-block">Monto Gastado</label>
						<input id="total" type="text" class="form-control inline-block">
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-4">
					<div class="form-expense">
						<label class="inline-block">Monto Restante</label>
						<input id="balance" type="text" class="form-control" value="S/180.00" readonly>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-4">
					<div class="form-expense btn-save-expense">
						<button id="save-expense" type="button" class="btn btn-primary">Registrar</button>
					</div>
				</div>
			</section>
			<section class="row reg-expense" style="margin:0">
				<div class="col-xs-12 col-sm-12 col-md-12">
					<div class="form-expense">
						<div class="table-responsive">
							<table id="table" class="table table-bordered">
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
									<tr>
										<th class="type_voucher">Factura</th>
										<th class="ruc">10448156279</th>
										<th class="razon">HUGO FRANCISCO GAMONAL BRAVO</th>
										<th class="number_voucher">001-2132132</th>
										<th class="date_movement">01/12/2014</th>
										<th class="total">200.00</th>
										<th><a class="edit-expense" href="#"><span class="glyphicon glyphicon-pencil"></span></a></th>
										<th><a class="delete-expense" href="#"><span class="glyphicon glyphicon-remove"></span></a></a></th>
									</tr>
									<tr>
										<th class="type_voucher">Boleta</th>
										<th class="ruc">10461782863</th>
										<th class="razon">REYES CAMPOS JUAN MANUEL</th>
										<th class="number_voucher">001-54334</th>
										<th class="date_movement">01/12/2014</th>
										<th class="total">120.00</th>
										<th><a class="edit-expense" href="#"><span class="glyphicon glyphicon-pencil"></span></a></th>
										<th><a class="delete-expense" href="#"><span class="glyphicon glyphicon-remove"></span></a></a></th>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<input id="tot-edit-hidden" type="hidden" value="">
			</section>
			<section class="row reg-expense align-center" style="margin:0">
				<div class="col-xs-12 col-sm-12 col-md-12">
					<button type="button" class="btn btn-success inline" style="margin:-2em 2em .5em 0">Terminar</button>
					<button type="button" class="btn btn-danger inline" style="margin:-2em 0em .5em 0">Cancelar</button>
				</div>
			</section>
		</div>
	</div>
</div>
@stop
