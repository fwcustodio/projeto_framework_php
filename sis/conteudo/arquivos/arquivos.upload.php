<?
//Starta Sessгo
session_start();

//Modifica Para Limite Mбximo de Execuзгo Permitido no Servidor
set_time_limit(0);

//Busca Dados de Configuraзгo
include_once('../../framework/config.conf.php'); ConfigSIS::Conf();

//Definindo Mуdulo/Pacote
define("MODULO","arquivos");
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
$Form      = new GaleriaArquivoAForm();
$ProjetoMA = new GaleriaArquivoA();

//Recupera Parametro de Identificaзгo de Projeto Multimidia
$Form->setEnv("true");
$Form->setOp("Cad");
$Form->setNomeForm("FormManu");
$Form->getFormManu();

//Intercepta Erros
$Erro = $Form->getErro();

//Intercepta Erro
try 
{
	//Inicia Conexгo
	$Con = Conexao::conectar();	
	
	//Determina Tipo de Arquivo
	$Extensao = strtolower($Arquivos->extenssaoArquivo($_FILES['FotosVideos']['name']));
	$Form->setCampoRetorna("Extensao",$Extensao);//Seta Extensao
	
	if($Extensao == "jpg" or $Extensao == "jpeg" or $Extensao == 'gif' or $Extensao == 'png')
	{
		$TipoArquivo = "F";
		$Form->setCampoRetorna("TipoArquivo",$TipoArquivo);
	}
	elseif ($Extensao == "wmv" or $Extensao == "avi")
	{
		$TipoArquivo = "V";
		$Form->setCampoRetorna("TipoArquivo",$TipoArquivo);
	}
	else if($Extensao == "mp3" or $Extensao == "wma")
	{
		$TipoArquivo = "A";
		$Form->setCampoRetorna("TipoArquivo",$TipoArquivo);
	}	
		
	//Verifica Erros
	if(!empty($Erros)) throw new Exception($Erro);
	
	//Recupera o Cуdigo da Galeria Multimidia  Selecionada
	$GaleriaMidiaCod = $Form->getCampoRetorna("GaleriaMidiaCod");
	
	//Upload de Acordo Com o Tipo
	if($TipoArquivo == "F")
	{
		//Inicia Transaзгo		
		$Con->startTransaction();
		
		//Variaveis Para Autor
		$_POST['AutorCod']  = $_GET['AutorCod']; 
		$_POST['AutorNome'] = $_GET['AutorCod'];
		
		//Grava no Banco
		$ProjetoMA->cadastrar($Form,$GaleriaMidiaCod);
		
		//Recupera Id Gerado
		$GaleriaArquivoCod = $ProjetoMA->getGaleriaArquivoCod(); 
		
		//Definindo o nome dos arquivos
		$ArquivoGrande  = $_SESSION['DirBaseSite'].'arquivos/multimidia/'.$GaleriaMidiaCod.'/fotos/'.$GaleriaArquivoCod.'.'.$Extensao;
		$ArquivoPequeno = $_SESSION['DirBaseSite'].'arquivos/multimidia/'.$GaleriaMidiaCod.'/fotos/tb/'.$GaleriaArquivoCod.'.'.$Extensao;
		
		//Upload - Imagem Grande
		$Arquivos->trataImagem("FotosVideos", $ArquivoGrande, null, 650, null);

		//Upload - Imagem Pequena
		$Arquivos->trataImagem("FotosVideos", $ArquivoPequeno, null, 110, null);
		
		//Finaliza Transaзгo					
		$Con->stopTransaction();	
	}
	elseif($TipoArquivo == "V")
	{
		//Inicia Transaзгo		
		$Con->startTransaction();
		
		//Variaveis Para Autor
		$_POST['AutorCod']  = $_GET['AutorCod']; 
		$_POST['AutorNome'] = $_GET['AutorCod'];
		
		//Grava no Banco
		$ProjetoMA->cadastrar($Form,$GaleriaMidiaCod);
		
		//Recupera Id Gerado
		$GaleriaArquivoCod = $ProjetoMA->getGaleriaArquivoCod(); 		

		//Definindo o nome dos arquivos
		$ArquivoVideo  = $_SESSION['DirBaseSite'].'arquivos/multimidia/'.$GaleriaMidiaCod.'/videos/'.$GaleriaArquivoCod.'.'.$Extensao;
		
		//Upload
		$Arquivos->upload("FotosVideos",$ArquivoVideo,null,array());
		
		//Finaliza Transaзгo					
		$Con->stopTransaction();	
	}
	elseif($TipoArquivo == "A")
	{
		//Inicia Transaзгo		
		$Con->startTransaction();
		
		//Grava no Banco
		$ProjetoMA->cadastrar($Form,$GaleriaMidiaCod);
		
		//Recupera Id Gerado
		$GaleriaArquivoCod = $ProjetoMA->getGaleriaArquivoCod(); 		

		//Definindo o nome dos arquivos
		$ArquivoAudio  = $_SESSION['DirBaseSite'].'arquivos/multimidia/'.$GaleriaMidiaCod.'/audios/'.$GaleriaArquivoCod.'.'.$Extensao;
		
		//Upload
		$Arquivos->upload("FotosVideos",$ArquivoAudio,null,array());
		
		//Finaliza Transaзгo					
		$Con->stopTransaction();	
	}
	else 
	{
		throw new Exception("Tipo de Arquivo Invбlido!");
	}
}
catch (Exception $E)
{
	
	file_put_contents("debug.txt");
	
	header("HTTP/1.1 500");
}
?>