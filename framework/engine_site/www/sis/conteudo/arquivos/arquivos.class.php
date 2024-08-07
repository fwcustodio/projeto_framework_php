<?
include_once($_SESSION['FMBase'].'grid_padrao.class.php');
include_once($_SESSION['FMBase'].'grid_visualizar.class.php');
include_once($_SESSION['DirBase'].'cadastros/autor/autor.class.php');
include_once($_SESSION['FMBase'].'funcoes_php.class.php');
include_once($_SESSION['FMBase'].'arquivos.class.php');
include_once($_SESSION['DirBase'].PACOTE.'/'.MODULO.'/'.MODULO.'.sql.php');

class GaleriaArquivoA extends GaleriaArquivoASQL
{	
	/*
	*	Seta a Código Chave	
	*	@return String
	*/
	public function getChave()
	{
		return "GaleriaArquivoCod";
	}
	
	/*
	*	Retorna um array com os parametros utilizados no filtro
	*	@return Array
	*/
	public function getParametros()
	{
		$Fil = new Filtrar();
			
		$Padrao = array("PaginaAtual","QuemOrdena","TipoOrdenacao");
		
		$MeusParametros = array("Identificacao", "ArquivoData", "TipoArquivo", "Creditos", "Legenda");
		
		$HiddenParametros = $Fil->getHiddenParametros($MeusParametros);
		
		return array_merge($Padrao, $MeusParametros, $HiddenParametros);
	}

	
	public function mostraArquivo($GaleriaMidiaCod, $IdArquivo, $TipoArquivo, $Extensao)
	{
		//Inicia Conexão
		$Con = Conexao::conectar();
		
		$Html = "";
		
		$Legenda = $Con->execRLinha(parent::getDadosSql($IdArquivo),"Legenda");
		
		if($TipoArquivo == "F")
		{
			$Html = '<a href="'.$_SESSION['UrlBaseSite'].'arquivos/multimidia/'.$GaleriaMidiaCod.'/fotos/'.$IdArquivo.'.'.$Extensao.'" rel="lightbox" title="'.$Legenda.'"><img src="'.$_SESSION['UrlBaseSite'].'arquivos/multimidia/'.$GaleriaMidiaCod.'/fotos/tb/'.$IdArquivo.'.'.$Extensao.'"  border="0" style="margin:3px" ></a>';
		}
		elseif ($TipoArquivo == "V")
		{
			$URLVideo = $_SESSION['UrlBase'].'multimidia/player_video.php?Tipo=GaleriaMidia&ArquivoCod='.$IdArquivo;
			$Html     = '<a href="javascript:void(0)" onclick="popUp(\''.$URLVideo.'\',400,300, true)"><img src="'.$_SESSION['UrlBase'].'figuras/icone_video.gif" border="0" style="margin:3px" ></a>';
		}
		elseif ($TipoArquivo == "A")
		{
			$URLVideo = $_SESSION['UrlBase'].'multimidia/player_audio.php?Tipo=GaleriaMidia&ArquivoCod='.$IdArquivo;
			$Html     = '<a href="javascript:void(0)" onclick="popUp(\''.$URLVideo.'\',400,300, true)"><img src="'.$_SESSION['UrlBase'].'figuras/icone_audio.gif" border="0" style="margin:3px" ></a>';			
		}
		
		return $Html;
	}
	
	public function mostraArquivoVis($GaleriaMidiaCod, $IdArquivo, $TipoArquivo, $Extensao)
	{
		//Inicia Conexão
		$Con = Conexao::conectar();
		
		$Html = "";
		
		$Legenda = $Con->execRLinha(parent::getDadosSql($IdArquivo),"Legenda");
		
		
		if($TipoArquivo == "F")
		{
			$Html = '<img src="'.$_SESSION['UrlBaseSite'].'arquivos/multimidia/'.$GaleriaMidiaCod.'/fotos/tb/'.$IdArquivo.'.jpg"  border="0" style="margin:3px" >';
		}
		elseif ($TipoArquivo == "V")
		{
			$Html = '<img src="'.$_SESSION['UrlBase'].'figuras/icone_video.gif" border="0" style="margin:3px" >';
		}
		elseif ($TipoArquivo == "A")
		{
			$Html = '<img src="'.$_SESSION['UrlBase'].'figuras/icone_audio.gif" border="0" style="margin:3px" >';
		}
		
		return $Html;
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
		$Gr->setListados(array("Identificacao", "AutorNome", "Legenda", "DataPublicacao","IdArquivo"));
		$Gr->setTitulos(array("Galeria", "Autor", "Legenda", "Data", "Arquivo"));
      	
		//Alinhamento
		$Gr->setAlinhamento(array("IdArquivo"=>"Centro"));
      	
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
		$Gr->setNaoOrdenePor(array("IdArquivo"));
		
		//Converssões
		$Gr->setObjConverte($FPHP,"convertDataHora","DataPublicacao",array("DataPublicacao"));
		$Gr->setObjConverte($this,"mostraArquivo","IdArquivo",array("GaleriaMidiaCod", "IdArquivo", "TipoArquivo", "Extensao"));
		
		//Lightbox
		if($_GET['ModoPrint'] != 'true')
		{
			$JSL = '
			<script type="text/javascript"> 
			$(function() { $("a[@rel*=lightbox]").lightBox({fixedNavigation:true}); }); 
			
			function popUp(url, largura, altura, scroll)
			{
				var esquerda = (screen.width - largura)/2;
				var topo = (screen.height - altura)/2;
				window.open(url,"video","height=" + altura + ", width=" + largura + ", scrollbars="+ scroll +", top=" + topo + ", left=" + esquerda); 
			}
			
			</script>';
		}
			
		//Retornando a Grid Formatada - HTML
		return $Gr->inForm($Gr->montaGridPadrao().$JSL,"FormGrid");
	}
		
	/**
	*	Monta Estrutura de Visualização dos Registros Selecionados
	*	@return String
	*/	
	public function visualizar()
	{	
		$Gr   = new GridVisualizar();
		$FPHP = new FuncoesPHP();//Instancia Funções PHP

		//Grid de Visualiza? Detalhada
		$Gr->setListados(array("CodigoArquivo", "Identificacao", "AutorNome", "Legenda", "DataPublicacao","GaleriaArquivoCod"));
		$Gr->setTitulos(array("Código", "Galeria", "Autor", "Legenda", "Data","Arquivo"));
		
		//Converssões
		$Gr->setObjConverte($FPHP,"convertData","DataPublicacao",array("DataPublicacao"));
		$Gr->setObjConverte($this,"mostraArquivoVis","IdArquivo",array("GaleriaMidiaCod", "IdArquivo", "TipoArquivo","Extensao"));

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
		
		//Instancia Classe de Arquivos
		$Arq = new Arquivos();
		
		//Inicia Transação		
		$Con->startTransaction();
		
		//Inicia Classe de Logs
		$Log = new Log();

		//Log Oculto
		$Con->setLogOculto(true);
		
		//Cadastra Autor se Existir
		$AutorCod = $Autor->novoAutorMultiplo($ObjForm);
		$ObjForm->setCampoRetorna("AutorCod",$AutorCod);
		
		//Log Oculto
		$Con->setLogOculto(false);
		
		//Verifica Data
		$DataPub = $ObjForm->getCampoRetorna("DataPublicacao");
		
		if(empty($DataPub))
		{
			$ObjForm->setCampoRetorna("DataPublicacao",date("Y/m/d"));
		}
		
		if(count($_FILES['Arquivos']['name']) < 1) throw new Exception("Nenhum arquivo selecionado!");
		
		//Recupera o nome da galeria
		$GaleriaMidiaCod = $ObjForm->getCampoRetorna("GaleriaMidiaCod");
		
		foreach ($_FILES['Arquivos']['name'] as $Posicao=>$NomeArquivo)
		{					
			//Determina Tipo de Arquivo
			$Extensao = strtolower($Arq->extenssaoArquivo($NomeArquivo));
			$ObjForm->setCampoRetorna("Extensao",$Extensao);//Seta Extensao
			
			if($Extensao == "jpg" or $Extensao == "jpeg" or $Extensao == 'gif' or $Extensao == 'png')
			{
				$TipoArquivo = "F";
				$ObjForm->setCampoRetorna("TipoArquivo",$TipoArquivo);
			}
			elseif ($Extensao == "wmv" or $Extensao == "avi")
			{
				$TipoArquivo = "V";
				$ObjForm->setCampoRetorna("TipoArquivo",$TipoArquivo);
			}
			else if($Extensao == "mp3" or $Extensao == "wma")
			{
				$TipoArquivo = "A";
				$ObjForm->setCampoRetorna("TipoArquivo",$TipoArquivo);
			}
			else 
			{
				throw new Exception("Tipo de Arquivo Inválido!");
			}	
			
			//Executa Sql		
			$Con->executar(parent::cadastrarSql($ObjForm));
			
			//Recupera o Id Gerado
			$GaleriaArquivoCod = $Con->ultimoId("galeria_arquivo","GaleriaArquivoCod");
			
			//Upload de Acordo Com o Tipo
			if($TipoArquivo == "F")
			{									
				//Definindo o nome dos arquivos
				$ArquivoGrande  = $_SESSION['DirBaseSite'].'arquivos/multimidia/'.$GaleriaMidiaCod.'/fotos/'.$GaleriaArquivoCod.'.'.$Extensao;
				$ArquivoPequeno = $_SESSION['DirBaseSite'].'arquivos/multimidia/'.$GaleriaMidiaCod.'/fotos/tb/'.$GaleriaArquivoCod.'.'.$Extensao;
				
				//Upload - Imagem Grande
				$Arq->trataImagem("Arquivos", $ArquivoGrande, null, 650, null,$Posicao);
		
				//Upload - Imagem Pequena
				$Arq->trataImagem("Arquivos", $ArquivoPequeno, null, 138, null,$Posicao);
			}
			elseif($TipoArquivo == "V")
			{		
				//Definindo o nome dos arquivos
				$ArquivoVideo  = $_SESSION['DirBaseSite'].'arquivos/multimidia/'.$GaleriaMidiaCod.'/videos/'.$GaleriaArquivoCod.'.'.$Extensao;
				
				//Upload
				$Arq->upload("Arquivos",$ArquivoVideo,null,array(),$Posicao);
			}
			elseif($TipoArquivo == "A")
			{		
				//Definindo o nome dos arquivos
				$ArquivoAudio  = $_SESSION['DirBaseSite'].'arquivos/multimidia/'.$GaleriaMidiaCod.'/audios/'.$GaleriaArquivoCod.'.'.$Extensao;
				
				//Upload
				$Arq->upload("Arquivos",$ArquivoAudio,null,array(),$Posicao);
			}
			else 
			{
				throw new Exception("Tipo de Arquivo Inválido!");
			}			
				
			//Grava Log
			$Log->geraLog($GaleriaArquivoCod);
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
		
		//Inicia Classe de Autor
		$Autor = new Autor();
		
		//Inicia Transação		
		$Con->startTransaction();
		
		//Inicia Classe de Logs
		$Log = new Log();
		
		//Log Oculto
		$Con->setLogOculto(true);
		
		//Cadastra Autor se Existir
		$AutorNome = $ObjForm->getCampoRetorna("AutorNome");
		$AutorCod  = $Autor->novoAutorMultiplo($AutorNome, $ObjForm->getCampoRetorna("AutorCod"));
		$AutorCod  = $ObjForm->setCampoRetorna("AutorCod",$AutorCod);
		
		//Log Oculto
		$Con->setLogOculto(false);
		
		//Verifica Data
		$DataPub = $ObjForm->getCampoRetorna("DataPublicacao");
		if(empty($DataPub))
		{
			$ObjForm->setCampoRetorna("DataPublicacao",date("Y/m/d"));
		}
		
		//Executa Sql		
		$Con->executar(parent::alterarSql($ObjForm));
		
		//Seta o Código
		$this->GaleriaArquivoCod = $ObjForm->getCampoRetorna("Id");
		
		//Grava Log
		$Log->geraLog($ObjForm->getCampoRetorna("Id"));
		
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
			
			//Percorre Array de Registros
			foreach ($_POST['SisReg'] as $Chave)
			{		
				//Define Tipo
				$TipoArquivo = $Con->execRLinha(parent::getDadosSql($Chave),"TipoArquivo");
				
				//Define Extensão
				$Extensao = $Con->execRLinha(parent::getDadosSql($Chave),"Extensao");
				
				//Define Projeto
				$GaleriaArquivoCod = $Con->execRLinha(parent::getDadosSql($Chave),"GaleriaMidiaCod");

				//Url Apaga Arquivos
				if($TipoArquivo == "F")
				{						
					//Apaga do banco
					$Con->executar(parent::removerSql($Chave));
				
					$URLG = $_SESSION['DirBaseSite'].'arquivos/multimidia/'.$GaleriaArquivoCod.'/fotos/'.$Chave.'.'.$Extensao;
					$URLP = $_SESSION['DirBaseSite'].'arquivos/multimidia/'.$GaleriaArquivoCod.'/fotos/tb/'.$Chave.'.'.$Extensao;
				
					if(!@unlink($URLG)) $Mensagem[] = "Fisicamente o arquivo Grande ".$Chave.".".$Extensao." não pode ser removido!";
					if(!@unlink($URLP)) $Mensagem[] = "Fisicamente o arquivo Pequeno ".$Chave.".".$Extensao." não pode ser removido!";
				
					$RApagados += 1;
					
					//Grava Log
					$Log->geraLog($Chave);
					
				}
				elseif ($TipoArquivo == "V")
				{
					//Apaga do banco
					$Con->executar(parent::removerSql($Chave));
					
					$URLV = $_SESSION['DirBaseSite'].'arquivos/multimidia/'.$GaleriaArquivoCod.'/videos/'.$Chave.'.'.$Extensao;
				
					if(!@unlink($URLV)) $Mensagem[] = "Fisicamente o video ".$Chave.".".$Extensao." não pode ser removido!";
				
					$RApagados += 1;
				}
				elseif ($TipoArquivo == "A")
				{
					//Apaga do banco
					$Con->executar(parent::removerSql($Chave));
					
					$URLA = $_SESSION['DirBaseSite'].'arquivos/multimidia/'.$GaleriaArquivoCod.'/audios/'.$Chave.'.'.$Extensao;
				
					if(!@unlink($URLA)) $Mensagem[] = "Fisicamente o audio ".$Chave.".".$Extensao." não pode ser removido!";
				
					$RApagados += 1;
				}
				else 
				{
					$Mensagem[] = "Tipo de Arquivo Inválido!";
				}
			}
		
			//Finaliza Transação
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
		$Campos   = array("Id", "GaleriaMidiaCod", "Identificacao", "AutorCod", "Legenda", "AutorNome", "DataPublicacao","TipoArquivo","Extensao");
		
		//Combina Sql com Campos Definidos
		$CamposForm = array_combine($Campos, $DadosSql);
		
		//Extrai Variaveis para o metodo desejado
		$FPHP->extractVar($CamposForm, $Metodo);
	}
}
?>