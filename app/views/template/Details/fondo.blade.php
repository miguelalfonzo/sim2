@if ( isset($fondos) || !is_null($solicitude->detalle->idfondo) )
    <div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
        <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12 control-label" for="textinput">Fondo</label>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            @if ( isset($fondos) )
                <select id="sub_type_activity" name="idfondo" class="form-control">
                    @foreach($fondos as $sub)
                        @if( $sub->idusertype == ASIS_GER )
                            <option value="{{$sub->id}}" style="background:#F7FE2E">
                                    {{$sub->nombre}} {{$sub->typeMoney->simbolo.' '.$sub->saldo}}
                            </option>
                        @else
                            @if( $sub->idusertype == SUP )
                                <option value="{{$sub->id}}" style="background:#A9F5F2" selected>
                                    {{$sub->nombre}} {{$sub->typeMoney->simbolo.' '.$sub->saldo}}
                                </option>
                            @elseif ( $sub->id == $solicitude->detalle->idfondo )
                                <option value="{{$sub->id}}" style="background:#A9F5F2" selected>
                                    {{$sub->nombre}} {{$sub->typeMoney->simbolo.' '.$sub->saldo}}
                                </option>
                            @else
                                <option value="{{$sub->id}}">
                                    {{$sub->nombre}} {{$sub->typeMoney->simbolo.' '.$sub->saldo}}
                                </option>                          
                            @endif
                        @endif
                    @endforeach
                </select>
            @else
                <select id="sub_type_activity" name="idfondo" class="form-control" disabled>            
                    <option value="{{$solicitude->detalle->idfondo}}" selected>
                        {{$solicitude->detalle->fondo->nombre}}
                    </option>
                </select>
            @endif    
        </div>
    </div>
@endif