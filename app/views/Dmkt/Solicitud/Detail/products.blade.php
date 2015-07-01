<div class="form-group col-xs-12 col-sm-12 col-md-6 col-lg-6">

    <div class="panel panel-default">
        <!-- Default panel contents -->
        <div class="panel-heading">Productos</div>
        <!-- List group -->
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
    
    <div class="form-group col-sm-12 col-md-12 col-lg-12">
        <span id="amount_error_families"></span>
    </div>
</div>
        