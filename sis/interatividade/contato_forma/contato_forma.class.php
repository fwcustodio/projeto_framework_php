<?
include_once($_SESSION['FMBase'].'grid_padrao.class.php');
include_once($_SESSION['FMBase'].'grid_visualizar.class.php');
include_once($_SESSION['DirBase'].PACOTE.'/'.MODULO.'/'.MODULO.'.sql.php');
include_once($_SESSION['DirBase'].'interatividade/endereco/endereco.class.php');
include_once($_SESSION['DirBase'].'interatividade/contato/contato.class.php');

class ContatoForma extends ContatoFormaSQL
{	
	
	public function ContatoForma()
	{
		$this->End   = new Endereco();
		$this->Conta = new Contato(); 
	}
	
	/*
	*	Seta a Código Chave	
	*	@return String
	*/
	public function getChave()
	{
		return "ContatoContatoCod";
	}
	
	/*
	*	Retorna um array com os parametros utilizados no filtro
	*	@return Array
	*/
	public function getParametros()
	{
		$Fil = new Filtrar();
			
		$Padrao = array("PaginaAtual","QuemOrdena","TipoOrdenacao");
		
		$MeusParametros = array("Nome", "Cidade", "AreaFormacao");
		
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
		$Gr->setListados(array("Titulo"));
		$Gr->setTitulos(array("Titulo"));
      	
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
		//$Gr->setQLinhas(ConfigSIS::$CFG['QLinhasGrid']);
		
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
		$Gr->setListados(array("ContatoContatoCod", "Titulo", "Descricao", "ContatoCategoria", "Contato","EnderecoDadosTipo","Estado","Cidade","Rua","Numero","Bairro","CEP"));
		$Gr->setTitulos(array("Código", "Titulo", "Descricao", "Categoria", "Contato","Tipo","Estado","Cidade","Rua","Numero","Bairro","CEP"));

      	//Configura?s Fixas da Grid
		$Gr->setChave($this->getChave());
		
		//Retornando a Grid Formatada - HTML
		if(!is_array($_POST['SisReg'])) throw new Exception("Nenhum registro selecionado!");
		
		foreach($_POST['SisReg'] as $Cod)
		{			
	      	$Gr->setSql(parent::visualizarSql($Cod));
			$Vis .= $Gr->montaGridVisualizar();
			
			$Gr->setSql(parent::infoContato($Cod));
			$Vis .= "<div style='background:#dae4ed; font-weight:bold; font-family:Arial, Helvetica, sans-serif; font-size:12px; vertical-align:middle; padding-left:10px; padding:8px;'>Formas de Contato:</div>";
			$Vis .= $Gr->montaGridVisualizar();
			
			$Gr->setSql(parent::infoEndereco($Cod));
			$Vis .= "<div style='background:#dae4ed; font-weight:bold; font-family:Arial, Helvetica, sans-serif; font-size:12px; vertical-align:middle; padding-left:10px; padding:8px;'>Endereços:</div>";
			$Vis .= $Gr->montaGridVisualizar();
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
		
		//Cadastra Endereco /
		$this->End->cadastrarEndereco($ObjForm);
		
		//Cadastra Contatos
		$this->Conta->cadastrarContato($ObjForm);
		
		$ObjForm->setCampoRetorna("EnderecoCod",$this->End->getEnderecoCod());
		$ObjForm->setCampoRetorna("ContatoCod",$this->Conta->getContatoCod());
	
		//Executa Sql		
		$Con->executar(parent::cadastrarSql($ObjForm));
		
		//Finaliza Transação					
		$Con->stopTransaction();			
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
		
		//Id
		$Id = $ObjForm->getCampoRetorna('Id');
		
		//Código do Endereço
		$EnderecoCod = $Con->execRLinha(parent::getDadosSql($Id),"EnderecoCod");
		
		//Código do Contato
		$ContatoCod = $Con->execRLinha(parent::getDadosSql($Id),"ContatoCod");

		//Executa Sql		
		$Con->executar(parent::alterarSql($ObjForm));
		
		//Aletar endereço Endereços
		$this->End->alterarEndereco($ObjForm, $EnderecoCod);
		
		//Alterar Contato
		$this->Conta->alterarContato($ObjForm, $ContatoCod);

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
			//Inicia Conexão
			$Con = Conexao::conectar();
			
			//Inicia Transação
			$Con->startTransaction();
			
			//Instancia Classe de Contato
			$Contato = new Contato();
			
			//Instancia Clssse de Endereços
			$Endereco = new Endereco();
			
			//Verifica se Existem Registros Selecionados
			if(!is_array($_POST['SisReg']))
			{
				$Mensagem[] = "Nenhum registro selecionado!";
			}
			else
			{
				//Percorre Array de Registros
				foreach ($_POST['SisReg'] as $Chave)
				{		
					//Verifica se já existe alguma operação
					//if($Con->existe("tabela","campo",$Chave)) 
					//{
					//	$Mensagem[50] = "Este registro já esta em uso!";
					//}
					//else 
					//{	
						$DadosContato = $Con->execLinha(parent::getDadosContatoSql($Chave));
					
						$Con->executar(parent::removerSql($Chave));
						
						//Remove Contato
						$Contato->removerContato($DadosContato['ContatoCod']);
						
						//Remove Endereço
						$Endereco->removerEndereco($DadosContato['EnderecoCod']);
						
						$RApagados += 1;
					//}
				}
			}
		
			//Finaliza Transação
			$Con->stopTransaction();
		}
		catch (Exception $E)
		{
			$Mensagem[] = $E->getMessage();
		}
		
		//Saida Array Javascript
		return 'var retorno = {"selecionados":'.$RSelecionados.', "apagados":'.$RApagados.',"mensagem":"'.implode("\\n",str_replace("<br>","\\n",$Mensagem)).'"}';
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
			$Campos   = array("Id", "EnderecoCod", "ContatoCod", "Titulo", "Descricao");

		//Combina Sql com Campos Definidos
		$CamposForm = array_combine($Campos, $DadosSql);
		
		//Extrai Variaveis para o metodo desejado
		$FPHP->extractVar($CamposForm, $Metodo);
	}
}
?>
