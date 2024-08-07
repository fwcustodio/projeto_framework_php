<?
//Starta Sess�o
session_start();

//Content
@header("Content-Type: text/html; charset=ISO-8859-1",true);

//Busca Dados de Configura��o
include_once('../../framework/config.conf.php'); ConfigSIS::Conf();

//Definindo M�dulo/Pacote
define("MODULO","contato_departamento");
define("PACOTE","interatividade");

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
		
		$Form = new DepartamentoForm();
		$Dep = new Departamento();
		$FPHP = new FuncoesPHP();
	
		try 
		{
			
			$Form->setEnv("true");
			$Form->setOp($_GET['Op']);
			$Form->getFormFiltro();
			
			echo($FPHP->formataErro($Form->getErro()).$Dep->filtrar($Form));
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

		$Dep = new Departamento();
			
		try 
		{
			echo($Dep->visualizar());
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
		
		$Form = new DepartamentoForm();
		$Dep = new Departamento();
		
		try 
		{		
			
			$Form->setEnv($_GET['Env']);
			$Form->setOp($_GET['Op']);
			$Form->setNomeForm("FormManu");
			$Campos = $Form->getFormManu();		

			if($Form->getEnv() === true)
			{
				$ArrayDados = $_POST['ContadorDados'];

				foreach($ArrayDados as $Cod) 
					$Form->getFormDados($Cod);

				$Erro = $Form->getErro();
				
				if(empty($Erro))
				{
					$Dep->cadastrar($Form);
					
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
				$Form->setOnLoad("CarregarCampo","addDados('');");
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
		
		$Form = new DepartamentoForm();
		$Dep = new Departamento();
		
		try 
		{		
			
			$Form->setEnv($_GET['Env']);
			$Form->setOp($_GET['Op']);
			
			if($Form->getEnv() === true)
			{
				$ArrayDados = $_POST['ContadorDados'];

				foreach($ArrayDados as $Cod) 
					$Form->getFormDados($Cod);
				
				$Form->getFormManu();		
				
				$Erro = $Form->getErro();
				
				if(empty($Erro))
				{
					$Dep->alterar($Form);
					
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
					$Dep->getDados($Id,"POST");
					$Campos = $Form->getFormManu();
					$Op = $Form->getOp();

					$ConteudoUsuarios  = $Dep->getDadosDados($Id,$Form);
					
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

		//Verificando permiss�es
		$Ac->setOpcao($_GET['Op']);
		$Ac->acessoModulo();
				
		//Instancia Classe
		$Dep = new Departamento();
		
		//Executa metodo de remo��o	
			echo $Dep->remover();
	
	break;
	

	case "AddDados": ##Adicionando Dados

		include_once($_SESSION['DirBase'].PACOTE.'/'.MODULO.'/'.MODULO.'.form.php');
		$Form = new DepartamentoForm();
		
		$Cod  = $_POST['RandJS'];
		
		$Campos = $Form->getFormDados($Cod);

		echo "<div id='campoUsuario' class='dadosLinha".$Cod."'>
					".$Campos['ContadorDados'].$Campos['UsuarioCod']."
					&nbsp;&nbsp;<img src=\"".$_SESSION['UrlBase']."figuras/del_2.gif\" border=\"0\" onClick=\"removeItenDado(this, '".$_POST['Id']."')\" style=\"cursor:pointer\">
			
			  </div>";

	break;
}
?>