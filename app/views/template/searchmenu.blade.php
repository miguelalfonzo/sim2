<div class="panel-body table-solicituds">
    <div class="col-md-12" style="padding: 0">
        <form method="post" action="" class="">
            {{Form::token()}}
            @if (Auth::user()->type != GER_COM)
                <input type="hidden" id="state_view" value="{{isset($state) ? $state:R_PENDIENTE}}">
            @else
                <input type="hidden" id="state_view" value="{{isset($state) ? $state:R_APROBADO}}">
            @endif
            <div class="form-group col-sm-3 col-md-2" style="padding: 0">
                <div class="">
                    <select id="idState" name="idstate" class="form-control selectestatesolicitude">
                        @foreach($states as $estado)
                            @if (Auth::user()->type != GER_COM)
                                @if(isset($state))
                                    @if($state == $estado->id)
                                        <option value="{{$estado->id}}" selected>{{$estado->nombre}}</option>
                                    @else
                                        <option value="{{$estado->id}}">{{$estado->nombre}}</option>
                                    @endif
                                @else    
                                    <option value="{{$estado->id}}">{{$estado->nombre}}</option>
                                @endif
                            @else
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
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group col-sm-4 col-md-3" style="padding-right: 0">
                <div class="" style="padding: 0">
                    <div class="input-group ">
                        <span class="input-group-addon">Desde</span>
                        <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                        <input id="date_start" type="text" name="date_start" value="{{isset($solicitude)? date_format(date_create($solicitude->fecha_entrega), 'd/m/Y' ) : null }}" class="form-control" maxlength="10" readonly>
                    </div>
                </div>
            </div>
            <div class="form-group col-sm-4 col-md-3" style="padding-right: 0">
                <div class="" style="padding: 0">
                    <div class="input-group ">
                        <span class="input-group-addon">Hasta</span>
                        <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                        <input id="date_end" type="text" name="date_end" value="{{isset($solicitude)? date_format(date_create($solicitude->fecha_entrega), 'd/m/Y' ) : null }}" class="form-control" maxlength="10" readonly>
                    </div>
                </div>
            </div>
            <div class="form-group col-sm-1 col-md-2">
                <div class="" style="padding: 0">
                    <a id="search-solicitude" class="btn btn-sm btn-primary ladda-button" data-style="zoom-in" data-size="l"><i class="glyphicon glyphicon-search"></i></a>
                </div>
            </div>
        </form>
        @if (Auth::user()->type == REP_MED || Auth::user()->type == SUP)
            <div class="form-group col-sm-6 col-md-2 button-new-solicitude" style="text-align: right; padding: 0">
                <div class="" style="padding: 0">
                    <a href="{{URL::to('nueva-solicitud-rm')}}" id="singlebutton" name="singlebutton" class="btn btn-primary">
                        Nueva Solicitud
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>