@extends ('template.main')
@section ('content')
	<h3 class="title">Registro de Gastos</h3>
	<div class="row reg-expense" style="margin:0">
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
				<input type="text" class="form-control" value="S/50.00" readonly>
			</div>
		</div>
		<div class="col-xs-12 col-sm-6 col-md-4">
			<div class="form-expense">
				<label>Tipo de Comprobante</label>
				<select name="" id="" class="form-control">
					<option value="">Boleta</option>
					<option value="">Factura</option>
					<option value="">Recibos por honorarios</option>
				</select>
			</div>
		</div>
		<div class="col-xs-12 col-sm-6 col-md-4">
			<div class="form-expense">
				<label>RUC</label>
				<input id="ruc" type="text" class="form-control">
			</div>
		</div>
		
		<div class="col-xs-12 col-sm-6 col-md-4">
			<div class="form-expense">
				<label class="inline-block">Número de Comprobante</label>
				<input type="text" class="form-control inline-block">
			</div>
		</div>
		<div class="col-xs-12 col-sm-6 col-md-4">
			<div class="form-expense">
				<label>Razón Social</label>
				<input type="text" class="form-control" value="Sinergy E.I.R.L" readonly>
			</div>
		</div>
		<div class="col-xs-12 col-sm-6 col-md-4">
			<div class="form-expense">
				<label class="inline-block">Monto Gastado</label>
				<input type="text" class="form-control inline-block">
			</div>
		</div>
		<div class="col-xs-12 col-sm-6 col-md-4">
			<div class="form-expense">
				<label>Fecha de Entrega</label>
				<input type="date" class="form-control">
			</div>
		</div>
		<div class="col-xs-12 col-sm-6 col-md-4">
			<div class="form-expense">
				<label class="inline-block">Monto Restante</label>
				<input type="text" class="form-control" value="S/30.00" readonly>
			</div>
		</div>
		<div class="col-xs-12 col-sm-6 col-md-4">
			<div class="form-expense btn-save-expense">
				<button type="button" class="btn btn-primary">Registrar</button>
			</div>
		</div>
	</div>
@stop
