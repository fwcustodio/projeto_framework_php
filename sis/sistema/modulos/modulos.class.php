<?
include_once($_SESSION['FMBase'].'grid_padrao.class.php');
include_once($_SESSION['FMBase'].'grid_visualizar.class.php');
include_once($_SESSION['DirBase'].PACOTE.'/'.MODULO.'/'.MODULO.'.sql.php');

class Modulos extends ModulosSQL
{	
	
	public function getChave()
	{
		//Chave da Consulta
		return "ModuloCod";
	}	
	
	public function getParametros()
	{
		$Fil = new Filtrar();
			
		$Padrao = array("PaginaAtual","QuemOrdena","TipoOrdenacao");
		
		$MeusParametros = array("NomeMenu","GrupoCod", "VisivelMenu");
		
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
      	$Gr->setListados(array("NomeMenu", "GrupoDesc", "ModuloNome","ModuloDesc", "Posicao","VisivelMenu"));  
      	$Gr->setTitulos(array("Nome no Menu", "Grupo", "Nome do M�dulo", "Descri��o do M�dulo", "Posi��o","Visivel"));
      	
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
      	$Gr->setListados(array("NomeMenu", "GrupoDesc", "ModuloNome","ModuloDesc", "Posicao", "VisivelMenu","Help"));  
      	$Gr->setTitulos(array("Nome no Menu", "Grupo", "Nome do M�dulo", "Descri��o do M�dulo", "Posi��o","� Visivel no Menu","Help"));
      	
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
			if($Con->duplicado("_modulos", "ModuloNome", $ObjForm->getCampoRetorna('NomeModulo')))
			throw new Exception("Este Pacote J� existe!");
			
			if(is_array($_POST['OpcoesModulo']))
			{
				$ModuloCod = $Con->ultimoId("_modulos", "ModuloCod");					
				
				foreach($_POST['OpcoesModulo'] as $Cod)
				{	
					 $Sql = parent::cadastrarOpcoesModuloSql($ObjForm, $ModuloCod, $Cod);
					 $Con->executar($Sql);
				}
			}			
			
			//Busca Pacote
			$Pacote = $Con->execRLinha(parent::getPacote($ObjForm->getCampoRetorna('GrupoCod')));
			
			//Cria Diretorio
			@mkdir($_SESSION['DirBase'].$Pacote."/".$ObjForm->getCampoRetorna('ModuloNome'));
						
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
			
			//Altera Dados Fixos
			$Sql = parent::alterarSql($ObjForm);
			$Con->executar($Sql);

			//Vefica Duplica��o
			if($Con->duplicado("_modulos", "ModuloNome", $ObjForm->getCampoRetorna('NomeModulo')))
			throw new Exception("Este Pacote J� existe!");
			
			if($_POST['SDados'] <> "S")
			{
				//Remove Tipo de Permiss�o
				$OpcoesModuloCod = $Con->execRLinha(parent::getDadosOpcoesSql($ObjForm->getCampoRetorna('Id')));
				if(!empty($OpcoesModuloCod))
				{
					$Sql = parent::removeTipoPermissaoModulo($OpcoesModuloCod);
					$Con->executar($Sql);
				}			
			
				//Remove Op��es
				$Sql = parent::removerOpcoesSql($ObjForm->getCampoRetorna('Id'));
				$Con->executar($Sql);
	
				//Cadastra as Novas Op��es
				if(is_array($_POST['OpcoesModulo']))
				{				
					foreach($_POST['OpcoesModulo'] as $Cod)
					{	
						 $Sql = parent::cadastrarOpcoesModuloSql($ObjForm, $ObjForm->getCampoRetorna('Id'), $Cod);
						 $Con->executar($Sql);
					}
				}			
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
			
			foreach ($_POST['SisReg'] as $Chave)
			{		
				//Remove M�dulo
				$Sql = parent::removerSql($Chave);
				$Con->executar($Sql);
				
				//Remove Tipo de Permiss�o
				$OpcoesModuloCod = $Con->execRLinha(parent::getDadosOpcoesSql($Chave));
				if(!empty($OpcoesModuloCod))
				{
					$Sql = parent::removeTipoPermissaoModulo($OpcoesModuloCod);
					$Con->executar($Sql);
				}
				
				//Remove Op��es
				$Sql = parent::removerOpcoesSql($Chave);
				$Con->executar($Sql);
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
			
			$Campos   = array("Id", "GrupoCod", "Referencia", "ModuloNome", 
							  "NomeMenu", "ModuloDesc", "VisivelMenu", "Posicao", "Help");
			
			$CamposForm = @array_combine($Campos, $DadosSql);
			
			$FPHP->extractVar($CamposForm, $Metodo);
		}
		catch (Exception $E)
		{
			throw new Exception($E->getMessage());
		}
	}
	
	public function getDadosOpcoes($Cod)
	{
		try 
		{
			$Con   = Conexao::conectar();
			
			$Sql   = parent::getDadosOpcaoSql($Cod);
			 
			$Dados = $Con->execLinha($Sql);
			
			$_POST['NomePermissao'.$Cod] = $Dados['NomePermissao'];
			$_POST['IdPermissao'.$Cod]   = $Dados['IdPermissao'];
			$_POST['ImagemOn'.$Cod]      = $Dados['ImagemOn'];
			$_POST['ImagemOff'.$Cod]     = $Dados['ImagemOff'];
			$_POST['AltP'.$Cod]          = $Dados['AltP'];
			$_POST['AltNP'.$Cod]         = $Dados['AltNP'];
			$_POST['PrecisaId'.$Cod]     = $Dados['PrecisaId'];
			$_POST['Funcao'.$Cod]        = $Dados['Funcao'];
			$_POST['Pos'.$Cod]	         = $Dados['Posicao'];
		}
		catch (Exception $E)
		{
			throw new Exception($E->getMessage());
		}
	}
}

?>