<?
//Starta Sess�o
session_start();

//Verifica se sess�o ainda esta ativa
if(empty($_SESSION['DirBase'])) header("Location: http://".$_SERVER['REMOTE_ADDR']."/sis/?Msg=3"."&Ref=".urlencode($_SERVER['HTTP_REFERER']));

//Classe de configura��es
include_once($_SESSION['DirBase'].'framework/config.conf.php'); ConfigSIS::Conf();

//Definindo M�dulo/Pacote
define("MODULO","log");
define("PACOTE","relatorios");

//Chamando Arquivos do Sistema
include_once($_SESSION['FMBase'].'acesso.class.php'); 	       
$Ac = new Acesso();

//Verifica Permiss�o
if(!$Ac->permissao())
{
	exit('<script language="javascript"> window.parent.erroPrint(); </script>');
}

//Cabe�alho padr�o
include_once($_SESSION['DirBase'].'includes/cabecalho_html.inc.php');

?>
</head>
<body>

<?
//Topo
include_once($_SESSION['DirBase'].'includes/topo_impressao.inc.php');

include_once($_SESSION['DirBase'].PACOTE.'/'.MODULO.'/'.MODULO.'.class.php'); 
include_once($_SESSION['DirBase'].PACOTE.'/'.MODULO.'/'.MODULO.'.form.php');

$Form = new LogsForm();
$Logs = new Logs();

try 
{
	$Form->setEnv("true");
	$Form->setOp('Fil');
	$Form->getFormFiltro();
	
	echo($Logs->filtrar($Form));
	
	echo '<script language="javascript"> $(document.body).ready(function(){ window.parent.sucessoPrint(); }); </script>';
}
catch (Exception $E)
{
	
	echo '<script language="javascript">window.parent.sucessoPrint();</script>';
}
?>
</body>
</html>