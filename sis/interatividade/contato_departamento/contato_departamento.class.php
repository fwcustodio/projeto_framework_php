<?
include_once($_SESSION['FMBase'].'grid_padrao.class.php');
include_once($_SESSION['FMBase'].'grid_visualizar.class.php');
include_once($_SESSION['DirBase'].PACOTE.'/'.MODULO.'/'.MODULO.'.sql.php');

class Departamento extends DepartamentoSQL
{	
	/*
	*	Seta a Código Chave	
	*	@return String
	*/
	public function getChave()
	{
		return "ContatoDepartamentoCod";
	}
	
	/*
	*	Retorna um array com os parametros utilizados no filtro
	*	@return Array
	*/
	public function getParametros()
	{
		$Fil = new Filtrar();
			
		$Padrao = array("PaginaAtual","QuemOrdena","TipoOrdenacao");
		
		$MeusParametros = array("Departamento");
		
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
		$Gr->setListados(array("Departamento","Status", "Finalidade"));
		$Gr->setTitulos(array("Departamento","Status", "Finalidade"));
      	
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
		$Gr->setListados(array("ContatoDepartamentoCod", "Departamento","Status", "Finalidade", "Nome","Login", "Email"));
		$Gr->setTitulos(array("Código", "Departamento","Status", "Finalidade", "Nome", "Login", "Email"));

      	//Configura?s Fixas da Grid
		$Gr->setChave($this->getChave());
		
		//Retornando a Grid Formatada - HTML
		if(!is_array($_POST['SisReg'])) throw new Exception("Nenhum registro selecionado!");
		
		foreach($_POST['SisReg'] as $Cod)
		{			
	      	$Gr->setSql(parent::visualizarSql($Cod));
			$Vis .= $Gr->montaGridVisualizar();

			$Gr->setSql(parent::visualizarUsuariosSql($Cod));
			$Vis .= $Gr->montaGridVisualizar();
			$Vis .= "<div style='height=50px;'>&nbsp;</div>";
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
		
		//Ultimo Id da Tabela
		$DepartamentoCod = $Con->ultimoId("contato_departamento", "ContatoDepartamentoCod");

		//Log Oculto
		$Con->setLogOculto(true);
		
		//Cadastrando dados
		$this->cadastrarDados($DepartamentoCod);
		
		//Log Oculto
		$Con->setLogOculto(false);
		
		//Código Gerado
		$UnidadeCod = $Con->ultimoInsertId();
		
		//Grava Log
		$Log->geraLog($UnidadeCod);
		
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
		
		//Inicia Classe de Logs
		$Log = new Log();
		
		//Executa Sql		
		$Con->executar(parent::alterarSql($ObjForm));
		
		//Cronograma Orçamentario Cod - Recupera
		$DepartamentoCod = $ObjForm->getCampoRetorna('Id');

		//Log Oculto
		$Con->setLogOculto(true);
		
		//Cadastrando dados
		$this->cadastrarDados($DepartamentoCod);		

		//Log Oculto
		$Con->setLogOculto(false);		

		//Grava Log
		$Log->geraLog($ObjForm->getCampoRetorna('Id'));
		
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
				if
				(
					$Con->existe("contato_mensagem", "ContatoDepartamentoCod", $Chave)
				)
				{
					$Mensagem[] = $Con->execRLinha(parent::getDadosSql($Chave),"Departamento").' já esta sendo usado no sistema!';	
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
		//Instancia Funções PHP
		$FPHP = new FuncoesPHP();
		
		//Inicia Conexão	
		$Con = Conexao::conectar();
		
		//Extrai Dados Sql
		$DadosSql = array_values($Con->execLinhaArray(parent::getDadosSql($Id)));
		
		//Define Campos
		$Campos   = array("Id", "Departamento","Status", "Finalidade");
		
		//Combina Sql com Campos Definidos
		$CamposForm = array_combine($Campos, $DadosSql);
		
		//Extrai Variaveis para o metodo desejado
		$FPHP->extractVar($CamposForm, $Metodo);
	}
	
	
	/*DADOS*/
	
	public function cadastrarDados($DepartamentoCod)
	{
		//Inicia Conexão	
		$Con = Conexao::conectar();	
		
		//Instancia Funções PHP
		$FPHP = new FuncoesPHP();
		
		//Remove Dados
		$this->removerDados($DepartamentoCod);
		
		//Recupera Array
		$ArrayDados = $_POST['ContadorDados'];

		//Cadastra
		if(is_array($ArrayDados))
		{
			foreach ($ArrayDados as $Codigo)
			{
				$UsuarioCod  = $FPHP->converteHTML(utf8_decode($_POST['UsuarioCod'.$Codigo]));

				$Con->executar(parent::cadastrarDadosSql($DepartamentoCod, $UsuarioCod));
			}	
		}
	}
	

	public function removerDados($DepartamentoCod)
	{
		//Inicia Conexão	
		$Con = Conexao::conectar();	
				
		//Verifica se Existe
		if($Con->existe('contato_responsavel', 'ContatoDepartamentoCod', $DepartamentoCod))
		{
			$Con->executar(parent::removerDadosSql($DepartamentoCod));
		}
	}
	
	public function getDadosDados($DepartamentoCod, $ObjForm)
	{
		//Instancia Funções PHP
		$FPHP = new FuncoesPHP();
		
		//Inicia Conexão	
		$Con = Conexao::conectar();	
		
		//ResultSet Com Dados
		$RSDados = $Con->executar(parent::getDadosDadosSql($DepartamentoCod));
		
		//Buffer Html
		$BufferHtml = '';
		
		while ($Dados = @mysqli_fetch_array($RSDados))
		{
			//Criando POTS
			$Cod = $Dados["ContatoResponsavelCod"];

			$_POST["UsuarioCod".$Cod]  = $Dados['UsuarioCod'];
			
			//Recuperando Dados nos Campos
			$Campos = $ObjForm->getFormDados($Cod);

			$BufferHtml.= "<div id='campoUsuario' class='dadosLinha".$Cod."'>
							".$Campos['ContadorDados'].$Campos['UsuarioCod']."
					&nbsp;&nbsp;<img src=\"".$_SESSION['UrlBase']."figuras/del_2.gif\" border=\"0\" onClick=\"removeItenDado(this, '$DepartamentoCod')\" style=\"cursor:pointer\">
						   </div>";

		}
		
		return $BufferHtml;
	}
	
	/*DADOS*/
	
}
?>