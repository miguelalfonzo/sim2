@extends('template.main')
@section('solicitude')

<style type="text/css">
.table-responsive
{
    overflow-x: auto;
}
</style>
<div class="content">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title"><strong>Generar Asiento de Solicitud</strong></h3><strong class="user">Usuario : {{Auth::user()->username}}</strong>
		</div>
		<input value="{{csrf_token()}}" name="_token" id="_token" type="hidden">
		<div class="panel-body">
			<section class="row reg-expense" style="margin:0">
				<div class="col-xs-12 col-sm-6 col-md-4">
					<div class="form-expense">
						<label>Solicitud</label>
						<input id="idsolicitud" type="text" class="form-control" rel="{{$solicitud->id}}" value="{{$solicitud->id}} - {{mb_convert_case($solicitud->titulo,MB_CASE_TITLE,'UTF-8')}}" disabled>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-4">
					<div class="form-expense">
						<label>Fondo</label>
						<input type="text" class="form-control" value="{{mb_convert_case($solicitud->detalle->fondo->nombre, MB_CASE_TITLE, 'UTF-8')}}" disabled>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-4">
					<div class="form-expense">
						<label>Monto de la Solicitud</label>
						<div class="input-group">
					    	<div id="type-money" class="input-group-addon">{{$solicitud->detalle->typeMoney->simbolo}}</div>
					      	<input id="deposit" class="form-control" type="text" value="{{ json_decode( $solicitud->detalle->detalle )->monto_aprobado}}" disabled>
					    </div>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-4">
					<div class="form-expense">
						<label>Nombre del Solicitante</label>
						<div class="input-group">
	                        @if($solicitud->createdBy->type == 'R')
	                        <span class="input-group-addon">R</span>
	                        <input id="textinput" name="titulo" type="text" placeholder=""
	                               value="{{mb_convert_case($solicitud->createdBy->rm->nombres.' '.$solicitud->createdBy->rm->apellidos,MB_CASE_TITLE,'UTF-8')}}" disabled
	                               class="form-control">
	                        @elseif ( $solicitud->createdBy->type == SUP )
	                        <span class="input-group-addon">S</span>
	                        <input id="textinput" name="titulo" type="text" placeholder=""
	                               value="{{$solicitud->createdBy->sup->full_name}}" disabled
	                               class="form-control">
	                        @else
	                        	<span class="input-group-addon">AG</span>
		                        <input id="textinput" name="titulo" type="text" placeholder=""
	                            value="{{$solicitud->createdBy->person->full_name}}" disabled
	                            class="form-control">
	                        @endif
	                    </div>							
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-4">
					<div class="form-expense">
						<label>Autorizado por</label>
						<div class="input-group">
							@if ( $solicitud->createdBy->type == ASIS_GER )
		                        <span class="input-group-addon">AG</span>
		                        <input id="textinput" name="titulo" type="text" placeholder=""
		                        value="{{$solicitud->createdBy->person->full_name}}" disabled class="form-control">   
	                        @elseif($solicitud->approvedHistory->user->type == SUP )
		                        <span class="input-group-addon">S</span>
		                        <input id="textinput" name="titulo" type="text" placeholder=""
	                            value="{{ $solicitud->approvedHistory->updatedBy->sup->full_name}}" disabled
	                            class="form-control">
	                        @elseif ( $solicitud->approvedHistory->user->type == GER_PROD )
		                        <span class="input-group-addon">G</span>
		                        <input id="textinput" name="titulo" type="text" placeholder=""
		                        value="{{ $solicitud->approvedHistory->updatedBy->gerprod->full_name }}" disabled class="form-control">
	                        @elseif ( !is_null( $solicitud->approvedHistory->user->simApp ) )
		                        <span class="input-group-addon">G</span>
		                        <input id="textinput" name="titulo" type="text" placeholder=""
		                        value="{{ $solicitud->approvedHistory->updatedBy->person->full_name }}" disabled class="form-control">
	                        
	                        @endif
	                    </div>							
					</div>
				</div>
			</section>
			<hr>
			<div>
				<ul id="myTab" class="nav nav-tabs nav-justified">
				  <li role="presentation" class="active"><a href="#tab_seats">Asientos</a></li>
				  <li role="presentation"><a href="#tab_documents">Documentos</a></li>
				</ul>
			</div>
			

			<div id="myTabContent" class="tab-content">
					<div role="tabpanel" class="tab-pane fade active in" id="tab_seats" aria-labelledby="tab_seats-tab">
					<section class="row seats" style="margin:0">
						<div class="col-xs-12 col-sm-12 col-md-12">
							<div class="form-expense">
								<div class="table-responsive">
									<table class="table table-bordered table-hover table-condensed tb_style">
										<thead>
											<tr>
												<th>N° Cuenta</th>
												<th>CC</th>
												<th>N° Origen</th>
												<th>Fec. Origen</th>
												<th>IVA</th>
												<th>Cod.  Prov.</th>
												<th>Nombre Del proveedor</th>
												<th>Cod.</th>
												<th>RUC</th>
												<th>Prefijo</th>
												<th>Cbte. Proveedor</th>
												<th>D/C</th>
												<th>Importe</th>
												<th>Leyenda Fj</th>
												<th>Leyenda Variable</th>
												<th>Tipo  Resp.</th>
												<th>Opciones</th>
											</tr>
										</thead>
										<tbody>
											@if ( isset( $seats ) )
											@foreach($seats as $seatItem)
											<tr class="{{ $seatItem->type == 'IGV' ? 'info' : ($seatItem->type == 'SER' ? 'warning' : ($seatItem->type == 'REP' ? 'danger' : ($seatItem->type == 'CAN' ? 'success' : ''))) }}">
												<td class="cuenta editable" data-cuenta_mkt="{{ $seatItem->cuentaMkt }}">{{ $seatItem->numero_cuenta }}</td>
												<td class="codigo_sunat">{{ $seatItem->codigo_sunat }}</td>
												<td class="numero_origen"></td>
												<td class="fecha_origen">{{ $seatItem->fec_origen }}</td>
												<td class="iva">{{ $seatItem->iva }}</td>
												<td class="cod_prov">{{ $seatItem->cod_prov }}</td>
												<td class="nombre_proveedor">{{ $seatItem->nombre_proveedor }}</td>
												<td class="cod">{{ $seatItem->cod }}</td>
												<td class="ruc">{{ $seatItem->ruc }}</td>
												<td class="prefijo">{{ $seatItem->prefijo }}</td>
												<td class="cbte_proveedor">{{ $seatItem->cbte_proveedor }}</td>
												<td class="dc">{{ $seatItem->dc }}</td>
												<td class="importe">
													{{ $seatItem->importe }}
												</td>
												<td class="leyenda">{{ $seatItem->leyenda }}</td>
												<td class="leyenda_variable">{{ $seatItem->leyenda_variable }}</td>
												<td class="tipo_responsable">{{ $seatItem->tipo_responsable }}</td>

												<td><a class="edit-seat" href="#" {{ $seatItem->type != '' ? 'style="display:none;"' : ''  }}><span class="glyphicon glyphicon-pencil"></span></a></td>
											</tr>
											@endforeach
											@endif
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</section>
				</div>
				<div role="tabpanel" class="tab-pane fade" id="tab_documents" aria-labelledby="tab_documents-tab">
					<section class="row expense_items" style="margin:0">
						<div class="col-xs-12 col-sm-12 col-md-12">
							<div class="form-expense">
								<div class="table-responsive">
									<table class="table table-bordered table-condensed tb_style">
										<thead>
											<tr>
											    <th>Comprobante</th>
											    <th>N° Documento</th>
											    <th>RUC</th>
											    <th>Razon Social</th>
											    <th>Descripcion</th>
											    <th>Fecha</th>
											    <th>Sub Total</th>
											    <th>IGV</th>
											    <th>Imp Serv</th>
											    <th>Total</th>
											    <th>Cantidad</th>
											    <th>Descripcion</th>
											    <th>Importe</th>
											</tr>
										</thead>
										<tbody>
										@if ( isset($expenseItem) )
										@foreach($expenseItem as $expenseValue)
											<tr data-id="{{ $expenseValue->id }}">
											    @foreach($typeProof as $val)
											    	 @if($expenseValue->idcomprobante == $val->id)
											    <td rowspan="{{ $expenseValue->count }}" data-id="{{ $val->idcomprobante }}">{{ $val->descripcion }}</td>
											    	@endif
												@endforeach
											    <td rowspan="{{ $expenseValue->count }}">{{ $expenseValue->num_prefijo }}-{{ $expenseValue->num_serie }}</td>
											    <td rowspan="{{ $expenseValue->count }}">{{ $expenseValue->ruc }}</td>
											    <td rowspan="{{ $expenseValue->count }}">{{ $expenseValue->razon }}</td>
											    <td rowspan="{{ $expenseValue->count }}">{{ $expenseValue->descripcion }}</td>
											    <td rowspan="{{ $expenseValue->count }}">{{ date("Y/m/d",strtotime($expenseValue->fecha_movimiento)) }}</td>
											    <td rowspan="{{ $expenseValue->count }}">{{ $expenseValue->sub_tot }}</td>
											    <td rowspan="{{ $expenseValue->count }}">{{ $expenseValue->igv }}</td>
											    <td rowspan="{{ $expenseValue->count }}">{{ $expenseValue->imp_serv }}</td>
											    <td rowspan="{{ $expenseValue->count }}">{{ $expenseValue->monto }}</td>
											    @foreach($expenseValue->itemList as $expenseItem)
											    	<td>{{ $expenseItem->cantidad }}</td>
											    	<td>{{ $expenseItem->descripcion }}</td>
											    	<td>{{ $expenseItem->importe }}</td>
											    	</tr><tr>
											    @endforeach
											</tr>
										@endforeach
										@endif
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</section>
				</div>
			</div>
	
			<section class="row reg-expense align-center" style="margin:1.5em 0">
				<div class="col-xs-12 col-sm-12 col-md-12">
					<a id="saveSeatExpense" class="btn btn-success" style="margin:-2em 2em .5em 0">Generar Asiento Gasto</a>
					<a id="cancel-seat-cont" href="#" class="btn btn-danger" style="margin:-2em 2em .5em 0">Atras</a>
				</div>
			</section>
		</div>
	</div>
</div>

<script>
	@if ( isset($seats) )
		$(document).ready(function(){
			GBDMKT = typeof(GBDMKT) === 'undefined' ? {} : GBDMKT;
			GBDMKT.seatsList = {{ json_encode($seats) }};
		@if(isset($error))
			@foreach($error as $errorItem)
				bootbox.alert("{{ $errorItem['error'] }}: {{ $errorItem['msg'] }}<br> ");
			@endforeach
		@endif
			$('#myTab a').click(function (e) {
			  e.preventDefault()
			  $(this).tab('show')
			})
		});
	@endif
</script>
@stop