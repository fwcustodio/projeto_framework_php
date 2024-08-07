<?php
//Starta Sessão
session_start();

//Content
@header("Content-Type: text/html; charset=ISO-8859-1",true);

include_once('../sis/framework/config.conf.php'); 								ConfigSIS::Conf();
//Chamando Arquivos
include_once($_SESSION['FMBase'].'conexao.class.php');
include_once($_SESSION['FMBase'].'paginacao.class.php'); 			$Pag = new Paginacao();
include_once($_SESSION['FMBase'].'funcoes_php.class.php');	   		$FPHP = new FuncoesPHP();
//include_once($_SESSION['FMBase'].'menu.class.php');					$Menu = new Menu();
include_once($_SESSION['DirBaseSite'].'multimidia/multimidia.class.php'); 		$Midia  = new Midia();

//Inicia Conexão
$Con = Conexao::conectar();

$Tipo		 	= $_GET['Tipo'];
$ArquivoCod 	= $_GET['ArquivoCod'];


$Link = $_SESSION['UrlBaseSite']."arquivos/multimidia/".$Midia->recuperaArquivo($Tipo, $ArquivoCod, "A");
?>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>

<body bgcolor="#ffffff" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" scroll="no">

<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center" valign="middle">
    <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="273" height="131">
  <param name="movie" value="player.swf?Musica=<?=$Link?>" />
  <param name="quality" value="high" />
  <param name="wMode" value="transparent" />
  <embed src="player.swf?Musica=<?=$Link?>" width="273" height="131" quality="high" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" wmode="transparent"></embed>
</object>
    </td>
  </tr>
</table>
</body>
</html>
