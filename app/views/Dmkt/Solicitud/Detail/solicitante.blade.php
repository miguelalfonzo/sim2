<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
    <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12 control-label" for="textinput">Solicitante</label>
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="input-group">
            @if( $solicitud->createdBy->type == REP_MED )
                <span class="input-group-addon">{{ $solicitud->createdBy->userType->descripcion }}</span>
                <input id="textinput" class="form-control input-md" name="titulo" type="text" disabled
                value="{{ $solicitud->createdBy->rm->full_name }}">
            @elseif( $solicitud->createdBy->type == SUP ) 
                <span class="input-group-addon">{{ $solicitud->createdBy->userType->descripcion }}</span>
                <input id="textinput" class="form-control input-md" name="titulo" type="text" disabled
                value="{{ $solicitud->createdBy->Sup->full_name }}">
            @elseif ( $solicitud->createdBy->type === GER_PROD )
                <span class="input-group-addon">{{ $solicitud->createdBy->userType->descripcion }}</span>
                <input id="textinput" class="form-control input-md" name="titulo" type="text" disabled
                value="{{ $solicitud->createdBy->gerProd->full_name }}">
            @elseif ( ! is_null( $solicitud->createdBy->type ) )
                <span class="input-group-addon">{{ $solicitud->createdBy->userType->descripcion }}</span>
                <input id="textinput" class="form-control input-md" name="titulo" type="text" disabled
                value="{{$solicitud->createdBy->person->full_name}}">         
            @elseif ( !is_null( $solicitud->created_by ) )
                <span class="input-group-addon">{{ $solicitud->createdBy->userType->descripcion }}</span>
                <input id="textinput" name="titulo" type="text" class="form-control input-md"
                value="{{$solicitud->createdBy->person->full_name}}" disabled>
            @else
                <span class="input-group-addon">Rol no definido</span>
                <input id="textinput" name="titulo" type="text" class="form-control input-md"
                value="-" disabled>
            @endif
        </div>
    </div>
</div>
