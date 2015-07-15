<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4 has-warning">
    <label class="control-label">Fecha de Descuento / Monto de Descuento</label>
    <div class="input-group">
        <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
        <input type="text" class="form-control" maxlength="7" disabled value="{{$detalle->descuento}}">
        <span class="input-group-addon">{{ $detalle->typeMoney->simbolo }}</span>
        <input type="text" class="form-control" maxlength="7" disabled value="{{ ( $detalle->monto_aprobado - $solicitud->expenses->sum('monto') ) }}">
    </div>
</div>