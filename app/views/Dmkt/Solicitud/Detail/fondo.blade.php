@if ( isset( $fondos ) || ! is_null( $solicitud->detalle->id_fondo) )
    <div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
        <label class="control-label" for="textinput">Fondo</label>
        <div>
            @if ( isset( $fondos ) )
                <select id="sub_type_activity" name="idfondo" class="form-control">
                    <option value='0' disabled selected>Seleccione el Fondo</option>
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