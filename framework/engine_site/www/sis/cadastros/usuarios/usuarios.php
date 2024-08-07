<?
//Starta Sessão
session_start();

// HTTP/1.1 - Elimina Cache
header("Content-Type: text/html; charset=ISO-8859-1",true);

//Verifica se sessão ainda esta ativa
if(empty($_SESSION['DirBase'])) header("Location: http://".$_SERVER['REMOTE_ADDR']."/sis/?Msg=3"."&Ref=".urlencode($_SERVER['HTTP_REFERER']));

//Classe de configurações
include_once($_SESSION['DirBase'].'framework/config.conf.php'); ConfigSIS::Conf();

//Definindo Módulo/Pacote
define("MODULO","usuarios");
define("PACOTE","cadastros");

//Chamando Arquivos do Sistema
include_once($_SESSION['FMBase'].'acesso.class.php');
$Ac = new Acesso();

//Verificando permissões
$Ac->acessoModulo();

//Icluindo Arquivos de Sistema
include_once($_SESSION['DirBase'].PACOTE.'/'.MODULO.'/'.MODULO.'.form.php');
include_once($_SESSION['DirBase'].PACOTE.'/contato/contato.form.php');
include_once($_SESSION['DirBase'].PACOTE.'/endereco/endereco.form.php');
include_once($_SESSION['FMBase'].'conteiner.class.php');
include_once($_SESSION['FMBase'].'filtro.class.php');
include_once($_SESSION['FMBase'].'botoes_acesso.class.php');

//Instanciando Classes
$Form   = new UsuariosForm();
$FormC  = new ContatoForm($Form);
$FormE  = new EnderecoForm($Form);
$Cont   = new Conteiner();
$Bta    = new BotoesAcesso();
$Fil    = new Filtro();

//Cabeçalho padrão
include_once($_SESSION['DirBase'].'includes/cabecalho_html.inc.php');

//Validação e Arquivos
$Form->getFormFiltro();
$Form->getFormManu();
$Form->getFormAcesso();
echo $Form->chamaArquivos();

$Form->resetTodos();

$Form->setNomeForm("FormFiltro");
$Form->getFormFiltro();
$FormC->ajaxRetorno();
$FormE->ajaxRetorno();

echo $Form->geraFuncoes(), $Form->geraMascaras(), $Form->geraOnLoad(), $Form->geraOpcoesDeFiltro();

?>
<link href="css/tabs.css" rel="stylesheet" type="text/css" />

</head>
<body>

<?
//Conteiner Carregando
echo $Cont->carregando();

//Topo
include_once($_SESSION['DirBase'].'includes/topo.inc.php');
echo '<link rel="stylesheet" type="text/css" href="'.$_SESSION['CSSBase'].'css/css_permissoes_usuarios.css">';

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