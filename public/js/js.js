function newSolicitude(){


    var clients = [];
    var clients_select = [];
    $.getJSON( "http://localhost/BitBucket/bago_dmkt_rg/public/getclients", function( data ) {
        clients = data;
    });

    $('.clients_repeat').hide();

    $("#btn-add-family").on('click', function () {
        //console.log('hola');
        $(".btn-delete-family").show();
        $('#listfamily>li:first-child').clone(true, true).appendTo('#listfamily');
    });

    $(".btn-delete-family").hide();

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
    //Seleccionar estado (filtrar)


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
        $('<li><div style="position: relative"><input id="project'+client+'" name="clients[]" type="text" placeholder="" style="margin-top: 10px" class="form-control input-md project"><button type="button" class="btn-delete-client" style=""><span class="glyphicon glyphicon-remove"></span></button></div></li>').appendTo('#listclient');
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


    $('.register_solicitude').on('click',function(e){
        e.preventDefault();
       // var clientes = [];
        var aux = 0;

            //clientes = data;
            var obj = [];
            var clients_input = [];
            var families_input = [];
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
                    //validacion de campos duplicados en clientes
                    for(var i=0 ; i<clients_input.length  ; i++){
                        $('.project').each(function(index){

                        if(index!=i &&  clients_input[i] === $(this).val()){
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

                            if(index !=i && familes_input[i] === $(this).val()){
                                var ind = families_input.indexOf($(this).val());
                                families_input[index]='';
                                console.log($(this).val() + ' - ' + index);
                                $(this).css('border-color','red');
                            }
                        });
                    }


            },200)




        });

    $('#example').dataTable();

}


function onlyNumber(input){
    $(input).keypress(function(e){
        if(e.which != 8 && e.which != 0 && (e.which <48 || e.which >57))
        {
            return false;
        }
    });
}

$(function(){
    var url = window.location.href;
    if(url === "http://localhost/BitBucket/bago_dmkt_rg/public/nueva-solicitud")
    {
        newSolicitude();
    }

    //Expense - s
    
    //Registro de solo números en el campo RUC
    onlyNumber("#ruc");


    // $("#ruc").keypress(function(e){
    //     if(e.which != 8 && e.which != 0 && (e.which <48 || e.which >57))
    //     {
    //         return false;
    //     }
    // });

    // onlyNumber(ruc);
    
    //Búsqueda de Razón Social en la SUNAT una vez introducido el RUC
    $("#ruc").on("focusout",function(){
        var ruc        = $(this).val();
        var l          = Ladda.create(document.getElementById('razon'));
        $("#razon").val(0);
        if(ruc.length<11)
        {
            $("#ruc").addClass("error-incomplete");
            $("#razon").css("color","#5c5c5c");
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
                    $("#ruc").addClass("error-incomplete");
                    $("#razon").val(1);
                    $("#razon").html("No existe el Ruc consultado.");
                }
                else
                {
                    $("#razon").val(2);
                    $("#razon").html(response['razonSocial']);
                }
                l.stop();
                $("#ruc").attr("disabled",false);
            });
        }
    });
    
    //Removiendo las clases de errores
    $("#ruc").on("focus",function(){
        $(this).removeClass("error-incomplete");
        $(this).attr("placeholder",'');
        $(this).val('');
    });

    $("#number_voucher").on("focus",function(){
        $(this).removeClass("error-incomplete");
        $(this).attr("placeholder",'');
        $(this).val('');
    });

    $("#date").on("focus",function(){
        $(this).removeClass("error-incomplete");
        $(this).attr("placeholder",'');
        $(this).val('');
    });

    $("#total").on("focus",function(){
        $(this).removeClass("error-incomplete");
        $(this).attr("placeholder",'');
        $(this).val('');
    });

    var balance;
    $("#save-expense").on("click",function(){
        var type_voucher   = $("#type_voucher").val();
        var ruc            = $("#ruc").val();
        var razon          = $("#razon").text();
        var razon_hide     = $("#razon").val();
        var number_voucher = $("#number_voucher").val();
        var date           = $("#date").val();
        var total          = $("#total").val();
        var error          = 0;
        var deposit        = $("#deposit").val();
        
        if(!ruc)
        {
            $("#ruc").attr("placeholder","No se ha ingresado el RUC.");
            $("#ruc").addClass("error-incomplete");
            error = 1;
        }
        if(razon_hide == 0)
        {
            $("#razon").html("El ruc ingresado no contiene 11 dígitos.");
            error = 1;
        }
        else if(razon_hide == 1)
        {
            $("#razon").removeClass("error-incomplete");
            error = 1;
        }
        if(!number_voucher)
        {
            $("#number_voucher").attr("placeholder","No se ha ingresado el Número de Comprobante.");
            $("#number_voucher").addClass("error-incomplete");
            error = 1;
        }
        if(!date)
        {
            $("#date").attr("placeholder","No se ha ingresado la Fecha de Movimiento.");
            $("#date").addClass("error-incomplete");
            error = 1;
        }
        if(!total)
        {
            $("#total").attr("placeholder","No se ha ingresado el monto total.");
            $("#total").addClass("error-incomplete");
            error = 1;
        }

        deposit = parseInt(deposit.substring(2,deposit.length));
        balance = deposit - total;
        console.log(balance);



        if(error!=0)
        {
            return false;
        }
        else
        {
            var row = "<tr>";
                row+= "<th class='type_voucher'>"+type_voucher+"</th>";
                row+= "<th class='ruc'>"+ruc+"</th>";
                row+= "<th class='razon'>"+razon+"</th>";
                row+= "<th class='number_voucher'>"+number_voucher+"</th>";
                row+= "<th class='date'>"+date+"</th>";
                row+= "<th class='total'>"+total+"</th>";
                row+= "<th><a class='edit-expense' href='#'><span class='glyphicon glyphicon-pencil'></span></a></th>";
                row+= "<th><a class='delete-expense' href='#'><span class='glyphicon glyphicon-remove'></span></a></th>";
                row+= "</tr>";
            $("#table tbody").append(row);
        }




    });
    
    $(document).on("click","#table .delete-expense",function(e){
        e.preventDefault();
        var row = $(this).parent().parent();
        bootbox.confirm("¿Esta seguro que desea eliminar el gasto?", function(result) {
            if(result)
            {
                row.remove();
            }
        });
    });

    $(document).on("click","#table .edit-expense",function(e){
        e.preventDefault();
        $("#table tbody tr").removeClass("select-row");
        $("#type_voucher option").attr("selected",false);
        $("#save-expense ").html("Actualizar");
        var row = $(this).parent().parent();
        row.toggleClass("select-row");
        $.each(row,function(){
            var type_voucher_edit   = $(this).find(".type_voucher").html();
            var ruc_edit            = $(this).find(".ruc").html();
            var razon_edit          = $(this).find(".razon").html();
            var number_voucher_edit = $(this).find(".number_voucher").html();
            var date_edit           = $(this).find(".date_movement").html();
            var total_edit          = $(this).find(".total").html();
            $("#type_voucher option").filter(function(){
                return $(this).text() == type_voucher_edit;
            }).attr('selected', true);
            $("#ruc").val(ruc_edit);
            $("#razon").html(razon_edit).css("color","#5c5c5c");
            $("#number_voucher").val(number_voucher_edit);
            $("#date").val(date_edit);
            $("#total").val(total_edit);
        });
    });
});
