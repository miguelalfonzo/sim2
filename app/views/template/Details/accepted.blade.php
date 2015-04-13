@if( isset( $solicitude->acceptHist->id ) )    
    <div class="form-group col-sm-6 col-md-4 col-lg-4">
        <label class="col-sm-8 col-md-8 control-label">
            Aceptado Por
        </label>
        <div class="col-sm-12 col-md-12 col-lg-12">
            <div class="input-group">
                @if ( $solicitude->acceptHist->user->type == SUP )
                    <span class="input-group-addon">Supervisor</span>
                    <input type="text" class="form-control input-md"  readonly
                    value="{{$solicitude->acceptHist->user->sup->nombres.' '.$solicitude->acceptHist->user->sup->apellidos}}">
                @elseif( $solicitude->acceptHist->user->type == GER_PROD )
                    <span class="input-group-addon">G. Producto</span>
                    <input type="text" class="form-control input-md"  readonly
                    value="{{$solicitude->acceptHist->user->gerProd->descripcion}}">
                @endif
            </div>
        </div>
    </div>
@endif