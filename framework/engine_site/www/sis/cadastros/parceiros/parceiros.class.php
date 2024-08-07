<?
include_once($_SESSION['FMBase'].'grid_padrao.class.php');
include_once($_SESSION['FMBase'].'grid_visualizar.class.php');
include_once($_SESSION['FMBase'].'arquivos.class.php');
include_once($_SESSION['DirBase'].PACOTE.'/'.MODULO.'/'.MODULO.'.sql.php');

class Parceiros extends ParceirosSQL
{	
	/*
	*	Seta a Código Chave	
	*	@return String
	*/
	public function getChave()
	{
		return "ParceirosCod";
	}
	
	/*
	*	Retorna um array com os parametros utilizados no filtro
	*	@return Array
	*/
	public function getParametros()
	{
		$Fil = new Filtrar();
			
		$Padrao = array("PaginaAtual","QuemOrdena","TipoOrdenacao");
		
		$MeusParametros = array("ParceirosNome", "ParceirosLink", "ParceirosSituacao");
		
		$HiddenParametros = $Fil->getHiddenParametros($MeusParametros);
		
		return array_merge($Padrao, $MeusParametros, $HiddenParametros);
	}
		
	/**
	*	Reponsável pela filtragem dos dados na grid
	*	@return String
	*/	
	public function filtrar($ObjForm)
	{		
		$Gr  = new GridPadrao();
		
		//Grid de Visualização- Configurações
		$Gr->setListados(array("ParceirosNome", "ParceirosLink", "ParceirosSituacao"));
		$Gr->setTitulos(array("Nome", "Link", "Situação"));
      	
      	//Setando Parametros
      	Parametros::setParametros("GET", $this->getParametros());
      	     	
      	//Impressão
		if($_GET['ModoPrint'] == 'true')
		{
			$Gr->setQLinhas(0);
			$Gr->setModoImpressao(true);
		}
		else
		{
			$Gr->setQLinhas(ConfigSIS::$CFG['QLinhasGrid']);
			$Gr->setModoImpressao(false);		
		}
		
		//Configurações Fixas da Grid
      	$Gr->setSql(parent::filtrarSql($ObjForm));
		$Gr->setChave($this->getChave());
		$Gr->setTipoOrdenacao($_GET['TipoOrdenacao']);
		$Gr->setQuemOrdena($_GET['QuemOrdena']);
		$Gr->setPaginaAtual($_GET['PaginaAtual']);
		
		//Retornando a Grid Formatada - HTML
		return $Gr->inForm($Gr->montaGridPadrao(),"FormGrid");
	}
		
	/**
	*	Monta Estrutura de Visualização dos Registros Selecionados
	*	@return String
	*/	
	public function visualizar()
	{	
		$Gr  = new GridVisualizar();

		//Grid de Visualiza? Detalhada
		$Gr->setListados(array("ParceirosCod", "ParceirosNome", "ParceirosComentario", "ParceirosLink", "ParceirosSituacao"));
		$Gr->setTitulos(array("Código", "Nome", "Comentário", "Link", "Situação"));

      	//Configura?s Fixas da Grid
		$Gr->setChave($this->getChave());
		
		//Retornando a Grid Formatada - HTML
		if(!is_array($_POST['SisReg'])) throw new Exception("Nenhum registro selecionado!");
		
		foreach($_POST['SisReg'] as $Cod)
		{			
			$DadosImagem = $this->retornaNomeArquivo($Cod);
			
	      	$Gr->setSql(parent::visualizarSql($Cod));
			$Vis .= $Gr->montaGridVisualizar();
			
			if(empty($DadosImagem['ParceirosArquivo'])) {
				continue;	
			}
			$Vis .= "<div align='center' style='margin-top:10px; margin-bottom:20px'>";
			
			if($DadosImagem['ParceirosExtensao'] == "jpg" or $DadosImagem['ParceirosExtensao'] == "gif" or $DadosImagem['ParceirosExtensao'] == "png" or $DadosImagem['ParceirosExtensao'] == "jpeg") 
			{
				$Vis .= "<img src='".$_SESSION['UrlBaseSite']."/arquivos/parceiros/".$DadosImagem['ParceirosArquivo'].".".$DadosImagem['ParceirosExtensao']."?Cache=".date('d-m-Y-h-m-s').mt_rand(0,59889)."' border=0 />";
			} 
			else 
			{
				if($DadosImagem == "L") {
					$Altura  = "50";
					$Largura = "100";
				} else {
					$Altura  = "77";
					$Largura = "627";
				}
				
				$Vis .= '		<object id="FlashID" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="'.$Largura.'" height="'.$Altura.'">
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
			
			$Vis .= "</div>";
			
		}
		
		return $Vis;
	}	
	
	/**
	*	Reponsável pelo Cadastro das Informações
	*	@return Void
	*/	
	public function cadastrar($ObjForm)
	{
		//Inicia Conexão
		$Con = Conexao::conectar();
		
		//Inicia Transação		
		$Con->startTransaction();
		
		//Inicia Classe de Logs
		$Log = new Log();
		
		//Executa Sql		
		$Con->executar(parent::cadastrarSql($ObjForm));
		
		//Código Gerado
		$UnidadeCod = $Con->ultimoInsertId();
		
		$this->cadastraFoto($UnidadeCod,"Cad");
		
		//Grava Log
		$Log->geraLog($UnidadeCod);
		
		//Finaliza Transação					
		$Con->stopTransaction();			
	}	
	

	public function cadastraFoto($Id,$Op = NULL)
	{
		$Con = Conexao::conectar();
	
		$Arq = new Arquivos();

		$FilesCod = ($Op == "Cad") ? NULL : $Id;


		if(empty($_FILES['Imagens'.$FilesCod]['name']))
		{ 
			$Con->executar(parent::imagemSql($Id, NULL, NULL));
			return true;
		}

		
		$Quantidade = count($_FILES['Imagens'.$FilesCod]['name']);
		
		for($x=0;$x < $Quantidade; $x++) 
		{
			
			$Extensao = strtolower($Arq->extenssaoArquivo($_FILES['Imagens'.$FilesCod]['name'][$x]));
			
			$NomeArquivo = "parceiros".$Id.".".$Extensao;
			
			$DirBase = $_SESSION['DirBaseSite']."arquivos/parceiros/".$NomeArquivo;
			$Posicao = $x;
			
			
			if($Extensao == "jpg" or $Extensao == "jpeg" or $Extensao == "gif" or $Extensao == "png") 
			{
				$NovaLargura = '100';
				$NovaAltura  = '90';
				
				$tamanho = getimagesize($_FILES['Imagens'.$FilesCod]['tmp_name'][$x]);
				$largura = $tamanho[0];
				$altura  = $tamanho[1];
				
				if($largura > 100)
					$Arq->trataImagem("Imagens".$FilesCod, $DirBase, null, 100, null, $Posicao);
				else
					$Arq->trataImagem("Imagens".$FilesCod, $DirBase, 90, null, null, $Posicao);


			} 
			elseif($Extensao == "swf")
			{
				$Arq->upload("Imagens".$FilesCod, $DirBase, null, null, $Posicao);
			}


			$Con->executar(parent::imagemSql($Id, "parceiros".$Id, $Extensao));

		}
			
	}

	
	/**
	*	Reponsável pela alteração das Informações
	*	@return Void
	*/	
	public function alterar($ObjForm)
	{
		//Inicia Conexão
		$Con = Conexao::conectar();
		
		//Inicia Transação		
		$Con->startTransaction();
		
		//Inicia Classe de Logs
		$Log = new Log();
		
		//Executa Sql		
		$Con->executar(parent::alterarSql($ObjForm));
		
		//Retorna ID
		$Id = $ObjForm->getCampoRetorna('Id');
		
		if(empty($_POST['Manter'])) 
		{
			//Remove Arquivo
			$DadosImagem = $Con->execLinha(parent::retornaNomeArquivo($Id));
			@unlink($_SESSION['DirBaseSite'].'arquivos/parceiros/'.$DadosImagem['ParceirosArquivo'].".".$DadosImagem['ParceirosExtensao']);

			$this->cadastraFoto($Id,"Alt");
		}


		
		//Grava Log
		$Log->geraLog($Id);
		
		//Finaliza Transação					
		$Con->stopTransaction();			
	}
	
	/**
	*	Reponsável pela exclusão das Informações dos registros selecionados
	*	@return String
	*/	
	public function remover()
	{
		//Inicia Variaveis de Buffer
		$Mensagem      = array();//Array de Mensagens
		$RSelecionados = count($_POST['SisReg']);//Numero de Registros Selecionados na Grid
		$RApagados     = 0;//Numero de Registros Apagados
		
		//Intercepta Erros
		try
		{	
			if(!is_array($_POST['SisReg'])) throw new Exception("Nenhum registro selecionado!");
			
			//Inicia Conexão
			$Con = Conexao::conectar();
			
			//Inicia Transação
			$Con->startTransaction();
			
			//Inicia Classe de Logs
			$Log = new Log();
			
			foreach ($_POST['SisReg'] as $Chave)
			{		
				$DadosImagem = $Con->execLinha(parent::retornaNomeArquivo($Chave));
				@unlink($_SESSION['DirBaseSite'].'arquivos/parceiros/'.$DadosImagem['ParceirosArquivo'].".".$DadosImagem['ParceirosExtensao']);


				$Con->executar(parent::removerSql($Chave));

				$RApagados++;
			
				//Grava Log
				$Log->geraLog($Chave);
			}
			
			$Con->stopTransaction();
		}
		catch (Exception $E)
		{	
			$FPHP = new FuncoesPHP();
			
			$Mensagem[] = $FPHP->limpaStringJS($E->getMessage());
		}
						
		return 'var retorno = {"selecionados":'.$RSelecionados.', "apagados":'.$RApagados.',"mensagem":"'.implode("\\n",$Mensagem).'"}';
	}
	
	/**
	*	Retorna os dados gravados no banco encapsulados na superglobal desejada 
	*	@return Void
	*/	
	public function getDados($Id, $Metodo)
	{
		//Instancia Funções PHP
		$FPHP = new FuncoesPHP();
		
		//Inicia Conexão	
		$Con = Conexao::conectar();
		
		//Extrai Dados Sql
		$DadosSql = array_values($Con->execLinhaArray(parent::getDadosSql($Id)));
		
		//Define Campos
		$Campos   = array("Id", "ParceirosNome", "ParceirosComentario", "ParceirosLink", "ParceirosTipo", "ParceirosArquivo", "ParceirosExtensao","ParceirosSituacao");
		
		//Combina Sql com Campos Definidos
		$CamposForm = array_combine($Campos, $DadosSql);
		
		//Extrai Variaveis para o metodo desejado
		$FPHP->extractVar($CamposForm, $Metodo);
	}
	
	public function retornaNomeArquivo($Id)
	{
		//Inicia Conexão
		$Con = Conexao::conectar();

		$DadosImagem = $Con->execLinha(parent::retornaNomeArquivo($Id));
		
		return $DadosImagem;
	}
}
?>
