<table class="table table-striped table-bordered dataTable" id="table_solicitude" style="width: 100%">
    <thead>
        <tr>
            <th>#</th>
            <th>Solicitud</th>
            <th>Visto Ultimo</th>
            @if(Auth::user()->type == TESORERIA)
                <th style="display:none">Solicitado</th>
                <th style="display:none">Retencion</th>
                <th>Deposito</th>
            @else
                <th>Monto Solicitado</th>
            @endif
            <th>Estado</th>
            <th>Fecha</th>
            <th>Tipo de Solicitud</th>
            <th>Edicion</th>
        </tr>
    </thead>
    <tbody>
        @foreach($solicituds as $solicitude)
            <tr>
                <td style="text-align:center" class="id_solicitud">{{$solicitude->idsolicitud}}</td>
                @if ( Auth::user()->type == TESORERIA )
                    <input type="hidden" id="sol_token" value="{{$solicitude->token}}">  
                    @if(!is_null($solicitude->response))
                        @if($solicitude->response->type == 'R')
                            <input type="hidden" value="{{$solicitude->response->Rm->nombres.' '.$solicitude->response->Rm->apellidos}}" class="benef">
                        @elseif($solicitude->response->type == 'AG')
                            <input type="hidden" value="{{$solicitude->response->person->nombres.' '.$solicitude->response->person->apellidos}}" class="benef">
                        @else
                            <input type="hidden" value="Usuario no autorizado" class="benef">
                        @endif
                    @endif
                @endif
                <td style="text-align:left" class="sol_titulo">
                    @if (!is_null($solicitude->idetiqueta))
                        <span class="label label-info" style="margin-right:1em;background-color:{{$solicitude->etiqueta->color}}">
                            {{$solicitude->etiqueta->nombre}}
                        </span>
                    @endif
                    <label>{{$solicitude->titulo}}</label>
                </td>
                <td style="text-align:center"> 
                    @if(count($solicitude->history) != 0)
                        @if (is_object($solicitude->history[0]->user))    
                            @if ($solicitude->history[0]->user->type == REP_MED)
                                {{ucwords(strtolower($solicitude->history[0]->user->Rm->nombres.' '.$solicitude->history[0]->user->Rm->apellidos))}}
                            @elseif ($solicitude->history[0]->user->type == SUP)
                                {{ucwords(strtolower($solicitude->history[0]->user->Sup->nombres.' '.$solicitude->history[0]->user->Sup->apellidos))}}
                            @elseif ($solicitude->history[0]->user->type == GER_PROD)
                                {{ucwords(strtolower($solicitude->history[0]->user->GerProd->descripcion))}}
                            @elseif ( in_array($solicitude->history[0]->user->type, array(GER_COM,CONT,TESORERIA,ASIS_GER) ))
                                {{ucwords(strtolower($solicitude->history[0]->user->person->nombres.' '.$solicitude->history[0]->user->person->apellidos))}}
                            @else
                                Usuario no autorizado
                            @endif
                        @else
                            -
                        @endif
                    @endif
                </td>
                @if(Auth::user()->type == TESORERIA)
                    <td style="display:none;" class="total_deposit">
                        {{$solicitude->typemoney->simbolo.' '.$solicitude->monto }}
                    </td>
                    <td style="display:none" class="tes-ret">
                    @if ($solicitude->retencion == null)
                        0
                    @else
                        {{$solicitude->retencion}}
                    @endif
                    </td>
                    <td  style="text-align:center">
                        @if (!$solicitude->retencion == null)
                            {{$solicitude->typemoney->simbolo.' '.($solicitude->monto - $solicitude->retencion)}}
                        @else
                            {{$solicitude->typemoney->simbolo.' '.$solicitude->monto }}
                        @endif
                    </td>
                @else
                    <td style="text-align:center" class="total_deposit">{{$solicitude->typemoney->simbolo.$solicitude->monto}}</td>
                @endif
                @include('template/states')
                <td style="text-align:center">{{ date_format(date_create($solicitude->created_at), 'd/m/Y' )}}</td>
                <td style="text-align:center">{{$solicitude->typesolicitude->nombre}}</td>
                @include('template/icons')
            </tr>
        @endforeach
    </tbody>
</table>

@if( Auth::user()->type == TESORERIA )
    <div class="modal fade" id="enable_deposit_Modal" tabindex="-1" role="dialog" aria-labelledby="enable_deposit_ModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                        <span class="sr-only">Close</span>
                    </button>
                    <h4 class="modal-title" id="enable_deposit_ModalLabel">Registro del Depósito</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xs-12 col-sm-6 col-md-6">
                            <div class="form-expense">
                                <label>Solicitud</label>
                                <div class="input-group">
                                    <div id="id-solicitude" class="input-group-addon" value=""></div>
                                    <input id="sol-titulo" class="form-control" type="text" disabled>
                                    <input id="token" type="hidden">
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-6">
                            <div class="form-expense">
                                <label>Beneficiario</label>
                                <input id="beneficiario" class="form-control" type="text" disabled>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-6">
                            <div class="form-expense">
                                <label>Monto Solicitado</label>
                                <input id="tes-mon-sol" class="form-control" type="text" disabled>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-6">
                            <div class="form-expense">
                                <label>Retencion</label>
                                <input id="tes-mon-ret" class="form-control" type="text" disabled>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-6">
                            <div class="form-expense">
                                <label>Monto a Depositar</label>
                                <input id="total-deposit" class="form-control" type="text" disabled>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                         <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-expense">
                                <label for="op-number">Número de Operación, Transacción, Cheque:</label>
                                <input id="op-number" type="text" class="form-control">
                                <p id="message-op-number" style="margin-top:1em;color:#a94442;"></p> 
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a id="" href="#" class="btn btn-success register-deposit" data-deposit = "solicitude" style="margin-right: 1em;">Confirmar Operación</a>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
@endif
<script>
    $('.element').tooltip();
</script>