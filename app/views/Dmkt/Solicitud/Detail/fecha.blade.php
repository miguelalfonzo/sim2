<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
    <label class="control-label">Fecha de Creacion / Fecha de Entrega de Dinero</label>
    <div class="input-group date col-xs-12 col-sm-6 col-md-6 col-lg-6 pull-left">
        <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
        <input type="text" class="form-control" maxlength="10" disabled value="{{ date_format( date_create( $solicitud->created_at ) , 'd/m/Y' ) }}">
    </div>
    <div class="input-group date col-xs-12 col-sm-6 col-md-6 col-lg-6 pull-left">
        <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
        <input type="text" class="form-control" maxlength="10" disabled value="{{$detalle->fecha_entrega}}">
    </div>
</div>