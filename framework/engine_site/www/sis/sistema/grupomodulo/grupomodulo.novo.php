<?
//Starta Sess?
session_start();

//Busca Dados de Configura?
include_once('../../framework/config.conf.php'); ConfigSIS::Conf();

//Definindo M?o/Pacote
define("MODULO","grupomodulo");
define("PACOTE","sistema");

//Chamando Arquivos do Sistema
include_once($_SESSION['FMBase'].'acesso.class.php'); 	       
$Ac = new Acesso();

//Verificando permiss?
$Ac->acessoModulo();

//Icluindo Arquivos de Sistema
include_once($_SESSION['FMBase'].'grid.class.php'); 	       
include_once($_SESSION['DirBase'].PACOTE.'/'.MODULO.'/'.MODULO.'.form.php');
include_once($_SESSION['FMBase'].'conteiner.class.php'); 
include_once($_SESSION['FMBase'].'filtro.class.php'); 

//Instanciando Classes
$Gr     = new Grid();
$Form   = new GrupoModuloForm();
$Cont   = new Conteiner();
$Fil    = new Filtro();
$JS     = new JavaScript();

//Cabe?ho padr?
ConfigSIS::$CFG['LarguraTabela'] = "99%";
include_once($_SESSION['DirBase'].'includes/cabecalho_html.inc.php');
echo "<script language=\"javascript\"> var FORMGLOBAL = '".$_GET['NF']."'; var CAMPOGLOBAL = '".$_GET['CP']."'; </script>";

//Valida? e Arquivos
$Form->getFormManu();
$Form->ajaxCadastro(); //***********
echo $Form->chamaArquivos();

//Chama Cadastro
echo $Form->geraFuncoes();
echo $JS->entreJS($JS->onLoad($JS->onLoad(" sis_cadastrar(); ")));

?>
</head>
<body>

<?
//Conteiner Carregando
echo $Cont->carregando();

//Topo
include_once($_SESSION['DirBase'].'includes/topo_pop.inc.php'); 
	 
//Conteiner Para Mensagens (Alertas, Informa?s e Erros)
echo $Cont->abreConteiner("mensagem"),$Cont->fechaConteiner();

//Conteiner Para Manuten? (Cadastro e Altera?)
echo $Cont->abreConteiner("manu"),$Cont->fechaConteiner();


include_once($_SESSION['DirBase'].'includes/rodape_pop.inc.php'); 
?>

</body>
</html>