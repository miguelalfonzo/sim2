var server = "http://localhost/BitBucket/bago_dmkt_rg/public/";

$(function(){
    //Vars
    var idsolicitude   = parseInt($("#idsolicitude").val(),10);
    var deposit        = parseFloat($("#deposit").val());
    var type_money     = $("#type-money").html();
    var balance        = 0;
    var proof_type;
    var proof_type_sel;
    var ruc;
    var ruc_hide;
    var razon;
    var razon_hide;
    var razon_edit;
    var number_proof_one;
    var number_proof_two;
    var voucher_number;
    var date;
    var row_item;
    var tot_item = 0;
    var row_items;
    var tot_items = 0;
    var row_expense;
    var tot_expense = 0;
    var row_expenses = $(".total").parent();
    var tot_expenses = 0;
    var quantity          = $(".quantity input");
    var description       = $(".description input");
    var total_item        = $(".total-item input");
    var row_item_first    = $("#table-items tbody tr:eq(0)").clone();
    var row_expense_first = $("#table-expense tbody tr:eq(0)").clone();
    var data              = {};
    var data_response     = {};
   
    //Submit Event
    $(document).on("click","#token-a",function(e){
        e.preventDefault();
        $(this).parent().parent().find('#form-token').submit();
    });

    //Default events
        //Calculate the IGV loading
        if(parseFloat($(".total-item").val())) calcularIGV();

    //Restrictions on data entry forms
        //only numbers integers
        $("#ruc").numeric({negative:false,decimal:false});
        $("#number-proof-one").numeric({negative:false,decimal:false});
        $("#number-proof-two").numeric({negative:false,decimal:false});

        //only numbers floats
        $("#imp-ser").numeric({negative:false});
        $(".total-item input").numeric({negative:false});
        $(".quantity input").numeric({negative:false});
        $("#igv").numeric({negative:false});

        //Calcule the IGV once typed the total amount per item
        $(document).on("focusout",".total-item input",function(e){
           calcularIGV();
        });

        //Calcule the IGV once typed the imp service
        $("#imp-ser").on("focusout",function(e){
           calcularIGV();
        });

    //Events: Datepicker, Buttons, Keyup.
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
            }
        });

        //Delete a document already registered
        $(document).on("click","#table-expense .delete-expense",function(e){
            e.preventDefault();
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
                        tot_expenses = calculateTot(row_expenses,'.total_expense');
                        balance = parseFloat(deposit - tot_expenses);
                        balance = balance.toFixed(2);
                        $("#balance").val(balance);
                    });
                }
            });
        });

        //Editar un documento ya registrado
        $(document).on("click","#table-expense .edit-expense",function(e){
            e.preventDefault();
            $(".message-expense").text('').hide();
            row_expense    = $(this).parent().parent();
            ruc            = $(this).parent().parent().find(".ruc").html();
            voucher_number = $(this).parent().parent().find(".voucher_number").html();

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
                    data_response = JSON.parse(JSON.stringify(response));
                    $("#proof-type").val(data_response.expense.tipo_comprobante);
                    $("#ruc").val(data_response.expense.ruc).attr("disabled",true);
                    $("#ruc-hide").val(data_response.expense.ruc);
                    $("#razon").text(data_response.expense.razon).css("color","#5c5c5c");
                    $("#razon").attr("data-edit",1);
                    var arr = data_response.expense.num_comprobante.split('-');
                    $("#number-proof-one").val(arr[0]).attr("disabled",true);
                    $("#number-proof-two").val(arr[1]).attr("disabled",true);
                    date = data_response.date.split('-');
                    date = date[2].substring(0,2)+'/'+date[1]+'/'+date[0];
                    $("#date").val(date);
                    $.each(data_response.data,function(index,value){
                        var row_add = row_item_first.clone();
                        row_add.find('.quantity input').val(value.cantidad);
                        row_add.find('.description input').val(value.descripcion);
                        row_add.find('.type-expense').val(value.tipo_gasto);
                        row_add.find('.total-item input').val(value.importe);
                        $("#table-items tbody").append(row_add);
                    });
                    if(data_response.expense.tipo_comprobante == '2')
                    {
                        $(".tot-document").show();
                        $("#sub-tot").val(data_response.expense.sub_tot);
                        $("#imp-ser").val(data_response.expense.imp_serv);
                        $("#igv").val(data_response.expense.igv);
                    }
                    else
                    {
                        $(".tot-document").hide();
                        $("#sub-tot").val('');
                        $("#imp-ser").val('');
                        $("#igv").val('');
                    }
                    tot_expenses = calculateTot(row_expenses,'.total_expense');
                    balance = deposit - tot_expenses;
                    $("#balance").val(balance);
                    $("#total-expense").val(data_response.expense.monto);
                    $("html, body").animate({scrollTop:200},'500','swing');
                },1000);
            });
        });
    
        //Validación del botón registrar gasto
        $("#save-expense").on("click",function(e){
            e.preventDefault();
            $(".message-expense").text('').hide();
            var btn_save = $(this).html();
            var row = "<tr>";
                row+= "<th class='proof-type'></th>";
                row+= "<input class='idgasto' type='hidden'>";
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
            number_proof_one = $("#number-proof-one").val();
            number_proof_two = $("#number-proof-two").val();
            voucher_number   = number_proof_one+'-'+number_proof_two;
            date             = $("#date").val();

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
                $("html, body").animate({scrollTop:200},'500','swing');
                return false;
            }
            else
            {
                data.idsolicitude   = idsolicitude;
                data.proof_type     = proof_type;
                data.ruc            = ruc;
                data.razon          = razon;
                data.voucher_number = voucher_number;
                data.date_movement  = date;

                var error_json = 0;
                //Datos del detalle gastos por items
                var data_quantity    = validateEmpty(quantity);
                var data_description = validateEmpty(description);
                var data_total_item  = validateEmpty(total_item);
                var arr_type_expense = [];
                $.each($(".type-expense"),function(index){
                    arr_type_expense[index] = $(this).val();
                });

                (data_quantity.length>0) ? data.quantity = data_quantity : error_json = 1;
                (data_description.length>0) ? data.description = data_description : error_json = 1;
                (data_total_item.length>0) ? data.total_item = data_total_item : error_json = 1;
                data.type_expense = arr_type_expense;

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

                    tot_expenses = calculateTot(row_expenses,'.total_expense');
                    console.log(tot_expenses);
                    if(tot_expenses >0)
                    {
                        validateRuc(ruc);
                        if(validateRuc(ruc) === true)
                        {
                            balance = deposit - tot_expenses - tot_expense;
                            if(balance<0)
                            {
                                $(".message-expense").html("El monto ingresado supera el monto depositado.");
                                return;
                            }
                            else
                            {
                                ajaxExpense(JSON.stringify(data))
                                .done(function(result){
                                    setTimeout(function(){
                                        $.unblockUI();
                                        var new_row = $(row).clone(true,true);
                                        var detail_expense = $("#table-items tbody tr:eq(0)").clone(true,true);
                                        detail_expense.find('.quantity input').val('');
                                        detail_expense.find('.description input').val('');
                                        detail_expense.find('.total-item input').val('');
                                        if(result != 0)
                                        {
                                            new_row.find(".idgasto").val(result);
                                            new_row.find(".proof-type").text(proof_type_sel);
                                            new_row.find(".ruc").text(ruc);
                                            new_row.find(".razon").text(razon);
                                            new_row.find(".voucher_number").text(voucher_number);
                                            new_row.find(".date_movement").text(date);
                                            new_row.find(".type_money").text(type_money);
                                            new_row.find(".total_expense").text(tot_expense);
                                            $("#balance").val(balance);
                                            $("#table-expense tbody").append(new_row);
                                            $("#table-items tbody tr").remove();
                                            $("#table-items tbody").append(detail_expense);
                                            $("#ruc").val('');
                                            $("#razon").val('');
                                            $("#number-proof-one").val('');
                                            $("#number-proof-two").val('');
                                            $(".tot-document").hide();
                                            $("#total-expense").val('');
                                        }
                                        else
                                        {
                                            $(".message-expense").html("El documento ya se encuentra registrado.");
                                        }
                                    },1000);
                                }).fail(function(){
                                    setTimeout(function(){
                                        $.unblockUI();
                                        $(".message-expense").html("Ocurrió un error al registrar los gastos");
                                    },1000);
                                });
                            }
                        }
                        else
                        {
                            if(validateVoucher(ruc,voucher_number) === true)
                            {
                                balance = deposit - tot_expenses - tot_expense;
                                if(balance<0)
                                {
                                    $(".message-expense").html("El monto ingresado supera el monto depositado.");
                                    return;
                                }
                                else
                                {
                                    ajaxExpense(JSON.stringify(data))
                                    .done(function(result){
                                        setTimeout(function(){
                                            $.unblockUI();
                                            var new_row = $(row).clone(true,true);
                                            var detail_expense = $("#table-items tbody tr:eq(0)").clone(true,true);
                                            detail_expense.find('.quantity input').val('');
                                            detail_expense.find('.description input').val('');
                                            detail_expense.find('.total-item input').val('');
                                            if(result != 0)
                                            {
                                                new_row.find(".idgasto").val(result);
                                                new_row.find(".proof-type").text(proof_type_sel);
                                                new_row.find(".ruc").text(ruc);
                                                new_row.find(".razon").text(razon);
                                                new_row.find(".voucher_number").text(voucher_number);
                                                new_row.find(".date_movement").text(date);
                                                new_row.find(".type_money").text(type_money);
                                                new_row.find(".total_expense").text(tot_expense);
                                                $("#balance").val(balance);
                                                $("#table-expense tbody").append(new_row);
                                                $("#table-items tbody tr").remove();
                                                $("#table-items tbody").append(detail_expense);
                                                $("#ruc").val('');
                                                $("#razon").val('');
                                                $("#number-proof-one").val('');
                                                $("#number-proof-two").val('');
                                                $(".tot-document").hide();
                                                $("#total-expense").val('');
                                            }
                                            else
                                            {
                                                $(".message-expense").html("El documento ya se encuentra registrado.");
                                            }
                                        },1000);

                                    })
                                    .fail(function(){
                                        setTimeout(function(){
                                            $.unblockUI();
                                            $(".message-expense").html("Ocurrió un error al registrar los gastos");
                                        },1000);
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
                                    var rows = $(".total").parent();
                                    $.each(rows,function(index){
                                        if($(this).find(".voucher_number").html()===voucher_number)
                                        {
                                            balance = parseFloat($("#balance").val());
                                            if(total_expense>balance)
                                            {
                                                $(".message-expense").html("El monto ingresado supera el monto depositado.");
                                                return;
                                            }
                                            else
                                            {
                                                data.idgasto = $(".idgasto").val();
                                                $.ajax({
                                                    type: 'post',
                                                    url: 'update-expense',
                                                    data: {
                                                        data: JSON.stringify(data)
                                                    },
                                                    beforeSend: function(){
                                                        $.blockUI({css: {
                                                                border: 'none',
                                                                padding: '15px',
                                                                backgroundColor: '#000',
                                                                '-webkit-border-radius': '10px',
                                                                '-moz-border-radius': '10px',
                                                                opacity: 0.5,
                                                                color: '#fff'
                                                            },message: '<h2><img style="margin-right: 30px" src="' + server + 'img/spiffygif.gif" >' + 'Actualizando' + '</h2>'});
                                                    },
                                                    error: function(){
                                                        console.log("error");
                                                    }
                                                }).done( function (data) {
                                                    setTimeout(function(){
                                                        $.unblockUI();
                                                        var detail_expense = $("#table-items tbody tr:eq(0)").clone(true,true);
                                                        detail_expense.find('.quantity input').val('');
                                                        detail_expense.find('.description input').val('');
                                                        detail_expense.find('.total-item input').val('');
                                                        balance = deposit - tot_expenses + parseFloat($("#tot-edit-hidden").val()) - tot_expense;
                                                        $(".proof-type:eq("+index+")").text(proof_type_sel);
                                                        $(".ruc:eq("+index+")").text(ruc);
                                                        $(".razon:eq("+index+")").text(razon);
                                                        $(".voucher_number:eq("+index+")").text(voucher_number);
                                                        $(".date_movement:eq("+index+")").text(date);
                                                        $(".total_expense:eq("+index+")").text(tot_expense);
                                                        $("#save-expense").html("Registrar");
                                                        $("#table-expense tbody tr").removeClass("select-row");
                                                        $("#balance").val(balance);
                                                        $("#table-items tbody tr").remove();
                                                        $("#table-items tbody").append(detail_expense);
                                                        $("#ruc").val('');
                                                        $("#razon").text('');
                                                        $("#number-proof-one").val('');
                                                        $("#number-proof-two").val('');
                                                        $(".tot-document").hide();
                                                        $("#total-expense").val('');
                                                    },1000);
                                                });
                                            }
                                        }
                                    });
                                }
                            }
                        }
                    }
                    else
                    {
                        balance = parseFloat($("#balance").val());
                        if(tot_expense>balance)
                        {
                            $(".message-expense").html("El monto ingresado supera el monto depositado.");
                        }
                        else
                        {
                            ajaxExpense(JSON.stringify(data))
                            .done(function(result){
                                setTimeout(function(){
                                    $.unblockUI();
                                    var new_row = $(row).clone(true,true);
                                    var detail_expense = $("#table-items tbody tr:eq(0)").clone(true,true);
                                    detail_expense.find('.quantity input').val('');
                                    detail_expense.find('.description input').val('');
                                    detail_expense.find('.total-item input').val('');
                                    if(result != 0)
                                    {
                                        balance = deposit - tot_expense;
                                        new_row.find(".idgasto").val(result);
                                        new_row.find(".proof-type").text(proof_type_sel);
                                        new_row.find(".ruc").text(ruc);
                                        new_row.find(".razon").text(razon);
                                        new_row.find(".voucher_number").text(voucher_number);
                                        new_row.find(".date_movement").text(date);
                                        new_row.find(".type_money").text(type_money);
                                        new_row.find(".total_expense").text(tot_expense);
                                        $("#balance").val(balance);
                                        $("#table-expense tbody").append(new_row);
                                        $("#table-items tbody tr").remove();
                                        $("#table-items tbody").append(detail_expense);
                                        $("#ruc").val('');
                                        $("#razon").val('');
                                        $("#number-proof-one").val('');
                                        $("#number-proof-two").val('');
                                        $(".tot-document").hide();
                                        $("#total-expense").val('');
                                    }
                                    else
                                    {
                                        $(".message-expense").html("El documento ya se encuentra registrado.");
                                    }
                                },1000);
                            })
                            .fail(function(){
                                setTimeout(function(){
                                    $.unblockUI();
                                    $(".message-expense").html("Ocurrió un error al momento de registrar los gastos");
                                },1000);
                            });
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
            }, message: '<h2><img style="margin-right: 30px" src="' + server + 'img/spiffygif.gif" >' + message + '</h2>'});
            setTimeout(function(){
                $.unblockUI();
            },500);
        }    

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
                beforeSend: function(){
                    $.blockUI({css: {
                            border: 'none',
                            padding: '15px',
                            backgroundColor: '#000',
                            '-webkit-border-radius': '10px',
                            '-moz-border-radius': '10px',
                            opacity: 0.5,
                            color: '#fff'
                        },message: '<h2><img style="margin-right: 30px" src="' + server + 'img/spiffygif.gif" >' + 'Registrando' + '</h2>'});
                },
                error:function(){
                    alert("No se pueden grabar los datos.");
                }
            });
        }

        //Calculo de suma de filas
        function calculateTot(rows,clas){
            var sum = 0;
            $.each(rows,function(){
                sum += parseFloat($(this).find(clas).html());
            });
            return sum;
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
                if($(this).val())
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

        //Valida que los datos de un selector no esten vacíos.
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
});