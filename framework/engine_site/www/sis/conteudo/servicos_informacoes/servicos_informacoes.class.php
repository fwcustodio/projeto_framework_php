<?

include_once($_SESSION['FMBase'] . 'grid_padrao.class.php');
include_once($_SESSION['FMBase'] . 'grid_visualizar.class.php');
include_once($_SESSION['DirBase'] . PACOTE . '/' . MODULO . '/' . MODULO . '.sql.php');

class InfoProd extends InfoProdSQL {
    /*
     * 	Seta a Código Chave
     * 	@return String
     */

	public function getChave()
	{
		return "ServicosInformacoesCod";
	}

	/*
	*	Retorna um array com os parametros utilizados no filtro
	*	@return Array
	*/
	public function getParametros()
	{
		$Fil = new Filtrar();

		$Padrao = array("PaginaAtual","QuemOrdena","TipoOrdenacao");

		$MeusParametros = array("PortifolioInformacoesCod","Nome", "PortifolioInformacoesTipo");

		$HiddenParametros = $Fil->getHiddenParametros($MeusParametros);

		return array_merge($Padrao, $MeusParametros, $HiddenParametros);
	}

    /**
     * 	Reponsável pela filtragem dos dados na grid
     * 	@return String
     */
    public function filtrar($ObjForm) {
        $Gr = new GridPadrao();

        //Grid de Visualização- Configurações
        $Gr->setListados(array("Nome", "ServicoNome", "Status"));
        $Gr->setTitulos(array("Nome", "Título","Situacao"));

        //Setando Parametros
        Parametros::setParametros("GET", $this->getParametros());

        //Impressão
        if ($_GET['ModoPrint'] == 'true') {
            $Gr->setQLinhas(0);
            $Gr->setModoImpressao(true);
        } else {
            $Gr->setQLinhas(ConfigSIS::$CFG['QLinhasGrid']);
            $Gr->setModoImpressao(false);
        }

        //Configurações Fixas da Grid
        $Gr->setSql(parent::filtrarSql($ObjForm));
        $Gr->setChave($this->getChave());
        $Gr->setTipoOrdenacao($_GET['TipoOrdenacao']);
        $Gr->setQuemOrdena($_GET['QuemOrdena']);
        $Gr->setPaginaAtual($_GET['PaginaAtual']);

        $FPHP = new FuncoesPHP(); //Instancia Funções PHP
        //$Gr->setObjConverte($FPHP,"convertDataHora","Criacao",array("Criacao"));
        //Fundo para situacao = Inativo
        $Gr->setCondicaoTodosResultados("\$R = (\$Linha['Status'] == 'Não Lida') ? true : false;", "sis_fundoInativo");


        //Retornando a Grid Formatada - HTML
        return $Gr->inForm($Gr->montaGridPadrao() . "<hr />" . $this->getLegenda(), "FormGrid");
    }

    /**
     * 	Monta Estrutura de Visualização dos Registros Selecionados
     * 	@return String
     */
    public function visualizar() {
        $Gr = new GridVisualizar();
		
		

        //Grid de Visualiza? Detalhada
        $Gr->setListados(array("ServicosCod","ServicosInformacaoData","ServicoNome", "Nome", "Email", "Telefone","Observacoes","Mensagem","Status"));
        $Gr->setTitulos(array("Código","Data","Serviços", "Nome", "E-mail", "Telefone","Comentário","Mensagem","Situacao"));

        //Configura?s Fixas da Grid
        $Gr->setChave($this->getChave());

        //Retornando a Grid Formatada - HTML
        if (!is_array($_POST['SisReg']))
            throw new Exception("Nenhum registro selecionado!");

        foreach ($_POST['SisReg'] as $Cod) {
            $Gr->setSql(parent::visualizarSql($Cod));
            $Vis .= $Gr->montaGridVisualizar();
        }

        return $Vis;
    }

    /**
     * 	Reponsável pelo Cadastro das Informações
     * 	@return Void
     */
 	public function cadastrar($ObjForm)
	{

	}

	/**
	*	Reponsável pela alteração das Informações
	*	@return Void
	*/
	public function alterar($ObjForm)
	{

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
		$Campos   = array("Id", "ServicosInformacoesCod", "Status", "Observacoes");

		//Combina Sql com Campos Definidos
		$CamposForm = array_combine($Campos, $DadosSql);

		//Extrai Variaveis para o metodo desejado
		$FPHP->extractVar($CamposForm, $Metodo);
	}
	/*
	*	Publicar Conteúdo
	*/
	public function publicarSecao()
	{
		//Inicia Variaveis de Buffer
		$Mensagem      = array();//Array de Mensagens
		$RSelecionados = count($_POST['SisReg']);//Numero de Registros Selecionados na Grid
		$RPublicados     = 0;//Numero de Registros Publicados

		try
		{
			$Con = Conexao::conectar();

			$Con->startTransaction();

			//Inicia Classe de Logs
			$Log = new Log();

			if(!is_array($_POST['SisReg'])) throw new Exception("Nenhum registro foi selecionado!");

			foreach ($_POST['SisReg'] as $Chave)
			{
				$SituacaoRegistro = $Con->execRLinha(parent::getDadosSql($Chave),"Status");

				if($SituacaoRegistro == 'NL')
				{
					$Con->executar(parent::publicarSql($Chave));

					//Grava Log
					$Log->geraLog($Chave);

					$RPublicados++;
				}
				else
				{
					$Mensagem[] =  $Con->execRLinha(parent::getDadosSql($Chave),"Nome")." já hávia sido retirado de publicação!";
				}
			}

			$Con->stopTransaction();
		}
		catch (Exception $E)
		{
			$FPHP = new FuncoesPHP();

			$Mensagem[] = $FPHP->limpaStringJS($E->getMessage());
		}

		return 'var retorno = {"selecionados":'.$RSelecionados.', "publicados":'.$RPublicados.',"mensagem":"'.implode("\\n",$Mensagem).'"}';
	}

	/*
	*	Não Publicar Conteúdo
	*/
	public function naoPublicarSecao()
	{
		
		//Inicia Variaveis de Buffer
		$Mensagem       = array();//Array de Mensagens
		$RSelecionados  = count($_POST['SisReg']);//Numero de Registros Selecionados na Grid
		$RNaoPublicados = 0;//Numero de Registros Não Publicados

		try
		{
			$Con = Conexao::conectar();

			$Con->startTransaction();

			//Inicia Classe de Logs
			$Log = new Log();

			if(!is_array($_POST['SisReg'])) throw new Exception("Nenhum registro foi selecionado!");

			foreach ($_POST['SisReg'] as $Chave)
			{
				$SituacaoRegistro = $Con->execRLinha(parent::getDadosSql($Chave),"Status");
				
				

				if($SituacaoRegistro == 'L')
				{
					
					$Con->executar(parent::naoPublicarSql($Chave));

					//Grava Log
					$Log->geraLog($Chave);

					$RNaoPublicados++;
				}
				else
				{
					$Mensagem[] =  $Con->execRLinha(parent::getDadosSql($Chave),"Nome")." já estava publicado!";
				}
			}

			$Con->stopTransaction();
		}
		catch (Exception $E)
		{
			$FPHP = new FuncoesPHP();

			$Mensagem[] = $FPHP->limpaStringJS($E->getMessage());
		}

		return 'var retorno = {"selecionados":'.$RSelecionados.', "naoPublicados":'.$RNaoPublicados.',"mensagem":"'.implode("\\n",$Mensagem).'"}';
	}

	public function getLegenda()
	{
		$HTML = '<table width="100%" border="0" cellpadding="3" cellspacing="0"><tr>
					 <td class="fundoLegenda bordaLeg">Legenda:</td>
					 <td align="center" class="sis_fundoNaoPublicar fTamanho bordaLeg">Mensagem Não Lida</td>
					 <td align="center" class="sis_fundoInativo fTamanho bordaLeg">Mensagem Lida</td>
				 </tr></table>';

		return $HTML;
	}
}
?>