@if ( ! is_null( $solicitud->id_user_assign ) )
    <div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
        <label class="control-label"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> Asignado a</label>
        <div class="input-group">
            <span class="input-group-addon">{{$solicitud->asignedTo->type}}</span>    
            @if( $solicitud->asignedTo->type == REP_MED )
                <input type="text" class="form-control input-md" disabled
                value="{{ $solicitud->asignedTo->rm->full_name }}">
            @elseif( $solicitud->asignedTo->type == SUP )
                <input type="text" class="form-control input-md" disabled
                value="{{ $solicitud->asignedTo->sup->full_name }}">
            @elseif( $solicitud->asignedTo->type == GER_COM )
                <input type="text" class="form-control input-md" disabled
                value="{{ $solicitud->asignedTo->gerProd->full_name}}">
            @elseif( ! is_null( $solicitud->asignedTo->simApp() ) )
                <input type="text" class="form-control input-md" disabled
                value="{{ $solicitud->asignedTo->person->full_name}}">    
            @else
                <input type="text" class="form-control input-md" disabled
                value="No Identificado">
            @endif
        </div>
    </div>
@endif