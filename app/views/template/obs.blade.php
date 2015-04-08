@if (Auth::user()->type == REP_MED)
	@if($solicitude->idestado != PENDIENTE)
		<div class="form-group col-sm-6 col-md-4 col-lg-4">
		    <label class="col-sm-8 col-md-8 col-lg-8 control-label" for="selectbasic">Observacion</label>
		    <div class="col-sm-12 col-md-12 col-lg-12">
		        <textarea id="textinput" name="observacion" class="form-control" disabled>{{$solicitude->observacion}}</textarea>
		    </div>
		</div>
	@endif
@elseif (Auth::user()->type == SUP)
	<div class="form-group col-sm-6 col-md-4 col-lg-4">
        <label class="col-sm-8 col-md-8 col-lg-8 control-label" for="textinput">Observacion</label>
        <div class="col-sm-12 col-md-12 col-lg-12">
            @if($solicitude->idestado == PENDIENTE)
            	<textarea id="textinput" maxlength="200" name="observacion" class="form-control sol-obs"></textarea>
            @else
            	<textarea id="textinput" maxlength="200" name="observacion" class="form-control" disabled>{{$solicitude->observacion}}</textarea>
            @endif
        </div>
    </div>
@elseif (Auth::user()->type == GER_PROD)
	<div class="form-group col-sm-6 col-md-4 col-lg-4">
	    <label class="col-sm-8 col-md-8 col-lg-8 control-label" for="textinput">Observacion</label>
	    <div class="col-sm-12 col-md-12 col-lg-12">
	        @if($solicitude->idestado == DERIVADO)
	        	<textarea id="textinput" name="observacion" class="form-control sol-obs"></textarea>
	        @else
	        	<textarea id="textinput" name="observacion" class="form-control" disabled>{{$solicitude->observacion}}</textarea>
	        @endif
	    </div>
	</div>
@elseif ( Auth::user()->type == GER_COM)
	<div class="form-group col-sm-6 col-md-4">
        <label class="col-sm-8 col-md-8 control-label" for="textinput">Observacion</label>
        <div class="col-sm-12 col-md-12">
            @if($solicitude->idestado == ACEPTADO)
            	<textarea id="textinput" name="observacion" class="form-control sol-obs">{{ $solicitude->observacion }}</textarea>
            @else
            	<textarea id="textinput" name="observacion" class="form-control" disabled>{{ $solicitude->observacion }}</textarea>
            @endif
        </div>
    </div>
@elseif ( in_array(Auth::user()->type, array(CONT,TESORERIA,ASIS_GER) ))
    <div class="form-group col-sm-6 col-md-4">
        <label class="col-sm-8 col-md-8 control-label" for="textinput">Observacion</label>
        <div class="col-sm-12 col-md-12">
            <textarea id="textinput" class="form-control" disabled>{{ $solicitude->observacion }}</textarea>
        </div>
    </div>
@endif