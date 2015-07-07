<div class=" col-xs-12 col-sm-12 col-md-6 col-lg-6">
    <div class="panel panel-default">
        <div class="panel-heading">Productos</div>
        <ul class="list-group">
            @foreach( $solicitud->products as $product )
            <li class="list-group-item">        
                    @if($politicStatus)
                        <div class="input-group">
                            <span class="input-group-addon" style="width: 30%;">{{{ is_null( $product->marca ) ? '' : $product->marca->descripcion}}}</span>
                            @if ( Auth::user()->type == SUP || Auth::user()->type == GER_PROD )
                                <select name="fondo-producto[]" class="selectpicker form-control">
                                    @if ( ! is_null( $product->id_fondo ) )
                                        <option selected value="{{ $product->id_fondo . ',' . $product->id_fondo_producto . ',' . $product->id_fondo_user }}" style="background-color:gold">{{ $product->subCatFondo->descripcion }}</option> 
                                        @foreach( $product->getSubFondo( $solicitud ) as $subFondo )
                                            @if ( $subFondo->marca_id == $product->id_producto )
                                                <option value="{{ $subFondo->id . ',' . $subFondo->marca_id}}" style="background-color:#00FFFF">{{$subFondo->descripcion . 'S/.' . $subFondo->saldo }}</option>
                                            @else   
                                                <option value="{{ $subFondo->id . ',' . $subFondo->marca_id}}">{{$subFondo->descripcion . ' S/.' . $subFondo->saldo }}</option>
                                            @endif
                                        @endforeach
                                    @else
                                        @foreach( $product->getSubFondo( $solicitud ) as $subFondo )
                                            @if ( $subFondo->marca_id == $product->id_producto )
                                                <option selected value="{{ $subFondo->id . ',' . $subFondo->marca_id}}" style="background-color:#00FFFF">{{$subFondo->descripcion . 'S/.' . $subFondo->saldo }}</option>
                                            @else   
                                                <option value="{{ $subFondo->id . ',' . $subFondo->marca_id}}">{{$subFondo->descripcion . ' S/.' . $subFondo->saldo }}</option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                            @else
                                <span class="input-group-addon">{{$product->subCatFondo->descripcion}}</span>
                            @endif
                            <span class="input-group-addon">{{ $detalle->typemoney->simbolo }}</span>
                            <input name="monto_producto[]" type="text" class="form-control text-right amount_families" value="{{ isset( $product->monto_asignado ) ? $product->monto_asignado : 
                            round( $detalle->monto_actual / count( $solicitud->products ) , 2 )}}">
                        </div>
                    @else
                        {{{ is_null( $product->marca ) ? '' : $product->marca->descripcion}}}
                        @if( isset( $product->subCatFondo->descripcion ) )
                            <span class="badge">{{$product->subCatFondo->descripcion}}</span>    
                        @endif
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