<?
//Starta Sess�o
session_start();

//Modifica Para Limite M�ximo de Execu��o Permitido no Servidor
set_time_limit(0);

//Busca Dados de Configura��o
include_once('../../framework/config.conf.php'); ConfigSIS::Conf();

//Definindo M�dulo/Pacote
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

//Recupera Parametro de Identifica��o de Projeto Multimidia
$Form->setEnv("true");
$Form->setOp("Cad");
$Form->setNomeForm("FormManu");
$Form->getFormManu();

//Intercepta Erros
$Erro = $Form->getErro();

//Intercepta Erro
try 
{
	//Inicia Conex�o
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
	
	//Recupera o C�digo da Galeria Multimidia  Selecionada
	$GaleriaMidiaCod = $Form->getCampoRetorna("GaleriaMidiaCod");
	
	//Upload de Acordo Com o Tipo
	if($TipoArquivo == "F")
	{
		//Inicia Transa��o		
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
		
		//Finaliza Transa��o					
		$Con->stopTransaction();	
	}
	elseif($TipoArquivo == "V")
	{
		//Inicia Transa��o		
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
		
		//Finaliza Transa��o					
		$Con->stopTransaction();	
	}
	elseif($TipoArquivo == "A")
	{
		//Inicia Transa��o		
		$Con->startTransaction();
		
		//Grava no Banco
		$ProjetoMA->cadastrar($Form,$GaleriaMidiaCod);
		
		//Recupera Id Gerado
		$GaleriaArquivoCod = $ProjetoMA->getGaleriaArquivoCod(); 		

		//Definindo o nome dos arquivos
		$ArquivoAudio  = $_SESSION['DirBaseSite'].'arquivos/multimidia/'.$GaleriaMidiaCod.'/audios/'.$GaleriaArquivoCod.'.'.$Extensao;
		
		//Upload
		$Arquivos->upload("FotosVideos",$ArquivoAudio,null,array());
		
		//Finaliza Transa��o					
		$Con->stopTransaction();	
	}
	else 
	{
		throw new Exception("Tipo de Arquivo Inv�lido!");
	}
}
catch (Exception $E)
{
	
	file_put_contents("debug.txt");
	
	header("HTTP/1.1 500");
}
?>