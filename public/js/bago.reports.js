!function($) {
	var ajaxGE = null;
    var gbReports = function() {
        this.version = "0.1";
		this.lastReport={};
		this.reportDate = null;
		this.contentTypeAjax = false; // idkc: old = "text/plain; charset=UTF-8";
		this.token = $('meta[name="csrf-token"]').attr('content');
		var gbReportsObject = this;
		this.dateRangePickerCallback = function(start, end, label) {
			console.log(start.toISOString(), end.toISOString(), label);
			gbReportsObject.reportDate = start.format("YYYY/MM/DD") + " - " + end.format("YYYY/MM/DD");
			$('#reportrange span').html(start.format('LL') + ' - ' + end.format('LL'));
		};
		this.dateRangePickerLocaleDefault = {
			applyLabel      : 'Aplicar',
			cancelLabel     : 'Cancelar',
			fromLabel       : 'Desde',
			toLabel         : 'Hasta',
			weekLabel       : 'W',
			customRangeLabel: 'Personalizado',
		};
		this.dateRangePickerRangesDefault = {
			'Hoy'           : [moment(), moment()],
			'Ayer'          : [moment().subtract('days', 1), moment().subtract('days', 1)],
			'Ultimos 7 Dias': [moment().subtract('days', 6), moment()],
			'Mes Actual'    : [moment().startOf('month'), moment().endOf('month')],
			'Mes Pasado'    : [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')]
		};
		this.dateRangePickerStartDate   = moment().startOf('month');
		this.dateRangePickerEndDate     = moment();
		this.dateRangePickerFormat      = 'YYYY/MM/DD';
		this.dateRangePickerApplyClass  = 'btn-fill btn-success btn-sm';
		this.dateRangePickerCancelClass = 'btn-fill btn-default btn-sm';
		this.dateRangePickerOption = {
			locale     : this.dateRangePickerLocaleDefault,
			ranges     : this.dateRangePickerRangesDefault,
			startDate  : this.dateRangePickerStartDate,
			endDate    : this.dateRangePickerEndDate,
			format     : this.dateRangePickerFormat,
			applyClass : this.dateRangePickerApplyClass,
			cancelClass: this.dateRangePickerCacnelClass
		};
		this.modal = {};
		this.modal.sendEmail = '<label for="email_list">Ingresar usuarios a enviar Email</label>' +
		'<div class="form-check">' +
			'<input id="email_search" data-role="tagsinput" type="text" style="display: none;"><div class="bootstrap-tagsinput"><input type="text" placeholder="" style="width: 10em !important;" size="5" class="ui-autocomplete-input" autocomplete="off"></div>' +
		'</div>' +
		'<div class="form-check">' +
			'<label for="email_list">Ingresar Asunto</label>' +
			'<input id="email_subject" type="text" class="input-large form-control relative">' +
		'</div>' +
		'<div class="form-check">' +
			'<label for="email_list">Ingresar Contenido</label>' +
			'<textarea id="email_content" class="form-control" rows="4"></textarea>' +
		'</div>' +
		'<div class="form-check">' +
			'<input type="button" class="btn btn-default" id="email_send" style="background:#000080" value="Enviar">' +
		'</div>';
		this.templateNewReport = '<div class="modal fade report_new" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">' +
		'<div class="modal-dialog modal-lg">' +
			'<div class="modal-content">' +
				'<div id="new_report" class="col-sm-8 col-sm-offset-2">' +
					'<div  class="wizard-container" style="">' +
						'<form action="" method="">' +
							'<div class="card wizard-card ct-wizard-orange" id="wizard">' +
								'<!--        You can switch "ct-wizard-orange"  with one of the next bright colors: "ct-wizard-blue", "ct-wizard-green", "ct-wizard-orange", "ct-wizard-red"             -->' +
								'<div class="wizard-header">' +
									'<h3>' +
										'<b>Nuevo</b> Reporte <br>' +
										'<small>Complete la informacion necesaria para generar un nuevo reporte</small>' +
									'</h3>' +
								'</div>' +
								'<ul>' +
									'<li><a href="#dataset" data-toggle="tab">DataSet</a></li>' +
									'<li><a href="#fields" data-toggle="tab">Campos</a></li>' +
									'<li><a href="#details" data-toggle="tab">Detalles</a></li>' +
								'</ul>' +
								'<div class="tab-content">' +
									'<div class="tab-pane" id="dataset">' +
										'<div class="row">' +
											'<h4 class="info-text"> Seleccione una fuente de informacion</h4>' +
											'<div class="col-sm-10 col-sm-offset-1">' +
												'<div id="containerDataSet" class="form-group">' +
													'<div class="radio">' +
														'<label>' +
														'<input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked>' +
														'DataSet 1' +
														'</label>' +
													'</div>' +
													'<div class="radio">' +
														'<label>' +
														'<input type="radio" name="optionsRadios" id="optionsRadios2" value="option2">' +
														'DataSet 2' +
														'</label>' +
													'</div>' +
													'<div class="radio">' +
														'<label>' +
														'<input type="radio" name="optionsRadios" id="optionsRadios3" value="option3" >' +
														'DataSet 3' +
														'</label>' +
													'</div>' +
												'</div>' +
											'</div>' +
										'</div>' +
									'</div>' +
									'<div class="tab-pane" id="fields">' +
										'<h4 class="info-text"> Selecciones los campos a analizar.</h4>' +
										'<div class="row">' +
											'<div class="col-sm-10 col-sm-offset-1">' +
												'<div class="sideBySide">' +
													'<div class="left col-sm-4">' +
														'<ul id="dataHeaders" class="source connected ui-sortable">  ' +
														'</ul>' +
													'</div>' +
													'<div class="right col-sm-4">' +
														'<span>Filas</span>' +
														'<ul id="rows" class="source connected ui-sortable">' +
															'<li class="ui-sortable-handle">fecha</li>' +
															'<li class="ui-sortable-handle">representante</li>' +
															'<li class="ui-sortable-handle">medico</li>' +
															'<li class="ui-sortable-handle">institucion</li>' +
														'</ul>' +
													'</div>' +
													'<div class="right col-sm-4">' +
														'<span>Columnas</span>' +
														'<ul id="columns" class="target connected ui-sortable">' +
															'<li class="ui-sortable-handle">producto</li>' +
														'</ul>' +
													'</div>' +
													'<div class="right col-sm-8">' +
														'<span>Valor</span>' +
														'<ul id="values" class="target connected ui-sortable">' +
															'<li class="ui-sortable-handle">cantidad</li>' +
														'</ul>' +
													'</div>' +
													'<div class="clearfix"></div>' +
												'</div>' +
											'</div>' +
										'</div>' +
									'</div>' +
									'<div class="tab-pane" id="details">' +
										'<div class="row">' +
											'<div class="col-sm-12">' +
												'<h4 class="info-text"> Listo! </h4>' +
											'</div>' +
											'<div class="col-sm-7 col-sm-offset-1">' +
												'<div class="form-group">' +
													'<labe>Nombre del Reporte</label>' +
													'<input type="text" class="form-control" id="reportName" placeholder="">' +
												'</div>' +
											'</div>' +
											'<div class="col-sm-7 col-sm-offset-1">' +
												 '<div class="form-group">' +
													'<label>Frecuencia</label><br>' +
													 '<select id="frecuency" class="form-control">' +
														'<option value="N"> Ninguna </option>' +
														'<option value="S"> Semanal </option>' +
														'<option value="M"> Mensual </option>' +
													'</select>' +
												  '</div>' +
											'</div>' +
										'</div>' +
									'</div>' +
								'</div>' +
								'<div class="wizard-footer">' +
									'<div class="pull-right">' +
										'<input type="button" class="btn btn-next btn-fill btn-warning btn-wd btn-sm" name="next" value="Siguiente" />' +
										'<input type="button" class="btn btn-finish btn-fill btn-warning btn-wd btn-sm" name="finish" value="Guardar" />' +
									'</div>' +
									'<div class="pull-left">' +
										'<input type="button" class="btn btn-previous btn-fill btn-default btn-wd btn-sm" name="previous" value="Anterior" />' +
									'</div>' +
									'<div class="clearfix"></div>' +
								'</div>' +
							'</div>' +
						'</form>' +
					'</div>' +
					'<!-- wizard container -->' +
				'</div' +
			'</div>' +
		'</div>' +
	'</div>';
	this.initGBReports();
    };
    gbReports.prototype = {
        constructor: gbReports,
        initGBReports: function(){
			$('body').append(this.templateNewReport);
			this.reportDate = moment().startOf('month').format("YYYY/MM/DD") + " - " + moment().format("YYYY/MM/DD");
			$('#drp_menubar span').html(moment().startOf('month').format('LL') + ' - ' + moment().format('LL'));
			$('#drp_menubar').daterangepicker(this.dateRangePickerOption, this.dateRangePickerCallback);
			// GBREPORTS.setAutoResize();
			this.getReports();
        },
        setMenuBarReports: function(reportsList) {
            this.reportsArray = reportsList;
            if (this.reportsArray.length > 0) {
				$("#menubar_reports").html('');
                for (var z = 0; z < this.reportsArray.length; z++) {
					var temp = '<li class="report_menubar_option">' +
									'<a href="#" rel="open" data-id="' + this.reportsArray[z].id_reporte + '">' +
										'<span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span>' +
										'<span class="glyphicon-class"> ' + this.reportsArray[z].descripcion + '</span>' +
									'</a>' +
								'</li>';
                    $("#menu-report").append($(temp));
                }
            }
        },
        getReports: function(callbacks) {
			var url = URL_BASE + "reports/getUserReports";
            var gbReportsObject = this;
            $.ajax({
                url: url,
                ContentType: gbReportsObject.contentTypeAjax,
                cache: false
            }).done(function(dataResult) {
            	console.log(dataResult);
				if(dataResult.status == 'OK'){
					if(typeof(dataResult.data) != 'undefined')
						gbReportsObject.setMenuBarReports(dataResult.data);
				}
				else{
					bootbox.alert(dataResult.message);
				}
            });
        },
		getAllDataSet: function(){
			var url = URL_BASE + 'reports/getQuerys';
			var gbReportsObject = this;
            $.ajax({
                url: url,
                ContentType: gbReportsObject.contentTypeAjax,
                cache: false
            }).done(function(dataResult) {
				if(dataResult.status == 'OK'){
					$("#containerDataSet").html("");
					$(dataResult.data).each(function(i, temp){
						var radioButtonElement = $('<div class="radio"><label><input type="radio" name="optionsRadios" value="'+ temp.id +'" checked>'+ temp.name +'</label></div>');
						$("#containerDataSet").append(radioButtonElement);
					});
				}else{
					alert(dataResult.message);
					$("#loading").show("slow");
				}
            });
		},
		getDataHead: function(queryId){
			var url = URL_BASE + 'reports/getColumnsDataSet/'+queryId;
			var gbReportsObject = this;
            $.ajax({
                url: url,
                ContentType: gbReportsObject.contentTypeAjax,
                cache: false,
				async: false,
            }).done(function(dataResult) {				
				if(dataResult.status == 'OK'){
					$("#dataHeaders").html("");
					$("#rows").html("");
					$("#columns").html("");
					$("#values").html("");
					$(dataResult.data).each(function(i, temp){
						var liElement = $('<li>'+temp+'</li>');
						$("#dataHeaders").append(liElement);
					});
					gbReportsObject.activateDragAndDrop();
				}else{
					bootbox.alert(dataResult.message);
				}
            });
		},
		getReportExcel: function(selector, name){
			gbReportGlobals = this;
			gbReportGlobals.generateReport();
			// fromDate      = this.reportDate.split(" - ")[0].split("/");			
			// toDate        = this.reportDate.split(" - ")[1].split("/");			
			// var url       = 'reports/export/download/'+gbReportGlobals.lastReport.id_reporte+'/'+fromDate[0]+fromDate[1]+fromDate[2]+"/"+toDate[0]+toDate[1]+toDate[2];
			// location.href = url;
			// $("#loading").hide("slow");
		
		},
		openReport: function(id){
			$("#loading").show("slow");
			var reporte_id = id;
			var report;
			var dataStringTemp = {};
			if(reporte_id){
				report = GBREPORTS.getReportObject(reporte_id);
			}else if(GBREPORTS.lastReport){
				reporte_id = GBREPORTS.lastReport.id_reporte;
				report = GBREPORTS.getReportObject(reporte_id);
			}
			if(reporte_id){
				dataStringTemp.id_reporte = reporte_id;
				dataStringTemp.fromDate = this.reportDate.split(" - ")[0];
				dataStringTemp.toDate = this.reportDate.split(" - ")[1];
				this.getDataReport(dataStringTemp);
				GBREPORTS.filter = [];
			}else{
				$("#loading").hide("slow");
			}
		},
		saveReport: function(report){
			var url = URL_BASE + 'reports/save';
			var gbReportsObject = this;
			data={};
			data._token = gbReportsObject.token;
			data.data = report;
            $.ajax({
                url: url,
				type: 'POST',
                ContentType: gbReportsObject.contentTypeAjax,
                cache: false,
				data: data
            }).done(function(dataResult) {
				if(dataResult.status == 'OK'){
					bootbox.alert('Reporte Guardado Satisfactoriamente.');
					gbReportsObject.getReports();
					$(".bs-example-modal-lg").modal('hide');
					gbReportsObject.cleanWizard();
				}else{
					bootbox.alert(dataResult.message);
				}
			});
		},
		intVal: function(i) {
	        return typeof i === 'string' ?
	            i.replace(/[\$,]/g, '')*1 :
	            typeof i === 'number' ?
	                i : 0;
	    },
		getDataReport: function(dataString){
			fromDate = dataString.fromDate.split("/");
			toDate = dataString.toDate.split("/");
			
			var url = URL_BASE + "reports/generate_html/"+dataString.id_reporte+"/"+fromDate[0]+fromDate[1]+fromDate[2]+"/"+toDate[0]+toDate[1]+toDate[2];

			var calcDataTableHeight = function() {
		        return $(window).height()*50/100;
		    };
			
			var gbReportsObject = this;
			
			$.ajax({
				type: "GET",
                url: url,
                cache: false,
            }).done(function(data) {
            		formula = JSON.parse(GBREPORTS.lastReport.formula);
            		if (data.status == 'OK')
            		{
            			GBREPORTS.valores = data.valores;
						$("#dataTable").html('<table id="dt_report" class="table table-striped table-bordered" cellspacing="0" width="100%"></table>');
						var thead = '<thead><tr>';
						$(data.theadList).each(function(i, data){
							thead += '<td>' + data + '</td>';
						});
						thead += '</tr></thead>';

						var tfoot = '<tfoot><tr>';
						$(data.tfootList[0]).each(function(i, data){
							tfoot += '<td>' + data + '</td>';
						});
						tfoot += '</tr></tfoot>';

						$("#dt_report").append(thead);
						$("#dt_report").append(tfoot);
						var totalRow = data.tfootList[0];
						report = $("#dt_report").DataTable({
							"data" : data.tbodyList,
							"sDom"          : "<'container-fluid'<'page-header'><<'col-6 pull-right'f><'col-6 pull-left'l>r>>t<<'col-6 pull-left'i><'col-6 pull-right'p>>",
							"pagingType"    : "bootstrap",
							"sScrollY"      : calcDataTableHeight(),
							"sScrollX"      : "100%",
							"scrollCollapse": true,
							"autoWidth"     : true,
							"lengthMenu": [[-1, 10, 25, 50], ["All", 10, 25, 50]],
							"oLanguage": {
								'sInfoEmpty'   : 'No hay registros disponibles',
								'sLengthMenu'  : 'Mostrar _MENU_ registros por página',
								'sPrevious'    : 'Anterior',
								'sNext'        : 'Siguiente',
								'sSearch'      : 'Buscar',
								'sZeroRecords' : 'No se encontro Información',
								'sInfo'        : 'Mostrando _END_ de _TOTAL_',
								'sInfoFiltered': '(Filtrado de _MAX_ registros en Total)'
							},
							"fnDrawCallback" : function(oSettings) {
								console.log("Search callbacks");
								var rowNum = data.rows.length-1;
								console.log("rownum = "+ rowNum);
								z = oSettings;
								filterData = oSettings.aiDisplay;
								GBREPORTS.filter = filterData;
								var allData =oSettings.aoData;
								if(allData.length != 0){
									head = allData[0]._aData;

									totalArray = [];

									// $(GBREPORTS.valores).each(function(i,data){
									// 	totalArray[i] = [];
									// });

									if(filterData.length > 0){
										$(filterData).each(function(j,numData){

											var posTotalArray = $.inArray(allData[numData]._aData[rowNum+1], GBREPORTS.valores);
											// var posTotalArray = null;
											// $(GBREPORTS.valores).each(function(v,data){

											// 	posTotalArray = $.inArray(data, allData[numData]._aData);
											// 	if(posTotalArray != -1){
											// 		posTotalArray = v;
											// 		return false;
											// 	}
											// });
											if(posTotalArray == -1)
												posTotalArray = 0;
											console.log("PostTotalArray "+posTotalArray);
											totalArray[posTotalArray] = typeof(totalArray[posTotalArray]) == 'undefined' ? [] : totalArray[posTotalArray];
											$(head).each(function(i,headColumn){
												totalArray[posTotalArray][i] = typeof(totalArray[posTotalArray][i]) == 'undefined' ? 0 : totalArray[posTotalArray][i];
												if(totalArray[posTotalArray]){
													if(typeof(totalArray[posTotalArray][i]) == 'undefined')
														totalArray[posTotalArray][i] = 0;
													
													var number = GBREPORTS.intVal(allData[numData]._aData[i]);
													if(rowNum <= i){
														if(!isNaN(number)){
															if(GBREPORTS.valores.length >= 2){
																
																if(totalArray[posTotalArray][i-1] == null){
																	totalArray[posTotalArray][i-2] = 'Total';
																	totalArray[posTotalArray][i-1] = allData[numData]._aData[i-1];
																}
															}else{
																if(totalArray[posTotalArray][i-1] == null){
																	totalArray[posTotalArray][i-1] = 'Total';
																}
															}
															totalArray[posTotalArray][i] = totalArray[posTotalArray][i] + number;
														}else{
															totalArray[posTotalArray][i] = null;
														}
													}else{
														totalArray[posTotalArray][i] = null;
													}
												}
											});
										});
									}else{
										$(head).each(function(i,headColumn){

											var number = GBREPORTS.intVal(headColumn);
											if(!isNaN(number)){
												if(rowNum <= i){
													$(GBREPORTS.valores).each(function(v,data){
														if(GBREPORTS.valores.length >= 2){
															
															if(totalArray[v][i-1] == null){
																totalArray[v][i-2] = 'Total';
																totalArray[v][i-1] = allData[numData]._aData[i-1];
															}
														}else{
															if(totalArray[v][i-1] == null){
																totalArray[v][i-1] = 'Total';
															}
														}
														totalArray[v][i] = 0;
													});
												}
											}
											else{
												$(GBREPORTS.valores).each(function(v,data){
													totalArray[v][i] = null;
												});
											}
										});
									}
									q = totalArray;
									console.log(totalArray);
										var footerElement = $(".dataTables_scrollFoot table tfoot");
										console.log("set footer");
									$(totalArray).each(function(i,row){

										rowFaltantes = GBREPORTS.valores.length - footerElement.children().length;
										for(var numElem = rowFaltantes - 1; numElem >= 0; numElem--) {
											footerElement.append(footerElement.children().eq(0).clone());
										};

										$(row).each(function(c, column){
											footerElement.children().eq(i).children().eq(c).html(column);
										});

									});
									// $(totalArray[0]).each(function(i,data){
									// 	$(".dataTables_scrollFoot table tfoot tr").children().eq(i).html(data);
									// });
								}
							}
						});
						$("div.page-header").html('<h4 style="margin-top: 0;">'+ data.title +'</h4>');
						$("div.page-header").css('padding-bottom', 0);
						$("div.page-header").css('margin', '0 0 11px');
						$("#fm-grid-view").css('height','100vh');
						$('html, body').animate({scrollTop: $('#dataTable').offset().top -10 }, 'slow');
						// $(".fm-sheet-data-canvas").tablesorter();
						// gbReportsObject.generateExcel();
						$('#btn_extra').show('slow');
						gbReportsObject.changeDateRange(formula.frecuency);
					}
					else
					{
						$("#dataTable").empty();
						bootbox.alert(data.Status + ': ' + data.message);
						gbReportsObject.changeDateRange(formula.frecuency);
					}
					$("#loading").hide("slow");
            });
		},
		getDateRangePickerOption: function(option){
			return {
				locale     : typeof(option.locale) != 'undefined' ? option.locale : this.dateRangePickerLocaleDefault,
				ranges     : typeof(option.ranges) != 'undefined' ? option.ranges : this.dateRangePickerRangesDefault,
				startDate  : typeof(option.startDate) != 'undefined' ? option.startDate : this.dateRangePickerStartDate,
				endDate    : typeof(option.endDate) != 'undefined' ? option.endDate : this.dateRangePickerEndDate,
				format     : typeof(option.format) != 'undefined' ? option.format : this.dateRangePickerFormat,
				applyClass : typeof(option.applyClass) != 'undefined' ? option.applyClass : this.dateRangePickerApplyClass,
				cancelClass: typeof(option.cancelClass) != 'undefined' ? option.cancelClass : this.dateRangePickerCacnelClass
			};
		},
		changeDateRange: function(frec)
		{
			option        = {};
			option.ranges = {};
			if(frec == 'M')
			{
				for (i=8; i>=1; i--)
				{
					mom = moment().subtract('month', 8).add('month',i);
					range[mom.format('YYYY') + " " + mom.format('MMM')] =  [mom.startOf('month'), mom.endOf('month')];
				}		
			}		
			else if(frec == 'S'){
				for(i=8; i>=1; i--)
				{
					mom = moment().endOf('isoweek').subtract('days',56).add('days',7*i);
					range[mom.format('MMM') + " " + mom.format('YYYY') + "| Semana " + mom.format('WW')] =[mom.startOf('isoweek'), mom.endOf('isoweek')]; 
				}
			}else{
				option.ranges = this.dateRangePickerRangesDefault;
			}
			
			$('#drp_fecha').daterangepicker(option);
		},
		generateReport: function(){
			var url = URL_BASE + "reports/export/generate"; 
			var gbReportsObject = this;
			var dataString = {};
	
			dataString.filter    = gbReportsObject.filter;
			dataString.reporteId = gbReportsObject.lastReport.id_reporte;
			dataString.fromDate  = this.reportDate.split(" - ")[0].split("/");			
			dataString.toDate    = this.reportDate.split(" - ")[1].split("/");			

			ajaxGE = $.ajax({
				type: "POST",
                url: url,
                cache: false,
                async: true,
                data: JSON.stringify(dataString)
            }).done(function(result) {
            	if(result.status == 'OK')
            	{
            		$("#loading").hide("slow");
            		location.href = result.url;
            	}else{
            		bootbox.alert(result.message);
            	}

            });
		},
		getReportObject: function(id){
			var result = null;
			if (this.reportsArray.length > 0) {
				for (var z = 0; z < this.reportsArray.length; z++)
				{
					if(this.reportsArray[z].id_reporte == id){
						result = this.reportsArray[z];
						break;
					}
				}
			}
			this.lastReport = result;
			return result;
		},
        
		activateDragAndDrop: function() {
			
			$('.source, .target').sortable({
				connectWith:'.connected', 
				update: function(){
					element = $(this);
					if(element.attr("id") == "values"){
						element.find("li:not(.operation)").each(function(i, data){
							$(data).html("<label>SUM:"+$(data).html()+
							"</label><div class='btn-group btn-group-xs' role='group' data-toggle='buttons' style='float:right'>"+
							  "<label class='btn btn-fill btn-default' style='line-height:14px' active>"+
							  "   <input type='radio' name='operation' autocomplete='off' checked>SUM</label>"+
							  "<label class='btn btn-fill btn-warning' style='line-height:14px'>"+
							  "   <input type='radio' autocomplete='off' name='operation' >COUNT</label>"+
							  "</div>");
							$(data).addClass("operation");
						});
					}
					else
					{
						element.find("li.operation").each(function(i, data){
						
						desc = this.getElementsByTagName('label')[0].innerHTML.split(":");
						this.innerHTML = desc[1];
						
						var elementHTML = $(data).html();
						
						$(data).html((elementHTML.indexOf("SUM:") != -1 ? elementHTML.replace("SUM:","") : elementHTML.replace("COUNT:","")));
						$(data).removeClass("operation");
						});
					}
				}
			});
		},/*
        
        setAutoResize: function() {
			
            $('body').css("overflow", "hidden");
            window.onresize = function(event) {
                var height = $(window).height();
                $("#fm-grid-layout").css("height", (parseInt(height) - 189) + "px");
            };
            $(window).resize();
        },*/
		cleanWizard: function (){
			$("#containerDataSet").html("");
			$("#dataHeaders").html("");
			$("#rows").html("");
			$("#columns").html("");
			$("#values").html("");
			$("#reportName").val("");
			$('li:has([data-toggle="tab"]) a').first().tab('show');
		}
    };
    GBREPORTS = new gbReports();
}(window.jQuery);