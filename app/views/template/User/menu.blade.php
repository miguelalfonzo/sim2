<div class="row">   
    {{Form::token()}}
    <div class="form-group col-xs-8 col-sm-4 col-md-2 col-lg-2">
        <select id="idState" name="idstate" class="form-control selectestatesolicitude">
            @foreach( $states as $estado )
                @if ( Auth::user()->type == TESORERIA )
                    @if( in_array( $estado->id , array( R_REVISADO , R_GASTO ) ) )
                        @if( isset($state) && $state == $estado->id )
                            <option value="{{$estado->id}}" selected>{{$estado->nombre}}</option>
                        @else
                            <option value="{{$estado->id}}">{{$estado->nombre}}</option>
                        @endif
                    @endif
                @elseif ( Auth::user()->type == CONT )
                    @if( in_array( $estado->id , array( R_APROBADO , R_REVISADO , R_GASTO , R_FINALIZADO , R_NO_AUTORIZADO ) ) )
                        @if( isset( $state ) && $state == $estado->id )
                            <option value="{{$estado->id}}" selected>{{$estado->nombre}}</option>
                        @else
                            <option value="{{$estado->id}}">{{$estado->nombre}}</option>
                        @endif
                    @endif
                @elseif ( ! is_null( Auth::user()->simApp ) && ! Auth::user()->simApp->count() !== 0 )
                    @if( isset( $state ) && $state == $estado->id )
                        <option value="{{$estado->id}}" selected>{{$estado->nombre}}</option>
                    @else
                        <option value="{{$estado->id}}">{{$estado->nombre}}</option>
                    @endif
                @endif
            @endforeach
        </select>
    </div>
    @if ( in_array( Auth::user()->type , array( GER_COM , CONT ) ) )
        <div class="form-group col-sm-3 col-md-3" style="text-align:left">
            <div>
                <a id="btn-mass-approve" class="btn btn-primary">
                    Aprobación
                </a>
            </div>
        </div>
    @endif
    @if ( Auth::user()->type == TESORERIA )
        <div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-6 pull-left">
            <h4 style="margin:0px">
                Tasa de Cambio
                <span class="label label-info">
                    {{ $tc->fecha }}
                </span>
                Compra
                <span class="label label-info">
                    {{ $tc->compra }}
                </span>
                Venta
                <span class="label label-info">
                    {{$tc->venta}}
                </span>
            </h4>
        </div>
    @endif
    <div class="table-responsive">
        <table id="table_solicituds" class="table table-striped table-hover table-bordered" cellspacing="0" width="100%"></table>
    </div>
</div>