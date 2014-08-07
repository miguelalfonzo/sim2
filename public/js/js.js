
var availableTags = [
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

for(var f=0;f<availableTags.length;f++)
{
    console.log(availableTags);
}


$("#btn-add-prod").on('click',function(){
    //console.log('hola');
    $(".btn-delete-prod").show();
    $('#listprod>li:first-child').clone(true,true).appendTo('#listprod');
});

$(".btn-delete-prod").hide();
$(document).on("click",".btn-delete-prod",function(){

    $('#listprod>li .porcentaje_error').css({"border":"0"});
    $('.repeat').hide();
    $(".option-des-1").removeClass('error');
    var k = $("#listprod li").size();
    console.log(k);
    if(k>1)
    {

        var other = $(".btn-delete-prod").index(this);
        $("#listprod li").eq(other).remove();
        var p = $("#listprod li").size();
        if(p==1){
            $(".btn-delete-prod").hide();
        }
    }
});



$(document).on('click','#btn-add-client',function(){

    $('<li><div style="position: relative"><input id="inputclient" name="cliente" type="text" placeholder="" class="form-control input-md tags"> <button type="button" class="btn-delete-client" style=""><span class="glyphicon glyphicon-remove"></span></button></div></li>').appendTo('#listclient');
    $(".btn-delete-client").show();

    $( ".tags" ).autocomplete({
        source: availableTags.clnombre
        //select : function(){($(this).attr('readonly','readonly'))}
    });
});

$(document).on("click",".btn-delete-client",function(){

    $('#listclient>li .porcentaje_error').css({"border":"0"});
    $('.repeat').hide();
    $(".option-des-1").removeClass('error');
    var k = $("#listclient li").size();
    console.log(k);
    if(k>1)
    {

        var other = $(".btn-delete-client").index(this);
        $("#listclient li").eq(other).remove();
        var p = $("#listclient li").size();
        if(p==1){
            $(".btn-delete-client").hide();
        }
    }
});

// autocomplet
$(function() {

    $( ".tags" ).autocomplete({
        source: availableTags
        //select : function(){($(this).attr('readonly','readonly'))}
    });
});