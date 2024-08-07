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
define("MODULO","servicos_produtos");
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
		
		$Form = new ServicoForm();
		$Serv = new Servico();
		$FPHP = new FuncoesPHP();
	
		try 
		{
			
			$Form->setEnv("true");
			$Form->setOp($_GET['Op']);
			$Form->getFormFiltro();
			
			echo($FPHP->formataErro($Form->getErro()).$Serv->filtrar($Form));
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

		$Serv = new Servico();
			
		try 
		{
			echo($Serv->visualizar());
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
		
		$Form = new ServicoForm();
		$Form->setDecodificacao(false);//Semente para requisições não ajax!
		$Serv = new Servico();
		
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
					$Serv->cadastrar($Form);
					
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
		
		$Form = new ServicoForm();
		$Form->setDecodificacao(false);//Semente para requisições não ajax!
		$Serv = new Servico();
		
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
					$Serv->alterar($Form);
					
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
				
				//Midia
				include_once($_SESSION['DirBase'].'conteudo/galeria_midia/galeria_midia.class.php');
				include_once($_SESSION['DirBase'].'conteudo/up_arquivos/up_arquivos.class.php');
				
				//Recupera Conteudos Externos
				$GaleriaMidia = new GaleriaMidia();
				$UpArquivo    = new UpArquivo();
				
				foreach ($_POST['SisReg'] as $Id)
				{		
					$Form->setNomeForm("FormManu".$Id);
					$Serv->getDados($Id,"POST");
					$Campos = $Form->getFormManu();
					$Op = $Form->getOp();
					
					$ConteudoArquivo      = $UpArquivo->getListaArquivoServicos($Id);
					$ConteudoGaleriaMidia = $GaleriaMidia->getListaGaleriaMidiaServico($Id);
					
					$BlockId = $Serv->bloqueiaId($Id);
					$Form->setOnLoad("Block",'$("#FormManu'.$Id.' #linkConteudo").hide(); $("#FormManu'.$Id.' #trTipoInformacao").hide();');
					
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
		$Serv = new Servico();
		
		//Executa metodo de remoção	
			echo $Serv->remover();
	
	break;
	
	case "TextIntro": ##Remover

		//Verificando permissões
		$Ac->setOpcao($_GET['Op']);
		$Ac->acessoModulo();

		include_once($_SESSION['DirBase'].PACOTE.'/'.MODULO.'/'.MODULO.'.form.php');
		
		$Form = new ServicoForm();
		$Form->setDecodificacao(false);//Semente para requisições não ajax!
		$Serv = new Servico();

		try 
		{		
			$Form->setEnv($_GET['Env']);
			$Form->setOp($_GET['Op']);
			$Form->setNomeForm("FormManuIntro");

			if($Form->getEnv() === true)
			{
				$Erro = $Form->getErro();
				
				if(empty($Erro))
				{
					$Form->getFormManuIntro();	

					$Serv->alterarTextoIntro($Form);

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
				$Op 	= $Form->getOp();
				
				$_POST['TextoIntroducao'] = $Serv->getDadosTextoIntroducao();
				$Campos = $Form->getFormManuIntro();
				
				include($_SESSION['DirBase'].PACOTE.'/'.MODULO.'/'.MODULO.'.tpl.intro.php');

				echo $Form->geraFuncoes(), $Form->geraMascaras(), $Form->geraOnLoad();
			}
		}
		catch (Exception $E)
		{
			echo($E->getMessage());
		}	
	
	break;
}
?>
