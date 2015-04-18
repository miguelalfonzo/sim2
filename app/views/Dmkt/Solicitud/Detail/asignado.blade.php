@if ( !is_null( $solicitud->iduserasigned ) )
    <div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
        <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12 control-label" for="asignado">Asignado a</label>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="input-group">
                @if($solicitud->asignedTo->type == REP_MED)
                    <span class="input-group-addon">Representante</span>
                    <input id="asignado" name="titulo" type="text" class="form-control input-md" disabled
                    value="{{$solicitud->asignedTo->rm->nombres.' '.$solicitud->asignedTo->rm->apellidos}}">
                @elseif($solicitud->asignedTo->type == SUP)
                    <span class="input-group-addon">Supervisor</span>
                    <input id="asignado" name="titulo" type="text" class="form-control input-md" disabled
                    value="{{$solicitud->asignedTo->sup->nombres.' '.$solicitud->asignedTo->sup->apellidos}}">
                @elseif($solicitud->asignedTo->type == ASIS_GER)
                    <span class="input-group-addon">Asistente Gerencia</span>
                    <input id="asignado" name="titulo" type="text" class="form-control input-md" disabled
                    value="{{$solicitud->asignedTo->person->nombres.' '.$solicitud->asignedTo->person->apellidos}}">
                @endif
            </div>
        </div>
    </div>
@endif