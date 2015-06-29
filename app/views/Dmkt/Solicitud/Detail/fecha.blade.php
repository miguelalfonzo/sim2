<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
    <label class="col-xs-8 col-sm-8 col-md-8 col-lg-8 control-label">Fecha de Creacion / Fecha de Entrega</label>
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="input-group date">
            <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
            <input type="text" class="form-control" maxlength="10" disabled
            value="{{ date_format( date_create( $solicitud->created_at ) , 'd/m/Y' ) }}">
            <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
            <input type="text" class="form-control" maxlength="10" disabled
            value="{{$detalle->fecha_entrega}}">
        </div>
    </div>
</div>