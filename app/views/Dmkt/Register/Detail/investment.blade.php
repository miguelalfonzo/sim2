<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
    <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12 control-label">Tipo de Inversion</label>
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <select class="form-control" name="inversion">
            @if ( isset( $solicitud ) )
                <option disabled value="" style="display:none">SELECCIONE LA INVERSIÓN</option>
            @else
                <option selected disabled value="">SELECCIONE LA INVERSIÓN</option>
            @endif
            @foreach( $investments as $investment )
                @if( isset( $solicitud ) )
                    @if ( $solicitud->id_inversion == $investment->id )
                        <option selected value="{{$investment->id}}">{{$investment->nombre}}</option>
                    @else
                        <option value="{{$investment->id}}" style="display:none">{{$investment->nombre}}</option>
                    @endif
                @else
                    <option value="{{$investment->id}}">{{$investment->nombre}}</option>
                @endif
            @endforeach
        </select>
    </div>
</div>