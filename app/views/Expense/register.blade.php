@extends ('template.main')
@section ('content')
<div class="content">
	<div class="panel panel-default">
		<div class="panel-heading">
			@if (Auth::user()->type == REP_MED )
			<h3 class="panel-title"><strong>Registro de Gastos</strong></h3><strong class="user">Usuario : {{Auth::user()->Rm->nombres}}</strong>
			@elseif (Auth::user()->type == ASIS_GER )
			<h3 class="panel-title"><strong>Registro de Gastos</strong></h3><strong class="user">Usuario : {{ucwords(Auth::user()->person->nombres.' '.Auth::user()->person->apellidos)}}</strong>
			@endif
		</div>
		<div class="panel-body">
			<aside class="row reg-expense" style="margin-bottom: 0.5em;">
				<div class="col-xs-12 col-sm-6 col-md-4">
					<a id="detail_solicitude"><span id="text_solicitude">Ocultar </span>Detalle de Solicitud <span class="glyphicon glyphicon-chevron-up"></span></a>
				</div>
			</aside>
			<section id="collapseOne" class="row reg-expense collapse in" style="margin:0">
				<div class="col-xs-12 col-sm-6 col-md-4">
					<div class="form-expense">
						<label>Solicitud</label>
						<input type="text" class="form-control" value="{{mb_convert_case($solicitud->titulo, MB_CASE_TITLE, 'UTF-8')}}" disabled>
						<input type="hidden" id="token" value="{{$solicitud->token}}">
						{{Form::token()}}
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-4">
					<div class="form-expense">
						<label>Fondo</label>
						<input type="text" class="form-control" value="{{ $solicitud->detalle->fondo->nombre }}" disabled>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-4">
					<div class="form-expense">
						<label>Código de Depósito</label>
						<input type="text" class="form-control" value="{{$solicitud->iddeposito}}" disabled>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-4">
					<div class="form-expense">
						<label>Autorizado por</label>
						<input type="text" class="form-control" value="{{$name_aproved}}" disabled>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-4">
					<div class="form-expense">
						<label>Monto Depositado</label>
						<div class="input-group">
					    	<div id="type-money" class="input-group-addon">{{$solicitud->detalle->typemoney->simbolo}}</div>
					      	<input id="deposit" class="form-control" type="text" value="{{$solicitud->monto}}" disabled>
					    </div>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-4">
					<div class="form-expense">
						<label>Monto Restante</label>
						<div class="input-group">
					    	<div class="input-group-addon">{{$solicitud->detalle->typemoney->simbolo}}</div>
					      	<input id="balance" class="form-control" type="text" value="{{$balance}}" disabled>
					    </div>
					</div>
				</div>
			</section>
			@include('Dmkt.Solicitud.Section.gasto')
			<section class="row reg-expense align-center" style="margin-top:2em">
				<div class="col-xs-12 col-sm-12 col-md-12">
					<a href="#" id="finish-expense" class="btn btn-success" style="margin:-2em 2em .5em 0">Terminar</a>
					<a href="#" id="cancel-expense" class="btn btn-danger" style="margin:-2em 2em .5em 0">Cancelar</a>
				</div>
			</section>
		</div>
	</div>
</div>
@stop