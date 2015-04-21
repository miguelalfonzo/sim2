@if (Auth::user()->type == REP_MED)
	@if($solicitud->idestado != PENDIENTE)
		<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
		    <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12 control-label" for="selectbasic">Observacion</label>
		    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		        <textarea id="textinput" maxlength="200" name="observacion" class="form-control" disabled>{{$solicitud->observacion}}</textarea>
		    </div>
		</div>
	@endif
@elseif (Auth::user()->type == SUP)
	<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
        <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12 control-label" for="textinput">Observacion</label>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            @if($solicitud->idestado == PENDIENTE)
            	<textarea id="textinput" maxlength="200" name="observacion" class="form-control sol-obs"></textarea>
            @else
            	<textarea id="textinput" maxlength="200" name="observacion" class="form-control" disabled>{{$solicitud->observacion}}</textarea>
            @endif
        </div>
    </div>
@elseif (Auth::user()->type == GER_PROD)
	<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
	    <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12 control-label" for="textinput">Observacion</label>
	    <div class="col-sm-12 col-md-12 col-lg-12">
	        @if($solicitud->idestado == DERIVADO)
	        	<textarea id="textinput" maxlength="200" name="observacion" class="form-control sol-obs"></textarea>
	        @else
	        	<textarea id="textinput" maxlength="200" name="observacion" class="form-control" disabled>{{$solicitud->observacion}}</textarea>
	        @endif
	    </div>
	</div>
@elseif ( Auth::user()->type == GER_COM)
	<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
        <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12 control-label" for="textinput">Observacion</label>
        <div class="col-sm-12 col-md-12">
            @if($solicitud->idestado == ACEPTADO)
            	<textarea id="textinput" maxlength="200" name="observacion" class="form-control sol-obs">{{ $solicitud->observacion }}</textarea>
            @else
            	<textarea id="textinput" maxlength="200" name="observacion" class="form-control" disabled>{{ $solicitud->observacion }}</textarea>
            @endif
        </div>
    </div>
@elseif ( in_array(Auth::user()->type, array(CONT,TESORERIA,ASIS_GER) ))
    <div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
        <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12 control-label" for="textinput">Observacion</label>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <textarea id="textinput" maxlength="200" class="form-control" disabled>{{ $solicitud->observacion }}</textarea>
        </div>
    </div>
@endif