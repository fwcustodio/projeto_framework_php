<?
include_once($_SESSION['FMBase'].'grid_padrao.class.php');
include_once($_SESSION['FMBase'].'grid_visualizar.class.php');
include_once($_SESSION['DirBase'].PACOTE.'/'.MODULO.'/'.MODULO.'.sql.php');
include_once($_SESSION['DirBase'].PACOTE.'/'.MODULO.'/'.MODULO.'.ver.php');

class Logs extends LogsSQL
{	
	/*
	*	Seta a Código Chave	
	*	@return String
	*/
	public function getChave()
	{
		return "LogCod";
	}
	
	/*
	*	Retorna um array com os parametros utilizados no filtro
	*	@return Array
	*/
	public function getParametros()
	{
		$Fil = new Filtrar();
			
		$Padrao = array("PaginaAtual","QuemOrdena","TipoOrdenacao");
		
		$MeusParametros = array("UsuarioCod", "ModuloCod", "Acao", "Ip", "DataLog");
		
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
		$Gr->setListados(array("UsuarioDadosNome", "NomeMenu", "Acao", "NomePermissao", "Ip", "DataLog"));
		$Gr->setTitulos(array("Usuário", "Módulo","Ação Sql", "Opção do Módulo", "Ip", "Data do Log"));
      	
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
			$Gr->setQLinhas(50);
			$Gr->setModoImpressao(false);		
		}
		     	
      	//Configurações Fixas da Grid
      	$Gr->setSql(parent::filtrarSql($ObjForm));
		$Gr->setChave($this->getChave());
		$Gr->setTipoOrdenacao($_GET['TipoOrdenacao']);
		$Gr->setQuemOrdena($_GET['QuemOrdena']);
		$Gr->setPaginaAtual($_GET['PaginaAtual']);
		
		$Gr->setObjConverte(new FuncoesPHP(),"convertDataHora","DataLog",array("DataLog"));
		
		//Retornando a Grid Formatada - HTML
		return $Gr->inForm($Gr->montaGridPadrao(),"FormGrid");
	}
		
	/**
	*	Monta Estrutura de Visualização dos Registros Selecionados
	*	@return String
	*/	
	public function visualizar()
	{	
		$VerLog = new VerLog();
		
		//Retornando a Grid Formatada - HTML
		if(!is_array($_POST['SisReg'])) throw new Exception("Nenhum registro selecionado!");
		
		$Contador = count($_POST['SisReg']);
		
		foreach($_POST['SisReg'] as $Cod)
		{			
	      	$VerLog->setLogCod($Cod);
	      	$Vis .= $VerLog->montaLog();
			
			break; //Permite Apenas 1
		}

		if($Contador > 1)  $Vis.= '<script> alert("Não é possível visualizar mais de um registro por vez!\nvisualizando apenas o primeiro registro selecionado."); </script>';
		
		return $Vis;
	}		
}
?>