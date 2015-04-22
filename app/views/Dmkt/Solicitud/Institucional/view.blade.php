@extends ('template.main')
@section ('content')
	<div class="content">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">
					<strong>Solicitud Institucional</strong>
					<strong class="user" style="margin-top:0">Usuario: {{strtoupper(Auth::user()->username)}}</strong>
				</h3>
			</div>
			<div class="panel-body">
				@include('Dmkt.Solicitud.Section.aside')
				{{Form::token()}}
				<input type="hidden" id="idsolicitude" name="idsolicitude" value="{{$solicitud->id}}">
				<input type="hidden" name="token" value="{{$solicitud->token}}">
				<section id="collapseOne" class="row reg-expense collapse in">
					@include('Dmkt.Solicitud.Institucional.detail')
				</section>
				@if ( Auth::user()->type == CONT && $solicitud->idestado == DEPOSITADO )
                    @include('template.Seat.advance_table')
                @endif
                @include('Dmkt.Solicitud.Section.gasto')
                @include('Dmkt.Solicitud.Detail.buttons')
				<section class="row reg-expense align-center" style="margin-top:20px">
					<div class="col-xs-12 col-sm-12 col-md-12">
						@if (Auth::user()->type == TESORERIA && $solicitud->estado == DEPOSITO_HABILITADO)
							<button class="btn btn-success" data-toggle="modal" data-target="#myModal">Registrar Depósito</button>
						@endif
					</div>
				</section> 
			</div>
		</div>
	</div>
@stop