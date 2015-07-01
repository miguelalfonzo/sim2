<section class="row" style="padding:0.3em 1em">

    <div class="page-header">
        <h2>{{$solicitud->titulo}} <span class="label label-default">{{$solicitud->activity->nombre}}</span></h2>
    </div>
    <!-- MOTIVO  -->
    <div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
        <label class="control-label">Motivo</label>
        <input type="text" class="form-control" value="{{$detalle->reason->nombre}}" readonly>
    </div>

    <!-- INVERSION -->
    <div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
        <label class="control-label">Tipo de Inversión</label>
        <input type="text" class="form-control input-md" value="{{$solicitud->investment->nombre}}" readonly>
    </div>

    <!--TITULO  -->
    <!-- <div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
        <label class="control-label" for="titulo">Titulo</label>
        <div class="input-group">
            <span class="input-group-addon">{{$solicitud->activity->nombre}}</span>
            <input id="titulo" class="form-control input-md" type="text"
            value="{{$solicitud->titulo}}" readonly>
        </div>
    </div> -->

    <!-- MONTO -->
    <div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
        <label class="control-label" for="amount">Monto Solicitado / Monto Aprobado</label>
        <div class="input-group">
            <span class="input-group-addon">{{$solicitud->detalle->typeMoney->simbolo}}</span>
            <input class="form-control input-md" value="{{$detalle->monto_solicitado}}" name="monto" type="text" readonly>
            <span class="input-group-addon" id="type-money">{{$solicitud->detalle->typeMoney->simbolo}}</span>
            <input value="{{$detalle->monto_actual}}" class="form-control input-md" name="monto" type="text" id="amount">
        </div>
    </div>

    <!-- TIPO DE PAGO -->
    <div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4" >
        <label class="control-label">Tipo de Entrega</label>
        <input class="form-control" value="{{$solicitud->detalle->typePayment->nombre}}" disabled>
    </div>

    <!-- PAGO CHEQUE : RUC -->
    @if( $solicitud->detalle->id_pago == PAGO_CHEQUE )
        <!-- Ruc -->
        <div class="form-group col-sm-6 col-md-4">
            <label class="control-label">Ruc</label>
            <input type="text" class="form-control input-md" value="{{ $detalle->num_ruc }}" maxlength="11" readonly>
        </div>
    @endif

    <!-- FECHA DE CREACION / FECHA DE ENTREGA -->
    <div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
        <label class="control-label">Fecha de Creacion / Fecha de Entrega</label>
        <div class="input-group date col-xs-12 col-sm-6 col-md-6 col-lg-6 pull-left">
            <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
            <input type="text" class="form-control" maxlength="10" disabled value="{{ date_format( date_create( $solicitud->created_at ) , 'd/m/Y' ) }}">
        </div>
        <div class="input-group date col-xs-12 col-sm-6 col-md-6 col-lg-6 pull-left">
            <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
            <input type="text" class="form-control" maxlength="10" disabled value="{{$detalle->fecha_entrega}}">
        </div>
    </div>

    <!-- Solicitante -->
    <div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
        <label class=" control-label" for="textinput"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> Solicitante</label>
        <div class="input-group">
            @if( ! is_null( $solicitud->createdBy ) )
                @if( $solicitud->createdBy->type == REP_MED )
                    <span class="input-group-addon">RM</span>
                    <input id="textinput" class="form-control" name="titulo" type="text" disabled
                    value="{{ $solicitud->createdBy->rm->full_name }}">
                @elseif( $solicitud->createdBy->type == SUP ) 
                    <!-- <span class="input-group-addon">{{ $solicitud->createdBy->userType->descripcion }}</span> -->
                    <span class="input-group-addon">SUP</span>
                    <input id="textinput" class="form-control" name="titulo" type="text" disabled
                    value="{{ $solicitud->createdBy->Sup->full_name }}">
                @elseif ( $solicitud->createdBy->type == GER_PROD )
                    <span class="input-group-addon">GP</span>
                    <input id="textinput" class="form-control" name="titulo" type="text" disabled
                    value="{{ $solicitud->createdBy->gerProd->full_name }}">
                @elseif ( ! is_null( $solicitud->createdBy->type ) )
                    <span class="input-group-addon">{{ $solicitud->createdBy->userType->descripcion }}</span>
                    <input id="textinput" class="form-control" name="titulo" type="text" disabled
                    value="{{$solicitud->createdBy->person->full_name}}">         
                @elseif ( !is_null( $solicitud->created_by ) )
                    <span class="input-group-addon">{{ $solicitud->createdBy->userType->descripcion }}</span>
                    <input id="textinput" name="titulo" type="text" class="form-control input-md"
                    value="{{$solicitud->createdBy->person->full_name}}" disabled>
                @else
                    <span class="input-group-addon"></span>
                    <input id="textinput" name="titulo" type="text" class="form-control input-md"
                    value="-" disabled>
                @endif
            @endif
        </div>
    </div>


    <!-- Asignado a -->
    @if ( !is_null( $solicitud->id_user_assign ) )
        <div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
            <label class="control-label"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> Asignado a</label>
            <div class="input-group">
                <span class="input-group-addon">
                    @if( $solicitud->asignedTo->type == REP_MED )
                    RM
                    @elseif( $solicitud->asignedTo->type == SUP )
                    SUP
                    @elseif( $solicitud->createdBy->type == GER_PROD)
                    GP
                    @elseif( $solicitud->asignedTo->type == GER_COM )
                    GCOM
                    @else
                    {{ $solicitud->asignedTo->userType->descripcion }}
                    @endif
                </span>    
                @if( $solicitud->asignedTo->type == REP_MED )
                    <input type="text" class="form-control input-md" disabled
                    value="{{ $solicitud->asignedTo->rm->full_name }}">
                @elseif( $solicitud->asignedTo->type == SUP )
                    <input type="text" class="form-control input-md" disabled
                    value="{{ $solicitud->asignedTo->sup->full_name }}">
                @elseif( $solicitud->asignedTo->type == GER_COM )
                    <input type="text" class="form-control input-md" disabled
                    value="{{ $solicitud->asignedTo->gerProd->full_name}}">
                @else
                    <input type="text" class="form-control input-md" disabled
                    value="{{ $solicitud->asignedTo->person->full_name}}">
                @endif
            </div>
        </div>
    @endif

    <!-- Aceptador Por -->
    @include('Dmkt.Solicitud.Detail.accepted')

    <!-- Fondos -->
    @include('Dmkt.Solicitud.Detail.fondo')

    <!-- Depositado -->
    @include('Dmkt.Solicitud.Detail.depositado')

    <!-- Tasa de Cambio del Dia del Deposito -->
    @include('Dmkt.Solicitud.Detail.tasa')

    <!-- Observation-->
    @include('Dmkt.Solicitud.Detail.anotation')

    <!-- Modal Deposito -->
    @include('template.Modals.deposit-min')

    <div class="clearfix"></div>
    
        <div class=" col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <div class="panel panel-default">
                <div class="panel-heading">Productos</div>
                <ul class="list-group">
                    @foreach( $solicitud->products as $product )
                    <li class="list-group-item">
                        
                        
                            @if($politicStatus)
                                <div class="input-group">
                                    <span class="input-group-addon" style="width: 70%;">{{{ is_null( $product->marca ) ? '' : $product->marca->descripcion}}}</span>
                                    <span class="input-group-addon amount_families">{{ $detalle->typemoney->simbolo }}</span>
                                    <input name="monto_producto[]" type="text" class="form-control text-right" value="{{ isset( $product->monto_asignado ) ? $product->monto_asignado : 
                                round( $detalle->monto_actual / count( $solicitud->products ) , 2 ) }}">
                                </div>
                            @else
                                {{{ is_null( $product->marca ) ? '' : $product->marca->descripcion}}}
                                <span class="badge">{{ $detalle->typemoney->simbolo }} 
                                {{ isset( $product->monto_asignado ) ? $product->monto_asignado :
                                round( $detalle->monto_actual / count( $solicitud->products ) , 2 ) }}
                                </span>
                            @endif
                        <input type="hidden" name="producto[]" value="{{ $product->id }}">
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class=" col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <div class="panel panel-default">
                <div class="panel-heading">Clientes</div>
                <ul class="list-group">
                                
                @foreach( $solicitud->clients as $client )
                    <li class="list-group-item">
                    @if ( is_null( $client->id_cliente) )
                       No hay cliente Asignado
                    @else
                        {{ $client->{$client->clientType->relacion}->full_name }}
                    @endif
                </li>
                @endforeach
            </div>
        </div>
            
        <div class="form-group col-sm-12 col-md-12 col-lg-12">
            <span id="amount_error_families"></span>
        </div>
    

    <!-- Description Solicitude -->
    
    <div class="form-group col-sm-12 col-md-12 col-lg-12">
        <label class="control-label">
            Descripcion de la Solicitud
        </label>
        <textarea class="form-control col-sm-12 col-md-12 col-lg-12" readonly>{{$solicitud->descripcion}}</textarea>
    </div>
</section>