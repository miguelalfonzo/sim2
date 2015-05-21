@if( isset( $solicitud->acceptHist->id ) )    
    <div class="form-group col-sm-6 col-md-4 col-lg-4">
        <label class="col-sm-8 col-md-8 control-label">
            Aceptado Por
        </label>
        <div class="col-sm-12 col-md-12 col-lg-12">
            <div class="input-group">
                <span class="input-group-addon">
                    {{ $solicitud->asignedTo->userType->descripcion }}
                </span>  
                @if ( $solicitude->acceptHist->user->type == SUP )
                    <input type="text" class="form-control input-md"  readonly
                    value="{{$solicitude->acceptHist->user->sup->full_name}}">
                @elseif( $solicitude->acceptHist->user->type == GER_PROD )
                    <input type="text" class="form-control input-md"  readonly
                    value="{{$solicitude->acceptHist->user->gerProd->full_name}}">
                @else
                    <input type="text" class="form-control input-md"  readonly
                    value="{{$solicitude->acceptHist->user->person->full_name}}">
                @endif
            </div>
        </div>
    </div>
@endif