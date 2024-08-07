<?
include_once($_SESSION['FMBase'].'grid_padrao.class.php');
include_once($_SESSION['FMBase'].'grid_visualizar.class.php');
include_once($_SESSION['FMBase'].'arquivos.class.php');
include_once($_SESSION['DirBase'].'conteudo/galeria_arquivo/galeria_arquivo.sql.php');

class GaleriaArquivoA extends GaleriaArquivoASQL
{	
	/*
	*	Seta a C�digo Chave	
	*	@return String
	*/
	public function getChave()
	{
		return "ArquivoCategoriaCod";
	}
	
	/*
	*	Retorna um array com os parametros utilizados no filtro
	*	@return Array
	*/
	public function getParametros()
	{
		$Fil = new Filtrar();
			
		$Padrao = array("PaginaAtual","QuemOrdena","TipoOrdenacao");
		
		$MeusParametros = array("ArquivoCategoriaNome", "Documento", "Publicar");
		
		$HiddenParametros = $Fil->getHiddenParametros($MeusParametros);
		
		return array_merge($Padrao, $MeusParametros, $HiddenParametros);
	}
		
	/**
	*	Repons�vel pela filtragem dos dados na grid
	*	@return String
	*/	
	public function filtrar($ObjForm)
	{		
		$Gr  = new GridPadrao();
		
		//Grid de Visualiza��o- Configura��es
		$Gr->setListados(array("ArquivoCategoriaNome", "NArquivos"));
		$Gr->setTitulos(array("Galeria de Arquivos", "Numero de Arquivos"));
      	$Gr->setAlinhamento(array("NArquivos"=>"Direita"));
		
      	//Setando Parametros
      	Parametros::setParametros("GET", $this->getParametros());
      	     	
      	//Impress�o
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
		
		//Configura��es Fixas da Grid
      	$Gr->setSql(parent::filtrarSql($ObjForm));
		$Gr->setChave($this->getChave());
		$Gr->setTipoOrdenacao($_GET['TipoOrdenacao']);
		$Gr->setQuemOrdena($_GET['QuemOrdena']);
		$Gr->setPaginaAtual($_GET['PaginaAtual']);
		
		//Fundo para publicar = Nao
		$Gr->setCondicaoTodosResultados("\$R = (\$Linha['Publicar'] == 'N' ) ? true : false;", "sis_fundoNaoPublicar");

		//Fundo para situacao = Inativo
		$Gr->setCondicaoTodosResultados("\$R = (\$Linha['Situacao'] == 'I') ? true : false;", "sis_fundoInativo");

		//Retornando a Grid Formatada - HTML	
		return $Gr->inForm($Gr->montaGridPadrao()."<hr />".$this->getLegenda(),"FormGrid");
	}
		
	/**
	*	Monta Estrutura de Visualiza��o dos Registros Selecionados
	*	@return String
	*/	
	public function visualizar()
	{	
		$Gr  = new GridVisualizar();

		//Grid de Visualiza? Detalhada
		$Gr->setListados(array("ArquivoCategoriaCod", "ArquivoCategoriaNome", "Publicar", "Situacao"));
		$Gr->setTitulos(array("C�digo", "Galeria de Arquivos", "Publicar", "Situa��o"));

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
	*	Repons�vel pelo Cadastro das Informa��es
	*	@return Void
	*/	
	public function cadastrar($ObjForm)
	{
		//Inicia Conex�o
		$Con = Conexao::conectar();
		
		$Arquivos = new Arquivos();
		
		//Inicia Transa��o		
		$Con->startTransaction();
		
		//Inicia Classe de Logs
		$Log = new Log();
		
		//Executa Sql		
		$Con->executar(parent::cadastrarSql($ObjForm));
		
		$GaleriaArquivoCod = $Con->ultimoId("arquivo_categoria ","ArquivoCategoriaCod");
		
		//Cria Diretorios
		$DirBase = $_SESSION['DirBaseSite'].'arquivos/arquivos/';
		
		$Arquivos->criaDiretorio($DirBase.$GaleriaArquivoCod,0777);
		
		//Grava Log
		$Log->geraLog($GaleriaMidiaCod);
		
		//Finaliza Transa��o					
		$Con->stopTransaction();			
	}	
	
	/**
	*	Repons�vel pela altera��o das Informa��es
	*	@return Void
	*/	
	public function alterar($ObjForm)
	{
		//Inicia Conex�o
		$Con = Conexao::conectar();
		
		//Inicia Transa��o		
		$Con->startTransaction();
		
		//Inicia Classe de Logs
		$Log = new Log();
		
		//Executa Sql		
		$Con->executar(parent::alterarSql($ObjForm));
		
		//Grava Log
		$Log->geraLog($ObjForm->getCampoRetorna('Id'));
		
		//Finaliza Transa��o					
		$Con->stopTransaction();			
	}
	
	/**
	*	Repons�vel pela exclus�o das Informa��es dos registros selecionados
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
			
			//Inicia Conex�o
			$Con = Conexao::conectar();
			
			//Inicia Transa��o
			$Con->startTransaction();
			
			//Inicia Classe de Logs
			$Log = new Log();
			
			foreach ($_POST['SisReg'] as $Chave)
			{		
				if($Con->existe("arquivo", "ArquivoCategoriaCod", $Chave))
				{
					$Mensagem[] = $Con->execRLinha(parent::getDadosSql($Chave),"ArquivoCategoriaNome").' j� esta sendo usado no sistema!';	
				}
				else 
				{				
					$Con->executar(parent::removerSql($Chave));
					
					@rmdir($_SESSION['DirBaseSite']."/arquivos/arquivos/".$Chave);
					
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
		//Instancia Fun��es PHP
		$FPHP = new FuncoesPHP();
		
		//Inicia Conex�o	
		$Con = Conexao::conectar();
		
		//Extrai Dados Sql
		$DadosSql = array_values($Con->execLinhaArray(parent::getDadosSql($Id)));
		
		//Define Campos
		$Campos   = array("Id","ArquivoCategoriaNome", "Publicar", "Situacao");
		
		//Combina Sql com Campos Definidos
		$CamposForm = array_combine($Campos, $DadosSql);
		
		//Extrai Variaveis para o metodo desejado
		$FPHP->extractVar($CamposForm, $Metodo);
	}

	public function getLegenda()
	{
		$HTML = '<table width="100%" border="0" cellpadding="3" cellspacing="0"><tr>
					 <td class="fundoLegenda bordaLeg">Legenda:</td>
					 <td align="center" class="sis_fundoNaoPublicar fTamanho bordaLeg">Galeria N�o Publicada</td>
					 <td align="center" class="sis_fundoNormal fTamanho bordaLeg">Galeria Publicada</td>
				 </tr></table>';
		
		return $HTML;
	}			

}
?>