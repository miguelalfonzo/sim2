@extends('template.main')
@section('solicitude')
	<div class="content">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><strong>Generar Asiento Fondo</strong></h3><strong class="user">Usuario : {{Auth::user()->username}}</strong>
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
											<th class="name_account">Fondo</th>
											<th class="number_account">1413360</th>
											<th class="date_ini">{{$date['toDay']}}</th>
											<th class="dc">D</th>
											<th class="total">{{$solicitude->monto}}</th>
											<th class="leyenda">
												@if($solicitude->aproved->type == 'S')
													{{$solicitude->aproved->sup->nombres.' '.$solicitude->aproved->sup->apellidos.' '.$solicitude->titulo}}
												@else
													{{$solicitude->aproved->gerprod->descripcion.' '.$solicitude->titulo}}
												@endif
											</th>
											<th><a class="edit-seat-solicitude" href="#"><span class="glyphicon glyphicon-pencil"></span></a></th>
											<th><a class="delete-seat-solicitude" href="#"><span class="glyphicon glyphicon-remove"></span></a></th>
										</tr>
										<tr>
											<th class="name_account">Bancos</th>
											<th class="number_account">1041100</th>
											<th class="date_ini">{{$date['toDay']}}</th>
											<th class="dc">C</th>
											<th class="total">{{$solicitude->monto}}</th>
											<th class="leyenda">
												@if($solicitude->aproved->type == 'S')
													{{$solicitude->aproved->sup->nombres.' '.$solicitude->aproved->sup->apellidos.' '.$solicitude->titulo}}
												@else
													{{$solicitude->aproved->gerprod->descripcion.' '.$solicitude->titulo}}
												@endif
											</th>
											<th><a class="edit-seat-solicitude" href="#"><span class="glyphicon glyphicon-pencil"></span></a></th>
											<th><a class="delete-seat-solicitude" href="#"><span class="glyphicon glyphicon-remove"></span></a></th>
										</tr>
										@if(isset($solicitude->retencion))
											<tr>
												<th class="name_account">{{mb_convert_case($solicitude->typeRetention->descripcion,MB_CASE_TITLE,'UTF-8')}}</th>
												<th class="number_account">{{$solicitude->typeRetention->cta_contable}}</th>
												<th class="date_ini">{{$date['toDay']}}</th>
												<th class="dc">C</th>
												<th class="total">{{$solicitude->retencion}}</th>
												<th class="leyenda">
													@if($solicitude->aproved->type == 'S')
														{{$solicitude->aproved->sup->nombres.' '.$solicitude->aproved->sup->apellidos.' '.$solicitude->titulo}}
													@else
														{{$solicitude->aproved->gerprod->descripcion.' '.$solicitude->titulo}}
													@endif
												</th>
												<th><a class="edit-seat-solicitude" href="#"><span class="glyphicon glyphicon-pencil"></span></a></th>
												<th><a class="delete-seat-solicitude" href="#"><span class="glyphicon glyphicon-remove"></span></a></th>
											</tr>
										@endif
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</section>
				<section class="row reg-expense align-center" style="margin:1.5em 0">
					<div class="col-xs-12 col-sm-12 col-md-12">
						<a href="#" id="seat-solicitude" class="btn btn-success" style="margin:-2em 2em .5em 0">Generar Asiento Solicitud</a>
						<a href="{{URL::to('revisar-asiento-solicitud')}}/{{$solicitude->token}}" class="btn btn-danger" style="margin:-2em 2em .5em 0">Atras</a>
					</div>
				</section>
			</div>
		</div>
	</div>
@stop