@extends ('template.main')
@section ('content')
	<h3 class="title">Registro de Gastos</h3>
	<div class="row reg-expense" style="margin:0">
		<div class="col-xs-6 col-sm-6">
			<label>Nombre de Actividad</label>
			<input type="text" class="form-control" value="Refrigeradora para dueño" readonly>
			<label>Código de Depósito</label>
			<input type="text" class="form-control" value="001" readonly>
			<label>Tipo de Comprobante</label>
			<select name="" id="" class="form-control">
				<option value="">Boleta</option>
				<option value="">Factura</option>
				<option value="">Recibos por honorarios</option>
			</select>
			<label class="inline-block">Número de Comprobante</label>
			<input type="text" class="form-control inline-block">
			<label class="inline-block">Monto Gastado</label>
			<input type="text" class="form-control inline-block">
			<label class="inline-block">Monto Gastado</label>
			<input type="text" class="form-control inline-block">
		</div>
 		<div class="col-xs-6 col-sm-6">
			<label>Tipo de Actividad</label>
			<input type="text" class="form-control" value="Campaña" readonly>
			<label>Monto Depositado</label>
			<input type="text" class="form-control" value="S/50.00" readonly>
			<label>Tipo de Actividad</label>
			<input type="text" class="form-control" value="Campaña" readonly>
			<label>Monto Depositado</label>
			<input type="text" class="form-control" value="S/50.00" readonly>
			<label>Tipo de Actividad</label>
			<input type="text" class="form-control" value="Campaña" readonly>
			<label>Monto Depositado</label>
			<input type="text" class="form-control" value="S/50.00" readonly>
		</div>
	</div>
@stop
