var server = "http://localhost/BitBucket/bago_dmkt_rg/public/";
//Funciones Globales
function loadingUI(message){
    $.blockUI({ css: {
        border: 'none',
        padding: '15px',
        backgroundColor: '#000',
        '-webkit-border-radius': '10px',
        '-moz-border-radius': '10px',
        opacity: 0.5,
        color: '#fff'
    }, message: '<h2><img style="margin-right: 30px" src="' + server + 'img/spiffygif.gif" >' + message + '</h2>'});
}

function responseUI(message,color){
    $.unblockUI();
    $.blockUI({ css: {
        border: 'none',
        padding: '15px',
        backgroundColor: color,
        '-webkit-border-radius': '10px',
        '-moz-border-radius': '10px',
        opacity: 0.5,
        color: '#fff'
    }, message: '<h2>' + message + '</h2>'});
    setTimeout(function(){
        $.unblockUI();
    },750);
}

$(function(){
    //Vars
    var token          = $("#token").val();
    var deposit        = parseFloat($("#deposit").val());
    var type_money     = $("#type-money").html();
    var proof_type;
    var proof_type_sel;
    var ruc;
    var ruc_hide;
    var razon;
    var razon_hide;
    var razon_edit;
    var number_prefix;
    var number_serie;
    var voucher_number;
    var date;
    var desc_expense;
    var row_item;
    var tot_item = 0;
    var row_items;
    var tot_items = 0;
    var row_expense;
    var tot_expense = 0;
    var row_expenses;
    var tot_expenses = 0;
    var quantity;
    var description;
    var total_item;
    var row_item_first = $("#table-items tbody tr:eq(0)").clone();
    var row_expense_first;
    var data          = {};
    var data_response = {};
    //Submit Events
    $(document).on("click","#token-a",function(e){
        e.preventDefault();
        $(this).parent().parent().find('#form-token').submit();
    });
    //Default events
        //Calculate the IGV loading
        if(parseFloat($(".total-item").val())) calcularIGV();
        if(parseFloat($("#balance").val()) === 0)
        {
            $(".detail-expense").hide();
        }
    //Restrictions on data entry forms
        //only numbers integers
        $("#ruc").numeric({negative:false,decimal:false});
        $("#number-prefix").numeric({negative:false,decimal:false});
        $("#number-serie").numeric({negative:false,decimal:false});
        $("#op-number").numeric({negative:false,decimal:false});
        //only numbers floats
        $("#imp-ser").numeric({negative:false});
        $(".total-item input").numeric({negative:false});
        $(".quantity input").numeric({negative:false});
        $("#igv").numeric({negative:false});
        $("#ret0").numeric({negative:false});
        $("#ret1").numeric({negative:false});
        $("#ret2").numeric({negative:false});
    //Events: Datepicker, Buttons, Keyup.
        //Calcule the IGV and Balance once typed the total amount per item
        $(document).on("keyup",".total-item input",function(){
           calcularIGV();
           calcularBalance();
        });
        //Calcule the IGV and Balance once typed the imp service
        $(document).on("keyup","#imp-ser",function(){
            calcularIGV();
            calcularBalance();
        });
        //Datepicker date all classes
        $(".date").datepicker({
            language: 'es',
            startDate: "{{$data['date']['toDay']}}",
            endDate: $("#last-date").val(),
            format: 'dd/mm/yyyy'
        });
        //selected a date hide the datepicker
        $(".date").on("change",function(){
            $(this).datepicker('hide');
        });
        //Cancel the view button to register expense
        $("#cancel-expense").on("click",function(e){
            e.preventDefault();
            window.location.href = server+"show_rm";
        });
        //Cancel the view button to generate seat solicitude
        $("#cancel-seat-cont").on("click",function(e){
            e.preventDefault();
            window.location.href = server+"show_cont";
        });
        //Record end Solicitude
        $("#finish-expense").on("click",function(e){
            e.preventDefault();
            var balance = parseFloat($("#balance").val());
            if(balance === 0)
            {
                bootbox.confirm("¿Esta seguro que desea Finalizar el registro del gasto?", function(result) {
                    if(result)
                    {
                        window.location.href = server+'end-expense/'+token;
                    }
                });
            }
            else
            {
                bootbox.alert("No puede finalizar el registro del gasto, aún tiene saldo pendiente por registrar.");
            }
        });
        //Generate Seat Solicitude
        $("#seat-solicitude").on("click",function(e){
            e.preventDefault();
            var idsolicitude = $("#idsolicitud").val();
            bootbox.confirm("¿Esta seguro que desea Generar el Asiento Contable?", function(result) {
                if(result)
                {
                   window.location.href = server+'generate-seat-solicitude/'+idsolicitude;
                }
            });
        });
        //Enable deposit
        $("#enable-deposit").on("click",function(e){
            e.preventDefault();
            bootbox.confirm("¿Esta seguro que desea habilitar el depósito?", function(result) {
                if(result)
                {
                    if(validateRet() === true)
                    {
                        $("#form_enable_deposit").submit();
                    }
                    else
                    {
                        console.log("false");
                    }
                }
            });
        });
        //Register Deposit
        $("#register-deposit").on("click",function(e){
            e.preventDefault();
            $("#op_number").val('');
            $("#message-op-number").text('');
            var op_number  = $("#op-number").val();
            data.op_number = op_number;
            data.token     = token;
            if(!op_number)
            {
                $("#message-op-number").text("Ingrese el número de Operación");
            }
            else
            {
                $.post(server+"depositar", {data: JSON.stringify(data)})
                .done(function (data){
                    if(parseInt(data,10) === 1)
                    {
                        window.location.href = server+'show_tes';
                    }
                    else
                    {
                        $("#message-op-number").text("No se ha podido registrar el depósito.");
                    }
                });
            }
        });
        //Empty message in modal register deposit
        $(document).on("focus","#op-number",function(){
            $("#message-op-number").text('');
        });
        //IGV, Imp. Service show if you check Factura
        $("#proof-type").on("change",function(){
            calcularIGV();
            ($(this).val()==='2') ? $(".tot-document").show() : $(".tot-document").hide();
        });
        //Add an element of expense detail
        $("#add-item").on("click",function(e){
            e.preventDefault();
            row_item = $("#table-items").find('.quantity:eq(0)').parent().clone(true,true);
            row_item.find('input').val("");
            $("#table-items tbody").append(row_item);
        });
        //Remove an item from the document register
        $(document).on("click","#table-items .delete-item",function(e){
            e.preventDefault();
            row_item = $(this).parent().parent();
            if($("#table-items .delete-item").length>1)
            {
                row_item.remove();
                calcularIGV();
                calcularBalance();
            }
        });
        //Delete a document already registered
        $(document).on("click","#table-expense .delete-expense",function(e){
            e.preventDefault();
            $("#table-expense tbody tr").removeClass('select-row');
            $(".message-expense").text('').hide();
            row_expense    = $(this).parent().parent();
            tot_expense    = parseFloat($(this).parent().parent().find('.total_expense').html());
            ruc            = $(this).parent().parent().find('.ruc').html();
            voucher_number = $(this).parent().parent().find('.voucher_number').html();
            bootbox.confirm("¿Esta seguro que desea eliminar el gasto?", function(result) {
                if(result)
                {
                    data = {"ruc":ruc,"voucher_number":voucher_number};
                    $.post(server + 'delete-expense', {data: JSON.stringify(data)})
                    .done(function (data) {
                        row_expense.remove();
                        tot_expenses = calculateTot($(".total").parent(),'.total_expense');
                        balance = parseFloat(deposit - tot_expenses);
                        balance = balance.toFixed(2);
                        deleteItems();
                        deleteExpense();
                        $("#balance").val(balance);
                        $("#save-expense").html("Registrar");
                        $(".detail-expense").show();
                        $(".search-ruc").show();
                        $("#ruc-hide").siblings().parent().addClass('input-group');
                    });
                }
            });
        });
        //Edit a document already registered
        $(document).on("click","#table-expense .edit-expense",function(e){
            e.preventDefault();
            $("#ruc-hide").siblings().parent().removeClass('input-group');
            $(".search-ruc").hide();
            $(".message-expense").text('').hide();
            row_expense    = $(this).parent().parent();
            ruc            = $(this).parent().parent().find(".ruc").html();
            voucher_number = $(this).parent().parent().find(".voucher_number").html();
            var total_edit = parseFloat($(this).parent().parent().find(".total_expense").html());
            $("#total-expense").val(total_edit.toFixed(2));
            $("#tot-edit-hidden").val(total_edit.toFixed(2));
            $("#table-expense tbody tr").removeClass("select-row");
            $(this).parent().parent().addClass("select-row");
            $(".message-expense").text('').hide();
            $("#table-items tbody tr").remove();
            $("#save-expense ").html("Actualizar");
            data = {"ruc":ruc,"voucher_number":voucher_number};
            $.ajax({
                type: 'get',
                url: server+'edit-expense',
                dataType: 'json',
                data: {
                    data : JSON.stringify(data)
                },
                beforeSend:function(){
                    loadingUI('Cargando Datos');
                },
                error:function(){
                    $(".message-expense").text('No se pueden recuperar los datos del servidor.').show();
                }
            }).done(function (response){
                setTimeout(function(){
                    responseUI('Editar Gasto','green');
                    $("html, body").animate({scrollTop:200},'500','swing');
                    data_response = JSON.parse(JSON.stringify(response));
                    $("#proof-type").val(data_response.expense.idcomprobante).attr("disabled",true);
                    $("#ruc").val(data_response.expense.ruc).attr("disabled",true);
                    $("#ruc-hide").val(data_response.expense.ruc);
                    $("#razon").text(data_response.expense.razon).css("color","#5c5c5c");
                    $("#razon").attr("data-edit",1);
                    $("#number-prefix").val(data_response.expense.num_prefijo).attr("disabled",true);
                    $("#number-serie").val(data_response.expense.num_serie).attr("disabled",true);
                    date = data_response.date.split('-');
                    date = date[2].substring(0,2)+'/'+date[1]+'/'+date[0];
                    $("#date").val(date);
                    $("#desc-expense").val(data_response.expense.descripcion);
                    $.each(data_response.data,function(index,value){
                        var row_add = row_item_first.clone();
                        row_add.find('.quantity input').val(value.cantidad);
                        row_add.find('.description input').val(value.descripcion);
                        row_add.find('.type-expense').val(value.tipo_gasto);
                        row_add.find('.total-item input').val(value.importe);
                        $("#table-items tbody").append(row_add);
                        $(".total-item input").numeric({negative:false});
                        $(".quantity input").numeric({negative:false});
                    });
                    if(data_response.expense.idcomprobante == '2')
                    {
                        $(".tot-document").show();
                        $("#sub-tot").val(data_response.expense.sub_tot);
                        $("#imp-ser").val(data_response.expense.imp_serv);
                        $("#igv").val(data_response.expense.igv);
                    }
                    else
                    {
                        $(".tot-document").hide();
                        $("#sub-tot").val(0);
                        $("#imp-ser").val(0);
                        $("#igv").val(0);
                    }
                    var row_expenses = $(".total").parent();
                    tot_expenses = calculateTot(row_expenses,'.total_expense');
                    balance = deposit - tot_expenses;
                    $("#balance").val(balance.toFixed(2));
                    $(".detail-expense").show();
                },1000);
            });
        });
        //Validation spending record button
        $("#save-expense").on("click",function(e){
            e.preventDefault();
            $(".message-expense").text('').hide();
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

            proof_type       = $("#proof-type").val();
            proof_type_sel   = $("#proof-type option:selected").text();
            ruc              = $("#ruc").val();
            ruc_hide         = $("#ruc-hide").val();
            razon            = $("#razon").text();
            razon_hide       = $("#razon").val();
            razon_edit       = $("#razon").attr("data-edit");
            number_prefix    = $("#number-prefix").val();
            number_serie     = parseInt($("#number-serie").val(),10);
            voucher_number   = number_prefix+"-"+number_serie;
            date             = $("#date").val();
            desc_expense     = $("#desc-expense").val();
            var balance      = parseFloat($("#balance").val());
            //Validación de errores de cabeceras
            var error = 0;
            if(!ruc)
            {
                $("#ruc").attr("placeholder","No se ha ingresado el RUC.");
                $("#ruc").addClass("error-incomplete");
                error = 1;
            }
            if(ruc != ruc_hide)
            {
                $("#razon").addClass("error-incomplete");
                $("#razon").html("Busque el RUC otra vez.");
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
                $("#razon").html("No existe el ruc consultado.");
                $("#razon").removeClass("error-incomplete");
                error = 1;
            }
            if(!number_prefix)
            {
                $("#number-prefix").attr("placeholder","Nro. Prejifo vacío");
                $("#number-prefix").addClass("error-incomplete");
                error = 1;
            }
            if(!number_serie)
            {
                $("#number-serie").attr("placeholder","Nro. Serie vacío");
                $("#number-serie").addClass("error-incomplete");
                error = 1;
            }
            if(!date)
            {
                $("#date").attr("placeholder","No se ha ingresado la Fecha de Movimiento.");
                $("#date").addClass("error-incomplete");
                error = 1;
            }
            if(!desc_expense)
            {
                $("#desc-expense").attr("placeholder","No se ha ingresado la Descripción.");
                $("#desc-expense").addClass("error-incomplete");
                error = 1;
            }
            if(balance < 0)
            {
                $("#balance").addClass("error-incomplete");
                error = 1;
            }
            //Mostrando errores de cabeceras si es que existen
            if(error !== 0)
            {
                $("html, body").animate({scrollTop:200},'500','swing');
                return false;
            }
            else
            {
                data.token          = token;
                data.proof_type     = proof_type;
                data.ruc            = ruc;
                data.razon          = razon;
                data.number_prefix  = number_prefix;
                data.number_serie   = number_serie;
                data.date_movement  = date;
                data.desc_expense   = desc_expense;

                var error_json = 0;
                //Datos del detalle gastos por items
                quantity = $(".quantity input");
                total_item = $(".total-item input");
                var data_quantity    = validateEmpty(quantity);
                var data_total_item  = validateEmpty(total_item);
                // var arr_type_expense = [];
                var arr_description = [];
                // $.each($(".type-expense"),function(index){
                //     arr_type_expense[index] = $(this).val();
                // });
                $.each($(".description input"),function(index){
                    if($(this).val().length>0)
                    {
                        arr_description[index] = $(this).val();
                    }
                    else
                    {
                        $(this).val('').attr('placeholder','Debe ingresar la descripción');
                        $(this).addClass("error-incomplete");
                        error_json = 1;
                    }
                });

                (data_quantity.length>0) ? data.quantity = data_quantity : error_json = 1;
                data.description = arr_description;
                (data_total_item.length>0) ? data.total_item = data_total_item : error_json = 1;
                // data.type_expense = arr_type_expense;
                //Validando el Objeto JSON
                if(error_json === 0)
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
                    tot_expense = parseFloat($("#total-expense").val());
                    data.total_expense = tot_expense;
                    var tot_expenses = calculateTot($(".total").parent(),'.total_expense');
                    if(tot_expenses >0)
                    {
                        if(validateRuc(ruc) === true)
                        {
                            ajaxExpense(JSON.stringify(data))
                            .done(function(result){
                                if(result == -1)
                                {
                                    responseUI("Documento ya registrado","red");
                                    $(".message-expense").text("El documento ya se encuentra registrado.").show();
                                }
                                else if(result > 0)
                                {
                                    var new_row = $(row).clone(true,true);
                                    var arr_expense = [proof_type_sel,ruc,razon,voucher_number,date,type_money,tot_expense];
                                    newRowExpense(new_row,arr_expense);
                                    deleteItems();
                                    deleteExpense();
                                    responseUI("Gasto Registrado","green");
                                }
                                else
                                {
                                    responseUI("No se ha registrado el gasto","red");
                                    $(".message-expense").text("No se ha podido registrar el detalle de gastos");
                                }
                            })
                            .fail(function(){
                                responseUI("Error del servidor","red");
                                $(".message-expense").text("Ocurrió un error al momento de registrar los gastos");
                            });
                        }
                        else
                        {
                            if(validateVoucher(ruc,voucher_number) === true)
                            {
                                ajaxExpense(JSON.stringify(data))
                                .done(function(result){
                                    if(result == -1)
                                    {
                                        responseUI("Documento ya registrado","red");
                                        $(".message-expense").text("El documento ya se encuentra registrado.").show();
                                    }
                                    else if(result > 0)
                                    {
                                        var new_row = $(row).clone(true,true);
                                        var arr_expense = [proof_type_sel,ruc,razon,voucher_number,date,type_money,tot_expense];
                                        newRowExpense(new_row,arr_expense);
                                        deleteItems();
                                        deleteExpense();
                                        responseUI("Gasto Registrado","green");
                                    }
                                    else
                                    {
                                        responseUI("No se ha registrado el gasto","red");
                                        $(".message-expense").text("No se ha podido registrar el detalle de gastos");
                                    }
                                })
                                .fail(function(){
                                    responseUI("Error del servidor","red");
                                    $(".message-expense").text("Ocurrió un error al momento de registrar los gastos");
                                });
                            }
                            else
                            {
                                if(btn_save === 'Registrar')
                                {
                                    $(".message-expense").text("El documento ya se encuentra registrado.").show();
                                    return false;
                                }
                                else
                                {
                                    var rows = $(".total").parent();
                                    $.each(rows,function(index){
                                        if($(this).find(".voucher_number").html()===voucher_number && $(this).find(".ruc").html()===ruc)
                                        {
                                            $.ajax({
                                                type: 'post',
                                                url: server+'update-expense',
                                                data: {
                                                    data: JSON.stringify(data)
                                                },
                                                beforeSend: function(){
                                                    loadingUI('Actualizando ...');
                                                },
                                                error: function(){
                                                    console.log("error al Actualizar el gasto");
                                                }
                                            }).done( function (result) {
                                                if(result > 0)
                                                {
                                                    deleteItems();
                                                    deleteExpense();
                                                    $(".proof-type:eq("+index+")").text(proof_type_sel);
                                                    $(".ruc:eq("+index+")").text(ruc);
                                                    $(".razon:eq("+index+")").text(razon);
                                                    $(".voucher_number:eq("+index+")").text(voucher_number);
                                                    $(".date_movement:eq("+index+")").text(date);
                                                    $(".total_expense:eq("+index+")").text(tot_expense);
                                                    $("#save-expense").html("Registrar");
                                                    $("#table-expense tbody tr").removeClass("select-row");
                                                    $("")
                                                    responseUI("Gasto Actualizado","green");
                                                }
                                                else
                                                {
                                                    responseUI("No se ha actualizado el gasto","red");
                                                    $(".message-expense").text("No se ha podido actualizar el detalle de gastos");
                                                }
                                            });
                                        }
                                    });
                                }
                            }
                        }
                    }
                    else
                    {
                        ajaxExpense(JSON.stringify(data))
                        .done(function(result){
                            if(result == -1)
                            {
                                responseUI("Documento ya registrado","red");
                                $(".message-expense").text("El documento ya se encuentra registrado.").show();
                            }
                            else if(result > 0)
                            {
                                var new_row = $(row).clone(true,true);
                                var arr_expense = [proof_type_sel,ruc,razon,voucher_number,date,type_money,tot_expense];
                                newRowExpense(new_row,arr_expense);
                                deleteItems();
                                deleteExpense();
                                responseUI("Gasto Registrado","green");
                            }
                            else
                            {
                                responseUI("No se ha registrado el gasto","red");
                                $(".message-expense").text("No se ha podido registrar el detalle de gastos");
                            }
                        })
                        .fail(function(){
                            responseUI("Error del servidor","red");
                            $(".message-expense").text("Ocurrió un error al momento de registrar los gastos");
                        });
                    }
                }
                else
                {
                    $("html, body").animate({scrollTop:200},'500','swing');
                }
            }
            if(balance === 0)
            {
                $(".detail-expense").hide();
            }
            $(".search-ruc").show();
            $("#ruc-hide").siblings().parent().addClass('input-group');
        });
    //Ajax
        //Search Social Reason in SUNAT once introduced the RUC
        $(".search-ruc").on("click",function(){
            $(".message-expense").text("");
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
                    url: server+'consultarRuc',
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
        //Save data to the controller Expense
        function ajaxExpense(jsonExpense)
        {
            return $.ajax({
                type: 'post',
                url: server+'register-expense',
                datatype: 'json',
                asynch: false,
                data: {
                    data : jsonExpense
                },
                beforeSend: function(){
                    loadingUI('Registrando');
                },
                error:function(){
                    alert("No se pueden grabar los datos.");
                }
            });
        }
    //Handling Classes
        //Removing errros
        $(document).on("focus","input",function(){
            $(this).removeClass("error-incomplete").attr("placeholder",'');
            $(".message-expense").hide();
        });
        $(".ret").on("click",function(){
            $(".ret").removeClass("error-incomplete");
        });
        //Calculating sum of rows
        function calculateTot(rows,clas){
            var sum = 0;
            $.each(rows,function(){
                sum += parseFloat($(this).find(clas).html());
            });
            return sum;
        }
    //Functions
        //Add Expense
        function newRowExpense(row,arr)
        {
            row.find(".proof-type").text(arr[0]);
            row.find(".ruc").text(arr[1]);
            row.find(".razon").text(arr[2]);
            row.find(".voucher_number").text(arr[3]);
            row.find(".date_movement").text(arr[4]);
            row.find(".type_money").text(arr[5]);
            row.find(".total_expense").text(arr[6]);
            $("#table-expense tbody").append(row);
        }

        //Delete Items
        function deleteItems()
        {
            $("#table-items tbody tr").remove();
            row_item_first.find('.quantity input').val('');
            row_item_first.find('.description input').val('');
            row_item_first.find('.total-item input').val('');
             $("#table-items tbody").append(row_item_first);
        }
        //Delete data
        function deleteExpense()
        {
            $("#proof-type").val("1").attr("disabled",false);
            $("#ruc").val('').attr('disabled',false);
            $("#ruc-hide").val('');
            $("#razon").html('');
            $("#razon").val(0);
            $("#razon").attr("data-edit",0);
            $("#number-prefix").val('').attr('disabled',false);
            $("#number-serie").val('').attr('disabled',false);
            $("#sub-tot").val(0);
            $("#imp-ser").val(0);
            $("#igv").val(0);
            $(".tot-document").hide();
            $("#total-expense").val('');
            $("#tot-edit-hidden").val('');
            $("#desc-expense").val('');
        }
        //Calculate Balance
        function calcularBalance()
        {
            var balance;
            var deposit = parseFloat($("#deposit").val());
            var tot_expenses = calculateTot($(".total").parent(),'.total_expense');
            var btn_save = $("#save-expense").html();
            var tot_expense = parseFloat($("#total-expense").val());
            var imp_serv = parseFloat($("#imp-ser").val());
            if(!$.isNumeric(imp_serv)) imp_serv = 0;
            if(!$.isNumeric(tot_expense)) tot_expense = 0;
            if(btn_save === "Registrar")
            {
                balance = deposit - tot_expenses - tot_expense;
                $("#balance").val(balance.toFixed(2));
            }
            else
            {
                balance = deposit - tot_expense - tot_expenses + parseFloat($("#tot-edit-hidden").val());
                $("#balance").val(balance.toFixed(2));
            }
            if(balance<0)
            {
                $(".message-expense").html('El monto ingresado supera el depositado.').css("color","red").show();
                $("#balance").css("color","red");
            }
            else
            {
                $("#balance").removeClass('error-incomplete');
                $(".message-expense").hide().css("color","black");
                $("#balance").css("color","#555");
            }
        }
        //Calculate the IGV
        function calcularIGV()
        {
            //Total variables proof
            var total_item = $(".total-item input");
            var sub_total_expense = 0;
            var imp_service = parseFloat($("#imp-ser").val());
            var igv = 0;
            var total_expense = 0;
            $.each(total_item,function(){
                if(!$.isNumeric($(this).val()))
                    total_expense += 0;
                else
                    total_expense += parseFloat($(this).val());
            });
            if(total_expense>0)
            {
                if($("#proof-type").val()==='2')
                {
                    sub_total_expense = total_expense/1.18;
                    igv = sub_total_expense*0.18;
                    if(!imp_service) imp_service = 0;
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
                $("#sub-tot").val(0);
                $("#igv").val(0);
            }
        }
        //Validation and RUC in recorded documents
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
        //Validation RUC and voucher number already registered documents
        function validateVoucher(ruc,voucher_number)
        {
            var rows = $(".total").parent();
            var voucher_number_detail = [];
            $.each(rows,function(index){
                if(ruc === $(this).find(".ruc").html())
                    voucher_number_detail[index] = $(this).find(".voucher_number").html();
            });
            var index = voucher_number_detail.indexOf(voucher_number);
            if(index>=0)
                return false;
            else
                return true;
        }
        //Validation RUC and voucher number already registered documents
        function validateEmpty(selector){
            var data = [];
            var error = 0;
            $.each(selector,function(index){
                if(!($(this).val()) || $(this).val() == 0)
                {
                    $(this).val('');
                    $(this).addClass("error-incomplete");
                    $(this).attr("placeholder","> a 0");
                    $("html, body").animate({scrollTop:400},'500','swing');
                    error = 1;
                }
                else
                {
                    data[index] = parseFloat($(this).val());
                }
            });
            if(error === 0)
            {
                return data;
            }
        }

        //Validation inputs retention for discount background
        function validateRet()
        {
            var arrayRet = [];
            var j        = 0;
            for(var i=0; i<=2; i++)
            {
                if($.isNumeric($("#ret"+i).val()))
                {
                    if(parseFloat($("#ret"+i).val()) === 0)
                    {
                        $("#ret"+i).addClass('error-incomplete');
                        $("#ret"+i).val('');
                        $("#ret"+i).attr('placeholder','Número mayor a 0.');
                        return false;
                    }
                    else
                    {
                        arrayRet[j] = [i,parseFloat($("#ret"+i).val())];
                        j++;
                    }
                }
            }
            if(arrayRet.length > 1)
            {
                for(var i=0; i<arrayRet.length; i++)
                {
                    $.each(arrayRet[i],function(index,value){
                        if(index === 0)
                        {
                            $("#ret"+value).addClass('error-incomplete');
                        }
                    });
                }
                return false;
            }
            else
            {
                return true;
            }
        }
});