<?
//Starta Sess�o
session_start();

//Busca Dados de Configura��o
include_once('../../framework/config.conf.php'); ConfigSIS::Conf();

//Definindo M�dulo/Pacote
define("MODULO","contato_forma");
define("PACOTE","interatividade");

//Chamando Arquivos do Sistema
include_once($_SESSION['FMBase'].'acesso.class.php'); 	       
$Ac = new Acesso();

//Verificando permiss�es
$Ac->acessoModulo();

//Icluindo Arquivos de Sistema
include_once($_SESSION['DirBase'].PACOTE.'/'.MODULO.'/'.MODULO.'.form.php');
include_once($_SESSION['FMBase'].'conteiner.class.php'); 
include_once($_SESSION['FMBase'].'filtro.class.php'); 
include_once($_SESSION['FMBase'].'botoes_acesso.class.php'); 

//Instanciando Classes
$Form   = new ContatoFormaForm();
$Cont   = new Conteiner();
$Bta    = new BotoesAcesso();
$Fil    = new Filtro();

//Cabe�alho padr�o
include_once($_SESSION['DirBase'].'includes/cabecalho_html.inc.php');

//Valida��o e Arquivos
$Form->getFormManu();
$Form->getFormFiltro();
echo $Form->chamaArquivos();

$Form->resetTodos();

$Form->setNomeForm("FormFiltro");
$Form->getFormFiltro();

echo $Form->geraFuncoes(), $Form->geraMascaras(), $Form->geraOnLoad(), $Form->geraOpcoesDeFiltro();

?>
<script type="text/javascript" src="<?=$_SESSION['JSBase'];?>js/cluetip/jquery.cluetip.js"></script>
<script type="text/javascript" src="<?=$_SESSION['JSBase'];?>js/cluetip/jquery.dimensions.js"></script>
<link rel="stylesheet" href="<?=$_SESSION['UrlBase'].PACOTE.'/'.MODULO.'/cluetip.css'?>" type="text/css">
</head>
<body>

<?
//Conteiner Carregando
echo $Cont->carregando();

//Topo
include_once($_SESSION['DirBase'].'includes/topo.inc.php'); 

//Conteiner para Filtro
echo $Cont->abreConteiner("corpoFiltro","filtrar"),
	 $Fil->montaFiltro($Form->getFiltro()), 
	 $Cont->fechaConteiner();

//Bot�es de Acesso
echo $Bta->geraBotoes();
	 
//Conteiner Para Mensagens (Alertas, Informa��es e Erros)
echo $Cont->abreConteiner("mensagem"),$Cont->fechaConteiner();

//Conteiner Para Manuten��o (Cadastro e Altera��o)
echo $Cont->abreConteiner("manu"),$Cont->fechaConteiner();

//Conteiner Principal
echo $Cont->abreConteiner("corpoPrincipal"),$Cont->fechaConteiner();

include_once($_SESSION['DirBase'].'includes/rodape.inc.php'); 
?>

</body>
</html>