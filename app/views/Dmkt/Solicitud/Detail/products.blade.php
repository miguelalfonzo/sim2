<div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
    <div style="padding: 0 15px">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Productos</h3>
            </div>
            <div class="panel-body">
                @foreach($solicitud->families as $family)
                    <div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12"  style="padding: 0">
                        <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                            <input id="textinput" name="textinput" type="text"
                                   value="{{$family->marca->descripcion}}" readonly
                                   class="form-control input-md">
                        </div>
                        @if($solicitud->detalle->idaccepted == null )
                            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4" style="padding: 0">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        {{$solicitud->detalle->typemoney->simbolo}}
                                    </span>
                                    @if ( Auth::user()->type == REP_MED )
                                        <input name="amount_assigned[]" type="text" disabled
                                        class="form-control input-md amount_families"
                                        value="{{isset($family->monto_asignado)? $family->monto_asignado : round($detalle->monto_solicitado/count($solicitud->families),2)}}">
                                    @elseif ( Auth::user()->type == SUP )
                                        @if( $solicitud->idestado == PENDIENTE )
                                            <input name="amount_assigned[]" type="text"
                                                   class="form-control input-md amount_families"
                                                   value="{{isset($family->monto_asignado)? $family->monto_asignado : round($detalle->monto_solicitado/count($solicitud->families),2)}}">
                                        @else
                                            <input disabled name="amount_assigned[]" type="text"
                                                   class="form-control input-md amount_families"
                                                   value="{{isset($family->monto_asignado)? $family->monto_asignado : round($detalle->monto_solicitado/count($solicitud->families),2)}}">
                                        @endif
                                    @elseif ( Auth::user()->type == GER_PROD )
                                        @if( $solicitud->idestado == DERIVADO )
                                            <input name="amount_assigned[]" type="text"
                                                   class="form-control input-md amount_families"
                                                   value="{{isset($family->monto_asignado)? $family->monto_asignado : round($detalle->monto_solicitado/count($solicitud->families),2)}}">
                                        @else
                                            <input disabled name="amount_assigned[]" type="text"
                                                   class="form-control input-md amount_families"
                                                   value="{{isset($family->monto_asignado)? $family->monto_asignado : round($detalle->monto_solicitado/count($solicitud->families),2)}}">
                                        @endif
                                    @elseif ( Auth::user()->type == GER_COM )
                                        @if( $solicitud->idestado == ACEPTADO )
                                            <input name="amount_assigned[]" type="text"
                                                   class="form-control input-md amount_families"
                                                   value="{{isset($family->monto_asignado)? $family->monto_asignado : round($detalle->monto_solicitado/count($solicitud->families),2)}}">
                                        @else
                                            <input disabled name="amount_assigned[]" type="text"
                                                   class="form-control input-md amount_families"
                                                   value="{{isset($family->monto_asignado)? $family->monto_asignado : round($detalle->monto_solicitado/count($solicitud->families),2)}}">
                                        @endif
                                    @else
                                      <input disabled name="amount_assigned[]" type="text"
                                                   class="form-control input-md amount_families"
                                                   value="{{isset($family->monto_asignado)? $family->monto_asignado : round($detalle->monto_solicitado/count($solicitud->families),2)}}">
                                    @endif
                                </div>
                                <input type="hidden" name="idfamily[]" value="{{$family->id}}">
                            </div>
                        @endif
                    </div>
                @endforeach
                <div class="form-group col-sm-12 col-md-12 col-lg-12">
                    <span id="amount_error_families"></span>
                </div>
            </div>
        </div>
    </div>
</div>