<div class=" col-xs-12 col-sm-12 col-md-6 col-lg-6">
    <div class="panel panel-default">
        <div class="panel-heading">Productos</div>
        <ul class="list-group">
            @foreach( $solicitud->products as $product )
            <li class="list-group-item">        
                    @if($politicStatus)
                        <div class="input-group">
                            <span class="input-group-addon" style="width: 30%;">{{{ is_null( $product->marca ) ? '' : $product->marca->descripcion}}}</span>
                            <select name="fondo-producto[]" class="selectpicker form-control">
                                @foreach( $product->getSubFondo() as $subFondo )
                                    @if ( $subFondo->marca_id == $product->id_producto )
                                        <option selected value="{{ $subFondo->id . ',' . $subFondo->marca_id}}" style="background-color:#00FFFF">{{$subFondo->descripcion}}</option>
                                    @else   
                                        <option value="{{ $subFondo->id . ',' . $subFondo->marca_id}}">{{$subFondo->descripcion}}</option>
                                    @endif
                                @endforeach
                            </select>
                            <span class="input-group-addon">{{ $detalle->typemoney->simbolo }}</span>
                            <input name="monto_producto[]" type="text" class="form-control text-right amount_families" value="{{ isset( $product->monto_asignado ) ? $product->monto_asignado : 
                            round( $detalle->monto_actual / count( $solicitud->products ) , 2 )}}">
                        </div>
                    @else
                        {{{ is_null( $product->marca ) ? '' : $product->marca->descripcion}}}
                        : {{ $product->subCatFondo->descripcion }}
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