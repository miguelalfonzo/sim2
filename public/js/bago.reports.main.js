$(document).ready(function() 
{
	$('#email_send').click(function() 
    {	
    	if( $("#email_search").val() == '' )
    	{
    		alert('Por Favor ingresar el correo presionando Enter al final del campo de Enviar Correo');
    	}
    	else if( $("#email_content").val() == '' || $("email_subject").val() == '' )
    	{
    		alert('Por Favor ingresar un asunto y contenido');
    	}
    	else
    	{
	    	ele = this;
	    	ele.disabled = true;
	    	dat = {} ;
	    	emails = $("#email_search").val();
	    	dat.id_reporte = GBREPORTS.lastReport.id_reporte;
	    	aEmails = emails.split(',');
	    	dat.emails = aEmails;
	    	dat.fromDate = $("#drp_fecha").val().split(" - ")[0];
	    	dat.toDate = $("#drp_fecha").val().split(" - ")[1];
	    	dat.subject = $("#email_subject").val();
	    	dat.content = $("#email_content").val();

	    	jStr = JSON.stringify(dat);
	    	$.ajax(
	    	{
				type: 'post',
				url : server+'mail_send',
				data: jStr,
				error: function()
				{
					bootbox.alert("<p class='red'>Error, no se puede enviar.</p>");
					ele.disabled = false;
				}
			}).done( function (data)
			{
				jData = JSON.parse(data);
				if (jData.Status == 'Ok')
				{
				$("#email_search").tagsinput('removeAll');
				$("#email_content").val('');
				$("#email_subject").val('');
				jdata = JSON.parse(data);
				alert("El mensaje se envio correctamente");
				ele.disabled = false;
				$('#modal_sendmail').modal('hide');
				}
				else
				{
					jdata = JSON.parse(data);
					alert(jdata.Description);
					ele.disabled = false;
				}
			});
		}
	});
	

	function getEmails(request, response) 
	{
		console.log("hola");
		$.ajax(
		{
			type: 'post',
			url: server+'get_emails',
			data: request.term,
		
			error: function(jqXHR,textStatus,errorThrow)
			{
				bootbox.alert('<p class="red">Error, no se puede acceder al sistema: .'+textStatus+'</p>')
			}
		}).done( function (data)
		{
				match = JSON.parse(data);
				response(match.Data);
		});
	}

	$(".bootstrap-tagsinput input").on("keyup",function(){
		$(this).autocomplete({
			minLength: 3,
			source: getEmails,
			select: function( event, ui ) {
				$("#email_search").tagsinput('add',ui.item);
				$(this).val('');
				return false;
			}
		}).data("ui-autocomplete")._renderItem = function (ul, item) {
			return $("<li>").append("<a>" +
                        "<span style='font-size: 60%;'>"+ item.name + "</span></a><br/>" +
                        "<a>" +
                        "<span style='font-size: 60%;'>"+ item.email + "</span></a>").appendTo(ul);
        };
	});

	

	$("#email_search").tagsinput(
	{
			itemValue: 'email',
			itemText: 'name'
	});

	moment.lang('es', {
	    months : "Enero_Febrero_Marzo_Abril_Mayo_Junio_Julio_Agosto_Setiembre_Octubre_Noviembre_Diciembre".split("_"),
	    monthsShort : "Ene_Feb_Mar_Abr_May_Jun_Jul_Ago_Set_Oct_Nov_Dic".split("_"),
	    weekdays : "Domingo_Lunes_Martes_Miercoles_Jueves_Viernes_Sabado".split("_"),
	    weekdaysShort : "Dom_Lun_Mar_Mie_Jue_Vie_Sab".split("_"),
	    weekdaysMin : "Do_Lu_Ma_Mi_Ju_Vi_Sa".split("_"),
	    longDateFormat : {
	        LT : "HH:mm",
	        L : "DD/MM/YYYY",
	        LL : "D MMMM YYYY",
	        LLL : "D MMMM YYYY LT",
	        LLLL : "dddd D MMMM YYYY LT"
	    },
	    calendar : {
	        sameDay: "[Today at] LT",
	        nextDay: '[Tomorrow at] LT',
	        nextWeek: 'dddd [at] LT',
	        lastDay: '[Yesterday at] LT',
	        lastWeek: '[Last] dddd [at] LT',
	        sameElse: 'L'
	    },
	    relativeTime : {
	        	future: 'in %s',
				past: '%s ago',
				s: 'a few seconds',
				m: 'a minute',
				mm: '%d minutes',
				h: 'an hour',
				hh: '%d hours',
				d: 'a day',
				dd: '%d days',
				M: 'a month',
				MM: '%d months',
				y: 'a year',
				yy: '%d years'
	    },
	    ordinal : function (number) {
	        return number + (number === 1 ? 'er' : 'ème');
	    },
	    week : {
	        dow : 0, // Monday is the first day of the week.
	        doy : 6  // The week that contains Jan 4th is the first week of the year.
	    }
	});
	

    $(document).off("change", "#query");
	$(document).on("change", "#query", function(){
		a = this;
		a.style.height = 'auto';
		a.style.height = a.scrollHeight+'px';
	});

	$(document).off("click","li.report_menubar_option>a");
	$(document).on("click","li.report_menubar_option>a", function(e){
		e.preventDefault();

		var rel = $(this).attr("rel");
		if(rel=="open"){
			
			$('#btn_extra').hide();
			var reporte_id = $(this).attr("data-id");
			GBREPORTS.openReport(reporte_id);

		}else if(rel=="new"){
			GBREPORTS.getAllDataSet();
			
		}
		else if(rel=="export")
		{
			
			$("#loading").show("slow");
			GBREPORTS.getReportExcel("fm-grid-layout", GBREPORTS.lastReport.descripcion);
		}
		else if(rel=="email")
		{
			bootbox.dialog({
			  message: GBREPORTS.modal.sendEmail,
			  title: "Enviar Reporte por Email",
			  buttons: {
			    success: {
			      label: "Enviar",
			      className: "btn-success",
			      callback: function() {
				  
			      }
			    }
			  }
			});

		}	
	});
	$("#drp_menubar").submit(function(e){
		e.preventDefault();
		$("#drp_fecha").blur();
		return false;
	});
});

HTMLElement.prototype.click = function() {
   var evt = this.ownerDocument.createEvent('MouseEvents');
   evt.initMouseEvent('click', true, true, this.ownerDocument.defaultView, 1, 0, 0, 0, 0, false, false, false, false, 0, null);
   this.dispatchEvent(evt);
} 

GBREPORTS.cleanWizard();

