<?
include_once($_SESSION['FMBase'].'grid_padrao.class.php');
include_once($_SESSION['FMBase'].'grid_visualizar.class.php');
include_once($_SESSION['FMBase'].'grid_click.class.php');
include_once($_SESSION['FMBase'].'funcoes_php.class.php');
include_once($_SESSION['FMBase'].'arquivos.class.php');
include_once($_SESSION['DirBase'].'conteudo/up_arquivos/up_arquivos.sql.php');
include_once($_SESSION['DirBase'].'conteudo/atualizacao_log/atualizacao_log.class.php');

class UpArquivo extends UpArquivoSQL
{		
	/*
	*	Seta a Código Chave	
	*	@return String
	*/
	public function getChave()
	{
		return "ArquivoCod";
	}
	
	/*
	*	Seta o Código usado para gravar o módulo de Ultimas Atualizações
	*	@return String
	*/
	public function getAtualizacaoModuloCod()
	{
		return 4;
	}	
	/*
	*	Retorna um array com os parametros utilizados no filtro
	*	@return Array
	*/
	public function getParametros()
	{
		$Fil = new Filtrar();
			
		$Padrao = array("PaginaAtual","QuemOrdena","TipoOrdenacao");
		
		$MeusParametros = array("IdiomaCod", "ArquivoCategoriaCod", "ArquivoNome", "DataPublicacao", "Downloads");
		
		$HiddenParametros = $Fil->getHiddenParametros($MeusParametros);
		
		return array_merge($Padrao, $MeusParametros, $HiddenParametros);
	}
		
	/**
	*	Reponsável pela filtragem dos dados na grid
	*	@return String
	*/	
	public function filtrar($ObjForm)
	{		
		$Gr   = new GridPadrao();
		$FPHP = new FuncoesPHP();//Instancia Funções PHP
		
		//Grid de Visualização- Configurações
		$Gr->setListados(array("ArquivoNome", "ArquivoCategoriaNome", "DataPublicacao", "Downloads"));
		$Gr->setTitulos(array("Nome do Arquivo", "Galeria de Arquivos", "Data de Publicacao", "Downloads"));
      	
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
		
		$Gr->setObjConverte($FPHP,"convertDataHora","DataPublicacao",array("DataPublicacao"));
		
		//Retornando a Grid Formatada - HTML
		return $Gr->inForm($Gr->montaGridPadrao(),"FormGrid");
	}

	public function filtrarPop($ObjForm)
	{		
      	$Gr  = new GridClick(); 
      	$FPHP = new FuncoesPHP();//Instancia Funções PHP
		
		//Grid de Visualização- Configurações
		$Gr->setListados(array("ArquivoNome", "ArquivoCategoriaNome", "DataPublicacao", "Downloads"));
		$Gr->setTitulos(array("Nome do Arquivo", "Galeria de Arquivos", "Data de Publicacao", "Downloads"));
      	
      	//Setando Parametros
      	Parametros::setParametros("GET", array("PaginaAtual","QuemOrdena","TipoOrdenacao", "ArquivoCategoriaNome","Publicar","IdForm","TipoCampo"));
      	     			
		//Configura?s Fixas da Grid
      	$Gr->setSql(parent::filtrarPopSql($ObjForm));
		$Gr->setChave($this->getChave());
		$Gr->setTipoOrdenacao($_GET['TipoOrdenacao']);
		$Gr->setQuemOrdena($_GET['QuemOrdena']);
		$Gr->setPaginaAtual($_GET['PaginaAtual']);
		$Gr->setQLinhas(ConfigSIS::$CFG['QLinhasGrid']);
		
		$Gr->setObjConverte($FPHP,"convertDataHora","DataPublicacao",array("DataPublicacao"));
									
		//Marcar Contas pagas
		$Gr->setCondicaoTodosResultados("return(true) ? true : false;", "mao");
		
		//Retornando a Grid Formatada - HTML
		//Arquivo Reponsável pela ação do click
		$INCJS = '<script type="text/javascript" src="'.$_SESSION['JSBase'].'js/grid_pop.js"></script>';
			
		return $Gr->inForm($Gr->montaGridClick(),"FormGrid").$INCJS;
	}
		
	/**
	*	Monta Estrutura de Visualização dos Registros Selecionados
	*	@return String
	*/	
	public function visualizar()
	{	
		$Gr = new GridVisualizar();

		//Grid de Visualiza? Detalhada
		$Gr->setListados(array("ArquivoNome", "ArquivoCategoriaNome", "ArquivoDescricao", "DataPublicacao", "Extensao", "Downloads"));
		$Gr->setTitulos(array("Nome do Arquivo", "Galeria de Arquivo", "Descrição", "Data da Publicacao", "Tipo do Arquivo","Downloads"));

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
	*	Reponsável pelo Cadastro das Informações
	*	@return Void
	*/	
	public function cadastrar($ObjForm)
	{	
		//Inicia Conexão
		$Con = Conexao::conectar();
		
		//Classe de Arquivos
		$Arq = new Arquivos();
		
		//Inicia Transação		
		$Con->startTransaction();
		
		//Inicia Classe de Logs
		$Log = new Log();


		if(count($_FILES['Arquivos']['name']) < 1) throw new Exception("Nenhum arquivo selecionado!");
		
		foreach ($_FILES['Arquivos']['name'] as $Posicao=>$NomeArquivo)
		{		
			//Determina Tipo de Arquivo
			$Extensao = strtolower($Arq->extenssaoArquivo($NomeArquivo));

			//Hash
			$Hash = @md5(time().mt_rand());	
		
		
	
			//Retorna Nome Arquivo
			$Arquivo = strtolower($NomeArquivo);
			$VetExt  = explode(".",$Arquivo);
			
			$Ext     = $VetExt[count($VetExt) - 1];
	

			$NomeArquivo = "";
			for($x=0;$x<count($VetExt)-1;$x++) 
			{
				$ConcatenaVet = ($x == count($VetExt)-2) ? "" : ".";
				$NomeArquivo .= $VetExt[$x].$ConcatenaVet;
			}
			//Retorna Nome Arquivo
		
		
			//Seta Valores no Form
			//$ObjForm->setCampoRetorna("ArquivoNome",$NomeArquivo);
			$ObjForm->setCampoRetorna("HashCod",$Hash);
			$ObjForm->setCampoRetorna("Extensao",$Extensao);
						
			//Executa Sql		
			$Con->executar(parent::cadastrarSql($ObjForm));
			
			//Código Gerado
			$UAC = $Con->ultimoId("arquivo","ArquivoCod");
						
			//Definindo o nome dos arquivos	
			$ArquivoCategoriaCod = $ObjForm->getCampoRetorna("ArquivoCategoriaCod");
			$Destino  = $_SESSION['DirBaseSite'].'arquivos/arquivos/'.$ArquivoCategoriaCod.'/'.$Hash.'.'.$Extensao;			

			$Arq->upload("Arquivos",$Destino,"", array(), $Posicao);
                        
			
			
			//Grava Log
			$Log->geraLog($UAC);
			
                        //Inicia Class Atualização
                        $Atualizacao = new AtualizacaoLog();

                        //Graça Atualização
                        $Atualizacao->geraUltimaAtualizacao($this->getAtualizacaoModuloCod(),$UAC,"A");
			
		}	

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

				$ArquivoCategoriaCod = $Con->execRLinha(parent::getDadosSql($Chave),"ArquivoCategoriaCod");
				$HashCod             = $Con->execRLinha(parent::getDadosSql($Chave),"HashCod");
				$Extensao            = $Con->execRLinha(parent::getDadosSql($Chave),"Extensao");
				$ArquivoNome         = $Con->execRLinha(parent::getDadosSql($Chave),"ArquivoNome");

                              
				if
				(
					
					$Con->existe("arquivo_servicos","ArquivoCod",$Chave) or
					$Con->existe("arquivo_secao","ArquivoCod",$Chave)
				)
				{
					$Mensagem[] = $Con->execRLinha(parent::getDadosSql($Chave),"ArquivoNome").' já esta sendo usado no sistema!';	
				}
				else 
				{
                                    
					$Con->executar(parent::removerSql($Chave));
					
					$URLArq = $_SESSION['DirBaseSite'].'arquivos/arquivos/'.$ArquivoCategoriaCod.'/'.$HashCod.'.'.$Extensao;
					
					if(!@unlink($URLArq)) $Mensagem[] = "Fisicamente o arquivo ".$ArquivoNome." não pode ser removido!";
										
					//Grava Log
					$Log->geraLog($Chave);
					
                                        $Atualizacao = new AtualizacaoLog();

                                        //Graça Atualização
                                        $Atualizacao->removerUltimaAtualizacao($this->getAtualizacaoModuloCod(),$Chave);
						
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
		//Instancia Funções PHP
		$FPHP = new FuncoesPHP();
		
		//Inicia Conexão	
		$Con = Conexao::conectar();
		
		//Extrai Dados Sql
		$DadosSql = array_values($Con->execLinhaArray(parent::getDadosSql($Id)));
		
		//Define Campos
		$Campos   = array("Id", "ArquivoCategoriaCod", "ArquivoNome", "ArquivoDescricao", "DataPublicacao", "HashCod", "Extensao", "Downloads");
		
		//Combina Sql com Campos Definidos
		$CamposForm = array_combine($Campos, $DadosSql);
		
		//Extrai Variaveis para o metodo desejado
		$FPHP->extractVar($CamposForm, $Metodo);
	}
	
	public function getNomeArquivo($ArquivoCod)
	{
		//Inicia Conexão	
		$Con = Conexao::conectar();	
		
		$Html = '<tr><td>';
		$Html .= $Con->execRLinha(parent::getDadosSql($ArquivoCod),"ArquivoNome");
		$Html .= '</td><td width="20" align="center"><input type="hidden" name="ArrayArquivoCod['.$ArquivoCod.']" id="ArrayArquivoCod'.$ArquivoCod.'" value="'.$ArquivoCod.'" /><img src="'.$_SESSION['UrlBase'].'figuras/del_2.gif" alt="Remover" onclick="$(this).parent().parent().remove();" style="cursor:pointer" /></td></tr>';
		
		return $Html;
	}

	public function getListaArquivoProduto($SecaoCod)
	{
		//Inicia Conex?o
		$Con = Conexao::conectar();	

		$RSArquivoSecao = $Con->executar(parent::getArquivoProdutoSql($SecaoCod));

		while ($DadosArquivo = mysqli_fetch_array($RSArquivoSecao))
		{
			$Html .= '<tr><td>';
			$Html .= $DadosArquivo['ArquivoNome'];
			$Html .= '</td><td width="20" align="center"><input type="hidden" name="ArrayArquivoCod['.$DadosArquivo['ArquivoCod'].']" id="ArrayArquivoCod'.$DadosArquivo['ArquivoCod'].'" value="'.$DadosArquivo['ArquivoCod'].'" /><img src="'.$_SESSION['UrlBase'].'figuras/del_2.gif" alt="Remover" onclick="$(this).parent().parent().remove();" style="cursor:pointer" /></td></tr>';
		}

		return $Html;
	}

	public function getListaArquivoSecao($SecaoCod)
	{
		//Inicia Conexão	
		$Con = Conexao::conectar();	
		
		$RSArquivoSecao = $Con->executar(parent::getArquivoSecaoSql($SecaoCod));
		
		while ($DadosArquivo = mysqli_fetch_array($RSArquivoSecao))
		{
			$Html .= '<tr><td>';
			$Html .= $DadosArquivo['ArquivoNome'];
			$Html .= '</td><td width="20" align="center"><input type="hidden" name="ArrayArquivoCod['.$DadosArquivo['ArquivoCod'].']" id="ArrayArquivoCod'.$DadosArquivo['ArquivoCod'].'" value="'.$DadosArquivo['ArquivoCod'].'" /><img src="'.$_SESSION['UrlBase'].'figuras/del_2.gif" alt="Remover" onclick="$(this).parent().parent().remove();" style="cursor:pointer" /></td></tr>';
		}
	
		return $Html;
	}
	
	public function getListaArquivoAcoes($SecaoCod)
	{
		//Inicia Conexão	
		$Con = Conexao::conectar();	
		
		$RSArquivoSecao = $Con->executar(parent::getArquivoAcoesSql($SecaoCod));
		
		while ($DadosArquivo = mysqli_fetch_array($RSArquivoSecao))
		{
			$Html .= '<tr><td>';
			$Html .= $DadosArquivo['ArquivoNome'];
			$Html .= '</td><td width="20" align="center"><input type="hidden" name="ArrayArquivoCod['.$DadosArquivo['ArquivoCod'].']" id="ArrayArquivoCod'.$DadosArquivo['ArquivoCod'].'" value="'.$DadosArquivo['ArquivoCod'].'" /><img src="'.$_SESSION['UrlBase'].'figuras/del_2.gif" alt="Remover" onclick="$(this).parent().parent().remove();" style="cursor:pointer" /></td></tr>';
		}
	
		return $Html;
	}
	
	
	public function getListaArquivoServicos($SecaoCod)
	{
		//Inicia Conexão	
		$Con = Conexao::conectar();	
		
		$RSArquivoSecao = $Con->executar(parent::getArquivoServicosSql($SecaoCod));
		
		while ($DadosArquivo = mysqli_fetch_array($RSArquivoSecao))
		{
			$Html .= '<tr><td>';
			$Html .= $DadosArquivo['ArquivoNome'];
			$Html .= '</td><td width="20" align="center"><input type="hidden" name="ArrayArquivoCod['.$DadosArquivo['ArquivoCod'].']" id="ArrayArquivoCod'.$DadosArquivo['ArquivoCod'].'" value="'.$DadosArquivo['ArquivoCod'].'" /><img src="'.$_SESSION['UrlBase'].'figuras/del_2.gif" alt="Remover" onclick="$(this).parent().parent().remove();" style="cursor:pointer" /></td></tr>';
		}
	
		return $Html;
	}
	
	public function getListaArquivoEvento($EventoCod)
	{
		//Inicia Conexão	
		$Con = Conexao::conectar();	
		
		$RSArquivoEvento = $Con->executar(parent::getArquivoEventoSql($EventoCod));
		
		while ($DadosArquivo = mysqli_fetch_array($RSArquivoEvento))
		{
			$Html .= '<tr><td>';
			$Html .= $DadosArquivo['ArquivoNome'];
			$Html .= '</td><td width="20" align="center"><input type="hidden" name="ArrayArquivoCod['.$DadosArquivo['ArquivoCod'].']" id="ArrayArquivoCod'.$DadosArquivo['ArquivoCod'].'" value="'.$DadosArquivo['ArquivoCod'].'" /><img src="'.$_SESSION['UrlBase'].'figuras/del_2.gif" alt="Remover" onclick="$(this).parent().parent().remove();" style="cursor:pointer" /></td></tr>';
		}
	
		return $Html;
	}

	public function getListaArquivoPublicacao($PublicacaoCod)
	{
		//Inicia Conexão	
		$Con = Conexao::conectar();	
		
		$RSArquivoPublicacao = $Con->executar(parent::getArquivoPublicacaoSql($PublicacaoCod));
		
		while ($DadosArquivo = mysqli_fetch_array($RSArquivoPublicacao))
		{
			$Html .= '<tr><td>';
			$Html .= $DadosArquivo['ArquivoNome'];
			$Html .= '</td><td width="20" align="center"><input type="hidden" name="ArrayArquivoCod['.$DadosArquivo['ArquivoCod'].']" id="ArrayArquivoCod'.$DadosArquivo['ArquivoCod'].'" value="'.$DadosArquivo['ArquivoCod'].'" /><img src="'.$_SESSION['UrlBase'].'figuras/del_2.gif" alt="Remover" onclick="$(this).parent().parent().remove();" style="cursor:pointer" /></td></tr>';
		}
	
		return $Html;
	}

        public function getListaArquivoPortifolio($SecaoCod){
            		//Inicia Conexão
		$Con = Conexao::conectar();

		$RSArquivoSecao = $Con->executar(parent::getListaArquivoPortifolioSql($SecaoCod));

		while ($DadosArquivo = mysqli_fetch_array($RSArquivoSecao))
		{
			$Html .= '<tr><td>';
			$Html .= $DadosArquivo['ArquivoNome'];
			$Html .= '</td><td width="20" align="center"><input type="hidden" name="ArrayArquivoCod['.$DadosArquivo['ArquivoCod'].']" id="ArrayArquivoCod'.$DadosArquivo['ArquivoCod'].'" value="'.$DadosArquivo['ArquivoCod'].'" /><img src="'.$_SESSION['UrlBase'].'figuras/del_2.gif" alt="Remover" onclick="$(this).parent().parent().remove();" style="cursor:pointer" /></td></tr>';
		}

		return $Html;
        }
}