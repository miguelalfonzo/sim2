<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
    <label class="control-label">Productos</label>
    <div>
        <ul id="listfamily" style="padding:0">
            @if ( !isset( $solicitud ) )
                <li>
                    <div style="position: relative">
                        <select id="selectfamily" name="productos[]" class="form-control products" style="margin-top:5px">
                            @foreach( $families as $family )
                                <option value="{{$family->id}}">{{$family->descripcion}}</option>
                            @endforeach
                        </select>
                        <button type='button' class='btn-delete-family'>
                            <span class='glyphicon glyphicon-remove'></span>
                        </button>
                    </div>
                </li>
            @else
                @foreach ( $solicitud->products as $solProduct )
                    <li>
                        <div style="position: relative">
                            <select id="selectfamily" name="productos[]" class="form-control products">
                                @foreach( $families as $family )
                                    @if( $solProduct->id_producto == $family->id )
                                        <option value="{{$family->id}}" selected>{{$family->descripcion}}</option>
                                    @else
                                        <option value="{{$family->id}}">{{$family->descripcion}}</option>
                                    @endif
                                @endforeach
                            </select>
                            <button type='button' class='btn-delete-family'>
                                <span class='glyphicon glyphicon-remove'></span>
                            </button>
                        </div>
                    </li>
                @endforeach
            @endif
        </ul>
        <span class="col-sm-10 col-md-10 families_repeat" style="margin-bottom: 10px ; margin-top: -10px"></span>
        <button type="button" class="btn btn-default" id="btn-add-family" style="margin-top:10px">Agregar Otra Familia</button>
    </div>
</div>