<?
include_once($_SESSION['FMBase'].'grid_padrao.class.php');
include_once($_SESSION['FMBase'].'grid_visualizar.class.php');
include_once($_SESSION['DirBase'].PACOTE.'/'.MODULO.'/'.MODULO.'.sql.php');

class Mensagem extends MensagemSQL
{	
	/*
	*	Seta a Código Chave	
	*	@return String
	*/
	public function getChave()
	{
		return "ContatoMensagemCod";
	}
	
	/*
	*	Retorna um array com os parametros utilizados no filtro
	*	@return Array
	*/
	public function getParametros()
	{
		$Fil = new Filtrar();
			
		$Padrao = array("PaginaAtual","QuemOrdena","TipoOrdenacao");
		
		$MeusParametros = array("ContatoDepartamentoCod", "AssuntoCod", "Nome", "Criacao", "Status");
		
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
		$Gr->setListados(array("Departamento", "Assunto","Nome","Criacao","Status"));
		$Gr->setTitulos(array("Departamento", "Assunto","Nome", "Criação","Status"));
      	
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
		
		
		$FPHP = new FuncoesPHP();//Instancia Funções PHP
				
		$Gr->setObjConverte($FPHP,"convertDataHora","Criacao",array("Criacao"));
		
		//Fundo para situacao = Inativo
		$Gr->setCondicaoTodosResultados("\$R = (\$Linha['Status'] == 'Não Lida') ? true : false;", "sis_fundoInativo");

		//Retornando a Grid Formatada - HTML	
		return $Gr->inForm($Gr->montaGridPadrao()."<hr />".$this->getLegenda(),"FormGrid");
	}
		
	/**
	*	Monta Estrutura de Visualização dos Registros Selecionados
	*	@return String
	*/	
	public function visualizar()
	{	
		//Inicia Conexão
		$Con = Conexao::conectar();
		
		//Inicia Transação		
		$Con->startTransaction();

		$Gr  = new GridVisualizar();

		//Grid de Visualiza? Detalhada
		$Gr->setListados(array("ContatoMensagemCod", "Idioma", "Departamento", "Assunto", "Nome", "Email", "Telefone", "Pais", "UF", "Cidade", "Mensagem", "Criacao", "Status", "Observacoes"));
		$Gr->setTitulos(array("Código", "Idioma", "Departamento", "Assunto",  "Nome", "Email", "Telefone", "Pais", "UF", "Cidade", "Mensagem", "Criação", "Status", "Observações"));

      	//Configura?s Fixas da Grid
		$Gr->setChave($this->getChave());
		
		//Retornando a Grid Formatada - HTML
		if(!is_array($_POST['SisReg'])) throw new Exception("Nenhum registro selecionado!");
		
		foreach($_POST['SisReg'] as $Cod)
		{	
			//Altera Status da Mensagem para LIDA
			$Con->executar(parent::visualizarAlteraStatusSql($Cod));
			
	      	$Gr->setSql(parent::visualizarSql($Cod));
			$Vis .= $Gr->montaGridVisualizar();
		}
		
		//Finaliza Transação					
		$Con->stopTransaction();	
		
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
		
		//Executa Sql Verifica Departamento Anterior
		$DepartamentoCodAnterior = $Con->execRLinha(parent::getDepartamentoSql(),"ContatoDepartamentoCod");	
		$DepartamentoCodNovo	 = $_POST['ContatoDepartamentoCod'];

		//Executa Sql	
		$Con->executar(parent::alterarSql($ObjForm));		

		//Caso sera adotado um novo departamento, será disparado um e-mail!
		if($DepartamentoCodAnterior != $DepartamentoCodNovo) {
			//Altera status da Msg para Não Lido
			$Con->executar(parent::alterarStatusSql());		

			//Dispara e-Mail
			$this->enviaEmail($DepartamentoCodNovo, $DepartamentoCodAnterior);
		}
		
		//Grava Log
		$Log->geraLog($ObjForm->getCampoRetorna('Id'));
		
		//Finaliza Transação					
		$Con->stopTransaction();			
	}
	
	public function enviaEmail($DepartamentoCod,$DepartamentoCodAnterior)
	{
		//Inicia Conexão
		$Con = Conexao::conectar();
		
		//Inicia Transação		
		$Con->startTransaction();

		$Dados 				  = $Con->execLinha(parent::geraMensagemEmailSql($Cod));
		$DepartamentoAnterior = $Con->execRLinha(parent::getDepartamentoAntigoSql($DepartamentoCodAnterior),"Departamento");	
		
		$Header   = "FROM: ".$Dados['Nome']." <".$Dados['Email'].">\nContent-type: text/html;  charset=iso-8859-1\n\r";
		
		$CorpoMsg  = "<b>".ConfigSIS::$CFG["TituloAdm"]."</b><br>
					  ------------------------------------------------------------------------------------------------------<br><br>
					  <b>Código da Mensagem:</b> ".$Dados['ContatoMensagemCod']."<br>
					  <b>Idioma:</b> ".$Dados['Idioma']."<br>
					  <b>Departamento:</b> ".$Dados['Departamento']."<br>
					  <b>Nome:</b> ".$Dados['Nome']."<br>
					  <b>E-mail:</b> ".$Dados['Email']."<br>
					  <b>Telefone:</b> ".$Dados['Telefone']."<br>
					  <b>País:</b> ".$Dados['Pais']."<br>
					  <b>Cidade:</b> ".$Dados['Cidade']." / <b>UF:</b> ".$Dados['UF']."<br>
					  <b>Mensagem:</b> ".$Dados['Mensagem']."<br><br>

					  ------------------------------------------------------------------------------------------------------<br>
					  <b>Observações:</b> ".$Dados['Observacoes']."<br><br>

					  ------------------------------------------------------------------------------------------------------<br>
					  A mensagem foi enviada através formulário de contato em <b>".$Dados['Criacao']."</b>.<br>
					  Foi encaminhada pelo departamento: <b>".$DepartamentoAnterior."</b><br>
					  ------------------------------------------------------------------------------------------------------<br>";

		$Conteudo = "<html><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" /><style type=\"text/css\"> body,td,th { font-family: Arial, Verdana, Geneva, sans-serif; font-size: 12px; color: #000; } </style></head><body>";
		$Conteudo .= $CorpoMsg;
		$Conteudo .= "</body></html>";


		$RsUsuariosDepartamento = $Con->executar(parent::geraListaUsuariosSql($DepartamentoCod));
		
		while($DadosUsuarios = mysqli_fetch_array($RsUsuariosDepartamento)) {
			@mail("".$DadosUsuarios['Email']."", "[CPP] ".$Dados['Assunto']."", "".$Conteudo."", "".$Header."");
		}

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
	*	Reponsável pela exclusão das Informações dos registros selecionados
	*	@return String
	*/	
	public function marcarLida()
	{
		//Inicia Variaveis de Buffer
		$Mensagem      = array();//Array de Mensagens
		$RSelecionados = count($_POST['SisReg']);//Numero de Registros Selecionados na Grid
		$RMarcados     = 0;//Numero de Registros Apagados
		
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
				if($Con->execRLinha(parent::getDadosSql($Chave),"Status") == "NL") {
					$Con->executar(parent::marcarLidaSql($Chave));
					$RMarcados++;
				
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
						
		return 'var retorno = {"selecionados":'.$RSelecionados.', "lida":'.$RMarcados.',"mensagem":"'.implode("\\n",$Mensagem).'"}';
	}
	
	
/**
	*	Reponsável pela exclusão das Informações dos registros selecionados
	*	@return String
	*/	
	public function marcarNaoLida()
	{
		//Inicia Variaveis de Buffer
		$Mensagem      = array();//Array de Mensagens
		$RSelecionados = count($_POST['SisReg']);//Numero de Registros Selecionados na Grid
		$RMarcados     = 0;//Numero de Registros Apagados
		
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
				if($Con->execRLinha(parent::getDadosSql($Chave),"Status") == "L") {
					$Con->executar(parent::marcarNaoLidaSql($Chave));
					$RMarcados++;
				
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
						
		return 'var retorno = {"selecionados":'.$RSelecionados.', "naolida":'.$RMarcados.',"mensagem":"'.implode("\\n",$Mensagem).'"}';
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
		$Campos   = array("Id", "ContatoDepartamentoCod", "AssuntoCod", "Nome", "Email", "Telefone", "Pais", "UF", "Cidade", "Mensagem", "Observacoes", "Status");
		
		//Combina Sql com Campos Definidos
		$CamposForm = array_combine($Campos, $DadosSql);
		
		//Extrai Variaveis para o metodo desejado
		$FPHP->extractVar($CamposForm, $Metodo);
	}

	public function getLegenda()
	{
		$HTML = '<table width="100%" border="0" cellpadding="3" cellspacing="0"><tr>
					 <td class="fundoLegenda bordaLeg">Legenda:</td>
					 <td align="center" class="sis_fundoInativo fTamanho bordaLeg">Mensagem Não Lida</td>
					 <td align="center" class="sis_fundoNormal fTamanho bordaLeg">Mensagem Lida</td>
				 </tr></table>';
		
		return $HTML;
	}			

}
?>