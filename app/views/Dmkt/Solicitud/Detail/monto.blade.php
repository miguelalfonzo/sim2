<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
    <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12 control-label" for="amount">
        Monto Solicitado / Monto Aprobado
    </label>
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="input-group">
            <span class="input-group-addon">
                {{$solicitud->detalle->typeMoney->simbolo}}
            </span>
            <input class="form-control input-md"
            value="{{$detalle->monto_solicitado}}" name="monto" type="text">
            <span class="input-group-addon" id="type-money">
                {{$solicitud->detalle->typeMoney->simbolo}}
            </span>
            <input id="amount" value="{{$detalle->monto_actual}}"
            class="form-control input-md" name="monto" type="text" readonly>
        </div>
    </div>
</div>