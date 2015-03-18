<div class="panel-body table-solicituds">
    <div class="col-md-12" style="padding: 0">
        <form method="post" action="" class="">
            {{Form::token()}}
            @if (Auth::user()->type == GER_COM || Auth::user()->type == CONT)
                <input type="hidden" id="state_view" value="{{isset($state) ? $state:R_APROBADO}}">
            @elseif ( Auth::user()->type == REP_MED || Auth::user()->type == SUP || Auth::user()->type == GER_PROD )
                <input type="hidden" id="state_view" value="{{isset($state) ? $state:R_PENDIENTE}}">
            @elseif ( Auth::user()->type == TESORERIA)
                <input type="hidden" id="state_view" value="{{isset($state) ? $state:R_REVISADO}}">
            @endif
            <div class="form-group col-sm-2 col-md-2" style="padding: 0">
                <div>
                    <select id="idState" name="idstate" class="form-control selectestatesolicitude">
                        @foreach($states as $estado)
                            @if ( Auth::user()->type == REP_MED || Auth::user()->type == SUP || Auth::user()->type == GER_PROD )
                                @if(isset($state))
                                    @if($state == $estado->id)
                                        <option value="{{$estado->id}}" selected>{{$estado->nombre}}</option>
                                    @else
                                        <option value="{{$estado->id}}">{{$estado->nombre}}</option>
                                    @endif
                                @else    
                                    <option value="{{$estado->id}}">{{$estado->nombre}}</option>
                                @endif
                            @elseif ( Auth::user()->type == GER_COM || Auth::user()->type == CONT )
                                @if($estado->id == R_APROBADO || $estado->id == R_REVISADO || $estado->id == R_FINALIZADO)
                                    @if(isset($state))
                                        @if($state == $estado->id)
                                            <option value="{{$estado->id}}" selected >{{$estado->nombre}}</option>
                                        @else
                                            <option value="{{$estado->id}}">{{$estado->nombre}}</option>
                                        @endif
                                    @else    
                                        <option value="{{$estado->id}}">{{$estado->nombre}}</option>
                                    @endif
                                @endif
                            @elseif ( Auth::user()->type == TESORERIA )
                                @if( $estado->id == R_REVISADO )
                                    <option value="{{$estado->id}}">{{$estado->nombre}}</option>
                                @endif
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group col-sm-3 col-md-3" style="padding-right: 0">
                <div class="" style="padding: 0">
                    <div class="input-group ">
                        <span class="input-group-addon">
                            <i class="glyphicon glyphicon-calendar"></i>
                        </span>
                        <input id="date_start" type="text" style="background-color:#FFF" class="form-control" placeholder="Desde" readonly>
                    </div>
                </div>
            </div>
            <div class="form-group col-sm-3 col-md-3" style="padding-right: 0">
                <div class="" style="padding: 0">
                    <div class="input-group ">
                        <span class="input-group-addon">
                            <i class="glyphicon glyphicon-calendar"></i>
                        </span>
                        <input id="date_end" type="text" style="background-color:#FFF" class="form-control" placeholder="Hasta" readonly>
                    </div>
                </div>
            </div>
            <div class="form-group col-sm-1 col-md-1">
                <div class="" style="padding: 0">
                    <a id="search-solicitude" class="btn btn-sm btn-primary ladda-button" data-style="zoom-in" data-size="l"><i class="glyphicon glyphicon-search"></i></a>
                </div>
            </div>
        </form>
        @if (Auth::user()->type == REP_MED || Auth::user()->type == SUP)
            <div class="form-group col-sm-3 col-md-3 button-new-solicitude" style="text-align: right; padding: 0">
                <div class="" style="padding: 0">
                    <a href="{{URL::to('nueva-solicitud-rm')}}" id="singlebutton" name="singlebutton" class="btn btn-primary">
                        Nueva Solicitud
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>