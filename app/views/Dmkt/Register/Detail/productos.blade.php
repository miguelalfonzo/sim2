<div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
    <label class="control-label">Productos</label>
    <ul id="listfamily" style="padding:0">
        @if ( ! isset( $solicitud ) )
            <li>
                <div style="margin-top:5px;position:relative">
                    <select id="selectfamily" name="productos[]" class="form-control products">
                        @foreach( $families as $family )
                            <option value="{{$family->id}}">{{$family->descripcion}}</option>
                        @endforeach
                    </select>
                    <button type='button' class='btn-delete-family' style="display:none;z-index:100">
                        <span class='glyphicon glyphicon-remove'></span>
                    </button>
                </div>
            </li>
        @else
            @foreach ( $solicitud->products as $solProduct )
                <li>
                    <div style="margin-top:5px;position:relative">
                        <select id="selectfamily" name="productos[]" class="form-control products">
                            @foreach( $families as $family )
                                @if( $solProduct->id_producto == $family->id )
                                    <option value="{{$family->id}}" selected>{{$family->descripcion}}</option>
                                @else
                                    <option value="{{$family->id}}">{{$family->descripcion}}</option>
                                @endif
                            @endforeach
                        </select>
                        <button type='button' class='btn-delete-family' style="z-index:100">
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
<script>
    $( '#btn-add-family' ).on( 'click' , function() 
    {
        $( '.btn-delete-family' ).show();
        $( '#listfamily>li:first-child' ).clone(true, true).appendTo( '#listfamily' );
    });
    $( document ).off( 'click' , '.btn-delete-family' );
    $( document ).on( 'click', '.btn-delete-family' , function() 
    {
        $( '#listfamily>li .porcentaje_error' ).css( { border: 0 } );
        $( '.option-des-1' ).removeClass( 'error' );
        $( '.families_repeat' ).text( '' );
        var k = $( '#listfamily li' ).size();
        if ( k > 1 )
        {
            var other = $( '.btn-delete-family' ).index( this );
            $( '#listfamily li' ).eq( other ).remove();
            var p = $("#listfamily li").size();
            if( p === 1 )
            {
                $( '.btn-delete-family' ).hide();
            }
        }
    });
    $(document).off( 'click' , '.btn-delete-client' );
    $(document).on( 'click' , '.btn-delete-client' , function () 
    {
        var li = $(this).closest('li');
        var ul = li.parent();
        if ( li.index() === 0 && ul.children().length > 1 )
        {
            var clientType = li.find( 'input[ name="tipos_cliente[]"]' ).val();
            li.remove();
            var old_clientType = ul.children().first().find( 'input[ name="tipos_cliente[]"]' ).val();
            if( clientType !== old_clientType )
            {
                clientFilter( old_clientType , 'eliminacion' );    
            }
        }
        else
        {
            li.remove();
        }
        if ( ul.children().length === 0 )
        {
            fillInvestmentsActivities();
        }
});
</script>