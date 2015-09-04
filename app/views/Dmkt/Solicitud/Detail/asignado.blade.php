@if ( ! is_null( $solicitud->id_user_assign ) )
    <div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
        <label class="control-label"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> Asignado a</label>
        <div class="input-group">
            <span class="input-group-addon">{{$solicitud->asignedTo->type}}</span>
            <input type="text" class="form-control input-md" readonly value="{{ $solicitud->asignedTo->personal->getFullName() }}">
        </div>
    </div>
@endif