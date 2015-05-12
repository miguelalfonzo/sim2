<form id="form-register-solicitude" class="" method="post" enctype="multipart/form-data"
action="{{isset($solicitud) ? 'editar-solicitud' : 'registrar-solicitud' }}">
    {{Form::token()}}
    @if(isset($solicitud))
        <input value="{{$solicitud->id}}" name="idsolicitud" type="hidden">
    @endif
    
    <!-- MOTIVO -->
    <div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
        <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12 control-label" for="reason">Motivo</label>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <select class="form-control" name="reason">
                @foreach( $reasons as $reason )
                    @if ( $reason->id != 2 )
                        @if( isset( $solicitud ) && $solicitud->detalle->idmotivo == $reason->id)
                            <option selected value="{{$reason->id}}">{{$reason->nombre}}</option>
                        @else
                            <option value="{{$reason->id}}">{{$reason->nombre}}</option>
                        @endif
                    @endif
                @endforeach
            </select>
        </div>
    </div>

    <!-- BUSQUEDA DE CLIENTES -->
    <div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
        <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12 control-label">Clientes</label>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <input class="form-control input-md cliente-seeker" type="text" style="display:inline">
        </div>
    </div>

    <!-- TIPO DE INVERSION -->
    <div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
        <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12 control-label" for="inversion">Tipo de Inversion</label>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <select class="form-control" name="inversion">
                @foreach( $investments as $investment )
                    @if( isset( $solicitud ) && $solicitud->idtipoinversion == $investment->id )
                        <option selected value="{{$investment->id}}">{{$investment->nombre}}</option>
                    @else
                        <option value="{{$investment->id}}">{{$investment->nombre}}</option>
                    @endif
                @endforeach
            </select>
        </div>
    </div>

    <!-- TIPO DE ACTIVIDAD -->
    @include('Dmkt.Register.Detail.actividad')

    <!-- NOMBRE DE LA SOLICITUD -->
    <div class="form-group col-sm-6 col-md-4">
        <label class="col-sm-8 col-md-8 control-label" for="titulo">Nombre Solicitud</label>
        <div class="col-sm-12 col-md-12">
            <input class="form-control input-md" name="titulo" type="text"
            value="{{isset($solicitud->titulo)? $solicitud->titulo : null }}">
        </div>
    </div>
    
    <!-- Moneda -->
    @include('Dmkt.Register.Detail.moneda')

    <!-- Monto -->
    <div class='form-group col-xs-12 col-sm-6 col-md-4 col-lg-4'>
        <label class='col-xs-12 col-sm-12 col-md-12 col-lg-12 control-label' for="monto">Monto Solicitado</label>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <input class="form-control input-md" name="monto" type="text"
            value="{{ isset( $detalle->monto_solicitado ) ? $detalle->monto_solicitado : null }}">
        </div>
    </div>
    
    <div class="solicitud_monto form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
        <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12 control-label" for="monto_factura">Monto Factura</label>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <input class="form-control input-md" name="monto_factura" type="text"
            value="{{isset($detalle->monto_factura) ? $detalle->monto_factura : null }}">
        </div>
    </div>
    
    <!-- Factura Imagen -->
    @include('Dmkt.Register.Detail.image')
    
    <!-- TIPO DE PAGO -->
    <div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
        <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12 control-label" for="pago">Tipo de Entrega</label>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <select name="pago" class="form-control">
                @foreach( $payments as $payment )
                    @if ( $payment->id != 3 )
                        @if(isset($solicitud) && $solicitud->detalle->idpago == $payment->id)
                            <option value="{{$payment->id}}" selected>{{$payment->nombre}}</option>
                        @else
                            <option value="{{$payment->id}}">{{$payment->nombre}}</option>
                        @endif
                    @endif
                @endforeach
            </select>
        </div>
    </div>

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

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        
        <!-- PRODUCTOS -->
        @include('Dmkt.Register.Detail.productos')

        <!-- LISTA DE CLIENTES -->
        <div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <label class="col-xs-12 col-sm-12 col-md-10 col-lg-10 control-label" for="ruc">Lista de Clientes</label>
            <div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">
                <ul class="list-group" id="clientes"></ul>
            </div>
        </div>
    </div>

    <!-- Ver Comprobante -->
    @include('Dmkt.Register.Detail.comprobante')
   
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