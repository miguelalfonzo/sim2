@if ( ( in_array( Auth::user()->type , array ( REP_MED , ASIS_GER ) ) && $solicitud->idestado == GASTO_HABILITADO ) || ( Auth::user()->type == CONT && $solicitud->idestado == REGISTRADO ) )
	<section class="row reg-expense">
		<input type="hidden" name="idgasto">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<hr>
		</div>
		<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
			<label class="col-xs-12 col-sm-12 col-md-12 col-lg-12 control-label">Tipo de Comprobante</label>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<select id="proof-type" class="form-control">
					@foreach($typeProof as $val)
						<option value="{{$val->id}}">{{$val->descripcion}}</option>
					@endforeach
				</select>
			</div>
		</div>
		<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
			<label class="col-xs-12 col-sm-12 col-md-12 col-lg-12 control-label">RUC</label>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="input-group">
					<input id="ruc" type="text" class="form-control" maxlength="11">
					<div class="input-group-addon search-ruc" data-sol="1">
						<span class="glyphicon glyphicon-search" style="font-size:1.0em"></span>
					</div>
					<input id="ruc-hide" type="hidden">
				</div>
			</div>
		</div>

		<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
			<label class="col-xs-12 col-sm-12 col-md-12 col-lg-12 control-label">Razón Social</label>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<button id="razon" type="button" class="form-control ladda-button" data-style="expand-left" data-spinner-color="#5c5c5c" value=0 data-edit=0 readonly>
			</div>
		</div>

		<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
			<label class="col-xs-12 col-sm-12 col-md-12 col-lg-12 control-label">Número de Comprobante</label>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="input-group">
					<input id="number-prefix" type="text" class="form-control">
					<div class="input-group-addon">-</div>
			      	<input id="number-serie" class="form-control" type="text">
				</div>
			</div>
		</div>

		<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
			<label class="col-xs-12 col-sm-12 col-md-12 col-lg-12">Descripción del Gasto</label>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<input id="desc-expense" type="text" class="form-control">
			</div>
		</div>

		<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
			<label class="col-xs-12 col-sm-12 col-md-12 col-lg-12 control-label">Fecha de Movimiento</label>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="input-group date">
					<span class="input-group-addon">
						<i class="glyphicon glyphicon-calendar"></i>
					</span>
					<input id="date" type="text" class="form-control" maxlength="10" value="{{$date['toDay']}}" readonly>
					<input id="last-date" type="hidden" value="{{$date['lastDay']}}">
				</div>
			</div>
		</div>

		<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
			<label class="col-xs-12 col-sm-12 col-md-12 col-lg-12 control-label">Balance</label>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 control-label">	
				<div class="input-group">
			    	<div class="input-group-addon">{{$solicitud->detalle->typemoney->simbolo}}</div>
			    	@if ( isset( $balance) )
			    		<input id="balance" class="form-control" type="text" value="{{$balance}}" disabled>
			    	@else
			      		<input id="balance" class="form-control" type="text" value="{{$detalle->monto_aprobado}}" disabled>
			    	@endif
			    </div>
			</div>
		</div>
	</section>

	<section class="row reg-expense detail-expense" style="margin:0">
		<div style="padding:0 15px">
			<div class="panel panel-info">
				<div class="panel-heading">
					<span class="text-left">Detalle del Comprobante</span>
				</div>
				<div class="panel-body">
					<div class="table-responsive">
						<table id="table-items" class="table table-bordered">
							<thead>
								<tr>
									<th class="w-quantity">Cantidad</th>
									<th class="w-desc-item">Descripción</th>
									<th class="w-type-expense">Tipo de Gasto</th>
									<th class="w-total-item">Valor de Venta</th>
									<th>Eliminar</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<th class="quantity"><input type="text" class="form-control" maxlength="4"></th>
									<th class="description"><input type="text" class="form-control"></th>
									<th>
										<select class="form-control type-expense">
											@foreach($typeExpense as $val)
												<option value="{{$val->id}}">{{$val->descripcion}}</option>
											@endforeach
										</select>
									</th>
									<th class="total-item">
										<div class="input-group">
									    	<div class="input-group-addon">{{$solicitud->detalle->typemoney->simbolo}}</div>
									      	<input class="form-control" type="text" maxlength="8">
									    </div>
									</th>
									<th>
										<a class="delete-item" href="#">
											<span class="glyphicon glyphicon-remove"></span>
										</a>
									</th>
								</tr>
							</tbody>
						</table>
						@if ( Auth::user()->type == CONT )
							<aside class="col-xs-12 col-sm-6 col-md-4" style="padding:0;">
								<button id="add-item" type="button" class="btn btn-dafault">Agregar Item</button>
							</aside>
						@endif
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
			    	<div class="input-group-addon">{{$solicitud->detalle->typemoney->simbolo}}</div>
			      	<input id="sub-tot" class="form-control" type="text" value=0>
			    </div>
			</div>
		</div>
		<div class="col-xs-12 col-sm-6 col-md-4 tot-document">
			<div class="form-expense">
				<label>Impuesto por Servicio</label>
				<div class="input-group">
			    	<div class="input-group-addon">{{$solicitud->detalle->typemoney->simbolo}}</div>
			      	<input id="imp-ser" class="form-control" type="text" value=0>
			    </div>
			</div>
		</div>
		<div class="col-xs-12 col-sm-6 col-md-4 tot-document">
			<div class="form-expense">
				<label>IGV</label>
				<div class="input-group">
			    	<div class="input-group-addon">{{$solicitud->detalle->typemoney->simbolo}}</div>
			      	<input id="igv" class="form-control" type="text" value=0>
			    </div>
			</div>
		</div>
		<div class="col-xs-12 col-sm-6 col-md-4">
			<div class="form-expense">
				<label>Monto Total</label>
				<div class="input-group">
			    	<div class="input-group-addon">{{$solicitud->detalle->typemoney->simbolo}}</div>
			      	<input id="total-expense" class="form-control" type="text" disabled>
			    </div>
			</div>
		</div>

		@if ( Auth::user()->type == CONT )
			<div id="dreparo" class="col-xs-12 col-sm-6 col-md-4">
				<div class="form-expense">
					<label>Reparo</label>
					<div class="input-group">
						<div class="btn-group" role="group" aria-label="...">
							<label class="btn btn-default">
						 		<input value="1" type="radio" name="reparo" style="margin-top:.5em;">Si
							</label>
							<label class="btn btn-default">
								<input value="0" type="radio" name="reparo" style="margin-top:.5em;" checked>No
							</label> 
						</div>
				    </div>
				</div>
			</div>
		@endif

	</section>
	<section class="row reg-expense detail-expense" style="margin:0">
		<div class="col-xs-12 col-sm-12 col-md-12">
			<div class="form-expense">
				<button id="save-expense" type="button" class="btn btn-primary">Registrar</button>
				<div class="inline"><p class="inline message-expense"></p></div>
			</div>
		</div>
	</section>
@endif
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
							@if ( $solicitud->idestado == GASTO_HABILITADO || Auth::user()->type == CONT )
								<th>Editar</th>
								<th>Eliminar</th>
							@endif
						</tr>
					</thead>
					<tbody>
						@if(isset($expense))
							@foreach($expense as $val)
								<tr data-id="{{$val->id}}">
									<th class="proof-type">{{mb_convert_case($val->idProofType->descripcion,MB_CASE_TITLE,'UTF-8')}}</th>
									<th class="ruc">{{$val->ruc}}</th>
									<th class="razon">{{$val->razon}}</th>
									<th class="voucher_number">{{$val->num_prefijo.'-'.$val->num_serie}}</th>
									<th class="date_movement">{{date('d/m/Y',strtotime($val->fecha_movimiento))}}</th>
									<th class="total">
										<span class="type_money">{{$solicitud->detalle->typemoney->simbolo}}</span>
										<span class="total_expense">{{(real)$val->monto}}</span>
									</th>
									@if ( $solicitud->idestado == GASTO_HABILITADO || Auth::user()->type == CONT )
										<th>
											<a href="#" class="edit-expense">
												<span class="glyphicon glyphicon-pencil"></span>
											</a>
										</th>
										<th>
											<a href="#" class="delete-expense">
												<span class="glyphicon glyphicon-remove"></span>
											</a>
										</th>
									@endif
								</tr>	
							@endforeach
						@endif
					</tbody>
				</table>
			</div>
			<input id="tot-edit-hidden" type="hidden">
		</div>
	</div>
</section>