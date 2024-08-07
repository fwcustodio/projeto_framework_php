<?
include_once($_SESSION['FMBase'].'grid_padrao.class.php');
include_once($_SESSION['FMBase'].'grid_visualizar.class.php');
include_once($_SESSION['DirBase'].PACOTE.'/'.MODULO.'/'.MODULO.'.sql.php');

class GrupoModulo extends GrupoModuloSQL 
{	
	
	public function getChave()
	{
		return "GrupoCod";
	}
	
	public function getParametros()
	{
		$Fil = new Filtrar();
			
		$Padrao = array("PaginaAtual","QuemOrdena","TipoOrdenacao");
		
		$MeusParametros = array("NomeGrupo","Pacote");
		
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
		
		//Grid de Visualiza��o - Configura��es
      	$Gr->setListados(array("GrupoDesc", "Pacote", "Posicao"));  
      	$Gr->setTitulos(array("Nome do Grupo", "Pacote", "Posi��o"));
      	
      	//Setando Parametros
      	Parametros::setParametros("GET", $this->getParametros());
      	     	
      	//Configura��es Fixas da Grid
      	$Gr->setSql(parent::filtrarSql($ObjForm));
		$Gr->setChave($this->getChave());
		$Gr->setTipoOrdenacao($_GET['TipoOrdenacao']);
		$Gr->setQuemOrdena($_GET['QuemOrdena']);
		$Gr->setPaginaAtual($_GET['PaginaAtual']);
		$Gr->setQLinhas(ConfigSIS::$CFG['QLinhasGrid']);
		
		//Retornando a Grid Formatada - HTML
		return $Gr->inForm($Gr->montaGridPadrao(),"FormGrid");
	}
		
	/**
	*	Repons�vel pelo ResultSet de visualiza��o dos dados
	*	@return ResultSet
	*/	
	public function visualizar()
	{	
		$Gr  = new GridVisualizar();

		
		//Grid de Visualiza��o Detalhada
      	$Gr->setListados(array("GrupoDesc", "Pacote", "Posicao"));  
      	$Gr->setTitulos(array("Nome do Grupo", "Pacote", "Posi��o"));

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
		try 
		{
			$Con = Conexao::conectar();
					
			$Con->startTransaction();
					
			$Sql = parent::cadastrarSql($ObjForm);
			
			$Con->executar($Sql);

			//Vefica Duplica��o
			if($Con->duplicado(" _grupomodulo", "Pacote", $ObjForm->getCampoRetorna('Pacote')))
			throw new Exception("Este Pacote J� existe!");
			
			//Cria Diretorio
			@mkdir($_SESSION['DirBase'].$ObjForm->getCampoRetorna('Pacote'));
			
			$Log = new Log();
			$Log->geraLog($Sql);
			
			$Con->stopTransaction();			
		}
		catch (Exception $E)
		{
			$Con->stopTransaction(true);
			throw new Exception($E->getMessage());
		}			
	}	
	
	/**
	*	Repons�vel pela altera��o das Informa��es
	*	@return Void
	*/	
	public function alterar($ObjForm)
	{
		try 
		{
			$Con = Conexao::conectar();
					
			$Con->startTransaction();

			$Sql = parent::alterarSql($ObjForm);
			$Con->executar($Sql);
			
			//Vefica Duplica��o
			if($Con->duplicado(" _grupomodulo", "Pacote", $ObjForm->getCampoRetorna('Pacote')))
			throw new Exception("Este Pacote J� existe!");			
			
			$Log = new Log();
			$Log->geraLog($Sql, $ObjForm->getCampoRetorna('Id'));
			
			$Con->stopTransaction();			
		}
		catch (Exception $E)
		{
			$Con->stopTransaction(true);
			throw new Exception($E->getMessage());
		}			
	}
	
	/**
	*	Repons�vel pela exclus�o das Informa��es
	*	@return Void
	*/	
	public function remover()
	{
		try 
		{
			$Con = Conexao::conectar();
		
			$Con->startTransaction();
			
			if(!is_array($_POST['SisReg'])) throw new Exception("Nenhum registro selecionado!");

			$Log = new Log();
			
			foreach ($_POST['SisReg'] as $Chave)
			{		
				$Sql = parent::removerSql($Chave);
					
				$Con->executar($Sql);
			
				$Log->geraLog($Sql,$Chave);
			}
			
			$Con->stopTransaction();
		}
		catch (Exception $E)
		{
			$Con->stopTransaction(true);
			throw new Exception($E->getMessage());
		}				
	}
	
	/**
	*	Repons�vel pela recupera��o dos dados gravado no banco de dados
	*	@return Void
	*/	
	public function getDados($Id, $Metodo)
	{
		$FPHP = new FuncoesPHP();
			
		try 
		{
			$Con = Conexao::conectar();
			
			$Sql  = parent::getDadosSql($Id);
			
			$DadosSql = array_values($Con->execLinhaArray($Sql));
			
			$Campos   = array("Id", "NomeGrupo", "Pacote","Posicao");
			
			$CamposForm = array_combine($Campos, $DadosSql);
			
			$FPHP->extractVar($CamposForm, $Metodo);
		}
		catch (Exception $E)
		{
			throw new Exception($E->getMessage());
		}
	}
	
	public function dadosUltimoModulo()
	{
		try 
		{
			$Con = Conexao::conectar();
			
			$Sql  = parent::dadosUltimoModuloSql();
			
			return $Con->execLinha($Sql);
		}
		catch (Exception $E)
		{
			throw new Exception($E->getMessage());
		}
	}
}

?>