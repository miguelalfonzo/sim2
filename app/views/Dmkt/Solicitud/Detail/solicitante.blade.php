<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
    <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12 control-label" for="textinput">Solicitante</label>
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="input-group">
            @if( $solicitud->createdBy->type == REP_MED )
                <span class="input-group-addon">Representante</span>
                <input id="textinput" class="form-control input-md" name="titulo" type="text" disabled
                value="{{$solicitud->createdBy->rm->nombres.' '.$solicitud->createdBy->rm->apellidos}}">
            @elseif( $solicitud->createdBy->type == SUP ) 
                <span class="input-group-addon">Supervisor</span>
                <input id="textinput" class="form-control input-md" name="titulo" type="text" disabled
                value="{{$solicitud->createdBy->Sup->nombres.' '.$solicitud->createdBy->Sup->apellidos}}">
            @elseif ( $solicitud->createdBy->type == ASIS_GER )
                <span class="input-group-addon">Asis. Gerencia</span>
                <input id="textinput" class="form-control input-md" name="titulo" type="text" disabled
                value="{{$solicitud->createdBy->person->nombres.' '.$solicitud->createdBy->person->apellidos}}">           
            @elseif ( !is_null( $solicitud->created_by ) )
                <span class="input-group-addon">Rol No Definido</span>
                <input id="textinput" name="titulo" type="text" class="form-control input-md"
                value="{{$solicitud->createdBy->email}}" disabled>      
            @endif
        </div>
    </div>
</div>
