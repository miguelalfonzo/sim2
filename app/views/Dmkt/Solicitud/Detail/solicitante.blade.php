<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
    <label class="control-label" for="textinput"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> Solicitante</label>
    <div class="input-group">
        @if( ! is_null( $solicitud->createdBy ) )
            <span class="input-group-addon">{{$solicitud->createdBy->type}}</span>
            @if( $solicitud->createdBy->type == REP_MED )
                <input id="textinput" class="form-control" name="titulo" type="text" disabled
                value="{{ $solicitud->createdBy->rm->full_name }}">
            @elseif( $solicitud->createdBy->type == SUP ) 
                <input id="textinput" class="form-control" name="titulo" type="text" disabled
                value="{{ $solicitud->createdBy->Sup->full_name }}">
            @elseif ( $solicitud->createdBy->type == GER_PROD )
                <input id="textinput" class="form-control" name="titulo" type="text" disabled
                value="{{ $solicitud->createdBy->gerProd->full_name }}">
            @elseif ( ! is_null( $solicitud->createdBy->simApp() ) )
                <input id="textinput" class="form-control" name="titulo" type="text" disabled
                value="{{$solicitud->createdBy->person->full_name}}">         
            @else
                <input id="textinput" name="titulo" type="text" class="form-control input-md" value="-" disabled>
            @endif
        @endif
    </div>
</div>