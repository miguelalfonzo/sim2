<div class="panel-body">
    <div class="col-md-12" style="padding: 0">
        {{Form::token()}}
        <div class="form-group col-sm-2 col-md-2" style="padding: 0">
            <div>
                <select id="idState" name="idstate" class="form-control selectestatesolicitude">
                    @foreach( $states as $estado )
                        @if ( Auth::user()->type == TESORERIA )
                            @if( $estado->id == R_REVISADO )
                                <option value="{{$estado->id}}" selected>{{$estado->nombre}}</option>
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
        @if ( Auth::user()->type == SUP )
            <div class="form-group col-xs-6 col-sm-2 col-md-1 col-lg-1" style="text-align: left; padding: 0">
                <div>
                    <a class="btn btn-primary" data-toggle="modal" data-target="#modal-temporal-user">
                        Derivacion de Usuario
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
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 table_solicitudes" style="overflow:scrollable">
    </div>
</div>