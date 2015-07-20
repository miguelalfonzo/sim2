<div class="row">
	<!-- TIPO DE INVERSION DE LA SOLICITUD -->
	<div class="form-group col-xs-12 col-sm-6 col-md-6 col-lg-6">
	    <label class="control-label">Tipo de Inversion</label>
	    <div>
	        <select class="form-control" name="inversion">
	            @if ( isset( $solicitud ) )
	                <option disabled value="" style="display:none">SELECCIONE LA INVERSIÓN</option>
	            @else
	                <option selected disabled value="">SELECCIONE LA INVERSIÓN</option>
	            @endif
	            @foreach( $investments as $investment )
	                @if( isset( $solicitud ) )
	                    @if ( $solicitud->id_inversion == $investment->id )
	                        <option selected value="{{$investment->id}}">{{$investment->nombre}}</option>
	                    @else
	                        <option value="{{$investment->id}}" style="display:none">{{$investment->nombre}}</option>
	                    @endif
	                @else
	                    <option value="{{$investment->id}}">{{$investment->nombre}}</option>
	                @endif
	            @endforeach
	        </select>
	    </div>
	</div>
	<!-- TIPO DE ACTIVIDAD DE LA SOLICITUD-->
	<div class="form-group col-xs-12 col-sm-6 col-md-6 col-lg-6">
	    <label class="control-label">Tipo de Actividad</label>
	    <div>
	        <select class="form-control" name="actividad">
	            @if ( isset( $solicitud) )
	                <option value="" disabled style="display:none">SELECCIONE LA ACTIVIDAD</option>
	            @else
	                <option value="" disabled selected style="display:none">SELECCIONE LA ACTIVIDAD</option>
	            @endif
	            @foreach( $activities as $activity )
	                @if ( isset( $solicitud ) )
	                    @if ( $solicitud->id_actividad == $activity->id )
	                        <option selected value="{{ $activity->id }}" >{{ $activity->nombre }}</option>
	                    @else
	                        <option value="{{ $activity->id }}" style="display:none">{{ $activity->nombre }}</option>
	                    @endif
	                @else
	                    <option value="{{ $activity->id }}">{{ $activity->nombre }}</option>
	                @endif
	            @endforeach
	        </select>
	    </div>
	</div>
</div>
