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

    var userType = $('#typeUser').val();
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
                $(".btn-delete-client").hide();
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
        validate = 0;
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
            console.log(validate = 1);
        }
        if (!amount.val()) {
            amount.parent().addClass('has-error');
            amount.attr('placeholder', 'Ingrese monto');
            amount.addClass('input-placeholder-error');
            console.log(validate = 1);
        }
        if (!delivery_date.val()) {
            delivery_date.parent().addClass('has-error');
            delivery_date.attr('placeholder', 'Ingrese Fecha');
            delivery_date.addClass('input-placeholder-error');
            console.log(validate = 1);
        }
        if (!input_client.val()) {
            input_client.parent().addClass('has-error');
            input_client.attr('placeholder', 'Ingrese Cliente');
            input_client.addClass('input-placeholder-error');
            console.log(validate = 1);
        }
        if(select_type_solicitude.val() == 2){
            if (!amount_fac.val()) {
                amount_fac.parent().addClass('has-error');
                amount_fac.attr('placeholder', 'Ingrese Monto de la Factura');
                amount_fac.addClass('input-placeholder-error');
                console.log(validate = 1);
            }
            if(!input_file_factura.val() && isSetImage.val()==null){
                input_file_factura.parent().addClass('has-error');
                input_file_factura.attr('placeholder', 'Ingrese Imagen');
                input_file_factura.addClass('input-placeholder-error');
                console.log(validate = 1);
            }
        }

        setTimeout(function () {

            //validate fields client are correct
            $('.input-client').each(function (index) {
                var input = $(this).val();
                clients_input[index] = input;

                var ban = obj.indexOf(input);
                if (ban != -1) {
                    //field no correct

                } else {
                    aux = 1;
                    console.log(validate = 1);

                    $(this).parent().addClass('has-error has-feedback');
                    $(this).parent().children('.span-alert').addClass('span-alert glyphicon glyphicon-remove form-control-feedback');
                }

            });


        }, 100);

        setTimeout(function () {
            //Validate of fields duplicate in clients
            for (var i = 0; i < clients_input.length; i++) {
                $('.input-client').each(function (index) {

                    if (index != i && clients_input[i] === $(this).val()) {
                        console.log(validate = 1);
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

        setTimeout(function () {

            var families = $('.selectfamily');
            families.each(function (index) {
                families_input[index] = $(this).val();

            });

            //Validate fields select family duplicates are not
            for (var i = 0; i < families_input.length; i++) {
                families.each(function (index) {

                    if (index != i && families_input[i] === $(this).val()) {
                        console.log(validate = 1);
                        var ind = families_input.indexOf($(this).val());
                        families_input[index] = '';

                        $(this).css('border-color', 'red');
                    }
                });
            }
            console.log(validate);

            if (validate == 0) {

                var form = $('#form-register-solicitude');
                var formData = new FormData(form[0]);
                var rute = form.attr('action');
                var message1 = 'Registrando';
                var message2 = 'Registro Completado';
                if (rute == 'editar-solicitud') {
                    message1 = 'Actualizando';
                    message2 = 'Registro Actualizado'
                }

                $.ajax({
                    url: server + rute,
                    type: 'POST',
                    data: formData,
                    // async: false,
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function () {
                        loadingUI(message1);
                    }

                }).done(function (data) {

                    // $.unblockUI();

                    if (data == 'R') {

                        responseUI(message2, 'green');
                        setTimeout(
                            function () {
                                window.location.href = server + 'show_rm'
                            }
                            , 200);

                    }
                    if(data == 'S'){
                        responseUI(message2, 'green');
                        setTimeout(
                            function () {
                                window.location.href = server + 'show_sup'
                            }
                            , 200);
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
                        "sInfo": 'Mostrando _END_ de _TOTAL_'
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
            var jqxhr = $.post(server + "buscar-solicitudes-"+typeUser, { idstate: $('#idState').val(), date_start: $('#date_start').val(), date_end: $('#date_end').val() })
                .done(function (data) {
                    console.log(data);
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
                                "sInfo": 'Mostrando _END_ de _TOTAL_'
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

    if(userType === 'R')
        listSolicitude('rm',PENDIENTE);


    /* Filter all solicitude by date */
    var search_solicitude = $('#search-solicitude');
    search_solicitude.on('click', function(){ searchSolicitudeToDate('rm',this )});


    /* Cancel Solicitude */
    var cancel_solicitude = '.cancel-solicitude';
    $(document).on('click', cancel_solicitude, function (e) {
        e.preventDefault();
        var aux = $(this);
        bootbox.confirm("¿Esta seguro que desea cancelar esta solicitud?", function (result) {
            if (result) {

                $.post(server + 'cancelar-solicitud-rm', {idsolicitude: $(aux).attr('data-idsolicitude')})
                    .done(function (data) {
                        console.log(data);

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
                                    "sInfo": 'Mostrando _END_ de _TOTAL_'
                                }
                            }
                        );

                    })
            }
        });


    });


    /**------------------------------------------------ SUPERVISOR ---------------------------------------------------*/

    /* list solicitude pending or depending of type state */
    if(userType === 'S')
        listSolicitude('sup', $('#state_view').val());


    var amount_families = $('.amount_families');
    amount_families.numeric({negative: false});

    /* validate amount families no more than amount total*/


    amount_families.keyup(function (e) {
        var sum_total =0;
        amount_families.each(function(i,v){
            sum_total += parseFloat($(this).val());
            console.log(Math.round(sum_total));
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
                    setTimeout(function () {

                        form_acepted_solicitude.attr('action', server + 'aceptar-solicitud');
                        form_acepted_solicitude.submit();
                    }, 1200);

                }
            });


        } else if (idamount.val() < Math.round(total)) {
            amount_error_families.text('El monto distribuido supera el monto total').css('color', 'red');

        } else {
            amount_error_families.text('El monto distribuido es menor al monto total').css('color', 'red');

        }
    });

    $('#deny_solicitude').on('click', function (e) {

        bootbox.confirm("¿Esta seguro que desea rechazar esta solicitud?", function (result) {
            if (result) {
                var url = server + 'rechazar-solicitud';
                form_acepted_solicitude.attr('action', url);
                form_acepted_solicitude.submit();
            }
        });

    });

    var cancel_solicitude_sup = '.cancel-solicitude-sup';
    $(document).on('click', cancel_solicitude_sup, function (e) {
        e.preventDefault();
        var aux = $(this);
        bootbox.confirm("¿Esta seguro que desea cancelar esta solicitud?", function (result) {
            if (result) {

                $.post(server + 'cancelar-solicitud-sup', {idsolicitude: aux.attr('data-idsolicitude')})
                    .done(function (data) {
                        console.log(data);

                        $('#table_solicitude_sup_wrapper').remove();
                        $('.table-solicituds-sup').append(data);
                        $('#table_solicitude_sup').dataTable({
                                "order": [
                                    [ 3, "desc" ]
                                ],
                                "bLengthChange": false,
                                'iDisplayLength': 7,
                                "oLanguage": {
                                    "sSearch": "Buscar: ",
                                    "sZeroRecords": "No hay solicitudes",
                                    "sInfoEmpty": "No hay solicitudes",
                                    "sInfo": 'Mostrando _END_ de _TOTAL_'
                                }
                            }
                        );

                    })
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
                    setTimeout(function () {

                        form_acepted_solicitude.attr('action', server + 'aceptar-solicitud-gerprod');
                        form_acepted_solicitude.submit();
                    }, 1200);

                }
            });


        } else if (idamount.val() < Math.round(total)) {
            amount_error_families.text('El monto distribuido supera el monto total').css('color', 'red');

        } else {
            amount_error_families.text('El monto distribuido es menor al monto total').css('color', 'red');

        }
    });

    $('#deny_solicitude_gerprod').on('click', function (e) {

        bootbox.confirm("¿Esta seguro que desea rechazar esta solicitud?", function (result) {
            if (result) {
                var url = server + 'rechazar-solicitud-gerprod';
                form_acepted_solicitude.attr('action', url);
                form_acepted_solicitude.submit();
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
                setTimeout(function () {

                    form_acepted_solicitude.attr('action', server + 'aprobar-solicitud');
                    form_acepted_solicitude.submit();
                }, 1200);

            }

        });
        }else if (idamount.val() < Math.round(total)) {
            amount_error_families.text('El monto distribuido supera el monto total').css('color', 'red');

        } else {
            amount_error_families.text('El monto distribuido es menor al monto total').css('color', 'red');

        }
    });
    /** --------------------------------------------- CONTABILIDAD ------------------------------------------------- **/

    if(userType === 'C')
        listSolicitude('cont',APROBADO);

    var search_solicitude_cont = $('#search_solicitude_cont');
    search_solicitude_cont.on('click', function () {
        searchSolicitudeToDate('cont',this)
    });

    /** --------------------------------------------- TESORERIA ------------------------------------------------- **/

    if(userType === 'T')
        listSolicitude('tes',APROBADO);

    var search_solicitude_cont = $('#search_solicitude_tes');
    search_solicitude_cont.on('click', function () {
        searchSolicitudeToDate('tes',this)
    });


    /** --------------------------------------------- ADMIN ------------------------------------------------- **/

    //quita los errores
    $('#form-register-user input').on('focus',function(){
        var span = $(this).parent().find('.error-incomplete').text('');
        console.log(span);
    });

    $('#username input').focusout(function(){

        if($(this).val()){
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

    $('#register_user').on('click',function(e){
        e.preventDefault();
        var form = $('#form-register-user');
        var message = 'Registrando Usuario';
        var message2 = 'Usuario Registrado';
        var message3 = 'Verifique sus campos';

        $.ajax({
            url: server + 'register-user',
            type: 'POST',
            data: form.serialize(),

            beforeSend: function () {
                loadingUI(message);
            }

        }).done(function (data) {
           // $.unblockUI();

            if (data == 'SI') {
                responseUI(message2, 'green');
                setTimeout(
                    function () {
                        window.location.href = server + 'register'
                    }
                    , 200);
            }else{
                responseUI(message3, 'red');
                if(typeof data.username != 'undefined')
                $('#username span:first').text(data.username[0]);
                if(typeof data.last_name != 'undefined')
                $('#last_name span:first').text(data.last_name[0]);
                if(typeof data.first_name != 'undefined')
                $('#first_name span:first').text(data.first_name[0]);
                if(typeof data.email != 'undefined')
                $('#email span:first').text(data.email[0]);
                if(typeof data.password != 'undefined')
                $('#password span:first').text(data.password[0]);



            }
        }).fail(function (e) {
            $.unblockUI();
            alert('error');
        })

    });
    $('#table-users').dataTable({
            "order": [
                [ 0, "desc" ]
            ],
            "bLengthChange": false,
            'iDisplayLength': 7,
            "oLanguage": {
                "sSearch": "Buscar: ",
                "sZeroRecords": "No hay solicitudes",
                "sInfoEmpty": "No hay solicitudes",
                "sInfo": 'Mostrando _END_ de _TOTAL_'
            }
        }
    );



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
}
