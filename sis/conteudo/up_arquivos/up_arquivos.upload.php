<?
//Starta Sessгo
session_start();

//Modifica Para Limite Mбximo de Execuзгo Permitido no Servidor
set_time_limit(0);

//Busca Dados de Configuraзгo
include_once('../../framework/config.conf.php'); ConfigSIS::Conf();

//Definindo Mуdulo/Pacote
define("MODULO","up_arquivos");
define("PACOTE","conteudo");

//Chamando Arquivos do Sistema
include_once($_SESSION['FMBase'].'acesso.class.php'); 	       
$Ac = new Acesso();

//Includes
include_once($_SESSION['FMBase'].'arquivos.class.php');
include_once($_SESSION['DirBase'].PACOTE.'/'.MODULO.'/'.MODULO.'.class.php');
include_once($_SESSION['DirBase'].PACOTE.'/'.MODULO.'/'.MODULO.'.form.php');

//Instancias
$Arquivos  = new Arquivos();
$Form      = new UpArquivoForm();
$UPArquivo = new UpArquivo();

//Recupera Parametro de Identificaзгo de Projeto Multimidia
$Form->setEnv("true");
$Form->setOp("Cad");
$Form->setNomeForm("FormManu");
$Form->getFormManu();

//Intercepta Erros
$Erro = $Form->getErro();

try 
{	
	//Verifica Erros
	if(!empty($Erros)) throw new Exception($Erro);
	
	//Inicia Conexгo
	$Con = Conexao::conectar();		
	
	//Determina Tipo de Arquivo
	$Extensao = strtolower($Arquivos->extenssaoArquivo($_FILES['FotosVideos']['name']));
			
	//Recupera o Cуdigo da Galeria Multimidia  Selecionada
	$ArquivoCategoriaCod = $Form->getCampoRetorna("ArquivoCategoriaCod");

	//Inicia Transaзгo		
	$Con->startTransaction();

	//Hash
	$Hash = @md5(time().mt_rand());	
	
	//Seta Valores no Form
	$Form->setCampoRetorna("ArquivoNome",$_FILES['FotosVideos']['name']);
	$Form->setCampoRetorna("HashCod",$Hash);
	$Form->setCampoRetorna("Extensao",$Extensao);
	
	//Grava no Banco
	$UPArquivo->cadastrar($Form);
	
	//Recupera Id Gerado
	$ArquivoCod = $UPArquivo->getArquivoCod();

	//Definindo o nome dos arquivos	
	$Arquivo  = $_SESSION['DirBaseSite'].'arquivos/arquivos/'.$ArquivoCategoriaCod.'/'.$Hash.'.'.$Extensao;
	
	//Upload - Imagem Grande
	$Arquivos->upload("FotosVideos",$Arquivo ,null,array());
	
	//Finaliza Transaзгo					
	$Con->stopTransaction();	
}
catch (Exception $E)
{
	header("HTTP/1.1 500");
}
?>