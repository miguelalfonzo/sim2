@extends ('template.main')
@section ('content')
	<div class="content">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">
					<strong>Solicitud Insitucional</strong>
					<strong class="user" style="margin-top:0">Usuario : {{strtoupper(Auth::user()->username)}}</strong>
				</h3>
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
			@if ($fondo->estado == DEPOSITADO)
				<div class="col-xs-12 col-sm-6 col-md-4">
					<div class="form-expense">
						<label>Código de Depósito</label>
						<input type="text" class="form-control" value="{{$fondo->deposit->num_transferencia}}" disabled>	
					</div>
				</div>
			@endif
			<div class="col-xs-12 col-sm-6 col-md-4">
				<div class="form-expense">
					<label>Nº de Cuenta</label>
					<input type="text" class="form-control" value="{{$fondo->cuenta}}" disabled>
				</div>
			</div>
			<div class="col-xs-12 col-sm-6 col-md-4">
				<div class="form-expense">
					<label>Monto Solicitado</label>
					<div class="input-group">
				    	<div id="type-money" class="input-group-addon">S/.</div>
				      	<input id="deposit" class="form-control" type="text" value="{{$fondo->total}}" disabled>
				    </div>
				</div>
			</div>
			<div class="col-xs-12 col-sm-6 col-md-4">
				<div class="form-expense">
					<label>SISOL - Hospital</label>
				    <input id="institucion" class="form-control" type="text" value="{{$fondo->institucion}}" disabled>
				</div>
			</div>
			<div class="col-xs-12 col-sm-6 col-md-4">
				<div class="form-expense">
					<label>Periodo</label>
					<div class="input-group">
						<span class="input-group-addon">
	                        <i class="glyphicon glyphicon-calendar"></i>
	                    </span>
						<input id="periodo" class="form-control date_month" type="text" value="{{substr($fondo->periodo,4,6).'-'.substr($fondo->periodo,0,4)}}" disabled>
					</div>
				</div>
			</div>
			@if ($fondo->estado == DEPOSITADO)
				<div class="col-xs-12 col-sm-6 col-md-4">
					<div class="form-expense">
						<label>Monto Restante</label>
						<div class="input-group">
					    	<div class="input-group-addon">S/.</div>
					      	<input id="balance" class="form-control" type="text" value="0" disabled>
					    </div>
					</div>
				</div>
			@endif
        	<div class="col-xs-12 col-sm-6 col-md-4"></div>
			</section>
			@if($fondo->estado == DEPOSITADO)
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
													<th class="total"><span class="type_moeny">S/.&nbsp;<span class="total_expense">{{(real)$val->monto}}</span></th>
												</tr>
											@endforeach
										@endif
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</section>
			@endif
			<section class="row reg-expense align-center" style="margin-top:2em">
				<div class="col-xs-12 col-sm-12 col-md-12">
					<a href="{{URL::to('show_user')}}" class="btn btn-danger" style="margin:-2em 2em .5em 0">Cancelar</a><!-- id="cancel-expense"  -->
				</div>
			</section>
		</div>
	</div>
</div>
@stop