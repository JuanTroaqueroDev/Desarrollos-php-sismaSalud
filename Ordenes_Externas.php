<?php

session_start();
require_once('../../Model/Model.php');
require_once('../../Model/WebModel.php');
require_once('menu_hc.php');
$model = new Model();
$model->online();
$wmodel = new WebModel();
$wmodel->Head();

$ImportarOrden = $model->getParametroGeneral('ImportarOrdenes','HISTORIA CLINICA');

if(isset($_REQUEST['desdeParaclinicos'])){
	$desdeParaclinicos=$_REQUEST['desdeParaclinicos'];
}else{
	$desdeParaclinicos=0;
}
  
ECHO '<input type="hidden" name="importarOrden" id="importarOrden" value="'.$ImportarOrden.'">';

if (!isset($_REQUEST["operacion"])&&!isset($_REQUEST["isCenso"])&&!isset($_REQUEST["EsNovedad"])) {
	clearSessionObject();
}

$urlParams = $model->ReutilizarREQUEST($_REQUEST, "&operacion&msg&n&oper&");

$imprime_hc=$model->getDato("imprimir_hc", "sis_nivelacceso", "codigo='".$_SESSION['nivel_acceso']."'");
?>

<?php 
                    
				
if($_REQUEST['general_paciente']!='si'&&$EsDescripcionQX!='1'&&!isset($_REQUEST["isCenso"])&&!isset($_REQUEST["EsNovedad"])){
	
	if($desdeParaclinicos==0){
	createMenu("Solicitud Ordenes");
	} 
}
?>
<style type="text/css">
		
		th.dato {
			border-right: 2px solid #FFF;
			border-top: 2px solid #FFF;
			border-left: 0px;
			border-bottom: 0px;
			color: #336699;
			background-color: #34b0c4;
		}

		td.dato {
			border-right: 2px solid #FFF;
			border-top: 2px solid #FFF;
			border-left: 0px;
			border-bottom: 0px;
			color: #315C7C;
		}
		
		datoHover:hover{
			background-color:#D1DBE6;
			cursor:pointer;
		}

		div.contenedor {
			width: 100%;
		}
		
		div.cuerpo {
			height: 160px;
			overflow: auto;
			border-bottom: 2px solid #FFF;
			border-right: 2px solid #FFF;
		}
		
		.intercalarColorTablaFind tr:nth-child(odd) {
		   background-color: #EFEFEF;
		   color:#777
		}
		
		.intercalarColorTablaFind tr:nth-child(odd):hover {
		   background-color: #D1DBE6;
		   cursor:pointer;
		}
		
		.intercalarColorTablaFind tr:nth-child(even) {
		   background-color: #FFF;
		   color:#FFF;
		}
		
		.intercalarColorTablaFind tr:nth-child(even):hover {
		   background-color: #D1DBE6;
		   cursor:pointer;
		}
		
		.selectedTrAncla{
			background-color:#C0DBA7 !important;
		}
	</style>
<script type="text/javascript" src="../../js/js.js"></script>
<script type="text/javascript" src="../../js/JSFindProcedimientos.js"></script>
<link href="../../Comun/Css/estilo-pg.css" rel="stylesheet" type="text/css" />
<link href="../../Comun/Css/Myestilo.css" rel="stylesheet" type="text/css" />
<link href="../../Comun/Css/main.css" rel="stylesheet" type="text/css" />
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="http://momentjs.com/downloads/moment.min.js"></script>
<br>
<?php
if(!isset($_REQUEST["isCenso"])){
?>
<script>



$(document).ready(function(){
	
	$("#VerOrdAnteriores").click(function(){
		var tipoOrden = $(".tactive").find("a").attr("tipo");
		cargarListaOrdenes(tipoOrden);
	});


	$("#ttabs li").click(function() {
		//	First remove class "active" from currently active tab
		$("#ttabs li").removeClass('tactive');

		//	Now add class "active" to the selected/clicked tab
		$(this).addClass("tactive");

		//	Hide all tab content
		$(".ttab_content").hide();

		//	Here we get the href value of the selected tab
		var selected_tab = $(this).find("a").attr("href");
		//	Show the selected tab content
		
		$(selected_tab).fadeIn();
		NuevaSolicitud();
		//	At the end, we add return false so that the click on the link is not executed
		return false;
	});
	
});

function ShowVadecum(){
		var contrato = get('contrato').value
		console.log(contrato)
		showWindowFind("codigo, descripcion",'vademecum','contrato|'+contrato,'Codigo,Descripcion','120,350','#proc2','Medicamentos Vademecum','actualizar(2)')
}
function showRn(){
	
	if($("#RealizarRn").is(":checked")){
		$("#hijode").val("HIJO 1 DE");
		$(".showRN").show();
	}else{
		$(".showRN").hide();
		$("#hijode").val("");
	}
}

function Nueva(numeroSolicitud,HasCodGrupo){
	var selected_tab = $(".tactive").find("a").attr("tipo");
	if(selected_tab=='Receta'){
		document.getElementById("frameDispensacionReceta").src="CtrlRecetas.php?operacion=abrir_ord&tipo_orden=rec&estudio=<?php echo $_REQUEST["estudio"];?>&estado_his=<?php echo $_REQUEST["estado_his"];?>&desdeParaclinicos=<?php echo $desdeParaclinicos;?>&llamadoOrdExt=1<?php echo $urlParams; ?>";
		return false;
	}else if(selected_tab=='Recomendaciones'){
		fnShowRecomendaciones();
		return false;
	}else if(selected_tab=='Incapacidades'){
		fnShowIncapacidades();
		return false;
	}else if(selected_tab=='FormulaMedica'){
		fnShowFormulaMedica();
		return false;
	}

	var titulo = '<label id="TituloWindows">'+$(".tactive").find("a").attr("titulo")+'</label>';
	//$.noConflict();
	$(this).ZS_CleanReferencias();
	var widget = new ModalContentZeusSalud(titulo,
			"nuevaOrdenExterna.php?tipo="+selected_tab+"&Eliminar=0", {numeroSolicitud:numeroSolicitud,esMontoPcte:getValue('esMontoPcte'),estudio:getValue("estudio"),HasCodGrupo:HasCodGrupo,IdNovedad:'<?php echo $_REQUEST["IdNovedad"];?>',auto_id:getValue("auto_id")}, "general", "", ((selected_tab=='medicamentos')?"80%":"90%"), '3%',null,listarItems,null,'showNuevaOrdExt');

	widget.imgs_data = {	
						
						close_icon:{
						src:"../../ImagenesZeus/salir 01.png", 
						_w:32, 
						_h:32, 
						_class:"icon close",
						_title:"Cerrar",
						id:"cerrar",
						fnAction:function(){
							//$.noConflict();
							closeModal('showNuevaOrdExt');
						}
						},
						
						guardar_icon:{
						src:"../../ImagenesZeus/guardar 01.png", 
						_w:32, 
						_h:32, 
						_class:"icon",
						_title:"Guardar",
						id:"guardar",
						fnAction:function(){
							var v=verificarfechaconsulta();
							if(v==false){
								return false;
							}
							else{
							if(selected_tab!='medicamentos' && selected_tab!='sol_medicamentos_dosis'){
								guardarSolicitud();	
							}else if(selected_tab=='medicamentos'){
								guardarSolicitudMedicamentos();		
							}else if(selected_tab=='sol_medicamentos_dosis'){
								guardarSolicitudMedicamentosDosis();	
							}
						}
					}
						},
						
						/*imprimir_icon:{
						src:"../../ImagenesZeus/imprimir 01.png", 
						_w:32, 
						_h:32, 
						_class:"icon",
						_title:"Imprimir SOlicitud",
						id:"imprimir",
						fnAction:function(){
							imprimirSolicitud(HasCodGrupo);	
						}
						}*/
				 };
	widget.process();
}

function NuevaSolicitud(numeroSolicitud,HasCodGrupo){
	var selected_tab = $(".tactive").find("a").attr("tipo");
	if(selected_tab=='Receta'){
		document.getElementById("frameDispensacionReceta").src="CtrlRecetas.php?operacion=abrir_ord&tipo_orden=rec&estudio=<?php echo $_REQUEST["estudio"];?>&estado_his=<?php echo $_REQUEST["estado_his"];?>&llamadoOrdExt=1<?php echo $urlParams; ?>";
		return false;
	}else if(selected_tab=='Recomendaciones'){
		fnShowRecomendaciones();
		return false;
	}else if(selected_tab=='Incapacidades'){
		fnShowIncapacidades();
		return false;
	}
	else if(selected_tab=='FormulaMedica'){
		fnShowFormulaMedica();
		return false;
	}

	var titulo = '<label id="TituloWindows">'+$(".tactive").find("a").attr("titulo")+'</label>';
	//$.noConflict();
	$(this).ZS_CleanReferencias();
	var widget = new ModalContentZeusSalud(titulo,
			"nuevaOrdenExterna_new.php?tipo="+selected_tab+"&Eliminar=0", {numeroSolicitud:numeroSolicitud,esMontoPcte:getValue('esMontoPcte'),estudio:getValue("estudio"),HasCodGrupo:HasCodGrupo,IdNovedad:'<?php echo $_REQUEST["IdNovedad"];?>'}, "general", "", ((selected_tab=='medicamentos')?"75%":"90%"), null,null,listarItems,null,'showNuevaOrdExt');

	widget.imgs_data = {	
						
						close_icon:{
						src:"../../ImagenesZeus/salir 01.png", 
						_w:32, 
						_h:32, 
						_class:"icon close",
						_title:"Cerrar",
						id:"cerrar",
						fnAction:function(){
							//$.noConflict();
							closeModal('showNuevaOrdExt');
						}
						},
						
						guardar_icon:{
						src:"../../ImagenesZeus/guardar 01.png", 
						_w:32, 
						_h:32, 
						_class:"icon",
						_title:"Guardar",
						id:"guardar",
						fnAction:function(){
							

							var v=verificarfechaconsulta();
							if(v==false){
								return false;
							}
							else{
							if(selected_tab!='medicamentos' && selected_tab!='sol_medicamentos_dosis'){
								guardarSolicitudNueva();	
							}else if(selected_tab=='medicamentos'){
								guardarSolicitudMedicamentos();		
							}else if(selected_tab=='sol_medicamentos_dosis'){
								guardarSolicitudMedicamentosDosis();	
							}
						}
					}
						},
						
						/*imprimir_icon:{
						src:"../../ImagenesZeus/imprimir 01.png", 
						_w:32, 
						_h:32, 
						_class:"icon",
						_title:"Imprimir SOlicitud",
						id:"imprimir",
						fnAction:function(){
							imprimirSolicitud(HasCodGrupo);	
						}
						}*/
				 };
	widget.process();
}

function guardarSolicitudNueva(){	
	console.log("Aqui");
	if($.trim($("#nroOrden").val())!=''){
		swal("No se puede modificar esta solicitud","","error");
		return false;
	}

	// if($.trim($("#codservicio").val())==''&&$.trim($('#ServicioSolicitudExterna').val())=='S'){
	// 	notify("Debe seleccionar un servicio.","error");
	// 	return false;
	// }
	
	// var codservicio=$("#codservicio").val();
	var cod=$("#cod").val();
	var fecha=$("#FechaSolicitud").val();
	var hora=$("#hora").val();
	var descripcion=$("#descripcion").val();
	var tipoOrden = $(".tactive").find("a").attr("tipo");
	var estudio=$('#estudio').val();
	
		
	var $ObjetoReferencias=JSON.stringify($(this).ZS_get_referencias());
	
	var object = {};
	if($("input[name='id_riesgos_asign_procs[]']").length > 0){
		$("input[name='id_riesgos_asign_procs[]']").each(function(key, value){
			object[key] = $(value).val();
		});
	}else{
		object = null;
	}
	// console.log(object)
	deshabilitarToolBar(true);
	ajaxPOST("../../controlador/HC/Ordenes_Externas.php","","habilitarToolBar(true);setNumOrdenNueva('"+tipoOrden+"');","","operacion=guardarSolicitudOrdenNuevo&estudio="+estudio+"&codespecialidad="+$("#codespecialidad").val()+"&fecha="+fecha+"&hora="+hora+"&descripcion="+encodeURIComponent(descripcion)+"&tipoOrden="+tipoOrden+"&cod="+cod+"&Referencias="+$ObjetoReferencias+"&IdNovedad=<?php echo $_REQUEST["IdNovedad"];?>"+"&hijode="+$("#hijode").val()+"&id_riesgos_asign_procs="+JSON.stringify(object));
}

function setNumOrdenNueva(tipoOrden){
	var resp=JSON.parse(_ResquestAJAX);
	//for(var i in resp) {

	var i=0;	
		if($.trim(resp["Mensaje"])==''){
			$("#nroOrden").val(resp["NumeroSolicitud"]);
			swal("Se ha guardado correctamente.","","succcess");
			
		 	cargarListaOrdenes(tipoOrden);
			//listarItems();
			$("#showNuevaOrdExt").parent().remove();
			$("#showNuevaOrdExt").remove();
			//closeModal('showNuevaOrdExt');
			if(tipoOrden=='medicamentos'){
				Nueva(resp["NumeroSolicitud"],0);
			}else{
				NuevaSolicitud(resp["NumeroSolicitud"],1);
			}
			
		}else{
			swal(resp["Mensaje"],"","error");
		}
	//}
}

function fnShowRecomendaciones(){
	respGetConsecutivo=function(){
		var resp=JSON.parse(_ResquestAJAX);

		$divGral=$("<div>");
		$divGral.ZS_div("");
		$div.ZS_tabla('width="700px"');
		$tabla.ZS_tr('');
		$tr.ZS_td('class="letraDisplay"','Nota No.');
		$tr.ZS_td('class="letraDisplay"','');
		$td.ZS_input('type="text" class="normDisabled" readonly',resp[0]["Consecutivo"]);
		$Numero=$input;
		var f='<?php echo date("Y/m/d"); ?>';
		var h='<?php echo date("H:i"); ?>' 
		$tr.ZS_td('class="letraDisplay"','Fecha');
		$tr.ZS_td('class="letraDisplay" width="120px"','');
		$td.ZS_input('value="'+f+'" type="text" style="width:80px" class="Calendario norm" name="FechaRecomendacion" id="FechaRecomendacion"','');
		$Fecha=$input;
		$tr.ZS_td('class="letraDisplay"','Hora');
		$tr.ZS_td('class="letraDisplay"','');
		//$td.ZS_input('value="'+h+'" type="text" class="norm" style="width:60px" onblur="return validFormat(this,event,\'Hour\',\'\')" onkeypress="return validFormat(this,event,\'Hour\',\'\')"','');
		$td.ZS_input('value="'+h+'" type="text" class="norm" name="horarecomendacion" id="horarecomendacion" style="width:60px" onblur="return validFormat(this,event,\'Hour\',\'\')" onkeypress="return validFormat(this,event,\'Hour\',\'\')"','');
		$Hora=$input;
		$tabla.ZS_tr('');
		$tr.ZS_td('class="letraDisplay"','M&eacute;dico');
		$tr.ZS_td('class="letraDisplay" colspan="2" ','');
		$td.ZS_input('type="text" class="normDisabled" style="width:100%" readonly','<?php echo $_SESSION['user'];?>');
		$NomMedico='<?php echo $_SESSION['user'];?>';
		$CodMedico='<?php echo $_SESSION['id_user_sistema'];?>';
		$tabla.ZS_tr('');
		$tr.ZS_td('class="letraDisplay"','<b>Recomendaciones</b>');
		$tabla.ZS_tr('');
		$tr.ZS_td('class="letraDisplay" colspan="6"','');
		$td.ZS_textarea('style="width:100%" class="norm"');
		$Resumen=$textarea;


		var widget = new ModalContentZeusSalud("Recomendaciones",
				$divGral, {}, "content", "", "", null,null,eventosRecomendaciones,null,'showRecomendaciones');

		widget.imgs_data = {	
							
							close_icon:{
							src:"../../ImagenesZeus/salir 01.png", 
							_w:32, 
							_h:32, 
							_class:"icon close",
							_title:"Cerrar",
							id:"cerrar",
							fnAction:function(){
								closeModal('showRecomendaciones');
							}
							},
							
							guardar_icon:{
							src:"../../ImagenesZeus/guardar 01.png", 
							_w:32, 
							_h:32, 
							_class:"icon",
							_title:"Guardar",
							id:"guardar",
							fnAction:function(){
								var v=verificarfecharecomendacion();
								if(v==false){
								return false;	
								}
								else {
								guardarRecomendacion($Numero.val(),$Fecha.val(),$Hora.val(),$NomMedico,$CodMedico,$Resumen.val(),'<?php echo $_REQUEST["estudio"];?>');
							}
						}
							},
					 };
		widget.process();
	}

	ajaxPOST("../../controlador/HC/Ordenes_Externas.php","","respGetConsecutivo()","",$.param({operacion:"GetConsecutivoRecomendacion",Estudio:getValue("estudio")}));
}

function guardarRecomendacion($Numero,$Fecha,$Hora,$NomMedico,$CodMedico,$Resumen,$Estudio){
	respSaveRecomendacion=function(){
		var resp=JSON.parse(_ResquestAJAX);

		if($.trim(resp[0]["ERRORMSG"])==''){
			closeModal();
			swal("Se ha guardado la recomendacion correctamente.","","success");
			fnGetRecomendaciones($Estudio);
		}else{
			swal(resp[0]["ERRORMSG"],"","error");
		}
	}
	deshabilitarToolBar(true);
	ajaxPOST("../../controlador/HC/Ordenes_Externas.php","","respSaveRecomendacion()","",$.param({operacion:"SaveRecomendacion",Numero:$Numero,Fecha:$Fecha,Hora:$Hora,NomMedico:$NomMedico,CodMedico:$CodMedico,Resumen:$Resumen,Estudio:$Estudio}));	
}

function fnGetRecomendaciones($Estudio){
	var tipoOrden = $(".tactive").find("a").attr("tipo");
	$divGral=$("#divListado"+tipoOrden);
	$divGral.empty();
	$divGral.ZS_tabla('width="100%" class="intercalarColorTab"');
	
	respGetRecomendaciones=function(){
		var resp=JSON.parse(_ResquestAJAX);
		
		for(var i in resp){
			$tabla.ZS_tr('height="20px"');
			$tr.ZS_td('class="letraDisplay" width="80px"',resp[i]["Numero"]);
			$tr.ZS_td('class="letraDisplay" width="100px"',resp[i]["Fecha"]);
			$tr.ZS_td('class="letraDisplay" width="100px"',resp[i]["Estudio"]);
			$tr.ZS_td('class="letraDisplay" width="300px"',resp[i]["Medico"]);
			$tr.ZS_td('class="letraDisplay"',resp[i]["Resumen"]);
			$tr.ZS_td('class="letraDisplay" width="20px"','&nbsp;');

			$imgEliminar=$('<img src="../../Imagenes/Eliminar.png" width="13px">');
			$imgEliminar.data('Numero',resp[i]["Numero"]);
			$imgEliminar.css({cursor:'pointer'});
			$imgEliminar.click(function(){
				MotivoEliminarOrden($(this).data('Numero'),$Estudio,2);
				//deleteRecomendacion($(this).data('Numero'),$Estudio);
			});
			$tr.ZS_td('class="letraDisplay" width="20px" align="center"',$imgEliminar); 

			$imgImprimir=$('<img src="../../Imagenes/print2.png" width="15px">');
			$imgImprimir.css({cursor:'pointer'});
			$imgImprimir.attr("title",'imprimir');
			$imgImprimir.data('Numero',resp[i]["Numero"]);
			$imgImprimir.click(function(){
				invocarReporte("reporte_datos_npostqui.php?estudio="+$Estudio+"&numero="+$(this).data('Numero'));
			});
			$tr.ZS_td('class="letraDisplay" width="20px" align="center"',$imgImprimir); 
		}
		
	}

	ajaxPOST("../../controlador/HC/Ordenes_Externas.php","","respGetRecomendaciones()","",$.param({operacion:"GetRecomendaciones",Estudio:$Estudio}));	
}

function deleteRecomendacion(Numero,Estudio){
	var Motivo= $('#motivo').val();
	alertify.confirm("Seguro desea eliminar la recomendaci&oacute;n?", function (e) {
		if (e) {
			respDeleteRecomendaciones=function(){
				var resp=JSON.parse(_ResquestAJAX);

				if($.trim(resp[0]["ERRORMSG"])==''){
					closeModal();
					swal("Se ha eliminado la recomendacion correctamente.","","success");
					fnGetRecomendaciones(Estudio);
				}else{
					swal(resp[0]["ERRORMSG"],"","error");
				}
			}

			ajaxPOST("../../controlador/HC/Ordenes_Externas.php","","respDeleteRecomendaciones()","",$.param({operacion:"DeleteRecomendacion",Numero:Numero,Motivo:Motivo}));
		}
	});
}

function eventosRecomendaciones(){
	setearCalendarioBarra();
	fnGetRecomendaciones(getValue("estudio"));
}


function altp(){
  $("#cerrar_autonomo").click();
  showWindowFind('codigo,descripcion','sis_diags','','Codigo,Nombre','80,350','codigo_diag#diag','Diagn&oacute;sticos', 'actualizar2()');
}
function actualizar2() {
	$('#datosincapacidad').val($('#datosincapacidad').val()+$('#codigo_diag').val()+' - '+$('#diag').val().toUpperCase()+"\n")
	 
	$('#datosincapacidad').focus();
}

function fnShowIncapacidades(NumIncapacidad){
	
	respGetTipoIncapacidad=function(){
		var respTiposIncapacidad=JSON.parse(_ResquestAJAX);
		respDatosIncapacidad=function(){
			var respDatoIncapacidad=JSON.parse(_ResquestAJAX);

			dtNumero='';
			dtFecha='';
			dtCodTipo='';
			dtResumen=''+getValue('dxPrincipal');
			dtNomMedico='';
			dtDuracion='';
			dtCodProrroga='';
			dtIncaretro='';
			dtincapo='';
			dtcausaAtencion='';
			dtmoserv='';
			dtgrserv='';
			dtfechaproparto='';
			dtgestacional='';
			dtnumeroNacido='';
			dtnumeroCertificado='';
			dtembmult='';

			var f = new Date();
			dtFecha=f.getFullYear()+ "/"+ (f.getMonth() +1) + "/" + f.getDate();
			dtfechaproparto=f.getFullYear()+ "/"+ (f.getMonth() +1) + "/" + f.getDate();

			if(NumIncapacidad==undefined || NumIncapacidad=='undefined'){
				NumIncapacidad='';
			}else{
				dtNumero=respDatoIncapacidad[0]["Numero"];
				dtFecha=respDatoIncapacidad[0]["Fecha"];
				dtCodTipo=respDatoIncapacidad[0]["CodTipo"];
				dtResumen=respDatoIncapacidad[0]["Resumen"];
				dtNomMedico=respDatoIncapacidad[0]["NomMedico"];
				dtDuracion=respDatoIncapacidad[0]["Duracion"];
				dtCodProrroga=respDatoIncapacidad[0]["CodProrroga"];
				dtIncaretro=respDatoIncapacidad[0]["CodIncar"];
				dtincapo=respDatoIncapacidad[0]["CodIncapo"];
				dtcausaAtencion=respDatoIncapacidad[0]["causa_motivo_atencion"];
				dtmoserv=respDatoIncapacidad[0]["CodMoserv"];
				dtgrserv=respDatoIncapacidad[0]["CodGrserv"];
				dtfechaproparto=respDatoIncapacidad[0]["fecha_prob_parto"];
				dtgestacional=respDatoIncapacidad[0]["edad_gestacional"];
				dtnumeroNacido=respDatoIncapacidad[0]["numero_nacido_vivo"];
				dtnumeroCertificado=respDatoIncapacidad[0]["certificado_nacido_vivo"];
				dtembmult=respDatoIncapacidad[0]["CodEmbmult"];
					
				if (dtCodTipo == '4') {
					$(".licMaternidad").fadeIn();
				}
				
			}
			var disabled='';
			var calendario='Calendario';
			var editaFecha='<?php echo $model->getParametroGeneral("fechaIncapacidad","HISTORIA CLINICA"); ?>';
			if(editaFecha=='N'){
				disabled='disabled';
				calendario='';
			}
			$divGral=$("<div>");
			$divGral.ZS_div("");
			$div.ZS_tabla('width="700px"');
			$tabla.ZS_tr('');
			$tr.ZS_td('class="letraDisplay" width="60px"','Fecha');
			$tr.ZS_td('class="letraDisplay" width="120px"','');
			$td.ZS_input(disabled+' type="text" style="width:80px" class="'+calendario+' norm" name="FechaIncapacidad" id="FechaIncapacidad"',$.trim(dtFecha));
			$Fecha=$input;
			$tr.ZS_td('class="letraDisplay"','Duracion (Dias)');
			$tr.ZS_td('class="letraDisplay"','');
			$td.ZS_input('type="number" class="norm" style="width:60px"',$.trim(dtDuracion));
			$Duracion=$input;
			$tr.ZS_td('class="letraDisplay"','Pr&oacute;rroga');
			$tr.ZS_td('class="letraDisplay"','');
			$td.ZS_select('class="norm"',[{name:'[SELECCIONE]',value:''},{name:'No prorrogable',value:'0'},{name:'Prorrogable',value:'1'}]);
			$Prorroga=$select;

			$Prorroga.val($.trim(dtCodProrroga));

			$tabla.ZS_tr('');
			$tr.ZS_td('class="letraDisplay"','Tipo Incapacidad');
			$tr.ZS_td('class="letraDisplay" width="120px"','');

			$dataTipoIncapacidad=new Array();
			$dataTipoIncapacidad.push({name:'[SELECCIONE]',value:''});
			for(var i in respTiposIncapacidad.tinca){
				$dataTipoIncapacidad.push({name:respTiposIncapacidad.tinca[i]["desplegable"],value:respTiposIncapacidad.tinca[i]["valor"]});
			}
			

			$td.ZS_select('class="norm" style="120px"',$dataTipoIncapacidad);
			$TipoIncapacidad=$select;

			$TipoIncapacidad.val($.trim(dtCodTipo));
			
			$TipoIncapacidad.change(function() {
				if ($(this).val() == '4') {
					$(".licMaternidad").fadeIn();
				} else {
					$(".licMaternidad").fadeOut();
				}
			});

			$NomMedico='<?php echo $_SESSION['user'];?>';
			$CodMedico='<?php echo $_SESSION['id_user_sistema'];?>';

			if($.trim(NumIncapacidad)!=''){
				$NomMedico=$.trim(dtNomMedico);
			}

			$tr.ZS_td('class="letraDisplay"','Medico');
			$tr.ZS_td('class="letraDisplay"','');
			$td.ZS_input('type="text" class="normDisabled" style="width:150px"',$NomMedico);

			$tr.ZS_td('class="letraDisplay"','Incapacidad retroactiva');
			$tr.ZS_td('class="letraDisplay" width="120px"','');

			$dataincaretro=new Array();
			$dataincaretro.push({name:'No Aplica',value:0});
			for(var i in respTiposIncapacidad.incar){
				$dataincaretro.push({name:respTiposIncapacidad.incar[i]["desplegable"],value:respTiposIncapacidad.incar[i]["valor"]});
			}
			
			$td.ZS_select('class="norm" style="width:120px"',$dataincaretro);	
			$incaretro=$select;
			$incaretro.val($.trim(dtIncaretro));

			$tabla.ZS_tr('');
			$tr.ZS_td('class="letraDisplay"','Presunto origen de la incapacidad');
			$tr.ZS_td('class="letraDisplay" width="120px"','');

			$datapresorigeninca=new Array();
			$datapresorigeninca.push({name:'[SELECCIONE]',value:''});
			for(var i in respTiposIncapacidad.incapo){
				$datapresorigeninca.push({name:respTiposIncapacidad.incapo[i]["desplegable"],value:respTiposIncapacidad.incapo[i]["valor"]});
			}
			
			$td.ZS_select('class="norm" style="100px"',$datapresorigeninca);	
			$incapo=$select;
			$incapo.val($.trim(dtincapo));

			$tr.ZS_td('class="letraDisplay"','Causa que motiva la atencion');
			$tr.ZS_td('class="letraDisplay" ','');
			$td.ZS_textarea('style="width:100%" class="norm" name="causaAtencion" id="causaAtencion"',$.trim(dtcausaAtencion));
			$causaAtencion=$textarea;

			$tr.ZS_td('class="letraDisplay"','Modalidad de la prestacion del servicio');
			$tr.ZS_td('class="letraDisplay" width="120px"','');

			$datamodalprestserv=new Array();
			$datamodalprestserv.push({name:'[SELECCIONE]',value:''});
			for(var i in respTiposIncapacidad.moserv){
				$datamodalprestserv.push({name:respTiposIncapacidad.moserv[i]["desplegable"],value:respTiposIncapacidad.moserv[i]["valor"]});
			}
			
			$td.ZS_select('class="norm" style="100px"',$datamodalprestserv);	
			$moserv=$select;
			$moserv.val($.trim(dtmoserv));

			$tabla.ZS_tr('');
			$tr.ZS_td('class="letraDisplay"','Grupo de servicios');
			$tr.ZS_td('class="letraDisplay" width="120px" colspan="3"','');

			$datagruposervi=new Array();
			$datagruposervi.push({name:'[SELECCIONE]',value:''});
			for(var i in respTiposIncapacidad.grserv){
				$datagruposervi.push({name:respTiposIncapacidad.grserv[i]["desplegable"],value:respTiposIncapacidad.grserv[i]["valor"]});
			}
			
			$td.ZS_select('class="norm" style="100px"',$datagruposervi);	
			$grserv=$select;
			$grserv.val($.trim(dtgrserv));


			// -----------------Datos Lic Maternidad---------------

			$tabla.ZS_tr('class="licMaternidad" style="display: none;" ');
			$tr.ZS_td('class="letraDisplay" width="20px"','Fecha Probable del Parto');
			$tr.ZS_td('class="letraDisplay" width="120px"','');
			$td.ZS_input(' type="text" style="width:80px" class="'+calendario+' norm" name="FechaproParto" id="FechaproParto"',$.trim(dtfechaproparto));
			$FechaproParto=$input;

			$tr.ZS_td('class="letraDisplay"','Edad Gestacional(semanas)');
			$tr.ZS_td('class="letraDisplay"','');
			$td.ZS_input('type="number" class="norm" style="width:100px"',$.trim(dtgestacional));
			$gestacional=$input;

			$tr.ZS_td('class="letraDisplay"','Embarazo Multiple');
			$tr.ZS_td('class="letraDisplay" width="120px"','');

			$dataembarzomultiple=new Array();
			$dataembarzomultiple.push({name:'[SELECCIONE]',value:''});
			for(var i in respTiposIncapacidad.embmult){
				$dataembarzomultiple.push({name:respTiposIncapacidad.embmult[i]["desplegable"],value:respTiposIncapacidad.embmult[i]["valor"]});
			}
			
			$td.ZS_select('class="norm" style="100px"',$dataembarzomultiple);	
			$embmult=$select;
			$embmult.val($.trim(dtembmult));

			$tabla.ZS_tr('class="licMaternidad" style="display: none;"');
			$tr.ZS_td('class="letraDisplay" width="60px"','Numero de Nacidos Vivos');
			$tr.ZS_td('class="letraDisplay"','');
			$td.ZS_input('type="number" class="norm" style="width:100px"',$.trim(dtnumeroNacido));
			$numeroNacido=$input;


			$tr.ZS_td('class="letraDisplay" width="80px"','Numero del Certificado de Cada Nacido Vivo');
			$tr.ZS_td('class="letraDisplay"','');
			$td.ZS_input('type="text" class="norm" style="width:100px"',$.trim(dtnumeroCertificado));
			$numeroCertificado=$input;


			// -------------- 

			$tabla.ZS_tr('');
			$tr.ZS_td('class="letraDisplay" colspan="6"','<a accesskey="p" onFocus="document.f.datosincapacidad.focus()" href="javascript:altp()"></a>Presione (Alt + P) para ver los diagnosticos <input name="codigo_diag" type="hidden" id="codigo_diag" /><input name="diag" type="hidden" id="diag" />');

			$tabla.ZS_tr('');
			$tr.ZS_td('class="letraDisplay"','<b>Resumen</b>');
			$tabla.ZS_tr('');
			$tr.ZS_td('class="letraDisplay" colspan="6"','');
			$td.ZS_textarea('style="width:100%" class="norm" name="datosincapacidad" id="datosincapacidad"',$.trim(dtResumen));
			$Diagnosticos=$textarea;


			var widget = new ModalContentZeusSalud("Incapacidades",
					$divGral, {}, "content", "", "", null,null,eventosIncapacidad,null,'showIncapacidad');

			widget.imgs_data = {	
								
								close_icon:{
								src:"../../ImagenesZeus/salir 01.png", 
								_w:32, 
								_h:32, 
								_class:"icon close",
								_title:"Cerrar",
								id:"cerrar",
								fnAction:function(){
									closeModal('showRecomendaciones');
								}
								},
								
								guardar_icon:{
								src:"../../ImagenesZeus/guardar 01.png", 
								_w:32, 
								_h:32, 
								_class:"icon",
								_title:"Guardar",
								id:"guardar",
								fnAction:function(){
									var v=verificarincapacidad();
								if(v==false){
									return false;
								}
								else{
									fnGuardarIncapacidad(NumIncapacidad,$Fecha.val(),$Duracion.val(),$Prorroga.val(),$TipoIncapacidad.val(),$Diagnosticos.val(),'<?php echo $_REQUEST["estudio"];?>',$incaretro.val(),$incapo.val(),$causaAtencion.val(),$moserv.val(),$grserv.val(),$FechaproParto.val(),$gestacional.val(),$embmult.val(),$numeroNacido.val(),$numeroCertificado.val());
								}
							}
								},
						 };
			widget.process();
		}

		ajaxPOST("../../controlador/HC/Ordenes_Externas.php","","respDatosIncapacidad()","",$.param({operacion:"GetIncapacidad",Estudio:getValue("estudio"),Numero:NumIncapacidad}));
	}

	ajaxPOST("../../controlador/HC/Ordenes_Externas.php","","respGetTipoIncapacidad()","",$.param({operacion:"GetTipoIncapacidad",Estudio:getValue("estudio")}));
}

function fnGuardarIncapacidad(NumIncapacidad,Fecha,Duracion,Prorroga,TipoIncapacidad,Diagnosticos,$Estudio,incaretro,incapo,causaAtencion,moserv,grserv,FechaproParto,gestacional,embmult,numeroNacido,numeroCertificado){
	if($.trim(TipoIncapacidad) != 4 && $.trim(Duracion) > 30){
		swal("La Incapacidad no puede ser superior 30 dias","","error");
		return false;
	}

	if($.trim(Fecha)==''){
		swal("Seleccione la fecha","","error");
		return false;
	}else if($.trim(Duracion)==''){
		swal("Ingrese la duracion de la incapacidad","","error");
		return false;
	}else if($.trim(Duracion) < 1){
		swal("La incapacidad debe ser mayor o igual a  1","","error");
		return false;
	}else if($.trim(Prorroga)==''){
		swal("Seleccione si es prorrogable o no","","error");
		return false;
	}else if($.trim(TipoIncapacidad)==''){
		swal("Seleccione el tipo de incapacidad","","error");
		return false;
	}else if($.trim(incaretro)==''){
		swal("Seleccione Incapacidad retroactiva","","error");
		return false;
	}else if($.trim(incapo)==''){
		swal("Seleccione Presunto origen de la incapacidad","","error");
		return false;
	}else if($.trim(causaAtencion)==''){
		swal("Ingrese la Causa que motiva la atencion","","error");
		return false;
	}else if($.trim(moserv)==''){
		swal("Seleccione Modalidad de la prestacion del servicio","","error");
		return false;
	}else if($.trim(grserv)==''){
		swal("Seleccione el Grupo de servicios","","error");
		return false;
	}else if($.trim(Diagnosticos)==''){
		swal("Ingrese los diagnosticos","","error");
		return false;
	}

	if($.trim(TipoIncapacidad) == 4){
		if($.trim(FechaproParto)==''){
			swal("Ingrese Fecha Probable del Parto","","error");
			return false;
		}else if($.trim(gestacional)==''){
			swal("Ingrese Edad Gestacional","","error");
			return false;
		}else if($.trim(gestacional) < 1){
			swal("La edad gestacional no debe ser valor negativo","","error");
			return false;
		}else if($.trim(embmult)==''){
			swal("Seleccione Embarazo Multiple","","error");
			return false;
		}else if($.trim(numeroNacido)==''){
			swal("Ingrese Numero de Nacidos Vivos","","error");
			return false;
		}else if($.trim(numeroNacido) < 1){
			swal("El Numero Nacido Vivo debe ser mayor a 0","","error");
			return false;
		}else if($.trim(numeroCertificado)==''){
			swal("Ingrese Numero del Certificado de Cada Nacido Vivo","","error");
			return false;
		}
	}

	

	respSaveIncapacidad=function(){
		var resp=JSON.parse(_ResquestAJAX);

		if($.trim(resp[0]["ERRORMSG"])==''){
			closeModal();
			swal("Se ha guardado la incapacidad correctamente.","","success");
			fnGetIncapacidades($Estudio);
		}else{
			swal(resp[0]["ERRORMSG"],"","error");
		}
	}
	deshabilitarToolBar(true);
	ajaxPOST("../../controlador/HC/Ordenes_Externas.php","","respSaveIncapacidad()","",$.param({operacion:"SaveIncapacidad",NumIncapacidad:NumIncapacidad,Fecha:Fecha,Duracion:Duracion,Prorroga:Prorroga,TipoIncapacidad:TipoIncapacidad,Diagnosticos:Diagnosticos,Estudio:getValue("estudio"),incaretro:incaretro,incapo:incapo,causaAtencion:causaAtencion,moserv:moserv,grserv:grserv,FechaproParto:FechaproParto,gestacional:gestacional,embmult:embmult,numeroNacido:numeroNacido,numeroCertificado:numeroCertificado}));
}
//OLD
// function fnShowIncapacidades(NumIncapacidad){
	
// 	respGetTipoIncapacidad=function(){
// 		var respTiposIncapacidad=JSON.parse(_ResquestAJAX);

// 		respDatosIncapacidad=function(){
// 			var respDatoIncapacidad=JSON.parse(_ResquestAJAX);

// 			dtNumero='';
// 			dtFecha='';
// 			dtCodTipo='';
// 			dtResumen=''+getValue('dxPrincipal');
// 			dtNomMedico='';
// 			dtDuracion='';
// 			dtCodProrroga='';

// 			var f = new Date();
// 			dtFecha=f.getFullYear()+ "/"+ (f.getMonth() +1) + "/" + f.getDate();

// 			if(NumIncapacidad==undefined || NumIncapacidad=='undefined'){
// 				NumIncapacidad='';
// 			}else{
// 				dtNumero=respDatoIncapacidad[0]["Numero"];
// 				dtFecha=respDatoIncapacidad[0]["Fecha"];
// 				dtCodTipo=respDatoIncapacidad[0]["CodTipo"];
// 				dtResumen=respDatoIncapacidad[0]["Resumen"];
// 				dtNomMedico=respDatoIncapacidad[0]["NomMedico"];
// 				dtDuracion=respDatoIncapacidad[0]["Duracion"];
// 				dtCodProrroga=respDatoIncapacidad[0]["CodProrroga"];
// 			}
// 			var disabled='';
// 			var calendario='Calendario';
// 			var editaFecha='<?php echo $model->getParametroGeneral("fechaIncapacidad","HISTORIA CLINICA"); ?>';
// 			if(editaFecha=='N'){
// 				disabled='disabled';
// 				calendario='';
// 			}
// 			$divGral=$("<div>");
// 			$divGral.ZS_div("");
// 			$div.ZS_tabla('width="700px"');
// 			$tabla.ZS_tr('');
// 			$tr.ZS_td('class="letraDisplay"','Fecha');
// 			$tr.ZS_td('class="letraDisplay" width="120px"','');
// 			$td.ZS_input(disabled+' type="text" style="width:80px" class="'+calendario+' norm" name="FechaIncapacidad" id="FechaIncapacidad"',$.trim(dtFecha));
// 			$Fecha=$input;
// 			$tr.ZS_td('class="letraDisplay"','Duracion (Dias)');
// 			$tr.ZS_td('class="letraDisplay"','');
// 			$td.ZS_input('type="text" class="norm" style="width:60px"',$.trim(dtDuracion));
// 			$Duracion=$input;
// 			$tr.ZS_td('class="letraDisplay"','Pr&oacute;rroga');
// 			$tr.ZS_td('class="letraDisplay"','');
// 			$td.ZS_select('class="norm"',[{name:'[SELECCIONE]',value:''},{name:'No prorrogable',value:'0'},{name:'Prorrogable',value:'1'}]);
// 			$Prorroga=$select;

// 			$Prorroga.val($.trim(dtCodProrroga));

// 			$tabla.ZS_tr('');
// 			$tr.ZS_td('class="letraDisplay"','Tipo Incapacidad');
// 			$tr.ZS_td('class="letraDisplay" width="120px"','');

// 			$dataTipoIncapacidad=new Array();
// 			$dataTipoIncapacidad.push({name:'[SELECCIONE]',value:''});
// 			for(var i in respTiposIncapacidad){
// 				$dataTipoIncapacidad.push({name:respTiposIncapacidad[i]["desplegable"],value:respTiposIncapacidad[i]["valor"]});
// 			}
			

// 			$td.ZS_select('class="norm" style="120px"',$dataTipoIncapacidad);
// 			$TipoIncapacidad=$select;

// 			$TipoIncapacidad.val($.trim(dtCodTipo));

// 			$NomMedico='<?php echo $_SESSION['user'];?>';
// 			$CodMedico='<?php echo $_SESSION['id_user_sistema'];?>';

// 			if($.trim(NumIncapacidad)!=''){
// 				$NomMedico=$.trim(dtNomMedico);
// 			}

// 			$tr.ZS_td('class="letraDisplay"','Medico');
// 			$tr.ZS_td('class="letraDisplay"','');
// 			$td.ZS_input('type="text" class="normDisabled" style="width:120px"',$NomMedico);

// 			$tabla.ZS_tr('');
// 			$tr.ZS_td('class="letraDisplay"','<a accesskey="p" onFocus="document.f.datosincapacidad.focus()" href="javascript:altp()"></a>Presione (Alt + P) para ver los diagnosticos <input name="codigo_diag" type="hidden" id="codigo_diag" /><input name="diag" type="hidden" id="diag" />');

// 			$tabla.ZS_tr('');
// 			$tr.ZS_td('class="letraDisplay"','<b>Resumen</b>');
// 			$tabla.ZS_tr('');
// 			$tr.ZS_td('class="letraDisplay" colspan="6"','');
// 			$td.ZS_textarea('style="width:100%" class="norm" name="datosincapacidad" id="datosincapacidad"',$.trim(dtResumen));
// 			$Diagnosticos=$textarea;


// 			var widget = new ModalContentZeusSalud("Incapacidades",
// 					$divGral, {}, "content", "", "", null,null,eventosIncapacidad,null,'showIncapacidad');

// 			widget.imgs_data = {	
								
// 								close_icon:{
// 								src:"../../ImagenesZeus/salir 01.png", 
// 								_w:32, 
// 								_h:32, 
// 								_class:"icon close",
// 								_title:"Cerrar",
// 								id:"cerrar",
// 								fnAction:function(){
// 									closeModal('showRecomendaciones');
// 								}
// 								},
								
// 								guardar_icon:{
// 								src:"../../ImagenesZeus/guardar 01.png", 
// 								_w:32, 
// 								_h:32, 
// 								_class:"icon",
// 								_title:"Guardar",
// 								id:"guardar",
// 								fnAction:function(){
// 									var v=verificarincapacidad();
// 								if(v==false){
// 									return false;
// 								}
// 								else{
// 									fnGuardarIncapacidad(NumIncapacidad,$Fecha.val(),$Duracion.val(),$Prorroga.val(),$TipoIncapacidad.val(),$Diagnosticos.val(),'<?php echo $_REQUEST["estudio"];?>');
// 								}
// 							}
// 								},
// 						 };
// 			widget.process();
// 		}

// 		ajaxPOST("../../controlador/HC/Ordenes_Externas.php","","respDatosIncapacidad()","",$.param({operacion:"GetIncapacidad",Estudio:getValue("estudio"),Numero:NumIncapacidad}));
// 	}

// 	ajaxPOST("../../controlador/HC/Ordenes_Externas.php","","respGetTipoIncapacidad()","",$.param({operacion:"GetTipoIncapacidad",Estudio:getValue("estudio")}));
// }


// function fnGuardarIncapacidad(NumIncapacidad,Fecha,Duracion,Prorroga,TipoIncapacidad,Diagnosticos,$Estudio){
// 	if($.trim(Fecha)==''){
// 		swal("Seleccione la fecha","","error");
// 		return false;
// 	}else if($.trim(Duracion)==''){
// 		swal("Ingrese la duracion de la incapacidad","","error");
// 		return false;
// 	}else if($.trim(Prorroga)==''){
// 		swal("Seleccione si es prorrogable o no","","error");
// 		return false;
// 	}else if($.trim(TipoIncapacidad)==''){
// 		swal("Seleccione el tipo de incapacidad","","error");
// 		return false;
// 	}else if($.trim(Diagnosticos)==''){
// 		swal("Ingrese los diagnosticos","","error");
// 		return false;
// 	}

// 	respSaveIncapacidad=function(){
// 		var resp=JSON.parse(_ResquestAJAX);

// 		if($.trim(resp[0]["ERRORMSG"])==''){
// 			closeModal();
// 			swal("Se ha guardado la incapacidad correctamente.","","success");
// 			fnGetIncapacidades($Estudio);
// 		}else{
// 			swal(resp[0]["ERRORMSG"],"","error");
// 		}
// 	}
// 	deshabilitarToolBar(true);
// 	ajaxPOST("../../controlador/HC/Ordenes_Externas.php","","respSaveIncapacidad()","",$.param({operacion:"SaveIncapacidad",NumIncapacidad:NumIncapacidad,Fecha:Fecha,Duracion:Duracion,Prorroga:Prorroga,TipoIncapacidad:TipoIncapacidad,Diagnosticos:Diagnosticos,Estudio:getValue("estudio")}));
// }

$_EditarIncapacidad=false;
function eventosIncapacidad(){
	setearCalendarioBarra();
	if(!$_EditarIncapacidad){
		fnGetIncapacidades(getValue("estudio"));
	}
	$_EditarIncapacidad=false;
}

function fnGetIncapacidades($Estudio){
	
	
	
	respGetIncapacidades=function(){
		var tipoOrden = $(".tactive").find("a").attr("tipo");
		$divGral=$("#divListado"+tipoOrden);
		$divGral.empty();
		$divGral.ZS_tabla('width="100%" class="intercalarColorTab"');
		
		var resp=JSON.parse(_ResquestAJAX);
		
		for(var i in resp){
			$tabla.ZS_tr('height="20px"');
			$tr.ZS_td('class="letraDisplay" align="center" width="80px"',resp[i]["Numero"]);
			$tr.ZS_td('class="letraDisplay" align="center" width="100px"',resp[i]["Fecha"]);
			$tr.ZS_td('class="letraDisplay" align="center" width="100px"',resp[i]["Estudio"]);
			$tr.ZS_td('class="letraDisplay" width="300px"',resp[i]["NomMedico"]);
			$tr.ZS_td('class="letraDisplay" width="100px"',resp[i]["Tipo"]);
			$tr.ZS_td('class="letraDisplay" align="center" width="100px"',resp[i]["Duracion"]);
			$tr.ZS_td('class="letraDisplay" align="center" width="100px"',resp[i]["Prorroga"]);
			$tr.ZS_td('class="letraDisplay"',resp[i]["Resumen"]);
			
			$imgEditar=$('<img src="../../Imagenes/errorpages.gif" width="13px">');
			$imgEditar.data('Numero',resp[i]["Numero"]);
			$imgEditar.css({cursor:'pointer'});
			$imgEditar.click(function(){
				$_EditarIncapacidad=true;
				fnShowIncapacidades($(this).data('Numero'));
			});
			if(resp[i]["PuedeModificar"]!=1){
				$imgEditar='';
			}
			$tr.ZS_td('class="letraDisplay" width="20px" align="center"',$imgEditar); 

			$imgEliminar=$('<img src="../../Imagenes/Eliminar.png" width="13px">');
			$imgEliminar.data('Numero',resp[i]["Numero"]);
			$imgEliminar.css({cursor:'pointer'});
			$imgEliminar.attr("title","Eliminar");
			$imgEliminar.click(function(){
				MotivoEliminarOrden($(this).data('Numero'),$Estudio,1);
				//deleteIncapacidad($(this).data('Numero'),$Estudio);
			});
			if(resp[i]["PuedeModificar"]!=1){
				$imgEliminar='';
			}
			$tr.ZS_td('class="letraDisplay" width="20px" align="center"',$imgEliminar); 

			$imgImprimir=$('<img src="../../Imagenes/print2.png" width="15px">');
			$imgImprimir.css({cursor:'pointer'});
			$imgImprimir.attr("title",'imprimir');
			$imgImprimir.data('Numero',resp[i]["Numero"]);
			$imgImprimir.click(function(){
				invocarReporte("reporte_datos_incapacidad.php?estudio="+$Estudio+"&numero="+$(this).data('Numero'));
			});
			$tr.ZS_td('class="letraDisplay" width="20px" align="center"',$imgImprimir); 
		}
		
	}

	ajaxPOST("../../controlador/HC/Ordenes_Externas.php","","respGetIncapacidades()","",$.param({operacion:"GetIncapacidades",Estudio:$Estudio}));
}

function MotivoEliminarSolicitud(numeroOrden,hasCodGrupo){
	var $div = $(document.createElement("div"));				
    
	var $text = $(document.createElement("textarea"));
	$text.attr("id", "motivo");
	$text.attr("name", "motivo");
	$text.addClass("norm");
	$text.css({width:"800px", height:"100px", textTransform:"uppercase"});
	$text.appendTo($div);
	
	var w = new ModalContentZeusSalud("Motivo de la anulacion", $div, {}, "content", "", null, null, null,function(){$("#motivoNota").focus()},true, 'motivoAnularSolicitud');
	var icons = {	revertir_icon:{
			src:"../../ImagenesZeus/guardar 01.png",
			_w:32,
			_h:32,
			_class:"icon save",
			_title:"Anular",
			id:"revertirBtn",
				fnAction: function (){
					EliminarSolicitud(numeroOrden,hasCodGrupo);
				
				} // Boton guardar
			}
		}
	$.extend(w.imgs_data, icons);
	w.process();
}

function MotivoEliminarOrden(Numero,Estudio,TipoOrden){
	var $div = $(document.createElement("div"));				
    
	var $text = $(document.createElement("textarea"));
	$text.attr("id", "motivo");
	$text.attr("name", "motivo");
	$text.addClass("norm");
	$text.css({width:"800px", height:"100px", textTransform:"uppercase"});
	$text.appendTo($div);
	
	var w = new ModalContentZeusSalud("Motivo de la anulacion", $div, {}, "content", "", null, null, null,function(){$("#motivoNota").focus()},true, 'motivoAnularFormato');
	var icons = {	revertir_icon:{
			src:"../../ImagenesZeus/guardar 01.png",
			_w:32,
			_h:32,
			_class:"icon save",
			_title:"Anular",
			id:"revertirBtn",
				fnAction: function (){
					switch (TipoOrden) {
						case 1:
							deleteIncapacidad(Numero,Estudio)
							break;
						case 2:
							deleteRecomendacion(Numero,Estudio);
							break;
						case 3: 
							deleteFormulaMedica(Numero,Estudio);
							break;
						default:
							break;
					}
				
				} // Boton guardar
			}
		}
	$.extend(w.imgs_data, icons);
	w.process();
}

function deleteIncapacidad(Numero,Estudio){
	var Motivo= $('#motivo').val();
	// alert(2);
	alertify.confirm("Seguro desea eliminar la incapacidad?", function (e) {
		if (e) {
			respDeleteInapacidad=function(){
				var resp=JSON.parse(_ResquestAJAX);

				if($.trim(resp[0]["ERRORMSG"])==''){
					closeModal();
					swal("Se ha eliminado la incapacidad correctamente.","","success");
					fnGetIncapacidades(Estudio);
				}else{
					swalr(resp[0]["ERRORMSG"],"","error");
				}
			}

			ajaxPOST("../../controlador/HC/Ordenes_Externas.php","","respDeleteInapacidad()","",$.param({operacion:"DeleteIncapacidad",Numero:Numero,Motivo:Motivo}));
		}
	});
}

function fnShowFormulaMedica(Numero){
	
	respDatosFormulaMedica=function(){
		var respDatoIncapacidad=JSON.parse(_ResquestAJAX);
		$divGral = '<h2>HOLA MUNDO</h2>';

		var widget = new ModalContentZeusSalud("Formula Medica",
		"nuevaOrdenFormulaMedica.php", 
		{numero:Numero,esMontoPcte:getValue('esMontoPcte'),estudio:getValue("estudio"), tipo_orden:'exm'
		,estado_his: '<?php echo($_REQUEST["estado_his"]); ?>',sexo: '<?php echo($_REQUEST["sexo"]); ?>'
		,naci: '<?php echo($_REQUEST["naci"]); ?>',embarazo: '<?php echo($_REQUEST["embarazo"]); ?>'
		,rn: '<?php echo($_REQUEST["rn"]); ?>'}
		, "general", "", "", null,null,eventosFormulasMedicas,null,'showFormulaMedica');

		widget.imgs_data = {	
							
							close_icon:{
							src:"../../ImagenesZeus/salir 01.png", 
							_w:32, 
							_h:32, 
							_class:"icon close",
							_title:"Cerrar",
							id:"cerrar",
							fnAction:function(){
								closeModal('showFormulaMedica');
							}
							},
							
							guardar_icon:{
							src:"../../ImagenesZeus/guardar 01.png", 
							_w:32, 
							_h:32, 
							_class:"icon",
							_title:"Guardar",
							id:"guardar",
							fnAction:function(){
								var v=verificarfechaformula();
								if(v==false){
									return false;
								}
								else{
								fnGuardarFormulaMedica(Numero,$("#medico").val(),$("#codigo_medico").val(),
								$("#fecha").val(),$("#hora").val(),$("#desc_orden").val(),getValue("estudio"));
							}
						}
							},
							imprimir_icon:{
								src:"../../ImagenesZeus/imprimir 01.png", 
								_w:32, 
								_h:32, 
								_class:"icon",
								_title:"Imprimir Formula",
								id:"imprimir",
								fnAction:function(){
									imprimirFormula();
								}
							}
						};
		widget.process();
	}

	ajaxPOST("../../controlador/HC/Ordenes_Externas.php","","respDatosFormulaMedica()","",$.param({operacion:"GetFormulaMedica",Estudio:getValue("estudio"),Numero:Numero}));
}

function imprimirFormula() {
	numero = $("#numero").val();
	if(numero!="" && numero != undefined){
		invocarReporte('reporte_datos_orden.php?estudio='+getValue("estudio")+'&numero='+numero+'&tipo_orden=exm');
	}else{
		swal("Debe guardar la orden para poder visualizar la impresion.","","error");
	}
}

function fnGuardarFormulaMedica(Numero, Medico, CodMedico, Fecha, Hora, Descripcion, $Estudio){
	if($.trim(Fecha)==''){
		swal("Seleccione la fecha","","error");
		return false;
	}else if($.trim(Hora)==''){
		swal("Ingrese la Hora","","error");
		return false;
	}else if($.trim(CodMedico)==''){
		swal("No existe medico para la Formula","","error");
		return false;
	}else if($.trim(Descripcion)==''){
		swal("Ingrese la descripcion de la formula","","error");
		return false;
	}

	respSaveFormulaMedica=function(){
		var resp=JSON.parse(_ResquestAJAX);

		if($.trim(resp.error)==false){
			closeModal();
			$("#numero").val(resp.numero);
			swal("Se ha guardado la Formula correctamente.","","sucess");
			fnGetFormulasMedicas($Estudio);
		}else{
			swal(resp.mensaje,"","error");
		}
	}

	ajaxPOST("../../controlador/HC/Ordenes_Externas.php","","respSaveFormulaMedica()","",$.param({operacion:"GuardarFormulaMedica",numero:Numero,fecha:Fecha,hora:Hora,codigo_medico:CodMedico,medico:Medico,desc_orden:Descripcion, tipo_orden:'exm',estudio:getValue("estudio")}));
}

function eventosFormulasMedicas(){
	setearCalendarioBarra();
	fnGetFormulasMedicas(getValue("estudio"));
}

function fnGetFormulasMedicas($Estudio){
	
	respGetFormulasMedicas=function(){
		var tipoOrden = $(".tactive").find("a").attr("tipo");
		$divGral=$("#divListado"+tipoOrden);
		$divGral.empty();
		$divGral.ZS_tabla('width="100%" class="intercalarColorTab"');
		console.log(tipoOrden);
		var resp=JSON.parse(_ResquestAJAX);
		console.log(resp);
		for(var i in resp){
			$tabla.ZS_tr('height="20px"');
			$tr.ZS_td('class="letraDisplay" align="center" width="70"',resp[i]["numero"]);
			$tr.ZS_td('class="letraDisplay" align="center" width="70"',resp[i]["estudio"]);
			$tr.ZS_td('class="letraDisplay" align="center" width="110"',resp[i]["fecha"]);
			$tr.ZS_td('class="letraDisplay" width="70"',resp[i]["hora"]);
			$tr.ZS_td('class="letraDisplay" width="250"',resp[i]["medico"]);
			$tr.ZS_td('class="letraDisplay" align="center" width="350"',resp[i]["ResumenOrden"]);
			$tr.ZS_td('class="letraDisplay" align="center" width="70"',"Formula");
			
			if('<?php echo($_REQUEST["estado_his"]); ?>' == 'A' && resp[i]["es_propietario"] == 1){
				$imgEditar=$('<img src="../../Imagenes/errorpages.gif" width="13px">');
				$imgEditar.data('Numero',resp[i]["numero"]);
				$imgEditar.css({cursor:'pointer'});
				$imgEditar.click(function(){
					$_EditarIncapacidad=true;
					fnShowFormulaMedica($(this).data('Numero'));
				});
			}else{
				$imgEditar='';
			}
			// if(resp[i]["PuedeModificar"]!=1){
			// 	$imgEditar='';
			// }
			$tr.ZS_td('class="letraDisplay" width="20px" align="center"',$imgEditar); 


			$imgImprimir=$('<img src="../../Imagenes/print2.png" width="15px">');
			$imgImprimir.css({cursor:'pointer'});
			$imgImprimir.attr("title",'imprimir');
			$imgImprimir.data('Numero',resp[i]["numero"]);
			$imgImprimir.data('Medico',resp[i]["cod_med"]);
			$imgImprimir.data('Ingreso',resp[i]["ingreso"]);
			$imgImprimir.data('Fecha',resp[i]["fecha"]);
			$imgImprimir.click(function(){
				invocarReporte("reporte_datos_orden.php?estudio="+resp[i]['estudio']+"&numero="+resp[i]['numero']+"&tipo_orden=exm", "");
				//invocarReporte("reporte_ordenamiento_medico_por_fecha.php?medico="+$(this).data('Medico')+"&ingreso="+$(this).data('Ingreso')+"&fecha="+$(this).data('Fecha')+"&numero="+$(this).data('Numero'), "hc");
			});
			$tr.ZS_td('class="letraDisplay" width="20px" align="center"',$imgImprimir); 

			if('<?php echo($_REQUEST["estado_his"]); ?>' == 'A' && resp[i]["es_propietario"] == 1){
				$imgEliminar=$('<img src="../../Imagenes/Eliminar.png" width="13px">');
				$imgEliminar.data('Numero',resp[i]["numero"]);
				$imgEliminar.css({cursor:'pointer'});
				$imgEliminar.attr("title","Eliminar");
				$imgEliminar.click(function(){
					MotivoEliminarOrden($(this).data('Numero'),$Estudio,3);
					//deleteFormulaMedica($(this).data('Numero'),$Estudio);
				});
			} else {
				$imgEliminar='';
			}
			$tr.ZS_td('class="letraDisplay" width="20px" align="center"',$imgEliminar); 
			$tr.ZS_td('class="letraDisplay" align="center"',"&nbsp;");
		}
		
	}

	ajaxPOST("../../controlador/HC/Ordenes_Externas.php","","respGetFormulasMedicas()","",$.param({operacion:"GetFormulasMedicas",Estudio:$Estudio}));
}

function deleteFormulaMedica(Numero,Estudio){
	var Motivo= $('#motivo').val();
	alertify.confirm("Seguro desea eliminar la Formula Medica?", function (e) {
		if (e) {
			respDeleteFormulaMedica=function(){
				var resp=JSON.parse(_ResquestAJAX);
				console.log(resp);
				if(resp.error == false){
					closeModal();
					swal("Se ha eliminado la Formula Medica correctamente.","","success");
					fnGetFormulasMedicas(Estudio);
				}else{
					swal(resp.mensaje,"","info");
				}
			}

			ajaxPOST("../../controlador/HC/Ordenes_Externas.php","","respDeleteFormulaMedica()","",$.param({operacion:"EliminarFormulaMedica",Numero:Numero,Motivo:Motivo}));
		}
	});
}

function actualizar(tipo) {
	//document.f.datosordext.value = (document.f.datosordext.value + document.f.proc.value).toUpperCase();
	tipo = tipo || 1;
	if(tipo == 1){
		document.fFormula.desc_orden.value += (document.fFormula.proc.value +" - "+ document.fFormula.proc2.value).toUpperCase()+"\n";
	}else{
		document.fFormula.desc_orden.value += document.fFormula.proc2.value.toUpperCase()+"\n";
	}
	document.fFormula.desc_orden.focus();
}

function setAutocomplete(){
	/*var j = jQuery.noConflict();
	
	j("#nomprocedimiento").autocomplete({
		urlOrData:"../../controlador/HC/Ordenes_Externas.php?operacion=getProcedimientos&tipoManual="+getValue("tipoManual"),
		ids:"codprocedimiento#nomprocedimiento",						
			
		label_desc:"info",
		callBackFn:function(){		
			//buscarPacientes();							
		}
	});	*/	
}


function edit_items_cargado(i,orden){
	
	
	
	var tipo = $(".tactive").find("a").attr("tipo");
	deshabilitarToolBar(true);
	ajaxPOST("../../controlador/HC/Ordenes_Externas.php","divItems","habilitarToolBar(true);$(this).ZS_set_referencia();","","operacion=remove_items_med_cargado&item="+i+"&orden="+orden);
	
}

function buscares(){
	showWindowFind('id,nombre','sis_es',"",'Codigo,Nombre','80,350','cod#nom','es', '');
	
}
function buscarServicios(){
	if($.trim($("#nroOrden").val())!=''){
		swal("No se puede modificar esta solicitud","","error");
		return false;
	}
	
	var selected_tab = $(".tactive").find("a").attr("tipo");
	
	if(getValue('esMontoPcte')=="1"){
		showWindowFind('st.fuente,st.nombre','Agrupar a,Agrupar_detalle ad,sis_tipo st,sis_maes sm',"ad.tipo='servicios' AND ad.Contrato|sm.contrato and sm.con_Estudio|"+getValue("estudio")+" And a.codigo|ad.codigo_grupo AND st.fuente|a.tipo_grupo group by st.fuente,st.nombre",'Codigo,Nombre','80,350','codservicio#nomservicio','Servicios', '');
		
	}else{
		showWindowFind('fuente,nombre','sis_tipo',"concepto = !"+selected_tab+"!",'Codigo,Nombre','80,350','codservicio#nomservicio','Servicios', '');
	}
}

function buscarProcs(){
	var tipoOrden = $(".tactive").find("a").attr("tipo");
	if(tipoOrden!='01'){		
		var $selected_tab = $(".tipoOrdenSeleccionado").attr("tipo");
		if($.trim($selected_tab)==''){
			swal("Seleccione un tipo de procedimientos.","","error");
			return false;
		}
	}else{
		$selected_tab='01';
	}
	
	if($(".servicioSeleccionado").length==0){
		swal("Seleccione un servicio","",'error');
		return false;
	}
	var servicioSeleccionado=$(".servicioSeleccionado").data('ServicioLey');
	
	$("#cantidad").val(1);
	if($.trim(servicioSeleccionado)==''){
		//showWindowFind("cups,codigo,nombreve",'sis_proc','tipo = !'+getValue("tipoManual")+'!','Cups,Codigo,Nombre','80,80,350','#codprocedimiento#nomprocedimiento','Procedimientos', 'nextFocus("cantidad")');
		
		showWinBuscarProcs("","codprocedimiento","nomprocedimiento",
		{operacion:"BuscarProcedimientos",TipoManual:getValue("tipoManual")},"cantidad");
			
	}else{
		
		/*showWindowFind("sp.codigo,sp.nombreve,sp.cups",'dbo.sis_proc_servicios sps,dbo.sis_proc sp',
					   'sps.Servicio=!'+servicioSeleccionado+'! '+(($("#codespecialidad").val()!='')?' AND sps.Especialidad='+$("#codespecialidad").val()+' ':'')+' AND sps.TipoManual = !'+getValue("tipoManual")+'! AND sps.TipoManual=sp.tipo AND ((sp.cups BETWEEN sps.CodigoInicial AND sps.CodigoFinal) OR (sp.cups BETWEEN sps.CodigoFinal AND sps.CodigoInicial)) AND sps.Estado=1 group by sp.cups,sp.codigo,sp.nombreve','Codigo,Nombre,Cups','80,350,80','codprocedimiento#nomprocedimiento','Procedimientos', 'nextFocus("cantidad")');*/
					   
	showWinBuscarProcs("","codprocedimiento","nomprocedimiento",
	{operacion:"BuscarProcedimientosXServicio",TipoManual:getValue("tipoManual"),
		ServicioSeleccionado:servicioSeleccionado,CodEspecialidad:$("#codespecialidad").val()},
		"cantidad");


	}
}

function buscarProcs2(soloAC=0){
	var tipoOrden = $(".tactive").find("a").attr("tipo");


	if(tipoOrden!='01'){		
		// var $selected_tab = $(".tipoOrdenSeleccionado").attr("tipo");
		// if($.trim($selected_tab)==''){
		// 	notify("Seleccione un tipo de procedimientos.","error");
		// 	return false;
		// }
	}else{
		$selected_tab='01';
	}
	
	// if($(".servicioSeleccionado").length==0){
	// 	notify("Seleccione un servicio",'error');
	// 	return false;
	// }
	// var servicioSeleccionado=$(".servicioSeleccionado").data('ServicioLey');
	
	$("#cantidad").val(1);
	// if($.trim(servicioSeleccionado)==''){		
		showWinBuscarProcs("","codprocedimiento","nomprocedimiento",{operacion:"BuscarProcedimientos",TipoManual:getValue("tipoManual"),soloAC:soloAC},"cantidad");			
	// }else{		   
		// showWinBuscarProcs("","codprocedimiento","nomprocedimiento",{operacion:"BuscarProcedimientosXServicio",TipoManual:getValue("tipoManual"),ServicioSeleccionado:servicioSeleccionado,CodEspecialidad:$("#codespecialidad").val()},"cantidad");
	// }
}

function addItem(tipoOrdenSel){
	
	var codProcedimiento=$("#codprocedimiento").val();
	var nomProcedimiento=$("#nomprocedimiento").val();
	var cantidad=$("#cantidad").val();
	var tipoManual=$("#tipoManual").val();
	var tipo = $(".tactive").find("a").attr("tipo");
	var nomespecialidad=$("#nomespecialidad").val();
	var CodEspecialidad=$("#codespecialidad").val();


	var ctc= $("#ctc").val();
	if(tipo=='01'){
		var tipoOrdenSeleccionado= '01';
	}else{
		var tipoOrdenSeleccionado= $(".tipoOrdenSeleccionado").attr("tipo");
	}
	if($.trim(tipoOrdenSel)!=''){
		tipoOrdenSeleccionado=tipoOrdenSel;
	}
	
	if($.trim(tipoOrdenSeleccionado)==''){
		tipoOrdenSeleccionado=$_tipoOrdenExt;
		$_tipoOrdenExt='';
	}
	
	var codservicio=$("#codservicio").val();
	
	
		
	if($.trim($("#nroOrden").val())!=''){
		swal("No se puede modificar esta solicitud","","error");
		return false;
	}
	
	if($.trim(codservicio)==''){
		swal("Seleccione un servicio.","","error");
		return false;
	}else if($.trim(codProcedimiento)==''){
		swal("Seleccione un procedimiento.","","error");
		buscarProcs();
		return false;
	}else if($.trim(cantidad)==''){
		swal("Ingrese la cantidad.","","error");
		return false;
	}else{
		deshabilitarToolBar(true);
		ajaxPOST("../../controlador/HC/Ordenes_Externas.php","divItems","habilitarToolBar(true);existeItem();","","operacion=addItem&estudio=<?php echo $_REQUEST["estudio"];?>&codProcedimiento="+codProcedimiento+"&cantidad="+cantidad+"&tipoManual="+tipoManual+"&nomProcedimiento="+nomProcedimiento+"&tipo="+tipo+"&ctc="+ctc+"&codservicio="+codservicio+"&tipoOrdenSeleccionado="+tipoOrdenSeleccionado+"&NroItemMed="+$("#NroItemMed").val()+"&NroItemProc="+$("#NroItemProc").val()+"&CodEspecialidad="+CodEspecialidad+"&NomEspecialidad="+nomespecialidad);
		$("#codprocedimiento").val("");
		$("#nomprocedimiento").val("");
		if(tipo!='04'){
		$("#cantidad").val("");
		}
		$("#ctc").val("1");
		
	}
}

function addItem2(tipoOrdenSel){
	
	var codProcedimiento=$("#codprocedimiento").val();
	var nomProcedimiento=$("#nomprocedimiento").val();
	var cantidad=$("#cantidad").val();
	var tipoManual=$("#tipoManual").val();
	var tipo = $(".tactive").find("a").attr("tipo");
	var nomespecialidad=$("#nomespecialidad").val();
	var CodEspecialidad=$("#codespecialidad").val();


	var ctc= $("#ctc").val();
	if(tipo=='01'){
		var tipoOrdenSeleccionado= '01';
	}else{
		var tipoOrdenSeleccionado= $(".tipoOrdenSeleccionado").attr("tipo");
	}
	if($.trim(tipoOrdenSel)!=''){
		tipoOrdenSeleccionado=tipoOrdenSel;
	}
	
	if($.trim(tipoOrdenSeleccionado)==''){
		tipoOrdenSeleccionado=$_tipoOrdenExt;
		$_tipoOrdenExt='';
	}
	
	var codservicio=$("#codservicio").val();
	
	
		
	if($.trim($("#nroOrden").val())!=''){
		swal("No se puede modificar esta solicitud","","error");
		return false;
	}
	
	// if($.trim(codservicio)==''){
	// 	notify("Seleccione un servicio.","error");
	// 	return false;
	// }else 
	if($.trim(codProcedimiento)==''){
		swal("Seleccione un procedimiento.","","error");
		buscarProcs();
		return false;
	}else if($.trim(cantidad)==''){
		swal("Ingrese la cantidad.","","error");
		return false;
	}else{
		deshabilitarToolBar(true);
		ajaxPOST("../../controlador/HC/Ordenes_Externas.php","divItems","habilitarToolBar(true);existeItem();","","operacion=addItem2&estudio=<?php echo $_REQUEST["estudio"];?>&codProcedimiento="+codProcedimiento+"&cantidad="+cantidad+"&tipoManual="+tipoManual+"&nomProcedimiento="+nomProcedimiento+"&tipo="+tipo+"&ctc="+ctc+"&codservicio="+codservicio+"&tipoOrdenSeleccionado="+tipoOrdenSeleccionado+"&NroItemMed="+$("#NroItemMed").val()+"&NroItemProc="+$("#NroItemProc").val()+"&CodEspecialidad="+CodEspecialidad+"&NomEspecialidad="+nomespecialidad+"<?php echo $urlParams; ?>");
		$("#codprocedimiento").val("");
		$("#nomprocedimiento").val("");
		if(tipo!='04'){
		$("#cantidad").val("");
		}
		$("#ctc").val("1");
		
	}
}

function buscarProcedimientoNoPOS(){
	showWindowFind("cups, nombreve, cups *mas ' - ' *mas nombreve,justificacion_procedimiento",'sis_proc','tipo|dbo.fnGetTipoManualEstudio(<?php echo $_REQUEST["estudio"];?>)','CUPS,Nombre','80,500','##procedimiento_solicitado#just_med','Procedimientos');
}

function buscarMedicamentoNoPos(){
	showWindowFind("azi.Codigo,azi.Nombre,azi.Presentacion,azi.vchPrincipioActivo,azi.vchINVIMA,azi.vchCantidadConcentrada *mas ' ' *mas azi.Presentacion AS presentacionConcentracion,sp.justificacion_medicamento,sp.precaucion_medicamento",
		"dbo.ArticulosZI azi,sis_prod sp",
		"sp.codigo=azi.Codigo COLLATE DATABASE_DEFAULT AND sp.pos=2 ORDER BY Nombre",
		"Codigo, Nombre, Presentacion, Principio Activo, Invima, Cant. Concentrada",
		"120,200,100,200,200,100",
		"#princ_activo###reg_invima#pres_conc#just_med#precaucion",
		"Medicamentos",
		"");	
}

function existeItem(){
	$(this).ZS_set_referencia();
	if($("#isLimiteItem").val()=='1'){
		swal("Se ha alcanzado el n&uacute;mero m&aacute;ximo de items permitidos por el contrato ("+$("#isLimiteItem").attr("maximo")+")","","error");
	}else if($("#existeItem").val()=='1'){		
		swal("Ya se ha agregado este item ("+$("#existeItem").attr("nombreItem")+").","","error");
	}
}

function addItemMedicamentoDosis(){
	var codMedicamento=$("#codmedicamento").val();
	var nomMedicamento=$("#nommedicamento").val();
	
	var dosis=$("#dosis").val();
	var und_dosis=$("#und_dosis").val();
	var und_equivalencia=$("#und_equivalencia").val();
	var frecuencia_hora=$("#frecuencia_hora").val();
	var formulacion=$("#formulacion").val();
	var viaAdministracion=$("#viaAdministracion").val();
	var NomViaAdministracion=$("#viaAdministracion option:selected").attr("descripcion");
	var detalle=$("#detalle").val();
	var recomendacion=$("#recomendacion").val();
	var cantidad = $("#cantidad")
	
	

	var tipo = $(".tactive").find("a").attr("tipo");
	
	var pos_nopos=$("#pos_nopos").val();
	
	if($.trim($("#nroOrden").val())!='' && $.trim($("#editable").val())=='false'){
		swal("No se puede modificar esta solicitud","","error");
		return false;
	}
	
	if($.trim(codMedicamento)==''){
		swal("Seleccione un medicamento.","","error");
		return false;
	}else if($.trim(dosis)==''){
		swal("Ingrese la dosis.","error");
		return false;
	}else if($.trim(und_equivalencia)==''){
		swal("Seleccione una unidad de equivalencia.","error");
		return false;
	}else if($.trim(frecuencia_hora)==''){
		swal("Ingrese la frecuencia.","error");
		return false;
	}else if($.trim(viaAdministracion)==''){
		swal("Seleccione la via de administracion.","error");
		return false;
	}else{
		deshabilitarToolBar(true);
		var data={codMedicamento:codMedicamento,nomMedicamento:nomMedicamento,dosis:dosis,und_dosis:und_dosis,und_equivalencia:und_equivalencia,frecuencia_hora:frecuencia_hora,formulacion:formulacion,viaAdministracion:viaAdministracion,NomViaAdministracion:NomViaAdministracion,detalle:detalle,recomendacion:recomendacion,pos_nopos:pos_nopos};
		
			ajaxPOST("../../controlador/HC/Ordenes_Externas.php","divItems","habilitarToolBar(true);existeItem();","","operacion=addItemMedicamentoDosis&JSON="+JSON.stringify(data)+"&NroItemMed="+$("#NroItemMed").val()+"&NroItemProc="+$("#NroItemProc").val()+'<?php echo $urlParams; ?>');	
		

		/*$("#codmedicamento").val("");
		$("#nommedicamento").val("");
		$("#dosis").val("");
		$("#und_equivalencia").empty();
		$("#frecuencia_hora").val("");
		$("#formulacion").val("");
		$("#detalle").val("");
		$("#recomendacion").val("");
		$("#viaAdministracion").val("");*/
	}
	
}

function addItemMedicamento(){
	var codMedicamento=$("#codmedicamento").val();
	var nomMedicamento=$("#nommedicamento").val();
	var cantidad=$("#cantidad").val();
	var posologia=$("#posologia").val();
	var dias=$("#dias").val();
	var tipo = $(".tactive").find("a").attr("tipo");
	
	var pos_nopos=$("#pos_nopos").val();
	
	if($.trim($("#nroOrden").val())!='' && $("#editable").val() =="false" ){
		swal("No se puede modificar esta solicitud","error");
		return false;
	}
	
	if($.trim(codMedicamento)==''){
	//	pos_nopos=1;
		
	}
	
	if($.trim(nomMedicamento)==''){
		swal("Seleccione o digite un medicamento.","","error");
		return false;
	}else if($.trim(cantidad)==''){
		swal("Ingrese la cantidad.","","error");
		return false;
	}else if($.trim(posologia)==''){
		swal("Ingrese la posologia.","","error");
		return false;
	}else if($.trim(dias)==''){
		swal("Ingrese los dias.","","error");
		return false;
	}else{
		deshabilitarToolBar(true);
		
		if($("#nroOrden").val()!=""){
			swal("Item agregado de la orden "+ $("#nroOrden").val(),"","success");
			ajaxPOST("../../controlador/HC/Ordenes_Externas.php","divItems","habilitarToolBar(true);existeItem();","","operacion=addItemMedicamentosNew&codMedicamento="+codMedicamento+"&cantidad="+cantidad+"&nomMedicamento="+nomMedicamento+"&posologia="+posologia+"&dias="+dias+"&tipoorden="+tipo+"&pos_nopos="+pos_nopos+"&NroItemMed="+$("#NroItemMed").val()+"&NroItemProc="+$("#NroItemProc").val()+"&orden="+$("#nroOrden").val()+'<?php echo $urlParams; ?>');
		}else{
			ajaxPOST("../../controlador/HC/Ordenes_Externas.php","divItems","habilitarToolBar(true);existeItem();","","operacion=addItemMedicamento&codMedicamento="+codMedicamento+"&cantidad="+cantidad+"&nomMedicamento="+nomMedicamento+"&posologia="+posologia+"&dias="+dias+"&tipoorden="+tipo+"&pos_nopos="+pos_nopos+"&NroItemMed="+$("#NroItemMed").val()+"&NroItemProc="+$("#NroItemProc").val()+'<?php echo $urlParams; ?>');
		}
		
		
		
		$("#codmedicamento").val("");
		$("#nommedicamento").val("");
		$("#posologia").val("");
		$("#dias").val("");
		$("#cantidad").val("");
		$("#pos_nopos").val("");
		nextFocus("btnBuscarMcto");
	}
}

function listarItems(){
	setearCalendarioBarra();
	$( "#FiltroServicio" ).keypress(function( event ) {
	  if ( event.which == 13 ) {
			setServicios();	 
	  }
	});
	
	$(".DivTipoOrden").click(function(){
		$(".DivTipoOrden").removeClass("tipoOrdenSeleccionado");
		$(this).addClass("tipoOrdenSeleccionado");
		if($(this).attr('tipo')=='02'){
			$("#TituloWindows").html("Solicitud De Procedimientos De Diagnosticos");
		}else if($(this).attr('tipo')=='03'){
			$("#TituloWindows").html("Solicitud De Procedimientos Terapeuticos No Quirurgicos");
		}else if($(this).attr('tipo')=='04'){
			$("#TituloWindows").html("Solicitud De Procedimientos Quirurgicos");
		}
		setServicios();
		
	});
	
	
	var tipoOrden = $(".tactive").find("a").attr("tipo");
	if(tipoOrden=='01'){
		setServicios();
	//	ajaxPOST("../../controlador/HC/Ordenes_Externas.php","divItems","setServicios()","","operacion=listarItems&tipoOrden="+tipoOrden+"&NroOrden="+$("#nroOrden").val());
	}
}

function buscarEspecialidades(){
	showWindowFind('id,nombre','sis_especialidades',"",'Codigo,Nombre','80,350','codespecialidad#nomespecialidad','Especialidades', 'cargaProcDefecto()');	
}

function cargaProcDefecto(){
	respGetProcDefecto=function(){
		var resp=JSON.parse(_ResquestAJAX);
		if(resp.length==1){
			$("#codprocedimiento").val(resp[0]["Codigo"]);
			$("#nomprocedimiento").val(resp[0]["Descripcion"]);
			$("#cantidad").val('1');
			if(resp[0]["Pos"]=="1"){
				$("#ctc").val("0");
			}else{
				$("#ctc").val("1");	
			}
			addItem();
		}		
	}
	
	ajaxPOST("../../controlador/HC/Ordenes_Externas.php","","respGetProcDefecto()","",$.param({operacion:"GetProcDefecto",CodServicio:$(".servicioSeleccionado").data('ServicioLey'),CodEspecialidad:$("#codespecialidad").val(),Estudio:'<?php echo $_REQUEST["estudio"];?>'}));	
}

function setServicios(){
	
	var tipoOrden = $(".tactive").find("a").attr("tipo");
	var SolicitudOrdenes=0;
	if(tipoOrden!='01'){		
		var $selected_tab = $(".tipoOrdenSeleccionado").attr("tipo");
		if($.trim($selected_tab)==''){
			swal("Seleccione un tipo de procedimientos.","","error");
			return false;
		}
	}else{
		var $selected_tab='01';
		SolicitudOrdenes=1;
	}
	
	respServicios=function(){
		var resp=JSON.parse(_ResquestAJAX);
		DivListaServicios=$("#DivListaServicios");
		DivListaServicios.empty();
		for(var i in resp) {
			DivListaServicios.ZS_div('class="letraDisplay DivServicios"');
			$div.ZS_tabla('width="100%"');
			$tabla.ZS_tr('');
			$tr.ZS_td('class="letraDisplay" valign="center"',resp[i]["nombre"]);
			$div.data('fuente',resp[i]["fuente"]);
			$div.data('nombre',resp[i]["nombre"]);
			$div.data('ServicioLey',resp[i]["ServicioLey"]);
			$div.data('IdEspecialidad',resp[i]["IdEspecialidad"]);
			$div.data('NomEspecialidad',resp[i]["NomEspecialidad"]);
			$div.click(function(){
				$(".servicioSeleccionado").removeClass("servicioSeleccionado");
				$(this).addClass('servicioSeleccionado');
				$("#codservicio").val($(this).data('fuente'));
				$("#nomservicio").val($(this).data('nombre'));
				
				if($.trim($(this).data('IdEspecialidad'))!=''){
					$("#codespecialidad").val($(this).data('IdEspecialidad'));
					$("#nomespecialidad").val($(this).data('NomEspecialidad'));
					cargaProcDefecto();
				}else{
					$("#codespecialidad").val("");
					$("#nomespecialidad").val("");
				}
				cargarBateriaRutasAtencion(getValue("autoid"));
			});
		}
		
	}
	
	ajaxPOST("../../controlador/HC/Ordenes_Externas.php","","respServicios()","","operacion=GetServicios&estudio="+estudio+"&Concepto="+$selected_tab+"&FiltroServicio="+$("#FiltroServicio").val()+"&SolicitudOrdenes="+SolicitudOrdenes);
	//var selected_tab = $(".tactive").find("a").attr("tipo");
	//alert(selected_tab);
}

function delProc(i, codigo = 0){
	var tipo = $(".tactive").find("a").attr("tipo");
	$(".procs_riesgo_ruta[data-cups='"+codigo+"']:first").remove();
	deshabilitarToolBar(true);
	ajaxPOST("../../controlador/HC/Ordenes_Externas.php","divItems","habilitarToolBar(true);$(this).ZS_set_referencia();","","operacion=delProc&item="+i+"&tipoorden="+tipo+"<?php echo $urlParams; ?>");

	
}

function guardarSolicitud(){
	
	if($.trim($("#nroOrden").val())!=''){
		swal("No se puede modificar esta solicitud","","error");
		return false;
	}

	if($.trim($("#codservicio").val())==''&&$.trim($('#ServicioSolicitudExterna').val())=='S'){
		swal("Debe seleccionar un servicio.","","error");
		return false;
	}
	
	var codservicio=$("#codservicio").val();
	var cod=$("#cod").val();
	var fecha=$("#FechaSolicitud").val();
	var hora=$("#hora").val();
	var descripcion=$("#descripcion").val();
	var tipoOrden = $(".tactive").find("a").attr("tipo");
	var estudio=$('#estudio').val();
		
	var $ObjetoReferencias=JSON.stringify($(this).ZS_get_referencias());
	
	var object = {};
	if($("input[name='id_riesgos_asign_procs[]']").length > 0){
		$("input[name='id_riesgos_asign_procs[]']").each(function(key, value){
			object[key] = $(value).val();
		});
	}else{
		object = null;
	}
	// console.log(object)
	deshabilitarToolBar(true);
	ajaxPOST("../../controlador/HC/Ordenes_Externas.php","","habilitarToolBar(true);setNumOrden('"+tipoOrden+"');","","operacion=guardarSolicitudOrden&estudio="+estudio+"&codservicio="+codservicio+"&codespecialidad="+$("#codespecialidad").val()+"&fecha="+fecha+"&hora="+hora+"&descripcion="+encodeURIComponent(descripcion)+"&tipoOrden="+tipoOrden+"&cod="+cod+"&Referencias="+$ObjetoReferencias+"&IdNovedad=<?php echo $_REQUEST["IdNovedad"];?>"+"&hijode="+$("#hijode").val()+"&id_riesgos_asign_procs="+JSON.stringify(object));
}

function guardarSolicitudMedicamentos(){
	var tipoOrden = $(".tactive").find("a").attr("tipo");

	var FechaSolicitud=$("#FechaSolicitud").val();
	var recomendacion=$("#recomendacion").val();
	var estudio=$('#estudio').val();	

	if($.trim($("#nroOrden").val())!=''){
		swal("No se puede modificar esta solicitud","","error");
		return false;
	}
	
	if($.trim($("#FechaSolicitud").val())==''){
		swal("Seleccione la fecha de la solicitud","","error");
		return false;
	}
	
	deshabilitarToolBar(true);
	ajaxPOST("../../controlador/HC/Ordenes_Externas.php","","habilitarToolBar(true);setNumOrden('"+tipoOrden+"');","","operacion=guardarSolicitudOrdenMedicamento&estudio="+estudio+"&fecha="+FechaSolicitud+"&recomendacion="+encodeURIComponent(recomendacion)+"&IdNovedad=<?php echo $_REQUEST["IdNovedad"];?>");	
}

function guardarSolicitudMedicamentosDosis(){
	var tipoOrden = $(".tactive").find("a").attr("tipo");

	var FechaSolicitud=$("#FechaSolicitud").val();
	var estudio=$('#estudio').val();

	if($.trim($("#nroOrden").val())!=''){
		swal("No se puede modificar esta solicitud","","error");
		return false;
	}
	
	if($.trim($("#FechaSolicitud").val())==''){
		swal("Seleccione la fecha de la solicitud","","error");
		return false;
	}
	
	deshabilitarToolBar(true);
	ajaxPOST("../../controlador/HC/Ordenes_Externas.php","","habilitarToolBar(true);setNumOrden('"+tipoOrden+"');","","operacion=guardarSolicitudOrdenMedicamentoDosis&estudio="+estudio+"&fecha="+FechaSolicitud+"&recomendacion="+encodeURIComponent(recomendacion)+"&IdNovedad=<?php echo $_REQUEST["IdNovedad"];?>");	
}

function setNumOrden(tipoOrden){
	var resp=JSON.parse(_ResquestAJAX);
	//for(var i in resp) {

	var i=0;	
		if($.trim(resp["Mensaje"])==''){
			$("#nroOrden").val(resp["NumeroSolicitud"]);
			swal("Se ha guardado correctamente.","","success");
			
		 	cargarListaOrdenes(tipoOrden);
			//listarItems();
			$("#showNuevaOrdExt").parent().remove();
			$("#showNuevaOrdExt").remove();
			//closeModal('showNuevaOrdExt');
			if(tipoOrden=='medicamentos'){
				Nueva(resp["NumeroSolicitud"],0);
			}else{
				Nueva(resp["NumeroSolicitud"],1);
			}
			
		}else{
			swal(resp["Mensaje"],"","error");
		}
	//}
}

function EliminarSolicitud(numeroOrden,hasCodGrupo){
	var Motivo= $('#motivo').val();
	if(confirm("Seguro desea eliminar la solicitud?")){
		ajaxPOST("../../controlador/HC/Ordenes_Externas.php","","setEliminaSol();","","operacion=eliminarSolicitudOrden&Motivo="+Motivo+"&numeroOrden="+numeroOrden+"&hasCodGrupo="+hasCodGrupo);
	}
	
}

function setEliminaSol(){
	var tipoOrden = $(".tactive").find("a").attr("tipo");
	var resp=JSON.parse(_ResquestAJAX);
	for(var i in resp) {			
		if($.trim(resp["TipoMsg"])=='OK'){
			closeModal();
			swal("Se ha eliminado la solicitud correctamente.","","success");
		 	cargarListaOrdenes(tipoOrden);
		}else{
			swal(resp["Mensaje"],"","error");
		}
	}
}
function cargarListaOrdenes(tipoOrden){
	deshabilitarToolBar(true);
	estadoHC=$("#estadoHC").val();
	ajaxPOST("../../controlador/HC/Ordenes_Externas.php","divListado"+tipoOrden,"habilitarToolBar(true);","","operacion=getListadoOrdenes&tipoOrden="+tipoOrden+"&estadoHC="+estadoHC+"&VerOrdAnteriores="+(($("#VerOrdAnteriores").is(":checked"))?1:0)+'<?php echo $urlParams; ?>');


}

function cargarDescripcionNovedad(IdNovedad){

	
var widget = new ModalContentZeusSalud("Observacion de la Novedad",
			"../../controlador/HC/historiaClinica.php?operacion=observacionNovedad", {IdNovedad:IdNovedad}, "general", "", "500px", null,null,null,true,'ShowObsNovedad');
			
			widget.imgs_data = {	
								
								close_icon:{
								src:"../../ImagenesZeus/salir 01.png", 
								_w:32, 
								_h:32, 
								_class:"icon close",
								_title:"Cerrar",
								id:"cerrar"
								}
						 };
			widget.process();	

}

function imprimirSolicitud(HasCodGrupo){
	if($.trim($("#nroOrden").val())==''){
		swal("Seleccione una solicitud para imprimir.","","error");
		return false;
	}else{
		var tipoOrden = $(".tactive").find("a").attr("tipo");
		if(HasCodGrupo==1||(tipoOrden!='medicamentos' && $.trim(HasCodGrupo)=='')){
			invocarReporte("reporte_datos_ordenGrupo.php?estudio=<?php echo $_REQUEST["estudio"];?>&numero="+$("#nroOrden").val()+"&tipo_orden=ext&HasCodGrupo="+HasCodGrupo);
		}else{
			invocarReporte("reporte_datos_orden.php?estudio=<?php echo $_REQUEST["estudio"];?>&numero="+$("#nroOrden").val()+"&tipo_orden=ext&HasCodGrupo="+HasCodGrupo);
		}
	}		
}

<?php
//$configuracionxContrato = $model->getParametroGeneral('ImportarOrdenes','HISTORIA CLINICA');
$contrato=$model->getDato('contrato','sis_maes','con_estudio='.$_REQUEST["estudio"]);
$total=$model->getDato('count(*)','medicamentosContratos','contrato='.$contrato);
?>

function buscarMedicamentos(MedDosis) {
	
	if($.trim($("#nroOrden").val())!='' && $.trim($("#editable").val())=='false'){
		swal("No se puede modificar esta solicitud","","error");
		return false;
	}
	
	var estudio = '<?php echo $_REQUEST["estudio"]; ?>';
	var stock = "<?php echo $model->getDato("stock", "Ufuncionales", "id = ".$model->getUfuncionalActual($_REQUEST["estudio"]));?>";
	var medsRutaAtencion = $("#med_ruta_atencion").val();

	var total="<?php echo $total; ?>";
	var contrato="<?php echo $contrato; ?>";
		
	if($.trim($("#manejaNivelesMctos").val())=='S'){
		var NivelMctos=($('#NivelMctos').val()==0?' IS NULL ':' <| '+$('#NivelMctos').val()+' AND Nivel >0 ');
		if(configuracionxContrato=='N'){
		 showWindowFind("p.codigo, p.descripcion,p.concentracion,CASE WHEN(p.pos=1)THEN 'POS' ELSE 'NO POS' END AS posnopos,CASE WHEN(p.pos=1)THEN 1 ELSE 0 END AS pos,p.unimed",
			"sis_prod AS p LEFT JOIN triageMctos tm ON p.codigo=tm.CodMed", 
			"p.activo | 1 AND ISNULL(p.tipo, 0) | 1 And p.recetable | 1  AND (tm.Nivel "+NivelMctos+") AND ('"+medsRutaAtencion+"'= '' or p.codigo in(SELECT items FROM dbo.Split('"+medsRutaAtencion+"',',')))", 
			"Codigo, Nombre, Concentracion,POS/NO POS", "80, 250, 110,80", 
			"codmedicamento#nommedicamento#med_concentracion##pos_nopos#med_unimed", 
			"Medicamentos", "loadSelect("+MedDosis+")");
		}
		else{
			showWindowFind("p.codigo, p.descripcion,p.concentracion,CASE WHEN(p.pos=1)THEN 'POS' ELSE 'NO POS' END AS posnopos,CASE WHEN(p.pos=1)THEN 1 ELSE 0 END AS pos,p.unimed",
			"sis_prod AS p LEFT JOIN triageMctos tm ON p.codigo=tm.CodMed"
			+ " INNER JOIN medicamentosContratos mc on mc.codigo=p.codigo and mc.contrato="+contrato,  
			"p.activo | 1 AND ISNULL(p.tipo, 0) | 1 And p.recetable | 1  AND (tm.Nivel "+NivelMctos+") AND ('"+medsRutaAtencion+"'= '' or p.codigo in(SELECT items FROM dbo.Split('"+medsRutaAtencion+"',',')))", 
			"Codigo, Nombre, Concentracion,POS/NO POS", "80, 250, 110,80", 
			"codmedicamento#nommedicamento#med_concentracion##pos_nopos#med_unimed", 
			"Medicamentos", "loadSelect("+MedDosis+")");
		}
	}else{
			
		if(total==0){
		showWindowFind("p.codigo, p.descripcion,p.concentracion,CASE WHEN(p.pos=1)THEN 'POS' ELSE 'NO POS' END AS posnopos,CASE WHEN(p.pos=1)THEN 1 ELSE 0 END AS pos,p.unimed",
			"sis_prod AS p", 
			"p.activo | 1 And p.recetable | 1 AND ('"+medsRutaAtencion+"' | '' OR p.codigo in (SELECT items FROM dbo.Split('"+medsRutaAtencion+"',',')))", 
			"Codigo, Nombre, Concentracion,POS/NO POS", "80, 250, 110,80", 
			"codmedicamento#nommedicamento#med_concentracion##pos_nopos#med_unimed", 
			"Medicamentos", "loadSelect("+MedDosis+")");
		}else{
			showWindowFind("p.codigo, p.descripcion,p.concentracion,CASE WHEN(p.pos=1)THEN 'POS' ELSE 'NO POS' END AS posnopos,CASE WHEN(p.pos=1)THEN 1 ELSE 0 END AS pos,p.unimed",
			"sis_prod AS p"
			+ " INNER JOIN medicamentosContratos mc on mc.codigo=p.codigo and mc.contrato="+contrato, 
			"p.activo | 1  And p.recetable | 1 AND ('"+medsRutaAtencion+"' | '' OR p.codigo in (SELECT items FROM dbo.Split('"+medsRutaAtencion+"',',')))", 
			"Codigo, Nombre, Concentracion,POS/NO POS", "80, 250, 110,80", 
			"codmedicamento#nommedicamento#med_concentracion##pos_nopos#med_unimed", 
			"Medicamentos", "loadSelect("+MedDosis+")");
		}
	}
}

function loadSelect(MedDosis){
	if(MedDosis==1){
	
		ajaxPOST("comboUND.php","divDosis","","","und="+$("#med_unimed").val()+"&dosis="+$("#med_concentracion").val(),"");
		//$("#med_concentracion").val()+' '+$("#med_unimed").val()
	}
	nextFocus('cantidad');
}

function ordenarProcsAgrupados(){
	if($.trim($("#nroOrden").val())!=''){
		swal("No se puede modificar esta solicitud","","error");
		return false;
	}
	
	var hc=1;
	limpiarAgrupados();
	if (getValue("codservicio").length == 0&&$.trim($('#ServicioSolicitudExterna').val())=='S') {
		swal("Primero debe seleccionar un servicio","","error");
		return;
	}
		
	var xfuente = getValue('codservicio');
	var estudio = <?php echo $_REQUEST['estudio']; ?>;
	dt = "fuente="+getValue("codservicio")+"&estudio="+estudio+"&multipleSelect=1"+'&hc='+hc+"&tamanoTabla=0";

	$.ajax({
		type: "POST",
		url: "OrdenarProcsAgrupados.php",
		data: dt,
		success: function(datos2){			
			document.getElementById('divProcsAgrupados').innerHTML = datos2;		
			document.getElementById('divProcsAgrupados').style.display="";
			filtroGuia();
		}
	});
}


function filtroGuia(){
	$("#buscarGuiaMedicaFiltro").keyup(function(e) {
	var inum = 0;
	var AnttableUnica ="";
	//console.log('ENTRO');
	$(".headStrong").find('tr').each (function(){
		var Cantidad = $(this).parents("table:first").attr('rows').length;
		var tableUnica = $(this).parents("table:first").find('.CABECERA').find("strong").html();
		//console.log(tableUnica);
		if(AnttableUnica != tableUnica){
			AnttableUnica = tableUnica;
			inum = 0;
			//console.log("entro");
		}
		var exp = $(this).html()
		//console.log("valor->"+inum);
		if($.trim(exp) != ""){
			var Val = new RegExp($("#buscarGuiaMedicaFiltro").val().toUpperCase());
			resultado = Val.test(exp);
			if(resultado){
				$(this).attr("style","display:''");
			}else{
				$(this).attr("style","display:none");
				inum++;
			}
		}
		
		if(inum == Cantidad){
			$(this).parents("table:first").attr("style","display:none");
		}else{
			$(this).parents("table:first").attr("style","display:''");
		}
		//console.log(inum+'---'+Cantidad)
	});               
	
	
	})
}

function agregarOrdenAgrupado(CodProc,NomProc){
	/*$("#codprocedimiento").val(CodProc);
	$("#nomprocedimiento").val(NomProc);
	$("#cantidad").val(1);
	addItem();*/
}

function seleccionarGrupo(idGrupo,numGrupo){
	
		$("[id*=cod_proc-"+numGrupo+"]").each(function(index, element) {
			if($("#"+idGrupo).is(":checked")){
				$(this).attr("checked",true);
			}else{
				$(this).attr("checked",false);	
			}
		});
}

function enviarAgrupados(){
	$("input:checkbox:checked").each(function(index,element){
		if($(this).attr("codproc")!=''&&$(this).attr("codproc")!=undefined&&$(this).attr("codproc")!='undefined'){
			$("#codprocedimiento").val($(this).attr("codproc"));
			$("#nomprocedimiento").val($(this).attr("nomproc"));
			$("#cantidad").val(1);
			addItem();
		}
	});	
	limpiarAgrupados();
}

function limpiarAgrupados(){
	document.getElementById('divProcsAgrupados').innerHTML = "";
	document.getElementById('divProcsAgrupados').style.display = "none";
}


function showFormCTC(medicamento,cantidad,nombre,tipo,posologia,NumOrden,CodServicio){
	var numero=get('nroOrden').value;
	if($.trim(NumOrden)!=''){
		numero=NumOrden;
	}
	var widget = new ModalContentZeusSalud("CTC "+((tipo=='procedimientos')?'PROCEDIMIENTOS':'MEDICAMENTOS'),
				"CTC2.php?op="+tipo+"&cod_medicamento="+medicamento+"&numero="+numero+"&cantidad="+cantidad+"&nombreMed="+nombre+"&es_receta=0&CodServicio="+$.trim(CodServicio), {posologia:posologia}, "general", "", "", "2%",null,null,false,'showWindowCTC',setBtnCtcAnterior);
	
				widget.imgs_data = {										
									close_icon:{
									src:"../../ImagenesZeus/salir 01.png", 
									_w:32, 
									_h:32, 
									_class:"icon close",
									_title:"Cerrar",
									id:"cerrar"
									},
									
									
									guardar_icon:{
									src:"../../ImagenesZeus/guardar 01.png", 
									_w:32, 
									_h:32, 
									_class:"icon guardar",
									_title:"Guardar",
									id:"guardar",
									fnAction:function(){
										guardarCTC();	
									},
									},
									
									buscar_icon:{
									src:"../../ImagenesZeus/buscar 01.png", 
									_w:32, 
									_h:32, 
									_class:"icon buscar",
									_title:"Buscar",
									id:"btnBuscarCtcAnterior",
									fnAction:function(){
										buscarCTCAnterior(medicamento,nombre);	
									},
									},

									imprimir_icon:{
									src:"../../ImagenesZeus/imprimir 01.png", 
									_w:32, 
									_h:32, 
									_class:"icon imprimir",
									_title:"Imprimir",
									id:"imprimir",
									fnAction:function(){
										imprimirCTC();	
									},
									}
									
									
								 };
				widget.process();		
}

function setBtnCtcAnterior(){
	/*var TipoOrden = $(".tactive").find("a").attr("tipo");
	if(TipoOrden!='procedimientos'){
		$("#btnBuscarCtcAnterior").remove();
	}*/
}
function imprimirCTC(){
	var idCTC=get('idCTC').value;
	invocarReporte('ctc_pdf.php?id_ctc='+idCTC,'reportesvarios');
}

function buscarCTCAnterior(medicamento,nombre){
	var TipoOrden = $(".tactive").find("a").attr("tipo");
	if(TipoOrden!='procedimientos'){
		showWindowFind('c.nom_medico,c.fecha,c.princ_activo,c.desc_cc,c.resp_med_pos,c.resp_med_pos_desc,c.pres_conc,c.dosis_dia_nopos,c.cant_soli_mes,c.tiempo_mes,c.reg_invima,c.grupoTerapeuticoNoPos,c.nro_dias_tratamiento,c.just_med,c.precaucion,c.t_resp_esperado,c.homologos_pos,c.copia_carnet,c.copia_fm,c.copia_hc,c.riesgo_salud,c.justificacion_salud,c.posibilidades_terapeuticas,c.aut_invima,c.id','ctc c,sis_maes sm',"c.estudio=sm.con_estudio AND sm.con_estudio=<?php echo $_REQUEST["estudio"];?> AND c.codmed_orden LIKE '%"+medicamento+"%' AND c.tipoCTC='Medicamento' ORDER BY c.id desc",'Medico,Fecha,Princ. Activo Procedimiento Solicitado','200,80,300','##CTC_princ_activo#CTC_descrip_caso#CTC_resp_med_pos#CTC_resp_med_pos_desc#CTC_pres_conc#CTC_dosis_dia_nopos#CTC_cant_soli_mes#CTC_tiempo_mes#CTC_reg_invima#grupoTerapeuticoNoPos#nro_dias_tratamiento#CTC_just_med#CTC_precaucion#CTC_t_resp_esperado#hiddenHomologoPos#hiddenCopiaCarnet#hiddenCopiaFm#hiddenCopiaHc#hiddenRiesgoSalud#justificacion_salud#hiddenPosibilidadesTerapeuticas#hiddenAutInvima#hiddenIdCtc','CTC Generados Anteriormente', 'setChecks()');
	}else{
		showWindowFind('c.nom_medico,c.fecha,c.procedimiento_solicitado,c.desc_cc,c.just_med,c.justificacion_salud,c.copia_carnet,c.copia_fm,c.copia_hc,c.riesgo_salud,c.posibilidades_terapeuticas','ctc c,sis_maes sm',"c.estudio=sm.con_estudio AND sm.con_estudio=<?php echo $_REQUEST["estudio"];?> AND c.princ_activo LIKE '%"+nombre+"%' AND c.tipoCTC='Procedimiento' ORDER BY c.id desc",'Medico,Fecha,Procedimiento Solicitado','200,80,300','##procedimiento_solicitado#CTC_descrip_caso#CTC_just_med#justificacion_salud#hiddenCopiaCarnet#hiddenCopiaFm#hiddenCopiaHc#hiddenRiesgoSalud#hiddenPosibilidadesTerapeuticas','CTC Generados Anteriormente', 'setChecks()');
	}
}

function setChecks(){
	if($("#hiddenCopiaCarnet").val()==1){
		$("#CTC_copia_carnet").attr("checked",true);
	}

	if($("#hiddenCopiaFm").val()==1){
		$("#CTC_copia_fm").attr("checked",true);
	}

	if($("#hiddenCopiaHc").val()==1){
		$("#CTC_copia_hc").attr("checked",true);
	}

	$("input:radio[name=riesgo_salud][value='"+$("#hiddenRiesgoSalud").val()+"']").attr("checked",true);
	$("input:radio[name=posibilidades_terapeuticas][value='"+$("#hiddenPosibilidadesTerapeuticas").val()+"']").attr("checked",true);
	
	
}


function guardarCTC(){
	var numero=get('nroOrden').value;
	if(numero!='' && $("#PermitirModificarCTC").val()=='N'){
		swal("No se puede crear o modificar CTC si la orden ya ha sido guardada.","","error");
		return false
	}
	var CTC_copia_carnet=0;
	var CTC_copia_fm=0;
	var CTC_copia_hc=0;
	
	if(get('CTC_copia_carnet').checked){
		CTC_copia_carnet=1;	
	}
	if(get('CTC_copia_fm').checked){
		CTC_copia_fm=1;	
	}
	if(get('CTC_copia_hc').checked){
		CTC_copia_hc=1;	
	}
	
	if($("#CTC_princ_activo").val()==''){
		swal("Ingrese el medicamento o principio activo","","error");
		return false;
	}
	
	var idCTC=$('#idCTC').val();
	var numero=$('#nroOrden').val();
	deshabilitarToolBar();
	ajaxPOST("../../controlador/HC/ctc.php","CTC_DIV_Respuesta","habilitarToolBar();","","operacion=guardarCTC&"+$("#CTC_formulario").serialize()+"&CTC_chk_copia_carnet="+CTC_copia_carnet+"&CTC_chk_copia_fm="+CTC_copia_fm+"&CTC_chk_copia_hc="+CTC_copia_hc+'&idCTC='+idCTC+"&numero="+numero+"&es_receta=0&estado=0&CTC_CodServicio="+$.trim($("#CTC_CodServicio").val()));
//	alert($("#CTC_formulario").serialize());

}


$(document).ready(function(e) {
    //cargarListaOrdenes('04');
	cargarListaOrdenes('procedimientos');
	cargarListaOrdenes('medicamentos');
	cargarListaOrdenes('01');
	/*cargarListaOrdenes('02');
	cargarListaOrdenes('medicamentos');
	cargarListaOrdenes('sol_medicamentos_dosis');
	*/
});


function buscarMedicamentoPos(){
	showWindowFind("azi.Codigo,azi.Nombre,azi.Presentacion,azi.vchPrincipioActivo,azi.vchINVIMA,azi.vchCantidadConcentrada *mas ' ' *mas azi.Presentacion AS presentacionConcentracion",
		"dbo.ArticulosZI azi,sis_prod sp",
		"sp.codigo=azi.Codigo COLLATE DATABASE_DEFAULT AND sp.pos=1 And sp.recetable | 1 ORDER BY Nombre",
		"Codigo, Nombre, Presentacion, Principio Activo, Invima, Cant. Concentrada",
		"120,200,100,200,200,100",
		"#CTC_med_pos_uti#presentacionPOS#CTC_principio_act##concentracionPOS",
		"Procedimientos POS",
		"");
	
}



function addMedicamentoPos(){
	var CTC_med_pos_uti=get('CTC_med_pos_uti').value;
	var CTC_principio_act=get('CTC_principio_act').value;
	var CTC_dosis_dia=get('CTC_dosis_dia').value;
	var concentracionPOS=get('concentracionPOS').value;			
	var presentacionPOS=get('presentacionPOS').value;			
	var tiempoUtilizacion=get('tiempoUtilizacion').value;	
	
	
	ajaxPOST("../../controlador/HC/ctc.php","","habilitarToolBar();addMed();","","operacion=addMedicamentoPOS&CTC_med_pos_uti="+CTC_med_pos_uti+'&CTC_principio_act='+CTC_principio_act+'&CTC_dosis_dia='+CTC_dosis_dia+'&concentracionPOS='+concentracionPOS+'&presentacionPOS='+presentacionPOS+'&tiempoUtilizacion='+tiempoUtilizacion);
}
function addMed(){
	var CTC_med_pos_uti=get('CTC_med_pos_uti').value;
	var CTC_principio_act=get('CTC_principio_act').value;
	var CTC_dosis_dia=get('CTC_dosis_dia').value;
	var concentracionPOS=get('concentracionPOS').value;			
	var presentacionPOS=get('presentacionPOS').value;			
	var tiempoUtilizacion=get('tiempoUtilizacion').value;	
	var bgcolor='bgcolor="#FFF"';
	var posicion=parseInt(get('nroItem').value)+1;
	
	$("#tabMedPos").append('<tr class="idTab'+posicion+'"><td class="letraDisplay" '+bgcolor+'>'+CTC_med_pos_uti+'</td><td class="letraDisplay" '+bgcolor+'>'+CTC_principio_act+'</td><td class="letraDisplay" '+bgcolor+'>'+CTC_dosis_dia+'</td><td class="letraDisplay" '+bgcolor+'>'+concentracionPOS+'</td><td class="letraDisplay" '+bgcolor+'>'+presentacionPOS+'</td><td class="letraDisplay" '+bgcolor+'>'+tiempoUtilizacion+'</td><td class="letraDisplay" align="center" '+bgcolor+'><a href="javascript:deleteMedPos(\'\',2,'+posicion+');"><img src="../../ImagenesZeus/Eliminar.png" width="13" height="13" /></a></td></tr>');
	
	get('CTC_med_pos_uti').value='';
	get('CTC_principio_act').value='';
	get('CTC_dosis_dia').value='';
	get('concentracionPOS').value='';
	get('presentacionPOS').value='';		
	get('tiempoUtilizacion').value='';
	get('nroItem').value=posicion;
}

function abrirHistoricoOrdenes(){
	var TipoOrden = $(".tactive").find("a").attr("tipo");
	none=($("#tdHistoricoOrden").css('display'));
	if(none=='none'){
		$("#imgHistoricoOrden").attr('src','../../Imagenes/flechita2_der.gif');
		$("#tdHistoricoOrden").css('display','');
		if(TipoOrden=='procedimientos'){
			$("#tdTipoDeOrden").css('display','none');
		}
		
		if(TipoOrden=='procedimientos' || TipoOrden=='01'){
			$("#tdServicios").css('display','none');	
		}
		
		cargarHistoricoOrdenes();
	}else{
		$("#imgHistoricoOrden").attr('src','../../Imagenes/flechita2_izq.gif');
		$("#tdHistoricoOrden").css('display','none');	
		if(TipoOrden=='procedimientos'){
			$("#tdTipoDeOrden").css('display','');
		}
		
		if(TipoOrden=='procedimientos' || TipoOrden=='01'){
			$("#tdServicios").css('display','');	
		}
	}
	
}
$_tipoOrdenExt='';
function cargarHistoricoOrdenes(){

	var TipoOrden = $(".tactive").find("a").attr("tipo");
	var ImportarOrden = document.getElementById("importarOrden").value;
	console.log(ImportarOrden);
	$("#DivlistaHistoricoOrdenes").html("");
	respGetHistoricoOrd=function(){
		var resp=JSON.parse(_ResquestAJAX);
		$divGral=$('<div>');
		$NumeroOrden=''; $CodGrupo='';$Servicio='';
		for(var i in resp){
			if($NumeroOrden!=resp[i]["Numero"]){
				$NumeroOrden=resp[i]["Numero"];
				$div=$('<div>');
				$div.ZS_tabla('width="100%" style="background-color:#C7D1E9" class="LSPRESSE"');
				$tabla.ZS_tr('');
				$tr.ZS_td('class="letraDisplay"','Orden:');
				$tr.ZS_td('class="letraDisplay"','<b>'+resp[i]["Numero"]+'</b>');
				$tr.ZS_td('class="letraDisplay"','Fecha Hora:');
				$tr.ZS_td('class="letraDisplay"','<b>'+resp[i]["Fecha"]+' '+resp[i]["Hora"]+'</b>');
				
				$img=$('<img title="Importar Orden" src="../../Imagenes/errorpages.gif" width="15px">');
				$img.css({cursor:'pointer'});
				$img.data('NumeroOrden',resp[i]["Numero"]);
				$img.click(function(){
						
					$(".Importar"+$(this).data('NumeroOrden')).each(function(index, element) {
                        $(this).click();						
                    });
				});
				$tr.ZS_td('class="letraDisplay"',$img);
				
				$tabla.ZS_tr('');
				$tr.ZS_td('class="letraDisplay"','Medico:');
				$tr.ZS_td('class="letraDisplay" colspan="3"',resp[i]["Medico"]);
				
				$tabla.ZS_tr('');
				$tr.ZS_td('class="letraDisplay"','Tipo Orden:');
				$tr.ZS_td('class="letraDisplay" colspan="3"',resp[i]["NomTipoOrden"]);
				
				$div.ZS_tabla('width="100%" style="background-color:#FFF" class="LSPRESSE"');
				$tabla.ZS_tr('');
				$tr.ZS_td('class="letraCaptionLink" bgcolor="#34b0c4"','Codigo');
				$tr.ZS_td('class="letraCaptionLink" bgcolor="#34b0c4"','Procedimiento');
				$tr.ZS_td('class="letraCaptionLink" bgcolor="#34b0c4"','Tipo');
				$tr.ZS_td('class="letraCaptionLink" bgcolor="#34b0c4"','Cant.');
				$tr.ZS_td('class="letraCaptionLink" bgcolor="#34b0c4"','&nbsp;');
				$tr.ZS_td('class="letraCaptionLink" bgcolor="#34b0c4"','&nbsp;');
				
				$divGral.append($div);
				
				$Servicio='';
			}
				if($Servicio!=resp[i]["Servicio"]){
					$Servicio=resp[i]["Servicio"];
					$tabla.ZS_tr('');
					$tr.ZS_td('colspan="6" class="letraDisplay" bgcolor="#DEECD2"',resp[i]["NomServicio"]);
				}
				$tabla.ZS_tr('');
				$tr.ZS_td('class="letraDisplay"',resp[i]["Codigo"]);
				$tr.ZS_td('class="letraDisplay"',resp[i]["Detalle"]);
				$tr.ZS_td('class="letraDisplay"',(resp[i]["PosNoPos"]==1)?'Pos':'No Pos');
				$tr.ZS_td('class="letraDisplay"',resp[i]["Cantidad"]);
				
				if(ImportarOrden=='S'){
					$img=$('<img title="Copiar Item" src="../../Imagenes/lapiz.gif" width="15px">');
				$img.css({cursor:'pointer'});
				$img.data('Servicio',resp[i]["Servicio"]);
				$img.data('TipoExt',resp[i]["TipoExt"]);
				$img.data('PosNoPos',resp[i]["PosNoPos"]);
				$img.data('Codigo',resp[i]["Codigo"]);
				$img.data('Detalle',resp[i]["Detalle"]);
				$img.data('Cantidad',resp[i]["Cantidad"]);
				$img.data('Posologia',resp[i]["Posologia"]);
				$img.data('Resumen',resp[i]["Resumen"]);
				$img.data('Dias',resp[i]["Dias"]);
				$img.click(function(){
					if(TipoOrden=='procedimientos' || TipoOrden=='01'){
						$("#codprocedimiento").val($(this).data('Codigo'));
						$("#nomprocedimiento").val($(this).data('Detalle'));
						$("#cantidad").val($(this).data('Cantidad'));
						$("#codservicio").val($(this).data('Servicio'));
						$_tipoOrdenExt=$(this).data('TipoExt');
						$("#cantidad").select();
					}else if(TipoOrden=='medicamentos'){
						$("#codmedicamento").val($(this).data('Codigo'));
						$("#nommedicamento").val($(this).data('Detalle'));
						$("#cantidad").val($(this).data('Cantidad'));
						$("#posologia").val($(this).data('Posologia'));
						$("#recomendacion").val($(this).data('Resumen'));
						$("#dias").val($(this).data('Dias'));
						$("#pos_nopos").val($(this).data('PosNoPos'));
						
						$("#codservicio").val($(this).data('Servicio'));
						$_tipoOrdenExt=$(this).data('TipoExt');
						$("#cantidad").select();
					}
					//addItem($(this).data('TipoExt'));
				});
				$tr.ZS_td('class="letraDisplay"',$img);
				
				$img=$('<img class="Importar'+resp[i]["Numero"]+'" title="Importar Item" src="../../Imagenes/errorpages.gif" width="15px">');
				$img.css({cursor:'pointer'});
				$img.data('Servicio',resp[i]["Servicio"]);
				$img.data('TipoExt',resp[i]["TipoExt"]);
				$img.data('PosNoPos',resp[i]["PosNoPos"]);
				$img.data('Codigo',resp[i]["Codigo"]);
				$img.data('Detalle',resp[i]["Detalle"]);
				$img.data('Cantidad',resp[i]["Cantidad"]);
				$img.data('Posologia',resp[i]["Posologia"]);
				$img.data('Resumen',resp[i]["Resumen"]);
				$img.data('Dias',resp[i]["Dias"]);
				$img.click(function(){
					if(TipoOrden=='procedimientos' || TipoOrden=='01'){
						$("#codprocedimiento").val($(this).data('Codigo'));
						$("#nomprocedimiento").val($(this).data('Detalle'));
						$("#cantidad").val($(this).data('Cantidad'));
						$("#codservicio").val($(this).data('Servicio'));
						addItem($(this).data('TipoExt'));
					}else if(TipoOrden=='medicamentos'){
						$("#codmedicamento").val($(this).data('Codigo'));
						$("#nommedicamento").val($(this).data('Detalle'));
						$("#cantidad").val($(this).data('Cantidad'));
						$("#posologia").val($(this).data('Posologia'));
						$("#recomendacion").val($(this).data('Resumen'));
						$("#dias").val($(this).data('Dias'));
						$("#pos_nopos").val($(this).data('PosNoPos'));
						
						$("#codservicio").val($(this).data('Servicio'));
						addItemMedicamento();
					}
				});
				$tr.ZS_td('class="letraDisplay"',$img);
				}
			
			
			
		}
		$("#DivlistaHistoricoOrdenes").append($divGral);
	}
	ajaxPOST("../../controlador/HC/Ordenes_Externas.php","","respGetHistoricoOrd()","",$.param({operacion:'GetHistoricoOrdenes',Estudio:'<?php echo $_REQUEST["estudio"];?>',TipoOrden:TipoOrden,isCenso:'<?php echo $_REQUEST["isCenso"];?>'}));
	
}


function fnModalCorreoPcte(estudio,numero,tipoOrden,HasCodGrupo){
	var $content = $(document.createElement('div'));
	$content.css( {width: '516px'} );
	$content.ZS_tabla('style="table-layout:fixed;width:100%;text-align:center" cellpadding="30"');
	$tabla.ZS_tr('');
	$tr.ZS_td('class="letraDisplay" style="width:10%" align="left"','Email: ');
	$tr.ZS_td('style="width:50%"','');
	$td.ZS_input('type="text" style="width:100%" class="norm" name="correo" id="correo" value="'+getValue('correoPcte')+'"','');
	$td.ZS_input('type="hidden" id="numero" value="'+numero+'"','');
	$td.ZS_input('type="hidden" id="tipoOrden" value="'+tipoOrden+'"','');
	$CodMedicamento=$input;
	$tr.ZS_td('','');
	$td.ZS_input('type="checkbox" id="actualizarCorreo" name="actualizarCorreo"','');
	$td.ZS_label('class="letraDisplay" for="actualizarCorreo"','Actualizar Correo');
	
	var widget = new ModalContentZeusSalud('Enviar Email', $content, {}, 'content', '', null, '4%', null, null, true);
	widget.imgs_data = {	
								
		close_icon:{
		src:"../../ImagenesZeus/salir 01.png", 
		_w:32, 
		_h:32, 
		_class:"icon close",
		_title:"Cerrar",
		id:"cerrar"
		} ,
		save_icon:{
			src:"../../ImagenesZeus/guardar 01.png", 
			_w:32, 
			_h:32, 
			_class:"icon",
			_title:"Guardar",
			id:"btnGuardarCombo",
			fnAction:function fnEnviarEmail(){
				var correo = getValue('correo');
				var estudio = getValue('estudio');
				var numero = getValue('numero');
				var tipoOrden = getValue('tipoOrden');
				if($('#actualizarCorreo').is(':checked')){
					var actualizarCorreo = 1;
				}else{
					var actualizarCorreo = 0;
				}
				
				var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;

				if (!regex.test(correo)) {
					swal('Debe Ingresar un correo valido','','error')
					return false;
				}
				var data = new Object();
				var url = "../../Controlador/HC/Ordenes_Externas.php?operacion=SendEmailOrdenamiento&estudio="+estudio+"&numero="+numero+"&tipo_orden="+tipoOrden+"&correo="+correo+"&actualizarCorreo="+actualizarCorreo;
					
				procesador.procesar = function(response){
					habilitarToolBar(true);
					if(response.status != "success"){
						swal(response.statusInfo, "","error");
					} else {
						if(actualizarCorreo == 1){
							$('#correoPcte').val(correo);
						}
						swal(response.statusInfo,"","info");
						closeModal();
					}
				}
				deshabilitarToolBar(true);	
				getJSONAjax(data, url, procesador, "POST", false, "");
		
			}
		}
			
			
	};
	widget.process();
}

function verificarfechaformula(){
   
   // AQUI  ///nueva validacion para que no deje registrar en fecha o hora superior a la actual
   <?php 
		   
		   $value=$model->RSAsociativo("SELECT value from parametrosgenerales where parametro like '%validarfechaenordenes%'");
		   
		   
		   ?>
		   var  dateServer="<?php echo date('Y-m-d'); ?>"
	var fechaserver = new Date(dateServer);
	
	var fechaCompleta=$("#fecha").val();
	var fechauno = new Date(fechaCompleta);
    var fechaF = moment(fechaCompleta);
    var fechaA = moment(dateServer);
    var diferencia = fechaA.diff(fechaF, 'days');
              horasvalidar = 0; 
	TiempoMaxRegistrarEvo="<?php echo $model->getDato("value",'parametrosGenerales',"parametro='TiempoMaxRegistrarEvo'"); ?>"
	editar_borrar_evos = "<?php echo $model->getDato("editar_borrar_evos","sis_medi","cedula = '".$_SESSION['codigo_user']."'")?>"
	diasAtras = "<?php echo $model->getDato("value",'parametrosGenerales',"parametro='TiempoMaxRetroceder'"); ?>"

	if(TiempoMaxRegistrarEvo <= 0 && diasAtras > 0){
		if(diferencia>diasAtras){
			swal('Error! No puede poner fechas con mas de '+diasAtras+' dias atras','','error' );
			return false;
		}
	}
	if(  TiempoMaxRegistrarEvo > 0 ){ 
		horasvalidar = TiempoMaxRegistrarEvo; 
	}
	Date.prototype.addHours = function(h) {
		this.setTime(this.getTime() + (h*60*60*1000));
		return this;
		}


	var horasvalidar2=horasvalidar;
	var fechaCompleta2=$('#fecha').val()+' '+$('#hora').val();
	var fechamaxima2 = new Date(fechaCompleta2).addHours(horasvalidar2);
	var dateServer2="<?php echo date('Y-m-d H:i:s'); ?>"
	var fechaserver2 = new Date(dateServer2);
   
	 var validar="<?php echo $value[0]['value']   ?>";
   console.log(validar);
	   if(validar=='S'){
	myDate = new Date();
   hours = myDate.getHours();
   minutes = myDate.getMinutes();
   seconds = myDate.getSeconds();
   if (hours < 10) hours = "0" + hours;
   if (minutes < 10) minutes = "0" + minutes;
   var horaactual=(hours+ ":" +minutes);
   var fechaactual="<?php echo date('Y/m/d '); ?>"
	var horaatencionformato=$('#hora').val();
	var horaatencionformato2=$('#horarecomendacion').val();
	var fechaatencionformato2=$('#fecha').val();
	

	var fa= new Date(fechaactual);
	var fa3=new Date(fechaatencionformato2);
		 

	console.log(fa);
	console.log(fa3);
   
	if(fa3.valueOf()==fa.valueOf()){
				  console.log("las fechas son iguales"); 
			   }
			  
			   
			  if((fa3.valueOf() > fa.valueOf()) ){
			   swal('La fecha del formato no puede ser superior a la actual','','error');
	   
			   horaactual='';
			   return false;
			  
			  }
   
			   if(horaatencionformato>horaactual && fa3.valueOf()==fa.valueOf()){
			   swal('La hora del formato no puede ser superior a la actual','','error' );
			   console.log(horaactual);
			   horaactual=''; 
			   return false; 
			  
			  }

			  if(fechamaxima2 <= fechaserver2 && horasvalidar2 > 0 && editar_borrar_evos != 1 ){
          swal('El tiempo permitido para guardar el formato es '+horasvalidar+' hora(s), este tiempo ya caduco','','error' );
          return false;
			  }
			  
		   }
	   else {
			   swal('EL PARAMETRO DE VALIDAR QUE LAS FECHAS INGRESADAS U HORAS EN LA ORDENES NO SEAN MAYORES A LAS ACTUALES SE ENCUENTRA INACTIVO','','error');
		   }
		}

function verificarincapacidad(){
   
   // AQUI  ///nueva validacion para que no deje registrar en fecha o hora superior a la actual
   <?php 
		   
		   $value=$model->RSAsociativo("SELECT value from parametrosgenerales where parametro like '%validarfechaenordenes%'");
		   
		   
		   ?>
		   		   var  dateServer="<?php echo date('Y-m-d'); ?>"
	var fechaserver = new Date(dateServer);
	
	var fechaCompleta=$("#FechaIncapacidad").val();
	var fechauno = new Date(fechaCompleta);
    var fechaF = moment(fechaCompleta);
    var fechaA = moment(dateServer);
    var diferencia = fechaA.diff(fechaF, 'days');
           horasvalidar = 0; 
	TiempoMaxRegistrarEvo="<?php echo $model->getDato("value",'parametrosGenerales',"parametro='TiempoMaxRegistrarEvo'"); ?>"
	editar_borrar_evos = "<?php echo $model->getDato("editar_borrar_evos","sis_medi","cedula = '".$_SESSION['codigo_user']."'")?>"
	diasAtras = "<?php echo $model->getDato("value",'parametrosGenerales',"parametro='TiempoMaxRetroceder'"); ?>"

	if(TiempoMaxRegistrarEvo <= 0 && diasAtras > 0){
		if(diferencia>diasAtras){
			swal('Error! No puede poner fechas con mas de '+diasAtras+' dias atras','','error' );
			return false;
		}
	}
	if(  TiempoMaxRegistrarEvo > 0 ){ 
		horasvalidar = TiempoMaxRegistrarEvo; 
	}
	Date.prototype.addHours = function(h) {
		this.setTime(this.getTime() + (h*60*60*1000));
		return this;
		}


	var horasvalidar2=horasvalidar;
	var fechaCompleta2=$('#FechaIncapacidad').val()+' 00:00:00';
	var fechamaxima2 = new Date(fechaCompleta2).addHours(horasvalidar2);
	var dateServer2="<?php echo date('Y-m-d H:i:s'); ?>"
	var fechaserver2 = new Date(dateServer2);
   
	 var validar="<?php echo $value[0]['value']   ?>";
   console.log(validar);
	   if(validar=='S'){
	myDate = new Date();
   hours = myDate.getHours();
   minutes = myDate.getMinutes();
   seconds = myDate.getSeconds();
   if (hours < 10) hours = "0" + hours;
   if (minutes < 10) minutes = "0" + minutes;
   var horaactual=(hours+ ":" +minutes);
   var fechaactual="<?php echo date('Y/m/d '); ?>"
	var fechaatencionformato2=$('#FechaIncapacidad').val();
	

	var fa= new Date(fechaactual);
	var fa3=new Date(fechaatencionformato2);
		 

	console.log(fa);
	console.log(fa3);
   
	if(fa3.valueOf()==fa.valueOf()){
				  console.log("las fechas son iguales"); 
			   }
			  
			   
			  if((fa3.valueOf() > fa.valueOf()) ){
			   swal('La fecha del formato no puede ser superior a la actual','','error');
	   
			   horaactual='';
			   return false;
			  
			  }
   
			  if(fechamaxima2 <= fechaserver2 && horasvalidar2 > 0 && editar_borrar_evos != 1 ){
          swal('El tiempo permitido para guardar el formato es '+horasvalidar+' hora(s), este tiempo ya caduco','','error' );
          return false;
			  }
			  
		   }
	   else {
			   swal('EL PARAMETRO DE VALIDAR QUE LAS FECHAS INGRESADAS U HORAS EN LA ORDENES NO SEAN MAYORES A LAS ACTUALES SE ENCUENTRA INACTIVO','','error');
		   }
		}


function verificarfecharecomendacion(){
   
   // AQUI  ///nueva validacion para que no deje registrar en fecha o hora superior a la actual
   <?php 
		   
		   $value=$model->RSAsociativo("SELECT value from parametrosgenerales where parametro like '%validarfechaenordenes%'");
		   
		   
		   ?>
		   		   var  dateServer="<?php echo date('Y-m-d'); ?>"
	var fechaserver = new Date(dateServer);
	
	var fechaCompleta=$("#FechaRecomendacion").val();
	var fechauno = new Date(fechaCompleta);
    var fechaF = moment(fechaCompleta);
    var fechaA = moment(dateServer);
    var diferencia = fechaA.diff(fechaF, 'days');
        horasvalidar = 0; 
	TiempoMaxRegistrarEvo="<?php echo $model->getDato("value",'parametrosGenerales',"parametro='TiempoMaxRegistrarEvo'"); ?>"
	editar_borrar_evos = "<?php echo $model->getDato("editar_borrar_evos","sis_medi","cedula = '".$_SESSION['codigo_user']."'")?>"
	diasAtras = "<?php echo $model->getDato("value",'parametrosGenerales',"parametro='TiempoMaxRetroceder'"); ?>"

	if(TiempoMaxRegistrarEvo <= 0 && diasAtras > 0){
		if(diferencia>diasAtras){
			swal('Error! No puede poner fechas con mas de '+diasAtras+' dias atras','','error' );
			return false;
		}
	}
	if(  TiempoMaxRegistrarEvo > 0 ){ 
		horasvalidar = TiempoMaxRegistrarEvo; 
	}
	Date.prototype.addHours = function(h) {
		this.setTime(this.getTime() + (h*60*60*1000));
		return this;
		}


	var horasvalidar2=horasvalidar;
	var fechaCompleta2=$('#FechaRecomendacion').val()+' '+$('#horarecomendacion').val();
	var fechamaxima2 = new Date(fechaCompleta2).addHours(horasvalidar2);
	var dateServer2="<?php echo date('Y-m-d H:i:s'); ?>"
	var fechaserver2 = new Date(dateServer2);
   
	 var validar="<?php echo $value[0]['value']   ?>";
   console.log(validar);
	   if(validar=='S'){
	myDate = new Date();
   hours = myDate.getHours();
   minutes = myDate.getMinutes();
   seconds = myDate.getSeconds();
   if (hours < 10) hours = "0" + hours;
   if (minutes < 10) minutes = "0" + minutes;
   var horaactual=(hours+ ":" +minutes);
   var fechaactual="<?php echo date('Y/m/d '); ?>"
	var horaatencionformato2=$('#horarecomendacion').val();
	var fechaatencionformato2=$('#FechaRecomendacion').val();
	

	var fa= new Date(fechaactual);
	var fa3=new Date(fechaatencionformato2);
		 

	console.log(fa);
	console.log(fa3);
   
	if(fa3.valueOf()==fa.valueOf()){
				  console.log("las fechas son iguales"); 
			   }
			  
			   
			  if((fa3.valueOf() > fa.valueOf()) ){
			   swal('La fecha del formato no puede ser superior a la actual','','error');
	   
			   horaactual='';
			   return false;
			  
			  }
   
			   if(horaatencionformato2>horaactual && fa3.valueOf()==fa.valueOf()){
			   swal('La hora del formato no puede ser superior a la actual','','error' );
			   console.log(horaactual);
			   horaactual=''; 
			   return false; 
			  
			  }
			  
			  if(fechamaxima2 <= fechaserver2 && horasvalidar2 > 0 && editar_borrar_evos != 1 ){
          		swal('El tiempo permitido para guardar el formato es '+horasvalidar+' hora(s), este tiempo ya caduco','','error' );
          		return false;
			  }

		   }
	   else {
			   swal('EL PARAMETRO DE VALIDAR QUE LAS FECHAS INGRESADAS U HORAS EN LA ORDENES NO SEAN MAYORES A LAS ACTUALES SE ENCUENTRA INACTIVO','','error');
		   }
		}

function verificarfechaconsulta(){
   
   // AQUI  ///nueva validacion para que no deje registrar en fecha o hora superior a la actual
   <?php 
		   
		   $value=$model->RSAsociativo("SELECT value from parametrosgenerales where parametro like '%validarfechaenordenes%'");
		   
		   
		   ?>
		   		   		   var  dateServer="<?php echo date('Y-m-d'); ?>"
	var fechaserver = new Date(dateServer);
	
	var fechaCompleta=$("#FechaSolicitud").val();
	var fechauno = new Date(fechaCompleta);
    var fechaF = moment(fechaCompleta);
    var fechaA = moment(dateServer);
    var diferencia = fechaA.diff(fechaF, 'days');
     horasvalidar = 0; 
	TiempoMaxRegistrarEvo="<?php echo $model->getDato("value",'parametrosGenerales',"parametro='TiempoMaxRegistrarEvo'"); ?>"
	editar_borrar_evos = "<?php echo $model->getDato("editar_borrar_evos","sis_medi","cedula = '".$_SESSION['codigo_user']."'")?>"
	diasAtras = "<?php echo $model->getDato("value",'parametrosGenerales',"parametro='TiempoMaxRetroceder'"); ?>"

	if(TiempoMaxRegistrarEvo <= 0 && diasAtras > 0){
		if(diferencia>diasAtras){
			swal('Error! No puede poner fechas con mas de '+diasAtras+' dias atras','','error' );
			return false;
		}
	}
	if(  TiempoMaxRegistrarEvo > 0 ){ 
		horasvalidar = TiempoMaxRegistrarEvo; 
	}
	Date.prototype.addHours = function(h) {
		this.setTime(this.getTime() + (h*60*60*1000));
		return this;
		}


	var horasvalidar2=horasvalidar;
	var fechaCompleta2=$('#FechaSolicitud').val()+' '+$('#hora').val();
	var fechamaxima2 = new Date(fechaCompleta2).addHours(horasvalidar2);
	var dateServer2="<?php echo date('Y-m-d H:i:s'); ?>"
	var fechaserver2 = new Date(dateServer2);

	var horasvalidar3=horasvalidar;
	var fechaCompleta3=$('#FechaSolicitud').val()+' 00:00:00';
	var fechamaxima3 = new Date(fechaCompleta3).addHours(horasvalidar3);
	var dateServer3="<?php echo date('Y-m-d H:i:s'); ?>"
	var fechaserver3 = new Date(dateServer3);
   
	 var validar="<?php echo $value[0]['value']   ?>";
   console.log(validar);
	   if(validar=='S'){
	myDate = new Date();
   hours = myDate.getHours();
   minutes = myDate.getMinutes();
   seconds = myDate.getSeconds();
   if (hours < 10) hours = "0" + hours;
   if (minutes < 10) minutes = "0" + minutes;
   var horaactual=(hours+ ":" +minutes);
   var fechaactual="<?php echo date('Y/m/d '); ?>"
	var horaatencionformato2=$('#hora').val();
	var fechaatencionformato2=$('#FechaSolicitud').val();
	

	var fa= new Date(fechaactual);
	var fa3=new Date(fechaatencionformato2);
		 

	console.log(fa);
	console.log(fa3);
   
	if(fa3.valueOf()==fa.valueOf()){
				  console.log("las fechas son iguales"); 
			   }
			  
			   
			  if((fa3.valueOf() > fa.valueOf()) ){
			   swal('La fecha del formato no puede ser superior a la actual','','error');
	   
			   horaactual='';
			   return false;
			  
			  }
   
			   if(horaatencionformato2>horaactual && fa3.valueOf()==fa.valueOf()){
			   swal('La hora del formato no puede ser superior a la actual','','error' );
			   console.log(horaactual);
			   horaactual=''; 
			   return false; 
			  
			  }
			  if((fechamaxima2 <= fechaserver2 || fechamaxima3 <= fechaserver3) && horasvalidar2 > 0 && editar_borrar_evos != 1 ){
          swal('El tiempo permitido para guardar el formato es '+horasvalidar+' hora(s), este tiempo ya caduco','','error' );
          return false;
			  }
			  
		   }
	   else {
			   swal('EL PARAMETRO DE VALIDAR QUE LAS FECHAS INGRESADAS U HORAS EN LA ORDENES NO SEAN MAYORES A LAS ACTUALES SE ENCUENTRA INACTIVO','','info');
		   }
		}

</script>
<?php
}
?>
<style>

	#ttabs_wrapper {
		width: 100%;
	}
	#ttabs_container {
		border-bottom: 1px solid #ccc;
	}
	#ttabs {
		list-style: none;
		padding: 5px 0 4px 0;
		margin: 0 0 0 10px;
		font: 0.75em arial;
	}
	#ttabs li {
		display: inline;
	}
	#ttabs li a {
		border: 1px solid #ccc;
		padding: 4px 6px;
		text-decoration: none;
		background-color: #B8CAEA;
		border-bottom: none;
		outline: none;
		border-radius: 5px 5px 0 0;
		-moz-border-radius: 5px 5px 0 0;
		-webkit-border-top-left-radius: 5px;
		-webkit-border-top-right-radius: 5px;
	}
	#ttabs li a:hover {
		background-color: #8EA7D1;
		padding: 4px 6px;
	}
	#ttabs li.tactive a {
		border-bottom: 1px solid #fff;
		background-color: #34b0c4;
		padding: 4px 6px 5px 6px;
		border-bottom: none;
		color: white;
	}
	#ttabs li.tactive a:hover {
		background-color: #8EA7D1;
		padding: 4px 6px 5px 6px;
		border-bottom: none;
	}
	
	#ttabs li a.ticon_accept {
		background-image: url(imagenes/estados/dental.png);
		background-position: 5px;
		background-repeat: no-repeat;
		padding-left: 24px;
	}
	#ttabs li a.icon_accept:hover {
		padding-left: 24px;
	}
	
	#ttabs_content_container {
		border: 1px solid #ccc;
		border-top: none;
		padding: 10px;
		width: 100%;
	}
	.ttab_content {
		display: none;
	}
	
	.tabDiag:hover{
		background-color:#CCD7E4;
		cursor:pointer;
	}
	
	div#hc_sin_diligenciar p{
		text-align:center;
		background-color:red;
		border:#993300 solid 2px;
		margin:4px;
		font-size:11pt;
		color:#FFF;
		text-transform:uppercase;
	}
	
	.DivServicios{
		width: 99%;
		text-align: left;
		border: solid !important;
		border-width: 1px !important;
		border-color: #34b0c4 !important;
		margin-top:4px;
		background-color: #FFF;
		min-height: 20px;
	}
	
	.DivServicios:hover,.servicioSeleccionado{
		outline:dashed !important;
		outline-width:1px !important;
		cursor:pointer !important;
		background-color:#C0DBA7 !important;
		font-weight:bold !important;
	}
	
	.servicioSeleccionado table tr td{
		font-weight:bold !important;
	}
	
	.DivTipoOrden{
		width: 99%;
		text-align: left;
		border: solid !important;
		border-width: 1px !important;
		border-color: #34b0c4 !important;
		margin-top:4px;
		background-color: #FFF;
		min-height: 20px;
	}
	
	.DivTipoOrden:hover,.tipoOrdenSeleccionado{
		outline:dashed !important;
		outline-width:1px !important;
		cursor:pointer !important;
		background-color:#C0DBA7 !important;
		font-weight:bold !important;
	}
	
	.tipoOrdenSeleccionado table tr td{
		font-weight:bold !important;
	}
</style>
    
<body bgcolor="#f5f5f5">
<?php
$rs=$model->select("con.esMontoPcte,con.NroItemProc,con.NroItemMed,sm.autoid,con.empresa","sis_maes sm,contratos con ","sm.con_Estudio=".$_REQUEST["estudio"]."
and con.codigo=sm.contrato");
$row=$model->nextRow($rs);
$esMontoPcte=$row["esMontoPcte"];
$NroItemProc=$row["NroItemProc"];
$NroItemMed=$row["NroItemMed"];
$autoid = $row["autoid"];
$empresa =$row["empresa"];

echo '<input type="hidden" name="NroItemMed" id="NroItemMed" value="'.$NroItemMed.'">';
echo '<input type="hidden" name="NroItemProc" id="NroItemProc" value="'.$NroItemProc.'">';
echo '<input type="hidden" name="auto_id" id="auto_id" value="'.$autoid.'">';


$es_eps=$model->getDato("es_eps","sis_maes","con_estudio=".$_REQUEST["estudio"]);
echo '<input type="hidden" name="es_eps" id="es_eps" value="'.$es_eps.'">';
$TipoEstudio=$model->getDato("tipo_estudio","sis_maes","con_estudio=".$_REQUEST["estudio"]);
echo '<input type="hidden" name="TipoEstudio" id="TipoEstudio" value="'.$TipoEstudio.'">';
echo '<input type="hidden" name="manejaEPS" id="manejaEPS" value="'.$model->getParametroGeneral('ManejaProcesosEPS','EPS').'">';
echo '<input type="hidden" name="ServicioSolicitudExterna" id="ServicioSolicitudExterna" value="'.$model->getParametroGeneral('ServicioSolicitudExterna','HC').'">';
$tipoManual = $model->tipoManual($_REQUEST['estudio']);
$emprexvademecum = $model->getDato("value","parametrosgenerales","parametro='EmpresasxVademecum'");	
$existeempre = $model->RsAsociativo("SELECT 1 as Valor FROM dbo.Split('".$emprexvademecum."',',') WHERE items ='".$empresa."'");	
if ($existeempre[0]['Valor']==1){
	$display ='style="display:none;"';
	echo $display;
}


?>
<input name="DispensaMcto" type="hidden" id="DispensaMcto" value="<?php echo $_REQUEST["DispensaMcto"]; ?>" />
<input name="tipoManual" type="hidden" id="tipoManual" value="<?php echo $tipoManual; ?>" />
<input name="estudio" type="hidden" id="estudio" value="<?php echo $_REQUEST["estudio"]; ?>" />
<input name="esMontoPcte" type="hidden" id="esMontoPcte" value="<?php echo $esMontoPcte; ?>" />
<input name="estadoHC" type="hidden" id="estadoHC" value="<?php echo $model->getDato("estado","hcingres","con_estudio=".$_REQUEST["estudio"]); ?>" />
<input name="PermitirModificarCTC" type="hidden" id="PermitirModificarCTC" value="<?php echo $model->getParametroGeneral("PermitirModificarCTC","HISTORIA CLINICA"); ?>" />
<input name="dxPrincipal" type="hidden" id="dxPrincipal" value="<?php echo $model->getDato("dx.codigo+' - '+dx.descripcion","sis_maes sm,sis_diags dx","sm.diagno_ing=dx.codigo and con_estudio=".$_REQUEST["estudio"]); ?>" />

<input type="hidden" name="hiddenCopiaCarnet" id="hiddenCopiaCarnet">
<input type="hidden" name="hiddenCopiaFm" id="hiddenCopiaFm">
<input type="hidden" name="hiddenCopiaHc" id="hiddenCopiaHc">

<input type="hidden" name="hiddenRiesgoSalud" id="hiddenRiesgoSalud">
<input type="hidden" name="hiddenPosibilidadesTerapeuticas" id="hiddenPosibilidadesTerapeuticas">
<input type="hidden" name="correoPcte" id="correoPcte" value="<?php echo $_REQUEST["correoPcte"]; ?>">
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td height="64" valign="top">
     <table width="100%" border="0" cellpadding="3" cellspacing="3">
      <tr>
        <td>
			<?php 
				if($EsDescripcionQX!=1&&!isset($_REQUEST["isCenso"])&&!isset($_REQUEST["EsNovedad"])){
					if($desdeParaclinicos==0){
					include ("InfoPaciente.php");
					} 
				}
			?>
		</td>
      </tr>
      <tr>
	  <style>
    .btn-mipres {
        display: inline-block;
        padding: 14px 26px;
        font-size: 17px;
        font-weight: bold;
        color: #fff;
        background-color: #1aa3b0;
        border-radius: 6px;
        text-decoration: none;
        box-shadow: 0 0 10px rgba(26, 163, 176, 0.6);
        animation: mipresGlow 1.8s infinite ease-in-out;
        transition: transform 0.2s ease;
    }

    .btn-mipres:hover {
        transform: scale(1.05);
        animation: none;
    }

    @keyframes mipresGlow {
        0% {
            box-shadow: 0 0 6px rgba(26, 163, 176, 0.4);
        }
        50% {
            box-shadow: 0 0 20px rgba(26, 163, 176, 0.9);
        }
        100% {
            box-shadow: 0 0 6px rgba(26, 163, 176, 0.4);
        }
    }
</style>

<td>
    <table width="100%">
        <!-- Acceso directo a MIPRES -->
        <tr>
            <td colspan="3" align="left" style="padding: 12px 0;">
                <a href="https://mipres.sispro.gov.co/MIPRESNOPBS/"
                   target="_blank"
                   class="btn-mipres">
                    Acceso directo a MIPRES
                </a>
            </td>
        </tr>
      	<td>
      		<table width="100%">
      			<tr>
      				<td width="20px"><input type="checkbox" name="VerOrdAnteriores" id="VerOrdAnteriores"></td>
      				<td class="letraDisplay">Ver Ordenes de Otros Estudios</td>
					<?php if($imprime_hc != '0'){ ?>
      				<td width="200px"><a href="javascript:void(0)" onclick="fnImprimirHistoriaConsultaExterna(2, true);">(Imprimir Ordenes Externas)</a></td>
					  <?php } ?>
      			</tr>
      		</table>
      	</td>
      </tr>
      <tr>
      	<td>
        <div id="ttabs_wrapper">
        <div id="ttabs_container">
            <ul id="ttabs">
                <li class="tactive"><a titulo="<?php echo ((!isset($_REQUEST["isCenso"]))?'Solicitud De Procedimientos':'Procedimientos');?>" tipo="procedimientos" href="#ttab2">Procedimientos</a></li>
              
                <!-- <li><a titulo="< php echo ((!isset($_REQUEST["isCenso"]))?'Solicitud De Procedimientos Quirurgicos':'Procedimientos Quirurgicos');?>" tipo="04" href="#ttab1">Proc. Terapeuticos Quirurgicos</a></li>
                <li><a titulo="< ?php echo ((!isset($_REQUEST["isCenso"]))?'Solicitud De Procedimientos De Diagnosticos':'Procedimientos De Diagnosticos');?>" tipo="02" href="#ttab3">Procedimientos de Diagnosticos</a></li>-->
                
					<?php if(!isset($_REQUEST["isCenso"])){?>
                    <?php if($model->getParametroGeneral('ocultarRecetasSolicitudExterna','HISTORIA CLINICA')=='N'||($model->getParametroGeneral('ocultarRecetasSolicitudExterna','HISTORIA CLINICA')=='S'&&$_REQUEST["DispensaMcto"]!='1')){?>
                    <li <?php echo $display; ?>><a titulo="<?php echo ((!isset($_REQUEST["isCenso"]))?'Recetas':'Medicamentos');?>" tipo="medicamentos" href="#ttab4"><?php echo ((!isset($_REQUEST["isCenso"]))?'Recetas':'Medicamentos');?></a></li>
                    <?php }else if($_REQUEST["DispensaMcto"]=='1'){	
									
						?>
                    	<li ><a titulo="Receta" tipo="Receta" href="#ttab7">Receta</a></li>
                    <?php
                    	}?>
                    <li><a titulo="Consulta Especializada" tipo="01" href="#ttab6">Consulta Especializada</a></li>
                    <li><a titulo="Recomendaciones" tipo="Recomendaciones" href="#ttab8">Recomendaciones</a></li>
                    <?php }?>
					<li><a titulo="Formula Medica" tipo="FormulaMedica" href="#ttab10">Formula Medica</a></li>
					
					<?php  
					$ValidarRegimenEnIncapacidades = strtolower($model->getParametroGeneral('ValidarRegimenEnIncapacidades', 'HISTORIA CLINICA'));
					$regimen = $model->getDato('c.regimen', 'sis_maes Sm INNER JOIN contratos c ON Sm.contrato = c.codigo', "Sm.con_estudio = {$_REQUEST['estudio']}");
					if ($ValidarRegimenEnIncapacidades=='s' && $regimen==1 || $regimen==5 || $regimen==6){
					?>
                    <li><a titulo="Incapacidades" tipo="Incapacidades"  href="#ttab9">Incapacidades</a></li>
					<?php } elseif ($ValidarRegimenEnIncapacidades=='n') { ?>
						<li><a titulo="Incapacidades" tipo="Incapacidades"  href="#ttab9">Incapacidades</a></li>
					<?php } ?>
            </ul>
        </div>
        <div id="ttabs_content_container">
           <!-- <div id="ttab1" class="ttab_content">
                <p>
					< ?php 
						$tipoOrden="04";
						include("detalle_Ordenes_Externas.php"); 
					?>
               </p>
            </div>-->
            <div id="ttab2" class="ttab_content" style="display: block;">
                <p>
					<?php 
						$tipoOrden="procedimientos";
						include("detalle_Ordenes_Externas_new.php"); 
					?>
                </p>
            </div>
            <!--<div id="ttab3" class="ttab_content">
                <p>
					< ?php 
						$tipoOrden="02";
						include("detalle_Ordenes_Externas.php"); 
					?>
                </p>
            </div>-->
            <div id="ttab4" class="ttab_content">
                <p>
                	<?php 
						$tipoOrden="medicamentos";
						include("detalle_Ordenes_Externas.php"); 
					?>
                </p>
            </div>
            <div id="ttab5" class="ttab_content">
                <p>
                	<?php 
						$tipoOrden="sol_medicamentos_dosis";
						include("detalle_Ordenes_Externas.php"); 
					?>
                </p>
            </div>
            <div id="ttab6" class="ttab_content">
                <p>
                	<?php 
						$tipoOrden="01";
						include("detalle_Ordenes_Externas.php"); 
					?>
                </p>
            </div>
            <div id="ttab7" class="ttab_content">
                <p>
                	<iframe id="frameDispensacionReceta" width="100%" style="border:0" height="500px"></iframe>
                </p>
            </div>
            <div id="ttab8" class="ttab_content">
                <p>
                	<?php 
						$tipoOrden="Recomendaciones";
						include("detalle_recomendaciones.php"); 
					?>
                </p>
            </div>
            <div id="ttab9" class="ttab_content">
                <p>
                	<?php 
						$tipoOrden="Incapacidades";
						include("detalle_incapacidades.php"); 
					?>
                </p>
            </div>
			<div id="ttab10" class="ttab_content">
                <p>
                	<?php 
						$tipoOrden="FormulaMedica";
						include("detalle_formula_medica.php"); 
					?>
                </p>
            </div>
        </div>
    </div>
        </td>
      </tr>
    </table>
    </td>
  </tr>
</table>
</body></html>
