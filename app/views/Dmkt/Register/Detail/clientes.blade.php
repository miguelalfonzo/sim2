<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
    <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12 control-label" for="textinput">Cliente</label>
    <ul id="listclient" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        @if(isset($solicitude))
            @foreach($solicitude->clients as $client)
                <li>
                    <div style="position: relative" class="has-success has-feedback">
                        @if($client->from_table == TB_DOCTOR)
                            <input type='text' id="idclient0" name="clientes[]" type="text" style="margin-bottom: 10px"
                            class="form-control input-md project input-client cliente-seeker" pk="{{$client->idcliente}}"
                            value="DOCTOR: {{$client->doctors->pefnrodoc1.'-'.$client->doctors->pefnombres.' '.$client->doctors->pefpaterno.' '.$client->doctors->pefmaterno}}"
                            data-valor="all" table="{{$client->from_table}}" disabled>
                        @elseif($client->from_table == TB_INSTITUTE)
                            <input type='text' id="idclient0" name="clientes[]" type="text" style="margin-bottom: 10px"
                            class="form-control input-md project input-client cliente-seeker" pk="{{$client->idcliente}}"
                            value="CENTRO: {{$client->institutes->pejnrodoc.'-'.$client->instituteS->pejrazon}}"
                            data-valor="all" table="{{$client->from_table}}" disabled>
                        @else
                            <input id="idclient0" name="clientes[]" type="text" style="margin-bottom: 10px"
                            class="form-control input-md project input-client cliente-seeker" pk="{{$client->client->clcodigo}}"
                            value='{{isset($client->client->clnombre) ? $client->client->clcodigo.' - '.$client->client->clnombre : null }}'
                            data-valor="all" table="VTA.CLIENTES" disabled>
                        @endif
                        <button type='button' class='btn-delete-client' style="z-index: 2">
                            <span class='glyphicon glyphicon-remove'></span>
                        </button>
                    </div>
                </li>
            @endforeach
        @else
            <li>
                <div style="position: relative" class="">
                    <input id="idclient0" name="clientes[]" type="text" placeholder="" style="margin-bottom: 10px"
                    class="form-control input-md input-client cliente-seeker" data-valor=""
                    value="{{isset($client->clnombre) ? $client->clcodigo.' - '.$client->clnombre : null }}">
                    <button type='button' class='btn-delete-client' style="display: none; z-index: 2">
                        <span class='glyphicon glyphicon-remove'></span>
                    </button>
                </div>
            </li>
        @endif
    </ul>
    <button type="button" class="btn btn-default" id="btn-add-client">Agregar Otro Cliente</button>
</div>