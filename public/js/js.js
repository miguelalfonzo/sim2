var availableTags = [
    "ActionScript",
    "AppleScript",
    "Asp",
    "BASIC cesar",
    "C jose",
    "C++",
    "Clojure",
    "COBOL",
    "ColdFusion",
    "Erlang",
    "Fortran",
    "Groovy",
    "Haskell",
    "Java",
    "JavaScript",
    "Lisp",
    "Perl",
    "PHP",
    "Python",
    "Ruby",
    "Scala",
    "Scheme"
];





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


$(document).on('click', '#btn-add-client', function () {

    $('<li><div style="position: relative"><input id="inputclient" name="cliente" type="text" placeholder="" class="form-control input-md tags"> <button type="button" class="btn-delete-client" style=""><span class="glyphicon glyphicon-remove"></span></button></div></li>').appendTo('#listclient');
    $(".btn-delete-client").show();

    $(".tags").autocomplete({
        source: availableTags.clnombre
        //select : function(){($(this).attr('readonly','readonly'))}
    });
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
/*
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
*/
$(function () {

    var projects = [
        {
            value: "jquery",
            label: "jQuery",
            desc: "the write less, do more, JavaScript library",
            other: "9834275 9847598023 753425828975340 82974598823"
        },
        {
            value: "jquery-ui",
            label: "jQuery UI",
            desc: "the official user interface library for jQuery",
            other: "98 83475 9358 949078 8 40287089754 345 2345"
        },
        {
            value: "sizzlejs",
            label: "Sizzle JS",
            desc: "a pure-JavaScript CSS selector engine",
            other: "49857 2389442 573489057 89024375 928037890"
        }
    ];
    var availableTags2 = [
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
            return s.toLowerCase().indexOf(request.term.toLowerCase()) !== -1;
        }

        var i, l, obj, matches = [];

        if (request.term === "") {
            response([]);
            return;
        }

        for (i = 0, l = availableTags2.length; i < l; i++) {
            obj = availableTags2[i];
            if (hasMatch(obj.clnombre) || hasMatch(obj.clcodigo)) {
                matches.push(obj);
            }
        }
        response(matches);
    }

    $(".project").autocomplete({
        minLength: 0,
        source: lightwell,
        focus: function (event, ui) {

            $(".project").val(ui.item.clnombre);
            return false;
        }

    })
});

