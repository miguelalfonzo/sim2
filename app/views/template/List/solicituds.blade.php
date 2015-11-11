<table cellpadding="0" cellspacing="0" border="0" style="width:100%" id="table_solicitudes" class="table table-striped table-hover table-bordered" >
    <thead>
        <tr>
            <th>#</th>
            <th>Solicitud</th>
            <th>
                @if( Auth::user()->type == TESORERIA )
                    Responsable
                @else
                    Solicitado por
                @endif
            </th>
            <th>
                @if( in_array( Auth::user()->type , array( TESORERIA , CONT ) )) 
                    Fecha de Depósito
                @else
                    Fecha de Solicitud
                @endif
            </th>
            <th>Aprobado por</th>
            <th>Fecha de Aprobación</th>
            <th>
                @if( Auth::user()->type == TESORERIA )
                    Deposito
                @else
                    Monto
                @endif
            </th>
            <th>Estado</th>
            <th>Tipo</th>
            <th class="col-xs-2 col-sm-2">Edicion</th>
            @if ( in_array( Auth::user()->type , array( GER_COM , CONT ) ) )
                <th data-checkbox="true">Marcar</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @foreach( $solicituds as $solicitud )
            <tr>
                <input type="hidden" id="timeLineStatus" value="{{$solicitud->id_estado}}">
                <td class="text-center id_solicitud detail-control">{{$solicitud->id}}</td>
                <td class="text-left sol_titulo">
                    <label>{{$solicitud->titulo}}</label>
                </td>

                <td class="text-center">
                        {{ $solicitud->createdBy->personal->full_name }}
                </td>
                <td class="text-center">
                    {{ $solicitud->created_at }}
                </td>
                <td class="text-center">
                        -
                </td>
               <td class="text-center">
                        -
                </td>
                    <td class="text-center total_deposit">
                        {{ $solicitud->detalle->typeMoney->simbolo . ' ' . $solicitud->detalle->monto_actual }}
                    </td>
                
                <td class="text-center">
                        -
                </td>
                
                <td class="text-center">{{ $solicitud->typeSolicitude->nombre }}</td>
                
                <td class="text-center">
                        -
                </td>
                
            </tr>
        @endforeach
    </tbody>
</table>