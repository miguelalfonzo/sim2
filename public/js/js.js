var server = "http://localhost/BitBucket/bago_dmkt_rg/public/";

$(function(){

    //Eventos Submit
    $(document).on("click","#token-a",function(e){
        e.preventDefault();
        $(this).parent().parent().find('#form-token').submit();
    });

    //Eventos por default
        //calcula el IGV ni bien carga la página
        if(parseFloat($(".total-item").val()))
        {
            calcularIGV();
        }

    //Restricciónes en la entrada de datos en los formularios
        //Registro de solo números enteros
        $("#ruc").numeric({negative:false,decimal:false});
        $("#number-proof-one").numeric({negative:false,decimal:false});
        $("#number-proof-two").numeric({negative:false,decimal:false});

        //Registro de números flotantes
        $("#imp-ser").numeric({negative:false});
        $(".total-item input").numeric({negative:false});
        $(".quantity input").numeric({negative:false});
        $("#igv").numeric({negative:false});

        //Calcula el IGV una vez digitado el monto total por item
        $(document).on("focusout",".total-item input",function(e){
           calcularIGV();
        });

        //Calcula el IGV una vez digitado el impuest de servicio
        $("#imp-ser").on("focusout",function(e){
           calcularIGV();
        });

    //Evento  Datepicker, Botones, Keyup.
        //Datepicker a todas las clases date
        $(".date").datepicker({
            language: 'es',
            startDate: "{{$data['date']['toDay']}}",
            endDate: $("#last-date").val(),
            format: 'dd/mm/yyyy'
        });
        //Al escoger una fecha desaparece el datepicker
        $(".date").on("change",function(){
            $(this).datepicker('hide');
        });

        //Botón cancelar de la vista registro-gasto
        $("#cancel-expense").on("click",function(){
            window.location.href = server+"show_rm";
        });

        //Mostrar IGV, Imp. Servicio si marca factura
        $("#proof-type").on("change",function(){
            calcularIGV();
            if($(this).val()==='2')
            {
                $(".tot-document").show();
            }
            else
            {
                $(".tot-document").hide();
            }
        });

        //Agregar un item al momento de registrar el gasto
        $("#add-item").on("click",function(e){
            e.preventDefault();
            var rowItem = $("#table-items").find('.quantity:eq(0)').parent().clone(true,true);
            rowItem.find('input').val("");
            $("#table-items tbody").append(rowItem);
        });

        //Eliminar un item del documento a registrar
        $(document).on("click","#table-items .delete-item",function(e){
            e.preventDefault();
            var row_item = $(this).parent().parent();
            if($("#table-items .delete-item").length>1)
            {
                row_item.remove();
            }
            calcularIGV();
        });

        //Eliminar un documento ya registrado
        $(document).on("click","#table-expense .delete-expense",function(e){
            e.preventDefault();
            var tot_del  = $(this).parent().parent().find('.total_expense').html();
            var ruc  = $(this).parent().parent().find('.ruc').html();
            var voucher_number  = $(this).parent().parent().find('.voucher_number').html();
            var row_del  = $(this).parent().parent();
            var deposit  = $("#deposit").val();
            var tot_rows = 0;
            var balance;
            deposit = parseFloat(deposit);
            tot_del = parseFloat(tot_del);

            var rows = $(".total").parent();
            $.each(rows,function(){
                tot_rows += parseFloat($(this).find(".total_expense").html());
            });

            bootbox.confirm("¿Esta seguro que desea eliminar el gasto?", function(result) {
                if(result)
                {
                    var data = {};
                    data.ruc  = ruc;
                    data.voucher_number  = voucher_number;
                    data = JSON.stringify(data);

                    $.post(server + 'delete-expense', {data: data})
                    .done(function (data) {
                        balance = deposit - tot_rows + tot_del;
                        $("#balance").val(balance);
                        row_del.remove();
                    });
                }
            });
        });

        //Escoge editar un documento ya registrado
        $(document).on("click","#table-expense .edit-expense",function(e){
            e.preventDefault();
            var rowEdit = $(this).parent().parent();
            $("#table-expense tbody tr").removeClass("select-row");
            $(this).parent().parent().addClass("select-row");
            var row = $("#table-items tbody tr:eq(0)").clone();
            $("#table-items tbody tr").remove();
            $("#proof-type").attr("disabled",true);
            $("#date").attr("disabled",true);
            $("#save-expense ").html("Actualizar");

            var ruc_edit = $(this).parent().parent().find(".ruc").html();
            var voucher_number_edit = $(this).parent().parent().find(".voucher_number").html();
            data = {"ruc":ruc_edit,"number_voucher":voucher_number_edit};
            $.ajax({
                type: 'get',
                url: 'edit-expense',
                dataType: 'json',
                data: {
                    data : JSON.stringify(data)
                },
                beforeSend:function(){
                    $.blockUI({ css: {
                            border: 'none',
                            padding: '15px',
                            backgroundColor: '#000',
                            '-webkit-border-radius': '10px',
                            '-moz-border-radius': '10px',
                            opacity: 0.5,
                            color: '#fff'
                        }, message: '<h2><img style="margin-right: 30px" src="' + server + 'img/spiffygif.gif" >' + 'Cargando' + '</h2>'});
                },
                error:function(){
                    console.log("error");
                }
            }).done(function (response){
                console.log(response);
                setTimeout(function(){
                    $.unblockUI();
                    var data = JSON.parse(JSON.stringify(response));
                    $.each(data.data,function(index,value){
                        var row_add = row.clone();
                        row_add.find('.quantity input').val(value.cantidad);
                        row_add.find('.description input').val(value.descripcion);
                        row_add.find('.type-expense').val(value.tipo_gasto);
                        row_add.find('.total-item input').val(value.importe);
                        $("#table-items tbody").append(row_add);
                    });
                    var arr = data.expense.num_comprobante.split('-');
                    $("#number-proof-one").val(arr[0]);
                    $("#number-proof-two").val(arr[1]);
                    $("#ruc").val(data.expense.ruc);
                    $("#razon").text(data.expense.razon).css("color","#5c5c5c");
                    $("#razon").attr("data-edit",1);
                    $("#total-expense").val(data.expense.monto);
                    $("#proof-type").val(data.expense.tipo_comprobante);
                    var date = data.date.split('-');
                    date = date[2].substring(0,2)+'/'+date[1]+'/'+date[0];
                    $("#date").val(date);
                    if(data.expense.tipo_comprobante == '2')
                    {
                        $(".tot-document").show();
                        $("#sub-tot").val(data.expense.sub_tot);
                        $("#imp-ser").val(data.expense.imp_serv);
                        $("#igv").val(data.expense.igv);
                    }
                    else
                    {
                        $(".tot-document").hide();
                        $("#sub-tot").val(0);
                        $("#imp-ser").val(0);
                        $("#igv").val(0);
                    }
                    $("html, body").animate({scrollTop:200},'500','swing');
                    var deposit  = $("#deposit").val();
                    deposit = parseFloat(deposit);
                    var tot=0;
                    var rows = $(".total").parent();
                    $.each(rows,function(){
                        tot += parseFloat($(this).find(".total_expense").html());
                    });
                    var total_edit = rowEdit.find('.total_expense').text();
                    var balance = deposit - tot + parseFloat(total_edit);
                    $("#balance").val(balance);
                    $("#tot-edit-hidden").val(parseFloat(total_edit));
                },1000);
            });
        });
    
        //Validación del botón registrar gasto
        $("#save-expense").on("click",function(){
            $(".message-expense").html("");
            var btn_save = $(this).html();
            var row = "<tr>";
                row+= "<th class='proof-type'></th>";
                row+= "<th class='ruc'></th>";
                row+= "<th class='razon'></th>";
                row+= "<th class='voucher_number'></th>";
                row+= "<th class='date_movement'></th>";
                row+= "<th class='total'><span class='type_money'></span>&nbsp;<span class='total_expense'></span></th>";
                row+= "<th><a class='edit-expense' href='#'><span class='glyphicon glyphicon-pencil'></span></a></th>";
                row+= "<th><a class='delete-expense' href='#'><span class='glyphicon glyphicon-remove'></span></a></th>";
                row+= "</tr>";

            //Variables de la cabecera del documento
            var idsolicitude     = parseInt($("#idsolicitude").val(),10);
            var proof_type       = $("#proof-type").val();
            var proof_type_sel   = $("#proof-type option:selected").text();
            var ruc              = $("#ruc").val();
            var razon            = $("#razon").text();
            var razon_hide       = $("#razon").val();
            var razon_edit       = $("#razon").attr("data-edit");
            var number_proof_one = $("#number-proof-one").val();
            var number_proof_two = $("#number-proof-two").val();
            var date             = $("#date").val();
            var type_money       = $("#type-money").html();

            //Datos JSON para pasar al formulario
            var data = {};

            //Validación de errores de cabeceras
            var error = 0;
            if(!ruc)
            {
                $("#ruc").attr("placeholder","No se ha ingresado el RUC.");
                $("#ruc").addClass("error-incomplete");
                error = 1;
            }
            if(razon_hide == 0 && razon_edit == 0)
            {
                $("#razon").addClass("error-incomplete");
                $("#razon").html("No ha buscado la Razón Social.");
                error = 1;
            }
            else if(razon_hide == 1 && razon_edit == 0)
            {
                $("#razon").removeClass("error-incomplete");
                error = 1;
            }
            if(!number_proof_one)
            {
                $("#number-proof-one").attr("placeholder","Nro. Comprobante vacío");
                $("#number-proof-one").addClass("error-incomplete");
                error = 1;
            }
            if(!number_proof_two)
            {
                $("#number-proof-two").attr("placeholder","Nro. Comprobante vacío");
                $("#number-proof-two").addClass("error-incomplete");
                error = 1;
            }
            if(!date)
            {
                $("#date").attr("placeholder","No se ha ingresado la Fecha de Movimiento.");
                $("#date").addClass("error-incomplete");
                error = 1;
            }

            //Mostrando errores de cabeceras si es que existen
            if(error !== 0)
            {
                return false;
            }
            else
            {
                data.idsolicitude     = idsolicitude;
                data.proof_type       = proof_type;
                data.ruc              = ruc;
                data.razon            = razon;
                data.number_proof     = number_proof_one+'-'+number_proof_two;
                data.date_movement    = date;

                var error_json = 0;
                
                //Variables del detalle de items
                var quantity    = $(".quantity input");
                var description = $(".description input");
                var total_item  = $(".total-item input");

                //Datos del detalle gastos por items
                var data_quantity    = validateEmpty(quantity);
                var data_description = validateEmpty(description);
                var data_total_item  = validateEmpty(total_item);

                //Validación de gastos detallados
                var index;
                var auxq = [];
                var auxt = [];

                if(data_quantity)
                {
                    for(index = 0; index<data_quantity.length;index++)
                    {
                        if(!$.isNumeric(data_quantity[index]))
                        {
                            $(".quantity input:eq("+index+")").addClass("error-incomplete");
                            $(".quantity input:eq("+index+")").val("Vacío");
                            error_json = 1;
                            return;
                        }
                        else
                        {
                            if(data_quantity[index]<=0)
                            {
                                $(".quantity input:eq("+index+")").addClass("error-incomplete");
                                $(".quantity input:eq("+index+")").val("> a 0");
                                error_json = 1;
                                return;
                            }
                            else
                            {
                                auxq[index] = parseFloat(data_quantity[index]);
                            }
                        }
                    }
                    if(auxq)
                    {
                        data.quantity = auxq;
                    }
                }
                else
                {
                    error_json = 1;
                }

                if(data_description)
                {
                    data.description = data_description;
                }
                else
                {
                     error_json = 1;
                }

                var arr_type_expense = [];
                $.each($(".type-expense"),function(index){
                    arr_type_expense[index] = $(this).val();
                });
                data.type_expense = arr_type_expense;

                if(data_total_item)
                {
                    for(index = 0; index<data_total_item.length;index++)
                    {
                        if(!$.isNumeric(data_total_item[index]))
                        {
                            $(".total-item input:eq("+index+")").addClass("error-incomplete");
                            $(".total-item input:eq("+index+")").val("Vacío");
                            error_json = 1;
                            return;
                        }
                        else
                        {
                            if(data_total_item[index]<=0)
                            {
                                $(".total-item input:eq("+index+")").addClass("error-incomplete");
                                $(".total-item input:eq("+index+")").val("> a 0");
                                error_json = 1;
                                return;
                            }
                            else
                            {
                                auxt[index] = parseFloat(data_total_item[index]);
                            }
                        }
                    }
                    if(auxt)
                    {
                        data.total_item = auxt;
                    }
                }
                else
                {
                    error_json = 1;
                }

                //Validando el Objeto JSON
                if(error_json === 0)
                {
                    var ruc_hide = $("#ruc-hide").val();
                    if(ruc != ruc_hide)
                    {
                        $(".message-expense").html("Busque el RUC otra vez.");
                    }
                    else
                    {
                        if(proof_type == '2')
                        {
                            var sub_total_expense = parseFloat($("#sub-tot").val());
                            var imp_service       = parseFloat($("#imp-ser").val());
                            var igv               = parseFloat($("#igv").val());

                            if(isNaN(sub_total_expense)) sub_total_expense = 0;
                            if(isNaN(imp_service)) imp_service = 0;
                            if(isNaN(igv)) igv = 0;

                            data.sub_total_expense = sub_total_expense;
                            data.imp_service = imp_service;
                            data.igv = igv;
                        }
                        
                        var total_expense = parseFloat($("#total-expense").val());
                        data.total_expense = total_expense;

                        //Validando los documentos registrados.
                        var deposit  = parseFloat($("#deposit").val());
                        var rows_val = $(".total").val();
                        if(typeof rows_val != "undefined") rows = $(".total").parent();
                        var balance  = 0;
                        var tot_rows = 0;
                        var number_voucher = number_proof_one+'-'+number_proof_two;

                        if(typeof rows != "undefined")
                        {
                            $.each(rows,function(){
                                tot_rows += parseFloat($(this).find(".total_expense").html());
                            });
                            if(tot_rows>0)
                            {
                                validateRuc(ruc);
                                if(validateRuc(ruc) === true)
                                {
                                    balance = deposit - tot_rows - total_expense;
                                    if(balance<0)
                                    {
                                        $(".message-expense").html("El monto ingresado supera el monto depositado.");
                                        // alert("El monto ingresado supera el monto depositado");
                                        return;
                                    }
                                    else
                                    {
                                        ajaxExpense(JSON.stringify(data)).done(function(result){
                                            var new_row = $(row).clone(true,true);
                                            $(new_row).find(".proof-type").text(proof_type_sel);
                                            $(new_row).find(".ruc").text(ruc);
                                            $(new_row).find(".razon").text(razon);
                                            $(new_row).find(".voucher_number").text(number_voucher);
                                            $(new_row).find(".date_movement").text(date);
                                            $(new_row).find(".type_money").text(type_money);
                                            $(new_row).find(".total_expense").text(total_expense);
                                            $("#table-expense tbody").append(new_row);
                                            $("#balance").val(balance);
                                        }).fail(function(){
                                            console.log("error");
                                        });
                                    }
                                }
                                else
                                {
                                    if(validateVoucher(ruc,number_voucher) === true)
                                    {
                                        balance = deposit - tot_rows - parseFloat(total_expense);
                                        if(balance<0)
                                        {
                                            $(".message-expense").html("El monto ingresado supera el monto depositado.");
                                            // alert("El monto ingresado supera el monto depositado");
                                            return;
                                        }
                                        else
                                        {
                                            ajaxExpense(JSON.stringify(data)).done(function(result){
                                                console.log(result);
                                                var new_row = $(row).clone(true,true);
                                                $(new_row).find(".proof-type").text(proof_type_sel);
                                                $(new_row).find(".ruc").text(ruc);
                                                $(new_row).find(".razon").text(razon);
                                                $(new_row).find(".voucher_number").text(number_voucher);
                                                $(new_row).find(".date_movement").text(date);
                                                $(new_row).find(".type_money").text(type_money);
                                                $(new_row).find(".total_expense").text(total_expense);
                                                $("#table-expense tbody").append(new_row);
                                                $("#balance").val(balance);
                                            }).fail(function(){
                                                console.log("error");
                                            });
                                        }
                                    }
                                    else
                                    {
                                        if(btn_save === 'Registrar')
                                        {
                                            $(".message-expense").html("El documento ya se encuentra registrado.");
                                        }
                                        else
                                        {
                                            $.each(rows,function(index){
                                                if($(this).find(".number_voucher").html()===number_voucher)
                                                {
                                                    balance = parseFloat($("#balance").val());
                                                    if(total_expense>balance)
                                                    {
                                                        $(".message-expense").html("El monto ingresado supera el monto depositado.");
                                                        // alert("El monto ingresado supera el monto depositado");
                                                        return;
                                                    }
                                                    else
                                                    {
                                                        $(".date_movement:eq("+index+")").html(date);
                                                        $(".total:eq("+index+")").html(total_expense);
                                                        balance = deposit - tot_rows + parseFloat($("#tot-edit-hidden").val()) - total_expense;
                                                        $("#save-expense").html("Registrar");
                                                        $("#table tbody tr").removeClass("select-row");
                                                        $("#balance").val(balance);
                                                        $("#number_proof_one").attr("readonly",false);
                                                        $("#number_proof_two").attr("readonly",false);
                                                        $("#ruc").attr("readonly",false);
                                                    }
                                                }
                                            });
                                        }
                                    }
                                }
                            }
                        }
                        else
                        {
                            balance = parseFloat($("#balance").val());
                            if(total_expense>balance)
                            {
                                $(".message-expense").html("El monto ingresado supera el monto depositado.");
                                // alert("El monto ingresado supera el monto depositado");
                                return;
                            }
                            else
                            {
                                ajaxExpense(JSON.stringify(data)).done(function(result){
                                    console.log(result);
                                    var new_row = $(row).clone(true,true);
                                    balance = deposit - total_expense;
                                    $(new_row).find(".proof-type").text(proof_type_sel);
                                    $(new_row).find(".ruc").text(ruc);
                                    $(new_row).find(".razon").text(razon);
                                    $(new_row).find(".voucher_number").text(number_voucher);
                                    $(new_row).find(".date_movement").text(date);
                                    $(new_row).find(".type_money").text(type_money);
                                    $(new_row).find(".total_expense").text(total_expense);
                                    $("#table-expense tbody").append(new_row);
                                    $("#balance").val(balance);
                                }).fail(function(){
                                    console.log("error");
                                });
                            }
                        }
                    }
                }
            }
        });

    //Ajax
        //Búsqueda de Razón Social en la SUNAT una vez introducido el RUC
        $(".search-ruc").on("click",function(){
            $(".message-expense").html("");
            $("#razon").removeClass('error-incomplete');
            var ruc = $("#ruc").val();
            $("#razon").html("Buscando Razón Social...");
            $("#razon").val(0);
            if(ruc.length===0)
            {
                $("#ruc").addClass("error-incomplete");
                $("#razon").css("color","#5c5c5c");
                $("#razon").html("No ha ingresado el RUC.");
            }
            else if(ruc.length>0 && ruc.length<11)
            {
                $("#ruc").addClass("error-incomplete");
                $("#razon").css("color","#5c5c5c");
                $("#razon").html("El RUC ingresado no contiene 11 dígitos.");
            }
            else
            {
                var l = Ladda.create(document.getElementById('razon'));
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
                        $("#ruc-hide").val(ruc);
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
        //Grabado de datos en el controlador de Gastos
        function ajaxExpense(jsonExpense)
        {
            return $.ajax({
                type: 'post',
                url: 'register-expense',
                datatype: 'json',
                asynch: false,
                data: {
                    data : jsonExpense
                },
                beforeSend:function(){
                    console.log("Antes de ir al ajax");
                },
                error:function(){
                    alert("No se pueden grabar los datos.");
                }
            });
        }
        
        //Calculo del IGV
        function calcularIGV()
        {
            //Variables totales del comprobante
            var total_item = $(".total-item input");
            var sub_total_expense = 0;
            var imp_service = parseFloat($("#imp-ser").val());
            var igv = 0;
            var total_expense = 0;

            $.each(total_item,function(){
                total_expense += parseFloat($(this).val());
            });

            if(total_expense>0)
            {
                if($("#proof-type").val()==='2')
                {
                    igv = total_expense*0.18;
                    if(!imp_service)
                        imp_service = 0;
                    sub_total_expense = (total_expense+igv)/1.18;
                    total_expense = sub_total_expense + igv + imp_service;
                    $("#sub-tot").val(sub_total_expense.toFixed(2));
                    $("#igv").val(igv.toFixed(2));
                    $("#total-expense").val(total_expense.toFixed(2));
                }
                else
                {
                    $("#total-expense").val(total_expense.toFixed(2));
                }
            }
            else
            {
                $("#total-expense").val('');
            }
        }

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
                    number_voucher_detail[index] = $(this).find(".voucher_number").html();
            });
            var index = number_voucher_detail.indexOf(number_voucher);
            if(index>=0)
                return false;
            else
                return true;
        }

        //Valida que los datos de un selector no esten vacíos.
        function validateEmpty(selector){
            var data = [];
            var error = 0;
            $.each(selector,function(index){
                if(!($(this).val()))
                {
                    $(this).addClass("error-incomplete");
                    $(this).attr("placeholder","Vacío");
                    error = 1;
                }
                else
                {
                    data[index] = $(this).val();
                }
            });
            if(error === 0)
            {
                return data;
            }
        }
});