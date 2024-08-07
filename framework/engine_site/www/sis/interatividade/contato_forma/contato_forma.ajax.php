<?
//Starta Sessão
session_start();

//Content
@header("Content-Type: text/html; charset=ISO-8859-1",true);

//Busca Dados de Configuração
include_once('../../framework/config.conf.php'); ConfigSIS::Conf();

//Definindo Módulo/Pacote
define("MODULO","contato_forma");
define("PACOTE","interatividade");

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
		
		$Form = new ContatoFormaForm();
		$Prof = new ContatoForma();
		$FPHP = new FuncoesPHP();
	
		try 
		{
			
			$Form->setEnv("true");
			$Form->setOp($_GET['Op']);
			$Form->getFormFiltro();
			
			echo($FPHP->formataErro($Form->getErro()).$Prof->filtrar($Form));
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

		$Prof = new ContatoForma();
			
		try 
		{
			echo($Prof->visualizar());
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
		
		$Form = new ContatoFormaForm();
		$Prof = new ContatoForma();
		
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
					$Prof->cadastrar($Form);
					
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
				echo $Form->geraFuncoes(), $Form->geraOnLoad(), 
					 $Form->geraValidacaoJS("validaForm".$Id,"FormManu".$Id);
					 
				/*******************************************JAVASCRIPT********************************************/
				$Form->setOnLoad("End", "addContato(''); addEndereco(''); ");
				echo $Form->geraOnLoad(),$Form->geraMascaras();
				/*******************************************JAVASCRIPT********************************************/

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
		include_once($_SESSION['DirBase'].'interatividade/contato/contato.class.php');
		include_once($_SESSION['DirBase'].'interatividade/endereco/endereco.class.php');

		
		$Form = new ContatoFormaForm();
		$Prof = new ContatoForma();
		
		//Classe de Contatos
		$Conta = new Contato();
		
		//Classe Endreço
		$End   = new Endereco();

		
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
					$Prof->alterar($Form);
					
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
					$Prof->getDados($Id,"POST");
					$Campos = $Form->getFormManu();
					$Op = $Form->getOp();
					
					/************************************************Contato*************************************************/			
					$ConteudoContato = $Conta->getContato($_POST['ContatoCod'], $Id, $Form);
					
					/************************************************Contato*************************************************/

																 /*------------*/				
										
					/************************************************ENDEREÇO***********************************************/
					
					$ConteudoEnd = $End->getEndereco($_POST['EnderecoCod'],$Id, $Form);
						
					/**********************************************ENDEREÇO************************************************/

					
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
		$Prof = new ContatoForma();
		
		//Executa metodo de remoção	
			echo $Prof->remover();
	
	break;
	
	case "End": ##Endereço
	
		//Verificando permiss?
		$Ac->setOpcao('Fil');
		$Ac->acessoModulo();

		include_once($_SESSION['DirBase'].'interatividade/contato_forma/contato_forma.form.php');
		
		$Form = new ContatoFormaForm();
		
		try 
		{		
			//Nome do Formulário
			$IdForm = (empty($_POST['IdForm'])) ? "" : $_POST['IdForm'];
			
			$Cont = (empty($_POST['Cont'])) ? mt_rand() : $_POST['Cont'];
			$Form->setNomeForm("FormManu".$IdForm);
			$CamposEnd = $Form->getFormEndereco($Cont);	

			//$Form->setOnLoad("LoadClue", "$('#infoHelp".$Cont."').cluetip({cluetipClass: 'default', splitTitle: '|', showTitle: false, positionBy: 'bottomTop'});");


			include_once($_SESSION['DirBase'].'interatividade/endereco/endereco.tpl.php');
			echo $Form->geraFuncoes(), $Form->geraMascaras(), $Form->geraOnLoad();
		}
		catch (Exception $E)
		{
			echo($E->getMessage());
		}	
	
	break;		
	
	case "Contato": ##Contato
	
		//Verificando permiss?
		$Ac->setOpcao('Fil');
		$Ac->acessoModulo();

		include_once($_SESSION['DirBase'].'interatividade/contato_forma/contato_forma.form.php');
		include_once($_SESSION['DirBase'].'interatividade/contato/contato.class.php');

		
		$Form = new ContatoFormaForm();
		$Conta = new Contato(); 
		
		try 
		{		
			//Nome do Formulário
			$IdForm = (empty($_POST['IdForm'])) ? "" : $_POST['IdForm'];
			
			$Cont = (empty($_POST['Cont'])) ? mt_rand() : $_POST['Cont'];
			$Form->setNomeForm("FormManu".$IdForm);
			$CamposCont = $Form->getFormContato($Cont);
			
			//Conteudo
			$ConteudoTipoContato  = $Conta->getConteudoTipoContato($CamposCont,$IdForm,$Cont);		
			$ConteudoTipoContato .= $ConteudoTipoContato;
			
			echo $Conta->getConteudoContato($CamposCont,$IdForm,$Cont,$ConteudoTipoContato);
		}
		catch (Exception $E)
		{
			echo($E->getMessage());
		}	
	
	break;
	
	case "TipoContato": ##TipoContato
	
		//Verificando permiss?
		$Ac->setOpcao('Fil');
		$Ac->acessoModulo();

		include_once($_SESSION['DirBase'].'interatividade/contato_forma/contato_forma.form.php');
		include_once($_SESSION['DirBase'].'interatividade/contato/contato.class.php');


		$Form = new ContatoFormaForm();
		$Conta = new Contato(); 		
		
		try 
		{		
			//Nome do Formulário
			$IdForm = (empty($_POST['IdForm'])) ? "" : $_POST['IdForm'];
			$Cont   = (empty($_POST['Cont']))   ? "" : $_POST['Cont'];
					
			$Form->setNomeForm("FormManu".$IdForm);
			$CamposCont = $Form->getFormTipoContato($Cont);		

			$Html = $Conta->getConteudoTipoContato($CamposCont, $IdForm, $Cont);
			
			echo $Html;
		}
		catch (Exception $E)
		{
			echo($E->getMessage());
		}	
	
	break;	
}
?>