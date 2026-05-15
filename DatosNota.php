<?php
require_once('../../Model/Model.php');
require_once('../../Model/WebModel.php');
require_once('../General.php');
require_once('menu_hc.php');
$model = new Model();
$model->online();
$wmodel = new WebModel();
$wmodel->Head();
$frame_interno = false;
$frame_interno_req = $_REQUEST["frame_interno"];
if ($frame_interno_req == 1) {
  $frame_interno = true;
} else {
  $frame_interno = false;
}

$permitirEditarEliminarNotasEnfermeria = $model->getParametroGeneral('permitirEditarEliminarNotasEnfermeria', 'ENFERMERIA');
$existeNota = $model->getDato('1 existe', 'notas_enfermeria', 'numero=' . $_REQUEST['numero']);
$urlParams = $model->ReutilizarREQUEST($_REQUEST, "&estado_his&msg&isNotaCerrada&op&operacion&n&numero&");
$imprime_hc = $model->getDato("imprimir_hc", "sis_nivelacceso", "codigo='" . $_SESSION['nivel_acceso'] . "'");

//configuracion de nota aclaratoria
$conf_nota = $model->getDato('permiso', 'config_nota_aclaratoria', "tipo_documento = 'NotaEnfermeria' AND estado = 1");
$conf_nota = (isset($conf_nota) ? $conf_nota : 'IC');

$metodo_salir = "";
if ($_REQUEST['frame_interno'] == '1') {
  $metodo_salir = "accion_externa=recargar&op=" . $_REQUEST["op"];
}

if ($_REQUEST['general_paciente'] != "si") {
  if (!$frame_interno) { ?>

    <script language="javascript" src="../../Comun/Js/puerta.js"></script>
    <!---------- Menu Desplegable ------------------------>
    <script language="JavaScript1.2" src="../../Comun/menu/mm_menu.js"></script>
    <!---------- Fin Menu Desplegable ------------------------>

    <script language="javascript">
      var arrayBton = new Array();
      arrayBton[0] = ["Nuevo", "reDirect('Contrato.php')"];
      arrayBton[1] = ["Guardar", "guardar()"];
      //arrayBton[2] = ["Eliminar","Enviar('formulario','CtrlContrato.php?operacion=eliminar','',true)"];
      arrayBton[2] = ["Buscar", "buscar()"];
      arrayBton[3] = ["Imprimir", "Imprimir()"];
      arrayBton[4] = ["Salir", "reDirect('../main.php')"];



      function ReLoad() {
        document.f.datosnota.focus();
      }
    </script>

    <link href="../../Comun/Css/estilo-pg.css" rel="stylesheet" type="text/css" />
    <link href="../../Comun/Css/Myestilo.css" rel="stylesheet" type="text/css" />
    <link href="../../Comun/Css/main.css" rel="stylesheet" type="text/css" />

<?php createMenu("ordenes");
  }
}
?>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="http://momentjs.com/downloads/moment.min.js"></script>
<script type="text/javascript">
  //var x = document.getElementById("ta2");
  //x.addEventListener("blur", calcularTAM, true);

  function calcularTAM() {
    //alert ("Llego");
    var tas = ($("#ta1").val());
    var tad = ($("#ta2").val());

    if (tas == "" || tad == "") {
      return 1;
    }

    var resta = parseInt(tas) - parseInt(tad)
    var suma = parseInt(tad) + parseInt(1 / 3);

    var tam = parseInt(tad) + ((parseInt(tas) - parseInt(tad)) / 3) //suma * resta;
    tam = Math.round(tam * 100) / 100;
    $("#TAM").val(tam);
  }

  function verRecomendaciones() {
    invocarReporte('reporte_recomendaciones_medicas.php?estudio=' + "<?php echo $_REQUEST['estudio']; ?>", 'eferm')
  }

  function guardar(frame_interno_req, posicion_nota, solovitales, op) {
    var notaCerrada = ($('#notaCerrada').is(":checked") ? '1' : '0');

  novalidar = 'peso|glucometria|nom_supervisor|cod_supervisor|';
    if ($("#validarSignosVitalesNotasEnfermeria").val() == 'N') {
      novalidar += 'ta1|ta2|fr|fc|temp|o2|TAM|alerta_conciencia|datosnota|';
      // 
    }


    // AQUI  ///nueva validacion para que no deje registrar en fecha o hora superior a la actual
    <?php

    $value = $model->RSAsociativo("SELECT value from parametrosgenerales where parametro like '%validarfechaenordenes%'");


    ?>
    var dateServer2 = "<?php echo date('Y-m-d'); ?>"
    var fechaserver2 = new Date(dateServer2);

    var fechaCompleta3 = $("#fecha").val();

    var fechaF = moment(fechaCompleta3);
    var fechaA = moment(dateServer2);
    var diferencia = fechaA.diff(fechaF, 'days');
    console.log(diferencia);
    var fechaRegistro = "<?php echo $model->getDato("fechaRegistro", "nota_enfermia", "ingreso =" . $_REQUEST["estudio"] . " and numero = " . $_REQUEST["numero"]); ?>";
    var fechaAtencionHistoria = $('#fecha').val();
    var fechau = new Date(fechaRegistro);
    var fechad = new Date(fechaAtencionHistoria);

    if (fechad > fechau && fechaRegistro != '') {
      swal("La fecha de atencion no puede ser superior a la fecha de registro " + fechaRegistro, '', 'error');
      return false;
    }
    horasvalidar = 0;
    TiempoMaxRegistrarEvo = "<?php echo $model->getDato("value", 'parametrosGenerales', "parametro='TiempoMaxRegistrarEvo'"); ?>"
    editar_borrar_evos = "<?php echo $model->getDato("editar_borrar_evos", "sis_medi", "cedula = '" . $_SESSION['codigo_user'] . "'") ?>"
    diasAtras = "<?php echo $model->getDato("value", 'parametrosGenerales', "parametro='TiempoMaxRetroceder'"); ?>"

    if (TiempoMaxRegistrarEvo <= 0 && diasAtras > 0) {
      if (diferencia > diasAtras) {
        swal('Error! No puede poner fechas con mas de ' + diasAtras + ' dias atras', '', 'error');
        return false;
      }
    }
    if (TiempoMaxRegistrarEvo > 0) {
      horasvalidar = TiempoMaxRegistrarEvo;
    }
    Date.prototype.addHours = function(h) {
      this.setTime(this.getTime() + (h * 60 * 60 * 1000));
      return this;
    }


    var horasvalidar2 = horasvalidar;
    var fechaCompleta2 = $('#fecha').val() + ' ' + $('#hora').val();
    var fechamaxima2 = new Date(fechaCompleta2).addHours(horasvalidar2);
    var dateServer2 = "<?php echo date('Y-m-d H:i:s'); ?>"
    var fechaserver2 = new Date(dateServer2);



    var validar = "<?php echo $value[0]['value']   ?>";
    console.log(validar);
    if (validar == 'S') {
      myDate = new Date();
      hours = myDate.getHours();
      minutes = myDate.getMinutes();
      seconds = myDate.getSeconds();
      if (hours < 10) hours = "0" + hours;
      if (minutes < 10) minutes = "0" + minutes;
      var horaactual = (hours + ":" + minutes);
      var fechaactual = "<?php echo date('Y/m/d '); ?>"
      var horaatencionformato = $('#hora').val();
      var fechaatencionformato = $('#fecha').val();

      var fa = new Date(fechaactual);
      var fa2 = new Date(fechaatencionformato);

      console.log(fa);
      console.log(fa2);

      if (fa2.valueOf() == fa.valueOf()) {
        console.log("las fechas son iguales");
      }
      console.log(fa2);
      console.log(fa);

      if ((fa2.valueOf() > fa.valueOf())) {
        swal('La fecha del formato no puede ser superior a la actual', '', 'error');

        horaactual = '';
        return false;
      }

      if (horaatencionformato > horaactual && fa2.valueOf() == fa.valueOf()) {
        swal('La hora del formato no puede ser superior a la actual', '', 'error');
        console.log(horaactual);
        horaactual = '';
        return false;
      }

      if (fechamaxima2 <= fechaserver2 && horasvalidar2 > 0 && editar_borrar_evos != 1) {
        swal('El tiempo permitido para guardar el formato es ' + horasvalidar + ' hora(s), este tiempo ya caduco', '', 'error');
        return false;
      }


    } else {
      swal('EL PARAMETRO DE VALIDAR QUE LAS FECHAS INGRESADAS U HORAS EN LA ORDENES NO SEAN MAYORES A LAS ACTUALES SE ENCUENTRA INACTIVO', '', 'info');
    }
    ///HASTA AQUI

    if (fnValidarNumeroDecimal(document.getElementById('temp')) && fnValidarNumeroDecimal(document.getElementById('glucometria'), 1)) {
      Enviar('f', 'CtrlDatosNota.php?operacion=guardar_not&frame_interno=' + frame_interno_req + '&posicion_nota=' + posicion_nota + '&solovitales=' + solovitales + '&op=' + op + "&isNotaCerrada=" + notaCerrada + '<?php echo $urlParams; ?>', 'glucometria|' + novalidar);
    }

  }

  function agregar_articulo_costo(id) {
    //let art = Document.getElementById('ART'+id);

    let codigo = id;
    let nombre = $('#ART' + id).data('nom');
    let cantidad_ = $('#CANT' + id).val();
    let cantidad_max = $('#ART' + id).data("cantmax");

    var aux = 0;
    var cont = 0;

    if (cantidad_max < cantidad_) {
      aux = 1;
      cont = 1;
      swal('Ha ingresado una cantidad superior a la cantidad maxima.', '', 'error');
    } else {
      $("#tabla_art tr").each(function() { //Recorremos cada fila de la tabla segun su posición
        condicion_ = $(this).data('condicion');
        if (condicion_ == 'add') {
          cont++;
          cantidad_med = $(this).data('cant');
          codigo_ = $(this).data("cod");
          //idarticulo = $(this).data("idarticulo");

          if (codigo == codigo_) {
            aux = 1;
            swal('Este articulo ya fue agregado', '', 'error');
          }

          if (cantidad_med == 0) {
            aux = 1;
            swal('No se admiten cantidades en cero (0)', '', 'error');
          }
        }
      });
    }

    if (aux != 1 || cont == 0) {
      if ((cantidad_ > 0) || (cantidad_ != '')) {
        $('#tabla_art').prepend(
          `<tr id='ARTADD${codigo}' data-cod='${codigo}' data-cant='${cantidad_}' data-condicion='add' style='background-color: #D3DFE4'>
                <td align='center' class="letraDisplay">${codigo}</td>
                <td class="letraDisplay">${nombre}</td>
                <td align='center' class="letraDisplay">${cantidad_}</td>
                <td align='center' class="letraDisplay">
                  <a style="cursor:pointer;" onclick="eliminar_articulo_costo('ARTADD${codigo}')"><img src="../../Imagenes/Eliminar.png" width="15" border="0"></a>
                </td>
              <tr>`
        );
        $("#stock").attr("disabled", true).trigger("chosen:updated");
        $('#CANT' + id).val("");
      } else {
        swal('Agregue una cantidad al articulo: ' + nombre, '', 'error');
      }

    }
  }

  function eliminar_articulo_costo(id2) {
    $('#' + id2).remove();
    if ($("#tabla_art tr[data-condicion='add']").length <= 0) {
      $("#stock").attr("disabled", false).trigger("chosen:updated");
    }
  }

  function guardar_articulo_costo() {
    /* Guardar los medicamentos de consumo detallado  */

    var info = new Array();
    var cantidad_med = 0;
    var numero = $("#numero").val();
    var estudio = $("#estudio").val();
    var stock = $("#stock").val();

    let datosalma = false;
    if (stock != "") {
      $("#tabla_art tr").each(function() { //Recorremos cada fila de latabla segun su posición
        condicion_ = $(this).data('condicion');

        if (condicion_ == 'add') {
          cantidad_med = $(this).data('cant');
          codigo_ = $(this).data("cod");
          if ((cantidad_med != "") || (cantidad_med > 0)) {
            info.push({
              "cantidad": cantidad_med,
              "codigo": codigo_
            });
          }
        }
      });
    } else {
      swal('Seleccione un stock', '', 'info');
    }

    if (!$.isEmptyObject(info)) {
      ajaxPOST("CtrlDatosNota.php", "", "datos_almacenados()", "", "operacion=guardar_articulo_costo&info=" + JSON.stringify(info) + "&numero=" + numero + "&estudio=" + estudio + "&stock=" + stock);
    } else {
      swal('No hay articulos que enviar.', '', 'info');
    }
  }

  function datos_almacenados() {
    var resp = JSON.parse(_ResquestAJAX);
    //console.log(resp);
    if (resp.msg) {
      swal(resp.msg, "", "info");
      medicamentos_consumo_detallado("");
      Mostrar_historial();
      $("#stock").attr("disabled", false).trigger("chosen:updated");
    } else {
      swal(resp.error, "", "error");
    }

  }

  function Mostrar_historial() {
    mostrar_history = function() {
      let resp = JSON.parse(_ResquestAJAX);
      let elementos_ = eval(resp.datos_art_costos);


      let html = '';
      $.each(resp.datos_art_costos, function(indice, elemento_eval) {
        html += `
      <tr>
        <td class="letraDisplay" >${elemento_eval['codigo']}</td>
        <td class="letraDisplay" >${elemento_eval['descripcion']}</td>
        <td class="letraDisplay" >${elemento_eval['cantidad']}</td>
      </tr>`
      });
      $('#tabla_art').html(html);
    }
    ajaxPOST("CtrlDatosNota.php", "", "mostrar_history()", "", "operacion=consulta_historial_costos&estudio=" + <?php echo $_REQUEST["estudio"]; ?>);
  }

  function fnDatosDescripcionQx() {
    var url = "CtrlDatosNota.php";
    var data = new Object();
    data.operacion = "DatosDescripcionQx";
    data.numero = $("#numero").val();
    data.estudio = $("#estudio").val();
    var _window = new ModalContentZeusSalud("Registrar datos Descripcion Qx", url, data, "json");
    _window.imgs_data = {
      close_icon: {
        src: "../../ImagenesZeus/salir 01.png",
        _w: 28,
        _h: 28,
        _class: "sis-iconAction close",
        _title: "Cerrar",
        id: "cerrar"
      },
      confirmar_icon: {
        src: "../../ImagenesZeus/guardar 01.png",
        _w: 28,
        _h: 28,
        _class: "icon",
        _title: "Registrar datos",
        id: "confirmar",
        fnAction: fnGuardarDatosQx
      }
    };
    _window.process();
  }

  function fnBuscarIntrumentador() {
    showWindowFind('m.codigo, m.nombre',
      'sis_medi AS m', 'm.es_medico | 0 And m.estado | 1',
      'Codigo, Nombre, Valor',
      '60, 300, 80',
      'codigoInstrumentador#nombreInstrumentador',
      'Instrumentador', '')
  }

  function fnGuardarDatosQx() {
    var url = "CtrlDatosNota.php?operacion=ResgistrarDatosQx";
    url += "&numero=" + $("#numero").val() + "&estudio=" + $("#estudio").val();
    var noValidar = "codigoInstrumentador|nombreInstrumentador|codigoRotador|nombreRotador";
    deshabilitarToolBar();
    procesador.procesar = function(response) {
      var errorType = null;
      if (response.statusTran == "error") {
        errorType = "error";
      }
      swal(response.infoTran, "", errorType);
    }
    getJSONAjax("datosQxForm", url, procesador, "POST", false, noValidar);
  }

  function fnBuscarRotador() {
    showWindowFind('m.codigo, m.nombre',
      'sis_medi AS m', 'm.estado | 1',
      'Codigo, Nombre, Valor',
      '60, 300, 80',
      'codigoRotador#nombreRotador',
      'Instrumentador', '')
  }

  function fnClearFields(field) {
    $("#codigo" + field).val("");
    $("#nombre" + field).val("");
  }

  function fnValidarNumeroDecimal(_this, NoValidaVacio) {
    var m = _this.value;

    if ($("#validarSignosVitalesNotasEnfermeria").val() == 'N' && $.trim(m) == '') {
      return true;
    }

    if ($.trim(m) == '' && NoValidaVacio == 1) {
      return true;
    }
    var expreg = new RegExp("^[0-9]+(\.[0-9]+)?$");

    if (!expreg.test(m)) {
      swal("El valor digitado no es un numero", "", "error");
      $(_this).focus();
      return false;
    }

    return true;
  }

  function selec_stocks() {
    var stock = $(this).val();
    medicamentos_consumo_detallado(stock);
  }

  /* Mostrar los medicamentos con consumo detallado  */
  function medicamentos_consumo_detallado(stock) {

    if (stock == "") {
      stock = $("#stock").val();
    }
    ajaxPOST("CtrlDatosNota.php", "", "render_tabla()", "", "operacion=medicamentos_consumo_detallado&estudio=" + <?php echo $_REQUEST["estudio"]; ?> + "&stock=" + stock);

  }

  function render_tabla() {
    var consulta = JSON.parse(_ResquestAJAX);

    var string = "";
    var total = 0;

    $.each(consulta.resp, function(indice, resp) {
      /*Incrementa hacia arriba el valor */
      // total = Math.ceil(parseFloat(resp.cantidad_porcion) * parseFloat(resp.Existencias));
      total = resp.Porciones;
      string += ` <tr data-codigo ='${resp.codigo}' data-nom ='${resp.descripcion}' id='ART${resp.codigo}'
       data-cantmax = '${total}'>" 
       <td class="letraDisplay">${resp.codigo}</td>
       <td class="letraDisplay">${resp.descripcion}</td>
       <td align="center" class="letraDisplay">${total}</td>
       <td><input class="proc_costo" id="CANT${resp.codigo}" type="number" min="0" max="${total}" style="width:50px" value="" /></td>
       <td><a style="cursor:pointer;" onclick="agregar_articulo_costo('${resp.codigo}')">
         <img src="../../Imagenes/guardar 01.png" width="18" border="0"></a></td>`;
    });

    $("#tabla_render").html(string);
  }



  function NotaAclaratoria() {
    scroll(0, 0);
    var estudio = $("#estudio").val();
    var NroItem = getValue("numero");
    var widget = new ModalContentZeusSalud("Notas Aclaratorias",
      "NotaAclaratoria.php?tipo_documento=NotaEnfermeria&estudio=" + estudio + "&numero=" + NroItem, {}, "general", "", "80%", "2%", null, null);
    widget.imgs_data = {
      close_icon: {
        src: "../../ImagenesZeus/salir 01.png",
        _w: 28,
        _h: 28,
        _class: "sis-iconAction close",
        _title: "Cerrar",
        id: "cerrar"
      },

      confirmar_icon: {
        src: "../../ImagenesZeus/guardar 01.png",
        _w: 28,
        _h: 28,
        _class: "icon",
        _title: "Guardar",
        id: "guardar",
        fnAction: function() {
          guardarNotaAclaratoria();
        }
      }
    };
    widget.process();
  }

  function guardarNotaAclaratoria() {
    var estudio = $("#estudio").val();
    var NroItem = getValue("numero");
    var nota_aclaratoria = getValue("nota_aclaratoria");
    if ($.trim(nota_aclaratoria) == "") {
      swal("La nota aclaratoria no puede ser vacia", "", "error");
    } else {
      procesador.procesar = function(response) {
        if (response.error) {
          swal(response.msg, "", "error");
        } else {
          ListarNotasAclaratorias();
          swal(response.msg, "", "info");
          get("nota_aclaratoria").value = "";
        }
      }

      getJSONAjax({
        nota_aclaratoria: nota_aclaratoria,
        estudio: estudio,
        numero: NroItem
      }, "CtrlNotaAclaratoria.php?operacion=guardar&tipo_documento=NotaEnfermeria", procesador, "POST", 0, "");
    }
  }

  function ListarNotasAclaratorias() {
    var estudio = $("#estudio").val();
    var NroItem = getValue("numero");
    var tipo_documento = "NotaEnfermeria";
    procesador.procesar = function(response) {
      $("#listNotaAclaratoria").html(response.lista);
    }

    getJSONAjax({
      estudio: estudio,
      tipo_documento: tipo_documento,
      numero: NroItem
    }, "CtrlNotaAclaratoria.php?operacion=listar", procesador, "GET", 0, "");
  }

  function EliminarNotaAclaratoria(id) {
    conf = confirm("Seguro desea eliminar la nota aclaratoria?");
    if (conf) {
      procesador.procesar = function(response) {
        if (response.error) {
          swal(response.msg, "", "error");
        } else {
          ListarNotasAclaratorias();
          swal(response.msg, "", "info");
        }
      }
      getJSONAjax({
        id: id
      }, "CtrlNotaAclaratoria.php?operacion=eliminar", procesador, "GET", 0, "");
    }
  }

  function buscarMedi() {
    showWindowFind('codigo,nombre', 'sis_medi', 'es_medico | 1 and es_especialista | 1', 'Codigo,Nombre', '80,350', 'cod_supervisor#nom_supervisor', 'Medicos', '');

  }
</script>
<script type="text/javascript" src="../../js/mask.js"></script>
<script>
  $(document).ready(function(e) {
    medicamentos_consumo_detallado("");
    $("#stock").chosen({});
    calcularTAM();
  });
</script>
<br />
<?php
$validarSignosVitalesNotasEnfermeria = $model->getParametroGeneral('validarSignosVitalesNotasEnfermeria', 'HISTORIA CLINICA');
?>
<input type="hidden" name="validarSignosVitalesNotasEnfermeria" id="validarSignosVitalesNotasEnfermeria" value="<?php echo $validarSignosVitalesNotasEnfermeria; ?>">
<div>
  <table width="99.7%" border="0" cellpadding="0" cellspacing="0">
    <?php if ($_REQUEST['general_paciente'] != "si") {
      if (!$frame_interno) { ?>
        <tr>
          <td height="64" valign="top">
            <table width="100%" border="0" cellpadding="3" cellspacing="3">
              <tr>
                <td><?php include("InfoPaciente.php"); ?></td>
              </tr>
            </table>
          </td>
        </tr>
    <?php }
    } ?>
    <?php
    if ($frame_interno) {
      $rs = $model->select(
        "sp.full_name_tipo_id_inv as Paciente,dbo.fnEdadAproximadaAtencion(sp.fecha_naci,convert(date,isnull(fecha_estado_res, GETDATE()))) as Edad,
	  				      sp.tipo_sangre as TipoSangre,sp.sexo as Sexo",
        "sis_maes sm,pacientesView sp",
        "sm.con_estudio=" . $_REQUEST["estudio"] . "
					   	  and sm.autoid=sp.autoid"
      );
      $row = $model->nextRow($rs);
    ?>
      <tr>
        <td height="64" valign="top">
          <table width="100%" border="0" cellpadding="3" cellspacing="3" class="LSPRESSE">
            <tr>
              <td colspan="8" height="19" <?php $wmodel->linea(); ?> class="letraCaptionNegrita">&nbsp;Datos del Paciente</td>
            </tr>
            <tr>
              <td class="letraDisplay">Paciente:</td>
              <td class="letraDisplay"><b><?php echo $row["Paciente"]; ?></b></td>
              <td class="letraDisplay" width="30px">Edad:</td>
              <td class="letraDisplay" width="100px"><b><?php echo $row["Edad"]; ?></b></td>
              <td class="letraDisplay" width="80px">Tipo Sangre:</td>
              <td class="letraDisplay" width="100px"><b><?php echo $row["TipoSangre"]; ?></b></td>
              <td class="letraDisplay" width="30px">Sexo:</td>
              <td class="letraDisplay" width="100px"><b><?php echo $row["Sexo"]; ?></b></td>
            </tr>
          </table>
          <!-- <td>
         
        </td> -->

        </td>
        <td valign="top" ROWSPAN="2">
          <div style="border: solid 1px grey;  width: 100%; max-height:250; min-height:250; overflow: auto;">
            <div style="margin:10px">
              <label class="letradisplay"> Stocks </lable>
                <select id="stock" class='letradisplay' onchange="selec_stocks()">
                  <?php
                  $ufuncion = $model->getDato("ufuncional", "sis_maes", "con_estudio =" . $estudio);

                  $stokcs = $model->RSAsociativo("select sis_costo.codigo, sis_costo.nombre from Ufuncionales unf
                      inner join Ufuncionales_Stocks stockuf on stockuf.uf = unf.id
                      inner  join sis_costo on sis_costo.codigo = stockuf.stock
                      where unf.id= '$ufuncion'");

                  foreach ($stokcs as $key => $value) {
                  ?>
                    <option value="<?php echo $value['codigo']; ?>"><?php echo $value['nombre']; ?></option>
                  <?php
                  }
                  ?>
                </select>
            </div>

            <table style="width='100%'">
              <thead>
                <tr style="background-color: #05a1b9;" class="letraCaptionNegrita" width="70" height="19" align="center">
                  <td style="color: white;height: 20px;padding: 4px;">Codigo</td>
                  <td style="color: white;height: 20px;padding: 4px;">Articulo</td>
                  <td style="color: white;height: 20px;padding: 4px;">Cant. Maxima</td>
                  <td style="color: white;height: 20px;padding: 4px;">Cantidad</td>
                  <td style="color: white;height: 20px;padding: 4px;"></td>
                </tr>
              </thead>

              <tbody id="tabla_render">

              </tbody>
            </table>
          </div>

          <div style="border: solid 1px grey;  width: 100%; max-height:210px; overflow: auto;">
            <input style="margin-top: 10px; margin-left: 10px;" name="guardar-articulo-costo"
              onclick="guardar_articulo_costo()" type="button" class="boton sombra" value="Consumir" />
            <table style='width:100%; margin-top: 18px; margin-bottom: 18px;'>
              <thead style="background-color: #05a1b9;">
                <th class="letraDisplay" style="color: white;height: 20px;padding: 4px;">Codigo</th>
                <th class="letraDisplay" style="color: white;height: 20px;padding: 4px;">Nombre</th>
                <th class="letraDisplay" style="color: white;height: 20px;padding: 4px;">Cantidad</th>
                <th class="letraDisplay" style="color: white;height: 20px;padding: 4px;"></th>
              </thead>

              <tbody id="tabla_art">

                <?php
                $rs_nota_historial = $model->RSAsociativo("select prod.codigo, cd.cantidad, prod.descripcion, cd.numeronota from sis_prod prod
                      inner join notasconsumodetallado cd on cd.cod_articulo = prod.codigo where cd.estudio = " . $estudio . " order by (fecharegistro) desc");

                foreach ($rs_nota_historial as $key2 => $medicamwentos_h) {
                  $codigo_h = $medicamwentos_h['codigo'];
                  $descripcion_h = $medicamwentos_h['descripcion'];
                  $cantidad_h = $medicamwentos_h['cantidad'];
                  $numeronota_h = $medicamwentos_h['numeronota'];

                  if ($_REQUEST["numero"] == $numeronota_h) {

                    echo "
                            <tr id=" . $codigo_h . " data-cant=" . $cantidad_h . " data-condicion='no' style='background-color: #D3DFE4'>
                              <td class='letraDisplay'>" . $codigo_h . "</td>
                              <td class='letraDisplay'>" . $descripcion_h . "</td>
                              <td align='center' class='letraDisplay'>" . $cantidad_h . "</td>
                            </tr>";
                  } else {

                    echo "
                            <tr>
                              <td class='letraDisplay'>" . $codigo_h . "</td>
                              <td class='letraDisplay'>" . $descripcion_h . "</td>
                              <td align='center' class='letraDisplay'>" . $cantidad_h . "</td>
                            </tr>";
                  }
                }

                ?>

              </tbody>

            </table>

          </div>

</div>

<!-- </div> -->
</td>
</tr>
<?php
    }
?>

<tr>
  <td align="center" valign="top">
    <table width="100%" cellpadding="3" cellspacing="3">
      <tr>
        <td height="100%" valign="top">
          <table width="100%" cellpadding="0" cellspacing="0" class="LSPRESSE">
            <tr>
              <td height="19" <?php $wmodel->linea(); ?> class="letraCaptionNegrita">
                <?php if (empty($_REQUEST['solovitales'])) { ?>
                  &nbsp;Nota de Enfermera(o)
                <?php } else { ?>
                  Signos Vitales
                <?php } ?>
              </td>
            </tr>
            <tr>
              <td>
                <table width="100%" border="0" cellpadding="2" cellspacing="2">
                  <form method="post" name="f" id="f">
                    <tr>
                      <th width="95" align="left" class="letraDisplay" scope="col">N&uacute;mero </th>
                      <td width="131" align="left" class="letraDisplay" scope="col"><input name="numero" type="text" class="normDisabled" id="numero" title="N&uacute;mero" value="<?php echo $_REQUEST['numero']; ?>" size="20" readonly="true" /></td>
                      <td width="90" align="left" class="letraDisplay" scope="col">Fecha</td>
                      <td class="letraDisplay" width="200" scope="col">
                        <?php
                        $enable = 1;
                        if ($model->getParametroGeneral('fechaNotaEnfermeria', 'HISTORIA CLINICA') == 'N') {
                          $enable = 0;
                        }

                        $editaFechaHora = $model->getDato("CONVERT(CHAR(1), value) as valor", "parametrosGenerales", "parametro = 'EditarFechaNotaEnfEvolucion'");
                        $editEnable = 1;
                        if ($editaFechaHora == "N" && $existeNota == 1) {
                          $editEnable = 0;
                          $enable = 0;
                        }
                        $wmodel->insertarCalendario("fecha", $_REQUEST['fecha'], "Fecha", $enable);
                        ?>
                      </td>
                      <td width="85" align="left" class="letraDisplay" scope="col">Hora</td>
                      <td width="132" align="left" class="letraDisplay" scope="col"><input name="hora" type="text" class="norm" id="hora" title="Hora" onfocus="select()" onkeypress="return validFormat(this,event,'Hour','nextFocus(\'peso\')')" value="<?php echo $_REQUEST['hora']; ?>" size="18" maxlength="5" onblur="return valHour(this)" <?php echo (($editEnable == 0) ? "disabled" : ""); ?> /></td>
                      <td width="70" align="left" class="letraDisplay" scope="col">Peso </td>
                      <td width="152" align="left" class="letraDisplay" scope="col"><input name="peso" type="text" class="norm" id="peso" title="Peso" onfocus="select()" onkeypress="return validFormat(this,event,'Decimal','nextFocus(\'ta\')')" value="<?php echo $_REQUEST['peso']; ?>" size="17" />
                        [kg]</td>
                    </tr>
                    <tr>
                      <?php try {
                        $ta = explode('/', $_REQUEST['ta']);
                      } catch (Exception $e) {
                        $ta = $_REQUEST['ta'];
                      }
                      ?>
                      <td align="left" class="letraDisplay" scope="col">Tensi&oacute;n arterial </td>
                      <td align="left" class="letraDisplay" scope="col"><input name="ta1" onblur="calcularTAM()" type="text" class="norm" id="ta1" title="Tesi&oacute;n arterial" onfocus="select()" onkeypress="return validFormat(this,event,'Int','nextFocus(\'fr\')')" value="<?php echo $ta[0];  ?>" size="3" maxlength="3" />/<input name="ta2" type="text" class="norm" id="ta2" title="Tesi&oacute;n arterial" onfocus="select()" onkeypress="return validFormat(this,event,'Int','nextFocus(\'fr\')')" value="<?php echo $ta[1]  ?>" size="3" maxlength="3" onblur="calcularTAM()" /></td>
                      <td align="left" class="letraDisplay" scope="col">Frecuencia respiratoria </td>
                      <td align="left" class="letraDisplay" scope="col"><input name="fr" type="text" class="norm" id="fr" title="Frecuencia respiratoria" onfocus="select()" onkeypress="return validFormat(this,event,'Int','nextFocus(\'fc\')')" value="<?php echo $_REQUEST['fr']; ?>" size="20" /></td>
                      <td align="left" class="letraDisplay" scope="col">Frecuencia cardiaca </td>
                      <td align="left" class="letraDisplay" scope="col"><input name="fc" type="text" class="norm" id="fc" title="Frecuencia cardiaca " onfocus="select()" onkeypress="return validFormat(this,event,'Int','nextFocus(\'temp\')')" value="<?php echo $_REQUEST['fc']; ?>" size="18" /></td>
                      <td align="left" class="letraDisplay" scope="col">Temperatura</td>
                      <td align="left" class="letraDisplay" scope="col"><input name="temp" type="text" class="norm" id="temp" title="Temperatura" onfocus="select()" onkeypress="return validFormat(this,event,'Decimal','')" value="<?php echo $_REQUEST['temp']; ?>" size="20" /></td>
                    </tr>
                    <tr>
                      <td align="left" class="letraDisplay" scope="col">Saturaci&oacute;n Oxigeno </td>
                      <td align="left" class="letraDisplay" scope="col"><input name="o2" type="text" class="norm" id="o2" title="Saturaci&oacute;n Oxigeno" onfocus="select()" onkeypress="return validFormat(this,event,'Int','')" value="<?php echo $_REQUEST['o2']; ?>" size="20" /></td>

                      <td align="left" class="letraDisplay" scope="col">Glucometr&iacute;a</td>
                      <td align="left" class="letraDisplay" scope="col"><input name="glucometria" type="text" class="norm" id="glucometria" title="Glucometria" onfocus="select()" onkeypress="return validFormat(this,event,'Decimal','nextFocus(\'Medicos\')')" value="<?php echo $_REQUEST['glucometria']; ?>" size="20" /></td>

                    <tr>
                      <th align="left" valign="top" class="letraDisplay" scope="col">Enfermera(o)</th>
                      <td colspan="3" align="left" class="letraDisplay" scope="col">
                        <input name="enfermera" type="text" class="normDisabled" id="enfermera" title="Enfermera(o)" value="<?php echo $_REQUEST["enfermera"]; ?>" size="50" readonly="true" />
                        <a
                          href="javascript
          $(_this):showWindowFind('codigo,nombre','sis_medi','NOT es_medico | .focus(1','Codigo,Nombre','80,250','codigo_enfermera#enfermera','Enfermeras(os)')" );
                          onmouseover="window.status='Buscar';return true"
                          title="Buscar"></a>
                        <input name="codigo_enfermera" type="hidden" id="codigo_enfermera" value="<?php echo $_REQUEST["codigo_enfermera"]; ?>" />
                      </td>
                      <th align="left" valign="top" class="letraDisplay" scope="col">TAM</th>
                      <td><input type="text" id="TAM" name="TAM" value="<?php echo $_REQUEST["tam"]; ?>" class="normDisabled" readonly title="TAM" onclick="calcularTAM()" /></td>
                      <td colspan="3" align="center" scope="col"><a href="javascript:verRecomendaciones()">Ver Recomendacion</a></td>

                    </tr>

                    <tr>
                      <td width="140" class="letraDisplay">Estado de Conciencia</td>
                      <td width="302">
                        <select id="alerta_conciencia" name="alerta_conciencia" class="norm" title="estado_conciencia" value="<?= $_REQUEST['alerta_conciencia'] ?>">
                          <option value="">[SELECCIONE]</option>
                          <!--<#?php $model->elementos('SIGNOS', 'ESTAD_C', 1, $_REQUEST['alerta_conciencia']); ?>-->
                          <?php $estadoConciencia = isset($_REQUEST['alerta_conciencia']) && $_REQUEST['alerta_conciencia'] !== ''
                            ? $_REQUEST['alerta_conciencia']
                              : 1;
                              $model->elementos('SIGNOS', 'ESTAD_C', 1, $estadoConciencia); 
?>
                        </select>
                      </td>
                      <td width="140" colspan="2" class="letraDisplay" scope="col">Medico Especialista (Tratante)</td>
                      <td colspan="5" class="letraDisplay" scope="col">
                        <input name="nom_supervisor" title="Medico Supervisor" type="text" class="normDisabled" id="nom_supervisor" value="<?php echo $_REQUEST["nom_supervisor"]; ?>" size="60" readonly />
                        <input type="hidden" name="cod_supervisor" id="cod_supervisor" value="<?php echo $_REQUEST["cod_supervisor"]; ?>" />
                        &nbsp;&nbsp;
                        <?php
                        $cod_med = $model->getDato("codigo", "sis_medi", "cedula = '{$_SESSION['codigo_user']}'");
                        if ($UsuarioAuditor != 1) {
                          if ($_REQUEST["cod_supervisor"] != $cod_med) {
                        ?> <a href="javascript:buscarMedi();"><img src="../../Imagenes/lupa 01.png" width="14" height="14" border="0" /></a> <?php
                                                                                                                                              }
                                                                                                                                            }
                                                                                                                                                ?>
                      </td>
                    </tr>


                    <input name="estado_his" type="hidden" id="estado_his" value="<?php echo $_REQUEST["estado_his"]; ?>" />
                    <input name="estudio" type="hidden" id="estudio" value="<?php echo $_REQUEST["estudio"]; ?>" />

                    <?php if ($_REQUEST['frame_interno'] != '1' || $_REQUEST["solovitales"] != "1") { ?>
                      <tr>
                        <td colspan="2" align="left" valign="middle" class="letraDisplayNew" scope="col">Nota Diaria del Paciente </td>
                        <th align="left" valign="top" class="letraDisplay" scope="col"><input type="hidden" name="estado" value="<?php echo $_REQUEST["estado"]; ?>" />
                        </th>
                        <td align="left" class="letraDisplay" scope="col">&nbsp;</td>
                        <td align="left" class="letraDisplay" scope="col">&nbsp;</td>
                        <td align="left" class="letraDisplay" scope="col">&nbsp;</td>
                        <td align="left" class="letraDisplay" scope="col">&nbsp;</td>
                        <td align="left" class="letraDisplay" scope="col">&nbsp;</td>
                      </tr>
                      <tr>
                        <td colspan="8" align="left" valign="top" class="LSPRESSE" scope="col"><textarea name="datosnota" cols="160" rows="10" class="letraDisplay" id="evolucion" title="Nota Diaria del Paciente " onkeypress="return validFormat(this,event,'Mayus','')"><?php echo (strtoupper($_SESSION['datosnota' . $_REQUEST["estudio"]])); ?></textarea></td>
                      </tr>
                      <tr>
                        <td colspan="8" align="left">
                          <table>
                            <tr>
                              <td>
                                <input type="button" name="btnDatosDescripcionQx" id="btnDatosDescripcionQx" value="Registrar datos descripcion Qx" onclick="fnDatosDescripcionQx()" class="norm" />
                                <?php if ($ExisteDatosQx == 1): ?>
                                  <img src="../../Imagenes/Ok1.png" width="20" height="20" style="position:relative;vertical-align:bottom" title="Datos de la descripcion Qx registrados" />
                                <?php endif; ?>
                              </td>
                              <td>
                                <input type="checkbox" name="notaCerrada" id="notaCerrada" <?php echo (($_REQUEST["isNotaCerrada"] == 1) ? ' checked="checked" ' : ''); ?> />
                              </td>
                              <td class="letraDisplay">Cerrar Nota</td>
                            </tr>
                          </table>
                        </td>
                      </tr>
                      <tr>
                        <td colspan="8" align="center" class="letraDisplay" scope="col">&nbsp;</td>
                      </tr>
                    <?php } ?>


                    <?php
                      $notasHcCerrada = $model->getDato('value', 'parametrosGenerales', "parametro='notasHCCerrada'");
                      $editarNotas = $model->getParametroGeneral("editar_notasEnfermeria", "ENFERMERIA");
                    ?>

                    <tr>
                      <td colspan="8" align="center" class="letraDisplay" scope="col">

                        <?php if ($_REQUEST['general_paciente'] != "si") { ?><input name="button2" type="button" class="boton sombra" value="Nueva" onclick="<?php if ($_REQUEST["estado_his"] == "A" || ($_REQUEST["estado_his"] != "A" && $notasHcCerrada == 'S') || ($frame_interno)) { ?>reDirect('CtrlDatosNota.php?operacion=nueva_not&n=<?php echo $i; ?>&estado=<?php echo $_REQUEST['estado']; ?>&estudio=<?php echo $_REQUEST['estudio']; ?>&estado_his=<?php echo $_REQUEST['estado_his']; ?>&frame_interno=<?php echo $frame_interno_req; ?>&solovitales=<?php echo $_REQUEST["solovitales"]; ?>&op=<?php echo $_REQUEST["op"] . $urlParams; ?>')<?php } else { ?>swal('No se puede realizar esta operacion.\nLa historia ya est&aacute; cerrada','','error')<?php } ?>" />
                        <?php } ?>
                        &nbsp;&nbsp;

                        <?php
                        if ($editarNotas != 'N') {
                          $cod_med = $model->getDato("codigo", "sis_medi", "cedula = '{$_SESSION['codigo_user']}'");
                          if ($_REQUEST['general_paciente'] != "si") {

                            if ($cod_med == $codigo_enfermera) {

                              // $existeNota=$model->getDato('1 existe','notas_enfermeria','numero='.$_REQUEST['numero']);
                              $editar = true;
                              if (trim($existeNota) != '' && $permitirEditarEliminarNotasEnfermeria == 'N') {
                                $editar = false;
                              }

                              if ($editar) {
                        ?>

                                <input name="button" type="button" class="boton sombra" value="Guardar" onclick="<?php if ($_REQUEST["estado_his"] == "A" || ($_REQUEST["estado_his"] != "A" && $notasHcCerrada == 'S') || ($frame_interno)) {
                                                                                                                    if (trim($existeNota) != '' && ($conf_nota == 'NE' || ($conf_nota == 'IC' && $_REQUEST["estado_his"] == 'C'))) { ?> swal('No se permite la edicion de la Nota de enfermeria. Si necesita realizar alguna correccion use las notas aclaratorias.','','error'); <?php } else { ?>guardar('<?php echo $frame_interno_req; ?>','<?php echo $posicion_nota; ?>','<?php echo $_REQUEST["solovitales"]; ?>','<?php echo $_REQUEST["op"]; ?>')<?php }
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    } else { ?>swal('No se puede realizar esta operacion.\nLa historia ya est&aacute; cerrada','','error')<?php } ?>" />

                          <?php
                              }

                              /** Fin de validacion del medico que creo la evolucion*/
                            }
                          }
                        }
                        if (trim($existeNota) != '' && ($conf_nota == 'NE' || ($conf_nota == 'IC' && $_REQUEST["estado_his"] == 'C'))) {
                          ?>
                          &nbsp;&nbsp;
                          <input name="button" id="botonNotaAclara" type="button" class="boton sombra" value="Notas Aclaratorias" onclick="NotaAclaratoria();" />
                        <?php } ?>

                        &nbsp;&nbsp;
                        <?php
                        if ($imprime_hc != '0') { ?>
                          <input name="button" type="button" class="boton sombra" value="Imprimir" onclick="invocarReporte('reporte_datos_nota.php?estudio=<?php echo $_REQUEST["estudio"]; ?>&numero=<?php echo $_REQUEST["numero"]; ?>&frame_interno=<?php echo $frame_interno_req . $urlParams; ?>')" />
                          &nbsp;&nbsp;

                          <input name="button" type="button" class="boton sombra" value="Ver evoluciones" onclick="invocarReporte('reporte_evoluciones.php?estudio=<?php echo $_REQUEST["estudio"] . $urlParams; ?>')" />
                          &nbsp;&nbsp;
                        <?php } ?>
                        <?php if (empty($_REQUEST['solovitales'])) { ?>
                          <input name="button" type="button" class="boton sombra" value="&nbsp;&nbsp;Salir&nbsp;&nbsp;" onclick="<?php if ($_REQUEST['general_paciente'] != "si") { ?>reDirect('<?php if (!$frame_interno) { ?>Notas.php?operacion=mos_detalle&estado=<?php echo $_REQUEST["estado"]; ?>&estudio=<?php echo $_REQUEST["estudio"]; ?>&solovitales=<?php echo $_REQUEST["solovitales"]; ?>&estado_his=<?php echo $_REQUEST["estado_his"] . $urlParams . '&' . $metodo_salir;
                                                                                                                                                                                                                                                                                                                                                                                                              } else { ?>CtrlDatosNota.php?operacion=abrir_datos&solovitales=<?php echo $_REQUEST["solovitales"]; ?>&estudio=<?php echo $_REQUEST['estudio'] . $urlParams . '&' . $metodo_salir; ?><?php } ?>') <?php } else { ?> ajax('../Hc/CtrlNotas.php?operacion=abrir_not&solovitales=<?php echo $_REQUEST["solovitales"]; ?>&general_paciente=si&estudio=<?php echo $_REQUEST["estudio"]; ?>&div_contenedor=<?php echo $_REQUEST["div_contenedor"]; ?>', '<?php echo $_REQUEST["div_contenedor"]; ?>', ''); <?php } ?>" />
                      </td>
                    <?php } else { ?>
                      <input name="button" type="button" class="boton sombra" value="&nbsp;&nbsp;Salir&nbsp;&nbsp;" onclick="reDirect('controlsignosvitales.php?operacion=mos_detalle&estado=<?php echo $_REQUEST["estado"]; ?>&estudio=<?php echo $_REQUEST["estudio"]; ?>&solovitales=<?php echo $_REQUEST["solovitales"] . $urlParams; ?>')" />
              </td>
            <?php } ?>

            </tr>
            </form>
          </table>
        </td>
      </tr>
    </table>
  </td>
</tr>
</table>
</td>

</tr>

</table>
</tr>
<tr>
  <td valign="top" class="capa">
</tr>
</table>
<?php
if ($_REQUEST['general_paciente'] != "si") {
  if (!$frame_interno) { ?>
    <script language="JavaScript1.2">
      mmLoadMenus();
    </script>
<?php
    $wmodel->Pie('#5F87B6', 'smallwhite');
  }
  if ($_REQUEST['msg'] == "ok") {
    echo "<script>swal('Nota Almacena / Actualizada con Exito','','success')</script>";
  } else if (trim($_REQUEST['msg']) != "") {
    echo "<script>swal('" . $_REQUEST['msg'] . "','','info')</script>";
  }
}
?>
</div>
</body>

</html>
