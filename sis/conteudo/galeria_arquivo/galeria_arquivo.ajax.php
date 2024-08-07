<?
//Starta Sessão
session_start();

///Content
header("Content-Type: text/html; charset=ISO-8859-1",true);

//Verifica se sessão ainda esta ativa
if(empty($_SESSION['UsuarioCod'])) exit("sessaoexpirada");

//Classe de configurações
include_once($_SESSION['DirBase'].'framework/config.conf.php'); ConfigSIS::Conf();

//Definindo Módulo/Pacote
define("MODULO","galeria_arquivo");
define("PACOTE","conteudo");

//Chamando Arquivos do Sistema
include_once($_SESSION['FMBase'].'acesso.class.php'); 	       
$Ac = new Acesso();

//Includes
include_once($_SESSION['DirBase'].PACOTE.'/'.MODULO.'/'.MODULO.'.class.php'); 

switch ($_GET['Op'])
{
	case "Fil": ##Filtro
		
		//Verificando permissões
		$Ac->setOpcao($_GET['Op']);
		$Ac->acessoModulo();
		
		include_once($_SESSION['DirBase'].PACOTE.'/'.MODULO.'/'.MODULO.'.form.php');
		
		$Form            = new GaleriaArquivoAForm();
		$GaleriaArquivoA = new GaleriaArquivoA();
		$FPHP            = new FuncoesPHP();
	
		try 
		{
			$Form->setEnv("true");
			$Form->setOp($_GET['Op']);
			$Form->getFormFiltro();
			
			echo($FPHP->formataErro($Form->getErro()).$GaleriaArquivoA->filtrar($Form));
		}
		catch (Exception $E)
		{
			echo($E->getMessage());
		}
	
	break;
	
	case "Vis" : ##Visualizar 

		//Verificando permissões
		$Ac->setOpcao($_GET['Op']);
		$Ac->acessoModulo();

		$GaleriaArquivoA = new GaleriaArquivoA();
			
		try 
		{
			echo($GaleriaArquivoA->visualizar());
		}
		catch (Exception $E)
		{
			echo($E->getMessage());
		}	
	
	break;
	
	case "Cad": ##Cadastrar 
	
		//Verificando permissões
		$Ac->setOpcao($_GET['Op']);
		$Ac->acessoModulo();
		
		include_once($_SESSION['DirBase'].PACOTE.'/'.MODULO.'/'.MODULO.'.form.php');
		
		$Form = new GaleriaArquivoAForm();
//		$Form->setDecodificacao(false);//Semente para requisições não ajax!
		$GaleriaArquivoA = new GaleriaArquivoA();
		
		try 
		{		
			
			$Form->setEnv($_GET['Env']);
			$Form->setOp($_GET['Op']);
			$Form->setNomeForm("FormManu");
			$Campos = $Form->getFormManu();		

			if($Form->getEnv() === true)
			{
				$Erro = $Form->getErro();
				
				if(empty($Erro))
				{
					$GaleriaArquivoA->cadastrar($Form);
					
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
	
		//Verificando permissões
		$Ac->setOpcao($_GET['Op']);
		$Ac->acessoModulo();
		
		include_once($_SESSION['DirBase'].PACOTE.'/'.MODULO.'/'.MODULO.'.form.php');
		
		$Form = new GaleriaArquivoAForm();
//		$Form->setDecodificacao(false);//Semente para requisições não ajax!
		$GaleriaArquivoA = new GaleriaArquivoA();
		
		try 
		{		
			
			$Form->setEnv($_GET['Env']);
			$Form->setOp($_GET['Op']);
			
			if($Form->getEnv() === true)
			{
				$Form->getFormManu();		
				
				$Erro = $Form->getErro();
				
				if(empty($Erro))
				{
					$GaleriaArquivoA->alterar($Form);
					
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
					$GaleriaArquivoA->getDados($Id,"POST");
					$Campos = $Form->getFormManu();
					$Op = $Form->getOp();
					
					include($_SESSION['DirBase'].PACOTE.'/'.MODULO.'/'.MODULO.'.tpl.php');
					echo $Form->geraFuncoes(), $Form->geraMascaras(), $Form->geraOnLoad(), 
						 $Form->geraValidacaoJS("validaForm".$Id,"FormManu".$Id);
				}
			}
		}
		catch (Exception $E)
		{
			echo($E->getMessage());
		}	
	
	break;	
	
	case "Del": ##Remover

		//Verificando permissões
		$Ac->setOpcao($_GET['Op']);
		$Ac->acessoModulo();
				
		//Instancia Classe
		$GaleriaArquivoA = new GaleriaArquivoA();
		
		//Executa metodo de remoção	
			echo $GaleriaArquivoA->remover();
	
	break;
}
?>
