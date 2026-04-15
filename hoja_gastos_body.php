<?php
	// require_once("../template_2.php");
	 $registro = $_REQUEST['registro'];
	 //encabezado(is_null($registro) || $registro==2 ? "Hoja de Gastos" : "Registro de Medicamentos", "", false);
	$interno = $_REQUEST["interno"];
	$interno = is_null($interno)? 0 : 1;
	$rs = $model->select("e.codigo, e.nombre, e.nit, c.nombre, e.direccion, e.telefono, m.nro_factura, 
		m.fecha_egr, m.fecha_egreso, p.tipo_id, p.num_id, p.primer_nom, p.segundo_nom, p.primer_ape, p.segundo_ape,
		p.direccion, p.telefono, m.tipo_estudio, m.cod_clasi, es.nombre, p.tipo_usuario, p.nro_historia, p.fecha_naci, 
		m.nro_autoriza, m.fecha_ing, m.fecha_egr, m.valor_letra, m.vlr_factura, m.vlr_coopago, m.vlr_descto,
		m.emp_asume_desc", 
		"sis_maes AS m, sis_empre AS e, sis_paci AS p, sis_estrato AS es, contratos AS c", 
		"m.con_estudio = '{$_REQUEST['estudio']}' AND c.empresa = e.codigo 
		AND m.contrato = c.codigo AND p.autoid = m.autoid AND es.codigo = m.cod_clasi");
	$datos = $model->nextRow($rs);
	$model->freeResult($rs);
?>

<style>
#customers {
  font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

#customers td, #customers th {
  border: 1px solid #ddd;
  padding: 5px;
}

#customers tr:nth-child(even){background-color: #f2f2f2;}

#customers tr:hover {background-color: #ddd;}

#customers th {
  padding-top: 3px;
  padding-bottom: 3px;
  text-align: left;
  background-color: #EFF2FB;
  color: black;
}
</style>



<table style="width:100%;" class="border" border="0" cellpadding="0" cellspacing="0">

<tr>
	<td style="font-size:11px;"><strong>DATOS DE LA EMPRESA</strong></td>
	<td width="200" style="font-size:11px;text-align:right"><strong>Fecha impresion:</strong> <?php echo date('Y/m/d'); ?></td>

</tr>

</table>

<table style="width:100%;" class="border" border="0" cellpadding="0" cellspacing="0">


<tr>
	<td style="font-size:11px;"><strong>Prestado a:</strong> <?php echo "{$datos[0]} - {$datos[1]}"; ?></td>
	<td style="font-size:11px; text-align:right"><strong>Contrato:</strong> <?php echo $datos[3]; ?></td>

</tr>

<tr>
	<td colspan="2" style="font-size:11px;">
		 <strong>Tel:</strong> <?php echo $datos[2]; ?> &nbsp;&nbsp;
		 <strong>Nit:</strong> <?php echo $datos[2]; ?> &nbsp;&nbsp;
		 <strong>Dir:</strong> <?php echo $datos[4]; ?>
	</td>

</tr>


</table>

<div class="block">
  
  <!-- //                              -->
  <tr>
	<td> <hr color="green" size=1 width="150"></td>
		</tr>
</div>
<?php
  bloque("complemento/datos_paciente.php");
	$manual = $model->codigoManual($estudio);
	
	if($registro == 1){
		$SQL = "SELECT p.codigo, p.descripcion nombre, CONVERT(FLOAT, s.cantidad) cantidad,
		  s.usuario enfermera, s.fecha fecha, s.h_suministro hs
			FROM sis_prod AS p, sis_deta AS d, movStock AS s,sis_maes sm, contratos con
			WHERE sm.con_estudio = d.estudio and sm.contrato = con.codigo and  d.cod_servicio = p.codigo 
      AND d.estudio = {$_REQUEST['estudio']} AND d.id = s.servicio AND s.revertido = 0 AND s.nro_orden IS NULL 
      AND s.nro_orden_med IS NULL AND administracionGeneral = 0 And Isnull(s.IdInsulina, 0) = 0" 
			.(($registro == 1) ? " and p.tipo = 1 " : ($registro==2 ? " and p.tipo = 2 " : ""));
	}elseif($registro == 2){
		$SQL = "SELECT p.codigo, p.descripcion nombre, CONVERT(FLOAT, SUM(s.cantidad)) cantidad, uf.descripcion as ufuncional,
      s.usuario enfermera, s.fecha fecha, s.h_suministro hs
      FROM sis_prod AS p
      INNER JOIN movStock as s ON s.articulo = p.codigo
      INNER JOIN sis_maes as sm ON sm.con_estudio = s.estudio
      INNER JOIN contratos as con ON sm.contrato = con.codigo
      INNER JOIN Ufuncionales uf on uf.id = sm.ufuncional
      WHERE s.estudio = {$_REQUEST['estudio']} AND s.revertido = 0 
      AND s.nro_orden IS NULL AND s.nro_orden_med IS NULL  And Isnull(s.IdInsulina, 0) = 0" 
      .(($registro == 1) ? " and p.tipo = 1 " : ($registro==2 ? " and p.tipo = 2 " : ""))." 
      AND (uf.id = '{$_REQUEST['ufuncional']}' OR '{$_REQUEST['ufuncional']}' in ('0', '')) 
      and (convert(date, s.fecha) between '".$_REQUEST["fecha_inicio"]."' And '".$_REQUEST["fecha_fin"]."' or '".$_REQUEST["fecha_inicio"]."' = '' or '".$_REQUEST["fecha_fin"]."' = '')"
      ."GROUP BY uf.descripcion,p.codigo, p.descripcion, s.usuario,s.fecha, s.h_suministro, s.consecutivoAdmonCirugia "
      ."UNION ALL
      SELECT p.codigo, p.descripcion nombre, CONVERT(FLOAT, s.cantidad) cantidad,uf.descripcion as ufuncional,
      s.usuario enfermera, s.fecha fecha, s.h_suministro hs
      FROM sis_prod AS p
      INNER JOIN movStock as s ON s.articulo = p.codigo
      INNER JOIN sis_maes as sm ON sm.con_estudio = s.estudio
      INNER JOIN contratos as con ON sm.contrato = con.codigo
      INNER JOIN orden_enfer as ord ON ord.numero = s.nro_orden and ord.medicamento = s.articulo
        INNER JOIN Ufuncionales uf on uf.id = ord.ufuncional
      WHERE s.estudio = {$_REQUEST['estudio']} AND s.revertido = 0 AND s.nro_orden IS NOT NULL" 
      .(($registro == 1) ? " and p.tipo = 1 " : ($registro==2 ? " and p.tipo = 2 " : ""))
      ."  AND (uf.id = '{$_REQUEST['ufuncional']}' OR '{$_REQUEST['ufuncional']}'in ('0', '')) 
      and (convert(date, s.fecha) between '".$_REQUEST["fecha_inicio"]."' And '".$_REQUEST["fecha_fin"]."' or '".$_REQUEST["fecha_inicio"]."' = '' or '".$_REQUEST["fecha_fin"]."' = '')
      ORDER BY fecha, hs, nombre";
	}
	$medicamentos = $model->Execute($SQL);
?>
<tr>
	<td> <hr color="green" size=1 width="150"></td>
</tr>

<h2 style="text-transform:uppercase; margin-top:4px; margin-bottom:0px; font-size:12pt; text-align:center"><?php echo is_null($registro) || $registro==2 ? "" : ""; ?></h2>

<div class="block" style="text-align:center">
	<span><strong><?php echo is_null($registro) ? "Medicamentos e Insumos" : ($registro==1 ? "Medicamentos" : "Insumos"); ?></strong></span>

	<table id="customers">
		<tr>
		  <th width="50" class="head"><strong>C&Oacute;DIGO</strong></th>
		  <th class="head"><strong>DESCRIPCI&Oacute;N</strong></th>
		  <th class="head"><strong>FECHA</strong></th>
		  <th width="50" class="head"><strong>HORA ADMI.</strong></th>
		  <th width="70" class="head"><strong>CANTIDAD</strong></th>
		  <th align="center" class="head"><strong>ENFERMERO(A)</strong></th>
		  <th align="center" class="head"><strong>FIRMA</strong></th>
		  <th width="70" class="head"><strong>UFUNCIONAL</strong></th>
		</tr>
<?php
	$total = 0;
	$size = sizeof($medicamentos);
	$wmodel = new WebModel();

	for ($i = 0; $i < $size; $i++) {
		$total += $medicamentos[$i]["cantidad"];
		$color = $i % 2 == 0 ? '#F5F5F5' : '#FFFFFF';

		$cedulaEnfermera = trim($medicamentos[$i]["enfermera"]);
		$nombreEnfermera = trim($model->getDato('nombre','usuario',"cedula = '".$cedulaEnfermera."'"));
		$textoEnfermera = "CC - ".$cedulaEnfermera."<br>- ".$nombreEnfermera;
		$firmaEnfermera = $wmodel->getFirmaMedicoV2($cedulaEnfermera, 180, 90, "margin-top:10px");
?>
	<tr bgcolor="<?php echo $color; ?>" style="cursor:hand" onmouseover="bgColor='#f4f4f4'" onmouseout="bgColor='<?php echo $color; ?>'">
	  <td class="row"><?php echo $medicamentos[$i]["codigo"]; ?></td>
	  <td class="row2"><?php echo $medicamentos[$i]["nombre"]; ?></td>
	  <td class="row2"><?php echo convertirFecha($medicamentos[$i]["fecha"]); ?></td>
	  <td class="row"><?php echo $medicamentos[$i]["hs"]; ?></td>
	  <td class="row"><?php echo $medicamentos[$i]["cantidad"]; ?></td>
	  <td class="row2"><?php echo $textoEnfermera; ?></td>
	  <td class="row2"><?php echo $firmaEnfermera; ?></td>
	  <td class="row"><?php echo $medicamentos[$i]["ufuncional"]; ?></td>
	</tr>
<?php
	}
?>
		<tr>
			<td colspan="7" class="row2" align="right"><strong>Total:</strong></td>
			<td class="row"><strong><?php echo $total; ?></strong></td>
		</tr>
	</table>
</div>

<?php
	if($registro != 2){
		
$medicamentos = $model->RSAsociativo("SELECT d.id, p.codigo, p.descripcion AS medicamento, p.concentracion, ISNULL(CONVERT(VARCHAR(20), o.dosis_mto_und), CONVERT(VARCHAR(20), o.dosis)) + ' ' + ISNULL(m_eq.codigo, m.codigo) AS dosis, d.orden_med AS orden, d.fecha, d.fecha_fac, d.hora, o.fecha AS fecha_orden,
(SELECT u.nombre FROM usuario AS u WHERE u.cedula=d.usuario) AS enfermera, d.obs, d.fecha_obs,uf.descripcion as descripcion
FROM sis_prod AS p 
	INNER JOIN dosis_orden_med AS d ON d.item = p.codigo 
	LEFT JOIN orden_med AS o ON o.numero = d.orden_med 
	LEFT JOIN medidas AS m ON m.id = p.unimed 
	LEFT JOIN medidas AS m_eq ON m_eq.id = o.und_equivalencia
  left join Ufuncionales as uf on uf.id = o.ufuncional
WHERE d.item = o.medicamento AND o.ingreso = $estudio 
and (convert(date, d.fecha_obs) between '".$_REQUEST["fecha_inicio"]."' And '".$_REQUEST["fecha_fin"]."' or '".$_REQUEST["fecha_inicio"]."' = '' or '".$_REQUEST["fecha_fin"]."' = '')
and (uf.id = '{$_REQUEST['ufuncional']}' OR '{$_REQUEST['ufuncional']}' in ('0', ''))
AND (d.aplicado = 1 ".($interno? "OR LTRIM(RTRIM(d.obs)) <> ''" : "").")
UNION ALL
SELECT NULL AS id, p.codigo, p.descripcion AS medicamento, NULL AS concentracion, CONVERT(VARCHAR(20), CONVERT(FLOAT, ms.dosis_unidad)) + ' ' + ms.unimed_dosis AS dosis,
	NULL AS orden, ms.fecha, d.fecha_servicio AS fecha_fac, ms.h_suministro AS hora, NULL AS fecha_orden, u.nombre, NULL AS obs, NULL AS fecha_obs,uf.descripcion as descripcion
	FROM dbo.movStock AS ms	
	INNER JOIN sis_prod AS p ON ms.articulo = p.codigo
	INNER JOIN dbo.usuario AS u ON ms.usuario = u.cedula
  LEFT JOIN dbo.sis_deta AS d ON ms.servicio = d.id
  left join Ufuncionales as uf on uf.id = d.ufuncional
WHERE ms.estudio = $estudio AND administracionGeneral = 1 AND cons_zeus_inven IS NOT NULL AND p.tipo = 1
and (convert(date, ms.fecha) between '".$_REQUEST["fecha_inicio"]."' And '".$_REQUEST["fecha_fin"]."' or '".$_REQUEST["fecha_inicio"]."' = '' or '".$_REQUEST["fecha_fin"]."' = '')
and (uf.id = '{$_REQUEST['ufuncional']}' OR '{$_REQUEST['ufuncional']}' in ('0', ''))
UNION ALL
SELECT Orden.id AS id, p.codigo, p.descripcion AS medicamento, p.concentracion, CONVERT(VARCHAR(20), CONVERT(FLOAT, ms.dosis_unidad)) + ' ' + ms.unimed_dosis AS dosis,
	Orden.numero AS orden, ms.fecha, d.fecha_servicio AS fecha_fac, ms.h_suministro AS hora, Orden.fecha AS fecha_orden, u.nombre, Orden.ObsInsulinaTramitada AS obs, Orden.FechaInsulinaTramitada AS fecha_obs,uf.descripcion as descripcion
	FROM dbo.movStock AS ms	
	INNER JOIN orden_med AS Orden ON ms.IdInsulina = Orden.id
	INNER JOIN sis_prod AS p ON ms.articulo = p.codigo
	INNER JOIN dbo.usuario AS u ON ms.usuario = u.cedula
  LEFT JOIN dbo.sis_deta AS d ON ms.servicio = d.id
  left join Ufuncionales as uf on uf.id = d.ufuncional
WHERE ms.estudio = $estudio And ms.revertido = 0
and (convert(date,  Orden.FechaInsulinaTramitada) between '".$_REQUEST["fecha_inicio"]."' And '".$_REQUEST["fecha_fin"]."' or '".$_REQUEST["fecha_inicio"]."' = '' or '".$_REQUEST["fecha_fin"]."' = '')
and (uf.id = '{$_REQUEST['ufuncional']}' OR '{$_REQUEST['ufuncional']}' in ('0', ''))
ORDER BY 7 DESC,9 DESC
");

	}else{
		$medicamentos = array();
	}

	if($registro == 1){
?>
<tr>
	<td> <hr color="green" size=1 width="150"></td>
</tr>
<br><br>

<div class="block" style="text-align:center"><span><strong>Dosis</strong></span>
  <table id="customers">
    <tr>
      <th width="50" class="head"><strong>ORDEN</strong></th>
      <th width="50" class="head"><strong>C&Oacute;DIGO</strong></th>
      <th class="head"><strong>DESCRIPCI&Oacute;N</strong></th>
      <th align="center" class="head"><strong>ENFERMERA</strong></th>
      <th class="head"><strong>FECHA ADM</strong></th>
      <?php if($interno == 1): ?> 
      <th class="head"><strong>FECHA FAC</strong></th> 
      <?php endif; ?>
      <th width="50" class="head"><strong>HORA ADMI.</strong></th>
      <th width="70" class="head"><strong>DOSIS</strong></th>
      <?php if($interno):?>
      <th width="100" class="head"><strong>OBS</strong></th>
      <?php endif; ?>
      <th width="100" class="head"><strong>UF</strong></th>
    </tr>
<?php
	$total = 0;
	$size = sizeof($medicamentos);
	for ($i = 0; $i < $size; $i++) {
		if (($i + 1) == $size) $total = ($i + 1);
		$color = $i % 2 == 0 ? '#F5F5F5' : '#FFFFFF';
		$totalDosis += $medicamentos[$i]["dosis_aux"];
		$obs = trim($medicamentos[$i]["obs"]);
?>
    <tr bgcolor="<?php echo $color; ?>" style="cursor:hand" onmouseover="bgColor='#f4f4f4'" onmouseout="bgColor='<?php echo $color; ?>'">
      <td class="row"><?php echo $medicamentos[$i]["orden"]; ?></td>
      <td class="row"><?php echo $medicamentos[$i]["codigo"]; ?></td>
      <td class="row2"><?php echo $medicamentos[$i]["medicamento"]; ?></td>
      <td class="row2"><?php echo $medicamentos[$i]["enfermera"]; ?></td>
      <td class="row"><?php echo empty($obs)? convertirFecha($medicamentos[$i]["fecha"]) : convertirFecha($medicamentos[$i]["fecha_obs"]); ?></td>
      <?php if($interno == 1): ?> 
      <td class="row2"><?php echo empty($obs)? 
	  	(empty($medicamentos[$i]["fecha_fac"])? "" : convertirFecha($medicamentos[$i]["fecha_fac"])) : ""; ?></td>
      <?php endif; ?>
      <td class="row"><?php echo empty($obs)? (strpos($medicamentos[$i]["hora"],":")? $medicamentos[$i]["hora"] 
	  	: rellenar($medicamentos[$i]["hora"],2,'0') .':00') : ""; ?></td>
      <td class="row"><?php echo $medicamentos[$i]["dosis"]; ?></td>
      <?php if($interno):?>
      <td class="row2"><?php echo $medicamentos[$i]['obs']; ?></td>
      <?php endif; ?>
      <td class="row2"><?php echo $medicamentos[$i]['descripcion']; ?></td>
    </tr>
<?php
	}
?>
	<tr><td colspan="<?php echo $interno? 10 : 8; ?>" class="row2"><strong>Total Registro: <?php echo $total; ?></strong></td></tr>
  </table>
</div>

<tr>
	<td> <hr color="green" size=1 width="150"></td>
</tr>
<br><br>

<!-- INFUSIONES -->
<div class="block" style="text-align:center"><span><strong>Infusiones</strong></span>
  <table id="customers">
    <tr>
      <th width="50" class="head"><strong>ORDEN</strong></th>
      <th width="50" class="head"><strong>C&Oacute;DIGO</strong></th>
      <th class="head"><strong>DESCRIPCI&Oacute;N</strong></th>
      <th align="center" class="head"><strong>ENFERMERA</strong></th>
      <th class="head"><strong>FECHA ADM</strong></th>
      <?php if($interno == 1): ?> <th class="head"><strong>FECHA FAC</strong></th> <?php endif; ?>
      <th width="50" class="head"><strong>HORA ADMI.</strong></th>
      <th width="70" class="head"><strong>DOSIS</strong></th>
      <?php if($interno):?>
      <th width="100" class="head"><strong>OBS</strong></th>
      <?php endif; ?>
      <th width="100" class="head"><strong>UF</strong></th>
    </tr>
<?php
	$total = 0;
	$medicamentos = $model->RSAsociativo("Exec dbo.spReporteAdmonInfusiones @ingreso = " .$model->getIngreso($estudio) 
		.", @registroInterno = 'S'");
	$size = sizeof($medicamentos);
	for ($i = 0; $i < $size; $i++) {
?>
    <tr style="cursor:hand" onmouseover="bgColor='#f4f4f4'" onmouseout="bgColor='<?php echo $color; ?>'">
      <td class="row"><?php echo $medicamentos[$i]["orden_med"]; ?></td>
      <td class="row"><?php echo $medicamentos[$i]["item"]; ?></td>
      <td class="row2"><?php echo $medicamentos[$i]["medicamento"]; ?></td>
      <td class="row2"><?php echo $medicamentos[$i]["usuario"]; ?></td>
      <td class="row"><?php echo trim($medicamentos[$i]['obs']) == ""? convertirFecha($medicamentos[$i]["fechaAplicacion"]) : convertirFecha($medicamentos[$i]["fechaObs"]); ?></td>
      <?php if($interno == 1): ?> 
      <td class="row2"><?php echo trim($medicamentos[$i]['obs']) == ""? convertirFecha($medicamentos[$i]["fecha_fac"]) : ""; ?></td>
      <?php endif; ?>
      <td class="row"><?php echo  trim($medicamentos[$i]['obs']) == ""? $medicamentos[$i]["hora"] : ""; ?></td>
      <td class="row"><?php echo $medicamentos[$i]["dosis"] ." " .$medicamentos[$i]["unimed"]; ?></td>
      <?php if($interno):?>
      <td class="row2"><?php echo $medicamentos[$i]['obs']; ?></td>
      <?php endif; ?>
      <td class="row2"><?php echo $medicamentos[$i]['descripcion']; ?></td>
    </tr>
<?php
	}
?>
  </table>
</div>
<?php } ?>

<?php
	if ($model->existeElem($medicamentos, "FC", "facturar") != -1) {
?>
<?php
	}
	pie();
?>
