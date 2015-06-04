<tr class="solicitud-detail">
	<td colspan={{$colspan}}>
		<div class="panel panel-info">
			<div class="panel-heading">
		    	<h3 class="panel-title">{{$solicitud->titulo}}</h3>
		  	</div>
			<div class="panel-body">
		    	<p>Solicitud de {{$solicitud->activity->nombre}} creada por {{$solicitud->createdBy->rm->full_name}}</p>
			</div>
				<table class="table table-striped table-bordered table-hover table-condensed">
		    		<tbody>
		    			<tr>
		    				<td>
		    					<h4><span class="label label-info">Monto Solicitado</span></h4>&nbsp;
		    					<label>{{$solicitud->detalle->typeMoney->simbolo . ' ' . $solicitud->detalle->monto_actual}}</label>
		    				</td>
		    				<td>
		    					<h4><span class="label label-info">Inversion</span></h4>&nbsp;
		    					<label>{{ $solicitud->investment->nombre }}</label>
		    				</td>
		    				<td>
		    					<h4><span class="label label-info">Actividad</span></h4>&nbsp;
		    					<label>{{ $solicitud->activity->nombre }}</label>
		    				</td>
		    				<td rowspan="2">
		    					<ul class="list-group">
		    					@foreach( $solicitud->clients as $client )
		    						  <li class="list-group-item">Cras justo odio</li>
									  <li class="list-group-item">Dapibus ac facilisis in</li>
									  <li class="list-group-item">Morbi leo risus</li>
									  <li class="list-group-item">Porta ac consectetur ac</li>
									  <li class="list-group-item">Vestibulum at eros</li>
		    					@endforeach
		    					</ul>

		    				</td>
		    			</tr>
		    			<td>Asgshhhhhsg</td>
		    			<td>Asgshhhhhsg</td>
		    			<td>Asgshhhhhsg</td>	
		    		</tbody>
		    	</table>
		</div>
	</td>
</tr>