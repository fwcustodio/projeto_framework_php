<?
include_once($_SESSION['FMBase'].'grid_padrao.class.php');
include_once($_SESSION['FMBase'].'grid_visualizar.class.php');
include_once($_SESSION['FMBase'].'arquivos.class.php');
include_once($_SESSION['DirBase'].'conteudo/servicos_categoria/servicos_categoria.sql.php');

class CatServ extends CatServSQL
{	
	/*
	*	Seta a Cѓdigo Chave	
	*	@return String
	*/
	public function getChave()
	{
		return "ServicoCategoriaCod";
	}
	
	/*
	*	Retorna um array com os parametros utilizados no filtro
	*	@return Array
	*/
	public function getParametros()
	{
		$Fil = new Filtrar();
			
		$Padrao = array("PaginaAtual","QuemOrdena","TipoOrdenacao");
		
		$MeusParametros = array("ServicoCategoriaNome");
		
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
		$Gr->setListados(array("ServicoCategoriaNome"));
		$Gr->setTitulos(array("Nome da Categoria"));
      	
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
		$Gr->setListados(array("ServicoCategoriaCod", "ServicoCategoriaNome"));
		$Gr->setTitulos(array("Cѓdigo da Categoria", "Nome da Categoria"));

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
		
		//Instancia Arquivos
		$Arq = new Arquivos();
		
		//Inicia Classe de Logs
		$Log = new Log();
		
		//Executa Sql		
		$Con->executar(parent::cadastrarSql($ObjForm));
		
		//Cѓdigo Gerado
		$ServicoCategoriaCod = $Con->ultimoInsertId();
		
		//Cria diretorios
		$Arq->criaDiretorio($_SESSION['DirBaseSite'].'arquivos/servicos/'.$ServicoCategoriaCod,0777);
		$Arq->criaDiretorio($_SESSION['DirBaseSite'].'arquivos/servicos/'.$ServicoCategoriaCod.'/tb',0777);
		$Arq->criaDiretorio($_SESSION['DirBaseSite'].'arquivos/servicos/'.$ServicoCategoriaCod.'/home',0777);
		
		//Grava Log
		$Log->geraLog($ServicoCategoriaCod);
		
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
		
		$this->verificaAteracao($ObjForm);
		
		//Executa Sql		
		$Con->executar(parent::alterarSql($ObjForm));
		
		//Grava Log
		$Log->geraLog($ObjForm->getCampoRetorna('Id'));
		
		//Finaliza Transaчуo					
		$Con->stopTransaction();			
	}
	
	public function verificaAteracao($ObjForm)
	{
		//Instancia Funчѕes PHP
		$FPHP = new FuncoesPHP();
		
		//Inicia Conexуo	
		$Con = Conexao::conectar();
		
		$Pai  = $ObjForm->getCampoRetorna("CategoriaPai",false,"Inteiro");
		$Id   = $ObjForm->getCampoRetorna("Id",false,"Inteiro");
		
		
		$teste = $this->verificaFilhos($Id,$Pai);
			//var_dump($teste);
		if($teste == false) throw new Exception("Nуo Pode Selecionar Essa Categoria!");

	}
	
	//recursividade abstrata obscura// 
	public function verificaFilhos($Id, $Pai)
	{
		//Instancia Funчѕes PHP
		$FPHP = new FuncoesPHP();
		
		//Inicia Conexуo	
		$Con = Conexao::conectar();
		
		$Dados = $Con->executar(parent::filhosSql($Id));
		if ($Id == $Pai) return false;
		while($DadosFilhos = mysqli_fetch_array($Dados))
		{
			if ($this->verificaFilhos($DadosFilhos['ServicoCategoriaCod'],$Pai) == false) {
				return false;
			}
		}
		return true;
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
				if
				(
					$Con->existe("servico_produto", "ServicoCategoriaCod", $Chave)
					or
					$Con->existe("servico_categoria", "ServicoCategoriaCodPai", $Chave)
					
				)
				{
					$AA = $Con->execRLinha(parent::getDadosSql($Chave),"ServicoCategoriaNome");
					$Mensagem[] = $AA.' jс esta sendo usado no sistema!';	
				}
				else 
				{				
					$Con->executar(parent::removerSql($Chave));

					@rmdir($_SESSION['DirBaseSite'].'arquivos/servicos/'.$Chave.'/tb');
					@rmdir($_SESSION['DirBaseSite'].'arquivos/servicos/'.$Chave);
					@rmdir($_SESSION['DirBaseSite'].'arquivos/servicos/'.$Chave.'/home');
					

					
					//Grava Log
					$Log->geraLog($Chave);
					
					$RApagados++;
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
		$Campos   = array("Id", "CategoriaPai", "ServicoCategoriaNome", "Posicao");
		
		//Combina Sql com Campos Definidos
		$CamposForm = array_combine($Campos, $DadosSql);
		
		//Extrai Variaveis para o metodo desejado
		$FPHP->extractVar($CamposForm, $Metodo);
	}
}
?>