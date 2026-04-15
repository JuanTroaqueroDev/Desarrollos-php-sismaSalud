<div class="block">

<style>
#customers {
  font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

#customers td {
  border: 1px solid #ddd;
  padding: 5px;
  font-size:0.9em;
  
}
#customers th {
  border: 1px solid #ddd;
  padding: 2px;
  
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
</div>
<?php
  bloque("complemento/datos_paciente_ingreso.php");		
  
?>

	<tr>
	<td> <hr color="green" size=1 width="150"></td>
		</tr>
<!-- <h2 style="text-transform:uppercase; margin-top:4px; margin-bottom:0px; font-size:12pt; text-align:center">Registro de Medicamentos</h2> -->
<div class="block" style="text-align:center"><span><strong>Dosis</span>
  <table id = "customers">
    <tr>
      <th width="50" class="head" ><strong>ORDEN</th>
      <th width="50" class="head"><strong>C&Oacute;DIGO</th>
	   <th width="70" class="head"><strong>CUM</th>
	    <th width="70" class="head"><strong>CANTIDAD</th>
      <th class="head"><strong>DESCRIPCI&Oacute;N</th>     
      <th width="70" class="head"><strong>DOSIS</th>
      <th class="head"><strong>FECHA</th>
      <th width="50" class="head"><strong>HORA ADMON</th>
      <th class="head"><strong>V.A.</th>
      <th align="center" class="head"><strong>ENFERMERO(A)</th>
      <th align="center" class="head"><strong>FIRMA</th>
	    <th align="center" class="head"><strong>UFUNCIONAL</th>
    </tr>
    
<?php
	$empresaAnt = "";
	foreach($rsDosis as $k => $dosis):
		if($empresaAnt != $dosis["cod_entidad"]):
			$empresaAnt = $dosis["cod_entidad"];
?>
  <?php
   /*
  ?><tr>
    	<td colspan="7" class="head"><?php echo $dosis["empresa"]; ?></td>
    </tr><?php 
    */
    ?>
		<?php endif; ?>
    <?php 
    $wmodel = new WebModel();
    $cedula = $dosis["cedula"];
    
    $firma = $wmodel->getFirmaMedicoV2($cedula,180,90, "margin-top:10px");

    
    ?>

    <tr>
      <td class="row"><?php echo $dosis["orden_med"]; ?></td>
      <td class="row"><?php echo $dosis["item"]; ?></td>
	   <td class="row"><?php echo $dosis["cum"]; ?></td>
	    <td class="row"><?php echo $dosis["cantidad"]; ?></td>
      <td class="row2"><?php echo $dosis["medicamento"]; ?></td>      
      <td class="row"><?php echo $dosis["dosis"] ." " .$dosis["unimed"]; ?></td>
      <td class="row2"><?php echo convertirFecha($dosis["fecha"]); ?></td>
      <td class="row"><?php echo $dosis["hora"]; ?></td>
      <td class="row"><?php echo $dosis["viaAdministracion"]; ?></td>
      <td class="row2"><?php echo $dosis["usuario"]; ?></td>
     <td class="row2"><?php echo $firma; ?></td>
      <td class="row2"><?php echo $dosis["ufuncional"]; ?></td>
    </tr>
    
<?php
	endforeach;

?>	
  </table>
</div>
<tr>
	<td> <hr color="green" size=1 width="150"></td>
		</tr>
<br>
<div style="text-align:center" class="block"><span><strong>Infusiones</span>
  <table id = "customers">
  <tr>
      <th width="50" class="head" ><strong>ORDEN</th>
      <th width="50" class="head"><strong>C&Oacute;DIGO</th>
      <th class="head"><strong>DESCRIPCI&Oacute;N</th>     
      <th width="70" class="head"><strong>DOSIS</th>
      <th class="head"><strong>FECHA</th>
      <th width="50" class="head"><strong>HORA ADMON</th>
      <th class="head"><strong>V.A.</th>
      <th align="center" class="head"><strong>ENFERMERO(A)</th>
	    <th align="center" class="head"><strong>UFUNCIONAL</th>
    </tr>
<?php
	$empresaAnt = "";
	$rsDosis = $model->RSAsociativo("Exec spReporteAdmonInfusiones @ingreso = $ingreso, @fein = '".$_REQUEST["fecha_inicio"]."' , @fefi = '".$_REQUEST["fecha_fin"]."', @Ufuncional = {$ufuncional}");
	foreach($rsDosis as $k => $dosis):
		if($empresaAnt != $dosis["cod_entidad"]):
      $empresaAnt = $dosis["cod_entidad"];
      

?>
	<?php /*?><tr>
    	<td colspan="7" class="head"><?php echo $dosis["empresa"]; ?></td>
    </tr><?php */?>
		<?php endif; ?>
    <tr>
      <td class="row"><?php echo $dosis["orden_med"]; ?></td>
      <td class="row"><?php echo $dosis["item"]; ?></td>
      <td class="row2"><?php echo $dosis["medicamento"]; ?></td>      
      <td class="row"><?php echo $dosis["dosis"] ." " .$dosis["unimed"]; ?></td>
      <td class="row2"><?php echo convertirFecha($dosis["fecha"]); ?></td>
      <td class="row"><?php echo $dosis["hora"]; ?></td>  
      <td class="row"><?php echo $dosis["viaAdministracion"]; ?></td>      
      <td class="row2"><?php echo $dosis["usuario"]; ?></td>
	  <td class="row2"><?php echo $dosis["ufuncional"]; ?></td>
    </tr>
<?php
	endforeach;
?>	
  </table>
</div>
<tr>
	<td> <hr color="green" size=1 width="150"></td>
		</tr>
<br>
<div style="text-align:center" class="block"><span><strong>Medicamentos</span>
  <table id= "customers">
    <tr>
      <th width="50" class="head" ><strong>ORDEN</th>
      <th width="50" class="head"><strong>C&Oacute;DIGO</th>
      <th class="head"><strong>DESCRIPCI&Oacute;N</th>     
      <th class="head"><strong>FECHA ADM</th>
      <th width="50" class="head"><strong>HORA ADMON</th>
      <th class="head"><strong>CANTIDAD</th>
      <th align="center" class="head"><strong>ENFERMERO(A)</th>
      <th align="center" class="head"><strong>FIRMA</th>
	    <th align="center" border="1" class="head"><strong>UFUNCIONAL</th>
    </tr>
<?php
	$empresaAnt = "";
	foreach ($rsUnidades as $k => $medicamento) {
		if($empresaAnt != $medicamento["cod_entidad"]){
			$empresaAnt = $medicamento["cod_entidad"];
?>
   

		<?php } ?>

    <?php 
    $wmodel = new WebModel();
    $cedula = $medicamento["cedula"];
    $firma3 = $wmodel->getFirmaMedicoV2($cedula,180,90, "margin-top:10px");
    ?>
<tr>
    <td class="row"><?php echo $medicamento["nro_orden"]; ?></td>
      <td class="row"><?php echo $medicamento["articulo"]; ?></td>
      <td class="row5"><?php echo $medicamento["descripcion"]; ?></td>      
      <td class="row2"><?php echo convertirFecha($medicamento["fecha"]); ?></td>
      <td class="row2"><?php echo $medicamento["h_suministro"]; ?></td>
      <td class="row2"><?php echo $medicamento["cantidad"]; ?></td>
      <td class="row2"><?php echo $medicamento["enfermera_o"]; ?></td>
      <td class="row2"><?php echo $firma3; ?></td>
	   <td class="row2"><?php echo $medicamento["ufuncional"]; ?></td>
</tr>
<?php
	}
?>	
  </table>
</div>
<!-- <?php
	pie();
?> -->
