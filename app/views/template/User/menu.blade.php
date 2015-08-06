<div class="row">   
    {{Form::token()}}
    <div class="form-group col-sm-2 col-md-2">
        <div>
            <select id="idState" name="idstate" class="form-control selectestatesolicitude">
                @foreach( $states as $estado )
                    @if ( Auth::user()->type == TESORERIA )
                        @if( in_array( $estado->id , array( R_REVISADO , R_GASTO ) ) )
                            <option value="{{$estado->id}}">{{$estado->nombre}}</option>
                        @endif
                    @elseif ( Auth::user()->type == CONT )
                        @if( in_array( $estado->id , array( R_APROBADO , R_REVISADO , R_GASTO , R_FINALIZADO ) ) )
                            @if(isset($state))
                                @if($state == $estado->id)
                                    <option value="{{$estado->id}}" selected>{{$estado->nombre}}</option>
                                @else
                                    <option value="{{$estado->id}}">{{$estado->nombre}}</option>
                                @endif
                            @else    
                                <option value="{{$estado->id}}">{{$estado->nombre}}</option>
                            @endif
                        @endif
                    @elseif ( ! is_null( Auth::user()->simApp ) && ! Auth::user()->simApp->count() !== 0 )
                        @if( isset( $state ) )
                            @if( $state == $estado->id )
                                <option value="{{$estado->id}}" selected>{{$estado->nombre}}</option>
                            @else
                                <option value="{{$estado->id}}">{{$estado->nombre}}</option>
                            @endif
                        @else    
                            <option value="{{$estado->id}}">{{$estado->nombre}}</option>
                        @endif
                    @endif
                @endforeach
            </select>
        </div>
    </div>
    @if (Auth::user()->type == GER_COM )
        <div class="form-group col-sm-3 col-md-3 button-new-solicitude" style="text-align: left">
            <div>
                <a id="btn-mass-approve" class="btn btn-primary">
                    Aprobación
                </a>
            </div>
        </div>
    @endif
    @if (Auth::user()->type == TESORERIA)
        <div class="form-group col-sm-3 col-md-3 pull-right" >
            <span class="label label-warning">
                TC {{$tc->fecha}}: Compra({{$tc->compra}}) - Venta({{$tc->venta}})
            </span>
        </div>
    @endif
    <div class="container-fluid table_solicitudes" id="solicitudes"></div>
</div>