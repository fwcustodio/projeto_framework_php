<?
include_once($_SESSION['FMBase'].'grid_padrao.class.php');
include_once($_SESSION['FMBase'].'grid_visualizar.class.php');
include_once($_SESSION['DirBase'].'cadastros/autor/autor.sql.php');

class Autor extends AutorSQL
{	
	/*
	*	Seta a Cѓdigo Chave	
	*	@return String
	*/
	public function getChave()
	{
		return "AutorCod";
	}
	
	/*
	*	Retorna um array com os parametros utilizados no filtro
	*	@return Array
	*/
	public function getParametros()
	{
		$Fil = new Filtrar();
			
		$Padrao = array("PaginaAtual","QuemOrdena","TipoOrdenacao");
		
		$MeusParametros = array("AutorNome");
		
		$HiddenParametros = $Fil->getHiddenParametros($MeusParametros);
		
		return array_merge($Padrao, $MeusParametros, $HiddenParametros);
	}
		
	/**
	*	Reponsсvel pela filtragem dos dados na grid
	*	@return String
	*/	
	public function filtrar($ObjForm)
	{		
		$Gr  = new GridPadrao();
		
		//Grid de Visualizaчуo- Configuraчѕes
		$Gr->setListados(array("AutorNome"));
		$Gr->setTitulos(array("Nome do Autor ou Fonte"));
      	
      	//Setando Parametros
      	Parametros::setParametros("GET", $this->getParametros());
      	     	
      	//Impressуo
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
		
		//Configuraчѕes Fixas da Grid
      	$Gr->setSql(parent::filtrarSql($ObjForm));
		$Gr->setChave($this->getChave());
		$Gr->setTipoOrdenacao($_GET['TipoOrdenacao']);
		$Gr->setQuemOrdena($_GET['QuemOrdena']);
		$Gr->setPaginaAtual($_GET['PaginaAtual']);
		
		//Retornando a Grid Formatada - HTML
		return $Gr->inForm($Gr->montaGridPadrao(),"FormGrid");
	}
		
	/**
	*	Monta Estrutura de Visualizaчуo dos Registros Selecionados
	*	@return String
	*/	
	public function visualizar()
	{	
		$Gr  = new GridVisualizar();

		//Grid de Visualiza? Detalhada
		$Gr->setListados(array("AutorCod", "AutorNome"));
		$Gr->setTitulos(array("Cѓdigo", "Nome do Autor ou Fonte"));

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
	*	Reponsсvel pelo Cadastro das Informaчѕes
	*	@return Void
	*/	
	public function cadastrar($ObjForm)
	{
		//Inicia Conexуo
		$Con = Conexao::conectar();
		
		//Inicia Transaчуo		
		$Con->startTransaction();
		
		//Inicia Classe de Logs
		$Log = new Log();
		
		//Executa Sql		
		$Con->executar(parent::cadastrarSql($ObjForm));
		
		//Cѓdigo Gerado
		$AutorCod = $Con->ultimoInsertId();
		
		//Grava Log
		$Log->geraLog($AutorCod);
		
		//Finaliza Transaчуo					
		$Con->stopTransaction();			
	}	
	
	/**
	*	Reponsсvel pela alteraчуo das Informaчѕes
	*	@return Void
	*/	
	public function alterar($ObjForm)
	{
		//Inicia Conexуo
		$Con = Conexao::conectar();
		
		//Inicia Transaчуo		
		$Con->startTransaction();
		
		//Inicia Classe de Logs
		$Log = new Log();
		
		//Executa Sql		
		$Con->executar(parent::alterarSql($ObjForm));
		
		//Grava Log
		$Log->geraLog($ObjForm->getCampoRetorna('Id'));
		
		//Finaliza Transaчуo					
		$Con->stopTransaction();			
	}
	
	public function novoAutorExterno($ObjForm)
	{
		//Inicia Conexуo
		$Con = Conexao::conectar();
		
		$AutorNome = $ObjForm->getCampoRetorna("AutorNome");
		$AutorCod  = $ObjForm->getCampoRetorna("AutorCod");
		
		if(empty($AutorNome)) return null;
		
		$NovoAutorCod = $AutorCod;
		
		if(!empty($AutorCod))
		{
			if(!$Con->existe("autor","AutorNome",$AutorNome))
			{
				$Con->executar(parent::cadastrarSql($ObjForm));
				$NovoAutorCod = $Con->ultimoInsertId();	
			}
		}
		else 
		{
			$Con->executar(parent::cadastrarSql($ObjForm));
			$NovoAutorCod = $Con->ultimoInsertId();	
		}
		
		return $NovoAutorCod;	
	}
	
	public function novoAutorMultiplo($Nome,$Cod)
	{
		//Inicia Conexуo
		$Con = Conexao::conectar();
		
		$AutorNome = $Nome;
		$AutorCod  = $Cod;
		
		if(empty($AutorNome)) return null;
		
		$NovoAutorCod = $AutorCod;
		
		if(!empty($AutorCod))
		{
			if(!$Con->existe("autor","AutorNome",$AutorNome))
			{
				$Con->executar('INSERT INTO autor (AutorNome) VALUES ("'.$Nome.'")');
				$NovoAutorCod = $Con->ultimoInsertId();	
			}
		}
		else 
		{
			$Rs = $Con->execLinha('SELECT AutorCod, AutorNome FROM autor WHERE AutorNome = "'.$Nome.'" LIMIT 1');
			$NovoAutorCod = $Rs['AutorCod'];	
			
			if(empty($NovoAutorCod)) {
				$Con->executar('INSERT INTO autor (AutorNome) VALUES ("'.$Nome.'")');
				$NovoAutorCod = $Con->ultimoInsertId();	
			}
		}
		
		return $NovoAutorCod;	
	}
	
	/**
	*	Reponsсvel pela exclusуo das Informaчѕes dos registros selecionados
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
			
			//Inicia Conexуo
			$Con = Conexao::conectar();
			
			//Inicia Transaчуo
			$Con->startTransaction();
			
			//Inicia Classe de Logs
			$Log = new Log();
			
			foreach ($_POST['SisReg'] as $Chave)
			{		
				if($Con->existe("secao", "AutorCod", $Chave) or 
				   $Con->existe("galeria_arquivo", "AutorCod", $Chave))
				{
					$Mensagem[] = $Con->execRLinha(parent::getDadosSql($Chave),"AutorNome").' jс esta sendo usado no sistema!';	
				}
				else 
				{				
					$Con->executar(parent::removerSql($Chave));
					
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
		//Instancia Funчѕes PHP
		$FPHP = new FuncoesPHP();
		
		//Inicia Conexуo	
		$Con = Conexao::conectar();
		
		//Extrai Dados Sql
		$DadosSql = array_values($Con->execLinhaArray(parent::getDadosSql($Id)));
		
		//Define Campos
		$Campos   = array("Id", "AutorNome");
		
		//Combina Sql com Campos Definidos
		$CamposForm = array_combine($Campos, $DadosSql);
		
		//Extrai Variaveis para o metodo desejado
		$FPHP->extractVar($CamposForm, $Metodo);
	}
}
?>