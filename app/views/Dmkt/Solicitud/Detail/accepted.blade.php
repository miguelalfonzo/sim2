@if( ! is_null( $solicitud->approvedHistory ) )    
    <div class="form-group col-sm-6 col-md-4 col-lg-4">
        <label class="col-sm-8 col-md-8 control-label">
            Aprobado Por
        </label>
        <div class="col-sm-12 col-md-12 col-lg-12">
            <div class="input-group">
                <span class="input-group-addon">
                    {{ $solicitud->approvedHistory->user->userType->descripcion }}
                </span>  
                @if ( $solicitud->approvedHistory->user->type == SUP )
                    <input type="text" class="form-control input-md"  readonly
                    value="{{$solicitud->approvedHistory->user->sup->full_name}}">
                @elseif( $solicitud->approvedHistory->user->type == GER_PROD )
                    <input type="text" class="form-control input-md"  readonly
                    value="{{$solicitud->approvedHistory->user->gerProd->full_name}}">
                @elseif( $solicitud->approvedHistory->user->type == REP_MED )
                    <input type="text" class="form-control input-md"  readonly
                    value="{{$solicitud->approvedHistory->user->rm->full_name}}">
                @elseif( ! is_null( $solicitud->approvedHistory->user->simApp ) )
                    <input type="text" class="form-control input-md"  readonly
                    value="{{$solicitud->approvedHistory->user->person->full_name}}">                
                @else
                    <input type="text" class="form-control input-md"
                    value="No Registrado" readonly>
                @endif
            </div>
        </div>
    </div>
@endif