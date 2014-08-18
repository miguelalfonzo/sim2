

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

    newSolicitude();
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
