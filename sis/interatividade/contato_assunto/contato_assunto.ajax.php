<?
//Starta Sessуo
session_start();

//Content
@header("Content-Type: text/html; charset=ISO-8859-1",true);

//Busca Dados de Configuraчуo
include_once('../../framework/config.conf.php'); ConfigSIS::Conf();

//Definindo Mѓdulo/Pacote
define("MODULO","contato_assunto");
define("PACOTE","interatividade");

//Chamando Arquivos do Sistema
include_once($_SESSION['FMBase'].'acesso.class.php'); 	       
$Ac = new Acesso();

//Includes
include_once($_SESSION['DirBase'].PACOTE.'/'.MODULO.'/'.MODULO.'.class.php'); 

switch ($_GET['Op'])
{
	case "Fil": ##Filtro
		
		//Verificando permissѕes
		$Ac->setOpcao($_GET['Op']);
		$Ac->acessoModulo();
		
		include_once($_SESSION['DirBase'].PACOTE.'/'.MODULO.'/'.MODULO.'.form.php');
		
		$Form = new AssuntoForm();
		$Ass = new Assunto();
		$FPHP = new FuncoesPHP();
	
		try 
		{
			
			$Form->setEnv("true");
			$Form->setOp($_GET['Op']);
			$Form->getFormFiltro();
			
			echo($FPHP->formataErro($Form->getErro()).$Ass->filtrar($Form));
		}
		catch (Exception $E)
		{
			echo($E->getMessage());
		}
	
	break;
	
	case "Vis" : ##Visualizar 

		//Verificando permissѕes
		$Ac->setOpcao($_GET['Op']);
		$Ac->acessoModulo();

		$Ass = new Assunto();
			
		try 
		{
			echo($Ass->visualizar());
		}
		catch (Exception $E)
		{
			echo($E->getMessage());
		}	
	
	break;
	
	case "Cad": ##Cadastrar 
	
		//Verificando permissѕes
		$Ac->setOpcao($_GET['Op']);
		$Ac->acessoModulo();
		
		include_once($_SESSION['DirBase'].PACOTE.'/'.MODULO.'/'.MODULO.'.form.php');
		
		$Form = new AssuntoForm();
		$Ass = new Assunto();
		
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
					$Ass->cadastrar($Form);
					
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
	
		//Verificando permissѕes
		$Ac->setOpcao($_GET['Op']);
		$Ac->acessoModulo();
		
		include_once($_SESSION['DirBase'].PACOTE.'/'.MODULO.'/'.MODULO.'.form.php');
		
		$Form = new AssuntoForm();
		$Ass = new Assunto();
		
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
					$Ass->alterar($Form);
					
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
					$Ass->getDados($Id,"POST");
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

		//Verificando permissѕes
		$Ac->setOpcao($_GET['Op']);
		$Ac->acessoModulo();
				
		//Instancia Classe
		$Ass = new Assunto();
		
		//Executa metodo de remoчуo	
			echo $Ass->remover();
	
	break;
}
?>