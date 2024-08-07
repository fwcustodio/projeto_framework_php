<?
//Starta Sessão
session_start();

// HTTP/1.1 - Elimina Cache
@header("Cache-Control: no-store, no-cache, must-revalidate");
@header("Cache-Control: post-check=0, pre-check=0", false);
@header("Content-Type: text/html; charset=ISO-8859-1",true);

//Verifica se sessão ainda esta ativa
if(empty($_SESSION['UsuarioCod'])) exit("sessaoexpirada");

//Classe de configurações
include_once($_SESSION['DirBase'].'framework/config.conf.php'); ConfigSIS::Conf();

//Definindo Módulo/Pacote
define("MODULO","grupomodulo");	
define("PACOTE","sistema");

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
		
		$Form = new GrupoModuloForm();
		$GM   = new GrupoModulo();
		$FPHP = new FuncoesPHP();
	
		try 
		{
			
			$Form->setEnv("true");
			$Form->setOp($_GET['Op']);
			$Form->getFormFiltro();
			
			echo($FPHP->formataErro($Form->getErro()).$GM ->filtrar($Form));
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
		
		$GM   = new GrupoModulo();
			
		try 
		{
			echo($GM ->visualizar());
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
		
		$Form = new GrupoModuloForm();
		$GM   = new GrupoModulo();
		$FPHP = new FuncoesPHP();
		
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
					$GM ->cadastrar($Form);
					
					echo("true");
				}
				else 
				{
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
		
		$Form = new GrupoModuloForm();
		$GM   = new GrupoModulo();
		$FPHP = new FuncoesPHP();
		
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
					$GM->alterar($Form);
					
					echo("true");
				}
				else 
				{
					echo $FPHP->formataErro($Erro);
				}
			}			
			else 
			{
				if(!is_array($_POST['SisReg'])) throw new Exception("Nenhum registro selecionado!");
				
				foreach ($_POST['SisReg'] as $Id)
				{		
					$Form->setNomeForm("FormManu".$Id);
					$GM->getDados($Id,"POST");				
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
		
		$GM   = new GrupoModulo();
			
		try 
		{
			$GM->remover();
			echo("true");
		}
		catch (Exception $E)
		{
			echo($E->getMessage());
		}	
	
	break;
	
	case "Novo": ##Novo

		//Verificando permiss?
		$Ac->setOpcao('Vis');
		$Ac->acessoModulo();

		$MO = new GrupoModulo();
			
		try 
		{
			$Dados = $MO->dadosUltimoModulo();
			echo '<option value="'.$Dados['Cod'].'" selected="selected">'.$Dados['Nome'].'</option>';
		}
		catch (Exception $E)
		{
			echo($E->getMessage());
		}	
	
	break;
}
?>
