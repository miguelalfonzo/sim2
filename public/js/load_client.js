$(function() {
   
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

    $( "#project" ).autocomplete({
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
});