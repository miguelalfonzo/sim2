<div class="form-group col-sm-12 col-md-6 col-lg-6">
    <div style="padding:0 15px" >
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Clientes</h3>
            </div>
            <div class="panel-body">
                @foreach($solicitud->clients as $client)
                    @if (is_null($client->idcliente))
                        <div class="form-group ">
                            <div>
                                <input id="textinput" type="text" placeholder=""
                                       value="No hay cliente asignado" readonly
                                       class="form-control input-md ">
                            </div>
                        </div>
                    @elseif ($client->from_table == TB_DOCTOR)
                        <div class="form-group ">
                            <div>
                                <input id="textinput" type="text" placeholder=""
                                       value="DOCTOR: {{$client->doctors->pefnrodoc1.'-'.$client->doctors->pefnombres.' '.$client->doctors->pefpaterno.' '.$client->doctors->pefmaterno}}" readonly
                                       class="form-control input-md ">
                            </div>
                        </div>
                    @elseif ($client->from_table == TB_INSTITUTE)
                        <div class="form-group ">
                            <div>
                                <input id="textinput" type="text" placeholder=""
                                       value="CENTRO: {{$client->institutes->pejnrodoc.'-'.$client->institutes->pejrazon}}" readonly
                                       class="form-control input-md ">
                            </div>
                        </div>
                    @elseif(!is_null($client->idcliente))
                        <div class="form-group ">
                            <div>
                                <input id="textinput" type="text" placeholder=""
                                       value="{{$client->client->clnombre}}" readonly
                                       class="form-control input-md ">
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</div>