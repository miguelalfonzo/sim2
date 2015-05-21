@if ( !is_null( $solicitud->id_user_assign ) )
    <div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
        <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12 control-label">Asignado a</label>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="input-group">
                <span class="input-group-addon">
                    {{ $solicitud->asignedTo->userType->descripcion }}
                </span>    
                @if( $solicitud->asignedTo->type == REP_MED )
                    <input type="text" class="form-control input-md" disabled
                    value="{{ $solicitud->asignedTo->rm->full_name }}">
                @elseif( $solicitud->asignedTo->type == SUP )
                    <input type="text" class="form-control input-md" disabled
                    value="{{ $solicitud->asignedTo->sup->full_name }}">
                @elseif( $solicitud->asignedTo->type == GER_COM )
                    <input type="text" class="form-control input-md" disabled
                    value="{{ $solicitud->asignedTo->gerProd->full_name}}">
                @else
                    <input type="text" class="form-control input-md" disabled
                    value="{{ $solicitud->asignedTo->person->full_name}}">
                @endif
            </div>
        </div>
    </div>
@endif