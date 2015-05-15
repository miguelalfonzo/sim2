<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
    <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12 control-label">Motivo</label>
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <select class="form-control" name="motivo">
            @foreach( $reasons as $reason )
                @if ( $reason->id != 2 )
                    @if( isset( $solicitud ) && $solicitud->detalle->idmotivo == $reason->id)
                        <option selected value="{{$reason->id}}">{{$reason->nombre}}</option>
                    @else
                        <option value="{{$reason->id}}">{{$reason->nombre}}</option>
                    @endif
                @endif
            @endforeach
        </select>
    </div>
</div>