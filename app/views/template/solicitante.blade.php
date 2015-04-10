<div class="form-group col-sm-6 col-md-4 col-lg-4">
    <label class="col-sm-8 col-md-8 col-lg-8 control-label" for="textinput">Solicitante</label>
    <div class="col-sm-12 col-md-12 col-lg-12">
        <div class="input-group">
            @if( $solicitude->createdBy->type == REP_MED )
                <span class="input-group-addon">Representante</span>
                <input id="textinput" class="form-control input-md" name="titulo" type="text" disabled
                value="{{$solicitude->createdBy->rm->nombres.' '.$solicitude->createdBy->rm->apellidos}}">
            @elseif( $solicitude->createdBy->type == SUP ) 
                <span class="input-group-addon">Supervisor</span>
                <input id="textinput" class="form-control input-md" name="titulo" type="text" disabled
                value="{{$solicitude->createdBy->Sup->nombres.' '.$solicitude->createdBy->Sup->apellidos}}">
            @elseif ( !is_null( $solicitude->created_by ) )
                <span class="input-group-addon">Rol No Definido</span>
                <input id="textinput" name="titulo" type="text" class="form-control input-md"
                value="{{$solicitude->createdBy->email}}" disabled>      
            @endif
        </div>
    </div>
</div>
