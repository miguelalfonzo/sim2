var server = "";
var pathname = document.location.pathname;
var pathnameArray= pathname.split("/public/");

server =  pathnameArray.length >0 ? pathnameArray[0]+"/public/" : "";
var IGV = 0.18;
//Funciones Globales
var datef = new Date();
var dateactual = (datef.getMonth()+1)+'-'+datef.getFullYear();
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
    },2000);
}

$(function(){
    //Vars
    var token          = $('input[name=token]').val();
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
    var depositado     = $('#amount');
    var deposit        = parseFloat(depositado.val());
    var row_expense_first;
    var data          = {};
    var data_response = {};
    //Submit Events
    $(document).on("click","#token-reg-expense",function(e){
        e.preventDefault();
        var token = $(this).attr('data-url');
        if($(this).attr('data-cont'))
            window.location.href = server+'revisar-gasto/'+token;
        else
            window.location.href = server+'registrar-gasto/'+token;
    });
    $(document).on("click","#token-solicitude",function(e){
        e.preventDefault();
        var token = $(this).attr('data-url');
        window.location.href = server+'generar-asiento-solicitud/'+token;
    });
    $(document).on("click","#token-expense",function(e){
        e.preventDefault();
        var token = $(this).attr('data-url');
        window.location.href = server+'generar-asiento-gasto/'+token;
    });
    
    //Default events
        //Calculate the IGV loading
        if(parseFloat($(".total-item").val())) calcularIGV();
        if(parseFloat($("#balance").val()) === 0)
            $(".detail-expense").hide();
    //Restrictions on data entry forms
        //only numbers integers
        $("#ruc").numeric({negative:false,decimal:false});
        $("#number-prefix").numeric({negative:false,decimal:false});
        $("#number-serie").numeric({negative:false,decimal:false});
        $("#op-number").numeric({negative:false,decimal:false});
        $("#number_account").numeric({negative:false,decimal:false});
        //only numbers floats
        $("#imp-ser").numeric({negative:false});
        $(".total-item input").numeric({negative:false});
        $(".quantity input").numeric({negative:false});
        $("#igv").numeric({negative:false});
        $("#ret0").numeric({negative:false});
        $("#ret1").numeric({negative:false});
        $("#ret2").numeric({negative:false});
        $("#total").numeric({negative:false});
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
        // Datepicker date all classes
        // FIX COLOR IN INPUTS READONLY
        $('.date>input[readonly]').css('background-color', "#fff");
        if(!($('.date>input:not([disabled])').length == 0)){
            //var toDate = $(".date>input").val();
            var toDate = "";
            $(".date").datepicker({
                language: 'es',
                startDate: toDate == "" ? "{{$data['date']['toDay']}}" : toDate,
                endDate: $("#last-date").val(),
                format: 'dd/mm/yyyy'
            });
            //selected a date hide the datepicker
            $(".date").on("change",function(){
                $(this).datepicker('hide');
            });
            //$(".date").datepicker( "setDate" , typeof($("#last-date").val()) != 'undefined' ? $("#last-date").val() : toDate);
        }
        //Cancel the view button to register expense
        $("#cancel-expense").on("click",function(e){
            e.preventDefault();
            if (typeUser.value == "AG")
                window.location.href = server + "registrar-fondo";
            else
                window.location.href = server+"show_user";
        });
        //Cancel the view button to generate seat solicitude
        $("#cancel-seat-cont").on("click",function(e){
            e.preventDefault();
            window.location.href = server+"show_user";
        });
        //Add row seat solicitude
        $("#add-seat-solicitude").on("click", function(e)
        {
            var button = this;
            e.preventDefault();
            if(!$("#name_account").val())
                $("#name_account").attr("placeholder","No ha ingresado la cuenta.").addClass("error-incomplete");
            if(!$("#number_account").val())
                $("#number_account").attr("placeholder","No ha ingresado el Número de la cuenta.").addClass("error-incomplete");
            if(!$("#total").val())
                $("#total").attr("placeholder","No ha ingresado el Importe.").addClass("error-incomplete");
            if( $("#total").val() && $("#number_account").val() && $("#name_account").val())
            {
                if($("#add-seat-solicitude").text() === 'Actualizar Detalle')
                {
                    bootbox.confirm("¿Esta seguro que desea Actualizar el registro del Gasto?", function(result) 
                    { 
                        if (result) 
                        {
                            $("#table-seat-solicitude tbody tr").each(function(index)
                            {
                                if($(this).hasClass('select-row'))
                                {
                                    $('.name_account:eq('+index+')').text($("#name_account").val());
                                    $('.number_account:eq('+index+')').text($("#number_account").val());
                                    $('.dc:eq('+index+')').text($("#dc").val());
                                    $('.total:eq('+index+')').text($("#total").val());
                                }
                            });
                        }
                        cleanRegistro(button);
                    });
                }
                else
                {
                    bootbox.confirm("¿Esta seguro que desea Agregar este Registro?", function(result) 
                    { 
                        if (result) 
                        {
                            var row_seat = $("#table-seat-solicitude tbody tr:first").clone(true,true);
                            row_seat.find('.name_account').text($("#name_account").val());
                            row_seat.find('.number_account').text($("#number_account").val());
                            row_seat.find('.dc').text($("#dc").val());
                            row_seat.find('.total').text($("#total").val());
                            $("#table-seat-solicitude tbody").append(row_seat);
                        }
                        cleanRegistro(button);
                    });
                }    
            }
        });

        //Registro de Asiento de Anticipo
        function cleanRegistro (button)
        {
            $("#name_account").val('');
            $("#number_account").val('');
            $("#total").val('');
            $("#table-seat-solicitude tbody tr").removeClass('select-row');
            $(button).html('Agregar Detalle');
        } 

        //Record end Solicitude
        $("#finish-expense").on("click",function(e){
            e.preventDefault();
            var type = $(this).attr('data-type');
            var idfondo = $(this).attr('data-idfondo');
            var balance = parseFloat($("#balance").val());
            if(balance >= 0)
            {
                bootbox.confirm('¿Esta seguro que desea Finalizar el registro del gasto?', function(result) 
                {
                    if(result)
                    {
                        $.ajax(
                        {
                            type: 'post',
                            url: server + 'end-expense',
                            data: 
                            {
                                _token : $('input[name=_token]').val(),
                                token  : token
                            }
                        }).fail( function( statusCode , errorThrow )
                        {
                            bootbox.alert(statusCode + ': ' + errorThrow );
                        }).done( function ( data ) 
                        {
                            if ( data.Status == 'Ok' )
                            {
                                bootbox.alert('<h4 class="green">Gasto Registrado</h4>' , function()
                                {
                                    window.location.href = server + 'show_user';
                                });
                            }
                            else
                                bootbox.alert('<h4 class="red">' + data.Status + ': ' + data.Description + '</h4>');
                        });
                    }
                });
            }
            else
                bootbox.alert("<p style='color: red'>No puede finalizar el registro del gasto, el monto registrado supera al depositado.</p>");
        });
        //Generate Seat Solicitude
        $("#seat-solicitude").on("click",function(e){
            e.preventDefault();
            var data = {};
            data._token = $("input[name=_token]").val();
            var name_account = [];
            var number_account = [];
            var dc = [];
            var total = [];
            var leyenda = [];
            $("#table-seat-solicitude tbody .number_account").each(function(index){
                number_account[index] = $(this).text().trim();
            });
            $("#table-seat-solicitude tbody .dc").each(function(index){
                dc[index] = $(this).text().trim();
            });
            $("#table-seat-solicitude tbody .total").each(function(index){
                total[index] = parseFloat($(this).text());
            });
            data.number_account = number_account;
            data.dc = dc;
            data.total = total;
            data.leyenda = $("#table-seat-solicitude tbody .leyenda:eq(0)").text();
            var url = server+'generate-seat-solicitude';
            if($(this).data('url'))
            {
                url = server + 'generate-seat-fondo';
                data.idfondo = parseInt($("#idfondo").val(),10);
            }
            else
                data.idsolicitude = parseInt($("input[name=idsolicitude]").val(),10);
            bootbox.confirm("¿Esta seguro que desea Generar el Asiento Contable?", function(result) 
            {
                if(result)
                {
                    $.post(url, data)
                    .done( function (data)
                    {
                        console.log(data);
                        if(data.Status == 'Ok')
                        {
                            bootbox.alert("<h4 class='green'>Se generó el asiento contable correctamente.</h4>", function()
                            {
                                window.location.href = server+'show_user';
                            });
                        }
                        else
                            bootbox.alert("<h4 class='red'>" + data.Status + ": " + data.Description + "</h4>");
                    });
                }
            });
        });
        //Delete row seat solicitude
        $(document).on("click","#table-seat-solicitude .delete-seat-solicitude",function(e){
            e.preventDefault();
            var element = $(this);
            bootbox.confirm({
                message: '¿Esta seguro que desea Eliminar el registro?',
                buttons: {
                    'cancel': { label: 'Cancelar', className: 'btn-primary' },
                    'confirm': { label: 'Eliminar', className: 'btn-default' }
                },
                callback: function(result) {
                    if (result) 
                    {
                        element.parent().parent().remove();
                        if ( !$('#add-seat-solicitude').length == 0 )
                            cleanRegistro($('#add-seat-solicitude'));
                    }
                }
            });
        });
        //Generate Seat Expense
        $("#seat-expense").on("click",function(e){
            e.preventDefault();
            var idsolicitude = $("#idsolicitud").val();
            bootbox.confirm("¿Esta seguro que desea Generar el Asiento Contable?", function(result) {
                if(result)
                {
                    data.idsolicitude = idsolicitude;
                    data._token       = $("input[name=_token]").val();
                    $.post(server+'generate-seat-expense', data)
                    .done( function (data){
                        if(data == 1)
                        {
                            bootbox.alert("<p class='green'>Se generó el asiento contable correctamente.</p>", function(){
                                window.location.href = server+'show_user';
                            });
                        }
                        else
                            bootbox.alert("<p class='red'>Error, no se puede generar el asiento contable.</p>");
                    });
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
                        data._token       = $("input[name=_token]").val();
                        data.idsolicitude = $("input[name=idsolicitude]").val();
                        data.idretencion  = $("select[name=retencion]").val();
                        data.monto_retencion = $("input[name=monto_retencion]").val();
                        $.post(server+'enable-deposit', data)
                        .done(function (data){
                        if(data.Status == "Ok")
                        {
                            bootbox.alert("<p class='green'>Se habilito el depósito correctamente.</p>", function(){
                                window.location.href = server+'show_user';
                            });
                        }
                        else
                            bootbox.alert("<h4 class='red'>" + data.Status + ": " + data.Description +  "</h4>");
                        });
                    }
                    else
                        console.log("false");
                }
            });
        });
        //Deposit Fondo
        $(document).on('click','.deposit-fondo',function()
        {
            var idfondo = $(this).attr('data-idfondo');
            $('#idfondo').val(idfondo);
        });

        
            
        //Empty message in modal register deposit
        $(document).on("focus","#op-number",function(){
            $("#message-op-number").text('');
        });
        //IGV, Imp. Service show if you check Factura
        $("#proof-type").on("change",function(){
            proofType( this );
        });

        function proofType( element )
        {
            calcularIGV();
            $("#ruc").prop('disabled', false);
            $("#number-prefix").prop('disabled', false);
            $("#number-serie").prop('disabled', false);
            //console.log(this);
            console.log(element);
            var proof_type_sel = $(element).val();
            //console.log(val);
            if(proof_type_sel === '1' || proof_type_sel === '4' )//|| proof_type_sel === '6')
            {
                $(".tot-document").show();
                $('#dreparo').show();
            }
            else
            {
                $(".tot-document").hide();
                $('#dreparo').hide();
            }
            if(proof_type_sel === '7')
            {
                // DESHABILITA INPUTS + QUITAR MARCAR DE ERRORES ANTERIORES
                $("#ruc").prop('disabled', true);
                $("#ruc").removeClass("error-incomplete");
                $("#ruc").attr('placeholder', "");
                $("#number-prefix").prop('disabled', true);
                $("#number-prefix").removeClass("error-incomplete");
                $("#number-prefix").attr('placeholder', "");
                $("#number-serie").prop('disabled', true);
                $("#number-serie").removeClass("error-incomplete");
                $("#number-serie").attr('placeholder', "");
                $("#razon").removeClass("error-incomplete");
                $("#razon").attr('placeholder', "");

                // LIMPIA INPUTS
                $("#ruc").val("");
                $("#ruc-hide").val("");
                $("#number-prefix").val("");
                $("#number-serie").val("");
                $("#razon").text("");
            }
        }

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
        $(document).on("click","#table-expense .delete-expense", function(e)
        {
            e.preventDefault();
            var elementTr = $(this).parent().parent();
            var elementTrId = elementTr.attr("data-id");

            $("#table-expense tbody tr").removeClass('select-row');
            $(".message-expense").text('').hide();
            var tot_expense    = parseFloat(elementTr.find('.total_expense').html());
            
            // row_expense    = $(this).parent().parent();
            // ruc            = $(this).parent().parent().find('.ruc').html();
            // voucher_number = $(this).parent().parent().find('.voucher_number').html();
            
            bootbox.confirm("¿Esta seguro que desea eliminar el gasto?", function(result) 
            {
                if( result )
                {
                    // data = {"ruc":ruc,"voucher_number":voucher_number, "_token":$("input[name=_token]").val()};
                    data = {"gastoId": elementTrId, "_token":$("input[name=_token]").val()};
                    $.post(server + 'delete-expense', data)
                    .done(function (data) 
                    {
                        if ( data.Status == 'Ok' )
                        {
                            rechargeExpense().done( function ( data ) 
                            {
                                if ( data.Status == 'Ok' )
                                {
                                    $('#table-expense').html(data.Data);
                                    $("#save-expense").html("Registrar");
                                    //$(".detail-expense").show();
                                    //$(".search-ruc").show();
                                    //$("#ruc-hide").siblings().parent().addClass('input-group');   
                                    tot_expenses = calculateTot($(".total").parent(),'.total_expense');
                                    balance = parseFloat(deposit - tot_expenses);
                                    balance = balance.toFixed(2);
                                    $("#balance").val(balance);
                                    deleteItems();
                                    deleteExpense();
                                }
                                else
                                    bootbox.alert('<h4 class="red">No se ha podido recargar los gastos registrados, recarge la pagina</h4>');
                            });
                        }
                        else
                            bootbox.alert('<h4>' + data.Status + ': ' + data.Description + '</h4>');
                    });
                }
            });
        });

        function rechargeExpense()
        {
            return $.ajax({
            url  : server + 'get-expenses',
            type : 'POST',
            data : 
            {
                _token      : $('input[name=_token]').val() ,
                idsolicitud : $('input[name=idsolicitude]').val()
            }
            }).fail( function ( statusCode , errorThrown )
            {
                ajaxError( statusCode , errorThrown );
            });
        }

        $(document).on("click","#table-seat-solicitude .edit-seat-solicitude",function(e){
            e.preventDefault();
            $("#table-seat-solicitude tbody tr").removeClass("select-row");
            $("#add-seat-solicitude").html('Actualizar Detalle');
            var row_edit = $(this).parent().parent();
            $("#name_account").val(row_edit.find('.name_account').text());
            $("#number_account").val(row_edit.find('.number_account').text());
            $("#total").val(row_edit.find('.total').text());
            $("#dc").val(row_edit.find('.dc').text());
            row_edit.addClass('select-row');
        });
        //Edit a document already registered
        $(document).on("click","#table-expense .edit-expense", function( e )
        {
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
            //data = {"ruc":ruc,"voucher_number":voucher_number};
            var ruta = 'edit-expense';
            $.ajax({
                type : 'post' ,
                url  : server + ruta ,
                data : 
                {
                    _token  : $('input[name=_token]').val(),
                    idgasto : row_expense.attr('data-id')
                },
                beforeSend:function(){
                    loadingUI('Cargando Datos');
                },
                error:function(){
                    console.log("error");
                    $.blockUI();
                    $(".message-expense").text('No se pueden recuperar los datos del servidor.').show();
                }
            }).done( function ( response )
            {
                setTimeout(function()
                {
                    responseUI('Editar Gasto','green');
                    $("html, body").animate({scrollTop:200},'500','swing');
                    data_response = JSON.parse(JSON.stringify(response));
                    $('input[name=idgasto]').val(data_response.expense.id)
                    $("#proof-type").val(data_response.expense.idcomprobante).attr("disabled",true);
                    $("#ruc").val(data_response.expense.ruc).attr("disabled",true);
                    $("#ruc-hide").val(data_response.expense.ruc);
                    $("#razon").text(data_response.expense.razon).css("color","#5c5c5c");
                    $("#razon").attr("data-edit",1);
                    $("#number-prefix").val(data_response.expense.num_prefijo).attr("disabled",true);
                    $("#number-serie").val(data_response.expense.num_serie).attr("disabled",true);

					if ( !$('#dreparo').find('input[name=reparo]').length == 0)
						if ( data_response.expense.reparo == true )          
							$('#dreparo').find('input[name=reparo]')[0].checked = true;
						else
							$('#dreparo').find('input[name=reparo]')[1].checked = true;
                    date = data_response.expense.fecha_movimiento.split('-');
                    //date = data_response.date.split('-');
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
                    if(data_response.expense.idcomprobante == 1 || data_response.expense.idcomprobante == 4 )
                    {
                        $(".tot-document").show();
                        $("#sub-tot").val(data_response.expense.sub_tot);
                        $("#imp-ser").val(data_response.expense.imp_serv);
                        $("#igv").val(data_response.expense.igv);
                        $('#dreparo').show();
                    }
                    else
                    {
                        $(".tot-document").hide();
                        $("#sub-tot").val(0);
                        $("#imp-ser").val(0);
                        $("#igv").val(0);
                        $('#dreparo').hide();
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
            route_update = 'update-expense';
            $(".message-expense").text('').hide();
            
            var btn_save = $(this).html().trim();
            var row  = "<tr>";
                row += "<th class='proof-type'></th>";
                row += "<th class='ruc'></th>";
                row += "<th class='razon'></th>";
                row += "<th class='voucher_number'></th>";
                row += "<th class='date_movement'></th>";
                row += "<th class='total'><span class='type_money'></span>&nbsp;<span class='total_expense'></span></th>";
                row += "<th><a class='edit-expense' href='#'><span class='glyphicon glyphicon-pencil'></span></a></th>";
                row += "<th><a class='delete-expense' href='#'><span class='glyphicon glyphicon-remove'></span></a></th>";
                row += "</tr>";

            var proof_type       = $("#proof-type").val();
            var proof_type_sel   = $("#proof-type option:selected").text();
            var ruc              = $("#ruc").val();
            var ruc_hide         = $("#ruc-hide").val();
            var razon            = $("#razon").text();
            var razon_hide       = $("#razon").val();
            var razon_edit       = $("#razon").attr("data-edit");
            var number_prefix    = $("#number-prefix").val();
            var number_serie     = $("#number-serie").val();
            var voucher_number   = number_prefix+"-"+number_serie;
            var date             = $("#date").val();
            var desc_expense     = $("#desc-expense").val();
            var balance      = parseFloat($("#balance").val());
            
            //Validación de errores de cabeceras
            var error = 0;
            if(!ruc){   
                if(proof_type !== '7'){
                    $("#ruc").attr("placeholder","No se ha ingresado el RUC.");
                    $("#ruc").addClass("error-incomplete");
                    error = 1;
                }
            }
            if(ruc != ruc_hide){
                if(proof_type !== '7'){
                    $("#razon").addClass("error-incomplete");
                    $("#razon").html("Busque el RUC otra vez.");
                    error = 1;
                }
            }
            if(razon_hide == 0 && razon_edit == 0){
                if(proof_type !== '7'){
                    $("#razon").addClass("error-incomplete");
                    $("#razon").html("No ha buscado la Razón Social.");
                    error = 1;
                }
            }
            else if(razon_hide == 1 && razon_edit == 0){
                if(proof_type !== '7'){
                    $("#razon").html("No existe el ruc consultado.");
                    $("#razon").removeClass("error-incomplete");
                    error = 1;
                }
            }
            if(!number_prefix){
                if(proof_type !== '7'){
                    $("#number-prefix").attr("placeholder","Nro. Prejifo vacío");
                    $("#number-prefix").addClass("error-incomplete");
                    error = 1;
                }
            }
            if(!number_serie){
                if(proof_type !== '7'){
                    $("#number-serie").attr("placeholder","Nro. Serie vacío");
                    $("#number-serie").addClass("error-incomplete");
                    error = 1;
                }
            }
            if(!date){
                $("#date").attr("placeholder","No se ha ingresado la Fecha de Movimiento.");
                $("#date").addClass("error-incomplete");
                error = 1;
            }
            if(!desc_expense){
                $("#desc-expense").attr("placeholder","No se ha ingresado la Descripción.");
                $("#desc-expense").addClass("error-incomplete");
                error = 1;
            }
            if(balance < 0){
                $("#balance").addClass("error-incomplete");
                error = 1;
            }
            //Mostrando errores de cabeceras si es que existen
            if(error !== 0)
            {
                $("html, body").animate({
                    scrollTop: $("#proof-type").offset().top
                },300);
                return false;
            }
            else
            {
                data._token        = $('input[name=_token]').val();
                data.token         = $('input[name=token]').val();
                data.proof_type    = proof_type;
                data.ruc           = ruc;
                data.razon         = razon;
                data.number_prefix = number_prefix;
                data.number_serie  = number_serie;
                data.date_movement = date;
                data.desc_expense  = desc_expense;
            
                data.tipo_gasto = [];
                $('.type-expense').each(function() {
                    data.tipo_gasto.push( $(this).val() );
                });

                var error_json = 0;
                //Datos del detalle gastos por items
                quantity = $(".quantity input");
                total_item = $(".total-item input");
                
                var data_quantity    = validateEmpty(quantity);
                var data_total_item  = validateEmpty(total_item);
                var arr_description = [];
                
                $.each($(".description input"),function(index)
                {
                    if( $(this).val().length > 0 )
                        arr_description[index] = $(this).val();
                    else
                    {
                        $(this).val('').attr('placeholder','Debe ingresar la descripción');
                        $(this).addClass("error-incomplete");
                        error_json = 1;
                    }
                });
                var rep;
                if ( !$('#dreparo').find('input[name=reparo]').length == 0)
                {
                    if( $('#dreparo').find('input[name=reparo]')[0].checked == true )
                        rep = 1 ;
                    else
                        rep = 0 ;
                }
                (data_quantity.length>0) ? data.quantity = data_quantity : error_json = 1;
                data.description = arr_description;
                (data_total_item.length>0) ? data.total_item = data_total_item : error_json = 1;
                data.rep = rep;
                
                if(error_json === 0)
                {
                    if(proof_type == '1' || proof_type == '4' || proof_type == '6')
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
                    if( tot_expenses > 0 )
                        if(validateRuc(ruc) === true )
                        {
                            ajaxExpense(data)
                            .done(function(result){
                                if(result.code == -1)
                                {
                                    responseUI("Documento ya registrado","red");
                                    $(".message-expense").text("El documento ya se encuentra registrado.").show();
                                }
                                else if(result.code > 0)
                                {
                                    //var new_row = $(row).clone(true,true);
                                    /*var arr_expense = [proof_type_sel,ruc, razon, voucher_number, date, type_money, tot_expense, result.gastoId];
                                    newRowExpense(new_row,arr_expense);
                                    */
                                    responseUI("Gasto Registrado","green");
                                    rechargeExpense().done( function ( data ) 
                                    {
                                        if ( data.Status == 'Ok' )
                                        {
                                            $('#table-expense').html(data.Data);
                                            $("#save-expense").html("Registrar");
                                            //$(".detail-expense").show();
                                            //$(".search-ruc").show();
                                            //$("#ruc-hide").siblings().parent().addClass('input-group');   
                                            tot_expenses = calculateTot($(".total").parent(),'.total_expense');
                                            balance = parseFloat(deposit - tot_expenses);
                                            balance = balance.toFixed(2);
                                            $("#balance").val(balance);
                                            deleteItems();
                                            deleteExpense();
                                        }
                                        else
                                            bootbox.alert('<h4 class="red">No se ha podido recargar los gastos registrados, recarge la pagina</h4>');
                                    });
                                }
                                else
                                {
                                    responseUI("No se ha registrado el gasto","red");
                                    $(".message-expense").text("No se ha podido registrar el detalle de gastos");
                                }
                            })
                            .fail(function(){
                                responseUI("Error del servidor","red");
                                $(".message-expense").text("Error al momento de registrar los gastos");
                            });
                        }
                        else
                            if (validateVoucher(ruc,voucher_number) === true)
                            {
                                ajaxExpense(data).done(function(result)
                                {
                                    if(result.code == -1)
                                    {
                                        responseUI("Documento ya registrado","red");
                                        $(".message-expense").text("El documento ya se encuentra registrado.").show();
                                    }
                                    else if(result.code > 0)
                                    {
                                        //var new_row = $(row).clone(true,true);
                                        //var arr_expense = [proof_type_sel, ruc, razon, voucher_number, date, type_money, tot_expense, result.gastoId];
                                        //newRowExpense(new_row,arr_expense);
                                        //deleteExpense();
                                        responseUI("Gasto Registrado","green");
                                        rechargeExpense().done( function ( data ) 
                                        {
                                            if ( data.Status == 'Ok' )
                                            {
                                                $('#table-expense').html(data.Data);
                                                $("#save-expense").html("Registrar");
                                                //$(".detail-expense").show();
                                                //$(".search-ruc").show();
                                                //$("#ruc-hide").siblings().parent().addClass('input-group');   
                                                tot_expenses = calculateTot($(".total").parent(),'.total_expense');
                                                balance = parseFloat(deposit - tot_expenses);
                                                balance = balance.toFixed(2);
                                                $("#balance").val(balance);
                                                deleteItems();
                                                deleteExpense();
                                            }
                                            else
                                                bootbox.alert('<h4 class="red">No se ha podido recargar los gastos registrados, recarge la pagina</h4>');
                                        });
                                    }
                                    else
                                    {
                                        responseUI("No se ha registrado el gasto","red");
                                        $(".message-expense").text("No se ha podido registrar el detalle de gastos");
                                    }
                                }).fail(function()
                                {
                                    responseUI("Error del servidor","red");
                                    $(".message-expense").text("Ocurrió un error al momento de registrar los gastos");
                                });
                            }
                            else
                                if( btn_save === 'Registrar')
                                {
                                    $(".message-expense").text("El documento ya se encuentra registrado.").show();
                                    return false;
                                }
                                else
                                {
                                    var rows = $(".total").parent();
                                    $.each(rows,function(index)
                                    {
                                        if($(this).find(".voucher_number").html()===voucher_number && $(this).find(".ruc").html()===ruc)
                                        {
                                            data.idgasto = $('input[name=idgasto]').val();
                                            $.ajax({
                                                type: 'post',
                                                url: server + route_update,
                                                data: data,
                                                beforeSend: function(){
                                                    loadingUI('Actualizando ...');
                                                },
                                                error: function(){
                                                    console.log("error al Actualizar el gasto");
                                                }
                                            }).done( function (result) 
                                            {
                                                if(result > 0)
                                                {
                                                    /*$(".proof-type:eq("+index+")").text(proof_type_sel);
                                                    $(".ruc:eq("+index+")").text(ruc);
                                                    $(".razon:eq("+index+")").text(razon);
                                                    $(".voucher_number:eq("+index+")").text(voucher_number);
                                                    $(".date_movement:eq("+index+")").text(date);
                                                    $(".total_expense:eq("+index+")").text(tot_expense);
                                                    $("#save-expense").html("Registrar");
                                                    $("#table-expense tbody tr").removeClass("select-row");*/
                                                    responseUI("Gasto Actualizado","green");
                                                    rechargeExpense().done( function ( data ) 
                                                    {
                                                        if ( data.Status == 'Ok' )
                                                        {
                                                            $('#table-expense').html(data.Data);
                                                            $("#save-expense").html("Registrar");
                                                            //$(".detail-expense").show();
                                                            //$(".search-ruc").show();
                                                            //$("#ruc-hide").siblings().parent().addClass('input-group');   
                                                            tot_expenses = calculateTot($(".total").parent(),'.total_expense');
                                                            balance = parseFloat(deposit - tot_expenses);
                                                            balance = balance.toFixed(2);
                                                            $("#balance").val(balance);
                                                            deleteItems();
                                                            deleteExpense();
                                                        }
                                                        else
                                                            bootbox.alert('<h4 class="red">No se ha podido recargar los gastos registrados, recarge la pagina</h4>');
                                                    });
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
                    else
                    {
                        ajaxExpense(data).done(function(result)
                        {
                            if(result.code == -1)
                            {
                                responseUI("Documento ya registrado","red");
                                $(".message-expense").text("El documento ya se encuentra registrado.").show();
                            }
                            else if(result.code > 0)
                            {
                                responseUI("Gasto Registrado","green");
                                rechargeExpense().done( function ( data ) 
                                {
                                    if ( data.Status == 'Ok' )
                                    {
                                        $('#table-expense').html(data.Data);
                                        $("#save-expense").html("Registrar");
                                        //$(".detail-expense").show();
                                        //$(".search-ruc").show();
                                        //$("#ruc-hide").siblings().parent().addClass('input-group');   
                                        tot_expenses = calculateTot($(".total").parent(),'.total_expense');
                                        balance = parseFloat(deposit - tot_expenses);
                                        balance = balance.toFixed(2);
                                        $("#balance").val(balance);
                                        deleteItems();
                                        deleteExpense();
                                    }
                                    else
                                        bootbox.alert('<h4 class="red">No se ha podido recargar los gastos registrados, recarge la pagina</h4>');
                                });
                            }
                            else
                            {
                                responseUI("No se ha registrado el gasto","red");
                                $(".message-expense").text("No se ha podido registrar el detalle de gastos");
                            }
                        }).fail(function(){
                            responseUI("Error del servidor","red");
                            $(".message-expense").text("Ocurrió un error al momento de registrar los gastos");
                        });
                    }
                }
                else
                    $("html, body").animate({scrollTop:200},'500','swing');
            }
            if(balance === 0)
                $(".detail-expense").hide();
            $(".search-ruc").show();
            $("#ruc-hide").siblings().parent().addClass('input-group');
        });
    
        //Search Social Reason in SUNAT once introduced the RUC
        $(".search-ruc").on("click",function(){

            var port = location.port;
            port = port == "" ? port : ":"+port;

            rout_ruc = 'http://app.bagoperu.com.pe'+ port +'/snt_service/json/';
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
                data._token = $("input[name=_token]").val();
                $.ajax({
                    type: 'get',
                    url: rout_ruc + ruc + '/',
                    cache: false,
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
                    if(response.error == undefined){
                        $("#razon").val(2);
                        $("#ruc-hide").val(ruc);
                        $("#razon").html(response['razon_social']);
                    }else{
                        if(response.code == 1){
                            $("#razon").html("RUC menor a 11 digitos.");
                        }
                        if(response.code == 2){
                            $("#ruc").addClass("error-incomplete");
                            $("#razon").val(1);
                            $("#razon").html("No existe el RUC consultado.");
                        }if(response.code == 4){
                            $("#razon").html(response.error + ". "+ response.msg);
                        }
                        $("#ruc-hide").val(ruc);
                        //$("#razon").html(response.razon_social);
                        l.stop();
                        $("#ruc").attr("disabled",false);
                    }
                });
            }
        });
        //Save data to the controller Expense
        function ajaxExpense(data)
        {
            return $.ajax({
                type: 'post',
                url: server+'register-expense', 
                asynch: false,
                data: data,
                beforeSend: function(){
                    loadingUI('Registrando');
                },
                error:function(){
                    bootbox.alert("No se pueden grabar los datos.");
                }
            });
        }
    //Handling Classes
        //Removing errros
        $(document).on("focus","input",function(){
            $(this).removeClass("error-incomplete").attr("placeholder",'');
            $(".message-expense").hide();
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
            row.attr("data-id", arr[7]);
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
            $(".tot-document").show();
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
            $("#total-expense").val('');
            $("#tot-edit-hidden").val('');
            $("#desc-expense").val('');
        }
        //Calculate Balance
        function calcularBalance()
        {
            var balance;
            var deposit = parseFloat(depositado.val());
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
            var typeUser = 'R';
            if($(".user").attr('data-typeUser')){
                typeUser = 'C';
            };
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
                if($("#proof-type").val()==='1' || $("#proof-type").val()==='4' )
                {
                    if(!imp_service) imp_service = 0;
                    
                    igv = total_expense*IGV;
                    sub_total_expense = total_expense;
                    $("#sub-tot").val(sub_total_expense.toFixed(2));
                    total_expense = sub_total_expense + igv + imp_service;
                    
                    $("#igv").val(igv.toFixed(2));
                    $("#total-expense").val(total_expense.toFixed(2));
                }
                else
                    $("#total-expense").val(total_expense.toFixed(2));
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
                            $("#ret"+value).addClass('error-incomplete').attr('placeholder','Escoger solo una retención.').val('');
                        }
                    });
                }
                return false;
            }
            else
            {
                if(arrayRet.length == 1)
                {
                    if(parseInt($("#idamount").val(),10) < parseInt(arrayRet[0][1],10))
                    {
                        $("#ret"+arrayRet[0][0]).addClass('error-incomplete').attr('placeholder','Retención menor al monto depositado.').val('');
                        return false;
                    }    
                }
                return true;
            }
        }
        $(document).off("click", "#saveSeatExpense");
        $(document).on("click", "#saveSeatExpense", function(){
            var button = $(this);
            var data = {};
            data.seatList = GBDMKT.seatsList;
            data._token   = $("input[name=_token]").val();
            $.ajax({
                type: 'post',
                url: server+"guardar-asiento-gasto",
                data: data,
                async: false,
                beforeSend: function(){
                    loadingUI('Actualizando ...');
                },
                error: function(){
                    console.log("error al registrar asientos");
                }
            }).done( function (result) {
                if(!result.hasOwnProperty("error"))
                {
                    
                    responseUI("Gasto Actualizado","green");
                    button.hide('slow');
                    $(".edit-seat").hide('slow');
                }
                else
                {
                    responseUI("No se ha actualizado el gasto","red");
                }
            });
        });

        // EDIT SEAT CONT
        $(document).off("click", ".edit-seat-save");
        $(document).on("click", ".edit-seat-save", function(e){
            e.preventDefault(this);
            trElement = $(this).parent().parent();
            trElement.find(".editable").each(function(i,data){
                var editElement = $(data);
                var typeElement = editElement.attr("class").replace("editable", "").trim();

                if(typeElement == 'cuenta'){
                    var selectElement = editElement.find('select').find(":selected");
                    var marcarElement = trElement.find('.leyenda');
                    var marca = selectElement.attr('data-marca') + marcarElement.text().substr(-1);
                    var cuenta_cont = selectElement.attr('value');
                    $(GBDMKT.seatsList).each(function(i,data){
                        if(data.tempId == trElement.attr('data-id')){
                            data.numero_cuenta = cuenta_cont;
                            editElement.html(cuenta_cont);
                            data.leyenda = marca;
                            marcarElement.html(marca);
                            return false;
                        }
                    });
                }
            });
            var optionController = trElement.children().last();
            optionController.html(optionController.attr('data-html'));

        });

        $(document).off("click", ".edit-seat-cancel");
        $(document).on("click", ".edit-seat-cancel", function(e){
            e.preventDefault(this);
            trElement = $(this).parent().parent();
            trElement.find(".editable").each(function(i,data){
                var editElement = $(data);
                var typeElement = editElement.attr("class").replace("editable", "").trim();

                if(typeElement == 'cuenta'){
                    editElement.html(editElement.attr('data-html'));
                }
            });
            var optionController = trElement.children().last();
            optionController.html(optionController.attr('data-html'));
        });

        $(document).off("click", ".edit-seat");
        $(document).on("click", ".edit-seat", function(e){
            e.preventDefault();
            var trElement = $(this).parent().parent();
            trElement.find(".editable").each(function(i,data){
                var editElement = $(data);
                var typeElement = editElement.attr("class").replace("editable", "").trim();

                if(typeElement == 'cuenta'){
                    var data = {};
                    data.cuentaMkt = editElement.attr("data-cuenta_mkt");
                    data._token   = $("input[name=_token]").val();
                    $.ajax({
                        type: 'post',
                        url: server+"get-account",
                        data: data,
                        async: false,
                        error: function()
                        {
                            console.log("ERROR: No se pudo obtener cuentas.");
                        }
                    }).done( function (result) 
                    {
                        console.log(result);
                        e=result;
                        if(!result.hasOwnProperty("error"))
                        {
                            var select_temp = $('<select></select>');
                            a = result;
                            $(result).each(function(i,option)
                            {
                                var tempOption = $('<option value="'+ option.account_expense.num_cuenta +'">'+ option.account_fondo.nombre +' | '+ option.account_expense.num_cuenta +' | '+ option.account_expense.nombre +'</option>');
                                tempOption.attr('data-marca', option.mark.codigo + option.document.codigo);
                                console.log( tempOption);
                                select_temp.append(tempOption);
                            });
                            editElement.attr('data-html', editElement.html());
                            editElement.html('');
                            editElement.append(select_temp);
                        }
                        else
                            bootbox.alert(result.error + ": " + result.msg);
                    });
                }
            });
            var optionController = trElement.children().last();
            optionController.attr('data-html', optionController.html());
            optionController.html('<a class="edit-seat-save" href="#"><span class="glyphicon glyphicon-ok"></span></a>&nbsp;&nbsp;'+
                                  '<a class="edit-seat-cancel" href="#"><span class="glyphicon glyphicon-remove"></span></a>')

        });
        
        $(document).off("click", ".modal_deposit");
        $(document).on("click", ".modal_deposit", function(e)
        {
            //e.preventDefault();
            var tr = $(this).parent().parent().parent();
            var id_sol = tr.find('.id_solicitud').text();
            var sol_titulo = tr.find('.sol_titulo').find('label').text();
            var token = tr.find('#sol_token').val();
            var beneficiario = tr.find('.benef').val();
            var total_deposit = tr.find('.total_deposit').text().trim().split(" ");
            var retencion = tr.find('.tes-ret').html().trim();
            
            if ( retencion != 0 )
            {
                $("#tes-mon-ret").parent().css("display",true);
                $("#tes-mon-sol").parent().css("display",true);
            }
            else
            {
                $("#tes-mon-ret").parent().css("display","none");
                $("#tes-mon-sol").parent().css("display","none");    
            }
            
            $("#sol-titulo").val(sol_titulo);
            $("#tes-mon-sol").val(total_deposit[0] + " " + total_deposit[1]);
            $("#tes-mon-ret").val(retencion);
            $("#id-solicitude").text(id_sol);
            $("input[name=token]").val(token);
            $(".register-deposit").attr("data-deposit",tr.attr("sol-type"));
            $("#beneficiario").val(beneficiario);
            $("#total-deposit").val( tr.find('.deposit').text().trim() );
            $('#enable_deposit_Modal').modal();
        });
        
        //$("#collapseOne").collapse('toggle');
        
        $(document).off("click", "#detail_solicitude");
        $(document).on("click", "#detail_solicitude", function(e)
        {
            e.preventDefault();
            if($("#collapseOne").hasClass('in'))
            {
                $("#detail_solicitude span:last-child").removeClass('glyphicon-chevron-up').addClass('glyphicon-chevron-down');
                $("#detail_solicitude span:first-child").text('Mostrar');
            }
            else
            {
                $("#detail_solicitude span:last-child").removeClass('glyphicon-chevron-down').addClass('glyphicon-chevron-up');
                $("#detail_solicitude span:first-child").text('Ocultar');
            }
            $("#collapseOne").collapse('toggle');
        });

        if ( $(".search-ruc").length == 1 )
        {
            $("#detail_solicitude span:last-child").removeClass('glyphicon-chevron-up').addClass('glyphicon-chevron-down');
            $("#detail_solicitude span:first-child").text('Mostrar');
            $("#collapseOne").collapse('toggle');
        } 
});