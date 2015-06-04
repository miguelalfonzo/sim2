<form id="form-register-solicitude" class="" method="post" enctype="multipart/form-data" action="registrar-solicitud">
    {{ Form::token() }}

    @if( isset( $solicitud ) )
        <input value="{{$solicitud->id}}" name="idsolicitud" type="hidden">
    @endif
    
    <!-- MOTIVO DE LA SOLICITUD -->
    @include('Dmkt.Register.Detail.reason')

    <!-- BUSQUEDA DE CLIENTES -->
    <div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
        <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12 control-label">Clientes</label>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <input class="form-control input-md cliente-seeker" type="text" style="display:inline">
        </div>
    </div>

    <!-- TIPO DE INVERSION DE LA SOLICITUD -->
    @include('Dmkt.Register.Detail.investment')

    <!-- TIPO DE ACTIVIDAD DE LA SOLICITUD-->
    @include('Dmkt.Register.Detail.activity')

    <!-- NOMBRE DE LA SOLICITUD -->
    @include('Dmkt.Register.Detail.title')
    
    <!-- TIPO DE MONEDA -->
    @include('Dmkt.Register.Detail.currency')

    <!-- MONTO SOLICITADO -->
    @include('Dmkt.Register.Detail.amount')
    
    <!-- TIPO DE PAGO -->
    @include('Dmkt.Register.Detail.payment')

    <div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
        <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12 control-label" for="ruc">Ruc</label>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <input class="form-control input-md" maxlength="11" name="ruc" type="text"
            value="{{isset($detalle->num_ruc) ? $detalle->num_ruc : null }}">
        </div>
    </div>

    <div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
        <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12 control-label" for="fecha">Fecha de Entrega</label>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="input-group date">
                <span class="input-group-addon">
                    <i class="glyphicon glyphicon-calendar"></i>
                </span>
                <input type="text" name="fecha" class="form-control" maxlength="10" readonly
                value="{{ isset( $solicitud ) ? $detalle->fecha_entrega : null }}">
            </div>
        </div>
    </div>
    
    <div style="clear:both">
    <!-- PRODUCTOS -->
    @include('Dmkt.Register.Detail.productos')

    <!-- LISTA DE CLIENTES -->
    <div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
        <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12 control-label" for="ruc">Lista de Clientes</label>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <ul class="list-group" id="clientes">
                @if ( isset( $solicitud ) )
                    @foreach ( $solicitud->clients as $client )
                        <li class="list-group-item" tipo_cliente="{{$client->id_tipo_cliente}}" pk="{{$client->id_cliente}}">
                            <b>{{ $client->{$client->clientType->relacion}->full_name }}</b>
                            <button type='button' class='btn-delete-client' style="z-index:2">
                                <span class="glyphicon glyphicon-remove red" style="margin-left:20px ; float:right;"></span>
                            </button>
                            <span class="badge">{{$client->clientType->descripcion}}</span>
                        </li>
                    @endforeach
                @endif
            </ul>
        </div>
    </div>
    </div>
    
   
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-top: 10px">
        <div class="form-group">
            <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12 control-label" for="descripcion">Descripcion de la Solicitud</label>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <textarea class="form-control" name="descripcion">{{ isset( $solicitud->descripcion ) ? $solicitud->descripcion : null }}</textarea>
            </div>
        </div>
    </div>

    <!-- Button (Double) -->
    <div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-top: 20px">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="text-align: center">
            @if ( isset( $solicitud ) )
                @if( $solicitud->blocked == 0 )
                    <button id="registrar" class="btn btn-primary">Actualizar</button>
                @endif
            @else
                <button id="registrar" class="btn btn-primary">Crear</button>
            @endif
            <a href="{{ URL::to('show_user') }}" class="btn btn-primary">Regresar</a>    
        </div>
    </div>
</form>