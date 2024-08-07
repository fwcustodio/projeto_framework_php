<?
include_once($_SESSION['FMBase'].'grid_padrao.class.php');
include_once($_SESSION['FMBase'].'grid_visualizar.class.php');
include_once($_SESSION['DirBase'].PACOTE.'/'.MODULO.'/'.MODULO.'.sql.php');
include_once($_SESSION['FMBase'].'arquivos.class.php'); 

class Servico extends ServicoSQL
{	
	/*
	*	Seta a Código Chave	
	*	@return String
	*/
	public function getChave()
	{
		return "ServicoProdutoCod";
	}
	
	/*
	*	Retorna um array com os parametros utilizados no filtro
	*	@return Array
	*/
	public function getParametros()
	{
		$Fil = new Filtrar();
			
		$Padrao = array("PaginaAtual","QuemOrdena","TipoOrdenacao");
		
		$MeusParametros = array("ServicoCategoriaCod", "ServicoNome", "ServicoPublicar");
		
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
		$Gr->setListados(array( "ServicoNome", "ServicoCategoriaNome", "ServicoFoto"));
		$Gr->setTitulos(array( "Serviço", "Categoria", "Possui Imagem de Capa?"));
      	
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
		
		//Fundo para publicar = Nao
		$Gr->setCondicaoTodosResultados("\$R = (\$Linha['ServicoPublicar'] == 'Não' ) ? true : false;", "sis_fundoNaoPublicar");

		//Fundo para situacao = Inativo
		$Gr->setCondicaoTodosResultados("\$R = (\$Linha['ServicoSituacao'] == 'Inativo') ? true : false;", "sis_fundoInativo");
		
		//Retornando a Grid Formatada - HTML	
		return $Gr->inForm($Gr->montaGridPadrao()."<hr />".$this->getLegenda(),"FormGrid");
	}
		
	/**
	*	Monta Estrutura de Visualização dos Registros Selecionados
	*	@return String
	*/	
	public function visualizar()
	{	
		$Gr  = new GridVisualizar();

		//Grid de Visualiza? Detalhada
		$Gr->setListados(array("ServicoProdutoCod", "ServicoCategoriaNome", "ServicoNome",  "ServicoDescricao", "ServicoPublicar", "ServicoSituacao"));
		$Gr->setTitulos(array("Código", "Categoria", "Serviço",  "Descrição", "Publicar", "Situação"));

      	//Configura?s Fixas da Grid
		$Gr->setChave($this->getChave());
		
		//Retornando a Grid Formatada - HTML
		if(!is_array($_POST['SisReg'])) throw new Exception("Nenhum registro selecionado!");
		
		foreach($_POST['SisReg'] as $Cod)
		{			
	      	$Gr->setSql(parent::visualizarSql($Cod));
			$Vis .= $Gr->montaGridVisualizar();
			
			$Vis .= "<table width='100%' border='0' cellpadding='0' cellspacing='0'>";

				$Vis .= "<tr><td class='textoTituloGrid zebraB'>Imagem de Capa:</td>
							 <td class='textoGrid zebraB'>".$this->geraFotoVis($Cod)."</td>
						 </tr>";
			
			$Vis .= "<tr height=5><td></td>
							 <td></td>
						 </tr>";
			
				$Vis .= "<tr><td class='textoTituloGrid zebraB'>Imagem Homepage:</td>
							 <td class='textoGrid zebraB'>".$this->geraFotoVisHome($Cod)."</td>
						 </tr>";
				$Vis .= "<tr height=5><td></td>
							 <td></td>
						 </tr>";

			$Vis .= "</table><hr/><br />";
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
		
		//Código Gerado
		$UnidadeCod = $Con->ultimoInsertId();
		$CategoriaCod = $ObjForm->getCampoRetorna("ServicoCategoriaCod",false,"Inteiro");
		
		$this->galeriaMidiaSecao($UnidadeCod);
		$this->arquivoSecao($UnidadeCod);
		$this->cadastraFoto($CategoriaCod, $UnidadeCod);
		$this->cadastraFotoHome($CategoriaCod, $UnidadeCod);
		
		//Grava Log
		$Log->geraLog($UnidadeCod);
		
		//Finaliza Transação					
		$Con->stopTransaction();			
	}	

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


	public function cadastraFoto($CategoriaCod, $ServicoCod, $Op = NULL)
	{
		$Con = Conexao::conectar();
	
		$Arq = new Arquivos();
                
		if($Op == "Alt") 
		{
			//Retorna o codigo atual.
			$Rs = $Con->execLinha(parent::getArquivosSql($ServicoCod));
			$ServicoCategoriaAtual = $Rs['ServicoCategoriaCod'];
			$NomeArquivo = $Rs['ServicoFoto'];
			$Extensao 	 = strtolower($Arq->extenssaoArquivo($NomeArquivo));

			if($ServicoCategoriaAtual != $CategoriaCod) {

				$DestinoAtual = $_SESSION['DirBaseSite']."arquivos/servicos/".$ServicoCategoriaAtual."/".$NomeArquivo;
				$DestinoNovo  = $_SESSION['DirBaseSite']."arquivos/servicos/".$CategoriaCod."/servicos".$CategoriaCod."_".$ServicoCod.'.'.$Extensao;
				
				$DestinoAtualTB = $_SESSION['DirBaseSite']."arquivos/servicos/".$ServicoCategoriaAtual."/tb/".$NomeArquivo;
				$DestinoNovoTB  = $_SESSION['DirBaseSite']."arquivos/servicos/".$CategoriaCod."/tb/servicos".$CategoriaCod."_".$ServicoCod.'.'.$Extensao;
				
				//Seta novo parametro para categoria.
				@copy($DestinoAtual, $DestinoNovo);
				@copy($DestinoAtualTB, $DestinoNovoTB);
				@unlink($DestinoAtual);
				@unlink($DestinoAtualTB);
				$ServicoCategoriaAtual = $CategoriaCod;
				//SalvaBanco
				$NovoNome = "servicos".$CategoriaCod."_".$ServicoCod.'.'.$Extensao;
				$Con->executar(parent::alteraImagemSql($ServicoCod, $NovoNome));
				
			}

			//Se o campo Manter estiver descelecionados entra no IF
			if(empty($_POST['Manter'])) {
                                @unlink($_SESSION['DirBaseSite']."arquivos/servicos/".$ServicoCategoriaAtual."/".$NomeArquivo);
                                @unlink($_SESSION['DirBaseSite']."arquivos/servicos/".$ServicoCategoriaAtual."/tb/".$NomeArquivo);
                                $Con->executar(parent::removeImagemServicoSql($ServicoCod));
				if(empty($_FILES['ImagemServico']['name']))
					return false;
			} else {
				return false;
			}
		}

                
		
		$Quantidade = count($_FILES['ImagemServico']['name']);

		for($x=0;$x < $Quantidade; $x++) 
		{
			$Extensao = strtolower($Arq->extenssaoArquivo($_FILES['ImagemServico']['name'][$x]));

			$NomeArquivo = "servicos".$CategoriaCod."_".$ServicoCod.".".$Extensao;

			$DirBase     = $_SESSION['DirBaseSite']."arquivos/servicos/".$CategoriaCod."/".$NomeArquivo;
			$DirBaseTB   = $_SESSION['DirBaseSite']."arquivos/servicos/".$CategoriaCod."/tb/".$NomeArquivo;
			
			$Posicao = $x;
			$tamanho = getimagesize($_FILES['ImagemServico']['tmp_name'][$x]);
			$largura = $tamanho[0];
			$altura  = $tamanho[1];

			if($altura > $largura) 
			{
				if($altura > 310){
					$Arq->trataImagem("ImagemServico", $DirBase, 310, null, null, $Posicao);
				}else{
					$Arq->trataImagem("ImagemServico", $DirBase, null, null, null, $Posicao);
				}
			}else{
				if($largura > 610){
					$Arq->trataImagem("ImagemServico", $DirBase, null, 610, null, $Posicao);
				}else{
					$Arq->trataImagem("ImagemServico", $DirBase, null, null, null, $Posicao);
				}
			}
			
			//TB
			$Arq->trataImagem("ImagemServico", $DirBaseTB, null, 80, null, $Posicao);

			//SalvaBanco
			$Con->executar(parent::alteraImagemSql($ServicoCod, $NomeArquivo));
		}
	}


	public function cadastraFotoHome($CategoriaCod, $ServicoCod, $Op = NULL)
	{
		
		$Con = Conexao::conectar();
	
		$Arq = new Arquivos();
                
		if($Op == "Alt") 
		{
			//Retorna o codigo atual.
			$Rs = $Con->execLinha(parent::getArquivosSql($ServicoCod));
			$ServicoCategoriaAtual = $Rs['ServicoCategoriaCod'];
			$NomeArquivo = $Rs['ServicoFoto'];
			$Extensao 	 = strtolower($Arq->extenssaoArquivo($NomeArquivo));

			if($ServicoCategoriaAtual != $CategoriaCod) {

				$DestinoAtual = $_SESSION['DirBaseSite']."arquivos/servicos/home/".$ServicoCategoriaAtual."/".$NomeArquivo;
				$DestinoNovo  = $_SESSION['DirBaseSite']."arquivos/servicos/".$CategoriaCod."/home/servicos".$CategoriaCod."_".$ServicoCod.'.'.$Extensao;
			
				//Seta novo parametro para categoria.
				@copy($DestinoAtual, $DestinoNovo);
				@unlink($DestinoAtual);
				$ServicoCategoriaAtual = $CategoriaCod;
				//SalvaBanco
				$NovoNome = "servicos".$CategoriaCod."_".$ServicoCod.'.'.$Extensao;
				$Con->executar(parent::alteraImagemSql($ServicoCod, $NovoNome));
				
			}

			//Se o campo Manter estiver descelecionados entra no IF
			if(empty($_POST['ManterHome'])) {
                                @unlink($_SESSION['DirBaseSite']."arquivos/servicos/home/".$ServicoCategoriaAtual."/".$NomeArquivo);
                                @unlink($_SESSION['DirBaseSite']."arquivos/servicos/home/".$ServicoCategoriaAtual."/tb/".$NomeArquivo);
                                $Con->executar(parent::removeImagemServicoSqlHome($ServicoCod));
				if(empty($_FILES['ImagemServicoHomepage']['name']))
					return false;
			} else {
				return false;
			}
		}

                
		$Quantidade = count($_FILES['ImagemServicoHomepage']['name']);

		for($x=0;$x < $Quantidade; $x++) 
		{
			$Extensao = strtolower($Arq->extenssaoArquivo($_FILES['ImagemServico']['name'][$x]));

			$NomeArquivo = "servicos".$CategoriaCod."_".$ServicoCod.".".$Extensao;

			$DirBase     = $_SESSION['DirBaseSite']."arquivos/servicos/".$CategoriaCod."/home/".$NomeArquivo;
			
			$Posicao = $x;
			$tamanho = getimagesize($_FILES['ImagemServicoHomepage']['tmp_name'][$x]);
			$largura = $tamanho[0];
			$altura  = $tamanho[1];
			

			if($altura > $largura) 
			{
							
				if($altura > 190){
					$Arq->trataImagem("ImagemServicoHomepage", $DirBase, 190, 100, null, $Posicao);
				}else{
					$Arq->trataImagem("ImagemServicoHomepage", $DirBase, null, null, null, $Posicao);
				}
			}else{
				if($largura > 100){
					$Arq->trataImagem("ImagemServicoHomepage", $DirBase, 190, 100, null, $Posicao);
				}else{
					$Arq->trataImagem("ImagemServicoHomepage", $DirBase, null, null, null, $Posicao);
				}
			}
			
			//SalvaBanco
			$Con->executar(parent::alteraImagemSqlHome($ServicoCod, $NomeArquivo));
		}
	}
        
	/*
	 * Gera as fotos pequenas usadas no Visualizar
	*/
	public function geraFotoVis($Id)
	{	
		//Inicia Conexão
		$Con = Conexao::conectar();
		
		$FotosSql = $Con->executar(parent::getArquivosSql($Id));
		$Qtde	  = $Con->nLinhas($FotosSql);

		if($Qtde > 0) {
			$Foto ='<table border="0" cellspacing="0" cellpadding="0" ><tr>';
			
			while($CadaFoto = mysqli_fetch_array($FotosSql))
			{
				$Foto.= '<td style="overflow:auto"><img src="'.$_SESSION["UrlBaseSite"].'arquivos/servicos/'.$CadaFoto['ServicoCategoriaCod'].'/'.$CadaFoto['ServicoFoto'].'" border="0" width="150" heigth="150"/></td>';
			}			
			
			$Foto.='</tr></table>';
			
			return $Foto;
		} else {
			return false;	
		}
	}


	public function geraFotoVisHome($Id)
	{	
		//Inicia Conexão
		$Con = Conexao::conectar();
		
		$FotosSql = $Con->executar(parent::getArquivosSqlHome($Id));
		$Qtde	  = $Con->nLinhas($FotosSql);

		if($Qtde > 0) {
			$Foto ='<table border="0" cellspacing="0" cellpadding="0" ><tr>';
			
			while($CadaFoto = mysqli_fetch_array($FotosSql))
			{
				$Foto.= '<td style="overflow:auto"><img src="'.$_SESSION["UrlBaseSite"].'arquivos/servicos/'.$CadaFoto['ServicoCategoriaCod'].'/home/'.$CadaFoto['ServicoHome'].'" border="0" width="100" heigth="190"/></td>';
			}			
			
			$Foto.='</tr></table>';
			
			return $Foto;
		} else {
			return false;	
		}
	}

	
	
	/**
	*	Reponsável pela alteração das Informações
	*	@return Void
	*/	
	public function alterarTextoIntro($ObjForm)
	{
		//Inicia Conexão
		$Con = Conexao::conectar();
		
		//Inicia Transação		
		$Con->startTransaction();

		//Executa Sql		
		$Con->executar(parent::alterarTextoIntroSql($ObjForm));

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
		
		$Id		= $ObjForm->getCampoRetorna('Id');
		$CategoriaCod = $ObjForm->getCampoRetorna("ServicoCategoriaCod");
		$this->cadastraFoto($CategoriaCod, $Id, "Alt");
		$this->cadastraFotoHome($CategoriaCod, $Id, "Alt");

		//Executa Sql		
		$Con->executar(parent::alterarSql($ObjForm));

		$this->galeriaMidiaSecao($Id);
		$this->arquivoSecao($Id);
		
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
				$Rs = $Con->execLinha(parent::getArquivosSql($Chave));
				
				@unlink($_SESSION['DirBaseSite']."arquivos/servicos/".$Rs['ServicoCategoriaCod']."/".$Rs['ServicoFoto']."");
				@unlink($_SESSION['DirBaseSite']."arquivos/servicos/".$Rs['ServicoCategoriaCod']."/tb/".$Rs['ServicoFoto']."");
				@unlink($_SESSION['DirBaseSite']."arquivos/servicos/".$Rs['ServicoCategoriaCod']."/home/".$Rs['ServicoFoto']."");
					
				$Con->executar(parent::removerSql($Chave));
				$this->removerGaleriaMidiaSecao($Chave);
				$this->removerArquivoSecao($Chave);

				
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
		$Campos   = array("Id","ServicoPosicao", "ServicoCategoriaCod", "ServicoNome",
						  "ServicoDescricao", "ServicoPublicar", "ServicoSituacao");

		//Combina Sql com Campos Definidos
		$CamposForm = array_combine($Campos, $DadosSql);
		
		//Extrai Variaveis para o metodo desejado
		$FPHP->extractVar($CamposForm, $Metodo);
	}
	
	public function getDadosTextoIntroducao()
	{
		//Instancia Funções PHP
		$FPHP = new FuncoesPHP();
		
		//Inicia Conexão	
		$Con = Conexao::conectar();
		
		return $Con->execRLinha(parent::getDadosTexto(),"ServicoIntroducaoConteudo");
		
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
	
	public function removerGaleriaMidiaSecao($ServicoCod)
	{
		//Inicia Conexão	
		$Con = Conexao::conectar();
		
		//Remove as Enquetes Atuais
		$Con->executar(parent::removerGaleriaMidiaSecaoSql($ServicoCod));
	}
	/*GALERIA MIDIA*/
	
	
	public function bloqueiaId($SecaoCod)
	{
		$Verifica = array();
		
		return (in_array($SecaoCod, $Verifica)); 
	}
	
	public function getLegenda()
	{
		$HTML = '<table width="100%" border="0" cellpadding="3" cellspacing="0"><tr>
					 <td class="fundoLegenda bordaLeg">Legenda:</td>
					 <td align="center" class="sis_fundoNaoPublicar fTamanho bordaLeg">Serviço Não Publicado</td>
					 <td align="center" class="sis_fundoInativo fTamanho bordaLeg">Serviço Inativo</td>
					 <td align="center" class="sis_fundoNormal fTamanho bordaLeg">Serviço Ativo</td>
				 </tr></table>';
		
		return $HTML;
	}
}