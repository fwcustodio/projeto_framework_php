<?
include_once($_SESSION['FMBase'].'grid_padrao.class.php');
include_once($_SESSION['FMBase'].'grid_visualizar.class.php');
include_once($_SESSION['DirBase'].'cadastros/autor/autor.class.php');
include_once($_SESSION['DirBase'].PACOTE.'/'.MODULO.'/'.MODULO.'.sql.php');

class Secao extends SecaoSQL
{	
	/*
	*	Seta a Código Chave	
	*	@return String
	*/
	public function getChave()
	{
		return "SecaoCod";
	}


	/*
	*	Retorna um array com os parametros utilizados no filtro
	*	@return Array
	*/
	public function getParametros()
	{
		$Fil = new Filtrar();
			
		$Padrao = array("PaginaAtual","QuemOrdena","TipoOrdenacao");
		
		$MeusParametros = array("SecaoGrupoCod", "SecaoNome", "Publicar","ExibirMenu");
		
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
		$Gr->setListados(array("SecaoGrupoNome", "SecaoPai", "SecaoNome", "SecaoPosicao", "ExibirMenu", "Link"));
		$Gr->setTitulos(array("Grupo", "Seção Pai", "Nome da Seção", "Posição", "Exibir no Menu", "Link"));
      	
      	//Alinhamento
		$Gr->setAlinhamento(array("SecaoPosicao"=>"Centro","Publicar"=>"Centro","ExibirMenu"=>"Centro"));
		
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
		
		//$FPHP = new FuncoesPHP();
		
		$Gr->setObjConverte($this,"limitaUrl","Link",array("Link"));
		
		$Gr->setObjConverte($this,"manipulaPosicao","SecaoPosicao",array("SecaoCod","SecaoPosicao"));
		
		//Fundo para publicar = Nao
		$Gr->setCondicaoTodosResultados("\$R = (\$Linha['Publicar'] == 'Não' ) ? true : false;", "sis_fundoNaoPublicar");


		//Fundo para situacao = Inativo
		$Gr->setCondicaoTodosResultados("\$R = (\$Linha['Situacao'] == 'I') ? true : false;", "sis_fundoInativo");

		
		//Retornando a Grid Formatada - HTML	
		return $Gr->inForm($Gr->montaGridPadrao()."<hr />".$this->getLegenda(),"FormGrid");

	}
	
	public function limitaUrl($String)
	{
		if(empty($String)) return '<em>(não se aplica)</em>';
		
		$Limite = 30;
		
		if(strlen($String) > $Limite)
		$String = substr($String, 0, $Limite)."...";
		
		return $String;
	}
	
	/*
	*	Manipulação de posições da grid
	*/
	public function manipulaPosicao($SecaoCod, $Posicao)
	{
		return '<div style="width:15px; float:left;">
				<img src="'.$_SESSION['UrlBase'].'figuras/bullet_menos.gif" style="cursor:pointer" onclick="secaoPosicao('.$SecaoCod.',\'-\')" />
				</div><div style="width:20px; float:left;" id="sis_grid_posicao'.$SecaoCod.'"> '.$Posicao.' </div>
				<div style="width:15px; float:left;">
				<img src="'.$_SESSION['UrlBase'].'figuras/bullet_mais.gif" style="cursor:pointer" onclick="secaoPosicao('.$SecaoCod.',\'+\')" /></div>';
	}
	
	/**
	*	Monta Estrutura de Visualização dos Registros Selecionados
	*	@return String
	*/	
	public function visualizar()
	{	
		$Gr  = new GridVisualizar();

		//Grid de Visualiza? Detalhada
		$Gr->setListados(array( "SecaoGrupoNome", "SecaoPai", "SecaoNome", "SecaoPosicao", "Publicar", "ExibirMenu", "Link", "SecaoConteudo"));
		$Gr->setTitulos(array("Seção Grupo", "Pai", "Nome", "Posição", "Publicar", "Exibir no Menu", "Link","Conteudo"));

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
		
		//Inicia Classe de Autor
		$Autor = new Autor();
		
		//Inicia Transação		
		$Con->startTransaction();
		
		//Inicia Classe de Logs
		$Log = new Log();
		
		//Verifica idioma
		$SecaoPai  = $ObjForm->getCampoRetorna('SecaoPai');
		
		//Seta Autor
		$AutorCod = $Autor->novoAutorExterno($ObjForm);
		$ObjForm->setCampoRetorna("AutorCod",$AutorCod);
		
		//Executa Sql		
		$Con->executar(parent::cadastrarSql($ObjForm));
		
		//Código Gerado
		$SecaoCod = $Con->ultimoInsertId();
		
		$this->arquivoSecao($SecaoCod);
		$this->galeriaMidiaSecao($SecaoCod);
//		$this->enqueteSecao($SecaoCod);
		
		//Seta Revisão
		$this->setRevisao($SecaoCod);
		
		//Grava Log
		$Log->geraLog($SecaoCod);
		
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
		
		//Inicia Classe de Autor
		$Autor = new Autor();
		
		//Inicia Transação		
		$Con->startTransaction();
		
		//Inicia Classe de Logs
		$Log = new Log();
		
		//Seta Autor
		$AutorCod = $Autor->novoAutorExterno($ObjForm);
		$ObjForm->setCampoRetorna("AutorCod",$AutorCod);
		
		//SecaoCod
		$SecaoCod = $ObjForm->getCampoRetorna('Id');
		
		//Verifica idioma
		$SecaoPai  = $ObjForm->getCampoRetorna('SecaoPai');
		
		//Ids de Sistemas Bloqueados
		$BlockId = $this->bloqueiaId($SecaoCod);
				
		$this->verificaAteracao($ObjForm);
				
		//Bloqueia Ids de Sistema	
		$Con->executar(parent::alterarSql($ObjForm,$BlockId));
		
		//Cadastra se Existir Arquivos, Midias e Enquetes
		if($_POST['Tipo'] == 'C' and !$BlockId)
		{
			//Cadastra se Existir Arquivos, Midias e Enquetes
			$this->arquivoSecao($SecaoCod);
			$this->galeriaMidiaSecao($SecaoCod);
//			$this->enqueteSecao($SecaoCod);
		}
		else if($_POST['Tipo'] == 'L')
		{
			$this->arquivoSecao($SecaoCod);
			$this->galeriaMidiaSecao($SecaoCod);
//			$this->enqueteSecao($SecaoCod);
		}
		else 
		{
			throw new Exception("Tipo de conteúdo inválido!");
		}
		
		//Seta Revisão
		$this->setRevisao($SecaoCod);
		
		//Grava Log
		$Log->geraLog($SecaoCod);
		
		//Finaliza Transação					
		$Con->stopTransaction();			
	}
	
	public function verificaAteracao($ObjForm)
	{
		//Instancia Funções PHP
		$FPHP = new FuncoesPHP();
		
		//Inicia Conexão	
		$Con = Conexao::conectar();
		
		$Pai  = $ObjForm->getCampoRetorna("SecaoPai",false,"Inteiro");
		$Id   = $ObjForm->getCampoRetorna("Id",false,"Inteiro");

		$teste = $this->verificaFilhos($Id,$Pai);
			//var_dump($teste);
		if($teste == false) throw new Exception("Não Pode Selecionar Essa Categoria!");
	}
	
	//recursividade abstrata obscura// 
	public function verificaFilhos($Id, $Pai)
	{
		//Instancia Funções PHP
		$FPHP = new FuncoesPHP();
		
		//Inicia Conexão	
		$Con = Conexao::conectar();
		
		$Dados = $Con->executar(parent::filhosSql($Id));
		if ($Id == $Pai) return false;
		while($DadosFilhos = mysqli_fetch_array($Dados))
		{
			if ($this->verificaFilhos($DadosFilhos['SecaoCod'],$Pai) == false) {
				return false;
			}
		}
		return true;
	}
	
	//Verifica Integridade de Idioma para secao subsequente
	public function verificaIdiomaPai($SecaoPai, $IdiomaCod)
	{
		if(empty($SecaoPai)) return;
		
		$Con = Conexao::conectar();
		
		$DadosPai = $Con->execRLinha(parent::getDadosPaiSql($SecaoPai));
		
		if(!empty($DadosPai))
		{
			if($DadosPai['IdiomaCod'] <> $IdiomaCod)
			throw new Exception("Não é possível cadastrar uma seção com o idioma diferente de sua seção pai!");
		}
	}
	
	//Bloqueia Ids que não podem ser alterados a URL
	public function bloqueiaId($SecaoCod)
	{
		$Verifica = array();
		
		return (in_array($SecaoCod, $Verifica)); 
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
				$SituacaoRegistro = $Con->execRLinha(parent::getDadosSql($Chave),"Publicar");
				
				if($SituacaoRegistro == 'N')
				{
					$Con->executar(parent::publicarSql($Chave));
					
					//Seta Revisão
					$this->setRevisao($Chave);
					
					//Grava Log
					$Log->geraLog($Chave);
					
					$RPublicados++;
				}
				else 
				{
					$Mensagem[] =  $Con->execRLinha(parent::getDadosSql($Chave),"SecaoNome")." já está publicado!";
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
				$SituacaoRegistro = $Con->execRLinha(parent::getDadosSql($Chave),"Publicar");
				
				if($SituacaoRegistro == 'S')
				{
					$Con->executar(parent::naoPublicarSql($Chave));
					
					//Seta Revisão
					$this->setRevisao($Chave);
					
					//Grava Log
					$Log->geraLog($Chave);
					
					$RNaoPublicados++;
				}
				else 
				{
					$Mensagem[] =  $Con->execRLinha(parent::getDadosSql($Chave),"SecaoNome")." já estava publicado!";
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
				$BlockId = $this->bloqueiaId($Chave);
				
				if($Con->existe("secao","SecaoPai",$Chave) or $BlockId)
				{
					if($BlockId)
					{
						$Con->execRLinha(parent::getDadosSql($Chave),"SecaoNome").' é uma seção de sistema e não pode ser apagada!';	
					}
					else 
					{
						$Mensagem[] = $Con->execRLinha(parent::getDadosSql($Chave),"SecaoNome").' já possui seções dependentes!';	
					} 
				}
				else 
				{		
					
					$Con->executar(parent::removerSql($Chave));
					
					$Con->setLogOculto(true);
					
					$this->removerArquivoSecao($Chave);
					$this->removerGaleriaMidiaSecao($Chave);
				//	$this->removerEqueteSecao($Chave);
					
					$Con->executar(parent::removeRevisoesSql($Chave));
					
					$Con->setLogOculto(false);
					
					//Grava Log
					$Log->geraLog($Chave);
										
					//Incrementa Apagados
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
		$Campos   = array("Id", "SecaoGrupoCod", "SecaoPai", "AutorCod", "AutorNome", "SecaoNome", "SecaoPosicao", "Publicar", "Situacao", "ExibirMenu","MostrarFilhos","LinkTipo", "Link", "SecaoConteudo", "LinkTarget");
		
		//Combina Sql com Campos Definidos
		$CamposForm = array_combine($Campos, $DadosSql);
		
		//Extrai Variaveis para o metodo desejado
		$FPHP->extractVar($CamposForm, $Metodo);
	}
	
	/*POSICOES*/
	public function mudaPosicao($SecaoCod, $Posicao, $Operacao)
	{
		try 
		{
			//Inicia Conexão	
			$Con = Conexao::conectar();
			
			$PosicaoAtual = $Con->execRLinha(parent::getDadosSql($SecaoCod),"SecaoPosicao");
			
			if($PosicaoAtual == "99" and operacao == "+") throw new Exception("A Posição máxima é 99!");
			if($PosicaoAtual == "1"  and operacao == "-") throw new Exception("A Posição mínima é 1!");
		
			$Con->executar(parent::mudaPosicao($SecaoCod, $Operacao));
		}
		catch (Exception $E)
		{
			throw new Exception("Probelmas com a conexão impediram a atualização!".$E->getMessage());
		}	
	}
	/*POSICOES*/
	
	/*ARQUIVO*/
	public function arquivoSecao($SecaoCod)
	{
		//Inicia Conexão	
		$Con = Conexao::conectar();
		
		//Remove Todas os Arquivos de Uma seção
		$this->removerArquivoSecao($SecaoCod);
		
		//Cadastra as Novos
		$ArrayItens = $_POST['ArrayArquivoCod'];
		
		if(is_array($ArrayItens))
		{
			foreach ($ArrayItens as $Chave)
			{
				$Con->executar(parent::cadastrarArquivoSecaoSql($SecaoCod, $Chave));
			}
		}
	}
	
	public function removerArquivoSecao($SecaoCod)
	{
		//Inicia Conexão	
		$Con = Conexao::conectar();
		
		//Remove as Enquetes Atuais
		$Con->executar(parent::removerArquivoSecaoSql($SecaoCod));
	}
	
	/*GALERIA MIDIA*/
	public function galeriaMidiaSecao($SecaoCod)
	{
		//Inicia Conexão	
		$Con = Conexao::conectar();
		
		//Remove Todas as Galeria de Midia de Uma seção
		$this->removerGaleriaMidiaSecao($SecaoCod);
		
		//Cadastra as Novas
		$ArrayItens = $_POST['ArrayGaleriaMidiaCod'];
		
		if(is_array($ArrayItens))
		{
			foreach ($ArrayItens as $Chave)
			{
				$Con->executar(parent::cadastrarGaleriaMidiaSecaoSql($SecaoCod, $Chave));
			}
		}
	}
	
	public function removerGaleriaMidiaSecao($SecaoCod)
	{
		//Inicia Conexão	
		$Con = Conexao::conectar();
		
		//Remove as Enquetes Atuais
		$Con->executar(parent::removerGaleriaMidiaSecaoSql($SecaoCod));
	}
	/*GALERIA MIDIA*/
	
	
	
	
	
	
	
	
	
	/*ENQUETE*/
	public function enqueteSecao($SecaoCod)
	{
		//Inicia Conexão	
		$Con = Conexao::conectar();
		
		//Remove Todas as Enquetes de Uma Seção
		$this->removerEqueteSecao($SecaoCod);
		
		//Cadastra as Novas
		$ArrayItens = $_POST['ArrayEnqueteCod'];
		
		if(is_array($ArrayItens))
		{
			foreach ($ArrayItens as $Chave)
			{
				$Con->executar(parent::cadastrarEnqueteSecaoSql($SecaoCod, $Chave));
			}
		}
	}

	public function removerEqueteSecao($SecaoCod)
	{
		//Inicia Conexão	
		$Con = Conexao::conectar();
		
		//Remove as Enquetes Atuais
		$Con->executar(parent::removerEnqueteSecaoSql($SecaoCod));
	}
	/*ENQUETE*/
	
	
	
	
	
	
	
	
	
	/*REVISOES*/
	public function setRevisao($SecaoCod)
	{
		//Inicia Conexão	
		$Con = Conexao::conectar();
		
		$Con->executar(parent::setRevisaoSql($SecaoCod));
	}
	
	public function getRevisoes($SecaoCod)
	{
		//Inicia Conexão	
		$Con = Conexao::conectar();
		
		$RSRevisoes = $Con->executar(parent::getRevisoesSql($SecaoCod));
		$NRevisoes  = $Con->nLinhas($RSRevisoes);
		
		$Html = '<table width="100%" border="0" cellspacing="0" cellpadding="0" id="tbRevisoes">';
		
		$Cont = 0;
		while ($DadosR = mysqli_fetch_array($RSRevisoes))
		{
			$Cont +=1;
			
			$Revisao = ($Cont == $NRevisoes) ? 'Criado por '.$DadosR['UsuarioDadosNome'].' em '.$DadosR['RevisaoData'] : 'Revisado por '.$DadosR['UsuarioDadosNome'].' em '.$DadosR['RevisaoData'];
			
			$Html.= '<tr><td>'.$Revisao.'</td></tr>';
		}
		
		$Html .= '</table>';
        
		return $Html;      
	}
	/*REVISOES*/
	
	
	
	public function getLegenda()
	{
		$HTML = '<table width="100%" border="0" cellpadding="3" cellspacing="0"><tr>
					 <td class="fundoLegenda bordaLeg">Legenda:</td>
					 <td align="center" class="sis_fundoNaoPublicar fTamanho bordaLeg">Seção Não Publicada</td>
					 <td align="center" class="sis_fundoInativo fTamanho bordaLeg">Seção Inativada</td>
					 <td align="center" class="sis_fundoNormal fTamanho bordaLeg">Seção Ativa e Publicada</td>
				 </tr></table>';
		
		return $HTML;
	}			

}
?>