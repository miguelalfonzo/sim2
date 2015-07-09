<div class=" col-xs-12 col-sm-12 col-md-6 col-lg-6">
    <div class="panel panel-default">
        <div class="panel-heading">
            Productos / Montos Asignados
            @if( ! in_array( $solicitud->id_estado , array( PENDIENTE , CANCELADO ) ) )
                <label class="pull-right">Fondo</label>
            @endif
        </div>
        <ul class="list-group">
            @foreach( $solicitud->products as $product )
                <li class="list-group-item">        
                    @if( $politicStatus )
                        <div class="input-group">
                            <span class="input-group-addon" style="width: 30%;">{{{ is_null( $product->marca ) ? '' : $product->marca->descripcion}}}</span>
                            @if ( in_array( $tipo_usuario , array( SUP , GER_PROD ) ) )
                                <select name="fondo-producto[]" class="selectpicker form-control">
                                    @if ( ! is_null( $product->id_fondo ) )
                                        <option selected value="{{ $product->id_fondo . ',' . $product->id_fondo_producto . ',' . $product->id_fondo_user }}" style="background-color:gold">{{ $product->fondoMarca->descripcion . ' | ' . $product->subCatFondo->descripcion . ' S/.' . $product->thisSubFondo()->saldo }}</option> 
                                        @foreach( $product->getSubFondo( $solicitud ) as $subFondo )
                                            @if ( $subFondo->marca_id == $product->id_producto )
                                                <option value="{{ $subFondo->id . ',' . $subFondo->marca_id}}" style="background-color:#00FFFF">{{$subFondo->descripcion . ' S/.' . $subFondo->saldo }}</option>
                                            @else   
                                                <option value="{{ $subFondo->id . ',' . $subFondo->marca_id}}">{{$subFondo->descripcion . ' S/.' . $subFondo->saldo }}</option>
                                            @endif
                                        @endforeach
                                    @else
                                        @foreach( $product->getSubFondo( $solicitud ) as $subFondo )
                                            @if ( $subFondo->marca_id == $product->id_producto )
                                                <option selected value="{{ $subFondo->id . ',' . $subFondo->marca_id}}" style="background-color:#00FFFF">{{$subFondo->descripcion . ' S/.' . $subFondo->saldo }}</option>
                                            @else   
                                                <option value="{{ $subFondo->id . ',' . $subFondo->marca_id}}">{{$subFondo->descripcion . ' S/.' . $subFondo->saldo }}</option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                            @else
                                <span class="input-group-addon">
                                    {{ $product->subCatFondo->descripcion . ' | ' . $product->fondoMarca->descripcion . ' S/.' . $product->thisSubFondo()->saldo }}
                                </span>
                                <input type="hidden" value="{{ $product->id_fondo . ',' . $product->id_fondo_producto . ',' . $product->id_fondo_user }}" name="fondo-producto[]">
                            @endif
                            <span class="input-group-addon">{{ $detalle->typemoney->simbolo }}</span>
                            <input name="monto_producto[]" type="text" class="form-control text-right amount_families" value="{{ isset( $product->monto_asignado ) ? $product->monto_asignado : 
                            round( $detalle->monto_actual / count( $solicitud->products ) , 2 )}}">
                        </div>
                    @else
                        {{{ $product->marca->descripcion or '-' }}}
                        <label class="label label-primary">
                            {{ $detalle->typemoney->simbolo . ( isset( $product->monto_asignado ) ? $product->monto_asignado : round( $detalle->monto_actual / count( $solicitud->products ) , 2 ) ) }}
                        </label>
                        @if( isset( $product->subCatFondo->descripcion ) )
                            <span class="badge">{{ $product->fondoMarca->descripcion . ' | ' . $product->subCatFondo->descripcion }}</span>    
                        @endif
                    @endif
                <input type="hidden" name="producto[]" value="{{ $product->id }}">
                </li>
            @endforeach
        </ul>
    </div>
</div>