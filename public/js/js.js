var server = "";
var pathname = document.location.pathname;
var pathnameArray= pathname.split("/public/");

server =  pathnameArray.length >0 ? pathnameArray[0]+"/public/" : "";
//var IGV = 0.18;
//Funciones Globales
var datef = new Date();
var dateactual = (datef.getMonth()+1)+'-'+datef.getFullYear();

function loadingUI(message)
{
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

function responseUI( message,color )
{
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

$(function()
{

    function ajaxError(statusCode,errorThrown)
    {
        if (statusCode.status == 0) 
            bootbox.alert('<h4 class="yellow">Internet: Problemas de Conexion</h4>');    
        else
            bootbox.alert('<h4 class="red">Error del Sistema</h4>');  
    }

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
    var deposit        = parseFloat($('#amount').val());
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
    
    $("#regimen").on("change" , function()
    {
        if ( $(this).val() == 0 )
            $("#monto-regimen").parent().parent().css("visibility" , "hidden" );
        else
            $("#monto-regimen").parent().parent().css("visibility" , "show" );        
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
    //$("#number_account").numeric({negative:false,decimal:false});
    //only numbers floats
    $("#imp-ser").numeric({negative:false});
    $(".total-item input").numeric({negative:false});
    $(".quantity input").numeric({negative:false});
    $("#igv").numeric({negative:false});
    $("#sub-tot").numeric({negative:false});
    $("#total-expense").numeric({negative:false});
    $("#total").numeric({negative:false});
    //Events: Datepicker, Buttons, Keyup.
    //Calcule the IGV and Balance once typed the total amount per item
    $( document ).off( 'keyup' , '.total-item input' );
    $( document).on( 'keyup' , '.total-item input' , function()
    {
        calcularIGV();
    });
    //Calcule the IGV and Balance once typed the imp service
    $(document).on("keyup","#imp-ser" , function()
    {
        calcularIGV();
    });

    $(document).on("keyup","#igv" , function()
    {
        calcularBalance();
    });

    $(document).on("keyup","#sub-tot" , function()
    {
        calcularBalance();
    });

    // Datepicker date all classes
    // FIX COLOR IN INPUTS READONLY
    $('.date>input[readonly]').css('background-color', "#fff");
    if(!($('.date>input:not([disabled])').length == 0))
    {
        $(".date").datepicker(
        {
            orientation: "auto",
            language: 'es',
            startDate: typeof START_DATE === 'undefined' ? '{{}}' : START_DATE ,
            endDate: typeof END_DATE === 'undefined' ? '' : END_DATE ,
            format: 'dd/mm/yyyy'
        });
        //selected a date hide the datepicker
        $(".date").on("change",function(){
            $(this).datepicker('hide');
        });
    }

    $( document ).off( 'focus' , 'input[name=numero_operacion_devolucion]' );
    $( document ).on( 'focus' , 'input[name=numero_operacion_devolucion]' , function()
    {
        $( this ).parent().parent().removeClass( 'has-error' );
    });

    function endExpenseAjax( data )
    {
        return  $.ajax(
                {
                    type: 'post',
                    url: server + 'end-expense',
                    data: data
                }).fail( function( statusCode , errorThrow )
                {
                    ajaxError( statusCode , errorThrow );
                });
    }

    function endExpense( otherData )
    {
        var data =
        {
            _token : $('input[name=_token]').val(),
            token  : token
        };
        data = $.extend( data , otherData );
        endExpenseAjax( data ).done( function ( response ) 
        {
            if ( response.Status == 'Ok' )
            {
                bootbox.alert( '<h4 class="green">Gasto Registrado</h4>' , function()
                {
                    window.location.href = server + 'show_user';
                });
            }
            else if( response.Status == 'Info' )
            {
                bootboxExpense( response.Title , response.View , response.Type );
            }
            else
                bootbox.alert( '<h4 class="red">' + response.Status + ': ' + response.Description + '</h4>');
        });
    }

    function bootboxExpense( title , view , type )
    {
        bootbox.dialog( 
        {
            title   : title ,
            message : view ,
            locale  : 'es' ,
            size    : 'large' ,
            buttons: 
            {
                danger: 
                {
                    label:'Cancelar',
                    className: 'btn-primary',
                    callback: function() 
                    {
                        bootbox.hideAll();
                    }
                },
                success: 
                {
                    label: 'Confirmar',
                    className: 'btn-success',
                    callback: function()
                    {
                        if( type == 'ID' )
                        {
                            var status = validateEndExpenseInstitucional();
                            if ( status == 1 )
                            {
                                validateEndExpenseDevolucion();   
                                return false;
                            }
                            else
                            {
                                status = validateEndExpenseDevolucion();
                                if ( status == 1 )
                                    return false;
                                else
                                {
                                    var otherData =
                                    {
                                        inversion : $( 'select[name=inversion]' ).val() ,
                                        actividad   : $( 'select[name=actividad]' ).val() ,
                                        numero_operacion_devolucion : $( 'input[name=numero_operacion_devolucion]' ).val()
                                    };
                                    endExpense( otherData );
                                }
                            }
                        }
                        else if( type == 'I' )
                        {
                            var status = validateEndExpenseInstitucional();
                            if ( status == 1 )
                                return false;
                            else
                            {
                                var otherData =
                                {
                                    inversion : $( 'select[name=inversion]' ).val() ,
                                    actividad   : $( 'select[name=actividad]' ).val() 
                                };
                                endExpense( otherData );
                            }
                        }
                        else if( type == 'D' )
                        {
                            var status = validateEndExpenseDevolucion();
                            if ( status == 1 )
                                return false;
                            else
                            {
                                var otherData = { numero_operacion_devolucion : $( 'input[name=numero_operacion_devolucion]' ).val() };
                                endExpense( otherData );
                            }
                        }
                        else
                            return false;
                    }
                }
            }
        });
        if( type === 'ID' || type === 'I' )
        {
            activity = $('input[name=actividad]')
            $('input[name=inversion]').on( 'change' , function()
            {
            inversionChange( $(this).val() );
                            });
                            
    }

    function validateEndExpenseDevolucion()
    {
        var status = 0;
        if ( $( 'input[name=numero_operacion_devolucion]' ).val().trim() === '' )
        {
            $( 'input[name=numero_operacion_devolucion]' ).parent().parent().addClass( 'has-error' ).focus();
            status = 1;
        }
        return status;
    }

    function validateEndExpenseInstitucional()
    {
        var status = 0;
        if ( $( 'select[name=inversion]' ).val() === null )
        {
            $( 'select[name=inversion]' ).parent().parent().addClass( 'has-error' ).focus();
            status = 1;
        }
        if ( $( 'select[name=actividad]' ).val() === null )
        {
            $( 'select[name=actividad]' ).parent().parent().addClass( 'has-error' ).focus();
            status = 1;
        }
        return status;
    }

    //Record end Solicitude
    $( '#finish-expense' ).on( 'click' , function(e)
    {
        e.preventDefault();
        var type = $(this).attr( 'data-type' );
        var idfondo = $(this).attr( 'data-idfondo' );
        var balance = parseFloat( $( '#balance' ).val() );
        var idtiposolicitud = $( '#tipo-solicitud').val();
        var otherData = {};
        if ( balance >= 0 )
            endExpense( otherData );
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
            data.idsolicitud = $("input[name=idsolicitud]").val();
        bootbox.confirm("¿Esta seguro que desea Generar el Asiento Contable?", function(result) 
        {
            if(result)
            {
                $.post(url, data)
                .done( function (data)
                {
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

    //Enable deposit
    $("#enable-deposit").on("click",function(e){
        e.preventDefault();
        bootbox.confirm("<h4>Para Confirmar presione OK</h4>", function( result ) 
        {
            if(result)
            {
                data._token      = $("input[name=_token]").val();
                data.idsolicitud = $("input[name=idsolicitud]").val();
                
                $.post( server + 'revisar-solicitud' , data ).done( function ( data )
                {
                    if(data.Status == "Ok")
                    {
                        bootbox.alert("<h4 class='green'>Se reviso la solicitud correctamente.</h4>", function()
                        {
                            window.location.href = server+'show_user';
                        });
                    }
                    else
                        bootbox.alert("<h4 class='red'>" + data.Status + ": " + data.Description +  "</h4>");
                });
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
    $( '#proof-type' ).on( 'change' , function()
    {
        proofType( this );
    });

    function proofType( element )
    {
        calcularIGV();
        $("#ruc").prop('disabled', false);
        $("#number-prefix").prop('disabled', false);
        $("#number-serie").prop('disabled', false);
        $( '#regimen' ).parent().parent().show();
        $( '#monto-regimen' ).parent().parent().show();
        var option = $("#proof-type :selected"); 
        var igv = option.attr("igv");
        var marca = option.attr("marca");

        if( igv == 1 )//|| proof_type_sel === '6')
        {
            $(".tot-document").show();
            $('#dreparo').show();
        }
        else
        {
            $(".tot-document").hide();
            $('#dreparo').hide();
        }
        if( marca === 'N' )
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
            $( '#regimen' ).parent().parent().hide();
            $( '#monto-regimen' ).parent().parent().hide();
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
        bootbox.confirm("¿Esta seguro que desea eliminar el gasto?", function(result) 
        {
            if( result )
            {
                data = {"gastoId": elementTrId, "_token":$("input[name=_token]").val()};
                $.post(server + 'delete-expense', data)
                .done(function ( response ) 
                {
                    ajaxExpenseDone( response , 'Gasto Eliminado' );
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
            idsolicitud : $('input[name=idsolicitud]').val()
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

    $(document).on("click"," #table-expense .edit-expense", function( e )
    {
        e.preventDefault();
        $("#ruc-hide").siblings().parent().removeClass('input-group');
        $(".search-ruc").hide();
        $(".message-expense").text('').hide();
        var row_expense    = $(this).parent().parent();
        var ruc            = $(this).parent().parent().find(".ruc").html();
        var voucher_number = $(this).parent().parent().find(".voucher_number").html();
        var total_edit = parseFloat($(this).parent().parent().find(".total_expense").html());
        $("#total-expense").val(total_edit.toFixed(2));
        $("#tot-edit-hidden").val(total_edit.toFixed(2));
        $("#table-expense tbody tr").removeClass("select-row");
        $(this).parent().parent().addClass("select-row");
        $(".message-expense").text('').hide();
        $("#table-items tbody tr").remove();
        $("#save-expense ").html("Actualizar");
        
        $.ajax({
            type : 'post' ,
            url  : server + 'edit-expense' ,
            data : 
            {
                _token  : $('input[name=_token]').val(),
                idgasto : row_expense.attr('data-id')
            },
            beforeSend:function()
            {
                loadingUI('Cargando Datos');
            },
            error:function()
            {
                responseUI("No se puede acceder al servidor" ,"red");
                $(".message-expense").text('No se pueden recuperar los datos del servidor.').show();
            }
        }).done( function ( response )
        {
            if ( response.Status == 'Ok' )
                setTimeout(function()
                {
                    responseUI('Editar Gasto','green');
                    $("html, body").animate({scrollTop:200},'500','swing');
                    var data = JSON.parse( JSON.stringify( response.Data ) );
                    $('input[name=idgasto]').val( data.expense.id );
                    $("#proof-type").val(data.expense.idcomprobante).attr("disabled",true);
                    $("#ruc").val(data.expense.ruc).attr("disabled",true);
                    $("#ruc-hide").val(data.expense.ruc);
                    $("#razon").text(data.expense.razon).css("color","#5c5c5c");
                    $("#razon").attr("data-edit",1);
                    $("#number-prefix").val(data.expense.num_prefijo).attr("disabled",true);
                    $("#number-serie").val(data.expense.num_serie).attr("disabled",true);
                    if ( $("#regimen").length == 1 )
                    {
                        if ( data.expense.idtipotributo === null || data.expense.idtipotributo == 0 )
                        {
                            $("#regimen").val(0);
                            $("#monto-regimen").val('').parent().parent().css("visibility" , "hidden" ); 
                        }
                        else
                        {
                            $("#regimen").val(data.expense.idtipotributo);
                            if ( data.expense.idtipotributo >= 1 )
                                $("#monto-regimen").val(data.expense.monto_tributo).parent().parent().css("visibility" , "show" ); 
                        }
                        $("#monto-regimen").numeric({negative:false});
                    }

					if ( !$('#dreparo').find('input[name=reparo]').length == 0)
						if ( data.expense.reparo == true )          
							$('#dreparo').find('input[name=reparo]')[0].checked = true;
						else
							$('#dreparo').find('input[name=reparo]')[1].checked = true;

                    if ( $("#proof-type option:selected").attr('marca') === 'N' )
                    {
                        $("#regimen").parent().parent().hide();
                        $("#monto-regimen").parent().parent().hide();
                    }
                    else
                    {
                        $("#regimen").parent().parent().show();
                        $("#monto-regimen").parent().parent().show();
                    }    

                    $('#igv').val( data.expense.igv );
                    
                    var date = data.expense.fecha_movimiento.split('-');
                    date = date[2].substring(0,2)+'/'+date[1]+'/'+date[0];
                    $("#date").val( date );
                    $("#desc-expense").val(data.expense.descripcion);
                    $.each( data.expenseItems , function( index , value )
                    {
                        var row_add = row_item_first.clone();
                        row_add.find('.quantity input').val(value.cantidad);
                        row_add.find('.description input').val(value.descripcion);
                        row_add.find('.type-expense').val(value.tipo_gasto);
                        row_add.find('.total-item input').val(value.importe);
                        $("#table-items tbody").append(row_add);
                        $(".total-item input").numeric({negative:false});
                        $(".quantity input").numeric({negative:false});
                    });
                    if(data.expense.idcomprobante == 1 || data.expense.idcomprobante == 4 )
                    {
                        $(".tot-document").show();
                        $("#sub-tot").val(data.expense.sub_tot);
                        $("#imp-ser").val(data.expense.imp_serv);
                        $("#igv").val(data.expense.igv);
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
                    tot_expenses = calculateTot( row_expenses , '.total_expense' );
                    var balance = deposit - tot_expenses;
                    $("#balance").val(balance.toFixed(2));
                    $(".detail-expense").show();
                    $('#cancel-expense').show();
                },1000);
            else
                bootbox.alert('<h4 class="red">' + response.Status + ': ' + response.Description + '</h4>');
        });
    });
    //Validation spending record button
    $( "#save-expense" ).click( function( e )
    {
        e.preventDefault();
        var btn_save = $(this).html().trim();
        var proof_type_sel   = $("#proof-type option:selected");
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
        var balance          = parseFloat($("#balance").val());

        //Validación de errores de cabeceras
        var error = 0;
        if( !ruc )
        {   
            if( proof_type_sel.attr("marca") !== 'N' )
            {
                $("#ruc").attr("placeholder","No se ha ingresado el RUC.");
                $("#ruc").addClass("error-incomplete");
                error = 1;
            }
        }
        if(ruc != ruc_hide)
        {
            if( proof_type_sel.attr("marca") !== 'N' )
            {
                $( '#razon' ).addClass( 'error-incomplete' ).html( 'Busque el RUC otra vez.' ).parent().parent().addClass( 'has-error' ).removeClass( 'has-success' );
                error = 1;
            }
        }
        if(razon_hide == 0 && razon_edit == 0)
        {
            if( proof_type_sel.attr("marca") !== 'N' )
            {
                $("#razon").addClass("error-incomplete");
                $("#razon").html("No ha buscado la Razón Social.").parent().parent().addClass( 'has-error' ).removeClass( 'has-success' );;
                error = 1;
            }
        }
        else if(razon_hide == 1 && razon_edit == 0)
        {
            if( proof_type_sel.attr("marca") !== 'N' )
            {
                $("#razon").html("No existe el ruc consultado.");
                $("#razon").removeClass("error-incomplete").parent().parent().addClass( 'has-error' ).removeClass( 'has-success' );
                error = 1;
            }
        }
        if(!number_prefix)
        {
            if( proof_type_sel.attr("marca") !== 'N' )
            {
                $("#number-prefix").attr("placeholder","Nro. Prejifo vacío");
                $("#number-prefix").addClass("error-incomplete");
                error = 1;
            }
        }
        if(!number_serie)
        {
            if( proof_type_sel.attr("marca") !== 'N' )
            {
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
        if( balance < 0 ){
            $("#balance").addClass("error-incomplete");
            error = 1;
        }
        //Mostrando errores de cabeceras si es que existen
        if( error !== 0 )
        {
            $("html, body").animate({
                scrollTop: $("#proof-type").parent().parent().offset().top
            } , 300 );
            return false;
        }
        else
        {
            var data = {};
            data._token        = $('input[name=_token]').val();
            data.token         = $('input[name=token]').val();
            data.proof_type    = $("#proof-type").val();
            data.idregimen     = $("#regimen").val();
            data.monto_regimen = $("#monto-regimen").val();
            data.ruc           = ruc;
            data.razon         = razon;
            data.number_prefix = number_prefix;
            data.number_serie  = number_serie;
            data.fecha_movimiento = date;
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
            ( data_quantity.length > 0 ) ? data.quantity = data_quantity : error_json = 1 ;
            data.description = arr_description;
            ( data_total_item.length > 0 ) ? data.total_item = data_total_item : error_json = 1 ;
            data.rep = rep;
            
            if( error_json === 0 )
            {
                if( proof_type_sel.attr( 'igv' ) == 1 || validateVoucher(ruc,voucher_number) === true )
                {
                    var sub_total_expense = parseFloat( $("#sub-tot").val() );
                    var imp_service       = parseFloat( $("#imp-ser").val() );
                    var igv               = $("#igv").val();
                    
                    if(isNaN(sub_total_expense)) sub_total_expense = 0;
                    if(isNaN(imp_service)) imp_service = 0;
                    if(isNaN(igv)) igv = 0;
                    
                    data.sub_total_expense = sub_total_expense;
                    data.imp_service = imp_service;
                    data.igv = igv;
                }

                tot_expense = parseFloat($("#total-expense").val());
                data.total_expense = tot_expense;
                if( validateRuc( ruc ) === true )
                {
                    ajaxExpense( data ).done( function( response )
                    {
                        ajaxExpenseDone( response , 'Gasto Registrado' );
                    }).fail(function( statusCode , errorThrown )
                    {
                        ajaxError( statusCode , errorThrown );
                    });
                }
                else
                    if ( validateVoucher(ruc,voucher_number) === true || ( proof_type_sel.attr( 'marca' ) == 'N' && btn_save === 'Registrar' ) )
                    {
                        ajaxExpense(data).done(function( response )
                        {
                            ajaxExpenseDone( response , 'Gasto Registrado' );
                        }).fail(function( statusCode , errorThrown )
                        {
                            ajaxError( statusCode , errorThrown );
                        });
                    }
                    else
                        if( btn_save === 'Registrar' )
                        {
                            $(".message-expense").text( 'El Documento ya se encuentra registrado.' ).css( 'color' , 'red' ).show();
                            return false;
                        }
                        else
                        {
                            var rows = $( ".total" ).parent();
                            $.each(rows,function(index)
                            {
                                if( $( this ).find( '.voucher_number' ).html() === voucher_number && $( this ).find( ".ruc" ).html() === ruc )
                                {
                                    data.idgasto = $('input[name=idgasto]').val();
                                    $.ajax(
                                    {
                                        type: 'post' ,
                                        url: server + 'register-expense' ,
                                        data: data,
                                        beforeSend: function()
                                        {
                                            loadingUI('Actualizando ...');
                                        }
                                    }).fail( function ( statusCode , errorThrown )
                                    {
                                        $.unblockUI();
                                        ajaxError( statusCode , errorThrown );
                                    }).done( function ( response ) 
                                    {
                                        $.unblockUI();
                                        if( response.Status == 'Ok' )
                                        {
                                            responseUI("Gasto Actualizado","green");
                                            $( 'input[ name=idgasto ]' ).val('');
                                            rechargeExpense().done( function ( data ) 
                                            {
                                                if ( data.Status === 'Ok' )
                                                    reloadExpenseView( data.Data );
                                                else
                                                    bootbox.alert('<h4 class="red">No se ha podido recargar la ventana, recarge la pagina (CTRL + F5)</h4>');
                                            });
                                        }
                                        else
                                            bootbox.alert( '<h4 class="red">' + response.Status + ': ' + response.Description );
                                    });
                                }
                            });
                        }
            }
            else
                $("html, body").animate({scrollTop:200},'500','swing');
        }
    });

    function ajaxExpenseDone( response , msg )
    {
        $.unblockUI();
        if( response.Status !== 'Ok' )
            bootbox.alert( '<h4 class="red">' + response.Status + ' : ' + response.Description + '</h4>' );
        else
        {
            responseUI( msg , 'green' );
            rechargeExpense().done( function ( data ) 
            {
                if ( data.Status === 'Ok' )
                    reloadExpenseView( data.Data );
                else
                    bootbox.alert('<h4 class="red">No se ha podido recargar la ventana , recarge la pagina (CTRL + F5)</h4>');
            });
        }
    }

    function reloadExpenseView( data )
    {
        $('#section-table-expense').html( data );
        rechargeViewExpense();
    } 

    function rechargeViewExpense()
    {
        $("#save-expense").html("Registrar");
        var tot_expenses = calculateTot($(".total").parent() , '.total_expense' );
        var balance = deposit - tot_expenses;
        $("#balance").val(balance);
        cleanExpenseView();
        if( balance === 0 )
        {
            $(".detail-expense").hide();
            $('#confirm-discount').hide();    
        }
        else
        {
            $('.detail-expense').show();
            $('#confirm-discount').show();    
        }
        $(".search-ruc").show();
        $("#ruc-hide").siblings().parent().addClass('input-group');
    }

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
        if( ruc.length === 0 )
        {
            $("#ruc").addClass("error-incomplete");
            $( '#razon' ).css( 'color","#5c5c5c' ).html( 'No ha ingresado el RUC.').parent().parent().addClass( 'has-error' ).removeClass( 'has-success' );
        }
        else if( ruc.length > 0 && ruc.length < 11 )
        {
            $("#ruc").addClass("error-incomplete");
            $("#razon").css("color","#5c5c5c").html("El RUC ingresado no contiene 11 dígitos.").parent().parent().addClass( 'has-error' ).removeClass( 'has-success' );;
        }
        else
        {
            var l = Ladda.create( document.getElementById( 'razon' ) );
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
                    $("#razon").html("No se puede buscar el RUC.").parent().parent().addClass( 'has-error' ).removeClass( 'has-success' );;
                    $("#ruc").attr("disabled",false);
                }
            }).done(function (response){
                if(response.error == undefined)
                {
                    $( '#razon' ).val( 2 ).html( response['razon_social'] ).parent().parent().addClass( 'has-success' ).removeClass( 'has-error' );;
                    $("#ruc-hide").val( ruc );
                }
                else
                {
                    if(response.code == 1)
                        $("#razon").html("RUC menor a 11 digitos.").parent().parent().addClass( 'has-error' ).removeClass( 'has-success' );
                    if(response.code == 2)
                    {
                        $("#ruc").addClass("error-incomplete");
                        $( '#razon' ).val( 1 ).html( 'No existe el RUC consultado.' ).parent().parent().addClass( 'has-error' ).removeClass( 'has-success' );
                    }
                    if(response.code == 4)
                        $( '#razon' ).html( response.error + '. ' + response.msg ).parent().parent().addClass( 'has-error' ).removeClass( 'has-success' );
                    $("#ruc-hide").val(ruc);
                }
                l.stop();
                $("#ruc").attr("disabled",false);
            });
        }
    });
    //Save data to the controller Expense
    function ajaxExpense(data)
    {
        return $.ajax({
            type : 'post',
            url  : server + 'register-expense', 
            data : data,
            beforeSend: function()
            {
                loadingUI('Registrando');
            }
        });
    }
    
    //Calculating sum of rows
    function calculateTot(rows,clas){
        var sum = 0;
        $.each(rows,function()
        {
            sum += parseFloat($(this).find(clas).html());
        });
        return sum;
    }

    $( '#cancel-expense' ).on( 'click' , function() 
    {
        rechargeViewExpense();
    });

    //Clean View of Expense
    function cleanExpenseView()
    {
        $("#table-items tbody tr").remove();
        row_item_first.find('.quantity input').val('');
        row_item_first.find('.description input').val('');
        row_item_first.find('.total-item input').val('');
        $("#table-items tbody").append(row_item_first);
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
        $( '#cancel-expense' ).hide();
        $("#table-expense tbody tr").removeClass("select-row");
    }

    //Calculate Balance
    function calcularBalance()
    {
        var balance;
        var tot_expenses = calculateTot($(".total").parent(),'.total_expense');
        console.log( tot_expenses );
        var btn_save = $("#save-expense").html();
        var tot_item_expense = parseFloat($("#total-expense").val());
        var imp_serv = parseFloat($("#imp-ser").val());
        if( ! $.isNumeric( imp_serv ) ) imp_serv = 0 ;
        if( ! $.isNumeric( tot_item_expense ) ) tot_item_expense = 0 ;
        console.log( tot_item_expense );
        
        if(btn_save === "Registrar")
        {
            balance = deposit - tot_expenses - tot_item_expense;
            $("#balance").val( balance.toFixed( 2 ) );
        }
        else
        {
            balance = deposit - tot_expenses - tot_item_expense + parseFloat($("#tot-edit-hidden").val());
            $("#balance").val( balance.toFixed( 2 ) );
        }
        if( balance < 0 )
        {
            $(".message-expense").html('El monto ingresado supera el saldo.').css("color","red").show();
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
        //Total variables Proof
        var total_item = $(".total-item input");
        var proof_type_sel = $("#proof-type :selected");
        console.log( proof_type_sel );
        var sub_total_expense = 0;
        var imp_service = parseFloat($("#imp-ser").val());
        var igv_percent = $('#igv').attr('igv') / 100;
        var total_expense = 0;
        $.each(total_item,function(){
            if(!$.isNumeric($(this).val()))
                total_expense += 0;
            else
                total_expense += parseFloat($(this).val());
        });
        if ( total_expense > 0 )
        {
            if( proof_type_sel.attr("igv") == 1 )
            {
                if(!imp_service) imp_service = 0;
                var igv = total_expense * igv_percent / ( 1 + igv_percent );
                sub_total_expense = total_expense / ( 1 + igv_percent );
                $("#sub-tot").val( sub_total_expense.toFixed(2) );
                total_expense = sub_total_expense + igv + imp_service;
                
                $("#igv").val( igv.toFixed(2) );
                $("#total-expense").val( total_expense.toFixed(2) );
            }
            else
                $("#total-expense").val( total_expense.toFixed(2) );
        }
        else
        {
            $("#total-expense").val('');
            $("#sub-tot").val(0);
            $("#igv").val(0);
        }
        calcularBalance();
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
            if( ruc === $(this).find(".ruc").html())
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
    
    $( document ).off( 'click' , '#saveSeatExpense' );
    $( document ).on( 'click' , '#saveSeatExpense' , function()
    {
        var button = $(this);
        var data = {};
        data.seatList = GBDMKT.seatsList;
        data._token   = $("input[name=_token]").val();
        data.idsolicitud = $('#idsolicitud').attr("rel");
        $.ajax(
        {
            type: 'post',
            url: server + 'guardar-asiento-gasto',
            data: data
        }).fail( function( statusCode , errorThrown)
        {
            bootbox.alert('<h4 class="red"> No se pudo acceder al servidor </h4>');
        }).done( function ( result ) 
        {
            if( result.Status != 'Ok')
                bootbox.alert('<h4>' + result.Status + ': ' + result.Description + '</h4>');
            else if ( result.Status == 'Ok' )
            {
                responseUI( "Asiento Diario Registrado" , "green" );
                setTimeout( function()
                { 
                    window.location.href = server + 'show_user'; 
                }, 2000);
            }
        });
    });

    // EDIT SEAT CONT
    $(document).off("click", ".edit-seat-save");
    $(document).on("click", ".edit-seat-save", function(e)
    {
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

            if(typeElement == 'cuenta')
            {
                var data = {};
                data.cuentaMkt = editElement.attr("data-cuenta_mkt");
                data._token    = $("input[name=_token]").val();
                $.ajax({
                    type: 'post',
                    url: server+"get-account",
                    data: data,
                    async: false,
                    error: function()
                    {
                    }
                }).done( function (result) 
                {
                    e=result;
                    if(!result.hasOwnProperty("error"))
                    {
                        var select_temp = $('<select></select>');
                        a = result;
                        $(result).each(function(i,option)
                        {
                            var tempOption = $('<option value="'+ option.account_expense.num_cuenta +'">'+ option.fondo.nombre +' | '+ option.account_expense.num_cuenta +' | '+ option.bago_account_expense.ctanombrecta + ' | ' + option.mark.codigo + '</option>');
                            tempOption.attr('data-marca', option.mark.codigo );
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
                              '<a class="edit-seat-cancel" href="#"><span class="glyphicon glyphicon-remove"></span></a>');
    });
    
    $(document).off( 'click' , '.modal-document');
    $(document).on( 'click' , '.modal-document' , function(e)
    {
        var tr = $(this).parent().parent().parent();
        var view_type = $(this).children().attr('data-type');
        $.ajax(
        {
            type: 'post',
            url: server+"get-document-detail",
            data: 
            {
                id : tr.attr( 'row-id') ,
                _token : GBREPORTS.token
            }
        }).fail( function( statusCode , errorThrown )
        {
            ajaxError( statusCode , errorThrown );
        }).done( function ( response ) 
        {
            if ( response.Status == 'Ok' )
            {
                if( view_type == 0 )
                {
                    $('#regimen').prop('disabled' , true );
                    $('#monto-regimen').prop( 'disabled' , true );
                }
                else
                {
                    $('#regimen').prop('disabled' , false );
                    $('#monto-regimen').prop( 'disabled' , false );        
                }
                var modal = $('#documents_Modal');
                if ( response.Data.sub_tot === null )
                    modal.find('#subtotal').val('').parent().parent().hide();
                else
                    modal.find('#subtotal').val( response.Data.moneda + ' ' + response.Data.sub_tot ).parent().parent().show();
                if ( response.Data.igv === null )
                    modal.find('#igv').val('').parent().parent().hide();
                else    
                    modal.find('#igv').val( response.Data.moneda + ' ' + response.Data.igv ).parent().parent().show();
                if ( response.Data.imp_serv === null )
                    modal.find('#imp-serv').val('').parent().parent().hide();
                else
                    modal.find('#imp-serv').val( response.Data.moneda + ' ' + response.Data.imp_serv ).parent().parent().show();
                if ( response.Data.reparo == 0 )
                    modal.find('#reparo').val( 'No' );
                else if ( response.Data.reparo == 1 )
                    modal.find('#reparo').val( 'Si' );
                modal.find('#total').val( response.Data.moneda + ' ' + response.Data.monto );
                modal.find('input[name=idDocumento]').val( response.Data.id );
                
                if ( response.Data.idtipotributo == null )
                {
                    modal.find('#regimen').val( 0 );
                    modal.find('#monto-regimen').val( response.Data.monto_tributo ).parent().parent().css( 'visibility' , 'hidden' ); 
                }
                else
                {
                    modal.find('#regimen').val( response.Data.idtipotributo );
                    modal.find('#monto-regimen').val( response.Data.monto_tributo ).parent().parent().css( 'visibility' , 'show' );
                }
                modal.modal();
            }
            else
                bootbox.alert( '<h4 class="red">' + response.Status + ': ' + response.Description + '</h4>');
        });
    });

    $(document).off( 'click' , '#update-document' );
    $(document).on( 'click' , '#update-document' , function(e)
    {
        var modal = $('#documents_Modal');
        $.ajax(
        {
            type: 'post',
            url: server + 'update-document' ,
            data: 
            {
                id : modal.find( 'input[name=idDocumento]' ).val() ,
                idregimen : modal.find( '#regimen' ).val() ,
                monto : modal.find( '#monto-regimen' ).val() ,
                _token : GBREPORTS.token
            }
        }).fail( function( statusCode , errorThrown )
        {
            ajaxError( statusCode , errorThrown );
        }).done( function ( response ) 
        {
            if ( response.Status == 'Ok' )
            {
                bootbox.alert('<h4 class="green">Documento Actualizado</h4>')
                modal.modal('hide');
            }
            else
                bootbox.alert('<h4 class="red">' + data.Status + ': ' + data.Description + '</h4>' );
        });
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

    $('#ruc').click( function()
    {
        $(this).removeClass('error-incomplete').attr( 'placeholder' , '' );
    });

    $('#number-prefix').click( function()
    {
        $(this).removeClass('error-incomplete').attr( 'placeholder' , '' );
    });

    $('#number-serie').click( function()
    {
        $(this).removeClass('error-incomplete').attr( 'placeholder' , '' );
    });

    $('#desc-expense').click( function()
    {
        $(this).removeClass('error-incomplete').attr( 'placeholder' , '' );
    });

    $('.quantity input').click( function()
    {
        $(this).removeClass('error-incomplete').attr( 'placeholder' , '' );
    });

    $('.description input').click( function()
    {
        $(this).removeClass('error-incomplete').attr( 'placeholder' , '' );
    });

    $('.total-item input').click( function()
    {
        $(this).removeClass('error-incomplete').attr( 'placeholder' , '' );
    });

    $('#edit-user').click( function()
    {
        $( "#user-seeker" ).typeahead( 'val' , '').attr( 'readonly' , false ).attr( 'data-cod' , '' ).parent().parent().removeClass( 'has-success' );
    });

    $('#button-confirm-temporal-user').click( function()
    {
        var user = $( '#user-seeker' );
        if ( user.parent().parent().hasClass( 'has-success' ) === false )
        {
            $('#modal-temporal-user').modal('hide');
            responseUI( 'Ingrese el Usuario' , 'red' );
        }
        else
        {
            if ( user.attr( 'data-cod' ).trim() === '' )
            {
                $('#modal-temporal-user').modal('hide');
                responseUI( 'No se encontrol el Id del Usuario' + user.val() , 'red' );
            }
            else
            {
                $.ajax(
                {
                    type : 'POST' ,
                    url  : server + 'confirm-temporal-user' ,
                    data :
                    {
                        _token : $( 'input[name=_token]').val() ,
                        iduser : $('#user-seeker').attr('data-cod')
                    }
                }).fail( function( statusCode , errorThrown )
                {
                    ajaxError( statusCode , errorThrown );
                }).done( function( response )
                {
                    if ( response.Status === 'Ok' )
                    {
                        $('#modal-temporal-user').modal('hide');
                        responseUI( 'Asignacion Correcta' , 'green' );
                        window.location.href = server + 'show_user';
                    }
                    else
                        bootbox.alert( '<h4 class="red">' + response.Status + ': ' + response.Description + '</h4>' );
                });
            }  
        }
    });

    $('#button-remove-temporal-user').click( function()
    {
         $.ajax(
        {
            type : 'GET' ,
            url  : server + 'remove-temporal-user'
        }).fail( function( statusCode , errorThrown )
        {
            ajaxError( statusCode , errorThrown );
        }).done( function( response )
        {   
            if ( response.Status === 'Ok' )
            {
                $('#modal-temporal-user').modal('hide');
                responseUI( 'Se quito la asignacion' , 'green' );
                window.location.href = server + 'show_user';
            }
            else
                bootbox.alert( '<h4 class="red">' + response.Status + ': ' + response.Description + '</h4>' );       
        });
    });

    $(document).off("click", ".btn-group-xs");
    $(document).on("click", ".btn-group-xs", function()
    {
        desc = this.parentElement.childNodes[0].innerHTML.split(":");
        operation = this.getElementsByTagName('input')[0].checked;
        
        if (operation)
        {
            
            text = "SUM:" + desc[1];
        }
        else
        {
            text = "COUNT:" + desc[1];
        }
        this.parentElement.childNodes[0].innerHTML = text;
    });
    //Carrousel photo events
    $(document).off("click", ".photosEvent");
    $(document).on("click", ".photosEvent", function(e){
        e.preventDefault();
        var event_id = $(this).data('event');
        if(event_id){
            $.ajax({
                type: 'post',
                url: server + 'photos',
                data: 
                {
                    _token : $('input[name=_token]').val(),
                    event_id  : event_id
                }
            }).fail( function( statusCode , errorThrow )
            {
                bootbox.alert("error" );
            }).done( function ( data ) 
            {
                bootbox.dialog({
                    message: data,
                    title  : "Galería de Fotos",
                    size   : "large"
                });
                $('.carousel').carousel();
            }); 
        }
    });

    $('#confirm-devolucion').on( 'click' , function()
    {
        bootbox.confirm( '<h4 class="text-center text-info">Confirme la devolucion</h4>' , function( response )
        {
            if( response )
            {
                var data = { _token : GBREPORTS.token , token : $( 'input[name=token]' ).val() };
                $.post( server + 'confirm-devolution' , data )
                .done(function ( response ) 
                {
                    if ( response.Status == 'Ok' )
                        bootbox.alert( '<h4 class="green">Confirmacion Correcta</h4>' , function()
                        {
                            window.location.href = server + 'show_user';
                        });
                    else
                        bootbox.alert( '<h4 style="color:red">' + response.Status + ' : ' + response.Description + '</h4>' );
                });
            }  
        });
    });

    $( document ).off( 'click' , '#periodo' );
    $( document ).on( 'click' , '#periodo' , function()
    {
        $( this ).parent().parent().removeClass( 'has-error' );
    });

    $( '#confirm-discount' ).on( 'click' , function()
    {
        var element = $( this );
        var html    = '<div class="form-group">' +
                      '<label class="control-label">Periodo del Descuento</label>' +
                      '<div>' +
                      '<input type="text" id="periodo" class="form-control date" size=7 placeholder="Seleccione el Periodo MM-YYYY" readonly>' +
                      '</div>'+
                      '</div>';
        var object  = $( html );
        object.find( '#periodo' ).datepicker(
        {
            format: 'mm-yyyy',
            startDate: '+0m',
            endDate: '+1y',
            minViewMode: 1,
            language: "es",
            orientation: "top",
            autoclose: true
        });

        bootbox.dialog({
            title: 'Confirmacion del Descuento',
            message: object ,
            buttons: 
            {
                danger: 
                {
                    label:'Cancelar',
                    className: 'btn-primary',
                    callback: function() 
                    {
                        bootbox.hideAll();
                    }
                },
                success: 
                {
                    label: 'Confirmar',
                    className: 'btn-success',
                    callback: function( response )
                    {
                        if ( $( '#periodo' ).val().trim() == '' )
                        {
                            $( '#periodo' ).parent().parent().addClass( 'has-error' ).focus();
                            return false;
                        }
                        else
                        {
                            var data = {};
                            data.periodo = $( '#periodo' ).val();
                            if ( response )
                            {
                                bootbox.confirm( '<h4 class="text-center text-warning">¿ Esta seguro de realizar el descuento en el Periodo ' + data.periodo + ' ?</h4>', function( response ) 
                                {    
                                    if ( response ) 
                                    {
                                        data._token   = GBREPORTS.token;
                                        data.token    = $( 'input[name=token]' ).val();
                                        $.post( server + 'confirm-discount' , data )
                                        .done(function ( response ) 
                                        {
                                            if ( response.Status == 'Ok' )
                                                bootbox.alert( '<h4 class="green">Descuento Confirmado</h4>' , function()
                                                {
                                                    element.remove();
                                                });
                                            else
                                                bootbox.alert( '<h4 style="color:red">' + response.Status + ' : ' + response.Description + '</h4>' );
                                        });
                                    }    
                                });
                            }
                        }
                    }
                }
            }
        });
    });
}); 