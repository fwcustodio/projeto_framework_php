<?
//Starta Sessão
session_start();

//Busca Dados de Configuração
include_once('../../framework/config.conf.php'); ConfigSIS::Conf();

//Definindo Módulo/Pacote
define("MODULO","servicos_produtos");
define("PACOTE","conteudo");

//Chamando Arquivos do Sistema
include_once($_SESSION['FMBase'].'acesso.class.php'); 	       
$Ac = new Acesso();

//Verifica Permissão
if(!$Ac->permissao())
{
	exit('<script language="javascript"> window.parent.erroPrint(); </script>');
}

//Cabeçalho padrão
include_once($_SESSION['DirBase'].'includes/cabecalho_html.inc.php');

?>
</head>
<body>

<?
//Topo
include_once($_SESSION['DirBase'].'includes/topo_impressao.inc.php');

include_once($_SESSION['DirBase'].PACOTE.'/'.MODULO.'/'.MODULO.'.class.php'); 
include_once($_SESSION['DirBase'].PACOTE.'/'.MODULO.'/'.MODULO.'.form.php');

$Form   = new ServicoForm();
$Serv = new Servico();

try 
{
	
	$Form->setEnv("true");
	$Form->setOp('Fil');
	$Form->getFormFiltro();
	
	echo($Serv->filtrar($Form));
	
	echo '<script language="javascript"> $(document.body).ready(function(){ window.parent.sucessoPrint(); }); </script>';
}
catch (Exception $E)
{
	
	echo '<script language="javascript">window.parent.sucessoPrint();</script>';
}
?>
</body>
</html>