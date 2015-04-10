@if ( isset($fondos) || !is_null($solicitude->detalle->idfondo) )
    <div class="form-group col-sm-6 col-md-4">
        <label class="col-sm-8 col-md-8 control-label" for="textinput">Fondo</label>
        <div class="col-sm-12 col-md-12">
            @if ( isset($fondos) )
                <select id="sub_type_activity" name="idfondo" class="form-control">
                    @foreach($fondos as $sub)
                        @if( $sub->idusertype == SUP )
                            <option value="{{$sub->id}}" style="background:#A9F5F2" selected>
                                {{$sub->nombre}} -> {{$sub->typeMoney->simbolo.' '.$sub->saldo}}
                            </option>
                        @elseif ( $sub->id == $solicitude->detalle->idfondo )
                            <option value="{{$sub->id}}" style="background:#A9F5F2" selected>
                                {{$sub->nombre}} -> {{$sub->typeMoney->simbolo.' '.$sub->saldo}}
                            </option>
                        @else
                            <option value="{{$sub->id}}">
                                {{$sub->nombre}} -> {{$sub->typeMoney->simbolo.' '.$sub->saldo}}
                            </option>                          
                        @endif
                    @endforeach
                </select>
            @else
                <select id="sub_type_activity" name="idfondo" class="form-control" disabled>            
                    <option value="{{$solicitude->detalle->idfondo}}" selected>
                        {{$solicitude->detalle->fondo->nombre}} -> {{$solicitude->detalle->fondo->typeMoney->simbolo.' '.$solicitude->detalle->fondo->saldo}}
                    </option>
                </select>
            @endif    
        </div>
    </div>
@endif