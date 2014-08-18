function newSolicitude(){

    var clients = [];

    $.getJSON( "http://localhost/BitBucket/bago_dmkt_rg/public/getclients", function( data ) {
        clients = data;
    });

    $('.clients_repeat').hide();

    $("#btn-add-family").on('click', function () {
        //console.log('hola');
        $(".btn-delete-family").show();
        $('#listfamily>li:first-child').clone(true, true).appendTo('#listfamily');
    });

    //$(".btn-delete-family").hide();

    $(document).on("click", ".btn-delete-family", function () {
        $('#listfamily>li .porcentaje_error').css({"border": "0"});

        $(".option-des-1").removeClass('error');
        var k = $("#listfamily li").size();
        console.log(k);
        if (k > 1) {

            var other = $(".btn-delete-family").index(this);
            $("#listfamily li").eq(other).remove();
            var p = $("#listfamily li").size();
            if (p == 1) {
                $(".btn-delete-family").hide();
            }
        }
    });
    //Validaciones
    $('#idestimate').numeric();


    //Selecionar tipo de solocitud

    $('.solicitude_factura').hide();
    $('.solicitude_monto').hide();
    $('.selecttypesolicitude').on('change',(function(){

        if($(this).val()==1){

            $('.solicitude_factura').hide()
            $('.solicitude_monto').hide();
        }else if($(this).val()==2){

            $('.solicitude_factura').show();
            $('.solicitude_monto').show();

        }else if($(this).val()==3){

            $('.solicitude_factura').hide();
            $('.solicitude_monto').hide();
        }

    }));

    function load_client(client){

        function lightwell(request, response) {
            function hasMatch(s) {
                return s.toLowerCase().indexOf(request.term.toLowerCase())!==-1;
            }
            var i, l, obj, matches = [];

            if (request.term==="") {
                response([]);
                return;
            }
            for  (i = 0, l = clients.length; i<l; i++) {
                obj = clients[i];
                if (hasMatch(obj.clnombre)) {
                    matches.push(obj);
                }
            }
            response(matches);
        }
        $('#project'+client).autocomplete({
            minLength: 0,
            source: lightwell,
            focus: function( event, ui ) {
                $( this ).val(ui.item.clcodigo +' - '+ ui.item.clnombre);

                return false;
            },
            select: function( event, ui ) {
                // $( "#project" ).val( ui.item.label );
                $(this).parent().removeClass('has-error has-feedback');
                $(this).parent().children('.span-alert').removeClass('glyphicon glyphicon-remove form-control-feedback');
                $(this).parent().addClass('has-success has-feedback');
                $(this).parent().children('.span-alert').addClass('glyphicon glyphicon-ok form-control-feedback');
                $(this).attr('readonly','readonly');
                return false;
            }
        })
            .data( "ui-autocomplete" )._renderItem = function( ul, item ) {
            return $( "<li>" )
                .append( "<a>" +
                    "<br><span style='font-size: 80%;'>Codigo: " + item.clcodigo + "</span>" +
                    "<br><span style='font-size: 60%;'>Cliente: " + item.clnombre + "</span></a>" )
                .appendTo( ul );
        };
    }
    load_client(1);
    var client = 2;
    $(document).on('click', '#btn-add-client', function () {
        $('<li><div style="position: relative"><input id="project'+client+'" name="clients[]" type="text" placeholder="" style="margin-bottom: 10px" class="form-control input-md project"><button type="button" class="btn-delete-client" style="z-index: 2"><span class="glyphicon glyphicon-remove"></span></button></div></li>').appendTo('#listclient');
        $(".btn-delete-client").show();
        setTimeout(function(){
            load_client(client);
            client++;
        }, 200);
    });
    $(document).on("click", ".btn-delete-client", function () {
        $('#listclient>li .porcentaje_error').css({"border": "0"});
        $('.clients_repeat').hide();
        $(".option-des-1").removeClass('error');
        var k = $("#listclient li").size();
        console.log(k);
        if (k > 1) {
            var other = $(".btn-delete-client").index(this);
            $("#listclient li").eq(other).remove();
            var p = $("#listclient li").size();
            if (p == 1) {
                $(".btn-delete-client").hide();
            }
        }
    });

    $('#type_activity').on('change',function(){

        var id = $(this).val();
        var sel = $('#sub_type_activity');
        $("#sub_type_activity option").remove();
        $.getJSON( "http://localhost/BitBucket/bago_dmkt_rg/public/getsubtypeactivities/"+id, function( data ) {

            $.each(data,function(index,value){
                console.log(value);
                $('<option>').text(value.nombre).val(value.idsubtipoactividad).appendTo(sel);
            });
        });
    });

    $('.register_solicitude').on('click',(function(e){


        var aux = 0;
        var validate = 0; // si cambia  a 1 hay errores
        var obj = [];
        var clients_input = [];
        var families_input = [];
        for  (var i = 0, l = clients.length; i<l; i++) {
            obj[i] = clients[i].clcodigo+' - '+clients[i].clnombre;
            //console.log(clients[i].clnombre);
        }

        setTimeout(function(){


            $('.project').each(function(index){
                var input = $(this).val();
                clients_input[index] = input;

                var ban = obj.indexOf(input);
                if(ban != -1){
                    console.log(ban);

                }else{
                    aux = 1;
                    validate =1;
                    console.log(ban);
                    $(this).parent().addClass('has-error has-feedback');
                    $(this).parent().children('.span-alert').addClass('span-alert glyphicon glyphicon-remove form-control-feedback');
                }

            });


        }, 100);

        setTimeout(function(){
            //validacion de campos duplicados en clientes
            for(var i=0 ; i<clients_input.length  ; i++){
                $('.project').each(function(index){

                    if(index!=i &&  clients_input[i] === $(this).val()){
                        validate=1;
                        var ind = clients_input.indexOf($(this).val());
                        clients_input[index]='';
                        // console.log($(this).val() + ' - '+ index )
                        $(this).parent().removeClass('has-success has-feedback');
                        $(this).parent().addClass('has-error has-feedback');
                        $(this).parent().children('.span-alert').addClass('span-alert glyphicon glyphicon-remove form-control-feedback');
                        $(".clients_repeat").show();
                    }

                });
            }
        },200);

        setTimeout(function(){

            var families = $('.selectfamily');
            families.each(function(index){
                families_input[index] = $(this).val();

            });


            for(var i=0 ; i<families_input.length ; i++){
                families.each(function(index){

                    if(index !=i && families_input[i] === $(this).val()){
                        validate =1;
                        var ind = families_input.indexOf($(this).val());
                        families_input[index]='';
                        console.log($(this).val() + ' - ' + index);
                        $(this).css('border-color','red');
                    }
                });
            }

            if(validate==0){

               $('#form-register-solicitude').submit();
            }else{
                alert('datos incorrectos');
            }

        },300);
        e.preventDefault();
    }));



}
