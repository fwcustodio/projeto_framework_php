<?
//Starta Sessão
session_start();

//Busca Dados de Configuração
include_once('../../framework/config.conf.php'); ConfigSIS::Conf();

//Definindo Módulo/Pacote
define("MODULO","arquivos");
define("PACOTE","conteudo");

//Chamando Arquivos do Sistema
include_once($_SESSION['FMBase'].'acesso.class.php'); 	       
$Ac = new Acesso();

//Verificando permissões
$Ac->acessoModulo();

//Icluindo Arquivos de Sistema
include_once($_SESSION['DirBase'].PACOTE.'/'.MODULO.'/'.MODULO.'.form.php');
include_once($_SESSION['FMBase'].'conteiner.class.php'); 
include_once($_SESSION['FMBase'].'filtro.class.php'); 
include_once($_SESSION['FMBase'].'botoes_acesso.class.php'); 

//Instanciando Classes
$Form   = new GaleriaArquivoAForm();
$Cont   = new Conteiner();
$Bta    = new BotoesAcesso();
$Fil    = new Filtro();

//Cabeçalho padrão
include_once($_SESSION['DirBase'].'includes/cabecalho_html.inc.php');

//Validação e Arquivos
$Form->getFormManu();
$Form->getFormFiltro();
echo $Form->chamaArquivos();

$Form->resetTodos();

$Form->setNomeForm("FormFiltro");
$Form->getFormFiltro();

echo $Form->geraFuncoes(), $Form->geraMascaras(), $Form->geraOnLoad(), $Form->geraOpcoesDeFiltro();

?>
<!-- LightBox-->
	<script src="<?=$_SESSION['UrlBaseSite']?>js/jquery.lightbox.js" language="javascript" type="text/javascript"></script>
	<link href="<?=$_SESSION['UrlBaseSite']?>css/jquery.lightbox.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?=$_SESSION['JSBase']?>js/jquery-modal.js"></script>


<style>
/* MODAL */
.load { position: absolute; top: 50%; left: 50%; width: 88px; height: 78px; margin: -39px 0 0 -44px; z-index: 99999;}
.bg_modal { position: absolute; top: 0; left: 0; z-index: 99998; }
.view_modal { position: absolute; left: 50%; top: 50%; text-align: left; z-index: 99998; }
.modal {  cursor:pointer; }
</style>

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

//Botões de Acesso
echo $Bta->geraBotoes();
	 
//Conteiner Para Mensagens (Alertas, Informações e Erros)
echo $Cont->abreConteiner("mensagem"),$Cont->fechaConteiner();

//Conteiner Para Manutenção (Cadastro e Alteração)
echo $Cont->abreConteiner("manu"),$Cont->fechaConteiner();

//Conteiner Principal
echo $Cont->abreConteiner("corpoPrincipal"),$Cont->fechaConteiner();

include_once($_SESSION['DirBase'].'includes/rodape.inc.php'); 
?>

</body>
</html>