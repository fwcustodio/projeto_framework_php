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
	*	Seta a C�digo Chave	
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
	*	Repons�vel pela filtragem dos dados na grid
	*	@return String
	*/	
	public function filtrar($ObjForm)
	{		
		$Gr  = new GridPadrao();
		
		//Grid de Visualiza��o- Configura��es
		$Gr->setListados(array("Titulo"));
		$Gr->setTitulos(array("Titulo"));
      	
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
		//$Gr->setQLinhas(ConfigSIS::$CFG['QLinhasGrid']);
		
		//Retornando a Grid Formatada - HTML
		return $Gr->inForm($Gr->montaGridPadrao(),"FormGrid");
	}
		
	/**
	*	Monta Estrutura de Visualiza��o dos Registros Selecionados
	*	@return String
	*/	
	public function visualizar()
	{	
		$Gr  = new GridVisualizar();

		//Grid de Visualiza? Detalhada
		$Gr->setListados(array("ContatoContatoCod", "Titulo", "Descricao", "ContatoCategoria", "Contato","EnderecoDadosTipo","Estado","Cidade","Rua","Numero","Bairro","CEP"));
		$Gr->setTitulos(array("C�digo", "Titulo", "Descricao", "Categoria", "Contato","Tipo","Estado","Cidade","Rua","Numero","Bairro","CEP"));

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
			$Vis .= "<div style='background:#dae4ed; font-weight:bold; font-family:Arial, Helvetica, sans-serif; font-size:12px; vertical-align:middle; padding-left:10px; padding:8px;'>Endere�os:</div>";
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
		
		//Inicia Transa��o		
		$Con->startTransaction();
		
		//Cadastra Endereco /
		$this->End->cadastrarEndereco($ObjForm);
		
		//Cadastra Contatos
		$this->Conta->cadastrarContato($ObjForm);
		
		$ObjForm->setCampoRetorna("EnderecoCod",$this->End->getEnderecoCod());
		$ObjForm->setCampoRetorna("ContatoCod",$this->Conta->getContatoCod());
	
		//Executa Sql		
		$Con->executar(parent::cadastrarSql($ObjForm));
		
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
		
		//Id
		$Id = $ObjForm->getCampoRetorna('Id');
		
		//C�digo do Endere�o
		$EnderecoCod = $Con->execRLinha(parent::getDadosSql($Id),"EnderecoCod");
		
		//C�digo do Contato
		$ContatoCod = $Con->execRLinha(parent::getDadosSql($Id),"ContatoCod");

		//Executa Sql		
		$Con->executar(parent::alterarSql($ObjForm));
		
		//Aletar endere�o Endere�os
		$this->End->alterarEndereco($ObjForm, $EnderecoCod);
		
		//Alterar Contato
		$this->Conta->alterarContato($ObjForm, $ContatoCod);

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
			//Inicia Conex�o
			$Con = Conexao::conectar();
			
			//Inicia Transa��o
			$Con->startTransaction();
			
			//Instancia Classe de Contato
			$Contato = new Contato();
			
			//Instancia Clssse de Endere�os
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
					//Verifica se j� existe alguma opera��o
					//if($Con->existe("tabela","campo",$Chave)) 
					//{
					//	$Mensagem[50] = "Este registro j� esta em uso!";
					//}
					//else 
					//{	
						$DadosContato = $Con->execLinha(parent::getDadosContatoSql($Chave));
					
						$Con->executar(parent::removerSql($Chave));
						
						//Remove Contato
						$Contato->removerContato($DadosContato['ContatoCod']);
						
						//Remove Endere�o
						$Endereco->removerEndereco($DadosContato['EnderecoCod']);
						
						$RApagados += 1;
					//}
				}
			}
		
			//Finaliza Transa��o
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
		//Instancia Fun��es PHP
		$FPHP = new FuncoesPHP();
		
		//Inicia Conex�o	
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
