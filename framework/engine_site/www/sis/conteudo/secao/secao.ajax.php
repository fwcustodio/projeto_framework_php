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
define("MODULO","secao");
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
		
		$Form  = new SecaoForm();
		$Secao = new Secao();
		$FPHP  = new FuncoesPHP();
	
		try 
		{
			$Form->setEnv("true");
			$Form->setOp($_GET['Op']);
			$Form->getFormFiltro();
			
			echo($FPHP->formataErro($Form->getErro()).$Secao->filtrar($Form));
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

		$Secao = new Secao();
			
		try 
		{
			echo($Secao->visualizar());
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
		
		$Form = new SecaoForm();
		$Secao = new Secao();
		
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
					$Secao->cadastrar($Form);
					
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
		
		$Form  = new SecaoForm();
		$Secao = new Secao();
		
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
					$Secao->alterar($Form);
					
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
				
				include_once($_SESSION['DirBase'].'conteudo/up_arquivos/up_arquivos.class.php');
				include_once($_SESSION['DirBase'].'conteudo/galeria_midia/galeria_midia.class.php');
//				include_once($_SESSION['DirBase'].'interatividade/enquete/enquete.class.php');
				
				//Recupera Conteudos Externos
				$UpArquivo    = new UpArquivo();
				$GaleriaMidia = new GaleriaMidia();
//				$Enquete      = new Enquete();
								
				foreach ($_POST['SisReg'] as $Id)
				{		
					$Form->setNomeForm("FormManu".$Id);
					$Secao->getDados($Id,"POST");
					$Campos = $Form->getFormManu();
										
					$CampoSecaoPai = $Form->getFormSecaoPai($_POST['SecaoGrupoCod'], true);
					
					$Campos['SecaoPai'] = $CampoSecaoPai['SecaoPai'];
										
					//Definindo Tipo
					$_POST['Tipo'] = ($_POST['Link'] <> '') ? 'L' : 'C'; 
					
					//Load Para Tipo
					$Form->setOnLoad('verificaTipo','verificaTipo("'.$Id.'");');
					
					$ConteudoArquivo      = $UpArquivo->getListaArquivoSecao($Id);
					$ConteudoGaleriaMidia = $GaleriaMidia->getListaGaleriaMidiaSecao($Id);
//					$ConteudoEnquete      = $Enquete->getListaEnquetesSecao($Id);
					
					$BlockId = $Secao->bloqueiaId($Id);
					
					if($BlockId) $Form->setOnLoad("Block",'$("#FormManu'.$Id.' #linkConteudo").hide(); $("#FormManu'.$Id.' #trTipoInformacao").hide();');
					
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
		$Secao = new Secao();
		
		//Executa metodo de remoção	
		echo $Secao->remover();
	
	break;
	
	case "SecaoPai": ##Seção Pai

		include_once($_SESSION['DirBase'].PACOTE.'/'.MODULO.'/'.MODULO.'.form.php');
		$Form = new SecaoForm();
		
		$SecaoGrupoCod = $_POST['SecaoGrupoCod'];

		$Campo = $Form->getFormSecaoPai($SecaoGrupoCod, false);

		echo $Campo['SecaoPai'];
	
	break;

	case "Pub": ##Publicar
		
		//Verificando permissões
		$Ac->setOpcao($_GET['Op']);
		$Ac->acessoModulo();
	
		$Secao = new Secao();
		
		echo $Secao->publicarSecao();
	
	break;				
	
	case "NPub": ##Não Publicar

		//Verificando permissões
		$Ac->setOpcao($_GET['Op']);
		$Ac->acessoModulo();

		$Secao = new Secao();
		
		echo $Secao->naoPublicarSecao();
		
	break;

	case "MudaPosicao": ##Mudar Posições

		$Secao = new Secao();
		
		try 
		{
			$Secao->mudaPosicao($_GET['SecaoCod'],$_GET['Posicao'],$_GET['Operacao']);
			
			echo "true";
		}
		catch (Exception $E)
		{
			echo $E->getMessage();
		}
		
	break;				
}
?>
