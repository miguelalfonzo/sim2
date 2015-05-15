newSolicitude();
function newSolicitude() {

    /* Declare Variables */
    //MENU
    var date_start = $('.date_start').first();
    var date_end = $('.date_end').first();

    //NEW SOLICITUD
    var title = $('input[name=titulo]');
    var clientes = $('#clientes');
    var search_cliente = $('.cliente-seeker');
    var amount = $('input[name=monto]');
    var delivery_date = $('input[name=fecha]');
    var amount_fac = $('input[name=monto_factura]');
    var selectfamily = $('.selectfamily');
    var ruc = $('input[name=ruc]');
    var select_type_payment = $('select[name=pago]');
    var input_file_factura = $('#input-file-factura');
    var isSetImage = $('#isSetImage');
    var comprobante = $('#comprobante');
    

    var doc_start = $('.date_start').last();
    var doc_end = $('.date_end').last();

    //VALIDACION DE MONTOS DE FAMILIAS
    var amount_error_families = $('#amount_error_families');
    
    //TIPO DE USUARIO
    var userType = $('#typeUser').val();//tipo de usuario se encuentra en main
    
    //SUB-ESTADOS
    var PENDIENTE =1;
    var ACEPTADO = 2;
    var APROBADO = 3;
    var DEPOSITADO = 4;
    var REGISTRADO = 5;
    var ENTREGADO = 6;
    var GENERADO = 7;
    var CANCELADO =  8;
    var RECHAZADO = 9;
    var TODOS = 10;
    var DERIVADO = 11;
    var POR_DEPOSITAR = 13;
    var POR_REGISTRAR = 12;

    //NUEVOS ESTADOS
    var R_PENDIENTE = 1;
    var R_APROBADO = 2;
    var R_REVISADO = 3;
    var R_GASTOS = 4;
    var R_FINALIZADO = 5;
    var R_NO_AUTORIZADO = 6;
    var R_TODOS = 10;
    //USERS
    var REP_MED   = 'R';
    var SUP       = 'S';
    var GER_PROD  = 'P';
    var GER_COM   = 'G';
    var CONT      = 'C';
    var TESORERIA = 'T';
    var ASIS_GER  = 'AG';

    var date_options2 = 
    {
        format: 'mm-yyyy',
        startDate: '-1y',
        minViewMode: 1,
        language: "es",
        autoclose: true
    };

    $(document).off("click", ".timeLine");
    $(document).on("click", ".timeLine", function(e){
        e.preventDefault();
        var state = parseInt($(this).parent().parent().parent().find('#timeLineStatus').val(), 10);
        var accept = $(this).parent().parent().parent().find('#timeLineStatus').data('accept');
        var rejected = $(this).parent().parent().parent().find('#timeLineStatus').data('rejected');
        var html  = $('.timeLineModal').clone();
        html.find('.container-fluid').removeClass('hide');
        if(state == DERIVADO){
            for (var i = 0 ; i < 2; i++) {
                html.find('.stage').eq(i).addClass('success');
                html.find('.stage .stage-header').eq(i).addClass('stage-success');
            }
            html.find('.stage').eq(2).addClass('pending');
            html.find('.stage .stage-header').eq(2).addClass('stage-pending');
        }else if(state == POR_DEPOSITAR){
            for (var i = 0 ; i <= 3; i++) {
                if(accept == 'S' && i != 2){
                    html.find('.stage').eq(i).addClass('success');
                    html.find('.stage .stage-header').eq(i).addClass('stage-success');
                }else if(accept == 'P'){
                    html.find('.stage').eq(i).addClass('success');
                    html.find('.stage .stage-header').eq(i).addClass('stage-success');
                }
            }
            html.find('.stage').eq(4).addClass('pending');
            html.find('.stage .stage-header').eq(4).addClass('stage-pending');
        }else if(state == POR_REGISTRAR){
            for (var i = 0 ; i <= 5; i++) {
                if(accept == 'S' && i != 2){
                    html.find('.stage').eq(i).addClass('success');
                    html.find('.stage .stage-header').eq(i).addClass('stage-success');
                }else if(accept == 'P'){
                    html.find('.stage').eq(i).addClass('success');
                    html.find('.stage .stage-header').eq(i).addClass('stage-success');
                }
            }
            html.find('.stage').eq(6).addClass('pending');
            html.find('.stage .stage-header').eq(6).addClass('stage-pending');
        }else if(state == REGISTRADO){
            for (var i = 0 ; i <= 6; i++) {
                if(accept == 'S' && i != 2){
                    html.find('.stage').eq(i).addClass('success');
                    html.find('.stage .stage-header').eq(i).addClass('stage-success');
                }else if(accept == 'P'){
                    html.find('.stage').eq(i).addClass('success');
                    html.find('.stage .stage-header').eq(i).addClass('stage-success');
                }
            }
            html.find('.stage').eq(7).addClass('pending');
            html.find('.stage .stage-header').eq(7).addClass('stage-pending');
        }else if(state == ACEPTADO || state == APROBADO || state == DEPOSITADO){
            for (var i = 0 ; i <= state; i++) {
                if(accept == 'S' && i != 2){
                    html.find('.stage').eq(i).addClass('success');
                    html.find('.stage .stage-header').eq(i).addClass('stage-success');
                }else if(accept == 'P'){
                    html.find('.stage').eq(i).addClass('success');
                    html.find('.stage .stage-header').eq(i).addClass('stage-success');
                }
            }
            html.find('.stage').eq(state+1).addClass('pending');
            html.find('.stage .stage-header').eq(state+1).addClass('stage-pending');
        }else if(state == CANCELADO){
            html.find('.stage').eq(0).addClass('rejected');
            html.find('.stage .stage-header').eq(0).addClass('stage-rejected');
        }else if(state == RECHAZADO){
            if(rejected == 'S'){
                var pos = 1;
            }else if(rejected == 'P'){
                var pos = 2;
            }else if(rejected == 'G'){
                var pos = 3;
            }
            for (var i = 0 ; i < pos; i++) {
                html.find('.stage').eq(i).addClass('success');
                html.find('.stage .stage-header').eq(i).addClass('stage-success');
            }
            html.find('.stage').eq(pos).addClass('rejected');
            html.find('.stage .stage-header').eq(pos).addClass('stage-rejected');
        }
        var h     = $(html).html();
        bootbox.dialog({
            message: h,
            title: "Línea del Tiempo",
            buttons: {
                danger: {
                    label: "Cancelar",
                    className: "btn-default"
                }
            },
            size: "large"
        });
    });

    //NEW OR EDIT SOLICITUDE BY RM OR SUP CLIENTES
   /* $.getJSON(server + "getclients", function (data) {
        clients = data;
    });
    */
    //LEYENDA
    $('#show_leyenda').on('click',function(){
        $('#leyenda').show();
        $(this).hide();
        $('#hide_leyenda').show()
    });

    $('#hide_leyenda').on('click',function(){
        $('#leyenda').hide();
        $(this).hide();
        $('#show_leyenda').show();
    });

    //add a family
    $("#btn-add-family").on('click', function () {

        $(".btn-delete-family").show();
        $('#listfamily>li:first-child').clone(true, true).appendTo('#listfamily');
    });

    //delete a family
    $(document).on("click", ".btn-delete-family", function () 
    {
        $('#listfamily>li .porcentaje_error').css({"border": "0"});
        $(".option-des-1").removeClass('error');
        $('.families_repeat').text('');
        var k = $("#listfamily li").size();
        if (k > 1)
            var other = $(".btn-delete-family").index(this);
            $("#listfamily li").eq(other).remove();
            var p = $("#listfamily li").size();
            if (p == 1)
                $(".btn-delete-family").hide();
    });

    //Validations
    amount.numeric({negative:false});
    amount_fac.numeric({negative:false});
    ruc.numeric({negative:false,decimal:false});
    
    if ( $('select[name=actividad] option:selected').attr('image') != 1 ) 
    {
        amount_fac.parent().parent().hide();
        comprobante.hide();
    }
    else if ( $('select[name=actividad] option:selected').attr('image') == 1 )
    {
        amount_fac.parent().parent().show();
        comprobante.show();        
    }

    $( 'select[name=inversion]' ).on( 'change' , function()
    {
        inversionChange( $(this).val() );
    }

    $( 'select[name=actividad]' ).on( 'change' , function() 
    {
        activityChange();
    });

    function inversionChange( id_inversion )
    {
        console.log( id_inversion );
        $.ajax(
        {
            url: server + 'cambio-inversion',
            type: 'POST' ,
            data:
            {
                _token : $('input[name=_token]').val(),
                id_inversion : id_inversion
            }
        }).fail( function ( statusCode , errorThrown )
        {
            ajaxError( statusCode , errorThrown );
        }).done( function ( response )
        {
            if ( response.Status === 'Ok' )
            {
                $('select[name=actividad]').val('');
                filterSelect( $('select[name=actividad]') , response.Data.id_actividad );
                activityChange();
            }
            else
                bootbox.alert( '<h4>' + response.Status + ': ' + response.Description + '</h4>');
        });
    }

    function activityChange()
    {
        if ( $('select[name=actividad] option:selected').attr('image') != 1 ) 
        {
            amount_fac.parent().parent().hide();
            comprobante.hide();
        }
        else if ( $('select[name=actividad] option:selected').attr('image') == 1 )
        {
            amount_fac.parent().parent().show();
            comprobante.show();        
        }
    }


    //TIPO DE ENTREGA
    if( select_type_payment.val()!=2 )  
        ruc.parent().parent().hide();
    
    select_type_payment.on('change', function()
    {
        if($(this).val() == 1)
            ruc.parent().parent().hide();
        else if($(this).val()== 2)
            ruc.parent().parent().show();
    });

    title.on('focus', function () {
        $(this).parent().removeClass('has-error');
    });
    amount.on('focus', function () {
        $(this).parent().removeClass('has-error');
    });
    delivery_date.on('focus', function () {
        $(this).parent().removeClass('has-error');
    });
    search_cliente.on('focus', function () {
        $(this).parent().parent().removeClass('has-error');
        clientes.parent().parent().removeClass('has-error');
    });
    amount_fac.on('focus', function () {
        $(this).parent().removeClass('has-error')
    });
    selectfamily.on('click', function () {
        $(this).css('border-color', 'none')
    });
    date_start.on('focus', function () {
        $(this).parent().removeClass('has-error');
    });
    date_end.on('focus', function () {
        $(this).parent().removeClass('has-error');
    });
    input_file_factura.on('focus', function () {
        $(this).parent().removeClass('has-error');
    });
    ruc.on('focus', function () {
        $(this).parent().removeClass('has-error');
    });

    function listMaintenanceTable( type )
    {
        $.ajax(
        {
            url: server + 'get-table-maintenance-info',
            type: 'POST' ,
            data:
            {
                _token : $('input[name=_token]').val(),
                type   : type
            }
        }).fail( function ( statusCode , errorThrown )
        {
            ajaxError( statusCode , errorThrown );
        }).done(function ( response ) 
        {
            if ( response.Status == 'Ok' )
            {
                $('#table_' + type + '_wrapper').remove();
                $('.table_' + type ).append(response.Data);
                $('#table_' + type ).dataTable(
                {
                    "order": [ [ 0, "desc" ] ],
                    "bLengthChange": false,
                    'iDisplayLength': 7,
                    "oLanguage": 
                    {
                        "sSearch": "Buscar: ",
                        "sZeroRecords": "No hay Informacion",
                        "sInfoEmpty": "No hay Informacion",
                        "sInfo": 'Mostrando _END_ de _TOTAL_',
                        "oPaginate": 
                        {
                            "sPrevious": "Anterior",
                            "sNext" : "Siguiente"
                        }
                    }
                });
            }
            else
                bootbox.alert('<h4 class="red">' + response.Status + ': ' + response.Description + '</h4>');
        });
    }

    //Function list Solicitude
    function listSolicitude()
    {
        state = $('#idState').val();
        var l = Ladda.create($("#search-solicitude")[0]);
        l.start();
        $.ajax(
        {
            url: server + 'buscar-solicitudes',
            type: 'POST',
            data:
            { 
                idstate: state,
                date_start: date_start.val(), 
                date_end: date_end.val(),
                _token : $('input[name=_token]').val() 
            }
        }).fail( function ( statusCode , errorThrown )
        {
            l.stop();
            ajaxError( statusCode , errorThrown );
        }).done(function (data) 
        {
            l.stop();
            if (data.Status == 'Ok' )
            {
                $('#table_solicitude_wrapper').remove();
                $('.table-solicituds').append(data.Data);
                $('#table_solicitude').dataTable(
                {
                    "order": 
                    [
                        [ 3, "desc" ] //order date
                    ],
                    "bLengthChange": false,
                    'iDisplayLength': 7,
                    "oLanguage": 
                    {
                        "sSearch": "Buscar: ",
                        "sZeroRecords": "No hay solicitudes",
                        "sInfoEmpty": "No hay solicitudes",
                        "sInfo": 'Mostrando _END_ de _TOTAL_',
                        "oPaginate": 
                        {
                            "sPrevious": "Anterior",
                            "sNext" : "Siguiente"
                        }
                    }
                });
            }
            else
                responseUI(data.Status + ': ' + data.Description,'red');
        });
    }

    function listDocuments()
    {
        var l = Ladda.create( $("#search-documents")[0] );
        l.start();
        
        $.ajax({
            url: server + 'list-documents',
            type: 'POST',
            data:
            {
                idProof      : $('#idProof').val() ,
                date_start   : doc_start.val() ,
                date_end     : doc_end.val() ,
                val          : $('#doc-search-key').val() ,
                _token       : $('input[name=_token]').val()
            }
        }).fail( function ( statusCode , errorThrown)
        {
            l.stop();
            ajaxError( statusCode , errorThrown );
        }).done(function (data)
        {
            l.stop();
            if ( data.Status == 'Ok' )
            {
                $("#table_documents_wrapper").remove();
                $('.table-documents').append(data.Data);
                $('#table_documents').dataTable(
                {
                    "order": [ [ 0, "desc" ] ],
                    "bLengthChange": false,
                    'iDisplayLength': 7,
                    "oLanguage": 
                    {
                        "sSearch": "Buscar: ",
                        "sZeroRecords": "No hay fondos",
                        "sInfoEmpty": "No hay fondos",
                        "sInfo": 'Mostrando _END_ de _TOTAL_',
                        "oPaginate": 
                        {
                            "sPrevious": "Anterior",
                            "sNext" : "Siguiente"
                        }
                    }
                });
            }
            else
                bootbox.alert( '<h4 class=red>' + data.Status + ': ' + data.Description + '</h4>');
        });
    }

    function listDocumentsType()
    {
        $.ajax({
            url: server + 'list-documents-type',
            type: 'GET',
            dataType: 'html'
        }).fail( function ( statusCode , errorThrown)
        {
            ajaxError( statusCode , errorThrown );
        }).done(function (data) {
            $("#table_document_contabilidad_wrapper").remove();
            $('.table_document_contabilidad').append(data);
            $('#table_document_contabilidad').dataTable(
            {
                "order": [ [ 0, "desc" ] ],
                "bLengthChange": false,
                'iDisplayLength': 7,
                "oLanguage": 
                {
                    "sSearch": "Buscar: ",
                    "sZeroRecords": "No hay fondos",
                    "sInfoEmpty": "No hay fondos",
                    "sInfo": 'Mostrando _END_ de _TOTAL_',
                    "oPaginate": 
                    {
                        "sPrevious": "Anterior",
                        "sNext" : "Siguiente"
                    }
                }
            });
        });
    }

    $('#derived').click( function()
    {
        var pdata = 
        {
            _token : $('input[name=_token]').val() ,
            idsolicitud : $('input[name=idsolicitude]').val()
        };
        $.ajax({
            url  : server + 'buscar-gerprod',
            type : 'POST',
            data : pdata
        }).fail( function ( statusCode , errorThrown )
        {
            ajaxError( statusCode , errorThrown );
        }).done( function ( data ) 
        {
            if ( data.Status != 'Ok' )
                bootbox.alert('<h4 class="red">' + data.Status + ' ' + data.Description + '</h4>');
            else
            {
                var list='';
                data.Data.forEach(function(entry)
                {
                    list = list + '<li><input type="radio" name="responsables"  value="' + entry.iduser + '"> ' + entry.descripcion + '</li>';    
                });
                bootbox.confirm("<h4 id='validate'>Seleccione al Responsable</h4><ul>" + list + "</ul>", function (result) 
                {
                    if (result) 
                    {
                        var resp = $("input[name=responsables]:checked");
                        if (resp.length == 0 )
                        {
                            $("#validate").css("color","red");
                            return false; 
                        }
                        
                        pdata.gerente = resp.val();
                        
                        $.ajax({
                        url: server + 'derivar-solicitud',
                        type: 'POST',
                        data : pdata
                        }).fail( function ( statusCode , errorThrown )
                        {
                            ajaxError( statusCode , errorThrown );
                        }).done( function ( data ) 
                        {
                            if ( data.Status != 'Ok' )
                                bootbox.alert('<h4 class="red">' + data.Status + ' ' + data.Description + '</h4>');
                            else
                            {
                                bootbox.alert('<h4 class="green">Solicitud Derivada</h4>' , function() 
                                {
                                    window.location.href = server +"show_user";
                                });  
                            }
                        });
                    }
                });
            }
        });      
    });


    // -------------------------------------  REPRESENTANTE MEDICO -----------------------------
    
    if ( $("#idState").length === 1 )
        listSolicitude();

    //Register Deposit
    $(document).off( "click" , ".register-deposit" );
    $(document).on( "click" , ".register-deposit" , function( e )
    {
        e.preventDefault();
        var url = 'deposit-solicitude';
        var data = {};
        data.op_number      = $("#op-number").val();
        data.token          = $("input[name=token]").val();
        data._token         = $("input[name=_token]").val();
        data.num_cuenta     = $("#bank_account").val();

        if ($("#op-number").val().trim() === "")
            $("#message-op-number").text("Ingrese el número de Operación");
        else
        {
            $.post(server + url, data)
            .done(function (data)
            {
                if(data.Status == 'Ok')
                {
                    $('#myModal').modal('hide');
                    $("#enable_deposit_Modal").modal('hide');
                    $("#op-number").val('');
                    bootbox.alert("<h4 class='green'>Se registro el codigo de deposito correctamente.</h4>", function()
                    {
                        if ($("tbody").length == 0)
                            window.location.href = server +"show_user";
                        else
                            listSolicitude();
                    });
                }
                else
                    bootbox.alert("<h4 class='red'>" + data.Status + ': ' + data.Description + "'.</h4>") ;    
            });
        }
    });

    /* Cancel Solicitude */
    var cancel_solicitude = '.cancel-solicitude';
    $(document).on('click', cancel_solicitude, function (e) 
    {
        e.preventDefault();
        elem = $( this );
        var data = 
        {
            idsolicitud: elem.attr('data-idsolicitude') ,
            _token : elem.attr('data-token')
        };
        cancelDialog( data );
    });

    $(document).on('click' , '.delete-fondo' , function (e)
    {
        e.preventDefault();
        var data = 
        {
            idsolicitud: $( this ).parent().parent().parent().children().first().text().trim() ,
            _token: $('input[name=_token]').val()
        };
        cancelDialog( data );
    });

    function cancelDialog  ( data )
    {
        bootbox.dialog(
        {
            title : '<h4>¿Esta seguro que desea cancelar esta solicitud?</h4>',
            message :  '<div class="form-group">' +
                       '<label class="control-label">Observación</label> ' +
                       '<div><textarea class="form-control sol-obs" maxlength="200"></textarea></div>' +
                       '</div>',          
            onEscape: function () 
            {
                bootbox.hideAll();
            },
            buttons: 
            {
                danger: 
                {
                    label:'Cancelar',
                    className: 'btn-primary',
                    callback: function ( result ) 
                    {
                        bootbox.hideAll();
                    }
                },
                success:
                { 
                    label :'Aceptar',
                    className: 'btn-default',
                    callback : function ( result ) 
                    {
                        if ($(".sol-obs").val() == "" )
                        {
                            $(".sol-obs").attr("placeholder","Ingresar Observación").parent().parent().addClass("has-error").focus();
                            return false;
                        }
                        else
                        {
                            if ( result )
                            {
                                data['observacion'] = $('.sol-obs').val();
                                $.post(server + 'cancelar-solicitud', data )
                                .done(function ( data ) 
                                {
                                    if ( data.Status == 'Ok')
                                    {
                                        bootbox.alert('<h4 class="green">Solicitud Cancelada</h4>' , function ()
                                        {
                                            if ( data.Type == 1 )
                                            {
                                                $("#idState").val(6);
                                                listSolicitude();
                                            }
                                            else if ( data.Type == 2)
                                                searchFondos( $('.date_month').first().val() );   
                                        });
                                    }
                                    else
                                        bootbox.alert('<h4 style="color:red">' + data.Status + ': ' + data.Description +'</h4>');
                                });
                            }
                        }
                    }
                }
            }
        });
    }

    /**------------------------------------------------ SUPERVISOR ---------------------------------------------------*/

    var amount_families = $('.amount_families');
    amount_families.numeric({negative: false});
    
    /*idamount.keyup( function ()
    {
        verifySum( this , 2 );
    });*/

    amount_families.keyup( function ()
    {
        verifySum( this , 1 );
    });

    function verifySum( element , type )
    {
        var sum_total = 0;
        var precision = 11;
        amount_families.each(function(i,v)
        {
            sum_total += parseFloat( $(this).val() );
        });
        
        if( $("#amount").val().trim() === "" )
        {
            amount_error_families.text('Ingresar el monto (Vacío)').css('color', 'red');
            idamount.parent().parent().parent().removeClass("has-success").addClass("has-error");
        }     
        else if ( parseFloat( $("#amount").val() ) === 0 )
        {
            amount_error_families.text('El monto especificado no debe ser igual a 0').css('color', 'red');
            idamount.parent().parent().parent().removeClass("has-success").addClass("has-error");
        }
        else if ( parseFloat( $("#amount").val() ) < 0 )
        {
            amount_error_families.text('El monto especificado no debe ser menor a 0').css('color', 'red');
            idamount.parent().parent().parent().removeClass("has-success").addClass("has-error");
        }
        else if ( type == 1 && $( element ).val().trim() === "" ) 
        {
            amount_error_families.text('Ingresar el monto de la familia').css('color', 'red');
            idamount.parent().parent().parent().removeClass("has-success").addClass("has-error");
        }
        else if ( type == 1 && parseFloat( $( element ).val() ) === 0 ) 
        {
            amount_error_families.text('El monto de la familia no debe ser igual a 0').css('color', 'red');
            idamount.parent().parent().parent().removeClass("has-success").addClass("has-error");
        }
        else if ( type == 1 && parseFloat( $( element ).val() ) < 0 ) 
        {
            amount_error_families.text('El monto de la familia no debe ser menora 0').css('color', 'red');
            idamount.parent().parent().parent().removeClass("has-success").addClass("has-error");
        }
        else if ( type == 1 && ( parseFloat( $( element ).val() ) > parseFloat( idamount.val() ) ) )
        {
            amount_error_families.text('El monto de la familia supera al monto especificado').css('color', 'red');
            idamount.parent().parent().parent().removeClass("has-success").addClass("has-error");
        }
        else if ( parseFloat( sum_total ) > parseFloat( idamount.val() ) )
        {
            amount_error_families.text('El monto total de las familias supera al monto especificado').css('color', 'red');
            idamount.parent().parent().parent().removeClass("has-success").addClass("has-error");
        }
        else if ( parseFloat( sum_total ) < parseFloat( idamount.val() ) )
        {
            amount_error_families.text('El monto total de las familias es menor al monto especificado').css('color', 'red');
            idamount.parent().parent().parent().removeClass("has-success").addClass("has-error");
        }
        else if ( parseFloat( sum_total ) == parseFloat( idamount.val() ) )
        {
            amount_error_families.text('Los montos asignados son iguales al monto especificado').css('color', 'green');    
            idamount.parent().parent().parent().removeClass("has-error").addClass("has-success");
        }
    }

    var form_acepted_solicitude = $('#form_make_activity');

    $(".date_start").datepicker({
        language: 'es',
        endDate: new Date(),
        format: 'dd/mm/yyyy'
    });

    $(".date_end").datepicker({
        //startDate: new Date($.datepicker.formatDate('dd, mm, yy', new Date($('#date_start').val()))),
        language: 'es',
        endDate: new Date(),
        format: 'dd/mm/yyyy'
    });

    $('.btn-file :file').on('fileselect', function (event, numFiles, label) {

        input_file_factura.val(label);
    });

    $(document).on('change', '.btn-file :file', function (e) {

        var file = e.target.files[0],
            imageType = /image.*/;
        if (!file.type.match(imageType))
            alert('ingrese solo imagenes');
        else 
        {
            var input = $(this),
            numFiles = input.get(0).files ? input.get(0).files.length : 1,
            label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
            input.trigger('fileselect', [numFiles, label]);
        }
    });

    /** ---------------------------------------------- GERENTE PRODUCTO -------------------------------------------- **/

    if ( $('#deny_solicitude').length != 0 )
    {
        verifySum( idamount[0] , 2);
    }

    $('#deny_solicitude').on('click', function (e) {

        if ( $(".sol-obs").val() == "" )
        {
            $(".sol-obs").attr("placeholder","Ingresar Observación").parent().parent().addClass("has-error").focus();
        }
        else
        {
            bootbox.confirm('¿Esta seguro que desea rechazar esta solicitud?', function (result) 
            {
                if (result) 
                {
                    $.post(server + 'rechazar-solicitud', form_acepted_solicitude.serialize()).done(function(data)
                    {
                        if(data.Status === 'Ok')
                        {
                            bootbox.alert('<h4 style="color: green">Solicitud Rechazada</h4>' , function()
                            {
                                window.location.href = server + 'show_user';
                            });
                        }
                        else
                            bootbox.alert('<h4 style="color: red">' + data.Status + ': '+ data.Description + '</h4>');
                    });
                }
            });
        }
    });
    
    /** --------------------------------------------- CONTABILIDAD ------------------------------------------------- **/

    if ( $("#search-solicitude").length == 1 )
    {
        var section = $('.maintenance-add');
        section.each( function()
        {
            listMaintenanceTable( $(this).attr("case") );
        });
        if( userType === 'C')
            listDocumentsType();          
    }

    /** --------------------------------------------- ASISTENCIA DE GERENCIA ------------------------------------------------- **/

    var fondo_repmed = $('#fondo_repmed');
    var fondo_total = $('#fondo_total');
    var fondo_cuenta = $('#fondo_cuenta');
    var fondo_supervisor = $('#fondo_supervisor');
    var fondo_institucion = $('#fondo_institucion');
    var date_reg_fondo = $('.date_month[data-type=fondos]');

    fondo_repmed.on('focus', function () {
        $(this).parent().removeClass('has-error');
    });
    fondo_cuenta.on('focus', function () {
        $(this).parent().removeClass('has-error');
    });
    fondo_total.on('focus', function () {
        $(this).parent().removeClass('has-error');
    });
    date_reg_fondo.on('focus', function () {
        $(this).parent().parent().removeClass('has-error');
    });
    fondo_institucion.on('focus', function() {
        $(this).parent().removeClass('has-error');     
    });
    fondo_supervisor.on('focus', function() {
        $(this).parent().removeClass('has-error');     
    });

    $('#edit-rep').hide();    
    fondo_total.numeric();

    $(document).on( 'click' , '.register_fondo' , function()
    {
        var validate = 0;
        var aux = this;
        if(!date_reg_fondo.val()){
            date_reg_fondo.parent().parent().addClass('has-error');
            date_reg_fondo.attr('placeholder', 'Ingrese Mes');
            date_reg_fondo.addClass('input-placeholder-error');
            validate = 1;
        }
        if(!fondo_total.val()){
            fondo_total.parent().addClass('has-error');
            fondo_total.attr('placeholder', 'Ingrese Cantidad a depositar');
            fondo_total.addClass('input-placeholder-error');
            validate = 1;
        }
        if(!fondo_cuenta.val()){
            fondo_cuenta.parent().addClass('has-error');
            fondo_cuenta.attr('placeholder', 'Ingrese Cuenta');
            fondo_cuenta.addClass('input-placeholder-error');
            validate = 1;
        }
        if(!fondo_repmed.val()){
            fondo_repmed.parent().addClass('has-error');
            fondo_repmed.attr('placeholder', 'Ingrese Representante');
            fondo_repmed.addClass('input-placeholder-error');
            validate = 1;
        }
        if(fondo_repmed.attr('data-select') == 'false'){
            fondo_repmed.val('');
            fondo_repmed.parent().addClass('has-error');
            fondo_repmed.attr('placeholder', 'Ingrese Representante');
            fondo_repmed.addClass('input-placeholder-error');
            validate = 1;
        }
        if (!fondo_institucion.val())
        {
            fondo_institucion.parent().addClass('has-error');
            fondo_institucion.attr('placeholder','Ingrese Institución');
            validate = 1;
        }
        if (!fondo_supervisor.val())
        {
            fondo_supervisor.parent().addClass('has-error');
            fondo_supervisor.attr('placeholder','Ingrese Supervisor');
            validate = 1;
        }
        if(validate == 0)
        {
            var periodo = $(this).parent().parent().parent().find(".date_month").val();
            var dato = 
            {
                'institucion' : fondo_institucion.val(),
                'idetiqueta'  : $("select[name=etiqueta]").val(),
                'repmed'      : fondo_repmed.val(),
                'codrepmed'   : fondo_repmed.attr('data-cod'),
                'supervisor'  : fondo_supervisor.val(),
                'codsup'      : fondo_supervisor.attr('data-cod'),
                'total'       : fondo_total.val(),
                'cuenta'      : fondo_cuenta.val(),
                '_token'      : $('input[name=_token]').val(),
                'idfondo'     : $("#sub_type_activity option:selected").val(),
                'mes'         : periodo
            };
            date_reg_fondo.last().val(date_reg_fondo.val());
            var l = Ladda.create(aux);
            l.start();
            $.post(server + 'registrar-fondo', dato )
            .fail( function ( statusCode , errorThrown )
            {
                l.stop();
                ajaxError( statusCode , errorThrown );
            }).done(function(data)
            {
                if (data.Status == 'Ok')
                {
                    fondo_institucion.val('');
                    fondo_repmed.val('');
                    fondo_supervisor.val('');
                    fondo_total.val('');
                    fondo_cuenta.val('');
                    fondo_repmed.val('');    
                    removeinput($('#edit-rep'));
                    bootbox.alert('<h4 class="green">Fondo Registrado</h4>' , function()
                    {
                        searchFondos( periodo );
                    });
                }
                else
                    bootbox.alert("<h4 style='color:red'>No se pudo registrar el fondo: " + data.Description + "</h4>");
                l.stop();
            });
        }
    });
    
    $("#search_responsable").on('click', function(e)
    {
        var div_monto = $("label[for=amount]").parent();
        if ( div_monto.hasClass("has-error") )
            idamount.focus();
        else if( div_monto.hasClass("has-success"))
        {
            $.ajax(
            {
                type: 'POST',
                url : server + 'buscar-responsable',
                data: 
                {
                    idfondo: $("#sub_type_activity").val(),
                    idsolicitude : $("input[name=idsolicitude]").val(),
                    _token: $("input[name=_token]").val()
                }
            }).fail( function ( statusCode, errorThrown)
            {
                ajaxError(statusCode,errorThrown);   
            }).done(function (data)
            {
                if (data.Status == 'Ok' )
                {
                    var list='';
                    data.Data.forEach(function(entry)
                    {
                        list = list + '<li><input type="radio" name="responsables"  value="' + entry.iduser + '"> ' + entry.nombres + ' ' + entry.apellidos + '</li>';    
                    });
                    bootbox.confirm("<h5 id='validate'>Seleccione el Responsable</h5><ul>" + list + "</ul>", function (result) 
                    {
                        if (result) 
                        {
                            var resp = $("input[name=responsables]:checked");
                            if (resp.length == 0 )
                            {
                                $("#validate").css("color","red");
                                return false; 
                            }
                            else
                                acceptedSolicitude(resp.val());
                        }
                    });
                }
                else
                    bootbox.alert("<h4 style='color:red'>" + data.Status + ": " + data.Description + "</h4>");
            });
        }
    });


    function acceptedSolicitude(idresp)
    {
        var formData = new FormData(form_acepted_solicitude[0]);
        formData.append("idresponsable",idresp);
        $.ajax(
        {
            type: 'POST',
            url :  server + 'aceptar-solicitud',
            data: formData,
            cache: false,
            contentType: false,
            processData: false
        }).fail(function (statusCode,errorThrown) 
        {
            ajaxError(statusCode,errorThrown);
        }).done(function (data)
        {
            if (data.Status == 'Error')
                responseUI('Hubo un error al procesar la solicitud','red');
            else if (data.Status == 'Warning')
                bootbox.alert("<h4 class='red'>" + data.Status + ": " + data.Description + "</h4>");
            else if (data.Status == 'Ok')
            {
                responseUI('Solicitud Procesada Correctamente','green');
                setTimeout(function()
                {
                    window.location.href = server + 'show_user';
                },800);
            }
        });
    }

    

    $('.btn_cancel_fondo').hide();
    $('.btn_edit_fondo').hide();

    $(document).on('click','#terminate-fondo',function(e)
    {
        e.preventDefault();
        var date = date_reg_fondo.val();
        bootbox.confirm(
        {
            message : '¿Esta seguro que desea terminar los fondos del periodo ' + date + '?',
            buttons: 
            {
                'cancel': {label: 'cancelar', className: 'btn-primary'},
                'confirm': {label: 'aceptar', className: 'btn-default'}
            },
            callback : function (result) 
            {
                if (result) 
                {
                    var url = server + 'endfondos/' + date;
                    $.get(url).done(function(data)
                    {
                        $('input[name=idsolicitud]').val('');
                        if (data.Status == 'Ok')
                        {
                            bootbox.alert('<h4 class="green">Fondos Terminados</h4>' , function()
                            {
                                searchFondos( date );
                            });
                        }
                        else
                            bootbox.alert("<h4 style='color:red'>No se pudo terminar los fondos - " + data.Description + "</h4>");   
                    });
                }
            }
        });
    });

    $(document).on('click','.edit-fondo' , function(e)
    {
        e.preventDefault();
        $('#table_solicitude_fondos > tbody > tr').css('background-color','');
        var tr = $(this).parent().parent().parent();
        var idsolicitud = tr.children().first().text().trim();
        data = 
        {
            'idsolicitud' : idsolicitud,
            '_token'      : $('input[name=_token]').val()
        };
        $.post( server + 'get-sol-inst' , data )
        .fail( function ( statusCode , errorThrown ) 
        {
            ajaxError( statusCode , errorThrown );
        }).done( function( data )
        {
            if ( data.Status == 'Ok' )
            {
                data = data.Data ;
                tr.css('background-color','#59A1F4');
                $('.btn_cancel_fondo').show();
                $('.btn_edit_fondo').show();
                $('.register_fondo').hide();
                $('#edit-rep').show();
            
                fondo_institucion.val(data.titulo);
                fondo_repmed.attr('disabled',true).attr('data-select',"true").parent().parent().addClass('has-success');
                fondo_repmed.val(data.rm);
                fondo_repmed.attr('data-cod',data.idrm)
                fondo_supervisor.val(data.supervisor).attr('disabled',true);
                fondo_supervisor.attr('data-cod',data.codsup).parent().addClass('has-success');
                fondo_total.val(data.monto);
                date_reg_fondo.val( data.periodo.substr(4,6) + '-' + data.periodo.substr(0,4) );
                fondo_cuenta.val(data.rep_cuenta).attr('disabled',true).parent().addClass('has-success');
                $('input[name=idsolicitud]').val(idsolicitud);
                $('select[name=idfondo]').val(data.idfondo);
                $('select[name=etiqueta]').val(data.idetiqueta);
            }
            else
                bootbox.alert('<h4 class=""red>' + data.Status + ': ' + data.Description + '</h4>');
        });
    });

    $(document).on('click','.btn_edit_fondo',function(e)
    {
        e.preventDefault();
        var aux = this;
        var dato = 
        {
            'idsolicitud'   : $('input[name=idsolicitud]').val(),
            'institucion'   : fondo_institucion.val(),
            'idetiqueta'    : $('select[name=etiqueta]').val(),
            'repmed'        : fondo_repmed.val(),
            'codrepmed'     : fondo_repmed.attr('data-cod'),
            'supervisor'    : fondo_supervisor.val(),
            'codsup'        : fondo_supervisor.attr('data-cod'),
            'total'         : fondo_total.val(),
            'cuenta'        : fondo_cuenta.val(),
            '_token'        : $('input[name=_token]').val(),
            'idfondo'       : $('select[name=idfondo]').val(),
            'mes'           : date_reg_fondo.val()
        };
        var l = Ladda.create(aux);
        l.start();

        $.post( server + 'update-fondo' , dato )
        .fail( function( statusCode , errorThrown )
        {
            l.stop();
            ajaxError( statusCode , errorThrown );
        }).done( function( data )
        {
            if (data.Status == 'Ok')
            {
                $("#idfondo").val('');
                $('#edit-rep').hide();
                $('.btn_cancel_fondo').hide();
                $('.btn_edit_fondo').hide();
                $('.register_fondo').show();
                fondo_institucion.val('');
                fondo_repmed.val('');
                fondo_supervisor.val('');
                fondo_total.val('');
                fondo_cuenta.val('');

                removeinput($('#edit-rep'));
                $('input[name=idsolicitud]').val('');
                bootbox.alert('<h4 class="green">Fondo Actualizado</h4>' , function()
                {
                    searchFondos( date_reg_fondo.val() );
                });
            }
            else
                bootbox.alert("<h4 style='color:red'>No se pudo editar la solicitud - " + data.Description + "</h4>");
            l.stop();
        });
    });

    function removeinput(data){

        var rep = data.parent().find('input:text');
        rep.typeahead( 'val' , '' );
        rep.attr('disabled', false).attr('data-select',"false").focus().parent().parent().removeClass('has-success');
        fondo_cuenta.val('').attr('disabled', false).parent().removeClass("has-success");
        fondo_supervisor.val('').attr('disabled',false).attr('data-cod',0).parent().removeClass("has-success");
        //data.parent().find('input:hidden').val('');
        data.fadeOut();
    }
    $(document).on("click","#edit-rep", function(e)
    {
        removeinput($(this))
    });

    $(document).on('click','.btn_cancel_fondo',function(e){
        $('.register_fondo').show();
        $('.btn_edit_fondo').hide();
        $(this).hide();
        removeinput($('#edit-rep'));
        fondo_institucion.val('');
        fondo_total.val('');
        $('input[name=idsolicitud]').val('');
        $('#table_solicitude_fondos > tbody > tr').css('background-color','');
    });

    $("#estado_fondo_cont").on("change", function(e){
        var datefondo = $("#datefondo").val();
        var aux = 'fondos-contabilidad';
        searchFondos(datefondo, aux);
    });

    function searchFondos( datefondo , aux ) 
    {
        var url = server + 'list-fondos/' + datefondo;
        $('#loading-fondo').attr('class','show');
        $('.table-solicituds-fondos > .fondo_r').remove();
        $('.fondo_r').remove();
        $.get(url)
        .done(function (data) 
        {
            if ( data.Status == 'Ok' )
            {
                $('#loading-fondo').attr('class','hide');
                $('.table_solicituds_fondos > .fondo_r').remove();
                $('#table_solicitude_fondos_wrapper').remove();
                $('.table-solicituds-fondos').append(data.Data);
                $('#export-fondo').attr('href', server + 'exportfondos/' + datefondo);
                $('#table_solicitude_fondos').dataTable(
                {
                    "order": 
                    [
                        [3, "desc"]
                    ],
                    "bLengthChange": false,
                    'iDisplayLength': 7,
                    "oLanguage": 
                    {
                        "sSearch": "Buscar: ",
                        "sZeroRecords": "No hay fondos",
                        "sInfoEmpty": " ",
                        "sInfo": 'Mostrando _END_ de _TOTAL_',
                        "oPaginate": 
                        {
                            "sPrevious": "Anterior",
                            "sNext": "Siguiente"
                        }
                    }
                });
            }
            else
                bootbox.alert('<h4 class="red">' + data.Status + ': ' + data.Description + '</h4>');
        });
    }

    function addImage(e) 
    {
        var file = e.target.files[0],
            imageType = /image.*/;
        if (!file.type.match(imageType))
            responseUI('Ingrese solo Imagenes','blue');
        else 
        {
            var reader = new FileReader();
            reader.onload = fileOnload;
            reader.readAsDataURL(file);
        }
    }

    input_file_factura.on("change", function(e)
    {
        addImage(e);
    });

    function fileOnload(e) {
        var result = e.target.result;
        $('#imgSalida').attr("src", result);
    }

    /* Menu */
    var navItems = $('.admin-menu li > a');
    var navListItems = $('.admin-menu li');
    var allWells = $('.admin-content');
    var allWellsExceptFirst = $('.admin-content:not(:first)');
    allWellsExceptFirst.hide();
    navItems.click(function(e)
    {
        e.preventDefault();
        navListItems.removeClass('active');
        $(this).closest('li').addClass('active');
        allWells.hide();
        var target = $(this).attr('data-target-id');
        $('#' + target).show();
    });

    $(document).off('click', '#register-deposit-fondo');
    $(document).on('click', '#register-deposit-fondo', function(e){
        e.preventDefault();
        var data = {};
        $("#op_number").val('');
        $("#message-op-number").text('');
        var op_number  = $("#op-number2").val();
        var type_deposit = $(this).attr('data-deposit');
        var date_fondo = $('#datefondo').val();
        if(type_deposit ==='fondo'){
            url = 'deposit-fondo'
            data.idfondo = $('#idfondo').val();
            data.op_number = op_number;
            data._token = $("input[name=_token]").val();
            data.date_fondo = date_fondo;
            data.idcuenta   = $("#bank_account").val();
        }else if(type_deposit === 'solicitude'){
            url = 'deposit-solicitude';
            data.op_number = op_number;
            data.token     = $("#token").val();
            data._token    = $("input[name=_token]").val();
            data.date_fondo = date_fondo;
            data.idcuenta   = $("#bank_account").val();
        }
        if(!op_number)
            $("#message-op-number").text("Ingrese el número de Operación");
        if(date_fondo == '')
            $("#message-op-number").text("Debe escoger la fecha del depósito");
        else
        {
            $.post(server + url, data)
            .done(function (data)
            {
                if(data === 'error')
                    $("#message-op-number").text("No se ha podido registrar el depósito.");  
                else
                {
                    $('#myModal').modal('hide');
                    bootbox.alert("<p class='green'>Se registro el asiento contable correctamente.</p>", function()
                    {
                        $('#table_solicitude_fondos-tesoreria_wrapper').remove();
                        $('.table_solicitude_fondos-tesoreria').append(data);
                        $('#table_solicitude_fondos-tesoreria').dataTable({
                            "order": [
                                [ 3, "desc" ] //order date
                            ],
                            "bLengthChange": false,
                            'iDisplayLength': 7,
                            "oLanguage": {
                                "sSearch": "Buscar: ",
                                "sZeroRecords": "No hay fondos",
                                "sInfoEmpty": "No hay fondos",
                                "sInfo": 'Mostrando _END_ de _TOTAL_',
                                "oPaginate": {
                                    "sPrevious": "Anterior",
                                    "sNext" : "Siguiente"
                                }
                            }
                        });
                    });
                }
            });
        }
    });

    $(document).off('show.bs.modal', '#myModal');
    $(document).on('show.bs.modal', '#myModal', function (e) {
        $("#message-op-number").html('');
        $("#op-number").val('');
    });

    $(document).off("click",".elementCancel");
    $(document).on("click",".elementCancel", function()
    {
        listDocumentsType();
    });

    $(document).off("click", ".elementEdit");
    $(document).on("click", ".elementEdit", function()
    {
        $("#add-doc").hide();
        var trElement = $(this).parent().parent();
        trElement.children().each(function(i,data)
        {
            var tempData = $(data).html();
            if( !( $(data).attr("id") == "icons" || $(data).attr("id") == "pk" || $(data).attr("id") == "sunat" ) )
                inputcell(data,tempData);
            else if ( $(data).attr("id") == "icons" )
            {

                $(data).html('<a class="elementSave" data-sol="1" href="#">'
                    + '<span class="glyphicon glyphicon-floppy-disk"></span></a>'
                    + '<a class="elementCancel" href="#"><span class="glyphicon glyphicon-remove"></span></a>');
            }
            $(data).attr("data-data", tempData);
        });
    });

    $(document).off( 'click' , '.maintenance-add');
    $(document).on( 'click' , '.maintenance-add' , function()
    {
        var button = $(this);
        $.ajax(
        {
            type: 'post' ,
            url :  server + 'add-maintenance-info' ,
            data:
            {
                _token : $('input[name=_token]').val() ,
                type   : button.attr("case")
            }
        }).fail( function( statusCode , errorThrown )
        {
            ajaxError( statusCode , errorThrown );
        }).done( function( response )
        {
            if ( response.Status == 'Ok')
                button.parent().parent().find('tbody').append( response.Data );
            else
                bootbox.alert('<h4 class="red">' + Data.Status + ': ' + Data.Description + '</h4>');            
        });
    });

    $(document).off("click", "#add-doc");
    $(document).on("click", "#add-doc", function()
    {
        var style = 'style="text-align: center"';
        $(this).hide();
        $(".fondo_d tbody").append('<tr class="new">'
            + '<td id="pk" ' +style + ' disabled></td>'
            + '<td id="desc" ' +style + '> <input style="width: 100%;" type="text"> </td>'
            + '<td id="sunat" ' +style + '> <input style="width: 100%;" type="text"></td>'
            + '<td id="marca" ' +style + '> <input maxlength="3" style="width: 100%;" type="text"> </td>'
            + '<td id="igv" ' +style + '> <select style="width: 100%;"><option>Si</option><option>No</option></select> </td>'
            + '<td id="icons" ' +style + '> '
            + '<a class="elementSave" data-sol="1" href="#"><span class="glyphicon glyphicon-floppy-disk"></span></a>'
            + '<a class="elementBack" href="#"><span class="glyphicon glyphicon-remove"></span></a>'
            + '</td>'
            + '</tr>')
    });

    $(document).off( 'click' , '.maintenance-cancel');
    $(document).on( 'click' , '.maintenance-cancel' , function()
    {
        var tr = $(this).parent().parent();
        listMaintenanceTable( tr.attr('type')  );
    });


    $(document).off('click' , '.maintenance-edit');
    $(document).on('click' , '.maintenance-edit' , function()
    {
        var trElement = $(this).parent().parent();
        trElement.children().each(function(i,data)
        {
            var td = $(data);
            if ( td.attr('editable') == 1 )
                enableTd( data );
            else if ( td.attr('editable') == 2 )
            {
                $(data).html('<a class="maintenance-update" href="#">'
                + '<span class="glyphicon glyphicon-floppy-disk"></span></a>'
                + '<a class="maintenance-cancel" href="#"><span class="glyphicon glyphicon-remove"></span></a>');        
            }
            else if ( td.attr('editable') == 3 )
            {
                var val = td.html();
                td.html('<input type="text" maxlength=7 value="' + val + '">');
                td.children().numeric();
            }
        });
    });

    $(document).off( 'click' , '.maintenance-save');
    $(document).on( 'click' , '.maintenance-save' , function()
    {
        var trElement = $(this).parent().parent();
        var aData = {};
        aData._token = $('input[name=_token]').val();
        aData.type   = trElement.attr('type');
        aData.Data   = {};
        trElement.children().each( function( i , data )
        {
            var td = $(data);
            if ( td.attr('save') == 1 )
                aData.Data[td[0].className] = td.children().val() ;
            else if ( td.attr('save') == 2 )
                aData[td[0].className] = td.children().val() ;
        });
        $.ajax(
        {
            type: 'post' ,
            url :  server + 'save-maintenance-info' ,
            data: aData
        }).fail( function( statusCode , errorThrown )
        {
            ajaxError( statusCode , errorThrown );
        }).done( function( response )
        {
            if ( response.Status == 'Ok')
            {
                bootbox.alert('<h4 class="green">Tabla Actualizada</h4>');
                listMaintenanceTable( aData.type );
            }
            else
                bootbox.alert('<h4 class="red">' + Data.Status + ': ' + Data.Description + '</h4>');            
        });
    });

    $(document).off('click' , '.maintenance-update');
    $(document).on('click' , '.maintenance-update' , function()
    {
        var trElement = $(this).parent().parent();
        var aData = {};
        aData._token = $('input[name=_token]').val();
        aData.id     = trElement.attr('row-id');
        aData.type   = trElement.attr('type');
        aData.Data   = {};
        trElement.children().each( function( i , data )
        {
            var td = $(data);
            if ( td.attr('editable') == 1  || td.attr('editable') == 3 )
                aData.Data[td[0].className] = td.children().val()
        });
        $.ajax(
        {
            type: 'post' ,
            url :  server + 'update-maintenance-info' ,
            data: aData
        }).fail( function( statusCode , errorThrown )
        {
            ajaxError( statusCode , errorThrown );
        }).done( function( response )
        {
            if ( response.Status == 'Ok')
            {
                bootbox.alert('<h4 class="green">Relaciones Actualizadas</h4>');
                listMaintenanceTable( aData.type );
            }
            else
                bootbox.alert('<h4 class="red">' + Data.Status + ': ' + Data.Description + '</h4>');            
        });
    });

    function enableTd( data )
    {
        var td = $(data);
        $.ajax(
        {
            type: 'post' ,
            url :  server + 'get-cell-maintenance-info' ,
            data: 
            {
                type   : td[0].className ,
                val    : td.html(),
                _token : $('input[name=_token]').val()
            }
        }).fail( function( statusCode , errorThrown )
        {
            ajaxError( statusCode , errorThrown );
        }).done( function( response )
        {
            if ( response.Status == 'Ok')
                td.html( response.Data );
            else
                bootbox.alert('<h4 class="red">' + Data.Status + ': ' + Data.Description + '</h4>');            
        });
    }

    function inputcell(data,tempData)
    {
        if ($(data).attr("id") == "igv")
        {
            var input = $('<select style="width: 100%;">'
                        + '<option>Si</option>'
                        + '<option>No</option>'
                        + '</select>');
        }
        else if ($(data).attr("id") == "marca")
            var input = $('<input maxlength="3" type="text" style="width: 100%;"></input>');
        else
            var input = $('<input type="text" style="width: 100%;"></input>');
        input.val(tempData);
        $(data).html(input);  
    }

    $(document).off("click", ".elementSave");
    $(document).on("click", ".elementSave", function()
    {
        var data_json = {} ;
        data_json.type = 'Update';
        data_json._token = $('input[name=_token]').val();
        var aux = false;
        trElement     = $(this).parent().parent();
        var z=trElement.children().first();
        if (z.html() == "")
        {
            aux = true;
            data_json.type = 'Insert';
        }
        else
            data_json.pk = trElement.children().first().html();
        trElement.children().each(function(i,data)
        {
            if (!aux)
            {
                if( !($(data).attr("id") == "icons" || $(data).attr("id") == "pk" || $(data).attr("id") == "sunat" ) )
                {

                    var input = $(data).children().first();
                    //$(data).html(input.val());
                    if(input.parent().attr("id")!='pk' && input.val()=="")
                    {
                        input.focus();
                        data_json.type = "Error";
                    }
                    if ( input.val() == 'No' )
                        data_json[$(data).attr("id")] = 0;
                    else if ( input.val() == 'Si' )
                        data_json[$(data).attr("id")] = 1;
                    else
                        data_json[$(data).attr("id")] = input.val();
                }
            }
            else
            {
                if( !($(data).attr("id") == "icons"))
                {
                    var input = $(data).children().first();
                    if(input.parent().attr("id")!='pk' && input.val()=="")
                    {
                        input.focus();
                        data_json.type = "Error";
                    }
                    if ( input.val() == 'No' )
                        data_json[$(data).attr("id")] = 0;
                    else if ( input.val() == 'Si' )
                        data_json[$(data).attr("id")] = 1;
                    else
                        data_json[$(data).attr("id")] = input.val();
                }
            }
        });
        if (data_json.type != "Error")
        {
            $.ajax(
            {
                type: 'post',
                url :  'cont-document-manage',
                data: data_json,
                error: function()
                {
                    responseUI('Error del Sistema','red');
                },
                success: function ( data )
                {   
                    if (data.Status == 'Ok')
                    {
                        responseUI('Datos Registrados','green');
                        listDocumentsType();
                    }
                    else
                        responseUI('<font color="black">Warning:Verificar la consistencia de la Informacion</font>','yellow');    
                }        
            });
        }
        else
            bootbox.alert("complete los datos");
    });

    $(document).off("click", ".elementBack");
    $(document).on("click", ".elementBack", function()
    {
        $(".fondo_d tbody tr").last().remove();
        $("#add-doc").show();
    });

    function seeker(element,name,url){
        if (element.length != 0)
        {
            element.typeahead(
            {
                minLength: 3,
                hightligth: true,
                hint: false
            },
            {
                name: name,
                displayKey: 'label',
                templates:  
                {
                    empty: 
                    [
                        '<p><strong>&nbsp; No se encontro resultados  &nbsp;</strong></p>'
                    ].join('\n'),
                    suggestion: function(data)
                    {
                        return '<p><strong>' + data.type + ': ' + data.label + '</strong></p>';
                    }
                },
                source: function (request , response)
                {
                    $.ajax(
                    {
                        type: 'post',
                        url: server + url ,
                        data:
                        {
                            "_token": $("input[name=_token]").val(),
                            "sVal": request
                        },
                        error: function()
                        {
                            var resp = '{"value:0","label":"Error de Conexion"}';
                            response(resp);
                        },
                    }).done( function (data)
                    {
                        if ( data.Status != 'Ok')
                            data.Data = '{"value":"0","label":"Error en la busqueda"}';   
                        response( data.Data );
                    });              
                }
            }).on('typeahead:selected', function ( evento, suggestion , dataset )
            {
                var input = $(this);
                if ( dataset == 'clients' )
                {
                    $.ajax(
                    {
                        type: 'post',
                        url: server + 'get-client-view' ,
                        data:
                        {
                            "_token": $("input[name=_token]").val(),
                            "data": suggestion
                        },
                    }).fail( function( statusCode , errorThrown )
                    {
                        ajaxError( statusCode , errorThrown );
                    }).done( function ( data )
                    {
                        if ( data.Status != 'Ok')
                            responseUI( 'Problema de Conexion' , 'red' );
                        else if ( data.Status == 'Ok' )
                        {
                            if ( $('#clientes').children().length >= 1 )
                            {
                                var aux = 0;
                                $('#clientes').children().each( function()
                                {
                                    var li = $(this);
                                    if ( li.attr('pk') == suggestion.value && li.attr('tipo_cliente') == suggestion.id_tipo_cliente )
                                        aux = 1;
                                });
                                if ( aux == 0 )
                                    $('#clientes').append( data.Data.View );
                            }
                            else
                                $('#clientes').append( data.Data.View );
                            filterSelect( $('select[name=inversion]') , data.Data.id_inversion );
                            filterSelect( $('select[name=actividad]') , data.Data.id_actividad );
                            activityChange();
                        }
                    });
                    input.typeahead( 'val' , '' );
                }
                else if ( dataset == 'reps' )
                {
                    $(this).attr('data-select','true');
                    $(this).attr('data-cod',suggestion.value);
                    $(this).val(suggestion.label );
                    $(this).attr('disabled',true).parent().parent().addClass('has-success');
                    $('.edit-repr').fadeIn();
                    repInfo( suggestion.value ).done( function (result)
                    {
                        if ( result.Data.cuenta )
                        {
                            fondo_cuenta.val(result.Data.cuenta);
                            fondo_cuenta.attr('disabled',true).parent().removeClass('has-error').addClass('has-success');        
                        }
                        if ( result.Data.sup )
                        {
                            fondo_supervisor.val(result.Data.sup.nombre);
                            fondo_supervisor.attr('data-cod',result.Data.sup.idsup);
                            fondo_supervisor.attr('disabled',true).parent().removeClass('has-error').addClass('has-success'); 
                        }
                    });
                }
            });
        }
    }

    function filterSelect( element , ids )
    {
        var select = $(element);
        if ( clientes.children().length == 1 )
        {
            select.val('');
            select.children().filter(function( index ) 
            {
                return $.inArray( $(this).val() ,  ids ) == -1;
            }).hide();
        }
    }


    function repInfo(rm)
    {
        return $.ajax(
        {
            type: 'post',
            url: server+'info-rep',
            data:
            {
                "_token": $("input[name=_token]").val(),
                "rm": rm
            },
            error: function(statusCode,errorThrown)
            {
                ajaxError(statusCode,errorThrown);
            }
        });  
    }

    function clientFilter( tipo_cliente )
    {
        $.ajax(
        {
            type: 'post' ,
            url: server + 'filtro_cliente' ,
            data:
            {
                "_token": $("input[name=_token]").val(),
                "tipo_cliente": tipo_cliente
            }
        }).fail( function ( statusCode , errorThrown )
        {
            ajaxError( statusCode , errorThrown );
        }).done( function ( response )
        {
            if ( response.Status === 'Ok' )
            {
                $( 'select[name=actividad]' ).val('').children().show();
                $( 'select[name=inversion]' ).val('').children().show();
                filterSelect( $( 'select[name=actividad]' ) , response.Data.id_actividad );
                filterSelect( $( 'select[name=inversion]' ) , response.Data.id_inversion );
                activityChange();
            }
            else
                bootbox.alert( '<h4 class="red">' + data.Status + ': ' + data.Description + '</h4>');
        });  
    }

    $(document).off( 'click' , '.btn-delete-client' );
    $(document).on( 'click' , '.btn-delete-client' , function () 
    {
        var li = $(this).parent();
        var ul = li.parent();
        if ( li.index() == 0 && ul.children().length > 1 )
        {
            var table = li.attr('tipo_cliente');
            li.remove();
            var old_li2 = ul.children().first();
            if ( table !== old_li2.attr( "tipo_cliente" ) )
                clientFilter( old_li2.attr( "tipo_cliente" ) );
        }
        else
            li.remove();
        if ( ul.children().length === 0 )
        {
            $('select[name=inversion]').children().show();
            $('select[name=actividad]').children().show();               
        }
    });

    $( document ).ready(function() 
    {
        seeker($('.cliente-seeker'),'clients','search-client');
        seeker($('.rep-seeker'),'reps','search-rep');
    });

    function ajaxError(statusCode,errorThrown)
    {
        if ( statusCode.status == 0 ) 
            bootbox.alert('<h4 class="yellow">Internet: Problemas de Conexion</h4>');    
        else
            bootbox.alert('<h4 class="red">Error del Sistema</h4>');  
    }

    function listAccountState(date)
    {
        date = typeof date !== 'undefined' ? date : null;
        $.ajax(
        {
            type: 'post',
            url: server+'list-account-state',
            data:
            {
                "_token": $("input[name=_token]").val(),
                "date": date
            },
            error: function(statusCode,errorThrown)
            {
                ajaxError(statusCode,errorThrown);   
            }
        }).done( function (data)
        {
            if ( data.Status == 'Ok' )
            {
                $('#table_estado_cuenta_wrapper').remove();
                $('.table_estado_cuenta').append(data.Data.View);
                $('#table_estado_cuenta').dataTable(
                {
                    "order": [
                        [ 0, "desc" ]
                    ],
                    "bLengthChange": false,
                    'iDisplabuscyLength': 7,
                    "oLanguage": 
                    {
                        "sSearch": "Buscar: ",
                        "sZeroRecords": "No hay Solicitudes Finalizadas",
                        "sInfoEmpty": "",
                        "sInfo": 'Mostrando _END_ de _TOTAL_',
                        "oPaginate": 
                        {
                            "sPrevious": "Anterior",
                            "sNext" : "Siguiente"
                        }
                    }
                });
                if ( data.Data.Total !== undefined )
                {
                    $('.estado-cuenta-deposito').first().val(data.Data.Total.Soles);
                    $('.estado-cuenta-deposito').last().val(data.Data.Total.Dolares);
                }
            }
            else
                responseUI('Error del Sistema','red');
        });   
    }

    $(".date_month").datepicker(date_options2).on('changeDate', function (e) {
        var date = $(this).val();
        var type = $(this).attr('data-type');

        if(date!='')
            if(type != "estado-cuenta")
            {
                $('.date_month').val( date );
                if ( !$('input[name=idsolicitud]').val() )
                    searchFondos(date);
            }
            else
                listAccountState(date);
    });
    
    /* Filter all solicitude by date */
    $('#search-solicitude').on('click', function()
    { 
        listSolicitude();
    });

    $('#search-documents').on('click', function()
    { 
        listDocuments();
    });



    function validateNewSol()
    {
        var aux = 0;
        if ( !title.val() ) 
        {
            title.parent().addClass('has-error');
            title.attr('placeholder', 'Ingrese nombre de la solicitud');
            title.addClass('input-placeholder-error');
            aux = 1;
        }
        if (!amount.val()) 
        {
            amount.parent().addClass('has-error');
            amount.attr('placeholder', 'Ingrese monto');
            amount.addClass('input-placeholder-error');
            aux = 1;
        }
        if (!delivery_date.val()) 
        {
            delivery_date.parent().addClass('has-error');
            delivery_date.attr('placeholder', 'Ingrese Fecha');
            delivery_date.addClass('input-placeholder-error');
            aux = 1;
        }
        /*if (!input_client.val()) 
        {
            input_client.parent().addClass('has-error');
            input_client.attr('placeholder', 'Ingrese Cliente');
            input_client.addClass('input-placeholder-error');
            aux = 1;
        }*/

        if ( clientes.children().length == 0 )
        {
            search_cliente.attr( 'placeholder' , 'Ingrese el Cliente' ).addClass('input-placeholder-error');
            search_cliente.parent().parent().addClass('has-error');
            clientes.parent().parent().addClass('has-error');
            aux = 1;
        }
        if( $('select[name=actividad] option:selected').attr('image') == 1 )
        {
            if ( !amount_fac.val() ) 
            {
                amount_fac.parent().addClass('has-error');
                amount_fac.attr('placeholder', 'Ingrese Monto de la Factura');
                amount_fac.addClass('input-placeholder-error');
                aux = 1;
            }
            if( !input_file_factura.val() && isSetImage.val()==null )
            {
                input_file_factura.parent().addClass('has-error');
                input_file_factura.attr('placeholder', 'Ingrese Imagen');
                input_file_factura.addClass('input-placeholder-error');
                aux = 1;
            }
        }
        if ( $('select[name=pago] option:selected').val() == 2 )
        {
            if ( !ruc.val() )
            {
                ruc.parent().addClass('has-error');
                ruc.attr('placeholder', 'Ingrese RUC').addClass('input-placeholder-error');
                aux = 1;
            }
        }
        return aux;
    }

    //Validate send register solicitude
    $('#registrar').off('click');
    $('#registrar').on( 'click' , function ( e ) 
    {
        e.preventDefault();
        var aux = 0;
        var d_clients = [];
        var d_tables = [];
        var families_input = [];
        
        aux = validateNewSol();
        
        //Validate fields client are correct
        clientes.children().each( function ()
        {
            elem = $(this);
            d_clients.push( elem.attr("pk") );
            d_tables.push( elem.attr("table") );
        });
        
        var families = $('.selectfamily');
        families.each( function (index) 
        {
            families_input[index] = $(this).val();
        });

        for ( var i = 0 ; i < families_input.length ; i++ ) 
        {
            families.each( function ( index ) 
            {
                if ( index != i && families_input[i] === $( this ).val() ) 
                {
                    var ind = families_input.indexOf( $( this ).val() );
                    families_input[index] = '';
                    $(this).css('border-color', 'red');
                    $(".families_repeat").text('Datos Repetidos').css('color', 'red');
                    aux=1;
                }
            });
        }
    
        if ( aux == 0 ) 
        {
            var form = $('#form-register-solicitude');
            var formData = new FormData(form[0]);
            formData.append( "clientes[]", d_clients );
            formData.append( "tables[]" , d_tables );
            var rute = form.attr('action');
            var message1 = 'Registrando';
            var message2 = '<strong style="color: green">Solicitud Registrada</strong>';
            if (rute == 'editar-solicitud') 
            {
                message1 = 'Actualizando';
                message2 = '<strong style="color: green">Solicitud Actualizada</strong>';
            }
            $.ajax(
            {
                url: server + rute,
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function()
                {
                    loadingUI(message1);
                }
            }).done(function ( data )
            {
                $.unblockUI();
                if(data.Status == 'Ok')
                {
                    responseUI('Solicitud Registrada', 'green');
                    setTimeout( function()
                    {
                        window.location.href = server + 'show_user';
                    },500);
                }
                else
                    bootbox.alert('<h4 class="red">' + data.Status + ': ' + data.Description + '</h4>');
            }).fail( function ( statusCode , errorThrown ) 
            {
                $.unblockUI();
                ajaxError( statusCode, errorThrown );
            });
        }
        else
            responseUI( 'Verifique los Datos' , 'red' );
    });

    $(document).off("click",".sol-obs");
    $(document).on("click",".sol-obs", function()
    {
        $(this).removeAttr("placeholder").parent().parent().removeClass("has-error");
    });

    $("#btn-mass-approve").click(function()
    {
        var checks = $("input[name=mass-aprov]:checked").length;
        if (checks == 0)
            bootbox.alert("<h4>No hay solicitudes seleccionadas</h4>")
        else
        {    
            bootbox.confirm("<h4 style='color:blue'>Esta seguro de aprobar todas las solicitudes seleccionadas</h4>" , function(result)
            {
                if (result)
                {
                    var data = {};
                    var trs = $('#table_solicitude tbody tr');
                    data._token = $('input[name=_token]').val();
                    data.sols = [];
                    trs.each(function( index)
                    {
                        var sol = {};
                        if ( $(this).find('input[name=mass-aprov]:checked').length != 0 )
                        {
                            sol.token = $(this).find("#sol_token").val();
                            data.sols.push(sol);
                        }
                    });
                    $.ajax(
                    {
                        url: server + 'gercom-mass-approv',
                        type: 'POST',
                        data: data,
                        beforeSend: function()
                        {
                            loadingUI("Procesando");
                        },
                        error: function(statusCode,errorThrown)
                        {
                            if (statusCode.status == 0) 
                                responseUI('<font color="black">Internet: Problemas de Conexion</font>','yellow');
                            else
                                responseUI('Error del Sistema','red');
                        }
                    }).done(function (data)
                    {
                        $.unblockUI();
                        if(data.Status == 'Ok')
                        {
                            bootbox.alert("<h4 style='color:green'>Solicitudes Aprobadas</h4>", function()
                            {
                                listSolicitude();
                                colorTr(data.Description);
                            });
                        }
                        else if(data.Status == 'Warning')
                        {
                            bootbox.alert("<h4 style='color:yellow'>No se han podido aprobar todas las solicitudes</h4>", function()
                            {
                                listSolicitude();
                                colorTr(data.Description);
                            });
                        }
                        else if(data.Status == 'Danger')
                        {
                            bootbox.alert("<h4 style='color:red'>No se han podido aprobar las solicitudes</h4>", function()
                            {
                                listSolicitude();
                                colorTr(data.Description);
                            });   
                        }
                        else
                            responseUI(data.Status + ': ' + data.Description,'red');
                    });
                }
            });
        }
    });

    var approved_solicitude = $('.approved_solicitude');
    approved_solicitude.on('click',function(e)
    {
        e.preventDefault();
        //almacenamos el monto total por cada familia
        var div_monto = $("label[for=amount]").parent();
        if ( div_monto.hasClass("has-error") )
            idamount.focus();
        else if( div_monto.hasClass("has-success"))
        {
            bootbox.confirm("¿Esta seguro que desea aprobar esta solicitud?", function (result) 
            {
                if (result) 
                {
                    var message = 'Validando Solicitud..';
                    loadingUI(message);
                    $.post(server + 'aprobar-solicitud', form_acepted_solicitude.serialize()).done(function(data){
                        $.unblockUI();
                        if(data.Status === 'Ok')
                        {
                            bootbox.alert('<h4 style="color: green">Solicitud Aceptada</h4>' , function()
                            {
                                window.location.href = server + 'show_user';
                            });
                        }
                        else
                            bootbox.alert('<h4 style="color: red">' + data.Status + ': '+ data.Description + '</h4>');
                    });
                }
            });
        }
    });
    
    function colorTr(tokens)
    {
        setTimeout(function()
        {
            var tr;
            $( document ).ready(function() 
            {
                for (var index in tokens['Error'])
                {
                    tr = $(".i-tokens[value=" + tokens['Error'][index] + "]").parent();
                    tr.css("color","red").addClass('danger');
                }
                for (var index in tokens['Ok'])
                {
                    tr = $(".i-tokens[value=" + tokens['Ok'][index] + "]").parent();
                    tr.css("color","green").addClass('success');
                }
            });
        },1000);
    }

}