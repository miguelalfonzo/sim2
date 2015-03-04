<table class="table table-striped table-bordered dataTable" id="table_solicitude_tes" style="width: 100%">
    <thead>
    <tr>
        <th>#</th>
        <th style="display:none">Retencion</th>
        <th>Solicitud</th>
        <th>Deposito</th>
        <th style="display:none">Solicitado</th>
        <th>Estado</th>
        <th>Fecha</th>
        <th>Tipo de Solicitud</th>
        <th>Edicion</th>
    </tr>
    </thead>
    <tbody>
    @if(isset($solicituds))
        @foreach($solicituds as $solicitude)
            <tr>
                <td style="text-align: center" class="id_solicitud" data-type-response="{{$solicitude->response->type}}">{{$solicitude->idsolicitud}}</td>
                <input type="hidden" id="sol_token" value="{{$solicitude->token}}">
                @if(!is_null($solicitude->response))
                    @if($solicitude->response->type == 'R')
                    <input type="hidden" value="{{$solicitude->response->Rm->nombres.' '.$solicitude->response->Rm->apellidos}}" class="benef">
                    <input type="hidden" value="{{$solicitude->titulo}}" class="sol_titulo">
                    @elseif($solicitude->response->type == 'AG')
                    <input type="hidden" value="{{$solicitude->response->person->nombres.' '.$solicitude->response->person->apellidos}}" class="benef">
                    <input type="hidden" value="{{$solicitude->titulo}}" class="sol_titulo">
                    @else
                    <input type="hidden" value="Usuario no autorizado" class="benef">
                    <input type="hidden" value="{{$solicitude->titulo}}" class="sol_titulo">
                    @endif
                @endif
                <td style="display:none" class="tes-ret">
                @if ($solicitude->retencion == null)
                    0
                @else
                    {{$solicitude->retencion}}
                @endif
                </td>
                <td>{{$solicitude->titulo}}</td>
                <td  style="text-align: center">
                @if (!$solicitude->retencion == null)
                    {{$solicitude->typemoney->simbolo.' '.($solicitude->monto - $solicitude->retencion)}}
                @else
                    {{$solicitude->typemoney->simbolo.' '.$solicitude->monto }}
                @endif
                </td>    
                <td style="display:none; text-align:center" class="total_deposit">
                    {{$solicitude->typemoney->simbolo.' '.$solicitude->monto }}
                </td>
                <td style="text-align: center">
                    <span class="label" style="background-color: {{$solicitude->state->color}}">{{$solicitude->state->nombre}}</span>
                    @if(!$solicitude->retencion == null)
                        <span class="label" style="background-color: {{$solicitude->state->color}}">RETENCION</span>
                    @endif
                </td>
                <td style="text-align: center">{{ date_format(date_create($solicitude->created_at), 'd/m/Y' )}}</td>
                <td style="text-align: center">{{$solicitude->typesolicitude->nombre}}</td>
                <td>
                    <div class="div-icons-solicituds">
                        <a href="{{URL::to('ver-solicitud-tes').'/'.$solicitude->token}}"><span class="glyphicon glyphicon-eye-open"></span></a>
                        @if($solicitude->estado == APROBADO)
                            <a class="modal_deposit"><span class="glyphicon glyphicon-usd"></span></a>
                        @endif
                    </div>
                </td>
            </tr>
        @endforeach
    @endif
    </tbody>
</table>

@if(isset($solicituds))
    @foreach($solicituds as $solicitude)
        <?php if($solicitude->estado == APROBADO) $state = APROBADO; ?>
    @endforeach
    @if(isset($state))
    <!-- Modal -->
    <div class="modal fade" id="enable_deposit_Modal" tabindex="-1" role="dialog" aria-labelledby="enable_deposit_ModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
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
@endif
