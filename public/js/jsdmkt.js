newSolicitude();
function newSolicitude() {

    var clients = [];

    // get clients
    $.getJSON(server + "getclients", function (data) {
        clients = data;
    });

    //add a family
    $("#btn-add-family").on('click', function () {

        $(".btn-delete-family").show();
        $('#listfamily>li:first-child').clone(true, true).appendTo('#listfamily');
    });


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
    $('#idestimate').numeric();
    $('#idamount').numeric();
    $('#amountfac').numeric();


    // Select type de solicitude
    $('.solicitude_factura').hide();
    $('.solicitude_monto').hide();

    var select_type_solicitude = $('.selecttypesolicitude');
    if (select_type_solicitude.val() == 2) {
        $('.solicitude_factura').show();
        $('.solicitude_monto').show();
    }
    select_type_solicitude.on('change', (function () {

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

    var title = $('#idtitle');
    var amount = $('#idestimate');
    var delivery_date = $('#delivery_date');
    var amountfac = $('#amount-fac');
    var input_client = $('.input-client');
    var selectfamily = $('.selectfamily');

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
    selectfamily.on('click', function () {
        $(this).css('border-color', 'none')
    });
    /* End Removing Errors */


    /* Validate send register solicitude */

    $('.register_solicitude').on('click', (function (e) {
        var aux = 0;
        var validate = 0; // si cambia  a 1 hay errores
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
        if (!amountfac.val()) {
            amountfac.parent().addClass('has-error');
            amountfac.attr('placeholder', 'Ingrese Cliente');
            amountfac.addClass('input-placeholder-error');
            // validate =1;
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
                        $.blockUI({ css: {
                            border: 'none',
                            padding: '15px',
                            backgroundColor: '#000',
                            '-webkit-border-radius': '10px',
                            '-moz-border-radius': '10px',
                            opacity: .5,
                            color: '#fff'
                        }, message: '<h2><img style="margin-right: 30px" src="' + server + 'img/spiffygif.gif" >' + message1 + '</h2>'});
                    }

                }).done(function (data) {

                    $.unblockUI();

                    if (data == true) {

                        $.blockUI({ css: {
                            border: 'none',
                            padding: '15px',
                            backgroundColor: '#28DA60',
                            '-webkit-border-radius': '10px',
                            '-moz-border-radius': '10px',
                            opacity: .7,
                            color: '#fff'
                        }, message: '<h2>' + message2 + '</h2>' });

                        setTimeout(
                            function () {
                                window.location.href = server + 'show_rm'
                            }
                            , 2000);

                    }

                }).fail(function (e) {
                    $.unblockUI();
                    alert('error');
                });


            }

        }, 300);
        e.preventDefault();
    }));

    /* List solicitude pending */
    $.ajax({
        url: server + 'listar-solicitudes/' + 2,
        type: 'GET',
        dataType: 'html'

    }).done(function (data) {
        $('.table-solicituds').append(data);
        $('#table_solicitude').dataTable({
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

    /*
     $('#idstate').on('change', function () {
     var idstate = $(this).val();
     $('#date_start').val();
     $('#date_end').val();
     $('#table_solicitude_wrapper').remove();
     setTimeout(function () {

     var jqxhr = $.post(server + "buscar-solicitudes", { idstate: $('#idstate').val(), date_start: $('#date_start').val(), date_end: $('#date_end').val() })
     .done(function (data) {
     $('#table_solicitude_wrapper').remove();
     $('.table-solicituds').append(data);
     $('#table_solicitude').dataTable({
     "bLengthChange": false,
     'iDisplayLength': 5,
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

     /*$.ajax({

     url: server + 'listar-solicitudes/' + idstate,
     type: 'GET',
     dataType: 'html',
     beforeSend:function(){
     $.blockUI({css: {
     border: 'none',
     padding: '15px',
     backgroundColor: 'invisible',
     '-webkit-border-radius': '10px',
     '-moz-border-radius': '10px',
     opacity: .7,
     color: '#fff'

     },  message: $('#loading') });
     }

     }).done(function (data) {
     $.unblockUI();
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
     */
    /* Filter all solicitude by date */
    var search_solicitude = $('#search-solicitude');
    search_solicitude.on('click', function () {
        var l = Ladda.create(this);
        l.start();
        var jqxhr = $.post(server + "buscar-solicitudes", { idstate: $('#idstate').val(), date_start: $('#date_start').val(), date_end: $('#date_end').val() })
            .done(function (data) {
                $('#table_solicitude_wrapper').remove();
                $('.table-solicituds').append(data);
                $('#table_solicitude').dataTable({
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

    });


    /* Cancel Solicitude */
    var cancel_solicitude = '.cancel-solicitude';
    $(document).on('click', cancel_solicitude, function (e) {
        e.preventDefault();
        var aux = $(this);
        bootbox.confirm("¿Esta seguro que desea eliminar esta solicitud?", function (result) {
            if (result) {

                $.post(server + 'cancelar-solicitud-rm', {idsolicitude: $(aux).attr('data-idsolicitude')})
                    .done(function (data) {
                        $('#table_solicitude_wrapper').remove();
                        $('.table-solicituds').append(data);
                        $('#table_solicitude').dataTable({
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


    /** SUPERVISOR */

    /* list solicitude pending */
    $.ajax({
        url: server + 'listar-solicitudes-sup/' + 2,
        type: 'GET',
        dataType: 'html'


    }).done(function (data) {
        $('.table-solicituds-sup').append(data);
        $('#table_solicitude_sup').dataTable({
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

    /* change state solicitude supervisor */
    /*
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
     "sInfo": 'Mostrando _END_ de _TOTAL_'
     }
     }
     );
     });
     }, 200)

     });*/


    var amount_families = $('.amount_families');
    amount_families.numeric({negative: false});
    /* validate amount families no more than amount total*/
    amount_families.keyup(function (e) {

        if (parseInt($(this).val()) > parseInt($('#idamount').val())) {
            amount_error_families.text('El monto supera al monto total').css('color', 'red');
        } else {
            if (e.keyCode == 8) {

                amount_error_families.text('');
            }
        }

    });

    var amount_error_families = $('#amount_error_families');
    amount_families.on('focus', function () {
        amount_error_families.text('');
    });
    /* solicitude accepted */
    var form_acepted_solicitude = $('#form_make_activity');
    $('.accepted_solicitude_sup').on('click', function (e) {
        var total = 0;
        var idamount = $('#idamount');
        e.preventDefault();
        amount_families.each(function (index, value) {

            total += parseFloat($(this).val());
        });

        if (idamount.val() == Math.round(total)) {

            bootbox.confirm("¿Esta seguro de aceptar esta solicitud?", function (result) {
                if (result) {
                    form_acepted_solicitude.attr('action', server + 'aceptar-solicitud');
                    form_acepted_solicitude.submit();
                }
            });


        } else if (idamount.val() < Math.round(total)) {
            amount_error_families.text('El monto distribuido supera el monto total').css('color', 'red');

        } else {
            amount_error_families.text('El monto distribuido es menor al monto total').css('color', 'red');

        }


    });

    $('#deny_solicitude').on('click', function (e) {

        var url = server + 'rechazar-solicitud';
        form_acepted_solicitude.attr('action', url);
        form_acepted_solicitude.submit();

    });


    var search_solicitude_sup = $('#search_solicitude_sup');
    search_solicitude_sup.on('click', function () {
        var l = Ladda.create(this);
        var idstate = $('#select_state_solicitude_sup').val();
        l.start();
        var jqxhr = $.post(server + "buscar-solicitudes-sup",
            { idstate: idstate, date_start: $('#date_start').val(), date_end: $('#date_end').val() })
            .done(function (data) {
                $('#table_solicitude_sup_wrapper').remove();
                $('.table-solicituds-sup').append(data);
                $('#table_solicitude_sup').dataTable({
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

   $('.btn-file :file').on('fileselect', function (event, numFiles, label) {

        $('#input-file-factura').val(label);
    });

    $(document).on('change', '.btn-file :file', function () {
        var input = $(this),
            numFiles = input.get(0).files ? input.get(0).files.length : 1,
            label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
        input.trigger('fileselect', [numFiles, label]);
    });

    /** GERENTE COMERCIAL */

    $.ajax({
        url: server + 'listar-solicitudes-gercom/' + 8,
        type: 'GET',
        dataType: 'html'


    }).done(function (data) {
        $('.table-solicituds-gercom').append(data);
        $('#table_solicitude_gercom').dataTable({
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

    $('.select_state_solicitude_gercom').on('change', function () {
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
        }, 200)
    })


    /* previsualizar imagen */
    $('#input-file-factura').change(function(e) {

        addImage(e);
    });

    function addImage(e){
        var file = e.target.files[0],
            imageType = /image.*/;

        if (!file.type.match(imageType))
            return;

        var reader = new FileReader();
        reader.onload = fileOnload;
        reader.readAsDataURL(file);
    }

    function fileOnload(e) {
        var result=e.target.result;
        $('#imgSalida').attr("src",result);
    }

}
