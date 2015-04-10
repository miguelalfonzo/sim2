@if ( !is_null( $solicitude->iduserasigned ) )
    <div class="form-group col-sm-6 col-md-4 col-lg-4">
        <label class="col-sm-8 col-md-8 col-lg-8 control-label" for="textinput">Asignado a</label>
        <div class="col-sm-12 col-md-12 col-lg-12">
            <div class="input-group">
                @if($solicitude->asignedTo->type == REP_MED)
                    <span class="input-group-addon">Representante</span>
                    <input id="textinput" name="titulo" type="text" class="form-control input-md" disabled
                    value="{{$solicitude->asignedTo->rm->nombres.' '.$solicitude->asignedTo->rm->apellidos}}">
                @elseif($solicitude->asignedTo->type == SUP)
                    <span class="input-group-addon">Supervisor</span>
                    <input id="textinput" name="titulo" type="text" class="form-control input-md" disabled
                    value="{{$solicitude->asignedTo->sup->nombres.' '.$solicitude->asignedTo->sup->apellidos}}">
                @elseif($solicitude->asignedTo->type == ASIS_GER)
                    <span class="input-group-addon">Asistente Gerencia</span>
                    <input id="textinput" name="titulo" type="text" class="form-control input-md" disabled
                    value="{{$solicitude->asignedTo->person->nombres.' '.$solicitude->asignedTo->person->apellidos}}">
                @endif
            </div>
        </div>
    </div>
@endif