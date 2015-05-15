<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
    <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12 control-label">Productos</label>
    <ul id="listfamily" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        @if ( !isset( $solicitude ) )
            <li>
                <div style="position: relative">
                    <select id="selectfamily" name="productos[]" class="form-control selectfamily" style="margin-bottom:10px ">
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
            @foreach ( $solicitud->families as $solFamily )
                <li>
                    <div style="position: relative">
                        <select id="selectfamily" name="productos[]" class="form-control selectfamily" style="margin-bottom:10px ">
                            @foreach($families as $family)
                                @if( $solFamily->idfamilia == $family->id )
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
    <button type="button" class="btn btn-default" id="btn-add-family">Agregar Otra Familia</button>
</div>