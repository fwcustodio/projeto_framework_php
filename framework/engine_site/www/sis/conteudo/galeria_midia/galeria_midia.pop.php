<?
//Starta Sessão
session_start();

//Busca Dados de Configuração
include_once('../../framework/config.conf.php'); ConfigSIS::Conf();

//Definindo Módulo/Pacote
define("MODULO","galeria_midia");
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
$Form   = new GaleriaMidiaForm();
$Cont   = new Conteiner();
$Bta    = new BotoesAcesso();
$Fil    = new Filtro();

//Cabe?ho padr?
ConfigSIS::$CFG['LarguraTabela'] = "99%";
include_once($_SESSION['DirBase'].'includes/cabecalho_html.inc.php');
echo "<script language=\"javascript\"> var FORMGLOBAL = '".$_GET['IdForm']."'; var CAMPOGLOBAL = '".$_GET['TipoCampo']."'; </script>";

//Validação e Arquivos
$Form->getFormManu();
$Form->getFormPop();
echo $Form->chamaArquivos();

$Form->resetTodos();

$Form->setNomeForm("FormFiltro");
$Form->getFormPop();

echo $Form->geraFuncoes(), $Form->geraMascaras(), $Form->geraOnLoad(), $Form->geraOpcoesDeFiltro();

?>
<style>
#campoRespostas {
	background:#e0e6ed;
	border:#ced8e1 1px solid;
	width:380px;
	margin:3px;
	padding:5px;
}
.mao{ cursor:pointer; }
</style>
<script>
function exibeImg(tipo)
{
	if(tipo == 'S') {
		$("#AlertaImagemCapa").hide('low');
		$("#ImagemCapa").show('low');
	} else {
		$("#AlertaImagemCapa").show('low');
		$("#ImagemCapa").hide('low');
	}
		
}
</script>
</head>
<body onLoad="self.focus(); sisShowFiltro();">

<?
//Conteiner Carregando
echo $Cont->carregando();

//Topo
include_once($_SESSION['DirBase'].'includes/topo_pop.inc.php'); 

//Conteiner para Filtro
echo $Cont->abreConteiner("corpoFiltro","filtrar"),
	 $Fil->montaFiltro($Form->getFiltro()), 
	 $Cont->fechaConteiner();

//Botões de Acesso
$Bta->setBotaoImprimir(false);
echo $Bta->geraBotoes(array('sis_inicial','sis_sair','Alt','Vis','Del'));
	 
 
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