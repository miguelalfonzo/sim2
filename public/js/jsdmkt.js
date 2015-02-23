newSolicitude();
function newSolicitude() {

    /* Declare Variables */
    var title = $('#idtitle');
    var amount = $('#idestimate');
    var delivery_date = $('#delivery_date');
    var amountfac = $('#amount-fac');
    var input_client = $('.input-client');
    var selectfamily = $('.selectfamily');
    var data_start = $('#date_start');
    var data_end = $('#date_end');
    var amount_fac = $('#amountfac');
    var solicitude_factura = $('.solicitude_factura');
    var solicitude_monto =  $('.solicitude_monto');
    var select_type_payment = $('.selectTypePayment');
    var solicitude_ruc = $('#div_ruc');
    var solicitude_number_account = $('#div_number_account');
    var number_account = $('#number_account');
    var idamount = $('#idamount');
    var clients = [];
    var input_file_factura = $('#input-file-factura');
    var validate = 0; // si cambia  a 1 hay errores
    var isSetImage = $('#isSetImage');
    var amount_error_families = $('#amount_error_families');



    var userType = $('#typeUser').val();//tipo de usuario se encuentra en main
    var TODOS = 0;
    var PENDIENTE =1 ;
    var ACEPTADO = 2;
    var APROBADO = 3;
    var DEPOSITADO = 4;
    var REGISTRADO = 5;
    var ENTREGADO = 6;
    var GENERADO = 7;
    var CANCELADO =  8;
    var RECHAZADO = 9;
    var BLOQUEADO = 10;


    // get clients
    $.getJSON(server + "getclients", function (data) {
        clients = data;
    });

    // leyenda

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

    // $('.selectestatesolicitude option[value=1]').attr('selected','selected');

    //delete a family
    $(document).on("click", ".btn-delete-family", function () {

        $('#listfamily>li .porcentaje_error').css({"border": "0"});
        $(".option-des-1").removeClass('error');
        $('.families_repeat').text('');
        var k = $("#listfamily li").size();
        if (k > 1) {
            var other = $(".btn-delete-family").index(this);
            $("#listfamily li").eq(other).remove();
            var p = $("#listfamily li").size();
            if (p == 1) {
                $(".btn-delete-family").hide();
            }
        }
    });

    //Validations
    amount.numeric();
    idamount.numeric();
    amount_fac.numeric();
    number_account.numeric();


    // Select type de solicitude
    solicitude_factura.hide();
    solicitude_monto.hide();

    var select_type_solicitude = $('.selecttypesolicitude');
    if (select_type_solicitude.val() == 2) {
        solicitude_factura.show();
        solicitude_monto.show();
    }
    select_type_solicitude.on('change', (function () {

        if ($(this).val() == 1) {

            solicitude_factura.hide();
            solicitude_monto.hide();
        } else if ($(this).val() == 2) {

            solicitude_factura.show();
            solicitude_monto.show();

        } else if ($(this).val() == 3) {

            solicitude_factura.hide();
            solicitude_monto.hide();
        }

    }));

    //select type payment
    solicitude_number_account.hide();
    solicitude_ruc.hide();
    if(select_type_payment.val()==2)  solicitude_ruc.show();
    if(select_type_payment.val()==3)  solicitude_number_account.show();
    select_type_payment.on('change', function(){
        if($(this).val() == 1){
            solicitude_ruc.hide();
            solicitude_number_account.hide();
        }else if($(this).val()== 2){
            solicitude_ruc.show();
            solicitude_number_account.hide();
        }else if($(this).val() == 3){
            solicitude_ruc.hide();
            solicitude_number_account.show();
        }

    });
    function load_client(client) {

        function lightwell(request, response) {
            function hasMatch(s) {
                return s.toLowerCase().indexOf(request.term.toLowerCase()) !== -1;
            }

            var i, l, obj, matches = [];

            if (request.term === "") {
                response([]);
                return;
            }
            for (i = 0, l = clients.length; i < l; i++) {
                obj = clients[i];
                if (hasMatch(obj.clnombre)) {
                    matches.push(obj);
                }
            }
            response(matches);
        }

        var idclient = '#idclient';
        if ($(idclient + client).length) {
            $(idclient + client).autocomplete({
                minLength: 0,
                source: lightwell,
                focus: function (event, ui) {
                    $(this).val(ui.item.clcodigo + ' - ' + ui.item.clnombre);

                    return false;
                },
                select: function (event, ui) {
                    // $( "#input-client" ).val( ui.item.label );
                    $(this).parent().removeClass('has-error has-feedback');
                    $(this).parent().children('.span-alert').removeClass('glyphicon glyphicon-remove form-control-feedback');
                    $(this).parent().addClass('has-success has-feedback');
                    $(this).parent().children('.span-alert').addClass('glyphicon glyphicon-ok form-control-feedback');
                    $(this).attr('readonly', 'readonly');
                    $(this).attr('data-valor','all');
                    return false;
                }
            })
                .data("ui-autocomplete")._renderItem = function (ul, item) {
                return $("<li>")
                    .append("<a>" +
                    "<br><span style='font-size: 80%;'>Codigo: " + item.clcodigo + "</span>" +
                    "<br><span style='font-size: 60%;'>Cliente: " + item.clnombre + "</span></a>")
                    .appendTo(ul);
            };
        }
    }

    load_client(1);
    var client = 2;
    $(document).on('click', '#btn-add-client', function () {
        $('<li><div style="position: relative"><input id="idclient' + client + '" name="clients[]" type="text" placeholder="" style="margin-bottom: 10px" class="form-control input-md input-client"><button type="button" class="btn-delete-client" style="z-index: 2"><span class="glyphicon glyphicon-remove"></span></button></div></li>').appendTo('#listclient');
        $(".btn-delete-client").show();
        setTimeout(function () {
            load_client(client);
            client++;
        }, 200);
    });
    $(document).on("click", ".btn-delete-client", function () {
        $('#listclient>li .porcentaje_error').css({"border": "0"});
        $('.clients_repeat').text('');

        $(".option-des-1").removeClass('error');
        var k = $("#listclient li").size();
        if (k > 1) {
            var other = $(".btn-delete-client").index(this);
            $("#listclient li").eq(other).remove();
            var p = $("#listclient li").size();
            if (p == 1) {
                $(".btn-delete-client").on('click',function(){
                   $('#idclient1').parent().removeClass('has-success has-feedback');
                    $('#idclient1').attr('readonly',false);
                    $('#idclient1').val('');
                });
            }
        }
    });

    /* Removing Errors */

    title.on('focus', function () {
        $(this).parent().removeClass('has-error');
    });
    amount.on('focus', function () {
        $(this).parent().removeClass('has-error');
    });
    delivery_date.on('focus', function () {
        $(this).parent().removeClass('has-error');
    });
    input_client.on('focus', function () {
        $(this).parent().removeClass('has-error')
    });
    amountfac.on('focus', function () {
        $(this).parent().removeClass('has-error')
    });
    amount_fac.on('focus', function () {
        $(this).parent().removeClass('has-error')
    });
    selectfamily.on('click', function () {
        $(this).css('border-color', 'none')
    });
    data_start.on('focus', function () {
        $(this).parent().removeClass('has-error');
    });
    data_end.on('focus', function () {
        $(this).parent().removeClass('has-error');
    });
    input_file_factura.on('focus', function () {
        $(this).parent().removeClass('has-error');
    });
    /* End Removing Errors */


    /* Validate send register solicitude */

    $('.register_solicitude').on('click', (function (e) {
        var aux = 0;
        var obj = [];
        var clients_input = [];
        var families_input = [];
        for (var i = 0, l = clients.length; i < l; i++) {
            obj[i] = clients[i].clcodigo + ' - ' + clients[i].clnombre;
        }
        if (!title.val()) {
            title.parent().addClass('has-error');
            title.attr('placeholder', 'Ingrese nombre de la solicitud');
            title.addClass('input-placeholder-error');
        }
        if (!amount.val()) {
            amount.parent().addClass('has-error');
            amount.attr('placeholder', 'Ingrese monto');
            amount.addClass('input-placeholder-error');
        }
        if (!delivery_date.val()) {
            delivery_date.parent().addClass('has-error');
            delivery_date.attr('placeholder', 'Ingrese Fecha');
            delivery_date.addClass('input-placeholder-error');
        }
        if (!input_client.val()) {
            input_client.parent().addClass('has-error');
            input_client.attr('placeholder', 'Ingrese Cliente');
            input_client.addClass('input-placeholder-error');
        }
        if(select_type_solicitude.val() == 2){
            if (!amount_fac.val()) {
                amount_fac.parent().addClass('has-error');
                amount_fac.attr('placeholder', 'Ingrese Monto de la Factura');
                amount_fac.addClass('input-placeholder-error');
            }
            if(!input_file_factura.val() && isSetImage.val()==null){
                input_file_factura.parent().addClass('has-error');
                input_file_factura.attr('placeholder', 'Ingrese Imagen');
                input_file_factura.addClass('input-placeholder-error');
            }
        }

        setTimeout(function () {

            //validate fields client are correct
            $('.input-client').each(function (index) {
                var input = $(this).val();
                clients_input[index] = input;

                var ban = obj.indexOf(input);
                if (ban == -1) 
                {
                    aux = 1;
                    $(this).parent().addClass('has-error has-feedback');
                    $(this).parent().children('.span-alert').addClass('span-alert glyphicon glyphicon-remove form-control-feedback');
                }
            });
        }, 100);
        setTimeout(function ()
        {
            $('.input-client').each(function (index) {
                var input = $(this).val();
                if (!$(this).attr('data-valor')) {
                    aux = 1;
                    $(this).parent().addClass('has-error has-feedback');
                    $(this).parent().children('.span-alert').addClass('span-alert glyphicon glyphicon-remove form-control-feedback');
                    $(this).val('');
                    $(this).attr('placeholder','Seleccione cliente');
                    $(this).addClass('input-placeholder-error');
                }
            });
        }, 100);
        setTimeout(function () {
            //Validate of fields duplicate in clients
            for (var i = 0; i < clients_input.length; i++) {
                $('.input-client').each(function (index) {
                    if (index != i && clients_input[i] === $(this).val()) {
                        var ind = clients_input.indexOf($(this).val());
                        clients_input[index] = '';

                        $(this).parent().removeClass('has-success has-feedback');
                        $(this).parent().addClass('has-error has-feedback');
                        $(this).parent().children('.span-alert').addClass('span-alert glyphicon glyphicon-remove form-control-feedback');
                        $(".clients_repeat").text('Datos Repetidos').css('color', 'red');
                    }

                });
            }
        }, 200);
        setTimeout(function () 
        {
            var families = $('.selectfamily');
            families.each(function (index) 
            {
            families_input[index] = $(this).val();
            });
            for (var i = 0; i < families_input.length; i++) 
            {
                families.each(function (index) 
                {
                    if (index != i && families_input[i] === $(this).val()) 
                    {
                        var ind = families_input.indexOf($(this).val());
                        families_input[index] = '';
                        $(this).css('border-color', 'red');
                        $(".families_repeat").text('Datos Repetidos').css('color', 'red');
                    }
                });
            }
            if (aux == 0) 
            {
                var form = $('#form-register-solicitude');
                var formData = new FormData(form[0]);
                var rute = form.attr('action');
                var message1 = 'Registrando';
                var message2 = '<strong style="color: green">Solicitud Registrada</strong>';
                if (rute == 'editar-solicitud') 
                {
                    message1 = 'Actualizando';
                    message2 = '<strong style="color: green">Solicitud Actualizada</strong>'
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
                }).done(function (data)
                {
                    $.unblockUI();
                    if (data == 'R') {
                        bootbox.alert(message2, function() {
                            window.location.href = server + 'show_rm'
                        });

                    }
                    else if(data == 'S'){
                        responseUI('Solicitud Registrada', 'green');
                        setTimeout(function()
                        {
                            window.location.href = server + 'show_sup';
                        },500);
                    }
                    else
                    {
                        responseUI("Faltan Ingresar Campos", 'red');
                    }
                }).fail(function (e) {
                    $.unblockUI();
                    alert('error');
                });
            }
        }, 300);
        e.preventDefault();
    }));


    //Function list Solicitude
    function listSolicitude(typeUser , state){
        $.ajax({
            url: server + 'listar-solicitudes-'+typeUser +'/' + state,
            type: 'GET',
            dataType: 'html'

        }).done(function (data) {
            $('.table-solicituds-'+typeUser).append(data);
            $('#table_solicitude_'+typeUser).dataTable({
                    "order": [
                        [ 3, "desc" ] //order date
                    ],
                    "bLengthChange": false,
                    'iDisplayLength': 7,
                    "oLanguage": {
                        "sSearch": "Buscar: ",
                        "sZeroRecords": "No hay solicitudes",
                        "sInfoEmpty": "No hay solicitudes",
                        "sInfo": 'Mostrando _END_ de _TOTAL_',
                        "oPaginate": {
                            "sPrevious": "Anterior",
                            "sNext" : "Siguiente"
                        }
                    }
                }
            );
        });

    }

    function listFondos(user, state){
        console.log(user, state);
        var url = server + 'list-'+user +'/'+dateactual + '/' + state;
        if(user != 'fondos-contabilidad') {
            url = server + 'list-'+user +'/'+dateactual;
        }
        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'html'

        }).done(function (data) {
            console.log('.table_solicitude_' + user);
            $(".fondo_r").remove();
            $('.table_solicitude_'+user).append(data);
            $("#datefondo").val(dateactual);
            $('#table_solicitude_'+user).dataTable({
                    "order": [
                        [ 0, "desc" ] //order date
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
                }
            );
        });
    }

    //Function search solicitude x date
    function searchSolicitudeToDate(typeUser ,search ) {

        var date_start = $('#date_start');
        var date_end = $('#date_end');
        var validate = 0;
        if (!date_start.val() && date_end.val()) {
            validate = 1;
            date_start.parent().addClass('has-error');
            date_start.attr('placeholder', 'Ingrese Fecha');
            date_start.addClass('input-placeholder-error');

        }
        if (!date_end.val() && date_start.val()) {
            validate = 1;
            date_end.parent().addClass('has-error');
            date_end.attr('placeholder', 'Ingrese Fecha');
            date_end.addClass('input-placeholder-error');
        }
        if (validate == 0) {

            date_start.parent().removeClass('has-error');
            date_start.attr('placeholder', '');
            date_end.parent().removeClass('has-error');
            date_end.attr('placeholder', '');
            var l = Ladda.create(search);
            l.start();
            var jqxhr = $.post(server + "buscar-solicitudes-"+typeUser, { idstate: $('#idState').val(), date_start: $('#date_start').val(), date_end: $('#date_end').val() ,_token : document.getElementsByName('_token')[0].value })
                .done(function (data) {

                    $('#table_solicitude_'+typeUser+'_wrapper').remove();
                    $('.table-solicituds-'+typeUser).append(data);
                    $('#table_solicitude_'+typeUser).dataTable({
                            "order": [
                                [ 3, "desc" ]
                            ],
                            "bLengthChange": false,
                            'iDisplayLength': 7,
                            "oLanguage": {
                                "sSearch": "Buscar: ",
                                "sZeroRecords": "No hay solicitudes",
                                "sInfoEmpty": "No hay solicitudes",
                                "sInfo": 'Mostrando _END_ de _TOTAL_',
                                "oPaginate": {
                                    "sPrevious": "Anterior",
                                    "sNext" : "Siguiente"
                                }
                            }
                        }
                    );
                    l.stop();
                })
                .fail(function () {
                    alert("error");
                })
        }
    }

    /* List solicitude pending */

    if(userType === 'R'){
        listSolicitude('rm',PENDIENTE);
        listFondosRm();
    }
    //

    function listFondosRm(){
        $.get(server + 'list-fondos-rm').done(function(data){
            $('.table_fondos_rm').append(data);
            $('#table_fondos_rm').dataTable({
                    "order": [
                        [ 0, "desc" ]
                    ],
                    "bLengthChange": false,
                    'iDisplayLength': 7,
                    "oLanguage": {
                        "sSearch": "Buscar: ",
                        "sZeroRecords": "No hay Fondos",
                        "sInfoEmpty": "",
                        "sInfo": 'Mostrando _END_ de _TOTAL_',
                        "oPaginate": {
                            "sPrevious": "Anterior",
                            "sNext" : "Siguiente"
                        }
                    }
                }
            );

        });
    }


    /* Filter all solicitude by date */
    var search_solicitude = $('#search-solicitude');
    search_solicitude.on('click', function(){ searchSolicitudeToDate('rm',this )});


    /* Cancel Solicitude */
    var cancel_solicitude = '.cancel-solicitude';
    $(document).on('click', cancel_solicitude, function (e) {
        e.preventDefault();
        var aux = $(this);
        bootbox.confirm({
            message : '¿Esta seguro que desea cancelar esta solicitud?',
            buttons: {
                'cancel' :{ label :'Cancelar' ,className: 'btn-primary'},
                'confirm' :{ label :'Aceptar' ,className: 'btn-default'}
            },
            callback : function (result) {
                if (result) {

                    $.post(server + 'cancelar-solicitud-rm', {idsolicitude: $(aux).attr('data-idsolicitude') ,_token :$(aux).attr('data-token')})
                        .done(function (data) {
                            bootbox.alert('Solicitud Cancelada' , function(){
                                $('#table_solicitude_rm_wrapper').remove();
                                $('.table-solicituds-rm').append(data);
                                $('#table_solicitude_rm').dataTable({
                                        "order": [
                                            [ 3, "desc" ]
                                        ],
                                        "bLengthChange": false,
                                        'iDisplayLength': 7,
                                        "oLanguage": {
                                            "sSearch": "Buscar: ",
                                            "sZeroRecords": "No hay solicitudes",
                                            "sInfoEmpty": "No hay solicitudes",
                                            "sInfo": 'Mostrando _END_ de _TOTAL_',
                                            "oPaginate": {
                                                "sPrevious": "Anterior",
                                                "sNext" : "Siguiente"
                                            }
                                        }
                                    }
                                );
                            });
                        });
                }
            }
        })

    });



    /**------------------------------------------------ SUPERVISOR ---------------------------------------------------*/

    /* list solicitude pending or depending of type state */
    if(userType === 'S' && $('#state_view').val() != undefined)
        listSolicitude('sup', $('#state_view').val());


    var amount_families = $('.amount_families');
    amount_families.numeric({negative: false});

    /* validate amount families no more than amount total*/


    amount_families.keyup(function (e) {
        var sum_total =0;
        amount_families.each(function(i,v){
            sum_total += parseFloat($(this).val());

        });

        if(sum_total > parseInt(idamount.val())){
            amount_error_families.text('La suma supera al monto total').css('color', 'red');
        }
        if (parseInt($(this).val()) > parseInt(idamount.val())) {
            amount_error_families.text('El monto supera al monto total').css('color', 'red');
        } else {
            if (e.keyCode == 8) {

                amount_error_families.text('');
            }
        }

    });


    amount_families.on('focus', function () {
        amount_error_families.text('');
    });

    /* solicitude accepted */
    var form_acepted_solicitude = $('#form_make_activity');
    $('.accepted_solicitude_sup').on('click', function (e) {
        var total = 0;

        e.preventDefault();
        amount_families.each(function (index, value) {

            total += parseFloat($(this).val());
        });

        if (idamount.val() == Math.round(total)) {

            bootbox.confirm("¿Esta seguro de aceptar esta solicitud?", function (result) {
                if (result) {
                    var message = 'Validando Solicitud..';
                    loadingUI(message);
                    $.post(server + 'aceptar-solicitud' , form_acepted_solicitude.serialize()).done(function(data){
                        $.unblockUI();
                        bootbox.alert('<h4 style="color: green">Solicitud Aceptada</h4>' , function(){
                            window.location.href = server + 'aceptar-solicitud';
                        });


                    });
                   /* setTimeout(function () {

                        form_acepted_solicitude.attr('action', server + 'aceptar-solicitud');
                        form_acepted_solicitude.submit();
                    }, 1200);
                    */
                }
            });


        } else if (idamount.val() < Math.round(total)) {
            amount_error_families.text('El monto distribuido supera el monto total').css('color', 'red');

        } else {
            amount_error_families.text('El monto distribuido es menor al monto total').css('color', 'red');

        }
    });

    $('#deny_solicitude').on('click', function (e) {

        bootbox.confirm({
            message : '¿Esta seguro que desea cancelar esta solicitud?',
            buttons: {
                'cancel' :{ label :'cancel' ,className: 'btn-primary'},
                'confirm' :{ label :'aceptar' ,className: 'btn-default'}
            },
            callback : function (result) {
                if (result) {
                    var url = server + 'rechazar-solicitud';
                    form_acepted_solicitude.attr('action', url);
                    form_acepted_solicitude.submit();
                }
            }

        });

    });

    var cancel_solicitude_sup = '.cancel-solicitude-sup';
    $(document).on('click', cancel_solicitude_sup, function (e) {
        e.preventDefault();
        var aux = $(this);
        bootbox.confirm({
            message: '¿Esta seguro que desea cancelar esta solicitud?',
            buttons: {
                'cancel': {label: 'cancelar', className: 'btn-primary'},
                'confirm': {label: 'aceptar', className: 'btn-default'}
            },
            callback: function (result) {
                if (result) {

                    $.post(server + 'cancelar-solicitud-sup', {idsolicitude: aux.attr('data-idsolicitude'),_token :$(aux).attr('data-token')})
                        .done(function (data) {
                            bootbox.alert('Solicitud Cancelada' , function(){
                                $('#table_solicitude_rm_wrapper').remove();
                                $('.table-solicituds-rm').append(data);
                                $('#table_solicitude_rm').dataTable({
                                        "order": [
                                            [ 3, "desc" ]
                                        ],
                                        "bLengthChange": false,
                                        'iDisplayLength': 7,
                                        "oLanguage": {
                                            "sSearch": "Buscar: ",
                                            "sZeroRecords": "No hay solicitudes",
                                            "sInfoEmpty": "No hay solicitudes",
                                            "sInfo": 'Mostrando _END_ de _TOTAL_',
                                            "oPaginate": {
                                                "sPrevious": "Anterior",
                                                "sNext" : "Siguiente"
                                            }
                                        }
                                    }
                                );
                            });
                            // idkc: este cambio es temporal... 
                            document.location.reload();
                        });
                }
            }
        });
    });


    var search_solicitude_sup = $('#search_solicitude_sup');
    search_solicitude_sup.on('click', function () {
        searchSolicitudeToDate('sup',this)
    });

    $("#date_start").datepicker({
        language: 'es',
        endDate: new Date(),
        format: 'dd/mm/yyyy'
    });

    $("#date_end").datepicker({
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
        if (!file.type.match(imageType)) {
            alert('ingrese solo imagenes');
        } else {
            var input = $(this),
                numFiles = input.get(0).files ? input.get(0).files.length : 1,
                label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
            input.trigger('fileselect', [numFiles, label]);
        }


    });

    /** ---------------------------------------------- GERENTE PRODUCTO -------------------------------------------- **/

    if(userType === 'P')
        listSolicitude('gerprod',$('#state_view').val());

    /* solicitude accepted */
    var form_acepted_solicitude = $('#form_make_activity');
    $('.accepted_solicitude_gerprod').on('click', function (e) {
        var total = 0;

        e.preventDefault();
        //almacenamos el monto total por cada familia
        amount_families.each(function (index, value) {

            total += parseFloat($(this).val());
        });

        if (idamount.val() == Math.round(total)) { // si la suma total es igual al monto

            bootbox.confirm("¿Esta seguro de aceptar esta solicitud?", function (result) {
                if (result) {
                    var message = 'Validando Solicitud..';
                    loadingUI(message);
                    $.post(server + 'aceptar-solicitud-gerprod', form_acepted_solicitude.serialize()).done(function(data){
                        $.unblockUI();
                        if(data === 'ok'){
                            bootbox.alert('<h4 style="color: green">Solicitud Aceptada</h4>' , function(){
                                window.location.href = server + 'aceptar-solicitud-gerprod';
                            });
                        }else{
                            bootbox.alert('<h4 style="color: red">La solicitud no se puedo guardar</h4>' , function(){
                                //window.location.href = server + 'aceptar-solicitud-gerprod';
                            });
                        }

                    });


                }
            });


        } else if (idamount.val() < Math.round(total)) {
            amount_error_families.text('El monto distribuido supera el monto total').css('color', 'red');

        } else {
            amount_error_families.text('El monto distribuido es menor al monto total').css('color', 'red');

        }
    });

    $('#deny_solicitude_gerprod').on('click', function (e) {

        bootbox.confirm({
            message : '¿Esta seguro que desea rechazar esta solicitud?',
            buttons: {
                'cancel': {label: 'cancelar', className: 'btn-primary'},
                'confirm': {label: 'aceptar', className: 'btn-default'}
            },
            callback : function (result) {
                if (result) {
                    var url = server + 'rechazar-solicitud-gerprod';
                    form_acepted_solicitude.attr('action', url);
                    form_acepted_solicitude.submit();
                }
            }
        });

    });

    var search_solicitude_gerprod = $('#search_solicitude_gerprod');
    search_solicitude_gerprod.on('click', function () {
        searchSolicitudeToDate('gerprod',this)
    });


    /** -------------------------------------------- GERENTE COMERCIAL --------------------------------------------- **/

    if(userType === 'G')
        listSolicitude('gercom',$('#state_view').val());

    /* preview image */


    var search_solicitude_gercom = $('#search_solicitude_gercom');
    search_solicitude_gercom.on('click', function () {
        searchSolicitudeToDate('gercom',this)
    });

    $('#deny_solicitude_gercom').on('click', function (e) {

        bootbox.confirm("¿Esta seguro que desea rechazar esta solicitud?", function (result) {
            if (result) {
                var url = server + 'rechazar-solicitud-gercom';
                form_acepted_solicitude.attr('action', url);
                form_acepted_solicitude.submit();
            }
        });

    });

    var approved_solicitude = $('.approved_solicitude');


    approved_solicitude.on('click',function(e){
        e.preventDefault();
        var aux = $(this);
        var total = 0;

        //almacenamos el monto total por cada familia
        amount_families.each(function (index, value) {

            total += parseFloat($(this).val());
        });

        if (idamount.val() == Math.round(total)) {
            bootbox.confirm("¿Esta seguro que desea aprobar esta solicitud?", function (result) {


                if (result) {
                    var message = 'Validando Solicitud..';
                    loadingUI(message);

                    $.post(server + 'aprobar-solicitud', form_acepted_solicitude.serialize()).done(function(data){
                        $.unblockUI();
                        if(data === 'ok'){
                            bootbox.alert('<h4 style="color: green">Solicitud Aprobada</h4>' , function(){
                                window.location.href = server + 'aprobar-solicitud';
                            });
                        }else{
                            bootbox.alert('<h4 style="color: red">La solicitud no se puedo aprobar</h4>' , function(){
                                //window.location.href = server + 'aceptar-solicitud-gerprod';
                            });
                        }

                    });



                }

            });
        }else if (idamount.val() < Math.round(total)) {
            amount_error_families.text('El monto distribuido supera el monto total').css('color', 'red');

        } else {
            amount_error_families.text('El monto distribuido es menor al monto total').css('color', 'red');

        }
    });
    /** --------------------------------------------- CONTABILIDAD ------------------------------------------------- **/

    if(userType === 'C'){
        listSolicitude('cont',APROBADO);
        listFondos('fondos-contabilidad', $('#estado_fondo_cont').val());
        $("#datefondo").val(dateactual);
    }


    var search_solicitude_cont = $('#search_solicitude_cont');
    search_solicitude_cont.on('click', function () {
        searchSolicitudeToDate('cont',this)
    });

    /** --------------------------------------------- TESORERIA ------------------------------------------------- **/

    if(userType === 'T')
    {
        listSolicitude('tes',APROBADO);
        listFondos('fondos-tesoreria');
    }


    var search_solicitude_cont = $('#search_solicitude_tes');
    search_solicitude_cont.on('click', function () {
        searchSolicitudeToDate('tes',this)
    });

    /** --------------------------------------------- ASISTENCIA DE GERENCIA ------------------------------------------------- **/

    var fondo_repmed = $('#fondo_repmed');
    var fondo_total = $('#fondo_total');
    var fondo_cuenta = $('#fondo_cuenta');
    var fondo_supervisor = $('#fondo_supervisor');
    var fondo_institucion = $('#fondo_institucion');
    var date_reg_fondo = $('#date_reg_fondo');

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
        $(this).parent().removeClass('has-error');
    });

    $(".change_before_rep").on("keyup",function(){
        $(this).autocomplete({
            minLength: 3,
            source: findRepresentatives,
            focus: function( event, ui ) {
                $(this).val( ui.item.visvisitador + ' - ' + ui.item.visnombre + ' ' +ui.item.vispaterno + ' '+ui.item.vismaterno );
                return false;
            },
            select: function( event, ui ) {

                //me trae la cuenta del rep medico
                $.get(server + 'getctabanc/' + ui.item.vislegajo).done(function(data){
                    $('#fondo_cuenta').val(data);
                });
                $(this).attr('data-select','true');
                $(this).attr('data-cod',ui.item.visvisitador);
                $(this).val(ui.item.visnombre + ' ' + ui.item.vispaterno + ' '+ui.item.vismaterno );
                $("#id_change_before_rep").val( ui.item.visvisitador );
                $(this).attr('disabled',true).addClass('success');
                $(this).parent().find('.edit-repr').fadeIn();

                return false;
            }
        })
            .data("ui-autocomplete")._renderItem = function (ul, item) {
            return $("<li>")
                .append("<a>" +
                "<span style='font-size: 70%;'>"+ item.visvisitador + ' - ' +item.visnombre+' '+item.vispaterno + ' '+ item.vismaterno+ "</span></a>")
                .appendTo(ul);
        };
    });

    $('#edit-rep').hide();
    function removeinput(data){

        data.parent().find('input:text').removeClass('success').attr('disabled', false).val('').focus();
        fondo_cuenta.val('');
        data.parent().find('input:hidden').val('');
        data.fadeOut();
    }
    $(document).on("click","#edit-rep", function(e){
        e.preventDefault();
        removeinput($(this))

    });

    function findRepresentatives(request, response) {
        function hasMatch(s) {
            return s.toLowerCase().indexOf(request.term.toLowerCase()) !== -1;
        }

        var i, l, obj, matches = [];

        if (request.term === "") {
            response([]);
            return;
        }
        for (i = 0, l = representatives.length; i < l; i++) {
            obj = representatives[i];
            if (hasMatch(obj.vispaterno)) {
                matches.push(obj);
            }
        }
        response(matches);
    }

    var urlFondos = $("#fondos").data('url');
    if(urlFondos){
        $.getJSON(server+'representatives', function (data) {
            representatives = data;
        });
        $.get(server + 'list-fondos/'+ dateactual)
        .done( function(data) {
            $('#datefondo').val(dateactual);
            $('#table_solicitude_fondos_wrapper').remove();
            $('.table-solicituds-fondos').append(data);
            $('#export-fondo').attr('href', server + 'exportfondos/' + dateactual);
            $('#table_solicitude_fondos').dataTable({
                "order": [
                    [ 0, "desc" ]
                ],
                "bLengthChange": false,
                'iDisplayLength': 7,
                "oLanguage": {
                    "sSearch": "Buscar: ",
                    "sZeroRecords": "No hay fondos",
                    "sInfoEmpty": " ",
                    "sInfo": 'Mostrando _END_ de _TOTAL_',
                    "oPaginate": {
                        "sPrevious": "Anterior",
                        "sNext" : "Siguiente"
                    }
                }
            });
        });
    }
    
    fondo_total.numeric();

    $(document).on('click','.register_fondo',function(){
        console.log(fondo_repmed.attr('disabled'));
        validate = 0;
        var aux = this;
        if(!date_reg_fondo.val()){
            date_reg_fondo.parent().addClass('has-error');
            date_reg_fondo.attr('placeholder', 'Ingrese Mes');
            date_reg_fondo.addClass('input-placeholder-error');
            console.log(validate = 1);
        }
        if(!fondo_total.val()){
            fondo_total.parent().addClass('has-error');
            fondo_total.attr('placeholder', 'Ingrese Cantidad a depositar');
            fondo_total.addClass('input-placeholder-error');
            console.log(validate = 1);
        }
        if(!fondo_cuenta.val()){
            fondo_cuenta.parent().addClass('has-error');
            fondo_cuenta.attr('placeholder', 'Ingrese Cuenta');
            fondo_cuenta.addClass('input-placeholder-error');
            console.log(validate = 1);
        }
        if(!fondo_repmed.val()){
            fondo_repmed.val('');
            fondo_repmed.parent().addClass('has-error');
            fondo_repmed.attr('placeholder', 'Ingrese Representante');
            fondo_repmed.addClass('input-placeholder-error');
            console.log(validate = 1);
        }
        if(fondo_repmed.attr('data-select') == 'false'){
            fondo_repmed.val('');
            fondo_repmed.parent().addClass('has-error');
            fondo_repmed.attr('placeholder', 'Ingrese Representante');
            fondo_repmed.addClass('input-placeholder-error');
            console.log(validate = 1);
        }
        if(validate == 0){
            var dato = {
                'institucion' : $('#fondo_institucion').val(),
                'repmed' :fondo_repmed.val(),
                'supervisor' : fondo_supervisor.val(),
                'codrepmed' : fondo_repmed.attr('data-cod'),
                'total' : fondo_total.val(),
                'cuenta': fondo_cuenta.val(),
                '_token' : $('#_token').val(),
                'start' : date_reg_fondo.val(),
                'mes' : date_reg_fondo.val()
            };
            $('#datefondo').val(date_reg_fondo.val());

            var l = Ladda.create(aux);
            l.start();
            $.post(server + 'registrar-fondo',dato)
            .done(function(data){
                if(data === 'blocked'){
                    bootbox.alert("<p class='red'>El mes se encuentra cerrado.</p>");
                    fondo_institucion.val('');
                    fondo_repmed.val('');
                    fondo_supervisor.val('');
                    fondo_total.val('');
                    fondo_cuenta.val('');
                    l.stop();
                    removeinput($('#edit-rep'));
                }
                else{
                    $('html, body').animate({scrollTop: $('.table-solicituds-fondos').offset().top -10 }, 'slow');
                    fondo_institucion.val('');
                    fondo_repmed.val('');
                    fondo_supervisor.val('');
                    fondo_total.val('');
                    fondo_cuenta.val('');
                    l.stop();
                    removeinput($('#edit-rep'));
                    $('.table-solicituds-fondos > .fondo_r').remove();
                    $('#table_solicitude_fondos_wrapper').remove();
                    $('.table-solicituds-fondos').append(data);
                    $('#export-fondo').attr('href', server + 'exportfondos/' + date_reg_fondo.val());
                    $('#table_solicitude_fondos').dataTable({
                        "order": [
                            [ 0, "desc" ]
                        ],
                        "bLengthChange": false,
                        'iDisplayLength': 7,
                        "oLanguage": {
                            "sSearch": "Buscar: ",
                            "sZeroRecords": "No hay fondos",
                            "sInfoEmpty": " ",
                            "sInfo": 'Mostrando _END_ de _TOTAL_',
                            "oPaginate": {
                                "sPrevious": "Anterior",
                                "sNext" : "Siguiente"
                            }
                        }
                    });
                }
                
            });
        }
    });
    
    $( '#form_asign-sol-resp' ).on( 'submit', function(e) 
    {
        e.preventDefault();
        responsable = '';
        $( this ).find('input[name=responsable]').each(function()
        {
            if (this.checked)
            {
                responsable = this.value;
            }
        });
        var label = $(this).find('#myModalLabel');
        if (responsable == '')
        {
                label.text('Debe Seleccionar un Responsable');
                label[0].style.color = "red";
        }
        else
        {   
            $('#modal_asign_sol_resp').modal('hide');
            label.text('Se asignara como responsable a :');
            label[0].style.color = "";  
            
            $.ajax(
            {
                type: 'post',
                url :  $( this ).prop( 'action' ),
                data: 
                {
                        "_token": $( this ).find( 'input[name=_token]' ).val(),
                        "token": $( this ).find( 'input[name=token]' ).val(),
                        "responsable": responsable
                },
                error: function()
                {
                        $('#gerdev').modal('hide');
                        responseUI('Error del Sistema','red');
                },
                success: function ( data )
                {   
                    if (data.Status == 'Error')
                    {
                        responseUI('Hubo un error al intentar asignar el responsable','red');
                    }
                    else if (data.Status == 'Ok')
                    {
                        responseUI('Responsable asignado correctamente','green');
                        setTimeout(function()
                        {
                            location.href = server + 'show_gercom';
                        },900);
                    }
                }
            });
        }
    });

    $(document).on('click' , '.delete-fondo' , function(e){
        e.preventDefault();
        var data = {
            idfondo: $(this).attr('data-idfondo'),
            _token: $(this).attr('data-token'),
            start: $("#datefondo").val()
        };
        $('#loading-fondo').attr('class','show');
        $.post(server + 'delete-fondo', data ).done(function(data){
            $('.table-solicituds-fondos > .fondo_r').remove();
            $('#table_solicitude_fondos_wrapper').remove();
            $('.table-solicituds-fondos').append(data);
            $('#table_solicitude_fondos').dataTable({
                    "order": [
                        [ 3, "desc" ]
                    ],
                    "bLengthChange": false,
                    'iDisplayLength': 7,
                    "oLanguage": {
                        "sSearch": "Buscar: ",
                        "sZeroRecords": "No hay fondos",
                        "sInfoEmpty": " ",
                        "sInfo": 'Mostrando _END_ de _TOTAL_',
                        "oPaginate": {
                            "sPrevious": "Anterior",
                            "sNext" : "Siguiente"
                        }
                    }
                }
            );
            $('#loading-fondo').attr('class','hide');
        })
    });
    $('.btn_cancel_fondo').hide();
    $('.btn_edit_fondo').hide();

    $(document).on('click','#terminate-fondo',function(e){
        e.preventDefault();
        bootbox.confirm({
            message : '¿Esta seguro que desea terminar los fondos?',
            buttons: {
                'cancel': {label: 'cancelar', className: 'btn-primary'},
                'confirm': {label: 'aceptar', className: 'btn-default'}
            },
            callback : function (result) {
                if (result) {
                    var date = $('#datefondo').val()
                    var url = server + 'endfondos/' + date;
                    $.get(url).done(function(data){
                            $('.table-solicituds-fondos > .fondo_r').remove();
                            $('#table_solicitude_fondos_wrapper').remove();
                            $('.table-solicituds-fondos').append(data);
                            $('#export-fondo').attr('href', server + 'exportfondos/' + date);
                            $('#table_solicitude_fondos').dataTable({
                                    "order": [
                                        [ 3, "desc" ]
                                    ],
                                    "bLengthChange": false,
                                    'iDisplayLength': 7,
                                    "oLanguage": {
                                        "sSearch": "Buscar: ",
                                        "sZeroRecords": "No hay fondos",
                                        "sInfoEmpty": " ",
                                        "sInfo": 'Mostrando _END_ de _TOTAL_',
                                        "oPaginate": {
                                            "sPrevious": "Anterior",
                                            "sNext" : "Siguiente"
                                        }
                                    }
                                }
                            );
                        }
                    )
                }
            }
        });
    })
    $(document).on('click','.edit-fondo',function(e){
        $(this).parent().parent().parent().css('background-color','#59A1F4')
        fondo_repmed.attr('disabled',true).addClass('success');
        $('.register_fondo').hide();
        $('#edit-rep').show();
        e.preventDefault();
        $.get(server + 'get-fondo/' + $(this).attr('data-idfondo')).done(function(data){
            $('.btn_cancel_fondo').show();
            $('.btn_edit_fondo').show();

            fondo_institucion.val(data.institucion);
            fondo_repmed.val(data.repmed);
            fondo_supervisor.val(data.supervisor);
            fondo_total.val(data.total);
            fondo_cuenta.val(data.cuenta);
            $('#idfondo').val(data.idfondo)
        });
        $('html, body').animate({scrollTop: $('#date_reg_fondo').offset().top -10 }, 'slow');

    });
    $(document).on('click','.btn_cancel_fondo',function(e){
        $('.register_fondo').show();
        $('.btn_edit_fondo').hide();
        $(this).hide();
        removeinput($('#edit-rep'));
        fondo_institucion.val('');
        fondo_repmed.val('');
        fondo_supervisor.val('');
        fondo_total.val('');
        fondo_cuenta.val('');
       $('#table_solicitude_fondos > tbody > tr').css('background-color','#fff');
    });
    $(document).on('click','.btn_edit_fondo',function(e){
        e.preventDefault();
        $('#edit-rep').hide();
        var aux = this;
        var dato = {
            'institucion' : fondo_institucion.val(),
            'repmed' : fondo_repmed.val(),
            'codrepmed' : fondo_repmed.attr('data-cod'),
            'supervisor' : fondo_supervisor.val(),
            'total' : fondo_total.val(),
            'cuenta': fondo_cuenta.val(),
            '_token' : $('#_token').val(),
            'idfondo' : $('#idfondo').val(),
            'start' : date_reg_fondo.val(),
            'mes' : date_reg_fondo.val()
        };
        var l = Ladda.create(aux);
        l.start();

        $.post(server + 'update-fondo',dato).done(function(data)
            {
                $('.btn_cancel_fondo').hide();
                $('.btn_edit_fondo').hide();
                $('.register_fondo').show();
                fondo_institucion.val('');
                fondo_repmed.val('');
                fondo_supervisor.val('');
                fondo_total.val('');
                fondo_cuenta.val('');

                removeinput($('#edit-rep'));
                l.stop();
                $('.table-solicituds-fondos > .fondo_r').remove();
                $('#table_solicitude_fondos_wrapper').remove();
                $('.table-solicituds-fondos').append(data);
                $('#export-fondo').attr('href', server + 'exportfondos/' + date_reg_fondo.val());
                $('#table_solicitude_fondos').dataTable({
                        "order": [
                            [ 0, "desc" ]
                        ],
                        "bLengthChange": false,
                        'iDisplayLength': 7,
                        "oLanguage": {
                            "sSearch": "Buscar: ",
                            "sZeroRecords": "No hay fondos",
                            "sInfoEmpty": " ",
                            "sInfo": 'Mostrando _END_ de _TOTAL_',
                            "oPaginate": {
                                "sPrevious": "Anterior",
                                "sNext" : "Siguiente"
                            }
                        }
                    }
                );
            }
        )

    });
    var date_options2 = {
        format: "mm-yyyy",
        startDate: "01/2014",
        minViewMode: 1,
        language: "es",
        autoclose: true
    };

    $("#date_reg_fondo").datepicker(date_options2).on('changeDate', function (e) {
        var datefondo = $(this).val();
        var type = $(this).attr('data-type');
        if(datefondo!='') {
            $("#datefondo").val(datefondo);
            searchFondos(datefondo,type);
        }
    });

    //change datefondo
    $("#datefondo").datepicker(date_options2).on('changeDate', function (e) {
        var datefondo = $(this).val();
        var type = $(this).attr('data-type');
        if(datefondo!='') {
            searchFondos(datefondo,type);
        }
    });

    $("#estado_fondo_cont").on("change", function(e){
        var datefondo = $("#datefondo").val();
        var aux = 'fondos-contabilidad';
        searchFondos(datefondo, aux);
    });

    function searchFondos(datefondo , aux) {
        console.log(datefondo+ "-"+aux);
        $('#loading-fondo').attr('class','show');
        $('.table-solicituds-fondos > .fondo_r').remove();
        $('.fondo_r').remove();
        var url = server + 'list-'+aux+'/' + datefondo;
        if(aux === 'fondos-contabilidad') {
            url = server + 'list-'+aux+'/' + datefondo + '/' + $('#estado_fondo_cont').val();
        }
        $.get(url)
        .done(function (data) {
            $('#loading-fondo').attr('class','hide');
            $('.table_solicituds_'+aux+' > .fondo_r').remove();
            $('#table_solicitude_'+aux+'_wrapper').remove();
            $('.table-solicituds-'+aux).append(data);
            $('#export-fondo').attr('href', server + 'exportfondos/' + datefondo);
            $('#table_solicitude_'+aux).dataTable({
                "order": [
                    [3, "desc"]
                ],
                "bLengthChange": false,
                'iDisplayLength': 7,
                "oLanguage": {
                    "sSearch": "Buscar: ",
                    "sZeroRecords": "No hay fondos",
                    "sInfoEmpty": " ",
                    "sInfo": 'Mostrando _END_ de _TOTAL_',
                    "oPaginate": {
                        "sPrevious": "Anterior",
                        "sNext": "Siguiente"
                    }
                }
            });
            //$('#total-fondo').val($('#total-fondo-hiden').val());
        });
    }

    if(userType === 'AG'){
        console.log('ager');
        $.ajax({
            url: server + 'listar-solicitudes-ager',
            type: 'GET',
            dataType: 'html'

        }).done(function (data) {
            $('.table-solicituds-ager').append(data);
            $('#table_solicitude_ager').dataTable({
                    "order": [
                        [ 3, "desc" ] //order date
                    ],
                    "bLengthChange": false,
                    'iDisplayLength': 7,
                    "oLanguage": {
                        "sSearch": "Buscar: ",
                        "sZeroRecords": "No hay solicitudes",
                        "sInfoEmpty": "No hay solicitudes",
                        "sInfo": 'Mostrando _END_ de _TOTAL_',
                        "oPaginate": {
                            "sPrevious": "Anterior",
                            "sNext" : "Siguiente"
                        }
                    }
                }
            );
        });
    }


    /** --------------------------------------------- ADMIN ------------------------------------------------- **/

        //quita los errores
    $('#form-register-user input').on('focus',function(){
        var span = $(this).parent().find('.error-incomplete').text('');

    });

    $('#username input').focusout(function(){

        if($(this).val() && $(this).attr('id') != 'user-no'){
            $('#username span:eq(2)').hide();
            $('#username span:eq(0)').hide();
            $.ajax({
                url:server + 'search-user/'+ $(this).val(),
                type:'GET',
                beforeSend : function(){
                    $('#username span:eq(1)').show();
                }
            }).done(function(data){
                $('#username span:eq(1)').hide();
                if(data === 'SI'){
                    $('#username span:eq(0)').show();
                    $('#username span:eq(0)').text('Usuario ya registrado');
                }else{
                    $('#username span:eq(2)').show();

                }
            })
        }
    });

    $('#first_name input').on('focus',function(){
        $('#first_name span').text('');
    });
    $('#last_name input').on('focus',function(){
        $('#last_name span').text('');
    });
    $('#username input').on('focus', function(){
        $('#username span').text('');
    });
    $('#email input').on('focus', function(){
        $('#email span').text('');
    });
    $('#password input').on('focus',function(){
        $('#password span').text('');
    });

    $('#register_user').on('click',function(e){
        e.preventDefault();
        var regex_email = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;


        if(!$('#first_name input').val()){
            $('#first_name span').text('Campo Obligatorio');
            validate = 1;
            console.log(validate);
        }
        if(!$('#last_name input').val()){
            $('#last_name span').text('Campo Obligatorio');
            validate = 1;
            console.log(validate);
        }
        if(!$('#username input').val()){
            $('#username span').text('Campo Obligatorio');
            validate = 1;
            console.log(validate);
        }
        if(!$('#email input').val()){
            $('#email span').text('Campo Obligatorio');

            validate = 1;
            console.log(validate);
        }else if(!regex_email.test($('#email input').val())){;
            $('#email span').text('Email Invalido');
        }
        if(!$('#password input').val() && $('#iduser').val()){
            $('#password span').text('Campo Obligatorio');
            validate = 1;
            console.log(validate);
        }

        if(validate = 0){
            $('.registerUser').submit();
        }else{
            console.log('error');
        }


    });
    $('#table-users').dataTable({
            "order": [
                [ 0, "desc" ]
            ],
            "bLengthChange": false,
            'iDisplayLength': 7,
            "oLanguage": {
                "sSearch": "Buscar: ",
                "sZeroRecords": "No hay usuarios",
                "sInfo": 'Mostrando _END_ de _TOTAL_',
                "oPaginate": {
                    "sPrevious": "Anterior",
                    "sNext" : "Siguiente"
                }
            }
        }
    );

    $('.active-user').on('click',function(e){
        e.preventDefault();
        var _iduser = $(this).data('iduser');
        var _token = $(this).data('token');
        bootbox.confirm("¿Esta seguro que desea activar este usuario?", function (result) {

            if(result){
                $.post(server + 'active-user',{ 'iduser' : _iduser , '_token' :_token } ,function(data){
                    alert('usuario activado');
                    window.location.href = server + 'register';
                })
            }
        })
    });

    $('.look-user').on('click',function(e){
        e.preventDefault();
        var _iduser = $(this).data('iduser');
        var _token  = $(this).data('token');

        bootbox.confirm("¿Esta seguro que desea desactivar este usuario?", function (result) {

            if(result){
                $.post(server + 'look-user',{ 'iduser' : _iduser , '_token' : _token} ,function(data){
                    alert('usuario desactivado');
                    window.location.href = server + 'register';
                })
            }
        })
    });

    $('#div-change-password').hide();
    if($('#iduser').val()){
        $('#div-change-password').show();
        $('#div-password').hide();
        $('#change-password').on('change',function(){
            if($(this).prop('checked')){
                $('#div-password').show();
            }else{
                $('#div-password').hide();
            }
        });

    }

    /** ------------------------------------------------------------------------------------------------------------ **/

    input_file_factura.change(function (e) {

        addImage(e);
    });
    function addImage(e) {
        var file = e.target.files[0],
            imageType = /image.*/;

        if (!file.type.match(imageType)) {
            alert('ingrese solo imagenes');
        } else {
            var reader = new FileReader();
            reader.onload = fileOnload;
            reader.readAsDataURL(file);
        }

    }

    function fileOnload(e) {
        var result = e.target.result;
        $('#imgSalida').attr("src", result);
    }

    /* end preview image */

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
        var op_number  = $("#op-number").val();
        var type_deposit = $(this).attr('data-deposit');
        var date_fondo = $('#datefondo').val();
        if(type_deposit ==='fondo'){
            url = 'deposit-fondo'
            data.idfondo = $('#idfondo').val();
            data.op_number = op_number;
            data._token = $("input[name=_token]").val();
            data.date_fondo = date_fondo;
        }else if(type_deposit === 'solicitude'){
            url = 'deposit-solicitude';
            data.op_number = op_number;
            data.token     = $("#token").val();
            data._token    = $("input[name=_token]").val();
            data.date_fondo = date_fondo;
        }
        if(!op_number)
        {
            $("#message-op-number").text("Ingrese el número de Operación");
        }
        if(date_fondo == '')
        {
            $("#message-op-number").text("Debe escoger la fecha del depósito");
        }
        else
        {
            console.log(server + url, data);
            $.post(server + url, data)
            .done(function (data){
                if(data === 'error')
                {
                    $("#message-op-number").text("No se ha podido registrar el depósito.");  
                }
                else
                {
                    $('#myModal').modal('hide');
                    bootbox.alert("<p class='green'>Se registro el asiento contable correctamente.</p>", function(){
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
}
