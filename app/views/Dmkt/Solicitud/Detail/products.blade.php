<div class=" col-xs-12 col-sm-12 col-md-6 col-lg-6">
    <div class="panel panel-default">
        <div class="panel-heading">
            Productos / Montos Asignados
            @if( ! in_array( $solicitud->id_estado , array( PENDIENTE , CANCELADO ) ) )
                <label class="pull-right">Fondo</label>
            @endif

            @if ( in_array( $tipo_usuario , array(GER_PROD ) ) )
                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addProduct">
                  agregar
                </button>
            @endif
        </div>
        <ul class="list-group">
            @foreach( $solicitud->products as $product )
                <li class="list-group-item">        
                    @if( $politicStatus )
                        <div class="input-group input-group-sm">
                            <span class="input-group-addon" style="width:15%;">{{{ is_null( $product->marca ) ? '' : $product->marca->descripcion}}}</span>
                            @if ( in_array( $tipo_usuario , array( SUP , GER_PROD ) ) )
                                @if ( $tipo_usuario === SUP )
                                    <select name="fondo_producto[]" class="selectpicker form-control" readonly>
                                @else
                                    <select name="fondo_producto[]" class="selectpicker form-control">
                                @endif
                                    @if ( is_null( $product->id_fondo_marketing ) )
                                        @if ( $tipo_usuario == SUP )
                                            @if ( count( $product->getSubFondo( $tipo_usuario , $solicitud )  ) === 0 )
                                                <option selected disabled value="0">-</option>
                                            @else
                                                @foreach( $product->getSubFondo( $tipo_usuario , $solicitud ) as $fondoMkt )
                                                    <option selected value="{{ $fondoMkt->id . ',' . $fondoMkt->tipo }}">{{ $fondoMkt->descripcion . ' S/.' . $fondoMkt->saldo_disponible }}</option>
                                                @endforeach
                                            @endif
                                        @else
                                            <option selected disabled value="0">Seleccione el Fondo</option>
                                            @foreach( $product->getSubFondo( $tipo_usuario , $solicitud ) as $fondoMkt )
                                                @if ( $fondoMkt->marca_id == $product->id_producto )
                                                    <option value="{{ $fondoMkt->id . ',' . $fondoMkt->tipo }}" style="background-color:#00FFFF">{{ $fondoMkt->descripcion . ' S/.' . $fondoMkt->saldo_disponible }}</option>
                                                @else   
                                                    <option value="{{ $fondoMkt->id . ',' . $fondoMkt->tipo }}">{{ $fondoMkt->descripcion . ' S/.' . $fondoMkt->saldo_disponible }}</option>
                                                @endif
                                            @endforeach
                                        @endif
                                    @else
                                        <option value="{{ $product->id_fondo_marketing . ',' . $product->id_tipo_fondo_marketing }}" style="background-color:gold" selected>
                                            {{ $product->thisSubFondo->subCategoria->descripcion . ' | ' . 
                                               $product->thisSubFondo->marca->descripcion . ' S/.' . 
                                               $product->thisSubFondo->saldo_disponible . ' ( Reservado ' . $product->monto_asignado . ' ) ' }}
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
                                    {{ $product->thisSubFondo->subCategoria->descripcion . ' | ' . $product->thisSubFondo->marca->descripcion . ' S/.' . $product->thisSubFondo->saldo_disponible . ' ( Reservado ' . $product->monto_asignado . ' )' }}
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
    </div>
</div>
@include('Dmkt.Solicitud.Section.modal-select-producto')
