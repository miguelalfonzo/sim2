/* Declare Variables */
//MENU
var date_start = $('.date_start').first();
var date_end = $('.date_end').first();

//NEW SOLICITUD
var reason        = $('select[name=motivo]');
var investment    = $('select[name=inversion]');
var activity      = $('select[name=actividad]');
var title         = $('input[name=titulo]');
var currency      = $('select[name=moneda]');
var amount        = $('input[name=monto]');
var payment       = $('select[name=pago]');
var delivery_date = $('input[name=fecha]');
var clients       = $('#clientes');

var invoice_amount    = $('input[name=monto_factura]');
var comprobante_img   = $( '.img-responsive' );
var ruc               = $('input[name=ruc]');
var fondo             = $('select[name="idfondo"]');
var fondoProducto     = $('select[name="fondo_producto[]"]');
var search_cliente    = $('.cliente-seeker');
var idamount          = $('#amount');
var doc_start         = $('.date_start').last();
var doc_end           = $('.date_end').last();
var cancel_solicitude = '.cancel-solicitude';
var id_solicitud      = $( 'input[name=idsolicitud]' );
var families          = $('#listfamily');
var idState           = $("#idState");

//VALIDACION DE MONTOS DE FAMILIAS
var amount_error_families = $('#amount_error_families');


//SUB-ESTADOS
var PENDIENTE     = 1;
var ACEPTADO      = 2;
var APROBADO      = 3;
var DEPOSITADO    = 4;
var REGISTRADO    = 5;
var ENTREGADO     = 6;
var GENERADO      = 7;
var CANCELADO     = 8;
var RECHAZADO     = 9;
var TODOS         = 10;
var DERIVADO      = 11;
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
var GER_PROM  = 'GP';

var date_options2 = 
{
    format: 'mm-yyyy',
    minViewMode: 1,
    language: "es",
    orientation: "top",
    autoclose: true
};

$(document).off( 'click' , '.timeLine' );
$(document).on( 'click' , '.timeLine' , function(e)
{
    e.preventDefault();
    var element = $(this);
    element.removeClass( 'timeLine' );
    var state = parseInt($(this).parent().parent().parent().find('#timeLineStatus').val(), 10);
    var accept = $(this).parent().parent().parent().find('#timeLineStatus').data('accept');
    var rejected = $(this).parent().parent().parent().find('#timeLineStatus').data('rejected');
    
    $.get( server + 'timeline-modal/' + $(this).attr('data-id') ).done( function( response ) 
    {
        element.addClass( 'timeLine' );
        var html = response; 
        var html = $(html);
        html.find('.container-fluid').removeClass('hide');
        var h     = html;
        bootbox.dialog(
        {
            message: h,
            title: "Línea del Tiempo",
            buttons: 
            {
                danger: 
                {
                    label: "Cancelar",
                    className: "btn-default"
                }
            },
            size: "large"
        });
    }).fail( function( statusCode , errorThrown )
    {
        element.addClass( 'timeLine' );
        ajaxError( statusCode , errorThrown );
    });
});

//LEYENDA
$('#show_leyenda').on('click',function(){
    var url = URL_BASE + "getleyenda";
    $.ajax({
        url: url,
        ContentType: GBREPORTS.contentTypeAjax,
        cache: false
    }).done(function(dataResult)
    {
        bootbox.dialog({
            message: dataResult,
            title: "Leyenda de Estados",
            buttons: {
                success: {
                  label: "Regresar",
                  className: "btn-primary",
                }
            }
        });
    });
});

$('#hide_leyenda').on( 'click',function()
{
    $('#leyenda').hide();
    $(this).hide();
    $('#show_leyenda').show();
});

//add a family
$("#btn-add-family").on('click', function () 
{
    $(".btn-delete-family").show();
    $('#listfamily>li:first-child').clone(true, true).appendTo('#listfamily');
});

//delete a family
$( document ).off( 'click' , '.btn-delete-family' );
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
invoice_amount.numeric({negative:false});
ruc.numeric({negative:false,decimal:false});


investment.on( 'change' , function()
{
    inversionChange( $(this).val() );
});


function inversionChange( id_inversion )
{
    $.ajax(
    {
        url: server + 'filtro-inversion',
        type: 'POST' ,
        data:
        {
            _token : GBREPORTS.token,
            id_inversion : id_inversion ,
            tipo_cliente : clients.children().first().attr('tipo_cliente')
        }
    }).fail( function ( statusCode , errorThrown )
    {
        ajaxError( statusCode , errorThrown );
    }).done( function ( response )
    {
        if ( response.Status === 'Ok' )
        {
            filterSelect( activity , response.Data , 'inversion' );
        }
        else
        {
            bootbox.alert( '<h4 class="red">' + response.Status + ': ' + response.Description + '</h4>');
        }
    });
}


//TIPO DE ENTREGA
if( payment.val() != 2 )
{
    ruc.parent().hide();
}

payment.on( 'change' , function()
{
    if ( $( this ).val() == 1 )
    {
        ruc.parent().hide();
    }
    else if( $( this ).val() == 2 )
    {
        ruc.parent().show();
    }
});

title.on('focus', function () {
    $(this).parent().parent().removeClass('has-error');
});
amount.on('focus', function () {
    $(this).parent().parent().removeClass('has-error');
});
delivery_date.on('focus', function () {
    $(this).parent().parent().removeClass('has-error');
});
search_cliente.on('focus', function () 
{
    $(this).parent().parent().parent().removeClass('has-error');
    clients.parent().parent().removeClass('has-error');
});
/*invoice_amount.on('focus', function () {
    $(this).parent().removeClass('has-error')
});*/
$( document ).off( 'click' , '.products' );
$( document ).on( 'click' , '.products' , function () 
{
    $(this).css('border-color', 'none');
    $('.families_repeat').text('').css('color','');
});

date_start.on('focus', function () {
    $(this).parent().removeClass('has-error');
});

date_end.on('focus', function () {
    $(this).parent().removeClass('has-error');
});

ruc.on('focus', function () {
    $(this).parent().removeClass('has-error');
});

$( document ).on( 'focus' , 'select[name=inversion]' , function()
{
    $(this).parent().parent().removeClass( 'has-error');
});

$( document ).on( 'focus' , 'select[name=actividad]' , function()
{
    $(this).parent().parent().removeClass( 'has-error');
});

function listMaintenanceTable( type )
{
    $.ajax(
    {
        url  : server + 'get-table-maintenance-info',
        type : 'POST' ,
        data :
        {
            _token : GBREPORTS.token,
            type   : type
        }
    }).fail( function ( statusCode , errorThrown )
    {
        ajaxError( statusCode , errorThrown );
    }).done(function ( response ) 
    {
        if ( response.Status == 'Ok' )
            dataTable( type , response.Data , 'registros' );
        else
            bootbox.alert('<h4 class="red">' + response.Status + ': ' + response.Description + '</h4>');
    });
}

function listDocuments()
{
    var l = Ladda.create( $( "#search-documents" )[ 0 ] );
    
    $.ajax({
        url: server + 'list-documents',
        type: 'POST',
        data:
        {
            idProof      : $('#idProof').val() ,
            date_start: $('#drp_menubar').data('daterangepicker').startDate.format("L"),
            date_end  : $('#drp_menubar').data('daterangepicker').endDate.format("L"),
            val          : $('#doc-search-key').val() ,
            _token       : GBREPORTS.token,
        }
    }).fail( function ( statusCode , errorThrown)
    {
        ajaxError( statusCode , errorThrown );
    }).done(function (data)
    {
        if ( data.Status == 'Ok' )
            dataTable( 'documents' , data.Data, 'registros' );
        else
            bootbox.alert( '<h4 class=red>' + data.Status + ': ' + data.Description + '</h4>');
    });
}

function listDocumentsType()
{
    window.location.reload(true);
    //window.location.href = server + 'maintenance/documenttype' ;
    /*$.ajax(
    {
        url: server + 'list-documents-type',
        type: 'GET',
        dataType: 'html'
    }).fail( function ( statusCode , errorThrown)
    {
        ajaxError( statusCode , errorThrown );
    }).done(function (data) 
    {
        dataTable( 'table_document_contabilidad' , data , 'documentos' );
    });*/
}

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
            $('#table_reporte_institucional_wrapper').remove();
            $('.table-solicituds-fondos').append(data.Data.View);
            $('#export-fondo').attr('href', server + 'exportfondos/' + datefondo);
            $('#table_reporte_institucional').DataTable(
            {
                order: 
                [
                    [3, "desc"]
                ],
                bLengthChange: false,
                iDisplayLength: 7,
                oLanguage: 
                {
                    sSearch: "Buscar: ",
                    sZeroRecords: "No hay fondos",
                    sInfoEmpty: " ",
                    sInfo: 'Mostrando _END_ de _TOTAL_',
                    oPaginate: 
                    {
                        sPrevious: "Anterior",
                        sNext: "Siguiente"
                    }
                }
            });
        }
        else
            bootbox.alert('<h4 class="red">' + data.Status + ': ' + data.Description + '</h4>');
    });
}

var calcDataTableHeight = function() 
{
    return $(window).height()*50/100;
};

function listTable( type , date )
{
    date = typeof date !== 'undefined' ? date : null;
    var l = Ladda.create($("#search-solicitude")[0]);
    $.ajax({
        url: server + 'list-table',
        type: 'POST',
        data:
        {
            _token     : GBREPORTS.token,
            type       : type ,
            date       : date ,
            idstate    : $('#idState').val() ,
            date_start : $('#drp_menubar').data('daterangepicker').startDate.format("L"),
            date_end   : $('#drp_menubar').data('daterangepicker').endDate.format("L") ,
            filter     : $( '.filter' ).val()
        }
    }).fail( function ( statusCode , errorThrown)
    {
        ajaxError( statusCode , errorThrown );
    }).done(function ( response ) 
    {
        if ( response.Status == 'Ok' )
        {
            dataTable( type , response.Data.View , type );
            if ( response.Data.Total !== undefined )
            {
                $( '.estado-cuenta-deposito' ).first().val( response.Data.Total.Soles);
                $( '.estado-cuenta-deposito' ).last().val( response.Data.Total.Dolares);
            }
            $('#export-fondo').attr('href', server + 'exportfondos/' + date);
        }
        else
            bootbox.alert( '<h4 class="red">' + response.Status + ': ' + response.Description + '</h4>');
    });
}

function dataTable( element , html , message )
{
    $( '#table_' + element + '_wrapper' ).remove();

    if( html != null )
        $( '#' + element ).append( html );

    $( '#table_' + element ).DataTable(
    {
        dom             : "<'row'<'col-xs-6'><'col-xs-6 pull-right'f>r>t<'row'<'col-xs-6'i><'col-xs-6'p>>",
        stateSave       : true,
        autoWidth       : true,
        scrollY         : calcDataTableHeight(),
        sScrollX        : "100%",
        bScrollCollapse : true,
        iDisplayLength  : 10 ,
        language        :
        {
            search       : 'Buscar',
            zeroRecords  : 'No hay ' + message ,
            infoEmpty    : 'No ha encontrado ' + message +'disponible',
            info         : 'Mostrando _END_ de _TOTAL_ ' + message ,
            lengthMenu   : "Mostrando _MENU_ registros por página",
            infoEmpty    : "No ha encontrado informacion disponible",
            infoFiltered : "(filtrado de _MAX_ regitros en total)",
            paginate     : 
            {
                sPrevious : 'Anterior',
                sNext     : 'Siguiente'
            }
        },
    });
}

// -------------------------------------  REPRESENTANTE MEDICO -----------------------------

//Register Deposit
$(document).off( "click" , ".register-deposit" );
$(document).on( "click" , ".register-deposit" , function( e )
{
    e.preventDefault();
    var url = 'deposit-solicitude';
    var data = {};
    data._token         = _token;
    data.token          = $("input[name=token]").val();
    data.op_number      = $("#op-number").val();
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
                    if ( $('.table_solicitudes').length == 0 )
                        window.location.href = server +"show_user";
                    else
                        listTable( 'solicitudes' );
                });
            }
            else
                bootbox.alert("<h4 class='red'>" + data.Status + ': ' + data.Description + "</h4>") ;    
        });
    }
});

$(document).on('click' , '.delete-fondo' , function (e)
{
    e.preventDefault();
    var data = 
    {
        idsolicitud: $( this ).parent().parent().parent().children().first().text().trim() ,
        _token: _token
    };
    cancelDialog( data , '¿Esta seguro de anular el registro del fondo?' );
});

/**------------------------------------------------ SUPERVISOR ---------------------------------------------------*/

$('.amount_families').numeric({negative: false});

idamount.keyup( function ()
{
    verifySum( this , 2 );
});

$( document).off( 'keyup' , '.amount_families' );
$( document).on( 'keyup' , '.amount_families' , function ()
{
    verifySum( this , 1 );
});

$( document).off( 'keyup' , '.amount_families2' );
$( document).on( 'keyup' , '.amount_families2' , function ()
{
    verifySum( this , 1 );
});

function verifySum( element , type )
{
    amount_error_families.text('');
    var sum_total = 0;
    var precision = 11;

    if($("#is-product-change").is(':checked'))
         $('.amount_families2').each(function(i,v)
        {
            sum_total += parseFloat( $(this).val() );
        });
    else
        $('.amount_families').each(function(i,v)
        {
            sum_total += parseFloat( $(this).val() );
        });
    
    if( $("#amount").val().trim() === "" )
    {
        amount_error_families.text('Ingresar el monto (Vacío)').css('color', 'red');
        idamount.parent().parent().removeClass("has-success").addClass("has-error");
    }     
    else if ( parseFloat( $("#amount").val() ) === 0 )
    {
        amount_error_families.text('El monto especificado no debe ser igual a 0').css('color', 'red');
        idamount.parent().parent().removeClass("has-success").addClass("has-error");
    }
    else if ( parseFloat( $("#amount").val() ) < 0 )
    {
        amount_error_families.text('El monto especificado no debe ser menor a 0').css('color', 'red');
        idamount.parent().parent().removeClass("has-success").addClass("has-error");
    }
    else if ( type == 1 && $( element ).val().trim() === "" ) 
    {
        amount_error_families.text('Ingresar el monto de la familia').css('color', 'red');
        idamount.parent().parent().removeClass("has-success").addClass("has-error");
    }
    else if ( type == 1 && parseFloat( $( element ).val() ) < 0 ) 
    {
        amount_error_families.text('El monto de la familia no debe ser menora 0').css('color', 'red');
        idamount.parent().parent().removeClass("has-success").addClass("has-error");
    }
    else if ( type == 1 && ( parseFloat( $( element ).val() ) > parseFloat( idamount.val() ) ) )
    {
        amount_error_families.text('El monto de la familia supera al monto especificado').css('color', 'red');
        idamount.parent().parent().removeClass("has-success").addClass("has-error");
    }
    else if ( parseFloat( sum_total ) > parseFloat( idamount.val() ) )
    {
        amount_error_families.text('El monto total de las familias supera al monto especificado').css('color', 'red');
        idamount.parent().parent().removeClass("has-success").addClass("has-error");
    }
    else if ( parseFloat( sum_total ) < parseFloat( idamount.val() ) )
    {
        amount_error_families.text('El monto total de las familias es menor al monto especificado').css('color', 'red');
        idamount.parent().parent().removeClass("has-success").addClass("has-error");
    }
    else if ( parseFloat( sum_total ) == parseFloat( idamount.val() ) )
    {
        amount_error_families.text('Los montos asignados son iguales al monto especificado').css('color', 'green');    
        idamount.parent().parent().removeClass("has-error").addClass("has-success");
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

/** ---------------------------------------------- GERENTE PRODUCTO -------------------------------------------- **/

if ( $('#deny_solicitude').length != 0 )
{
    verifySum( idamount[0] , 2 );
}

/* Cancel Solicitude */
$(document).off( 'click' , cancel_solicitude );
$(document).on( 'click' , cancel_solicitude, function () 
{
    elem = $( this );
    var data = 
    {
        idsolicitud: elem.attr('data-idsolicitude') ,
        _token : elem.attr('data-token')
    };
    cancelDialog( data , '¿Esta seguro que desea cancelar esta solicitud?' );
});

$('#deny_solicitude').on('click', function (e)
{
    var data =
    {
        idsolicitud : id_solicitud.val() ,
        _token      : _token   
    }
    cancelDialog( data , '¿Esta seguro que desea rechazar esta solicitud?' );
});

function cancelDialog  ( data , message )
{
    bootbox.dialog(
    {
        title : '<h4>' + message + '</h4>',
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
                                            idState.val(6);
                                            listTable( 'solicitudes' );
                                        }
                                        else if ( data.Type == 2 )
                                            searchFondos( $('.date_month').first().val() );
                                        else if ( data.Type == 3 )
                                            window.location.href = server + 'show_user';
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



/** --------------------------------------------- ASISTENCIA DE GERENCIA ------------------------------------------------- **/

var fondo_repmed = $('#fondo_repmed');
var fondo_total = $('#fondo_total');
var fondo_institucion = $('#fondo_institucion');
var date_reg_fondo = $( '.date_month[data-type=fondos]' );

fondo_repmed.on('focus', function () {
    $(this).parent().parent().parent().removeClass('has-error');
});
fondo_total.on('focus', function () {
    $(this).parent().parent().removeClass('has-error');
});
date_reg_fondo.on('focus', function () {
    $(this).parent().parent().removeClass('has-error');
});
fondo_institucion.on('focus', function() {
    $(this).parent().parent().parent().removeClass('has-error');     
});
fondo.on( 'focus' , function() {
    $( this ).parent().parent().removeClass( 'has-error' );
});
$( '#fondo-inversion').on( 'focus' , function()
{
    $( this ).parent().removeClass( 'has-error' );
});
fondo_total.numeric();

function validateFondoInstitucional()
{
    var fondo_inversion = $( '#fondo-inversion' );
    var validate = 0;

    if( fondo.val() == 0  || fondo.val() === null )
    {
        fondo.parent().parent().addClass( 'has-error' );
        validate = 1;
    }
    if ( fondo_inversion.val() === null )
    {
        $( '#fondo-inversion' ).parent().removeClass( 'has-success' ).addClass( 'has-error');
        validate = 1;
    }
    if( ! date_reg_fondo.val() )
    {
        date_reg_fondo.parent().parent().addClass('has-error');
        date_reg_fondo.attr('placeholder', 'Ingrese Mes');
        date_reg_fondo.addClass('input-placeholder-error');
        validate = 1;
    }
    if( ! fondo_total.val() )
    {
        fondo_total.parent().parent().addClass('has-error');
        fondo_total.attr('placeholder', 'Ingrese Cantidad a depositar');
        fondo_total.addClass('input-placeholder-error');
        validate = 1;
    }
    if( ! fondo_repmed.val() )
    {
        fondo_repmed.parent().parent().parent().addClass('has-error');
        fondo_repmed.attr('placeholder', 'Ingrese Representante');
        fondo_repmed.addClass('input-placeholder-error');
        validate = 1;
    }
    if( fondo_repmed.attr('data-select') == 'false' )
    {
        fondo_repmed.val('');
        fondo_repmed.parent().parent().parent().addClass('has-error');
        fondo_repmed.attr('placeholder', 'Ingrese Representante');
        fondo_repmed.addClass('input-placeholder-error');
        validate = 1;
    }
    if ( typeof fondo_institucion.attr( 'data-cod' ) === 'undefined' || fondo_institucion.attr( 'data-cod' ).trim() === '' )
    {
        fondo_institucion.parent().parent().parent().addClass( 'has-error' );
        fondo_institucion.attr( 'placeholder' , 'Ingrese Institución' );
        validate = 1;
    }
    return validate;
}

function fondoData()
{
    var fondo_inversion = $( '#fondo-inversion' );
    var data = 
    { 
        _token            : GBREPORTS.token,
        mes               : $( '.date_month' ).val(),
        fondo_producto    : $('select[name="fondo_producto[]"]').val(),
        inversion         : fondo_inversion.val(),
        'institucion-cod' : fondo_institucion.attr( 'data-cod' ),
        codrepmed         : fondo_repmed.attr( 'data-cod' ),
        total             : fondo_total.val(),
        idsolicitud       : id_solicitud.val()
    };         
    return data;
}

$( '.btn_edit_fondo' ).on( 'click' , function()
{
    registerFondoInstitucional();
});

$( '.register_fondo' ).on( 'click' , function()
{
    registerFondoInstitucional();
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
        '_token'      : _token
    };
    $.post( server + 'get-sol-inst' , data )
    .fail( function ( statusCode , errorThrown ) 
    {
        ajaxError( statusCode , errorThrown );
    }).done( function( response )
    {
        if ( response.Status == 'Ok' )
        {
            data = response.Data ;
            tr.css('background-color','#59A1F4');
            $('.btn_cancel_fondo').show();
            $('.btn_edit_fondo').show();
            $('.register_fondo').hide();
            $('#edit-rep').show();
            $( '#edit-institucion').show();
            fondo_repmed.attr('disabled',true).attr('data-select', true ).parent().parent().parent().addClass('has-success');
            fondo_repmed.val(data.rm);
            fondo_repmed.attr('data-cod',data.idrm);
            fondo_total.val(data.monto);
            fondo_institucion.val( data.institucion ).attr( 'data-cod' , data[ 'institucion-cod' ] ).attr( 'disabled' , true ).attr( 'data-select' , true ).parent().parent().parent().addClass( 'has-success' );
            date_reg_fondo.val( data.periodo.substr(4,6) + '-' + data.periodo.substr(0,4) );
            id_solicitud.val(idsolicitud);
            $('select[name="fondo_producto[]"]').val( data.idfondo );
            $('#fondo-inversion').val( data.idinversion );
        }
        else
            bootbox.alert('<h4 class=""red>' + data.Status + ': ' + data.Description + '</h4>');
    });
});


function registerFondoInstitucional()
{
    var aux = this;
    var validate = validateFondoInstitucional();

    if(validate == 0)
    {
        var periodo = $(".date_month").val();
        var dato = fondoData(); 
        date_reg_fondo.last().val(date_reg_fondo.val());
        $.post( server + 'registrar-fondo', dato )
        .fail( function ( statusCode , errorThrown )
        {
            ajaxError( statusCode , errorThrown );
        }).done(function( response )
        {
            if ( response.Status == 'Ok' )
            {
                cleanFondoInstitucion();
                bootbox.alert('<h4 class="green">Fondo Registrado</h4>' , function()
                {
                    searchFondos( periodo );
                });
            }
            else
                bootbox.alert("<h4 style='color:red'>No se pudo registrar el fondo: " + response.Description + "</h4>");
        });
    }
}

$( '#search_responsable' ).on( 'click' , function(e)
{
    var div_monto = $( 'label[for=monto]' ).parent();
    if ( div_monto.hasClass( 'has-success' ) )
        acceptedSolicitude();
    else
        return false;
});

$( '#derivar_solicitud' ).on( 'click' , function()
{
    acceptedSolicitude( 'derivacion' );   
});


function acceptedSolicitude( type )
{
    var formData = new FormData( form_acepted_solicitude[ 0 ] );
    console.log( type );
    if ( type !== undefined )
    {
        formData.append( 'derivacion' , 1 );
    }
    else
    {
        formData.append( 'derivacion' , 0 );
    }

    if ( $("#is-product-change").is(':checked'))
    {
        formData.append( 'modificacion_productos' , 1 );
    }
    else
    {
        formData.append( 'modificacion_productos' , 0 );
    }

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
        if (data.Status == 'Error' )
        {
            responseUI('Hubo un error al procesar la solicitud','red');
        }
        else if (data.Status == 'Warning')
        {
            bootbox.alert("<h4 class='red'>" + data.Status + ": " + data.Description + "</h4>");
        }
        else if (data.Status == 'Ok')
        {
            responseUI( 'Solicitud Procesada Correctamente' , 'green' );
            setTimeout(function()
            {
                window.location.href = server + 'show_user';
            },800);
        }
    });
}

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
                    id_solicitud.val('');
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

function cleanFondoInstitucion()
{
    fondo_total.val('');
    fondoProducto.val( 0 );
    $( '#fondo-inversion').val( 0 );            
    $('#fondo_institucion').typeahead( 'val' , '' ).attr( 'disabled' , false ).attr( 'data-cod' , '' ).parent().parent().parent().removeClass('has-success');
    $( '#edit-institucion' ).fadeOut();
    removeinput( $( '#edit-rep' ) );
    $( '.btn_cancel_fondo' ).hide();
    $( '.btn_edit_fondo' ).hide();
    $( '.register_fondo').show();

}

function removeinput(data)
{
    var rep = data.parent().find('input:text');
    rep.typeahead( 'val' , '' );
    rep.attr('disabled', false).attr('data-select',"false").attr('data-cod','').attr('data-cod-sup' ,'' ).attr('data-cuenta','').focus().parent().parent().parent().removeClass('has-success');
    data.fadeOut();
}

$( document ).off( 'click' , '#edit-rep' );
$(document).on("click","#edit-rep", function(e)
{
    removeinput($(this))
});

$(document).on('click','.btn_cancel_fondo',function(e){
    cleanFondoInstitucion();
    $('#table_reporte_institucional > tbody > tr').css('background-color','');
});

function fileOnload( event ) 
{
    $('.img-responsive').attr("src", event.target.result );
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

$(document).off( 'click' , '.maintenance-add' );
$(document).on( 'click' , '.maintenance-add' , function()
{
    var button = $(this);
    $.ajax(
    {
        type: 'post' ,
        url :  server + 'add-maintenance-info' ,
        data:
        {
            _token : GBREPORTS.token,
            type   : button.attr("case")
        }
    }).fail( function( statusCode , errorThrown )
    {
        ajaxError( statusCode , errorThrown );
    }).done( function( response )
    {
        if ( response.Status == 'Ok')
        {
            button.parent().parent().find('tbody').append( response.Data );
            var table = '#table_' + button.attr( 'case' );
            var scroll = $( table ).parent();
            scroll.scrollTop( scroll[0].scrollHeight );
            button.hide();
        }
        else
            bootbox.alert('<h4 class="red">' + Data.Status + ': ' + Data.Description + '</h4>');            
    });
});

$(document).off("click", "#add-doc");
$(document).on("click", "#add-doc", function()
{
    var style = 'style="text-align: center"';
    $(this).hide();
    $("#table_document_contabilidad tbody").append('<tr class="new">'
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
    $('tr.new td#sunat input').numeric({negative:false,decimal:false});
});

$(document).off( 'click' , '.maintenance-cancel');
$(document).on( 'click' , '.maintenance-cancel' , function()
{
    var tr = $(this).parent().parent();
    $( 'input[case=' + tr.attr( 'type' ) + ']' ).show();
    listMaintenanceTable( tr.attr('type')  );
});

$(document).off( 'click' , '.maintenance-edit' );
$(document).on( 'click' , '.maintenance-edit' , function()
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
            td.html('<input type="text" style="width:100%" value="' + val.trim() + '">');
            td.children().numeric();
        }
        else if ( td.attr('editable') == 4 )
        {
            var val = td.html();
            td.html('<input type="text" style="width:100%" value="' + val.trim() + '">');
        }
    });
});

$(document).off( 'click' , '.maintenance-save');
$(document).on( 'click' , '.maintenance-save' , function()
{
    var trElement = $(this).parent().parent();
    var aData = {};
    aData._token = _token;
    aData.type   = trElement.attr('type');
    aData.Data   = {};
    trElement.children().each( function( i , data )
    {
        var td = $(data);
        if ( td.attr('save') == 1 )
            aData.Data[td[0].classList[0]] = td.children().val() ;
        else if ( td.attr('save') == 2 )
            aData[td[0].classList[0]] = td.children().val() ;
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
            bootbox.alert('<h4 class="text-success">Tabla Actualizada</h4>');
            // listMaintenanceTable( aData.type );
            window.location.reload(true);
        }
        else
            bootbox.alert('<h4 class="text-danger">' + response.Status + ': ' + response.Description + '</h4>');            
    });
});

$(document).off('click' , '.maintenance-update');
$(document).on('click' , '.maintenance-update' , function()
{
    var trElement = $(this).parent().parent();
    var aData = {};
    aData._token = _token;
    aData.id     = trElement.attr('row-id');
    aData.type   = trElement.attr('type');
    aData.Data   = {};
    trElement.children().each( function( i , data )
    {
        var td = $(data);
        if ( td.attr('editable') == 1  || td.attr('editable') == 3 || td.attr('editable') == 4 )
            aData.Data[ td.attr( 'data-key') ] = td.children().val()
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
            bootbox.alert('<h4 class="text-success">Relaciones Actualizadas</h4>');
            // listMaintenanceTable( aData.type );
            window.location.reload(true);
        }
        else
            bootbox.alert('<h4 class="text-danger">' + response.Status + ': ' + response.Description + '</h4>');            
    });
});

$(document).off( 'click' , '.maintenance-disable' );
$(document).on( 'click' , '.maintenance-disable' , function()
{
    var trElement = $(this).parent().parent();
    var aData = {};
    aData._token = _token;
    aData.id     = trElement.attr('row-id');
    aData.type   = trElement.attr('type');
    $.ajax(
    {
        type: 'post' ,
        url :  server + 'maintenance-disable' ,
        data: aData
    }).fail( function( statusCode , errorThrown )
    {
        ajaxError( statusCode , errorThrown );
    }).done( function( response )
    {
        if ( response.Status == 'Ok' )
        {
            bootbox.alert('<h4 class="green">Registro deshabilitado</h4>');
            //listMaintenanceTable( aData.type );
            window.location.reload(true); 
        }
        else
            bootbox.alert('<h4 class="red">' + response.Status + ': ' + response.Description + '</h4>');            
    });
});

$(document).off('click' , '.maintenance-enable');
$(document).on('click' , '.maintenance-enable' , function()
{
    var trElement = $(this).parent().parent();
    var aData = {};
    aData._token = _token;
    aData.id     = trElement.attr('row-id');
    aData.type   = trElement.attr('type');
    $.ajax(
    {
        type: 'post' ,
        url :  server + 'maintenance-enable' ,
        data: aData
    }).fail( function( statusCode , errorThrown )
    {
        ajaxError( statusCode , errorThrown );
    }).done( function( response )
    {
        if ( response.Status == 'Ok' )
        {
            bootbox.alert('<h4 class="green">Registro habilitado</h4>');
            //listMaintenanceTable( aData.type );
            window.location.reload(true);
        }
        else
            bootbox.alert('<h4 class="red">' + response.Status + ': ' + response.Description + '</h4>');            
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
            type   : td[ 0 ].classList[ 0 ] ,
            val    : td.html().trim(),
            _token : _token
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
    data_json._token = _token;
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
$('#edit-institucion').on('click' , function()
{
    $(this).fadeOut();
    $( '#fondo_institucion' ).typeahead( 'val' , '' ).attr( 'disabled' , false ).attr( 'data-cod' , '' ).parent().parent().parent().removeClass( 'has-success' );

});

$(document).off("click", ".elementBack");
$(document).on("click", ".elementBack", function()
{
    $("#table_document_contabilidad tbody tr").last().remove();
    $("#add-doc").show();
});

function seeker( element , name , url )
{
    if (element.length != 0)
    {
        element.typeahead(
        {
            minLength: 3,
            hightligth: true,
            hint: true
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
                        _token : GBREPORTS.token,
                        sVal   : request
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
                    return response( data.Data );
                });              
            }
        }).on('typeahead:selected', function ( evento, suggestion , dataset )
        {
            var input = $(this);
            if ( dataset === 'users' )
            {
                $( this ).attr( 'readonly' , '' ).attr( 'data-cod' , suggestion.value ).parent().parent().addClass( 'has-success' );
            }
            else if ( dataset == 'institutions')
            {
                $( this ).attr( 'disabled' , true ).attr( 'data-cod' , suggestion.value ).parent().parent().parent().addClass( 'has-success' );
                $( this ).parent().parent().find('.edit-repr').fadeIn();
            }
            else if ( dataset == 'clients' )
            {
                $.ajax(
                {
                    type: 'post',
                    url: server + 'get-client-view' ,
                    data:
                    {
                        _token : GBREPORTS.token,
                        data   : suggestion
                    },
                }).fail( function( statusCode , errorThrown )
                {
                    ajaxError( statusCode , errorThrown );
                }).done( function ( data )
                {
                    if ( data.Status != 'Ok')
                    {
                        responseUI( 'Problema de Conexion' , 'red' );
                    }
                    else if ( data.Status == 'Ok' )
                    {
                        if ( clients.children().length >= 1 )
                        {
                            var aux = 0;
                            clients.children().each( function()
                            {
                                var li = $(this);
                                if ( li.attr('pk') == suggestion.value && li.attr('tipo_cliente') == suggestion.id_tipo_cliente )
                                    aux = 1;
                            });
                            if ( aux == 0 )
                            {
                                clients.append( data.Data.View );
                            }
                        }
                        else
                        {
                            clients.append( data.Data.View );
                        }
                        filterSelect( investment , data.Data.id_inversion , 'cliente' );
                        filterSelect( activity , data.Data.id_actividad , 'cliente' );
                    }
                });
                input.typeahead( 'val' , '' );
            }
            else if ( dataset == 'reps' )
            {
                var element = $(this);
                element.attr( 'data-select' , 'true' );
                element.attr( 'data-cod' , suggestion.value );
                element.val( suggestion.label );
                element.attr( 'disabled' , true ).parent().parent().parent().addClass('has-success');
                $( this ).parent().parent().find('.edit-repr').fadeIn();
            }
        });
    }
}

function filterSelect( element , ids , type )
{
    var select = $(element);
    if ( type === 'cliente' && clients.children().length === 1 ) 
    {
        select.children().filter(function( index ) 
        {
            var option = $( this );
            return $.inArray( option.val() ,  ids ) == -1 && option.val() !== '0'  ;
        }).remove();
    }
    else if ( type === 'inversion' || type === 'eliminacion' )
    {
        select.empty();
        if( ids.Status === 'Ok' && ids.Data.length > 0 )
        {
            select.append( '<option value=0 disabled selected>' + ids.Description + '</option>')
            $( ids.Data ).each(function( i , data )
            {
                select.append('<option value="'+ data.id +'">'+ data.nombre +'</option>');
            });
        }
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
            _token : GBREPORTS.token,
            rm     : rm
        },
        error: function(statusCode,errorThrown)
        {
            ajaxError(statusCode,errorThrown);
        }
    });  
}

function clientFilter( tipo_cliente , tipo_filtro )
{
    tipo_filtro = typeof tipo_filtro !== 'undefined' ? tipo_filtro : 'cliente';
    $.ajax(
    {
        type: 'post' ,
        url: server + 'filtro_cliente' ,
        data:
        {
            _token       : GBREPORTS.token,
            tipo_cliente : tipo_cliente
        }
    }).fail( function ( statusCode , errorThrown )
    {
        ajaxError( statusCode , errorThrown );
    }).done( function ( response )
    {
        if ( response.Status === 'Ok' )
        {
            filterSelect( activity , response.Data.Activities , tipo_filtro );
            filterSelect( investment , response.Data.Investments , tipo_filtro );
        }
        else
        {
            bootbox.alert( '<h4 class="red">' + response.Status + ': ' + response.Description + '</h4>');
        }
    });  
}

function fillInvestmentsActivities()
{
    $.get( server + 'get-investments-activities' ).done( function( response ) 
    {
        investment.empty();
        activity.empty();
        investment.append( response.Data.Investments );
        activity.append( response.Data.Activities ); 
    }).fail( function( statusCode , errorThrown )
    {
        ajaxError( statusCode , errorThrown );
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
        if ( table !== old_li2.attr( 'tipo_cliente' ) )
        {
            clientFilter( old_li2.attr( 'tipo_cliente' ) , 'eliminacion' );    
        }
    }
    else
    {
        li.remove();
    }
    if ( ul.children().length === 0 )
    {
        fillInvestmentsActivities();
    }
});


function ajaxError(statusCode,errorThrown)
{
    if ( statusCode.status == 0 )
    {
        bootbox.alert('<h4 class="yellow">Internet: Problemas de Conexion</h4>');    
    }
    else
    {
        console.log( statusCode );
        console.log( errorThrown );
        bootbox.alert('<h4 class="red">Error del Sistema</h4>');  
    }
}

$(".date_month").datepicker(date_options2).on('changeDate', function (e) {
    var date = $(this).val();
    var type = $(this).attr('data-type');

    if( date != '' && type != 'estado-cuenta' )
    {
        $( '.date_month' ).val( date );
        if ( ! id_solicitud.val() )
            searchFondos(date);
    }
});

/* Filter all solicitude by date */
$('#search-solicitude').on('click', function()
{ 
    listTable( 'solicitudes' );
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
        title.parent().parent().addClass('has-error');
        title.attr('placeholder', 'Ingrese nombre de la solicitud');
        aux = 1;
    }
    if (!amount.val()) 
    {
        amount.parent().parent().addClass('has-error');
        amount.attr('placeholder', 'Ingrese monto');
        aux = 1;
    }
    if (!delivery_date.val()) 
    {
        delivery_date.parent().parent().addClass('has-error');
        delivery_date.attr('placeholder', 'Ingrese Fecha');
        aux = 1;
    }
    if ( clients.children().length == 0 )
    {
        search_cliente.attr( 'placeholder' , 'Ingrese el Cliente' );
        search_cliente.parent().parent().parent().addClass('has-error');
        clients.parent().parent().addClass('has-error');
        aux = 1;
    }
    if ( payment.val() == 2 )
    {
        if ( ! ruc.val() )
        {
            ruc.parent().addClass('has-error');
            ruc.attr( 'placeholder' , 'Ingrese RUC' ).addClass( 'input-placeholder-error' );
            aux = 1;
        }
    }
    return aux;
}

//Validate send register solicitude
$( '#registrar' ).off( 'click' );
$( '#registrar' ).on( 'click' , function ( e ) 
{
    e.preventDefault();
    var aux = 0;
    var d_clients = [];
    var d_clients_type = [];
    var families_input = [];
    aux = validateNewSol();
    
    //Validate fields client are correct
    clients.children().each( function ()
    {
        elem = $(this);
        d_clients.push( elem.attr("pk") );
        d_clients_type.push( elem.attr("tipo_cliente") );
    });
    var products      = $('.products');
    products.each( function (index) 
    {
        families_input[index] = $(this).val();
    });

    for ( var i = 0 ; i < families_input.length ; i++ ) 
    {
        products.each( function ( index ) 
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
        var formData = new FormData( form[ 0 ] );
        d_clients.forEach( function( entry )
        {
            formData.append( "clientes[]", entry );
        });   
        d_clients_type.forEach( function( entry )
        {
            formData.append( "tipos_cliente[]" , entry );
        });
        var rute = form.attr('action');
        var message1 = 'Registrando';
        var message2 = '<strong style="color: green">Solicitud Registrada</strong>';
        if ( id_solicitud ) 
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
            if(data.Status == 'Ok' )
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

$("#btn-mass-approve").click( function()
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
                var trs = $('#table_solicitudes tbody tr');
                data._token = _token;
                data.solicitudes = [];
                trs.each(function( index)
                {
                    var sol = {};
                    if ( $(this).find('input[name=mass-aprov]:checked').length != 0 )
                    {
                        sol.token = $(this).find("#sol_token").val();
                        data.solicitudes.push(sol);
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
                        var color = 'green';
                    else if(data.Status == 'Warning')
                        var color = 'gold';
                    else if(data.Status == 'Danger')
                        var color = 'red';
                    else
                        var color = '';
                    listTable( 'solicitudes' );
                    bootbox.alert("<h4 style='color:" + color + "'>" + data.Description + "</h4>", function()
                    {
                        colorTr(data.token);
                    });
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
    var tr;
    $( document ).ready(function() 
    {
        for (var index in tokens.Error )
        {
            tr = $(".i-tokens[value=" + tokens.Error[index] + "]").parent();
            tr.addClass('danger');
        }
        for (var index in tokens.Ok )
        {
            tr = $(".i-tokens[value=" + tokens.Ok[index] + "]").parent();
            tr.addClass('success');
        }
    });
    
}

function addTr( data , tr )
{
    var tr = $( tr );
    tr.a
}

$( document ).off( 'click' , '.open-details' );
$( document ).on( 'click' , '.open-details' , function()
{
    var td = $( this );
    td.removeClass('open-details');
    var tr = td.parent();
    var colspan = tr.children().length;
    var span = td.find( 'span' );
    $.ajax(
    {
        url: server + 'detail-solicitud',
        type: 'POST',
        data: 
        {
            _token       : GBREPORTS.token,
            id_solicitud : td.attr( 'data-id' ),
            colspan      : colspan
        }
    }).fail( function( statusCode , errorThrown )
    {
        td.addClass('open-details');
        ajaxError( statusCode , errorThrown );
    }).done( function( response )
    {
        td.addClass('open-details');
        if ( response.Status == 'Ok' )
        {
            bootbox.dialog({
                message: response.Data.View,
                animate: true ,
                className : 'solicitud-detail-modal',
                backdrop  : true,
                onEscape  : true });
            return true;
            //addTr( td , response.Data.View );
        }
        else
            bootbox.alert( '<h4 class="red">' + response.Status + ' : ' + response.Description + '</h4>');
    });
});

$( document ).on( 'click' , '.open-details2' , function()
{
    var td = $( this );
    var tr = td.parent();
    var colspan = tr.children().length;
    var span = td.find( 'span' );
    $.ajax(
    {
        url: server + 'detail-solicitud',
        type: 'POST',
        data: 
        {
            _token       : GBREPORTS.token,
            id_solicitud : td.attr( 'rel' ),
            colspan      : colspan
        }
    }).fail( function( statusCode , errorThrown )
    {
        ajaxError( statusCode , errorThrown );
    }).done( function( response )
    {
        if ( response.Status == 'Ok' )
        {
            bootbox.dialog({
                message: response.Data.View,
                animate: true ,
                className : 'solicitud-detail-modal',
                backdrop  : true,
                onEscape  : true
            });

            return true;
        }
        else
            bootbox.alert( '<h4 class="red">' + response.Status + ': ' + response.Description + '</h4>')
    });

});


// Add family-fondo
$( '#btn-add-family-fondo' ).on( 'click' ,function () 
{
    var family_id = $( '#selectfamilyadd' ).val();
    var family_exist = false;
    $('.producto_value').each(function(i,v)
        {
            if(family_id == $(this).val() )
                family_exist = true;
        });

    if(!family_exist)
        $.ajax(
        {
            url: server + 'agregar-familia-fondo',
            type: 'POST' ,
            data:
            {
                _token       : GBREPORTS.token,
                solicitud_id : id_solicitud.val() ,
                producto : family_id
            }
        }).fail( function ( statusCode , errorThrown )
        {
            ajaxError( statusCode , errorThrown );
        }).done( function ( response )
        {
            if ( response.Status === 'Ok' )
            {
                 if(response.Data.Cond == true){
                    var options_val = '<option selected="" disabled="" value="0">Seleccione el Fondo</option>';
                    $.each( response.Data.Fondo_product, function( i, val ) 
                    {
                        options_val += '<option value="'+val.id+',' + val.tipo+'">'+ val.descripcion +' S/.'+ val.saldo_disponible +'</option>';
                    });
                    $("#list-product2").append('<li class="list-group-item"><div class="input-group input-group-sm"><span class="input-group-addon" style="width:15%;">'+
                    $("#selectfamilyadd option:selected").text() + '</span><select name="fondo_producto[]" class="selectpicker form-control">' +
                    options_val +'</select><span class="input-group-addon">S/.</span>'+
                    '<input name="monto_producto[]" type="text" class="form-control text-right amount_families2" value="0" style="padding:0px;text-align:center">'+
                    '<button type="button" class="btn-remove-family" style=""><span class="glyphicon glyphicon-remove"></span></button> </div>'+
                    '<input type="hidden" name="producto[]" class="producto_value" value="'+family_id+'"></li>');


                    $( ".btn-remove-family" ).bind( "click", function() {
                        $(this).prev('input').val(0);
                        verifySum( $(this).prev('input') , 1 )
                        $(this).closest('li').remove();
                        
                    });
                    $('#approval-product-modal').modal('toggle');
                }
                else
                     bootbox.alert( '<h4 class="red">' + response.Data.Description + '</h4>');
            }
            else
            {
                bootbox.alert( '<h4 class="red">' + response.Status + ': ' + response.Description + '</h4>');
            }
        });
    else
         bootbox.alert( '<h4 class="red">El producto ya se encuentra en la lista</h4>');
});


$( '.btn-remove-family' ).click( function () {
    $(this).prev('input').val(0);
    verifySum( $(this).prev('input') , 1 );
    $(this).closest('li').remove();   
});

$("#is-product-change").change(function() {
    if(this.checked) {
        
        $("#open_modal_add_product").show();
         $("#list-product").hide();

        $('#list-product :input').attr('disabled', true);
        $('#list-product2 :input').removeAttr('disabled');
       $("#list-product2").show();
    }
    else{
         $("#open_modal_add_product").hide();
        $("#list-product2").hide();
        $('#list-product2 :input').attr('disabled', true);
        $('#list-product :input').removeAttr('disabled');
        $("#list-product").show();
    }
});

// Edit Family Fondo
$( '.editProduct' ).on( 'click' , function()
{
    var id_solicitud_product = $( this ).parent().parent().find( 'input[ name = "producto[]" ]' ).val();
    $.ajax(
    {
        url  : server + 'editar-familia-fondo',
        type : 'POST' ,
        data :
        {
            _token             : GBREPORTS.token,
            solicitud_producto : family_id
        }
    }).fail( function ( statusCode , errorThrown )
    {
        ajaxError( statusCode , errorThrown );
    }).done( function ( response )
    {

    });
});

$( document ).ready(function() 
{
    $(".sim_alerta").hide();
    function getAlerts()
    {
        var url = URL_BASE + "alerts";
        $.ajax(
        {
            type       : 'POST',
            url        : url,
            ContentType: false,
            cache      : false,
            data       : 
            {
                _token     : GBREPORTS.token
            }
        }).done( function( dataResult ) 
        {
            if( dataResult.status == 'OK' )
            {
                if( typeof( dataResult.alerts ) != 'undefined' )
                {
                    if( dataResult.alerts.length > 0 )
                    {
                        $( '.sim_alerta' ).show( 'slow' );
                        var alerts = dataResult.alerts;
                        $( '.sim_alerta' ).find( 'span' ).html( 
                            ( typeof alerts[0] === 'undefined' ? 0 : alerts[0].data.length ) + 
                            ( typeof alerts[1] === 'undefined' ? 0 : alerts[1].data.length ) + 
                            ( typeof alerts[2] === 'undefined' ? 0 : alerts[2].data.length ) );
                    }
                }
            }         
        });
    }
    getAlerts();

    seeker( $( '.cliente-seeker' ) , 'clients' , 'search-client' );
    seeker( $( '.institucion-seeker' ) , 'institutions' , 'search-institution' );
    seeker( $( '.rep-seeker' ) , 'reps' , 'search-rep' );
    seeker( $( '#user-seeker' ) , 'users' , 'search-users' );

    if ( $("#idState").length === 1 )
    {
        listTable( 'solicitudes' );
    }

    _token       =  GBREPORTS.token;

    /** --------------------------------------------- CONTABILIDAD ------------------------------------------------- **/

    $(document).off('change','#idState')
    $(document).on('change','#idState',function()
    {
        listTable( 'solicitudes' );
    });


    /** ---------------------------------------- SET DISABLED INPUTS INIT ------------------------------------------**/
    $('#list-product2 :input').attr('disabled', true);

});

