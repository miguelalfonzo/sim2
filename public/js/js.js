function onlyNumber(input){
    $(input).keypress(function(e){
        if(e.which != 8 && e.which != 0 && (e.which <48 || e.which >57))
        {
            return false;
        }
    });
}
var server = "http://localhost/BitBucket/bago_dmkt_rg/public/";

$(function(){
    //Restricciónes en la entrada de datos en los formularios
        //Registro de solo números enteros
        onlyNumber("#ruc");
        onlyNumber("#number-proof-one");
        onlyNumber("#number-proof-two");

        //Registro de números flotantes
        $(".total-item input").numeric();
        $(".quantity input").numeric();

    //Evento de Botones
        //Botón cancelar de la vista registro-gasto
        $("#cancel-expense").on("click",function(){
            window.location.href = server+"show_rm";
        });
        //Datepicker a todas las clases date
        $(".date").datepicker({
            language: 'es',
            startDate: "{{$data['date']['toDay']}}",
            endDate: $("#lastDate").val(),
            format: 'dd/mm/yyyy'
        });
        //Al escoger una fecha desaparece el datepicker
        $(".date").on("change",function(){
            $(this).datepicker('hide');
        });

        //Escoge eliminar un documento ya registrado
        $(document).on("click","#table .delete-expense",function(e){
            e.preventDefault();
            var tot_del  = $(this).parent().parent().find(".total").html();
            var row_del  = $(this).parent().parent();
            var deposit  = $("#deposit").val();
            var tot_rows = 0;
            var balance;
            deposit = parseInt(deposit.substring(2,deposit.length),10);
            tot_del = parseInt(tot_del,10);
            
            rows = $(".total").parent();
            $.each(rows,function(){
                tot_rows += parseInt($(this).find(".total").html(),10);
            });
            bootbox.confirm("¿Esta seguro que desea eliminar el gasto?", function(result) {
                if(result)
                {
                    balance = deposit - tot_rows + tot_del;
                    $("#balance").val(balance);
                    row_del.remove();
                }
            });
        });

        //Escoge editar un documento ya registrado
        $(document).on("click","#table .edit-expense",function(e){
            e.preventDefault();
            $("#table tbody tr").removeClass("select-row");
            $("#type_voucher option").attr("selected",false);
            $("#save-expense ").html("Actualizar");
            $("#razon").attr("data-edit",1);

            var deposit  = $("#deposit").val();
            deposit = parseInt(deposit.substring(2,deposit.length),10);

            var tot=0;
            var rows = $(".total").parent();
            $.each(rows,function(){
                tot += parseInt($(this).find(".total").html(),10);
            });

            var row = $(this).parent().parent();
            row.toggleClass("select-row");
            $.each(row,function(){
                var type_voucher_edit   = $(this).find(".type_voucher").html();
                var ruc_edit            = $(this).find(".ruc").html();
                var razon_edit          = $(this).find(".razon").html();
                var number_voucher_edit = $(this).find(".number_voucher").html();
                var date_edit           = $(this).find(".date_movement").html();
                var total_edit          = $(this).find(".total").html();
                $("#tot-edit-hidden").val(parseInt(total_edit,10));
                var balance = deposit - tot + parseInt(total_edit,10);
                $("#balance").val(balance);
                $("#type_voucher option").filter(function(){
                    return $(this).text() == type_voucher_edit;
                }).attr('selected', true);
                $("#type_voucher").attr("readonly",true);
                $("#ruc").val(ruc_edit).attr("readonly",true);
                $("#razon").html(razon_edit).css("color","#5c5c5c");
                $("#number_voucher").val(number_voucher_edit);
                $("#date").val(date_edit);
                $("#total").val(total_edit);
            });
        });

        //Validación del botón registrar gasto
        var rows;
        $("#save-expense").on("click",function(){
            var type_voucher   = $("#type_voucher").val();
            var ruc            = $("#ruc").val();
            var razon          = $("#razon").text();
            var razon_hide     = $("#razon").val();
            var razon_edit     = $("#razon").attr("data-edit");
            var number_voucher = $("#number_voucher").val();
            var date           = $("#date").val();
            var total          = $("#total").val();
            var deposit        = $("#deposit").val();
            var error          = 0;
            var btn_save       = $(this).html();

            if(!ruc)
            {
                $("#ruc").attr("placeholder","No se ha ingresado el RUC.");
                $("#ruc").addClass("error-incomplete");
                error = 1;
            }
            if(razon_hide == 0 && razon_edit == 0)
            {
                $("#razon").html("El ruc ingresado no contiene 11 dígitos.");
                error = 1;
            }
            else if(razon_hide == 1 && razon_edit == 0)
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

            deposit = parseInt(deposit.substring(2,deposit.length),10);
            rows = $(".total").parent();

            var balance  = 0;
            var tot_rows = 0;
            if(rows)
            {
                $.each(rows,function(){
                    tot_rows += parseInt($(this).find(".total").html(),10);
                });
            }

            console.log("deposito:"+deposit);

            if(error!=0)
            {
                return false;
            }
            else
            {
                validateRuc(ruc);
                if(validateRuc(ruc) === true)
                {
                    balance = deposit - tot_rows - parseInt(total,10);
                    console.log(balance);
                    if(balance<0)
                    {
                        alert("El monto ingresado supera el monto depositado");
                        return;
                    }
                    $("#balance").val(balance);
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
                else
                {
                    if(validateVoucher(ruc,number_voucher) === true)
                    {
                        balance = deposit - tot_rows - parseInt(total,10);
                        console.log(balance);
                        if(balance<0)
                        {
                            alert("El monto ingresado supera el monto depositado");
                            return;
                        }
                        $("#balance").val(balance);
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
                    else
                    {
                        if(btn_save === 'Registrar')
                        {
                            alert("El documento ya se encuentra registrado.");
                        }
                        else
                        {
                            $.each(rows,function(index){
                                if($(this).find(".number_voucher").html()===number_voucher)
                                {
                                    console.log("total filas"+tot_rows);
                                    balance = parseInt($("#balance").val(),10);
                                    console.log(balance);
                                    if(total>balance)
                                    {
                                        alert("El monto ingresado supera el monto depositado");
                                        return;
                                    }
                                    else
                                    {
                                        $(".date_movement:eq("+index+")").html(date);
                                        $(".total:eq("+index+")").html(total);
                                        balance = deposit - tot_rows + parseInt($("#tot-edit-hidden").val(),10) - total;
                                        $("#save-expense").html("Registrar");
                                        $("#table tbody tr").removeClass("select-row");
                                        $("#balance").val(balance);
                                        $("#type_voucher").attr("readonly",false);
                                        $("#ruc").attr("readonly",false);
                                    }
                                }
                            });
                        }
                    }
                }
            }
        });
    
    //Ajax
        //Búsqueda de Razón Social en la SUNAT una vez introducido el RUC
        $("#ruc").on("focusout",function(){
            var ruc = $(this).val();
            var l   = Ladda.create(document.getElementById('razon'));
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

    //Manipulación de Clases
        //Removiendo las clases de errores
        $("input").on("focus",function(){
            $(this).removeClass("error-incomplete");
            $(this).attr("placeholder",'');
        });


    //Funciones
        //Validación de RUC en los documentos ya registrados
        function validateRuc(ruc)
        {
            var rows = $(".total").parent();
            var ruc_detail = [];
            $.each(rows,function(index){
                ruc_detail[index] = $(this).find(".ruc").html();
            });
            var index = ruc_detail.indexOf(ruc);
            if(index>=0)
                return false;
            else
                return true;
        }

        //Validación de RUC y Número de comprobante en los documentos ya registrados
        function validateVoucher(ruc,number_voucher)
        {
            var rows = $(".total").parent();
            var number_voucher_detail = [];
            $.each(rows,function(index){
                if(ruc === $(this).find(".ruc").html())
                    number_voucher_detail[index] = $(this).find(".number_voucher").html();
            });
            var index = number_voucher_detail.indexOf(number_voucher);
            if(index>=0)
                return false;
            else
                return true;
        }
});