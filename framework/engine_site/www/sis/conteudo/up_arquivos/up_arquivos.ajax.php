<?

// retorna o tamanho m�ximo do post em bytes
// adaptado do exemplo do manual do PHP. Ver "ini_get"
function get_post_max_size($em_MB = false) {
	$val = ini_get('post_max_size');
	$val = trim($val);
	$last = strtolower($val{strlen($val)-1});
	switch($last) {
		// The 'G' modifier is available since PHP 5.1.0
		case 'g':
			$val *= 1024;
		case 'm':
			$val *= 1024;
		case 'k':
			$val *= 1024;
	}
	if ($em_MB) {
		return sprintf("%.1f", ($val / 1024) / 1024);
	}
	return $val;
}


//Starta Sess�o
session_start();

//Content
@header("Content-Type: text/html; charset=ISO-8859-1",true);

//Busca Dados de Configura��o
include_once('../../framework/config.conf.php'); ConfigSIS::Conf();

//Definindo M�dulo/Pacote
define("MODULO","up_arquivos");
define("PACOTE","conteudo");

//Chamando Arquivos do Sistema
include_once($_SESSION['FMBase'].'acesso.class.php'); 	       
$Ac = new Acesso();

//Includes
include_once($_SESSION['DirBase'].PACOTE.'/'.MODULO.'/'.MODULO.'.class.php'); 

switch ($_GET['Op'])
{
	case "Fil": ##Filtro
		
		//Verificando permiss�es
		$Ac->setOpcao($_GET['Op']);
		$Ac->acessoModulo();
		
		include_once($_SESSION['DirBase'].PACOTE.'/'.MODULO.'/'.MODULO.'.form.php');
		
		$Form = new UpArquivoForm();
		$UpArquivo = new UpArquivo();
		$FPHP = new FuncoesPHP();
	
		try 
		{
			
			$Form->setEnv("true");
			$Form->setOp($_GET['Op']);
			$Form->getFormFiltro();
			
			//Tipo de Filtro
			$Pop  = $_GET['Pop'];

			echo (empty($Pop)) 
			? ($FPHP->formataErro($Form->getErro()).$UpArquivo->filtrar($Form)) 
			: ($FPHP->formataErro($Form->getErro()).$UpArquivo->filtrarPop($Form));
		}
		catch (Exception $E)
		{
			echo($E->getMessage());
		}
	
	break;
	
	case "Vis" : ##Visualizar 

		//Verificando permiss�es
		$Ac->setOpcao($_GET['Op']);
		$Ac->acessoModulo();

		$UpArquivo = new UpArquivo();
			
		try 
		{
			echo($UpArquivo->visualizar());
		}
		catch (Exception $E)
		{
			echo($E->getMessage());
		}	
	
	break;
	
	case "Cad": ##Cadastrar 
	
		//Verificando permiss�es
		$Ac->setOpcao($_GET['Op']);
		$Ac->acessoModulo();
		
		include_once($_SESSION['DirBase'].PACOTE.'/'.MODULO.'/'.MODULO.'.form.php');
		
		$Form = new UpArquivoForm();
		$Form->setDecodificacao(false);//Semente para requisi��es n�o ajax!
		$UpArquivo = new UpArquivo();
		
		try 
		{		
			$Form->setEnv($_GET['Env']);
			$Form->setOp($_GET['Op']);
			$Form->setNomeForm("FormManu");
			$Campos = $Form->getFormManu();		
			
			if($Form->getEnv() === true)
			{
				if (!isset($_POST['Id'])) {
					$max_post = get_post_max_size(true);
					throw new Exception('Erro: a soma do tamanho dos arquivos enviados passou '.$max_post.' MB');
				}
				
				$Erro = $Form->getErro();
				
				if(empty($Erro))
				{
					$UpArquivo->cadastrar($Form);
					
					echo("true");
				}
				else 
				{
					$FPHP = new FuncoesPHP();
					echo $FPHP->formataErro($Erro);
				}
			}
			else 
			{
				
				$Op = $Form->getOp();
				include_once($_SESSION['DirBase'].PACOTE.'/'.MODULO.'/'.MODULO.'.tpl.php');
				echo $Form->geraFuncoes(), $Form->geraMascaras(), $Form->geraOnLoad(), 
					 $Form->geraValidacaoJS("validaForm".$Id,"FormManu".$Id);
			}
		}
		catch (Exception $E)
		{
			echo($E->getMessage());
		}	
	
	break;

	case "Alt": ##Alterar
	
		//Verificando permiss�es
		$Ac->setOpcao($_GET['Op']);
		$Ac->acessoModulo();
		
		include_once($_SESSION['DirBase'].PACOTE.'/'.MODULO.'/'.MODULO.'.form.php');
		
		$Form = new UpArquivoForm();
//		$Form->setDecodificacao(false);//Semente para requisi��es n�o ajax!
		$UpArquivo = new UpArquivo();
		
		try 
		{		
			
			$Form->setEnv($_GET['Env']);
			$Form->setOp($_GET['Op']);
			
			if($Form->getEnv() === true)
			{
				if (!isset($_POST['Id'])) {
					$max_post = get_post_max_size(true);
					throw new Exception('Erro: a soma do tamanho dos arquivos enviados passou '.$max_post.' MB');
				}

				$Form->getFormManu();		
				
				$Erro = $Form->getErro();
				
				if(empty($Erro))
				{
					$UpArquivo->alterar($Form);
					
					echo("true");
				}
				else 
				{
					$FPHP = new FuncoesPHP();
					echo $FPHP->formataErro($Erro);
				}
			}			
			else 
			{
				if(!is_array($_POST['SisReg'])) throw new Exception("Nenhum registro selecionado!");
				
				foreach ($_POST['SisReg'] as $Id)
				{		
					$Form->setNomeForm("FormManu".$Id);
					$UpArquivo->getDados($Id,"POST");
					$Campos = $Form->getFormManu();
					$Op = $Form->getOp();
					
					include($_SESSION['DirBase'].PACOTE.'/'.MODULO.'/'.MODULO.'.tpl.php');
					echo $Form->geraFuncoes(), $Form->geraMascaras(), $Form->geraOnLoad(), 
						 $Form->geraValidacaoJS("validaForm".$Id,"FormManu".$Id);
				}
				
				echo '<br style="clear:both; ">';
			}
		}
		catch (Exception $E)
		{
			echo($E->getMessage());
		}	
	
	break;	
	
	case "Del": ##Remover
         
		//Verificando permiss�es
		$Ac->setOpcao($_GET['Op']);
		$Ac->acessoModulo();
				
		//Instancia Classe
		$UpArquivo = new UpArquivo();
               
		//Executa metodo de remo��o	
		echo $UpArquivo->remover();
	
	break;
	
	case "GetArquivo": ##Dados do Arquivo
		
		try
		{		
			//Instancia Classe
			$UpArquivo = new UpArquivo();
			
			//Executa metodo de remo��o	
			echo $UpArquivo->getNomeArquivo($_POST['ArquivoCod']);
		}
		catch(Exception $E)
		{
			echo "";
		}
		
	break;		
}
?>