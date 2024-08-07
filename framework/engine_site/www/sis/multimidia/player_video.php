<?php
//Starta Sessão
session_start();

//Content
@header("Content-Type: text/html; charset=ISO-8859-1",true);

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


$Link = $_SESSION['UrlBaseSite']."arquivos/multimidia/".$Midia->recuperaArquivo($Tipo, $ArquivoCod, "V");
?>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

</head>

<body bgcolor="#333333" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" scroll="no">
<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center">
          <script>
	document.write ('<object id="MP1" width=320 height=240 classid="CLSID:22D6F312-B0F6-11D0-94AB-0080C74C7E95" standby="Loading Microsoft&reg; Windows&reg; Media Player components..." type="application/x-oleobject" viewastext>' +
      '<param name="FileName" value="<?=$Link?>">' + 
      '<param name="AnimationatStart" value="True">' + 
      '<param name="ShowControls" value="False">' + 
      '<param name="ShowPositionControls" value="False">' + 
      '<param name="ShowAudioControls" value="False">' + 
      '<param name="ShowTracker" value="False">' + 
      '<param name="ShowStatusBar" value="True">' + 
      '<param name="AutoStart" value="True">' + 
      '<embed type="application/x-mplayer2" pluginspage = "http://www.microsoft.com/Windows/MediaPlayer/" src="<?=$Link?>" width=320 height=240 animationatstart=1 showstatusbar=1 showcontrols=0 showpositioncontrols=0 showaudiocontrols=0 showtracker=0 autostart=1></embed>' + 
    '</object>');
	      </script>	
	</td>
  </tr>
</table>
</body>
</html>
