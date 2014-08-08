$("#btn-add-prod").on('click', function () {
    //console.log('hola');
    $(".btn-delete-prod").show();
    $('#listprod>li:first-child').clone(true, true).appendTo('#listprod');
});

$(".btn-delete-prod").hide();
$(document).on("click", ".btn-delete-prod", function () {

    $('#listprod>li .porcentaje_error').css({"border": "0"});
    $('.repeat').hide();
    $(".option-des-1").removeClass('error');
    var k = $("#listprod li").size();
    console.log(k);
    if (k > 1) {

        var other = $(".btn-delete-prod").index(this);
        $("#listprod li").eq(other).remove();
        var p = $("#listprod li").size();
        if (p == 1) {
            $(".btn-delete-prod").hide();
        }
    }
});


function load_client(client){

    var clients = [
        {
            rn: "1",
            clcodigo: "834",
            clnombre: "FCIA. MEDISUR . SURQUILLO"
        },
        {
            rn: "2",
            clcodigo: "835",
            clnombre: "BOT. MEGAFARMA I"
        },
        {
            rn: "3",
            clcodigo: "836",
            clnombre: "BOT. CONTINENTA L ATE"
        },
        {
            rn: "4",
            clcodigo: "3026",
            clnombre: "RENE HUAMANI CALDERON"
        },
        {
            rn: "5",
            clcodigo: "2892",
            clnombre: "FARMACIA FARMA BOLIVAR"
        },
        {
            rn: "6",
            clcodigo: "2893",
            clnombre: "BOTICAS BAZAR INTERFARMA"
        }
    ];

    function lightwell(request, response) {
        function hasMatch(s) {
            return s.toLowerCase().indexOf(request.term.toLowerCase())!==-1;
        }
        var i, l, obj, matches = [];

        if (request.term==="") {
            response([]);
            return;
        }

        for  (i = 0, l = clients.length; i<l; i++) {
            obj = clients[i];
            if (hasMatch(obj.clnombre)) {
                matches.push(obj);
            }
        }
        response(matches);
    }

    $('#project'+client).autocomplete({
        minLength: 0,
        source: lightwell,
        focus: function( event, ui ) {
            $( this ).val( ui.item.clnombre );
            return false;
        },
        select: function( event, ui ) {
            // $( "#project" ).val( ui.item.label );
            $( "#project-id" ).val( ui.item.clcodigo ); // is this needed?
            $( "#project-title" ).html( ui.item.clcodigo );
            $( "#project-description" ).html( ui.item.clnombre );
            // $( "#project-other" ).html( ui.item.other );

            return false;
        }
    })

        .data( "ui-autocomplete" )._renderItem = function( ul, item ) {
        return $( "<li>" )
            .append( "<a>" +
                "<br><span style='font-size: 80%;'>Codigo: " + item.clcodigo + "</span>" +
                "<br><span style='font-size: 60%;'>Cliente: " + item.clnombre + "</span></a>" )
            .appendTo( ul );
    };

}
load_client(1);
var client = 2;
$(document).on('click', '#btn-add-client', function () {


    $('<li><div style="position: relative"><input id="project'+client+'" name="cliente" type="text" placeholder="" style="margin-top: 10px" class="form-control input-md project"> <button type="button" class="btn-delete-client" style=""><span class="glyphicon glyphicon-remove"></span></button></div></li>').appendTo('#listclient');
    $(".btn-delete-client").show();
    setTimeout(function(){
        load_client(client);
        client++;
    }, 200);







});

$(document).on("click", ".btn-delete-client", function () {

    $('#listclient>li .porcentaje_error').css({"border": "0"});
    $('.repeat').hide();
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

// autocomplet

$(function () {

    $(".tags").autocomplete({
        source: availableTags2,
        minLength: 0,
        select: function (event, ui) {
            alert("Selected " + ui.item.clnombre);
        },
        focus: function (event, ui) {
            // this is to prevent showing an ID in the textbox instead of name
            // when the user tries to select using the up/down arrow of his keyboard
            $(".tags").val(ui.item.clnombre);
            alert("Selected " + ui.item.clnombre);
            return false;
        }

        //select : function(){($(this).attr('readonly','readonly'))}
    });
});
