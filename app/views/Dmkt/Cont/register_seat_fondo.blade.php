@extends('template.main')
@section('solicitude')
	<div class="content">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><strong>Generar Asiento - Fondo Institucional</strong></h3><strong class="user">Usuario : {{Auth::user()->username}}</strong>
			</div>
			<div class="panel-body">
				<section class="row reg-expense" style="margin:0">
					<div class="col-xs-12 col-sm-6 col-md-4">
						<div class="form-expense">
							<label>Código del Fondo</label>
							<input id="idfondo" type="text" class="form-control" value="{{$fondo->idfondo}}" disabled>
						</div>
					</div>
					<div class="col-xs-12 col-sm-6 col-md-4">
						<div class="form-expense">
							<label>Mes Depositado</label>
							<div class="input-group">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                <input type="text" value="{{$mes}}" class="form-control" maxlength="10" disabled>
                            </div>
						</div>
					</div>
					<div class="col-xs-12 col-sm-6 col-md-4">
						<div class="form-expense">
							<label>Sisol - Hospital</label>
							<input type="text" class="form-control" value="{{$fondo->institucion}}" disabled>
						</div>
					</div>
					<div class="col-xs-12 col-sm-6 col-md-4">
						<div class="form-expense">
							<label>Represenante Médico</label>
							<input type="text" class="form-control" value="{{mb_convert_case($fondo->repmed, MB_CASE_TITLE, 'UTF-8')}}" disabled>
						</div>
					</div>
					<div class="col-xs-12 col-sm-6 col-md-4">
						<div class="form-expense">
							<label>Monto Depositado</label>
							<input type="text" class="form-control" value="{{$fondo->total}}" disabled>
						</div>
					</div>
					<div class="col-xs-12 col-sm-6 col-md-4">
						<div class="form-expense">
							<label>Supervisor</label>
							<input type="text" class="form-control" value="{{$fondo->supervisor}}" disabled>
						</div>
					</div>
				</section>
				<hr>
				<section class="row reg-expense" style="margin:0">
					<div class="col-xs-12 col-sm-6 col-md-4">
						<div class="form-expense">
							<label for="name_account">Cuenta</label>
							<input id="name_account" type="text" class="form-control">
							{{Form::token()}}
						</div>
					</div>
					<div class="col-xs-12 col-sm-6 col-md-4">
						<div class="form-expense">
							<label for="number_account">N° de Cuenta</label>
							<input id="number_account" type="text" class="form-control">
						</div>
					</div>
					<div class="col-xs-12 col-sm-6 col-md-4">
						<div class="form-expense">
							<label for="dc">Tipo de Cuenta</label>
							<select class="form-control" id="dc">
								<option value="D">DEBE</option>
								<option value="C">HABER</option>
							</select>
						</div>
					</div>
					<div class="col-xs-12 col-sm-6 col-md-4">
						<div class="form-expense">
							<label for="total">Importe</label>
							<input id="total" type="text" class="form-control">
						</div>
					</div>
					<div class="col-xs-12 col-sm-6 col-md-4">
						<div class="form-expense">
							<a href="#" id="add-seat-solicitude" class="btn btn-info" style="margin-top:1.75em;">Agregar Detalle</a>
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
											@foreach($cuenta as $account)
												<th class="name_account">{{$account->nombre_cont}}</th>
												<th class="number_account">{{$account->cuenta_mkt}}</th>
												<th class="date_ini">{{$getDay['toDay']}}</th>
												<th class="dc">D</th>
												<th class="total">{{$fondo->total}}</th>
												<th class="leyenda">{{$fondo->institucion}}</th>
												<th><a class="edit-seat-solicitude" href="#"><span class="glyphicon glyphicon-pencil"></span></a></th>
												<th><a class="delete-seat-solicitude" href="#"><span class="glyphicon glyphicon-remove"></span></a></th>
											@endforeach
										</tr>
										</tr>
											@foreach($banco as $bank)
												<th class="name_account">{{$bank->alias}}</th>
												<th class="number_account">{{$bank->num_cuenta}}</th>
												<th class="date_ini">{{$getDay['toDay']}}</th>
												<th class="dc">C</th>
												<th class="total">{{$fondo->total}}</th>
												<th class="leyenda">{{$fondo->institucion}}</th>
												<th><a class="edit-seat-solicitude" href="#"><span class="glyphicon glyphicon-pencil"></span></a></th>
												<th><a class="delete-seat-solicitude" href="#"><span class="glyphicon glyphicon-remove"></span></a></th>
											@endforeach
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</section>
				<section class="row reg-expense align-center" style="margin:1.5em 0">
					<div class="col-xs-12 col-sm-12 col-md-12">
						<a id="seat-solicitude" data-url="fondo" class="btn btn-success" style="margin:-2em 2em .5em 0">Generar Asiento Solicitud</a>
						<a class="btn btn-danger" style="margin:-2em 2em .5em 0" onclick="window.history.back()">Cancelar</a>
					</div>
				</section>
			</div>
		</div>
	</div>
@stop