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

//Verificando permissões
$Ac->acessoModulo();

//Icluindo Arquivos de Sistema
include_once($_SESSION['DirBase'].PACOTE.'/'.MODULO.'/'.MODULO.'.form.php');
include_once($_SESSION['FMBase'].'conteiner.class.php'); 
include_once($_SESSION['FMBase'].'filtro.class.php'); 
include_once($_SESSION['FMBase'].'botoes_acesso.class.php'); 

//Instanciando Classes
$Form   = new ServicoForm();
$Cont   = new Conteiner();
$Bta    = new BotoesAcesso();
$Fil    = new Filtro();

//Cabeçalho padrão
include_once($_SESSION['DirBase'].'includes/cabecalho_html.inc.php');

//Validação e Arquivos
$Form->getFormManu();
$Form->getFormManuIntro();
$Form->getFormFiltro();
echo $Form->chamaArquivos();

$Form->resetTodos();

$Form->setNomeForm("FormFiltro");
$Form->getFormFiltro();

echo $Form->geraFuncoes(), $Form->geraMascaras(), $Form->geraOnLoad(), $Form->geraOpcoesDeFiltro();

?>

<script language=javascript>


function CheckAll(IdForm,obj) { 
	
	var Opcao = $(obj).val();
	var Opcao2 = $(obj).attr('checked');
	
	
	if( Opcao2 == true){
   		$('#Lancamento').show();
	} else {
   		$('#Lancamento').hide();
	}
} 

function CheckAll_2(IdForm,obj) { 
	
	var Opcao = $(obj).val();
	var Opcao2 = $(obj).attr('checked');
	
	
	if( Opcao2 == false){
   		$('#Lancamento').show();
		$('#Atualizacao').attr('checked','checked');
	} else {
   		$('#Lancamento').hide();
	}
} 


</script>
<style type="text/css">
.classConteudo
{
	border:solid 1px #999999;
	background:#E6F2FF;
	border-right:none;
	padding:3px;
}

.classConfig
{
	border:solid 1px #999999;
	background:#E6F2FF;
	border-left:dashed 1px #999999;
	padding:3px;
	width:300px;
}


.classTitulo
{
	font-family:Arial, Helvetica, sans-serif;
	font-size:12px;
	font-weight:bold;
	background:#D2E4FF;
	height:30px;
}

.classTituloDescricao
{
	font-family:Arial, Helvetica, sans-serif;
	font-size:12px;
	background:#dbe9ff;
	height:30px;
}


#cTArquivo, #cTGaleriaMidia, #cTEnquete 
{
	font-family:Arial, Helvetica, sans-serif;
	font-size:11px;
	height:20px;
}

#cTArquivo td, #cTGaleriaMidia td, #cTEnquete td 
{ 
	height:25px;
	border-bottom:dashed 1px #003366;
}

#tbRevisoes td
{
	height:20px;
	border-bottom:solid 1px #CCCCCC;
	font-family:Arial, Helvetica, sans-serif;
	font-size:11px;
}

#divRevisoes
{
	margin:0;
	padding:0;
	overflow:auto;
	height:100px;
}


.campoUpload {
	font-family:Arial, Helvetica, sans-serif;
	font-size:12px;
	color:#000;
	text-decoration:none;
}

.campoUpload {
	font-family:Arial, Helvetica, sans-serif;
	font-size:12px;
	color:#000;
	text-decoration:none;
	overflow:hidden;
	width:290px;
	margin-top:10px;
	margin-bottom:10px;
	text-align:justify;
}
.campoUpload input {
	width:280px;
}

a:link, a:visited, a:active {
	color:#3c5e92;
	text-decoration: none;
}
a:hover {
	text-decoration: underline; 
	color:#1e3b66;
}

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