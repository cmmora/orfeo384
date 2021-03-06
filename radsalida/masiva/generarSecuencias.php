<?
session_start();
/**
 * Metodo alternativo para radicacion masiva
 * @author      Johnny Gonzalez
 *              Adaptacion Jairo Losada Correlibre.org  29/12/2012
 * @version     2.0
 */

$ruta_raiz = "../../";
 if (!$_SESSION['dependencia'])
        header ("Location: $ruta_raiz/cerrar_session.php");

// Modificado 2010 aurigadl@gmail.com
/**
* Paggina Cuerpo.php que muestra el contenido de las Carpetas
* Creado en la SSPD en el año 2003
* Se añadio compatibilidad con variables globales en Off
* @autor Jairo Losada 2009-05
* @licencia GNU/GPL V 3
*/

foreach ($_GET as $key => $valor)   ${$key} = $valor;
foreach ($_POST as $key => $valor)   ${$key} = $valor;

define('ADODB_ASSOC_CASE', 1);

$verrad         = "";

$krd            = $_SESSION["krd"];
$dependencia    = $_SESSION["dependencia"];
$usua_doc       = $_SESSION["usua_doc"];
$codusuario     = $_SESSION["codusuario"];
$digitosDependencia = $_SESSION["digitosDependencia"];

//include( "$ruta_raiz/debugger.php" );
require_once("$ruta_raiz/include/db/ConnectionHandler.php");
if (!$db)	$db = new ConnectionHandler($ruta_raiz);
$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
$phpsession = session_name()."=".session_id();
?>
<html>
<head>
<link rel="stylesheet" href="../../estilos/orfeo.css">
<script language="JavaScript">
<!--
function advertencia( cantidadRegistros, form )
{	var confirmaSecuencias = confirm( 'Seguro que desea generar ' + cantidadRegistros + ' secuencias para radicacion masiva?\nEl proceso no se puede revertir y seria necesario realizar la anulacion manual de todos los radicados generados.' );
	if( confirmaSecuencias )
	{
<?php
		$arreglo = array();
		//var_dump("Tipo rad" . $tipoRad);
		if ( $tipoRad && ( $cantidadRegistros != '' || $cantidadRegistros != null ) )
		{
			require_once($ruta_raiz."/class_control/Dependencia.php");

			$objDependecia = new Dependencia($db);
			$objDependecia->Dependencia_codigo($dependencia);
			$cursor = & $db;
			$cuenta = 0;
			$arregloH[] = "<table><tr><td>*RADICADO*</td><td>*TIPO*</td><td>*NIT*</td><td>*NOMBRE*</td><td>*MUNI_NOMBRE*</td><td>*DEPTO_NOMBRE*</td><td>*DIR*</td><td>*EMAIL*</td><td>*AÑO*</td></tr>";
			while ( $cuenta < $cantidadRegistros )
			{
				$secRadicacion = "secr_tp".$tipoRad."_".$objDependecia->getSecRadicTipDepe($dependencia,$tipoRad);
	 			$sec = $cursor->nextId($secRadicacion);
			 	$sec = str_pad($sec,6,"0",STR_PAD_LEFT);
				$depCompleto = str_pad($dependencia,$digitosDependencia,"0",STR_PAD_LEFT);
				$arreglo[] = date("Y").$depCompleto.$sec.substr($secRadicacion,7,1);
				$arregloH[] = "<tr><td>".date("Y").$depCompleto.$sec.substr($secRadicacion,7,1)."</td></tr>";
				$cuenta++;
			}
			$arregloH[]="</table>";
?>
			alert( 'Se generaron las ' + cantidadRegistros + ' secuencias entre el <?=$arreglo[0] ?> y el <?=$arreglo[$cuenta - 1 ] ?>, a continuacion apareceran en una ventana aparte,\npara que las copie a una hoja de calculo.');
			ventana = window.open(' ','secuenciasMasiva','menubar=no,scrollbars=yes,width=650,height=550');
			ventana.opener = self;
<?php
			$cadena = "<HTML><HEAD><TITLE>Secuencias para Radicaci&oacute;n Masiva</TITLE></HEAD><BODY><CENTER>";
			$cadena .= "<br><br><br><table border=1><tr><td>*RAD_S*</td><td>*TIPO*</td><td>*NIT*</td><td>*NOMBRE*</td><td>*MUNI_NOMBRE*</td><td>*DEPTO_NOMBRE*</td><td>*DIR*</td><td>*EMAIL*</td><td>*ANO*</td><td>*PAIS_NOMBRE*</td></tr>";
			foreach ( $arreglo as $numeroRadicado )
           	{
           		$cadena .= "<tr><td>" . $numeroRadicado."</td><td>1</td><td></td><td></td><td>BOGOTA</td><td>D.C.</td><td></td><td></td><td>".date("Y")."</td><td>COLOMBIA</td></tr>";
           	}
			$cadena .= "</table><br><br><br></CENTER></BODY></HTML>";
?>
            ventana.document.write("<?php echo ($cadena) ?>");
			ventana.document.close();
<?php
			$confirmado = true;
			require_once $ruta_raiz."/class_control/class_controlExcel.php";
				$controlSec = new CONTROL_ORFEO($db);
				if ($arreglo[ 1 ] != null || $arreglo[ 1 ] != '') {
					$intervaloSecuencia = $arreglo[ 1 ] - $arreglo[ 0 ];
				}else {
					$intervaloSecuencia = 0;
				}

             	$resultadoInsertaSec = $controlSec -> insertaSecuencias( $arreglo[ 0 ], $arreglo[ $cuenta - 1 ], $intervaloSecuencia, $dependencia, $codusuario, $tipoRad );
             	if( !$resultadoInsertaSec )
             	{
?>
              		alert('Se produjo un error insertando los datos de las secuencias.');
              		return false;
<?php
             	}

		}
		else
		{
?>
			alert('Debe seleccionar el tipo de radicacion.');
			return false;
<?php
		}
?>

	}
	else
	{
		return false;
	}
}

function valida( form )
{
	var cantidad = document.frmGeneraSecuenciasMasiva.cantidadRegistros.value;
	var band = false;
	if((cantidad == "") ||(document.frmGeneraSecuenciasMasiva.tipoRad.value == 0))
	{
		alert( 'Debe gestionar todos los datos!' );
		band = false;
	}
	else
	{
		band = advertencia( cantidad, form );
	}
	return band;
}

function ejecuta(){
	alert('ejecuta');
}


function f_close(){
	window.close();
}

function Start(URL, WIDTH, HEIGHT)
{
 windowprops = "top=0,left=0,location=no,status=no, menubar=no,scrollbars=yes, resizable=yes,width=";
 windowprops += WIDTH + ",height=" + HEIGHT;

 preview = window.open(URL , "preview", windowprops);
}
//-->
</script>
</head>
<body bgcolor="#FFFFFF" topmargin="0">
<form name="frmGeneraSecuenciasMasiva" action='generarSecuencias.php?<?=$phpsession ?>&krd=<?=$krd?>&<? echo "fechah=$fechah"; ?>' method="POST">
<table width="75%" align="center" border="0" cellpadding="0" cellspacing="5" class="borde_tab">
<tr>
	<td height="25" class="titulos4" colspan="2">RADICACI&Oacute;N MASIVA DE DOCUMENTOS   (Generaci&oacute;n de Secuencias)</td>
</tr>
<tr align="center" >
	<td class="listado2" colspan="2">
		La radicaci&oacute;n requiere la generaci&oacute;n de las secuencias para los radicados, si desea generarlas,
		<br>ingrese la cantidad de registros a procesar y haga click en Generar, de lo contario en Cerrar.
	</td>
</tr>
<tr align="center">
	<td class="listado2" >
		Cantidad de Registros
		</a>
	</td>
	<td class="listado2" >
		<input type="text" name="cantidadRegistros" value="<?=$cantidadRegistros?>" size="5" maxlength="4">
	</td>
</tr>
<tr align="center">
	<td class="listado2" colspan="2" >
	<table width="31%" align="center" border="0" cellpadding="0" cellspacing="5" class="borde_tab">
	<tr align="center">
		<td height="25" colspan="2" class="titulos4">TIPO DE RADICACI&oacute;N</td>
	</tr>
	<tr align="center">
		<td width="16%" class="titulos2">Seleccione: </td>
		<td width="84%" height="30" class="listado2">
			<?php
				$cad = "USUA_PRAD_TP";
				// Creacion del combo de Tipos de radicado habilitados seg�n permisos
				$sql = "SELECT SGD_TRAD_CODIGO,SGD_TRAD_DESCR FROM SGD_TRAD_TIPORAD WHERE SGD_TRAD_GENRADSAL > 0";	//Buscamos los TRAD En la entidad
				$Vec_Trad = $db->conn->GetAssoc($sql);
				$Vec_Perm = array();
				while (list($id, $val) = each($Vec_Trad))
				{	$sql = "SELECT ".$cad.$id." FROM USUARIO WHERE USUA_LOGIN='".$krd."'";
					$rs2 = $db->conn->Execute($sql);
					if  ($rs2->fields[$cad.$id] > 0)
					{	$Vec_Perm[$id] = $val;
					}
				}
				//print_r($Vec_Perm);
				reset($Vec_Perm);
			?>
			<select name="tipoRad" id="Slc_Trd" class="select" onchange="this.form.submit();">
			<option value="0">Seleccione una opci&oacute;n</option>
				<?
				while (list($id, $val) = each($Vec_Perm))
				{
					if($tipoRad==$id) $datoss = " selected "; else $datoss="";
					echo " <option value=".$id." $datoss>$val</option>";
				}
				?>
			</select>
		</td>
	</tr>
</table>
	</td>
</tr>
<tr align="center">
	<td class="listado2" colspan="2" >
		<center>
			<input type="button" name="Submit" value="Generar" class="botones" onclick="valida(this.form);">
		</center>
	</td>
</tr>
<tr align="center">
	<td class="listado2" colspan="2" >
		<center>
			<input class="botones" type=button name=Cerrar id=Cerrar Value=Cerrar onclick='f_close()'>
		</center>

	</td>
</tr>
</table>
</form>
</body>
</html>
