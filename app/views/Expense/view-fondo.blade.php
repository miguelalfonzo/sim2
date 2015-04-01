@extends ('template.main')
@section ('content')
	<div class="content">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">
					<strong>Ver Solicitud Institucional</strong>
					<strong class="user" style="margin-top:0">Usuario: {{strtoupper(Auth::user()->username)}}</strong>
				</h3>
			</div>
			<div class="panel-body">
				<section class="row reg-expense" style="margin:0">
					@include('fondo_body')
				</section>
				<section class="row reg-expense align-center" style="margin-top:20px">
					<div class="col-xs-12 col-sm-12 col-md-12">
						@if (Auth::user()->type == TESORERIA && $fondo->estado == DEPOSITO_HABILITADO)
							<button class="btn btn-success" data-toggle="modal" data-target="#myModal">Registrar Depósito</button>
						@endif
						<a href="{{URL::to('show_user')}}" class="btn btn-danger">Regresar</a>
					</div>
				</section>
				@if( Auth::user()->type == TESORERIA && $fondo->estado == DEPOSITO_HABILITADO )
	            	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	                    <div class="modal-dialog">
	                        <div class="modal-content">
	                            <div class="modal-header">
	                                <button type="button" class="close" data-dismiss="modal">
	                                	<span aria-hidden="true">&times;</span>
	                                	<span class="sr-only">Close</span>
	                                </button>
	                                <h4 class="modal-title" id="myModalLabel">Registro del Depósito</h4>
	                            </div>
	                            <div class="modal-body">
	                                <label for="op-number">Número de Operación, Transacción, Cheque:</label>
	                                <input id="op-number" type="text" class="form-control">
	                                <p id="message-op-number" style="margin-top:1em;color:#a94442;"></p>
	                            </div>
	                            <div class="modal-footer">
	                                <a id="" href="#" class="btn btn-success register-deposit" data-deposit="F" style="margin-right: 1em;">Confirmar Operación</a>
	                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
	                            </div>
	                        </div>
	                    </div>
	                </div>
	            @endif 
			</div>
		</div>
	</div>
@stop