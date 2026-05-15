<?php
@session_start();
require_once("template.php");
encabezado("Certificado de Estancia      Hospitalaria", "Certificado  de Estancia   Hospitalaria");
require_once("{$_SESSION['root']}/Model/Model.php");
$model = new Model();
$horacita =	$model->busCita($estudio);
?>

<?php 

$hoy = getdate();
$serial = $model->select("*", "seriales", "id in (select top(1) id_sede from sis_maes where con_estudio = '$estudio') ", NULL, 1);

	$r = $model->select("p.num_id,p.primer_nom + ' ' + p.segundo_nom  + ' ' +  p.primer_ape + ' ' + p.segundo_ape,
		p.fecha_naci, p.carnet, p.sexo, p.tipo_id, p.tipo_sangre, p.tipo_usuario, p.tipo_afilia, d.nombre, mu.nombre,
		b.nombres, p.telefono, p.direccion, p.zona, e.nombre, c.nombre, es.nombre, m.nom_usuario, me.nombre,
		me.registro, m.causa_ext, m.obs, m.nro_autoriza,fecha_ing,
		(SELECT TOP 1 ii.id_ingreso FROM ingresos_deta AS ii WHERE ii.estudio=m.con_estudio),
		convert(date,m.fecha_ing) as FechaIngreso,convert(date,m.fecha_egr) as fechaEgreso,m.hora_ing as HoraIngreso,m.con_estudio,sg.codigo,
		sg.descripcion, isnull(sc.estudio_paciente,'') as estudio_paciente",
		"sis_maes AS m left join sis_cama sc on sc.estudio_paciente=m.con_Estudio, sis_paci AS p LEFT JOIN sis_barrios AS b ON p.barrio = b.codigo, departamentos AS d, sis_muni AS mu, sis_empre AS e,
		contratos AS c, sis_estrato AS es, sis_medi AS me,sis_diags sg",
		"m.con_estudio = $estudio AND m.autoid = p.autoid AND p.cod_dep = d.codigo
		AND sg.codigo=m.diagno_ing and d.codigo = mu.id_dep AND p.cod_muni = mu.codigo 
		AND m.contrato = c.codigo AND c.empresa = e.codigo AND m.cod_clasi = es.codigo
		AND m.cod_medico = me.codigo",null,1,1);
		

	 $r_social = $model->RSAsociativo("select r_social from seriales ");
	 $r_social = $r_social[0]['r_social'];
	 
	 $fechas = $model->RSAsociativo("select convert(date,fecha_ing) as fecha_ing,convert(date,fecha_egr) as  fecha_egr from sis_maes where con_estudio = '".$estudio."' ");
	 $fecha_ing = $fechas[0]['fecha_ing'];
	 $fecha_egr = $fechas[0]['fecha_egr'];

	 
	
?>
<style>
*{
	font-size:13px !important	
	
}
</style>
   <table align="center"  >
	<tr>
		<td colspan="4" style="text-align:center">
		<h2>FUNDACION CLINICA DEL RIO
		<br>NIT: 900540156</h2>
		<br>
		<BR>
		<BR>
		<BR>
		<br>
			<h2>CERTIFICA</h2>
			<BR>
			<BR>
			<BR>
			<BR>
			<BR>
			<BR>
			<BR>
		</td>
	</tr>
	<?php if(empty($r["estudio_paciente"])){ ?>

	<tr>
		<td colspan="4"  >
			 <p>
				Que el(la) se&ntilde;or(a) <b><?php echo $r[1]; ?></b> con identificaci&oacute;n <b><?PHP echo $r[5].'-'.$r[0]; ?></b>
				estuvo hospitalizado(a) desde el d&iacute;a <b><?php echo $fecha_ing; ?></b>  hasta el d&iacute;a <b><?php echo $fecha_egr; ?></b>
				en nuestra instituci&oacute;n.
				<BR>
			<BR>
			<BR>



			<BR>
			<BR>
			<BR>
			<p>Se expide la siguiente certificaci&oacute;n a los <?php echo$hoy[mday]; ?> d&iacute;as del mes <?php echo $hoy[mon]; ?> del a&ntilde;o  <?php echo$hoy[year]; ?>. </p>



			</p>
			
			 
		</td>
	</tr>
<?php } else { ?>

	<tr>
		
		<td colspan="4"  >
			 <p>
				Que el(la) se&ntilde;or(a) <b><?php echo $r[1]; ?></b> con identificaci&oacute;n <b><?PHP echo $r[5].'-'.$r[0]; ?></b>
				se encuentra hospitalizado(a) desde el d&iacute;a <b><?php echo $fecha_ing; ?></b> 
				en nuestra instituci&oacute;n.
				<BR>
			<BR>
			<BR>

			

			<BR>
			<BR>
			<BR>
			<p>Se expide la siguiente certificaci&oacute;n a los <?php echo$hoy[mday]; ?> d&iacute;as del mes <?php echo$hoy[mon]; ?> del a&ntilde;o  <?php echo$hoy[year]; ?>. </p>



			</p>
			
			 
		</td>
	</tr>
	<?php }  ?>
	<TR>
		
	</TR>
	
	<tr>
	<BR>
			<BR>
			<BR>
		<td style="text-align: center " colspan="2"><br><br><BR>
			<BR>
			<BR>
			<BR>
			<?php
			/*$image_path = 'http://186.190.254.230:1800/SismaSalud/Archivos/Cliente//Firmas/Usuarios/1102807955.jpg';
			$image_data = file_get_contents($image_path);
			$image_base64 = base64_encode($image_data);
			echo '<img src="data:image/jpeg;base64,' . $image_base64 . '" alt="Image">';*/
						
			
			
			/*<b><?php echo $r[30]; ?></b> - <b><?php echo $r[31]; ?></b>  -- SECCION ELIMINADA QUE TRAE EL DIAGNOSTICO SE ENCONTRABA EN LA LINEA 75 -- */
			
			
			
			?>
			<img src="http://186.190.254.230:1800/SismaSalud/Archivos/Cliente//Firmas/Usuarios/98630620.png" . alt width = "200" height = "100">'
			<BR>_________________________________________</td> 
	</tr>
	<TR>
	<TD style="text-align: center " colspan="2">
	
	

			<h2>GUSTAVO ALEXANDER GONZALEZ MU&Ntilde;OZ<br>
			DIRECTOR MEDICO <br>
			FUNDACION CLINICA DEL RIO</h2>
		</TD>
	</TR>

   </table>

<?php
pie();
?>
