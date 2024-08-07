<?
include_once($_SESSION['FMBase'].'grid_padrao.class.php');
include_once($_SESSION['FMBase'].'grid_visualizar.class.php');
include_once($_SESSION['FMBase'].'grid_click.class.php');
include_once($_SESSION['FMBase'].'arquivos.class.php');
include_once($_SESSION['FMBase'].'funcoes_php.class.php');
include_once($_SESSION['DirBase'].'conteudo/galeria_midia/galeria_midia.sql.php');
include_once($_SESSION['DirBase'].'conteudo/atualizacao_log/atualizacao_log.class.php');

class GaleriaMidia extends GaleriaMidiaSQL
{	
	/*
	*	Seta a Cï¿½digo Chave	
	*	@return String
	*/
	public function getChave()
	{
		return "GaleriaMidiaCod";
	}

	public function getAtualizacaoModuloCod()
	{
		return 5;
	}
	/*
	*	Retorna um array com os parametros utilizados no filtro
	*	@return Array
	*/
	public function getParametros()
	{
		$Fil = new Filtrar();
			
		$Padrao = array("PaginaAtual","QuemOrdena","TipoOrdenacao");
		
		$MeusParametros = array("GaleriaNome", "DataCriacao", "Publicar","IdiomaCod","Situacao");
		
		$HiddenParametros = $Fil->getHiddenParametros($MeusParametros);
		
		return array_merge($Padrao, $MeusParametros, $HiddenParametros);
	}
		
	/**
	*	Reponsï¿½vel pela filtragem dos dados na grid
	*	@return String
	*/	
	public function filtrar($ObjForm)
	{		
		$Gr  = new GridPadrao();
		$FPHP = new FuncoesPHP();//Instancia Funï¿½ï¿½es PHP
		
		//Grid de Visualizaï¿½ï¿½o- Configuraï¿½ï¿½es
		$Gr->setListados(array("GaleriaNome", "DataCriacao",  "Capa", "NArquivos"));
		$Gr->setTitulos(array("Nome da Galeria", "Data de Criação", "Galeria de Capa?" , "Número de Arquivos"));
      	$Gr->setAlinhamento(array("NArquivos"=>"Direita"));
      		
      	//Setando Parametros
      	Parametros::setParametros("GET", $this->getParametros());
      	     	
      	//Impressï¿½o
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
		
		//Configuraï¿½ï¿½es Fixas da Grid
      	$Gr->setSql(parent::filtrarSql($ObjForm));
		$Gr->setChave($this->getChave());
		$Gr->setTipoOrdenacao($_GET['TipoOrdenacao']);
		$Gr->setQuemOrdena($_GET['QuemOrdena']);
		$Gr->setPaginaAtual($_GET['PaginaAtual']);
		
		
		//Fundo para publicar = Nao
		$Gr->setCondicaoTodosResultados("\$R = (\$Linha['Publicar'] == 'N' ) ? true : false;", "sis_fundoNaoPublicar");

		//Fundo para situacao = Inativo
		$Gr->setCondicaoTodosResultados("\$R = (\$Linha['Situacao'] == 'I') ? true : false;", "sis_fundoInativo");

		$Gr->setObjConverte($FPHP,"convertDataHora","DataCriacao",array("DataCriacao"));
		
		//Retornando a Grid Formatada - HTML	
		return $Gr->inForm($Gr->montaGridPadrao()."<hr />".$this->getLegenda(),"FormGrid");

		
	}

	public function filtrarPop($ObjForm)
	{		
      	$Gr  = new GridClick(); 
      	$FPHP = new FuncoesPHP();//Instancia Funï¿½ï¿½es PHP
		
		//Grid de Visualizaï¿½ï¿½o- Configuraï¿½ï¿½es
		$Gr->setListados(array("GaleriaNome", "DataCriacao",  "Capa", "NArquivos"));
		$Gr->setTitulos(array("Nome da Galeria", "Data de Criação", "Galeria de Capa?" , "Número de Arquivos"));
      	$Gr->setAlinhamento(array("NArquivos"=>"Direita"));
 
 		//Setando Parametros
      	Parametros::setParametros("GET", array("PaginaAtual","QuemOrdena","TipoOrdenacao", "GaleriaNome","DataCriacao","Idioma","IdForm","TipoCampo"));
      	     			
		//Configura?s Fixas da Grid
      	$Gr->setSql(parent::filtrarPopSql($ObjForm));
		$Gr->setChave($this->getChave());
		$Gr->setTipoOrdenacao($_GET['TipoOrdenacao']);
		$Gr->setQuemOrdena($_GET['QuemOrdena']);
		$Gr->setPaginaAtual($_GET['PaginaAtual']);
		$Gr->setQLinhas(ConfigSIS::$CFG['QLinhasGrid']);
		
		$Gr->setObjConverte($FPHP,"convertDataHora","DataCriacao",array("DataCriacao"));
									
		//Marcar Contas pagas
		$Gr->setCondicaoTodosResultados("return(true) ? true : false;", "mao");
		

		
		//Fundo para publicar = Nao
		$Gr->setCondicaoTodosResultados("\$R = (\$Linha['Publicar'] == 'N' ) ? true : false;", "sis_fundoNaoPublicar");

		//Fundo para situacao = Inativo
		$Gr->setCondicaoTodosResultados("\$R = (\$Linha['Situacao'] == 'I') ? true : false;", "sis_fundoInativo");

		$Gr->setObjConverte($FPHP,"convertDataHora","DataCriacao",array("DataCriacao"));
		
		//Retornando a Grid Formatada - HTML
		//Arquivo Reponsï¿½vel pela aï¿½ï¿½o do click
		$INCJS = '<script type="text/javascript" src="'.$_SESSION['JSBase'].'js/grid_pop.js"></script>';
			
		return $Gr->inForm($Gr->montaGridClick()."<hr />".$this->getLegenda(),"FormGrid").$INCJS;
	}
		
	/**
	*	Monta Estrutura de Visualizaï¿½ï¿½o dos Registros Selecionados
	*	@return String
	*/	
	public function visualizar()
	{	
		$Gr  = new GridVisualizar();

		//Grid de Visualiza? Detalhada
		$Gr->setListados(array("GaleriaMidiaCod", "GaleriaNome", "DataCriacao", "Capa", "Publicar","Situacao", "NArquivos"));
		$Gr->setTitulos(array("Código", "Nome da Galeria", "Data de Criação", "Galeria de Capa?", "Publicar","Situação", "Número de Arquivos"));

      	//Configura?s Fixas da Grid
		$Gr->setChave($this->getChave());
		
		//Retornando a Grid Formatada - HTML
		if(!is_array($_POST['SisReg'])) throw new Exception("Nenhum registro selecionado!");
		
		foreach($_POST['SisReg'] as $Cod)
		{			
	      	$Gr->setSql(parent::visualizarSql($Cod));
			$Vis .= $Gr->montaGridVisualizar();
		}
		
		return $Vis;
	}	
	
	/**
	*	Reponsï¿½vel pelo Cadastro das Informaï¿½ï¿½es
	*	@return Void
	*/	
	public function cadastrar($ObjForm)
	{
		//Inicia Conexï¿½o
		$Con = Conexao::conectar();
		
		$Arquivos = new Arquivos();
		
		//Inicia Transaï¿½ï¿½o		
		$Con->startTransaction();
		
		//Inicia Classe de Logs
		$Log = new Log();


		//Executa Sql		
		$Con->executar(parent::cadastrarSql($ObjForm));
		$GaleriaMidiaCod = $Con->ultimoInsertId();
		
		//Cria Diretorios
		$DirBase = $_SESSION['DirBaseSite'].'arquivos/multimidia/';
		$Arquivos->criaDiretorio($DirBase.$GaleriaMidiaCod,0777);
		chmod($DirBase.$GaleriaMidiaCod."/",0777);
	
		$Arquivos->criaDiretorio($DirBase.$GaleriaMidiaCod.'/fotos/',0777);
		chmod($DirBase.$GaleriaMidiaCod.'/fotos/',0777);
		
		$Arquivos->criaDiretorio($DirBase.$GaleriaMidiaCod.'/videos/',0777);	
		chmod($DirBase.$GaleriaMidiaCod.'/videos/',0777);
		
		$Arquivos->criaDiretorio($DirBase.$GaleriaMidiaCod.'/audios/',0777);
		chmod($DirBase.$GaleriaMidiaCod.'/audios/',0777);

		$Arquivos->criaDiretorio($DirBase.$GaleriaMidiaCod.'/fotos/tb/',0777);
		chmod($DirBase.$GaleriaMidiaCod.'/fotos/tb/',0777);
		
		$Arquivos->criaDiretorio($DirBase.$GaleriaMidiaCod.'/capa/',0777);
		chmod($DirBase.$GaleriaMidiaCod.'/capa/',0777);

		if($ObjForm->getCampoRetorna('Capa') == 'S') {
			$this->cadastraAlteraImagem($GaleriaMidiaCod, $ObjForm);
		}

		//Grava Log
		$Log->geraLog($GaleriaMidiaCod);

		//Finaliza Transaï¿½ï¿½o					
		$Con->stopTransaction();			
	}	
	
	/**
	*	Reponsï¿½vel pela alteraï¿½ï¿½o das Informaï¿½ï¿½es
	*	@return Void
	*/	
	public function alterar($ObjForm)
	{
		//Inicia Conexï¿½o
		$Con = Conexao::conectar();

		$Arquivos = new Arquivos();

		//Inicia Transaï¿½ï¿½o		
		$Con->startTransaction();

		//Inicia Classe de Logs
		$Log = new Log();

		$Id	 = $ObjForm->getCampoRetorna('Id');

		//Executa Sql		
		$Con->executar(parent::alterarSql($ObjForm));

		if($ObjForm->getCampoRetorna('Capa') == 'S') 
		{
			if(empty($_POST['Manter'])) 
			{
				$this->cadastraAlteraImagem($Id, $ObjForm);
			}
		} else {
			$NomeArquivo = $Con->execRLinha("SELECT GaleriaMidiaCod, GaleriaMidiaCapaExtensao FROM galeria_midia_capa WHERE GaleriaMidiaCod = ".$Id."","GaleriaMidiaCapaExtensao");
			$Con->executar(parent::removeImagemCapa($Id));
			
			$DestinoP  = $_SESSION['DirBaseSite'].'arquivos/multimidia/'.$NomeArquivo['GaleriaMidiaCod'].'/capa/pequena_'.$Id.'.'.$NomeArquivo;
			$DestinoM  = $_SESSION['DirBaseSite'].'arquivos/multimidia/'.$NomeArquivo['GaleriaMidiaCod'].'/capa/media_'.$Id.'.'.$NomeArquivo;
			$DestinoG  = $_SESSION['DirBaseSite'].'arquivos/multimidia/'.$NomeArquivo['GaleriaMidiaCod'].'/capa/grande_'.$Id.'.'.$NomeArquivo;
			@unlink($DestinoP);	
			@unlink($DestinoM);	
			@unlink($DestinoG);	
		}
		
		//Grava Log
		$Log->geraLog($ObjForm->getCampoRetorna('Id'));
		
		//Finaliza Transaï¿½ï¿½o					
		$Con->stopTransaction();			
	}
	
	public function cadastraAlteraImagem($Id, $ObjForm)
	{
		//Inicia Conexï¿½o
		$Con = Conexao::conectar();

		$Arquivos = new Arquivos();

		$NomeOriginal = $_FILES['ImagemColuna']['name'];
		
		if(empty($_FILES['ImagemColuna']['name'])) throw new Exception("Você deve selecionar uma imagem para Capa");

		if(!empty($NomeOriginal))
		{			
			//Determina Tipo de Arquivo
			$Extensao = strtolower($Arquivos->extenssaoArquivo($NomeOriginal));
			$ObjForm->setCampoRetorna('Extensao',$Extensao);

			//Grava Informaï¿½ï¿½a da Imagem
			$Con->executar(parent::cadastrarImagemSql($Id, $ObjForm));

			//Cï¿½digo Gerado
			$ImagemCod = $Con->ultimoInsertId();	
			
			//Destinos
			$DestinoP  = $_SESSION['DirBaseSite'].'arquivos/multimidia/'.$Id.'/capa/pequena_'.$Id.'.'.$Extensao;
			$DestinoM  = $_SESSION['DirBaseSite'].'arquivos/multimidia/'.$Id.'/capa/media_'.$Id.'.'.$Extensao;
			$DestinoG  = $_SESSION['DirBaseSite'].'arquivos/multimidia/'.$Id.'/capa/grande_'.$Id.'.'.$Extensao;
				
			//Upload
			$Arquivos->trataImagem("ImagemColuna", $DestinoP, null, 120);
			$Arquivos->trataImagem("ImagemColuna", $DestinoM, null, 288);
			$Arquivos->trataImagem("ImagemColuna", $DestinoG, null, 524);

		}
	}
	
	/**
	*	Reponsï¿½vel pela exclusï¿½o das Informaï¿½ï¿½es dos registros selecionados
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
			
			//Inicia Conexï¿½o
			$Con = Conexao::conectar();
			
			//Inicia Transaï¿½ï¿½o
			$Con->startTransaction();
			
			//Inicia Classe de Logs
			$Log = new Log();
			
			foreach ($_POST['SisReg'] as $Chave)
			{		
				if
				(
					$Con->existe("galeria_arquivo", "GaleriaMidiaCod", $Chave) or 
					$Con->existe("galeria_midia_portifolio", "GaleriaMidiaCod", $Chave) or 
					$Con->existe("galeria_midia_produto", "GaleriaMidiaCod", $Chave) or 
					$Con->existe("galeria_midia_publicacao", "GaleriaMidiaCod", $Chave) or 
					$Con->existe("galeria_midia_servico", "GaleriaMidiaCod", $Chave) or 
					$Con->existe("galeria_midia_secao", "GaleriaMidiaCod", $Chave)
				)
				{
					$Mensagem[] = $Con->execRLinha(parent::getDadosSql($Chave),"GaleriaNome").' já esta sendo usada no sistema!';	
				}
				else 
				{			
					$Con->executar(parent::removerSql($Chave));
					
					$DirBase = $_SESSION['DirBaseSite'].'arquivos/multimidia/';


					$NomeArquivo = $Con->execRLinha("SELECT GaleriaMidiaCod, GaleriaMidiaCapaExtensao FROM galeria_midia_capa WHERE GaleriaMidiaCod = ".$Chave."","GaleriaMidiaCapaExtensao");
					$Con->executar(parent::removeImagemCapa($Chave));
					$DestinoP  = $_SESSION['DirBaseSite'].'arquivos/multimidia/'.$Chave.'/capa/pequena_'.$Chave.'.'.$NomeArquivo;			
					$DestinoM  = $_SESSION['DirBaseSite'].'arquivos/multimidia/'.$Chave.'/capa/media_'.$Chave.'.'.$NomeArquivo;			
					$DestinoG  = $_SESSION['DirBaseSite'].'arquivos/multimidia/'.$Chave.'/capa/grande_'.$Chave.'.'.$NomeArquivo;			
					@unlink($DestinoP);	
					@unlink($DestinoM);	
					@unlink($DestinoG);	

					@rmdir($DirBase.$Chave."/fotos/tb/");
					@rmdir($DirBase.$Chave."/fotos/");
					@rmdir($DirBase.$Chave."/audios/");
					@rmdir($DirBase.$Chave."/videos/");
					@rmdir($DirBase.$Chave."/capa/");
					@rmdir($DirBase.$Chave."/");

					$RApagados++;
					
					//Grava Log
					$Log->geraLog($Chave);
					
				}
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
		//Instancia Funï¿½ï¿½es PHP
		$FPHP = new FuncoesPHP();
		
		//Inicia Conexï¿½o	
		$Con = Conexao::conectar();
		
		//Extrai Dados Sql
		$DadosSql = array_values($Con->execLinhaArray(parent::getDadosSql($Id)));
		
		//Define Campos
		$Campos   = array("Id", "GaleriaNome", "DataCriacao", "Capa", "Publicar", "Situacao");
		
		//Combina Sql com Campos Definidos
		$CamposForm = array_combine($Campos, $DadosSql);
		
		//Extrai Variaveis para o metodo desejado
		$FPHP->extractVar($CamposForm, $Metodo);
	}
	
	public function getNomeGaleriaMidia($GaleriaMidiaCod)
	{
		//Inicia Conexï¿½o	
		$Con = Conexao::conectar();	
		
		$Html = '<tr><td>';
		$Html .= $Con->execRLinha(parent::getDadosSql($GaleriaMidiaCod),"GaleriaNome");
		$Html .= '</td><td width="20" align="center"><input type="hidden" name="ArrayGaleriaMidiaCod['.$GaleriaMidiaCod.']" id="ArrayGaleriaMidiaCod'.$GaleriaMidiaCod.'" value="'.$GaleriaMidiaCod.'" /><img src="'.$_SESSION['UrlBase'].'figuras/del_2.gif" alt="Remover" onclick="$(this).parent().parent().remove();" style="cursor:pointer" /></td></tr>';
		
		return $Html;
	}

        public function getListaGaleriaMidiaAcoes($SecaoCod)
	{
		//Inicia Conexão
		$Con = Conexao::conectar();

		$RSGaleriaMidiaSecao = $Con->executar(parent::getGaleriaMidiaAcoesSql($SecaoCod));

		while ($DadosGaleriaMidia = mysqli_fetch_array($RSGaleriaMidiaSecao))
		{
			$Html .= '<tr><td>';
			$Html .= $DadosGaleriaMidia['GaleriaNome'];
			$Html .= '</td><td width="20" align="center"><input type="hidden" name="ArrayGaleriaMidiaCod['.$DadosGaleriaMidia['GaleriaMidiaCod'].']" id="ArrayGaleriaMidiaCod'.$DadosGaleriaMidia['GaleriaMidiaCod'].'" value="'.$DadosGaleriaMidia['GaleriaMidiaCod'].'" /><img src="'.$_SESSION['UrlBase'].'figuras/del_2.gif" alt="Remover" onclick="$(this).parent().parent().remove();" style="cursor:pointer" /></td></tr>';
		}

		return $Html;
	}

	public function getListaGaleriaMidiaSecao($SecaoCod)
	{
		//Inicia Conexï¿½o	
		$Con = Conexao::conectar();	
		
		$RSGaleriaMidiaSecao = $Con->executar(parent::getGaleriaMidiaSecaoSql($SecaoCod));
		
		while ($DadosGaleriaMidia = mysqli_fetch_array($RSGaleriaMidiaSecao))
		{
			$Html .= '<tr><td>';
			$Html .= $DadosGaleriaMidia['GaleriaNome'];
			$Html .= '</td><td width="20" align="center"><input type="hidden" name="ArrayGaleriaMidiaCod['.$DadosGaleriaMidia['GaleriaMidiaCod'].']" id="ArrayGaleriaMidiaCod'.$DadosGaleriaMidia['GaleriaMidiaCod'].'" value="'.$DadosGaleriaMidia['GaleriaMidiaCod'].'" /><img src="'.$_SESSION['UrlBase'].'figuras/del_2.gif" alt="Remover" onclick="$(this).parent().parent().remove();" style="cursor:pointer" /></td></tr>';
		}
	
		return $Html;
	}
//
//	public function getListaGaleriaMidiaLocalidade($LocalidadeCod)
//	{
//		//Inicia Conexï¿½o
//		$Con = Conexao::conectar();
//
//		$RSGaleriaMidiaLocalidade = $Con->executar(parent::getGaleriaMidiaLocalidadeSql($LocalidadeCod));
//
//		while ($DadosGaleriaMidia = mysqli_fetch_array($RSGaleriaMidiaLocalidade))
//		{
//			$Html .= '<tr><td>';
//			$Html .= $DadosGaleriaMidia['GaleriaNome'];
//			$Html .= '</td><td width="20" align="center"><input type="hidden" name="ArrayGaleriaMidiaCod['.$DadosGaleriaMidia['GaleriaMidiaCod'].']" id="ArrayGaleriaMidiaCod'.$DadosGaleriaMidia['GaleriaMidiaCod'].'" value="'.$DadosGaleriaMidia['GaleriaMidiaCod'].'" /><img src="'.$_SESSION['UrlBase'].'figuras/del_2.gif" alt="Remover" onclick="$(this).parent().parent().remove();" style="cursor:pointer" /></td></tr>';
//		}
//
//		return $Html;
//	}
	
	public function getListaGaleriaMidiaHospedagem($HospedagemCod)
	{
		//Inicia Conexï¿½o	
		$Con = Conexao::conectar();	
		
		$RSGaleriaMidiaHospedagem = $Con->executar(parent::getGaleriaMidiaHospedagemSql($HospedagemCod));
		
		while ($DadosGaleriaMidia = mysqli_fetch_array($RSGaleriaMidiaHospedagem))
		{
			$Html .= '<tr><td>';
			$Html .= $DadosGaleriaMidia['GaleriaNome'];
			$Html .= '</td><td width="20" align="center"><input type="hidden" name="ArrayGaleriaMidiaCod['.$DadosGaleriaMidia['GaleriaMidiaCod'].']" id="ArrayGaleriaMidiaCod'.$DadosGaleriaMidia['GaleriaMidiaCod'].'" value="'.$DadosGaleriaMidia['GaleriaMidiaCod'].'" /><img src="'.$_SESSION['UrlBase'].'figuras/del_2.gif" alt="Remover" onclick="$(this).parent().parent().remove();" style="cursor:pointer" /></td></tr>';
		}
	
		return $Html;
	}
	

	public function getListaGaleriaMidiaEvento($EventoCod)
	{
		//Inicia Conexï¿½o	
		$Con = Conexao::conectar();	
		
		$RSGaleriaMidiaEvento = $Con->executar(parent::getGaleriaMidiaEventoSql($EventoCod));
		
		while ($DadosGaleriaEvento = mysqli_fetch_array($RSGaleriaMidiaEvento))
		{
			$Html .= '<tr><td>';
			$Html .= $DadosGaleriaEvento['GaleriaNome'];
			$Html .= '</td><td width="20" align="center"><input type="hidden" name="ArrayGaleriaMidiaCod['.$DadosGaleriaEvento['GaleriaMidiaCod'].']" id="ArrayGaleriaMidiaCod'.$DadosGaleriaEvento['GaleriaMidiaCod'].'" value="'.$DadosGaleriaEvento['GaleriaMidiaCod'].'" /><img src="'.$_SESSION['UrlBase'].'figuras/del_2.gif" alt="Remover" onclick="$(this).parent().parent().remove();" style="cursor:pointer" /></td></tr>';
		}
	
		return $Html;
	}
	
	public function getListaGaleriaMidiaPublicacao($PublicacaoCod)
	{
		//Inicia Conexï¿½o	
		$Con = Conexao::conectar();	
		
		$RSGaleriaMidiaPublicacao = $Con->executar(parent::getGaleriaMidiaPublicacaoSql($PublicacaoCod));
		
		while ($DadosGaleriaMidia = mysqli_fetch_array($RSGaleriaMidiaPublicacao))
		{
			$Html .= '<tr><td>';
			$Html .= $DadosGaleriaMidia['GaleriaNome'];
			$Html .= '</td><td width="20" align="center"><input type="hidden" name="ArrayGaleriaMidiaCod['.$DadosGaleriaMidia['GaleriaMidiaCod'].']" id="ArrayGaleriaMidiaCod'.$DadosGaleriaMidia['GaleriaMidiaCod'].'" value="'.$DadosGaleriaMidia['GaleriaMidiaCod'].'" /><img src="'.$_SESSION['UrlBase'].'figuras/del_2.gif" alt="Remover" onclick="$(this).parent().parent().remove();" style="cursor:pointer" /></td></tr>';
		}
	
		return $Html;
	}	
	
	public function getListaGaleriaMidiaColuna($ColunaConteudoCod)
	{
		//Inicia Conexï¿½o	
		$Con = Conexao::conectar();	
		
		$RSGaleriaMidiaNoticia = $Con->executar(parent::getGaleriaMidiaNoticiaSql($ColunaConteudoCod));
		
		while ($DadosGaleriaMidia = mysqli_fetch_array($RSGaleriaMidiaNoticia))
		{
			$Html .= '<tr><td>';
			$Html .= $DadosGaleriaMidia['GaleriaNome'];
			$Html .= '</td><td width="20" align="center"><input type="hidden" name="ArrayGaleriaMidiaCod['.$DadosGaleriaMidia['GaleriaMidiaCod'].']" id="ArrayGaleriaMidiaCod'.$DadosGaleriaMidia['GaleriaMidiaCod'].'" value="'.$DadosGaleriaMidia['GaleriaMidiaCod'].'" /><img src="'.$_SESSION['UrlBase'].'figuras/del_2.gif" alt="Remover" onclick="$(this).parent().parent().remove();" style="cursor:pointer" /></td></tr>';
		}
	
		return $Html;
	}	

                public function getListaGaleriaMidiaProduto($ColunaConteudoCod)
	{
		//Inicia Conex?o
		$Con = Conexao::conectar();

		$RSGaleriaMidiaNoticia = $Con->executar(parent::getGaleriaMidiaProdutoSql($ColunaConteudoCod));

		while ($DadosGaleriaMidia = mysqli_fetch_array($RSGaleriaMidiaNoticia))
		{
			$Html .= '<tr><td>';
			$Html .= $DadosGaleriaMidia['GaleriaNome'];
			$Html .= '</td><td width="20" align="center"><input type="hidden" name="ArrayGaleriaMidiaCod['.$DadosGaleriaMidia['GaleriaMidiaCod'].']" id="ArrayGaleriaMidiaCod'.$DadosGaleriaMidia['GaleriaMidiaCod'].'" value="'.$DadosGaleriaMidia['GaleriaMidiaCod'].'" /><img src="'.$_SESSION['UrlBase'].'figuras/del_2.gif" alt="Remover" onclick="$(this).parent().parent().remove();" style="cursor:pointer" /></td></tr>';
		}

		return $Html;
	}


	public function getListaGaleriaMidiaNoticia($ColunaConteudoCod)
	{
		//Inicia Conexï¿½o	
		$Con = Conexao::conectar();	
		
		$RSGaleriaMidiaNoticia = $Con->executar(parent::getGaleriaMidiaNoticiaSql($ColunaConteudoCod));
		
		while ($DadosGaleriaMidia = mysqli_fetch_array($RSGaleriaMidiaNoticia))
		{
			$Html .= '<tr><td>';
			$Html .= $DadosGaleriaMidia['GaleriaNome'];
			$Html .= '</td><td width="20" align="center"><input type="hidden" name="ArrayGaleriaMidiaCod['.$DadosGaleriaMidia['GaleriaMidiaCod'].']" id="ArrayGaleriaMidiaCod'.$DadosGaleriaMidia['GaleriaMidiaCod'].'" value="'.$DadosGaleriaMidia['GaleriaMidiaCod'].'" /><img src="'.$_SESSION['UrlBase'].'figuras/del_2.gif" alt="Remover" onclick="$(this).parent().parent().remove();" style="cursor:pointer" /></td></tr>';
		}
	
		return $Html;
	}	
	
	public function getLegenda()
	{
		$HTML = '<table width="100%" border="0" cellpadding="3" cellspacing="0"><tr>
					 <td class="fundoLegenda bordaLeg">Legenda:</td>
					 <td align="center" class="sis_fundoNaoPublicar fTamanho bordaLeg">Galeria Não Publicada</td>
					 <td align="center" class="sis_fundoInativo fTamanho bordaLeg">Galeria Inativada</td>
					 <td align="center" class="sis_fundoNormal fTamanho bordaLeg">Galeria Ativa</td>
				 </tr></table>';
		
		return $HTML;
	}

        public function getListaGaleriaMidiaPortifolio($SecaoCod){
            		//Inicia Conexï¿½o
		$Con = Conexao::conectar();

		$RSGaleriaMidiaPortifolio = $Con->executar(parent::getListaGaleriaMidiaPortifolioSql($SecaoCod));

		while ($DadosGaleriaMidia = mysqli_fetch_array($RSGaleriaMidiaPortifolio))
		{
			$Html .= '<tr><td>';
			$Html .= $DadosGaleriaMidia['GaleriaNome'];
			$Html .= '</td><td width="20" align="center"><input type="hidden" name= "ArrayGaleriaMidiaCod['.$DadosGaleriaMidia['GaleriaMidiaCod'].']" id="ArrayGaleriaMidiaCod'.$DadosGaleriaMidia['GaleriaMidiaCod'].'" value="'.$DadosGaleriaMidia['GaleriaMidiaCod'].'" /><img src="'.$_SESSION['UrlBase'].'figuras/del_2.gif" alt="Remover" onclick="$(this).parent().parent().remove();" style="cursor:pointer" /></td></tr>';
		}

		return $Html;
        }
		
		
		 public function getListaGaleriaMidiaServico($SecaoCod){
            		//Inicia Conexï¿½o
		$Con = Conexao::conectar();

		$RSGaleriaMidiaPortifolio = $Con->executar(parent::getListaGaleriaMidiaServicoSql($SecaoCod));

		while ($DadosGaleriaMidia = mysqli_fetch_array($RSGaleriaMidiaPortifolio))
		{
			$Html .= '<tr><td>';
			$Html .= $DadosGaleriaMidia['GaleriaNome'];
			$Html .= '</td><td width="20" align="center"><input type="hidden" name= "ArrayGaleriaMidiaCod['.$DadosGaleriaMidia['GaleriaMidiaCod'].']" id="ArrayGaleriaMidiaCod'.$DadosGaleriaMidia['GaleriaMidiaCod'].'" value="'.$DadosGaleriaMidia['GaleriaMidiaCod'].'" /><img src="'.$_SESSION['UrlBase'].'figuras/del_2.gif" alt="Remover" onclick="$(this).parent().parent().remove();" style="cursor:pointer" /></td></tr>';
		}

		return $Html;
        }
		
}
?>