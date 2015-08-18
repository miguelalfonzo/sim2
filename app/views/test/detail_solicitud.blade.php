<tr class="solicitud-detail">
	<td colspan={{$colspan}}>
		<div class="panel panel-info">
			<div class="panel-heading">
		    	<h3 class="panel-title">{{$solicitud->titulo}}</h3>
		  	</div>
			<div class="panel-body">
				@if ( $solicitud->idtiposolicitud == SOL_INST )
						<p>Solicitud Institucional creada por {{$solicitud->createdBy->person->full_name}}</p>
				@elseif ( $solicitud->idtiposolicitud == SOL_REP )
					@if ( $solicitud->createdBy->type == REP_MED )
			    		<p>Solicitud de: {{$solicitud->activity->nombre}} creada por {{$solicitud->createdBy->rm->full_name}}</p>
					@elseif ( $solicitud->createdBy->type == SUP )
			    		<p>Solicitud de {{$solicitud->activity->nombre}} creada por {{$solicitud->createdBy->sup->full_name}}</p>
					@elseif ( $solicitud->createdBy->type == GER_PROD )
			    		<p>Solicitud de {{$solicitud->activity->nombre}} creada por {{$solicitud->createdBy->gerProd->full_name}}</p>
			    	@elseif ( ! is_null( $solicitud->createdBy->simApp ) )
		    			<p>Solicitud de {{$solicitud->activity->nombre}} creada por {{$solicitud->createdBy->person->full_name}}</p>
		    		@else
	    				<p>Usuario no identificado o eliminado</p>
		    		@endif
		    	@endif	
			</div>
			<table class="table table-striped table-bordered table-hover table-condensed">
	    		<tbody>
	    			<tr>
	    				@if( $solicitud->idtiposolicitud == SOL_INST )
		    				<td>
		    					<h4><span class="label label-info">Actividad</span></h4>&nbsp;
		    					<label>{{$solicitud->activity->nombre}}</label>
		    				</td>
		    				<td>
		    					<h4><span class="label label-info">Asignado a</span></h4>&nbsp;
		    					<label>{{$solicitud->asignedTo->personal->full_name}}</label>
		    				</td>
		    				<td>
		    					<h4><span class="label label-info">Fondo</span></h4>&nbsp;
		    					<label>{{ $solicitud->detalle->fondo->nombre }}</label>
		    				</td>
		    			@elseif ( $solicitud->idtiposolicitud == SOL_REP )
	    					<td>
		    					<h4><span class="label label-info">Inversion</span></h4>&nbsp;
		    					<label>{{$solicitud->investment->nombre}}</label>
		    				</td>
		    				<td>
		    					<h4><span class="label label-info">Actividad</span></h4>&nbsp;
		    					<label>{{$solicitud->activity->nombre}}</label>
		    				</td>
		    				<td>
		    					<h4><span class="label label-info">Productos</span></h4>&nbsp;
		    					<ul class="list-group">
			    					@foreach( $solicitud->products as $product )
			    						  <li class="list-group-item">{{ $product->marca->descripcion }}</li>
									@endforeach
		    					</ul>
		    				</td>
		    				<td>
		    					<h4><span class="label label-info">Clientes</span></h4>&nbsp;
		    					<ul class="list-group">
			    					@foreach( $solicitud->clients as $client )
			    						  <li class="list-group-item">{{ $client->{$client->clientType->relacion}->full_name }}</li>
									@endforeach
		    					</ul>
		    				</td>
		    			@endif
	    			</tr>
	    		</tbody>
	    	</table>
		</div>
	</td>
</tr>