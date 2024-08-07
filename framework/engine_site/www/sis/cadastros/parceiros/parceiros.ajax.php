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
define("MODULO","parceiros");
define("PACOTE","cadastros");

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
		
		$Form = new ParceirosForm();
		$Parceiros = new Parceiros();
		$FPHP = new FuncoesPHP();
	
		try 
		{
			
			$Form->setEnv("true");
			$Form->setOp($_GET['Op']);
			$Form->getFormFiltro();
			
			echo($FPHP->formataErro($Form->getErro()).$Parceiros->filtrar($Form));
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

		$Parceiros = new Parceiros();
			
		try 
		{
			echo($Parceiros->visualizar());
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
		
		$Form = new ParceirosForm();
		$Form->setDecodificacao(false);//Semente para requisições não ajax!
		$Parceiros = new Parceiros();
		
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
					$Parceiros->cadastrar($Form);
					
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
				
				$Op 		 = $Form->getOp();
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
		
		$Form = new ParceirosForm();
		$Form->setDecodificacao(false);//Semente para requisições não ajax!
		$Parceiros = new Parceiros();
		
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
					$Parceiros->alterar($Form);
					
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
					$Parceiros->getDados($Id,"POST");
					$Campos = $Form->getFormManu();
					$Op = $Form->getOp();
					
					
					$DadosImagem = $Parceiros->retornaNomeArquivo($Id);
				
					$ArquivoExib = "<div style='margin-top:10px;'>";
					
					if($DadosImagem['ParceirosExtensao'] == "jpg" or $DadosImagem['ParceirosExtensao'] == "gif" or $DadosImagem['ParceirosExtensao'] == "png" or $DadosImagem['ParceirosExtensao'] == "jpeg") {
						$ArquivoExib .= "<img src='".$_SESSION['UrlBaseSite']."/arquivos/parceiros/".$DadosImagem['ParceirosArquivo'].".".$DadosImagem['ParceirosExtensao']."?Cache=".date('d-m-Y-h-m-s').mt_rand(0,59889)."' border=0 />";
					} else {
	
						if($DadosImagem == "L") {
							$Altura  = "50";
							$Largura = "110";
						} else {
							$Altura  = "77";
							$Largura = "627";
						}
	
						$ArquivoExib .= '<object id="FlashID" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="'.$Largura.'" height="'.$Altura.'">
											  <param name="movie" value="'.$_SESSION['UrlBaseSite'].'arquivos/parceiros/'.$DadosImagem['ParceirosArquivo'].'.'.$DadosImagem['ParceirosExtensao'].'">
											  <param name="quality" value="high">
											  <param name="wmode" value="opaque">
											  <param name="swfversion" value="6.0.65.0">
											  <!-- This param tag prompts users with Flash Player 6.0 r65 and higher to download the latest version of Flash Player. Delete it if you don’t want users to see the prompt. -->
											  <param name="expressinstall" value="Scripts/expressInstall.swf">
											  <!-- Next object tag is for non-IE browsers. So hide it from IE using IECC. -->
											  <!--[if !IE]>-->
											  <object type="application/x-shockwave-flash" data="'.$_SESSION['UrlBaseSite'].'arquivos/parceiros/'.$DadosImagem['ParceirosArquivo'].'.'.$DadosImagem['ParceirosExtensao'].'" width="'.$Largura.'" height="'.$Altura.'">
												<!--<![endif]-->
												<param name="quality" value="high">
												<param name="wmode" value="opaque">
												<param name="swfversion" value="6.0.65.0">
												<param name="expressinstall" value="Scripts/expressInstall.swf">
												<!-- The browser displays the following alternative content for users with Flash Player 6.0 and older. -->
												<div>
												  <h4>Content on this page requires a newer version of Adobe Flash Player.</h4>
												  <p><a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" width="'.$Largura.'" height="'.$Altura.'" /></a></p>
												</div>
												<!--[if !IE]>-->
											  </object>
											  <!--<![endif]-->
											</object>';
					}
					
					$ArquivoExib .= "</div>";
						
					
					
					
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
		$Parceiros = new Parceiros();
		
		//Executa metodo de remoção	
			echo $Parceiros->remover();
	
	break;
}
?>
