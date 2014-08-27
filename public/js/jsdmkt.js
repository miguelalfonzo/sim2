newSolicitude();
function newSolicitude() {

    var clients = [];

    $.getJSON(server + "getclients", function (data) {
        clients = data;
    });

    //$('.clients_repeat').hide();

    $("#btn-add-family").on('click', function () {
        //console.log('hola');
        $(".btn-delete-family").show();
        $('#listfamily>li:first-child').clone(true, true).appendTo('#listfamily');
    });




    $(document).on("click", ".btn-delete-family", function () {
        $('#listfamily>li .porcentaje_error').css({"border": "0"});

        $(".option-des-1").removeClass('error');
        var k = $("#listfamily li").size();
        console.log(k);
        if (k > 1) {

            var other = $(".btn-delete-family").index(this);
            $("#listfamily li").eq(other).remove();
            var p = $("#listfamily li").size();
            if (p == 1) {
                $(".btn-delete-family").hide();
            }
        }
    });
    //Validaciones
    $('#idestimate').numeric();
    $('#idamount').numeric();


    //Selecionar tipo de solocitud

    $('.solicitude_factura').hide();
    $('.solicitude_monto').hide();
    $('.selecttypesolicitude').on('change', (function () {

        if ($(this).val() == 1) {

            $('.solicitude_factura').hide()
            $('.solicitude_monto').hide();
        } else if ($(this).val() == 2) {

            $('.solicitude_factura').show();
            $('.solicitude_monto').show();

        } else if ($(this).val() == 3) {

            $('.solicitude_factura').hide();
            $('.solicitude_monto').hide();
        }

    }));

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

        if ($('#idclient' + client).length) {
            $('#idclient' + client).autocomplete({
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
        console.log(k);
        if (k > 1) {
            var other = $(".btn-delete-client").index(this);
            $("#listclient li").eq(other).remove();
            var p = $("#listclient li").size();
            if (p == 1) {
                $(".btn-delete-client").hide();
            }
        }
    });

    //Removiendo Errores
    var title = $('#idtitle');
    var amount = $('#idestimate');
    var delivery_date = $('#delivery_date');
    var amountfac = $('#amount-fac');
    var input_client = $('.input-client');

    title.on('focus',function(){
        $(this).parent().removeClass('has-error');
    });
    amount.on('focus',function(){
        $(this).parent().removeClass('has-error');
    });
    delivery_date.on('focus',function(){
       $(this).parent().removeClass('has-error');
    });
    input_client.on('focus',function(){
       $(this).parent().removeClass('has-error')
    });
    amountfac.on('focus',function(){
        $(this).parent().removeClass('has-error')
    })



//validamos el envio del registro
    $('.register_solicitude').on('click', (function (e) {
        var aux = 0;
        var validate = 0; // si cambia  a 1 hay errores
        var obj = [];
        var clients_input = [];
        var families_input = [];
        for (var i = 0, l = clients.length; i < l; i++) {
            obj[i] = clients[i].clcodigo + ' - ' + clients[i].clnombre;
        }



        if(!title.val()) {
            title.parent().addClass('has-error');
            title.attr('placeholder','Ingrese nombre de la solicitud');
            title.addClass('input-placeholder-error');
        }
        if(!amount.val()) {
            amount.parent().addClass('has-error');
            amount.attr('placeholder','Ingrese monto');
            amount.addClass('input-placeholder-error');
        }
        if(!delivery_date.val()) {
            delivery_date.parent().addClass('has-error');
            delivery_date.attr('placeholder','Ingrese Fecha');
            delivery_date.addClass('input-placeholder-error');
        }
        if(!input_client.val()) {
            input_client.parent().addClass('has-error');
            input_client.attr('placeholder','Ingrese Cliente');
            input_client.addClass('input-placeholder-error');
        }
        if(!amountfac.val()) {
            amountfac.parent().addClass('has-error');
            amountfac.attr('placeholder','Ingrese Cliente');
            amountfac.addClass('input-placeholder-error');
        }

        setTimeout(function () {


            $('.input-client').each(function (index) {
                var input = $(this).val();
                clients_input[index] = input;

                var ban = obj.indexOf(input);
                if (ban != -1) {
                    console.log(ban);

                } else {
                    aux = 1;
                    validate = 1;
                    console.log(ban);
                    $(this).parent().addClass('has-error has-feedback');
                    $(this).parent().children('.span-alert').addClass('span-alert glyphicon glyphicon-remove form-control-feedback');
                }

            });


        }, 100);

        setTimeout(function () {
            //validacion de campos duplicados en clientes
            for (var i = 0; i < clients_input.length; i++) {
                $('.input-client').each(function (index) {

                    if (index != i && clients_input[i] === $(this).val()) {
                        validate = 1;
                        var ind = clients_input.indexOf($(this).val());
                        clients_input[index] = '';
                        // console.log($(this).val() + ' - '+ index )
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


            for (var i = 0; i < families_input.length; i++) {
                families.each(function (index) {

                    if (index != i && families_input[i] === $(this).val()) {
                        validate = 1;
                        var ind = families_input.indexOf($(this).val());
                        families_input[index] = '';
                        console.log($(this).val() + ' - ' + index);
                        $(this).css('border-color', 'red');
                    }
                });
            }

            if (validate == 0) {

                $('#form-register-solicitude').submit();
            } else {
                alert('datos incorrectos');
            }

        }, 300);
        e.preventDefault();
    }));

    /* Listar solicitudes pendientes*/
    $.ajax({
        url: server + 'listar-solicitudes/' + 2,
        type: 'GET',
        dataType: 'html'

    }).done(function (data) {
        $('.table-solicituds').append(data);
        $('#table_solicitude').dataTable({
                "bLengthChange": false,
                'iDisplayLength': 5,
                "oLanguage": {
                    "sSearch": "Buscar: ",
                    "sZeroRecords": "No hay solicitudes",
                    "sInfoEmpty": "No hay solicitudes",
                    "sInfo":'Mostrando _END_ de _TOTAL_'
                }
            }
        );
    });


    $('#idstate').on('change', function () {
        var idstate = $(this).val();
        $('#table_solicitude_wrapper').remove();
        setTimeout(function () {

            $.ajax({
                url: server + 'listar-solicitudes/' + idstate,
                type: 'GET',
                dataType: 'html'

            }).done(function (data) {
                $('.table-solicituds').append(data);
                $('#table_solicitude').dataTable({
                        "bLengthChange": false,
                        'iDisplayLength': 5,
                        "oLanguage": {
                            "sSearch": "Buscar: ",
                            "sZeroRecords": "No hay solicitudes",
                            "sInfoEmpty": "No hay solicitudes",
                            "sInfo":'Mostrando _END_ de _TOTAL_'
                        }
                    }
                );
            });
        }, 200)

    });

    /* Filtramos todas las solicitudes por fecha*/
    var search_solicitude = $('#search-solicitude');
    search_solicitude.on('click',function(){
        var l = Ladda.create(this);
        l.start();
        var jqxhr = $.post( server + "buscar-solicitudes", { idstate: $('#idstate').val() ,date_start: $('#date_start').val() ,date_end: $('#date_end').val() } )
            .done(function(data) {
                $('#table_solicitude_wrapper').remove();
                $('.table-solicituds').append(data);
                $('#table_solicitude').dataTable({
                        "bLengthChange": false,
                        'iDisplayLength': 5,
                        "oLanguage": {
                            "sSearch": "Buscar: ",
                            "sZeroRecords": "No hay solicitudes",
                            "sInfoEmpty": "No hay solicitudes",
                            "sInfo":'Mostrando _END_ de _TOTAL_'
                        }
                    }
                );
                l.stop();
            })
            .fail(function() {
                alert( "error" );
            })

    });


    /* Cancelar una solicitud */
    var cancel_solicitude = '.cancel-solicitude';
    $(document).on('click',cancel_solicitude,function(e){
        e.preventDefault();
        $.post(server + 'cancelar-solicitud-rm',{idsolicitude : $(this).attr('data-idsolicitude')})
         .done(function(data){
         $('#table_solicitude_wrapper').remove();
         $('.table-solicituds').append(data);
         $('#table_solicitude').dataTable({
         "bLengthChange": false,
         'iDisplayLength': 5,
                 "oLanguage": {
                     "sSearch": "Buscar: ",
                     "sZeroRecords": "No hay solicitudes",
                     "sInfoEmpty": "No hay solicitudes",
                     "sInfo":'Mostrando _END_ de _TOTAL_'
                 }
         }
         );
         })

    });


    /** SUPERVISOR */

    /* listar solicitudes pendientes */
    $.ajax({
        url: server + 'listar-solicitudes-sup/' +2,
        type: 'GET',
        dataType: 'html'


    }).done(function (data) {
        $('.table-solicituds-sup').append(data);
        $('#table_solicitude_sup').dataTable({
                "bLengthChange": false,
                'iDisplayLength': 5,
                "oLanguage": {
                    "sSearch": "Buscar: ",
                    "sZeroRecords": "No hay solicitudes",
                    "sInfoEmpty": "No hay solicitudes",
                    "sInfo":'Mostrando _END_ de _TOTAL_'
                }
            }
        );
    });

    $('#select_state_solicitude_sup').on('change', function () {
        var idstate = $(this).val();
       $('#table_solicitude_sup_wrapper').remove();
        setTimeout(function () {

            $.ajax({
                url: server + 'listar-solicitudes-sup/' + idstate,
                type: 'GET',
                dataType: 'html'

            }).done(function (data) {
                $('.table-solicituds-sup').append(data);
                $('#table_solicitude_sup').dataTable({
                        "bLengthChange": false,
                        'iDisplayLength': 5,
                        "oLanguage": {
                            "sSearch": "Buscar: ",
                            "sZeroRecords": "No hay solicitudes",
                            "sInfoEmpty": "No hay solicitudes",
                            "sInfo":'Mostrando _END_ de _TOTAL_'
                        }
                    }
                );
            });
        }, 200)

    });


    var amount_families = $('.amount_families');
    amount_families.numeric({negative:false});
    amount_families.keyup(function(){
        console.log($(this).val()+ ' ' + $('#idamount').val());
        if(parseInt($(this).val()) > parseInt($('#idamount').val())){
            alert('el monto supera el monto total');
        }
    });

    var amount_error_families =   $('#amount_error_families');
    amount_families.on('focus',function(){
        amount_error_families.text('');
    });
    var form_acepted_solicitude = $('#form_make_activity');
    $('.accepted_solicitude_sup').on('click', function (e) {
        var total = 0;
        var idamount = $('#idamount');
        e.preventDefault();
        amount_families.each(function(index,value){
            console.log(index + ' ' + $(this).val());
            total += parseFloat($(this).val());
        });

        if(idamount.val()== Math.round(total)){
            form_acepted_solicitude.attr('action', server + 'aceptar-solicitud');
            form_acepted_solicitude.submit();
        }else if(idamount.val() < Math.round(total)){
            amount_error_families.text('El monto distribuido supera el monto total').css('color','red');
            console.log('el monto distribuido supera el monto total')
        }else{
            amount_error_families.text('El monto distribuido es menor al monto total').css('color','red');
            console.log(total);
            console.log('el monto distribuido es menor al monto total')
        }


    });

    $('#deny_solicitude').on('click', function (e) {
        // e.preventDefault();

        var url = server + 'rechazar-solicitud';
        console.log(url);
        form_acepted_solicitude.attr('action', url);
        form_acepted_solicitude.submit();

    });



    var search_solicitude_sup = $('#search_solicitude_sup');
    search_solicitude_sup.on('click',function(){
        var l = Ladda.create(this);
        var idstate = $('#select_state_solicitude_sup').val();
        l.start();
        var jqxhr = $.post( server + "buscar-solicitudes-sup",
            { idstate: idstate ,date_start: $('#date_start').val() ,date_end: $('#date_end').val() } )
            .done(function(data) {
                $('#table_solicitude_sup_wrapper').remove();
                $('.table-solicituds-sup').append(data);
                $('#table_solicitude_sup').dataTable({
                            "bLengthChange": false,
                            'iDisplayLength': 5,
                            "oLanguage": {
                            "sSearch": "Buscar: ",
                            "sZeroRecords": "No hay solicitudes",
                            "sInfoEmpty": "No hay solicitudes",
                            "sInfo":'Mostrando _END_ de _TOTAL_'
                        }
                        }
                    );

                l.stop();
            })
            .fail(function() {
                alert( "error" );
            })

    });

    $("#date_start").datepicker({
        language: 'es',
        format: 'dd/mm/yyyy'
    });


    $("#date_end").datepicker({
        //startDate: new Date($.datepicker.formatDate('dd, mm, yy', new Date($('#date_start').val()))),
        language: 'es',
        endDate: new Date(),
        format: 'dd/mm/yyyy'
    });

    $('.btn-file :file').on('fileselect', function(event, numFiles, label) {
        console.log(numFiles);
        console.log(label);
        $('#input-file-factura').val(label);
    });

    $(document).on('change', '.btn-file :file', function() {
        var input = $(this),
            numFiles = input.get(0).files ? input.get(0).files.length : 1,
            label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
        input.trigger('fileselect', [numFiles, label]);
    });

    /** GERENTE COMERCIAL */

    $.ajax({
        url: server + 'listar-solicitudes-gercom/' +8,
        type: 'GET',
        dataType: 'html'


    }).done(function (data) {
        $('.table-solicituds-gercom').append(data);
        $('#table_solicitude_gercom').dataTable({
                "bLengthChange": false,
                'iDisplayLength': 5,
                "oLanguage": {
                    "sSearch": "Buscar: ",
                    "sZeroRecords": "No hay solicitudes",
                    "sInfoEmpty": "No hay solicitudes",
                    "sInfo":'Mostrando _END_ de _TOTAL_'
                }
            }
        );
    });

    $('.select_state_solicitude_gercom').on('change',function(){
        var idstate = $(this).val();
        $('#table_solicitude_gercom_wrapper').remove();
        setTimeout(function () {

            $.ajax({
                url: server + 'listar-solicitudes-gercom/' + idstate,
                type: 'GET',
                dataType: 'html'

            }).done(function (data) {
                $('.table-solicituds-gercom').append(data);
                $('#table_solicitude_gercom').dataTable({
                        "bLengthChange": false,
                        'iDisplayLength': 5,
                        "oLanguage": {
                            "sSearch": "Buscar: ",
                            "sZeroRecords": "No hay solicitudes",
                            "sInfoEmpty": "No hay solicitudes",
                            "sInfo":'Mostrando _END_ de _TOTAL_'
                        }
                    }
                );
            });
        }, 200)
    })
}
