<div>   
    {{Form::token()}}
    <div class="form-group col-xs-12 col-sm-3 col-md-2 col-lg-2">
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
    @if ( in_array( Auth::user()->type , array( GER_COM , CONT , TESORERIA ) ) )
        <div class="form-group col-xs-12 col-sm-3 col-md-2 col-lg-2">
            @if( Auth::user()->type == TESORERIA )
                <a class="btn btn-primary" data-toggle="modal" data-target="#massive-deposit-modal">
                    Deposito Masivo
                </a>
            @elseif( Auth::user()->type == GER_COM )
                <a id="btn-mass-approve" class="btn btn-primary">
                    Aprobación
                </a>
            @else
                <a class="btn btn-primary" data-toggle="modal" data-target="#massive-revision-modal">
                    Revision Masiva
                </a>
            @endif
        </div>
    @endif
    @if( Auth::user()->type == TESORERIA )
        <div class="form-group col-xs-12 col-sm-6 col-md-6 col-lg-6">
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
    <div class="container-fluid">
        <table id="table_solicituds" class="table table-striped table-hover table-bordered" cellspacing="0" cellpading="0" border="0" style="width:100%"></table>
    </div>
</div>