@extends('template.main')
@section('solicitude')
	<div class="content">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><strong>Generar Asiento de Solicitud</strong></h3>
			</div>
			<div class="panel-body">
				<section class="row reg-expense" style="margin:0">
					<div class="col-xs-12 col-sm-6 col-md-4">
						<div class="form-expense">
							<label>Código de la Solicitud</label>
							<input id="idsolicitud" type="text" class="form-control" value="{{$solicitude->idsolicitud}}" disabled>
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
							<label>Nombre del Solicitante</label>
							<input type="text" class="form-control" value="{{$solicitude->iduser}}" disabled>
						</div>
					</div>
					<div class="col-xs-12 col-sm-6 col-md-4">
						<div class="form-expense">
							<label>Fondo</label>
							<input type="text" class="form-control" value="{{mb_convert_case($solicitude->subtype->nombre, MB_CASE_TITLE, 'UTF-8')}}" disabled>
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
							<label>Autorizado por</label>
							<input type="text" class="form-control" value="Lucy Alfaro" disabled>
						</div>
					</div>
					<div class="col-xs-12 col-sm-6 col-md-4">
						<div class="form-expense">
							<label>Número de Depósito</label>
							<input type="text" class="form-control" value="101" disabled>
						</div>
					</div>
					<div class="col-xs-12 col-sm-6 col-md-4">
						<div class="form-expense">
							<label>Fecha de Depósito</label>
							<input type="text" class="form-control" value="15/09/2014" disabled>
						</div>
					</div>
			</div>
			<section class="row reg-expense align-center" style="margin:1.5em 0">
				<div class="col-xs-12 col-sm-12 col-md-12">
					<a href="#" id="seat-solicitude" class="btn btn-success" style="margin:-2em 2em .5em 0">Generar Asiento</a>
					<a href="#" id="cancel-seat-cont" class="btn btn-danger" style="margin:-2em 2em .5em 0">Cancelar</a>
				</div>
			</section>
		</div>
	</div>
@stop