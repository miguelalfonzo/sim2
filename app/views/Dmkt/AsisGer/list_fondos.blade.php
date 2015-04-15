<div class="form-group col-xs-12 col-sm-3 col-md-3 total-fondo fondo_r" style="">
    <input class="form-control input-md" name="total" type="text" value="Total : {{$total}}" readonly>
</div>
@if( $state == ACTIVE )
    <div class="form-group col-xs-3 col-sm-2 col-md-1 pull-right fondo_r" >
        <a id="export-fondo" class="btn btn-sm btn-primary ladda-button" data-style="zoom-in" data-size="l">
            <i class="glyphicon glyphicon-print"></i> 
            Exportar
        </a>
    </div>
    <div class="form-group col-xs-3 col-sm-2 col-md-1 pull-right fondo_r" >
        <a id="terminate-fondo" class="btn btn-sm btn-danger" data-style="zoom-in" data-size="l">
            <i class="glyphicon glyphicon-download"></i> 
            Terminar
        </a>
    </div>
@endif
<div class="table-responsive fondo_r">
    <table class="table table-hover table-bordered table-condensed dataTable" id="table_solicitude_fondos" style="width: 100%">
        <thead>
            <tr>
                <th>#</th>
                <th>SISOL - Hospital</th>
                <th>Depositar a</th>
                <th>Fondo</th>
                <th>Total a depositar</th>
                <th>Supervisor</th>
                <th>Edicion</th>
             </tr>
        </thead>
        <tbody>
            @foreach( $solicituds as $solicitud )
                <tr>
                    <td>{{$solicitud->id}}</td>
                    <td style="text-align:center">{{$solicitud->titulo}}</td>
                    <td style="text-align:center">{{$solicitud->asignedTo->rm->nombres.' '.$solicitud->asignedTo->rm->apellidos}}</td>
                    <td style="text-align:center">
                        {{$solicitud->detalle->fondo->nombre}}
                    </td>
                    <td style="text-align:center">{{$solicitud->detalle->fondo->typeMoney->simbolo.' '.json_decode($solicitud->detalle->detalle)->monto_aprobado}}</td>
                    <td style="text-align:center">{{json_decode($solicitud->detalle->detalle)->supervisor}}</td>
                    <td style="text-align:center">
                        @if ( $state == ACTIVE )
                            <div class="div-icons-solicituds">
                                <a href="#" class="edit-fondo" data-idfondo="{{$solicitud->id}}">
                                    <span class="glyphicon glyphicon-pencil"></span>
                                </a>
                                <a  href="#" class ="delete-fondo" data-idfondo="{{$solicitud->id}}" data-token="{{csrf_token()}}">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </a>
                            </div>
                        @elseif ( $state == BLOCKED )
                            TERMINADO
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>