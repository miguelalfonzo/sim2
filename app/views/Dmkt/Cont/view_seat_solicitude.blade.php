@extends('template.main')
@section('solicitude')
	<div class="content">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><strong>Revisar Solicitud</strong></h3><strong class="user">Usuario : {{Auth::user()->username}}</strong>
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
							<div class="input-group">
		                        @if($solicitude->user->type == 'R')
			                        <span class="input-group-addon">R</span>
			                        <input id="textinput" name="titulo" type="text" placeholder="" value="{{mb_convert_case($solicitude->user->rm->nombres.' '.$solicitude->user->rm->apellidos,MB_CASE_TITLE,'UTF-8')}}" disabled class="form-control">
		                        @else
			                        <span class="input-group-addon">S</span>
			                        <input id="textinput" name="titulo" type="text" placeholder="" value="{{mb_convert_case($solicitude->user->sup->nombres.' '.$solicitude->user->sup->apellidos,MB_CASE_TITLE,'UTF-8')}}" disabled class="form-control">
		                        @endif
		                    </div>							
						</div>
					</div>
					<div class="col-xs-12 col-sm-6 col-md-4">
						<div class="form-expense">
							<label>Fondo</label>
							<input type="text" class="form-control" value="{{mb_convert_case($solicitude->subtype->nombre_mkt, MB_CASE_TITLE, 'UTF-8')}}" disabled>
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
							<div class="input-group">
		                        @if($solicitude->aproved->type == 'S')
		                        <span class="input-group-addon">S</span>
		                        <input id="textinput" name="titulo" type="text" placeholder=""
		                               value="{{mb_convert_case($solicitude->aproved->sup->nombres.' '.$solicitude->aproved->sup->apellidos,MB_CASE_TITLE,'UTF-8')}}" disabled
		                               class="form-control">
		                        @else
		                        <span class="input-group-addon">G</span>
		                        <input id="textinput" name="titulo" type="text" placeholder=""
		                               value="{{mb_convert_case($solicitude->aproved->gerprod->descripcion,MB_CASE_TITLE,'UTF-8')}}" disabled class="form-control">
		                        @endif
		                    </div>
						</div>
					</div>
					<div class="col-xs-12 col-sm-6 col-md-4">
						<div class="form-expense">
							<label>Número de Depósito</label>
							<input type="text" class="form-control" value="{{$solicitude->iddeposito}}" disabled>
						</div>
					</div>	
					<div class="col-xs-12 col-sm-6 col-md-4">
						<div class="form-expense">
							<label>Fecha de Depósito</label>
							<input type="text" class="form-control" value="{{date_format(date_create($solicitude->deposit->created_at), 'd/m/Y')}}" disabled>
						</div>
					</div>
			</div>
			<section class="row reg-expense align-center" style="margin:1.5em 0">
				<div class="col-xs-12 col-sm-12 col-md-12">
					<a href="{{URL::to('generar-asiento-solicitud')}}/{{$solicitude->token}}" class="btn btn-info" style="margin:-2em 2em .5em 0">Vista Previa Asiento</a>
					<a href="#" id="cancel-seat-cont" class="btn btn-danger" style="margin:-2em 2em .5em 0">Cancelar</a>
				</div>
			</section>
		</div>
	</div>
@stop