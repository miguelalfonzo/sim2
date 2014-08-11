function newSolicitude(){

    var clients = [];
    var clients_select = [];
    $.getJSON( "http://localhost/BitBucket/bago_dmkt_rg/public/show", function( data ) {
        clients = data;
    });



    $("#btn-add-prod").on('click', function () {
        //console.log('hola');
        $(".btn-delete-prod").show();
        $('#listprod>li:first-child').clone(true, true).appendTo('#listprod');
    });

    $(".btn-delete-prod").hide();

    $(document).on("click", ".btn-delete-prod", function () {
        $('#listprod>li .porcentaje_error').css({"border": "0"});
        $('.repeat').hide();
        $(".option-des-1").removeClass('error');
        var k = $("#listprod li").size();
        console.log(k);
        if (k > 1) {

            var other = $(".btn-delete-prod").index(this);
            $("#listprod li").eq(other).remove();
            var p = $("#listprod li").size();
            if (p == 1) {
                $(".btn-delete-prod").hide();
            }
        }
    });


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
                $( this ).val( ui.item.clnombre);

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
        $('<li><div style="position: relative"><input id="project'+client+'" name="cliente" type="text" placeholder="" style="margin-top: 10px" class="form-control input-md project"><span class="span-alert"></span><button type="button" class="btn-delete-client" style=""><span class="glyphicon glyphicon-remove"></span></button></div></li>').appendTo('#listclient');
        $(".btn-delete-client").show();
        setTimeout(function(){
            load_client(client);
            client++;
        }, 200);
    });
    $(document).on("click", ".btn-delete-client", function () {
        $('#listclient>li .porcentaje_error').css({"border": "0"});
        $('.repeat').hide();
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


    $('.register_solicitude').on('click',function(e){
        e.preventDefault();
       // var clientes = [];
        var aux = 0;

            //clientes = data;
            var obj = [];
            var clients_input = [];
            for  (var i = 0, l = clients.length; i<l; i++) {
                obj[i] = clients[i].clnombre;
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
                        $(this).parent().addClass('has-error has-feedback');
                        $(this).parent().children('.span-alert').addClass('span-alert glyphicon glyphicon-remove form-control-feedback');
                    }

                });

                if(aux == 0){
                    alert('datos correctos');
                }else{
                    alert('ingrese cliente correcto');
                }
            }, 200);

            setTimeout(function(){

                    for(var i=0 ; i<clients_input.length  ; i++){
                        $('.project').each(function(index){


                        if(index!=i &&  clients_input[i] === $(this).val()){
                            var ind = clients_input.indexOf($(this).val());
                            clients_input.splice(ind,2);
                            console.log($(this).val() + ' - '+ index )
                        }

                        });
                     }
            },200)




        });



}

$(function(){
    var url = window.location.href;
    if(url === "http://localhost/BitBucket/bago_dmkt_rg/public/newSolicitude")
    {
        newSolicitude();
    }

    //Expense
    
    //Registro de solo números
    $("#ruc").keypress(function(e){
        if(e.which != 8 && e.which != 0 && (e.which <48 || e.which >57))
        {
            return false;
        }
    });

    //Búsqueda de Razón Social en la SUNAT una vez introducido el RUC
    $("#ruc").on("focusout",function(){
        var ruc = $(this).val();
        var l   = Ladda.create(document.getElementById('razon'));
        if(ruc.length<11)
        {
            $("#razon").html("El ruc ingresado no contiene 11 dígitos.");
        }
        else
        {
            $.ajax({
                type: 'post',
                url: 'consultarRuc',
                data: {
                    ruc: ruc
                },
                beforeSend:function(){
                    l.start();
                    $("#razon").css("color","#5c5c5c");
                    $("#ruc").attr("disabled",true);
                },
                error: function(){
                    l.stop();
                    $("#razon").html("No se puede buscar el RUC.");
                    $("#ruc").attr("disabled",false);
                }
            }).done(function (response){
                if(response == 0)
                {
                    $("#razon").html("Ruc menor a 11 digitos.");
                }
                else if(response == 1)
                {
                    $("#razon").html("No existe el Ruc consultado.");
                }
                else
                {
                    $("#razon").html(response['razonSocial']);
                }
                l.stop();
                $("#ruc").attr("disabled",false);
            });
        }
    });

    $("#save-expense").on("click",function(){
        var ruc            = $("#ruc").val();
        var razon          = $("#razon").text();
        var type_voucher   = $("#type_voucher").val();
        var number_voucher = $("#number_voucher").val();
        var date           = $("#date").val();
        var total          = $("#total").val();
        console.log(ruc);
        console.log(razon);
        console.log(type_voucher);
        console.log(number_voucher);
        console.log(date);
        console.log(total);
    });

    $(".delete-expense").on("click",function(e){
        e.preventDefault();
        $(this).parent().parent().remove();
    });

    $(".edit-expense").on("click",function(e){
        e.preventDefault();
        var row = $(this).parent().parent();
        $.each(row,function(){
            var type_voucher_edit   = $(this).find(".type_voucher").html();
            var ruc_edit            = $(this).find(".ruc").html();
            var razon_edit          = $(this).find(".razon").html();
            var number_voucher_edit = $(this).find(".number_voucher").html();
            var total_edit          = $(this).find(".total").html();
            $("#ruc").val(ruc_edit);
            $("#razon").html(razon_edit).css("color","#5c5c5c");
            $("#number_voucher").val(number_voucher_edit);
            $("#date").val();

        });
    });
});