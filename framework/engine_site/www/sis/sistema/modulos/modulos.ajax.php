<?
//Starta Sessão
session_start();

// HTTP/1.1 - Elimina Cache
@header("Cache-Control: no-store, no-cache, must-revalidate");
@header("Cache-Control: post-check=0, pre-check=0", false);
@header("Content-Type: text/html; charset=ISO-8859-1",true);

//Verifica se sessão ainda esta ativa
if(empty($_SESSION['UsuarioCod'])) exit("sessaoexpirada");

//Busca Dados de Configuração
include_once('../../framework/config.conf.php'); ConfigSIS::Conf();

//Definindo Módulo/Pacote
define("MODULO","modulos");	
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
		//$Ac->acessoModulo();
	
		include_once($_SESSION['DirBase'].PACOTE.'/'.MODULO.'/'.MODULO.'.form.php');
		
		$Form = new ModulosForm();
		$Mod  = new Modulos();
		$FPHP = new FuncoesPHP();
	
		try 
		{
			
			$Form->setEnv("true");
			$Form->setOp("Fil");
			$Form->getFormFiltro();
			
			echo($FPHP->formataErro($Form->getErro()).$Mod->filtrar($Form));
		}
		catch (Exception $E)
		{
			echo($E->getMessage());
		}
	
	break;
	
	case "Vis" : ##Visualizar 

		//Verificando permissões
		$Ac->setOpcao($_GET['Op']);
		//$Ac->acessoModulo();		
	
		$Mod  = new Modulos();
			
		try 
		{
			echo($Mod->visualizar());
		}
		catch (Exception $E)
		{
			echo($E->getMessage());
		}	
	
	break;
	
	case "Cad": ##Cadastrar 
	
		//Verificando permissões
		$Ac->setOpcao($_GET['Op']);
		//$Ac->acessoModulo();
	
		include_once($_SESSION['DirBase'].PACOTE.'/'.MODULO.'/'.MODULO.'.form.php');
		
		$Form = new ModulosForm();
		$Mod  = new Modulos();
		$FPHP = new FuncoesPHP();
		
		try 
		{		
			
			$Form->setEnv($_GET['Env']);
			$Form->setOp("Cad");
			$Form->setNomeForm("FormManu");
			
			$Campos = $Form->getFormManu();	
			$Form->getFormReferencia("A");
			
			//Opçoes
			if(is_array($_POST['OpcoesModulo']))
			{
				foreach($_POST['OpcoesModulo'] as $Cod)
				$Form->getFormOpcao($Cod);
			}
			
			if($Form->getEnv() === true)
			{
				$Erro = $Form->getErro();
				
				if(empty($Erro))
				{
					$Mod->cadastrar($Form);
					
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
				include_once($_SESSION['DirBase'].PACOTE.'/'.MODULO.'/modulos_fim.tpl.php');			
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
		//$Ac->acessoModulo();		
	
		include_once($_SESSION['DirBase'].PACOTE.'/'.MODULO.'/'.MODULO.'.form.php');
		
		$Form = new ModulosForm();
		$Mod  = new Modulos();
		$FPHP = new FuncoesPHP();
				
		try 
		{		
			
			$Form->setEnv($_GET['Env']);
			$Form->setOp("Alt");
									
			if($Form->getEnv() === true)
			{
				//Opçoes
				if(is_array($_POST['OpcoesModulo']))
				{
					foreach($_POST['OpcoesModulo'] as $Cod)
					$Form->getFormOpcao($Cod);
				}
	
				$Form->getFormManu();		
				
				$Erro = $Form->getErro();
				
				if(empty($Erro))
				{
					$Mod->alterar($Form);
					
					echo("true");
				}
				else 
				{
					echo $FPHP->formataErro($Erro);
				}				
			}
			else 
			{
				throw new Exception("Puts!!! Não é mais Possível alterar módulos, use o banco ow delete e crie novamente!!");
				if(!is_array($_POST['SisReg'])) throw new Exception("Nenhum registro selecionado!");
				
				foreach ($_POST['SisReg'] as $Id)
				{						
					$Form->setNomeForm("FormManu".$Id);
					$Mod->getDados($Id,"POST");				
					$Op = $Form->getOp();
					
					$Campos = $Form->getFormManu();
					$Form->getFormReferencia("A");
										
					include($_SESSION['DirBase'].PACOTE.'/'.MODULO.'/'.MODULO.'.tpl.php');
					
					//Recuperado Referencia 
					$Ref = $_POST['Referencia'];

					
					//Instancias
					
					$Con = Conexao::conectar();
					$RS  = $Con->executar($Mod->getDadosOpcoesSql($Id));
					$NL  = $Con->nLinhas($RS); 
					
					if($NL > 0)
					{
						while($Dados = @mysqli_fetch_array($RS))
						{
							unset($Campos);
							$Cod = $Dados['OpcoesModuloCod'];
							$Mod->getDadosOpcoes($Cod);	
							$Campos = $Form->getFormOpcao($Cod);
							include($_SESSION['DirBase'].PACOTE.'/'.MODULO.'/modulo_opcoes.tpl.php');
						}
					}
										
					include($_SESSION['DirBase'].PACOTE.'/'.MODULO.'/modulos_fim.tpl.php');	
					$Form->setOnLoad("FormManu","chamaReferencia('FormManu".$Id."','mReferentes', $Ref);");				
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
	
	case "Mod" : ##Opcoes Módulo 
		
		include_once($_SESSION['DirBase'].PACOTE.'/'.MODULO.'/'.MODULO.'.form.php');
		$Form = new ModulosForm();
		$Op = $Form->getOp();
		
		try 
		{		
			
			$Cod = $_POST['Id'];
			$Campos = $Form->getFormOpcao($Cod);
			include($_SESSION['DirBase'].PACOTE.'/'.MODULO.'/modulo_opcoes.tpl.php');
		}
		catch (Exception $E)
		{
			echo($E->getMessage());
		}	
	
	break;

	
	case "GMod" : ##Módulos Referentes ao Grupo
		
		include_once($_SESSION['DirBase'].PACOTE.'/'.MODULO.'/'.MODULO.'.form.php');
		$Form = new ModulosForm();
		$Op = $Form->getOp();
		
		try 
		{		
			if(empty($_POST['GrupoCod']))
			{
				echo $Form->getFormReferencia("I");
			}
			else 
			{
				echo $Form->getFormReferencia("A");
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
		//$Ac->acessoModulo();	
	
		$Mod  = new Modulos();
			
		try 
		{
			$Mod->remover();
			echo("true");
		}
		catch (Exception $E)
		{
			echo($E->getMessage());
		}	
	
	break;
}
?>
