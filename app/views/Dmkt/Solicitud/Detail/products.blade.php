<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
    <div class="panel panel-default">
        <div class="panel-heading">
            Productos / Montos Asignados
            @if( ! in_array( $solicitud->id_estado , array( PENDIENTE , CANCELADO ) ) )
                <label class="pull-right">Fondo</label>
            @endif

            @if ( isset( $tipo_usuario ) && in_array( $tipo_usuario , array( GER_PROD, GER_PROM , GER_COM , GER_GER  ) ) )
                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#approval-product-modal">
                    Agregar
                </button>
                 <input type="checkbox" name="modificacion-productos" id="is-product-change"> cambiar Fondos
            @endif
        </div>
        <ul class="list-group" id="list-product">
            @foreach( $solicitud->products as $product )
                <li class="list-group-item">        
                    @if( $politicStatus )
                        <div class="input-group input-group-sm">
                            <span class="input-group-addon" style="width:15%;">{{{ is_null( $product->marca ) ? '' : $product->marca->descripcion}}}</span>
                            @if ( in_array( $tipo_usuario , array( SUP , GER_PROD , GER_PROM , GER_COM , GER_GER ) ) )
                                <select name="fondo_producto[]" class="selectpicker form-control">
                                    @if ( is_null( $product->id_fondo_marketing ) )
                                        <option selected disabled value="0">Seleccione el Fondo</option>
                                        @foreach( $product->getSubFondo( $tipo_usuario , $solicitud ) as $fondoMkt )
                                            <option value="{{ $fondoMkt->id . ',' . $fondoMkt->tipo }}">
                                                {{ $fondoMkt->descripcion . ' S/.' . $fondoMkt->saldo_disponible }}
                                            </option>
                                        @endforeach
                                    @else
                                        <option value="{{ $product->id_fondo_marketing . ',' . $product->id_tipo_fondo_marketing }}" style="background-color:gold" selected>
                                            {{ $product->thisSubFondo->approval_product_name . ' ( Reservado ' . $product->monto_asignado . ' ) ' }}
                                        </option>    
                                        @foreach( $product->getSubFondo( $tipo_usuario , $solicitud ) as $fondoMkt )
                                            @if ( $fondoMkt->id == $product->id_fondo_marketing )
                                                <option value="{{ $fondoMkt->id . ',' . $fondoMkt->tipo }}" style="background-color:#00FFFF">{{$fondoMkt->descripcion . ' S/.' . $fondoMkt->saldo_disponible }}</option>
                                            @else   
                                                <option value="{{ $fondoMkt->id . ',' . $fondoMkt->tipo }}">{{$fondoMkt->descripcion . ' S/.' . $fondoMkt->saldo_disponible }}</option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                            @else
                                <span class="input-group-addon" style="max-width:350px;overflow:hidden">
                                    {{ $product->thisSubFondo->approval_product_name . ' ( Reservado ' . $product->monto_asignado . ' )' }}
                                </span>
                                <input type="hidden" value="{{ $product->id_fondo_marketing . ',' . $product->id_tipo_fondo_marketing }}" name="fondo_producto[]">
                            @endif
                            <span class="input-group-addon">{{ $detalle->typemoney->simbolo }}</span>   
                            <input name="monto_producto[]" type="text" class="form-control text-right amount_families" value="{{ isset( $product->monto_asignado ) ? $product->monto_asignado : 
                            round( $detalle->monto_actual / count( $solicitud->products ) , 2 )}}" style="padding:0px;text-align:center">
                             
                        </div>
                    @else
                        {{{ $product->marca->descripcion or '-' }}}
                        <label class="label label-primary">
                            {{ $detalle->typemoney->simbolo . ( isset( $product->monto_asignado ) ? $product->monto_asignado : round( $detalle->monto_actual / count( $solicitud->products ) , 2 ) ) }}
                        </label>
                        @if( isset( $product->id_fondo_marketing ) )
                            <span class="badge">{{ $product->thisSubFondo->subCategoria->descripcion . ' | ' . $product->thisSubFondo->marca->descripcion }}</span>    
                        @endif
                    @endif
                   
                    <input type="hidden" name="producto[]" value="{{ $product->id }}">
                </li>


            @endforeach
        </ul>
        @if ( $politicStatus && isset( $tipo_usuario ) && in_array( $tipo_usuario , array( GER_PROD , GER_PROM , GER_COM , GER_GER ) ) )
        <ul class="list-group" id="list-product2" style="display: none">
            @foreach( $solicitud->products as $product )
                

                <li class="list-group-item">        
                    @if( $politicStatus )
                        <div class="input-group input-group-sm">
                            <span class="input-group-addon" style="width:15%;">{{{ is_null( $product->marca ) ? '' : $product->marca->descripcion}}}</span>
                            @if ( in_array( $tipo_usuario , array( SUP , GER_PROD , GER_PROM ) ) )
                                <select name="fondo_producto[]" class="selectpicker form-control">
                                    @if ( is_null( $product->id_fondo_marketing ) )
                                        <option selected disabled value="0">Seleccione el Fondo</option>
                                        @foreach( $product->getSubFondo( $tipo_usuario , $solicitud ) as $fondoMkt )
                                            <option value="{{ $fondoMkt->id . ',' . $fondoMkt->tipo }}">
                                                {{ $fondoMkt->descripcion . ' S/.' . $fondoMkt->saldo_disponible }}
                                            </option>
                                        @endforeach
                                    @else
                                        <option value="{{ $product->id_fondo_marketing . ',' . $product->id_tipo_fondo_marketing }}" style="background-color:gold" selected>
                                            {{ $product->thisSubFondo->approval_product_name . ' ( Reservado ' . $product->monto_asignado . ' ) ' }}
                                        </option>    
                                        @foreach( $product->getSubFondo( $tipo_usuario , $solicitud ) as $fondoMkt )
                                            @if ( $fondoMkt->id == $product->id_fondo_marketing )
                                                <option value="{{ $fondoMkt->id . ',' . $fondoMkt->tipo }}" style="background-color:#00FFFF">{{$fondoMkt->descripcion . ' S/.' . $fondoMkt->saldo_disponible }}</option>
                                            @else   
                                                <option value="{{ $fondoMkt->id . ',' . $fondoMkt->tipo }}">{{$fondoMkt->descripcion . ' S/.' . $fondoMkt->saldo_disponible }}</option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                            @else
                                <span class="input-group-addon" style="max-width:350px;overflow:hidden">
                                    {{ $product->thisSubFondo->approval_product_name . ' ( Reservado ' . $product->monto_asignado . ' )' }}
                                </span>
                                <input type="hidden" value="{{ $product->id_fondo_marketing . ',' . $product->id_tipo_fondo_marketing }}" name="fondo_producto[]">
                            @endif
                            <span class="input-group-addon">{{ $detalle->typemoney->simbolo }}</span>
                            <input name="monto_producto[]" type="text" class="form-control text-right amount_families2" value="{{ isset( $product->monto_asignado ) ? $product->monto_asignado : 
                            round( $detalle->monto_actual / count( $solicitud->products ) , 2 )}}" style="padding:0px;text-align:center">
                             <button type="button" class="btn-remove-family" style="">
                                    <span class="glyphicon glyphicon-remove"></span>
                    </button>   
                        </div>
                    @else
                        {{{ $product->marca->descripcion or '-' }}}
                        <label class="label label-primary">
                            {{ $detalle->typemoney->simbolo . ( isset( $product->monto_asignado ) ? $product->monto_asignado : round( $detalle->monto_actual / count( $solicitud->products ) , 2 ) ) }}
                        </label>
                        @if( isset( $product->id_fondo_marketing ) )
                            <span class="badge">{{ $product->thisSubFondo->subCategoria->descripcion . ' | ' . $product->thisSubFondo->marca->descripcion }}</span>    
                        @endif
                    @endif
                   
                    <input type="hidden" name="producto[]" class="producto_value" value="{{ $product->id_producto  }}">
                </li>


            @endforeach
        </ul>
        @endif
    </div>
</div>
@if ( $politicStatus && isset( $tipo_usuario ) && in_array( $tipo_usuario , array( GER_PROD , GER_PROM , GER_COM , GER_GER ) ) )
    @include('Dmkt.Solicitud.Section.modal-select-producto')
@endif