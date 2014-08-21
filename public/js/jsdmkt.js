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

    //$(".btn-delete-family").hide();

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

        if ($('#project' + client).length) {
            $('#project' + client).autocomplete({
                minLength: 0,
                source: lightwell,
                focus: function (event, ui) {
                    $(this).val(ui.item.clcodigo + ' - ' + ui.item.clnombre);

                    return false;
                },
                select: function (event, ui) {
                    // $( "#project" ).val( ui.item.label );
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
        $('<li><div style="position: relative"><input id="project' + client + '" name="clients[]" type="text" placeholder="" style="margin-bottom: 10px" class="form-control input-md project"><button type="button" class="btn-delete-client" style="z-index: 2"><span class="glyphicon glyphicon-remove"></span></button></div></li>').appendTo('#listclient');
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

    $('.project').on('focus',function(){
        $(this).parent().removeClass('has-error');
    });



    $('.register_solicitude').on('click', (function (e) {


        var aux = 0;
        var validate = 0; // si cambia  a 1 hay errores
        var obj = [];
        var clients_input = [];
        var families_input = [];
        for (var i = 0, l = clients.length; i < l; i++) {
            obj[i] = clients[i].clcodigo + ' - ' + clients[i].clnombre;
            //console.log(clients[i].clnombre);
        }

        setTimeout(function () {


            $('.project').each(function (index) {
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
                $('.project').each(function (index) {

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
                'iDisplayLength': 5
            }
        );
    });


    /* Listar actividades pendientes */
/*
    $.ajax({
        url: server + 'listar-actividades-rm/' + 2,
        type: 'GET',
        dataType: 'html'

    }).done(function (data) {
        $('.table-activities').append(data);
        $('#table_activity_rm').dataTable({
                "bLengthChange": false,
                'iDisplayLength': 5
            }
        );
    });

*/


    $('#selectestatesolicitude').on('change', function () {
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
                        'iDisplayLength': 5
                    }
                );
            });
        }, 200)

    });
    /*
    $('#selectestateactivity').on('change', function () {

        var idstate = $(this).val();
        $('#table_activity_rm_wrapper').remove();
        setTimeout(function () {

            $.ajax({
                url: server + 'listar-actividades-rm/' + idstate,
                type: 'GET',
                dataType: 'html'

            }).done(function (data) {
                $('.table-activities').append(data);
                $('#table_activity_rm').dataTable({
                        "bLengthChange": false,
                        'iDisplayLength': 5
                    }
                );
            });
        }, 200)

    });
*/
    /** Supervisor */

    /* listar solicitudes pendientes */
    $.ajax({
        url: server + 'listar-solicitudes-sup/' +2,
        type: 'GET',
        dataType: 'html'


    }).done(function (data) {
        $('.table-solicituds-sup').append(data);
        $('#table_solicitude_sup').dataTable({
                "bLengthChange": false,
                'iDisplayLength': 5
            }
        );
    });

    /* listar actividades pendientes */
   /* $.ajax({
        url: server + 'listar-actividades-sup/' + 2,
        type: 'GET',
        dataType: 'html'

    }).done(function (data) {
        $('.table-activities-sup').append(data);
        $('#table_activity_sup').dataTable({
                "bLengthChange": false,
                'iDisplayLength': 5
            }
        );
    });*/

    $('#selectestatesolicitude_sup').on('change', function () {
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
                        'iDisplayLength': 5
                    }
                );
            });
        }, 200)

    });
    /*
    $('#selectestateactivity_sup').on('change', function () {

        var idstate = $(this).val();
        $('#table_activity_sup_wrapper').remove();
        setTimeout(function () {

            $.ajax({
                url: server + 'listar-actividades-sup/' + idstate,
                type: 'GET',
                dataType: 'html'

            }).done(function (data) {
                $('.table-activities-sup').append(data);
                $('#table_activity_sup').dataTable({
                        "bLengthChange": false,
                        'iDisplayLength': 5
                    }
                );
            });
        }, 200)

    });
*/

    var form_acepted_solicitude = $('#form_make_activity');
    $('#register_activity').on('click', function () {

        form_acepted_solicitude.attr('action', server + 'aceptar-solicitud');
        form_acepted_solicitude.submit();

    });

    $('#deny_solicitude').on('click', function (e) {
        // e.preventDefault();

        var url = server + 'rechazar-solicitud';
        console.log(url);
        form_acepted_solicitude.attr('action', url);
        form_acepted_solicitude.submit();

    });

}
