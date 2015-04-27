@if(!is_null($solicitud->detalle->iddeposito) )    
    <div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
        <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12 control-label" for="depositado">
            Depositado
        </label>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="input-group">
                <span class="input-group-addon">{{$solicitud->detalle->deposit->account->typeMoney->simbolo}}</span>
                <input id="depositado" type="text" value="{{$solicitud->detalle->deposit->total}}" class="form-control input-md" readonly>
            </div>
        </div>
    </div>
@endif