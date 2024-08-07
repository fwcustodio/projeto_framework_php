<?
//Starta Sessão
session_start();

// HTTP/1.1 - Elimina Cache
@header("Content-Type: text/html; charset=ISO-8859-1",true);

//Verifica se sessão ainda esta ativa
if(empty($_SESSION['DirBase'])) header("Location: http://".$_SERVER['REMOTE_ADDR']."/sis/?Msg=3"."&Ref=".urlencode($_SERVER['HTTP_REFERER']));

//Busca Dados de Configuração
include_once($_SESSION['DirBase'].'framework/config.conf.php'); ConfigSIS::Conf();

//Definindo Módulo/Pacote
define("MODULO","log");
define("PACOTE","relatorios");

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
$Form   = new LogsForm();
$Cont   = new Conteiner();
$Bta    = new BotoesAcesso();
$Fil    = new Filtro();

//Cabeçalho padrão
include_once($_SESSION['DirBase'].'includes/cabecalho_html.inc.php');

$Form->setNomeForm("FormFiltro");
$Form->getFormFiltro();

echo $Form->chamaArquivos();

echo $Form->geraFuncoes(), $Form->geraMascaras(), $Form->geraOnLoad(), $Form->geraOpcoesDeFiltro();

?>
<script type="text/javascript" src="<?=$_SESSION['JSBase']?>js/jcarousel/jquery.jcarousel.js"></script>
<link rel="stylesheet" type="text/css" href="<?=$_SESSION['JSBase']?>js/jcarousel/jquery.jcarousel.css" />
<link rel="stylesheet" type="text/css" href="<?=$_SESSION['JSBase']?>js/jcarousel/skin_tango/skin.css" />

<style type="text/css">
<!--
#logTituloTabela
{
	height:25px;
	padding:2px;
	margin:0;
	background:url(../../figuras/fundo_topo.gif);
	text-align:center;
	font-family:"Trebuchet MS", Tahoma, Arial;
	font-size:14px;
	font-weight:bold;
	border:solid 1px #CCCCCC;
}

#logTabelaConteudo
{
	border:solid 1px #CCCCCC;
	margin:0;
	padding:0;
}

#logTabelaConteudo td
{
	height:18px;
	padding:2px;
	margin:0;
	font-family:"Trebuchet MS", Tahoma, Arial;
	font-size:12px;
}

#logTdIdentica
{
	text-align:right;
	background:#F8F8F8;
}

#logTabelaInterpretado
{
	border: dotted 1px #669966;
}

.logTdCampo
{
	border-right:dotted 1px #CCCCCC;
	border-bottom:solid 1px #CCCCCC;
	width:150px;
}

.logTdValor
{
	border-bottom:solid 1px #CCCCCC;
	text-align:left !important;
}

.logTdValor, logTdCampo
{
	padding:2px;
}

/*MOSTRAR E OCULTAR LOGS OCULTOS*/
#logOcultoImagem
{
	width:100%;
	overflow:auto;
}

.logOcultoDiv
{
	overflow:auto;
	display:none;
}

/*Campo Alterado*/
.campoAlterado
{
	background:#FFFFD5 !important;
	color:#000000 !important;
}


/*Carrosel*/
.jcarousel-skin-tango .jcarousel-container-horizontal 
{
	width: 900px;
}

.jcarousel-skin-tango .jcarousel-clip-horizontal 
{
    width: 100%;
}

.jcarousel-skin-tango li
{
	height:300px !important;
	overflow:auto;
}

/*LOG REMOVIDO*/

#idLogRemovido
{
	padding:2px;
	overflow:auto;
	width:98%;
	text-align:center;
}

#idLogRemovido div 
{
	margin:2px;
	padding:0;
	border:solid 1px #CCCCCC;
	background:#FFD9D9;
	width:98%;
	overflow:auto;
}

/*LOG REMOVIDO*/

-->
</style>

<script language="javascript">
function mostraOcultaLog(conteiner, obj)
{
	var display = $("#"+conteiner).css('display');
	
	if(display == 'none')
	{
		$("#"+conteiner).show();
		$(obj).attr('src',URLBASE+'figuras/ocultar_log.gif');
	}
	else
	{
		$("#"+conteiner).hide();
		$(obj).attr('src',URLBASE+'figuras/mostrar_log.gif');
	}
}
</script>

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