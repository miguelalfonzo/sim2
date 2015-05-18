<div class="panel-body table-solicituds">
    <div class="col-md-12" style="padding: 0">
        {{Form::token()}}
        <div class="form-group col-sm-2 col-md-2" style="padding: 0">
            <div>
                <select id="idState" name="idstate" class="form-control selectestatesolicitude">
                    @foreach( $states as $estado )
                        @if ( in_array( Auth::user()->type , array( GER_COM , ASIS_GER ) ) )
                            @if( in_array( $estado->id , array( R_APROBADO , R_REVISADO , R_GASTO , R_FINALIZADO , R_NO_AUTORIZADO ) ) )
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
                        @elseif ( Auth::user()->type == TESORERIA )
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
                        @elseif ( ! Auth::user()->simApp->count() !== 0 )
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
        
        @include('Dmkt.User.date_picker')
        
        @if (Auth::user()->type == GER_COM )
            <div class="form-group col-sm-3 col-md-3 button-new-solicitude" style="text-align: left">
                <div>
                    <a id="btn-mass-approve" class="btn btn-primary">
                        Aprobación
                    </a>
                </div>
            </div>
            <div class="form-group col-sm-1 col-md-1" style="text-align: right;">
                <div style="padding: 0">
                    <a id="search-solicitude" class="btn btn-sm btn-primary ladda-button" data-style="zoom-in" data-size="l">
                        <i class="glyphicon glyphicon-search"></i>
                    </a>
                </div>
            </div>
        @else
            <div class="form-group col-sm-1 col-md-1">
                <div style="padding: 0">
                    <a id="search-solicitude" class="btn btn-sm btn-primary ladda-button" data-style="zoom-in" data-size="l">
                        <i class="glyphicon glyphicon-search"></i>
                    </a>
                </div>
            </div>
        @endif
        @if ( in_array( Auth::user()->type , array( REP_MED , SUP , GER_PROD ) ) )
            <div class="form-group col-sm-3 col-md-3 button-new-solicitude" style="text-align: right; padding: 0">
                <div style="padding: 0">
                    <a href="{{URL::to('nueva-solicitud')}}" id="singlebutton" name="singlebutton" class="btn btn-primary">
                        Nueva Solicitud
                    </a>
                </div>
            </div>
        @elseif (Auth::user()->type == TESORERIA)
            <div class="form-group col-sm-3 col-md-3" style="text-align: right; padding: 0">
                <div>
                    <span class="label" style="background-color:red">
                        Tasa de Cambio {{$tc->fecha}}: Compra({{$tc->compra}}) - Venta({{$tc->venta}})
                    </span>
                </div>
            </div>
        @endif
    </div>
</div>