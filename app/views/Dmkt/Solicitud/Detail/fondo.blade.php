@if ( isset( $fondos ) || ! is_null( $solicitud->detalle->id_fondo) )
    <div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
        <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12 control-label" for="textinput">Fondo</label>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            @if ( isset( $fondos ) )
                <select id="sub_type_activity" name="idfondo" class="form-control">
                    <option value=''>Seleccione el Fondo</option>
                    @foreach($fondos as $sub)
                        @if ( isset( $solicitud ) && ! is_null( $solicitud->detalle->id_fondo ) && ( $solicitud->detalle->id_fondo == $sub->id ) )
                            <option selected value="{{$sub->id}}" style="background-color:gold">
                                {{$sub->nombre}} {{$sub->typeMoney->simbolo.' '.$sub->saldo}}
                            </option>                          
                        @else
                            <option value="{{$sub->id}}">
                                {{$sub->nombre}} {{$sub->typeMoney->simbolo.' '.$sub->saldo}}
                            </option>
                        @endif                      
                    @endforeach
                </select>
            @else
                <input id="sub_type_activity" name="idfondo" class="form-control" value="{{$solicitud->detalle->fondo->nombre}}" disabled>
            @endif    
        </div>
    </div>
@endif