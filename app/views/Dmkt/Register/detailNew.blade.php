<form id="form-register-solicitude" class="" method="post" enctype="multipart/form-data" action="registrar-solicitud">
    {{ Form::token() }}

    @if( isset( $solicitud ) )
        <input value="{{$solicitud->id}}" name="idsolicitud" type="hidden">
    @endif
    
    <!-- MOTIVO DE LA SOLICITUD -->
    <div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
        <label class="control-label">Motivo</label>
        <div>
            <select class="form-control chosen-select" name="motivo" id="motivo">
                @foreach( $reasons as $reason )
                    @if( isset( $solicitud ) && $solicitud->idtiposolicitud == $reason->id)
                        <option selected value="{{$reason->id}}">{{$reason->nombre}}</option>
                    @else
                        <option value="{{$reason->id}}">{{$reason->nombre}}</option>
                    @endif
                @endforeach
            </select>
        </div>
    </div>

    <!-- BUSQUEDA DE CLIENTES -->
    <div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
        <label class="control-label">Clientes</label>
        <div class="form-group" id="divListadoClientes">
            <input type="text" class="form-control" name="clientesFind" id="clientesFind" style="max-height: 200px;overflow-y: auto;overflow-x: hidden;padding-right: 20px;">
        </div>
    </div>

    <!-- LISTA DE CLIENTES -->
    <div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
        <label class="control-label" for="ruc">Lista de Clientes</label>
        <div id="ulClientes">
            <ul class="list-group" id="clientes">
                
            </ul>
        </div>
    </div>

   
</form>
