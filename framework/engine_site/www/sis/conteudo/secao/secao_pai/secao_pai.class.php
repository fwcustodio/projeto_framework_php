<?
include_once($_SESSION['DirBase'].'conteudo/secao/secao_pai/secao_pai.sql.php');

class SecaoPai extends SecaoPaiSQL
{	
	//Atributos da Classe
	private $FPHP, $Con;
	
	//BufferHtml / Contador de Espaços
	private $Html, $ContaEspacos;
	
	//Menu
	private $HtmlMenu, $Origem, $PaiOrigem, $PasseiPelaOrigem, $ContaFilhos;
	
	//Valor a Ser Selecionado
	private $ValorSelecionado;
	
	//Construtor
	public function SecaoPai()
	{
		//Instancia Funções PHP
		$this->FPHP = new FuncoesPHP();
		
		//Inicia Conexão	
		$this->Con = Conexao::conectar();		
		
		//Html do Menu
		$this->HtmlMenu = "";
		
		//Numero de Filhos
		$this->ContaFilhos = 0;
		
		//Indica se já passou pela origem
		$this->PasseiPelaOrigem = false;
	}
	
	/*
	*	Gera Campo de Seções disponíveis em formato hierarquico
	*/
	public function geraCampoSecao($NomeCampo, $Valor, $Grupo, $Inicio=null)
	{		
		//Inicia Campo Select
		$this->Html = '<select name="'.$NomeCampo.'" id="'.$NomeCampo.'">';
		
		//Adiciona Opção Incial
		if($Valor <> '') $this->ValorSelecionado = $Valor;
		$Selecionado  = ($this->ValorSelecionado == '') ? 'selected' : '';
		$MsgSelecione = (empty($Inicio)) ? 'Selecine...' : $Inicio; 
		$this->Html  .= '<option value="" '.$Selecionado.'>'.$MsgSelecione.'</option>';
		
		//Pais Absolutos
		$RSPais = $this->Con->executar(parent::getPais($Grupo));
		
		//Percorre Pais Absolutos
		while ($DadosPais = mysqli_fetch_array($RSPais))
		{
			$this->ContaEspacos = 0;
			
			$this->montaOption($DadosPais['SecaoCod'],$DadosPais['SecaoNome']);
			
			$this->geraSubSecao($DadosPais['SecaoCod']);
		}
		
		//Final do Select
		$this->Html .= '</select>';
		
		//Retorno
		return $this->Html;
	}

	/*
	*	Gera o Menu para ser apresentado no site
	*	Versão 2.0.1 alpha
	*	Bruno A. Blank Cassol
	*
	*	Vô1
	*	[Vô2]
	*		Pai1
	*		Pai2
	*		[Pai3]		<---
	*			Filho1
	*			Filho2
	*			Filho3
	*		Pai4
	*		Pai5
	*	Vô3
	*	Vô4
	*	Vô5
	*	Vô6
	*/

	// Pega todos os menus em apenas uma query e armazena num vetor.
	// Isso pode parecer mais lento, porém é bem mais rápido e leve que
	// várias queries ao banco. CACHE IS THE KEYWORD HERE!
	public function getMenuItemCached($SecaoCod) {
		static $menus = null;
		
		// se o cache esta vazio, contrói ele
		if (is_null($menus)) {
			$menus = array();
			// FIXME: tire este * e substitua pelos campso usados
			$menuItems = $this->Con->query('SELECT * FROM secao');
			foreach ($menuItems as $menus) {
				$menus[$menuItems['SecaoCod']] = $menuItems;
			}
		}
		
		return $menus[$SecaoCod];
	}

	// Cuidado: esta função tem vida própria
	public function geraMenu2($selectedItem, $currentItem = NULL, $startItem = NULL, $endItem = NULL) {
		// apenas para a primeira chamada: se foi passado
		if (is_numeric($selectedItem)) {
			$selectedItem = $this->getMenuItemCached($selectedItem);
		}
		if (is_null($currentItem)) {
			$currentItem = $selectedItem;
		}
		if (is_null($startItem)) {
			$startItem = $this->getFirstBrotherOf($selectedItem);
		}
		if (is_null($endItem)) {
			$endItem = $this->getLastBrotherOf($selectedItem);
		}
		$items = $this->getBrothersOf($currentItem, $startItem, $endItem);
		foreach ($items as $item) {
			If ($item == $startItem) {
				$this->geraMenu();
			}
			/*print item
			if item is selectedCod:	get and print its childs
			if currCod is last item:	print its after-fathers*/
		}
	}

	
	
	/*
	*	Gera o Menu para ser apresentado no site
	*/
	public function geraMenu($SecaoCod)
	{
		if(empty($SecaoCod)) return;
		
		//Origem do menu
		$this->Origem = $SecaoCod;
		
		//Cod que deu origem a familha
		$this->PaiOrigem = $this->getPrimeiroDaFamilia($SecaoCod);
		
		//Pais do Menu Superior
		$RSPais = $this->Con->executar(parent::getPais(1));
		$NPais  = $this->Con->nLinhas($RSPais);
		
		if($NPais > 0)
		{
			$this->HtmlMenu .= '<ul>';
			
			//Percorra os pais de origem
			while ($DadosMenu = mysqli_fetch_array($RSPais))
			{
				if($DadosMenu['SecaoCod'] == $this->PaiOrigem)
				{
					$this->geraFilhos($this->PaiOrigem);
				}
				else 
				{
					$SecaoNome = ($DadosMenu['SecaoCod'] == $this->Origem) ? '<strong>'.$DadosMenu['SecaoNome'].'</strong>' : $DadosMenu['SecaoNome'];
					if(!empty($DadosMenu['Link'])) {
					   $LinkClick = ''.$DadosMenu['LinkTipo'].''.$DadosMenu['Link'].'';
					} else {
						$LinkClick = ''.$_SESSION['UrlBaseSite'].'conteudo/?SecaoCod='.$DadosMenu['SecaoCod'].'';
					}
					$this->HtmlMenu .= '<li><a href="'.$LinkClick.'">'.$SecaoNome.'</a></li>'."\n";
				}
			}
			
			$this->HtmlMenu .= '</ul>'."\n";
		}
		
		return "<div id=\"menuSite\">".$this->HtmlMenu."</div>";
	}
	
	/*
	*	Gera Filhos
	*/
	public function geraFilhos($Pai)
	{
		$DadosMenu    = $this->Con->execLinha(parent::getDadosSecao($Pai));
		$RSFilhos     = $this->Con->executar(parent::getSecoesDoPai($Pai));
		$NumeroFilhos = $this->Con->nLinhas($RSFilhos);	
				
		if($NumeroFilhos > 0)
		{
			$SecaoNome = ($DadosMenu['SecaoCod'] == $this->Origem) ? '<strong>'.$DadosMenu['SecaoNome'].'</strong>' : $DadosMenu['SecaoNome'];
			
			if($this->PaiOrigem == $DadosMenu['SecaoCod'])
			{				
				$this->HtmlMenu .= '<li><a href="'.$_SESSION['UrlBaseSite'].'conteudo/?SecaoCod='.$DadosMenu['SecaoCod'].'">'.$SecaoNome.'</a>';
			}
			else 
			{
				$this->HtmlMenu .= '<ul><li><a href="'.$_SESSION['UrlBaseSite'].'conteudo/?SecaoCod='.$DadosMenu['SecaoCod'].'">'.$SecaoNome.'</a>';
			}
			
			while ($DadosFilho = mysqli_fetch_array($RSFilhos))
			{
				if($this->Origem == $DadosMenu['SecaoCod']) $this->PasseiPelaOrigem = true;
				if($this->PasseiPelaOrigem == true) $this->ContaFilhos += 1;
				
				if($this->ContaFilhos > 1) 
				{
					$this->PasseiPelaOrigem = false; 
					$this->ContaFilhos = 0;
					//$this->HtmlMenu .= '</li>'."\n";
					//continue;
					
				}
				else 
				{
					$this->geraFilhos($DadosFilho['SecaoCod']);
				}
			}
			
			if($this->PaiOrigem == $DadosMenu['SecaoCod'])
			{
				$this->HtmlMenu .= '</li>'."\n";
			}
			else 
			{
				$this->HtmlMenu .= '</li></ul>'."\n";
			}
			
			return;
		}
		else 
		{
			$SecaoNome = ($DadosMenu['SecaoCod'] == $this->Origem) ? '<strong>'.$DadosMenu['SecaoNome'].'</strong>' : $DadosMenu['SecaoNome'];
			
			if($this->PaiOrigem == $DadosMenu['SecaoCod'])
			{
				$this->HtmlMenu .= '<li><a href="'.$_SESSION['UrlBaseSite'].'conteudo/?SecaoCod='.$DadosMenu['SecaoCod'].'">'.$SecaoNome.'</a></li>'."\n";
			}
			else 
			{
				$this->HtmlMenu .= '<ul><li><a href="'.$_SESSION['UrlBaseSite'].'conteudo/?SecaoCod='.$DadosMenu['SecaoCod'].'">'.$SecaoNome.'</a></li></ul>'."\n";
			}
			
			return;
		}	
	}
	
	/*
	*	Retorna o pai que deu origem a familia
	*/
	public function getPrimeiroDaFamilia($SecaoCod)
	{
		//Cod e Pai
		$CodPai = $this->Con->execLinha(parent::getDadosSecao($SecaoCod));		
		$Cod    = $CodPai['SecaoCod'];
		$Pai    = $CodPai['SecaoPai'];
		
		if(empty($Pai))
		{
			return $Cod;
		}
		else 
		{
			return $this->getPrimeiroDaFamilia($Pai);
		}
	}
	
	/*
	*	Gera SubSeções
	*/
	private function geraSubSecao($SecaoPai)
	{
		//Incrementa Contador de Espaços
		$this->ContaEspacos +=1;
		
		//ResultSet de SubSecoes
		$RSSubsecao = $this->Con->executar(parent::getSecoesDoPai($SecaoPai));
		
		//Percorre SebSecoes
		while ($DadosSub = mysqli_fetch_array($RSSubsecao))
		{	
			if($this->Con->existe('secao','SecaoPai',$DadosSub['SecaoCod']))
			{
				$this->montaOption($DadosSub['SecaoCod'],$DadosSub['SecaoNome']);
				
				$this->geraSubSecao($DadosSub['SecaoCod']);
				
				$this->ContaEspacos -=1;
			}
			else 
			{
				$this->montaOption($DadosSub['SecaoCod'],$DadosSub['SecaoNome']);
			}
		}
	}
	
	
	/*
	*	Encapsula seção dentro da opção html
	*/
	private function montaOption($Cod, $Nome)
	{
		$Selecionado  = ("$this->ValorSelecionado" == "$Cod") ? 'selected' : '';
		
		$this->Html .= '<option value="'.$Cod.'" '.$Selecionado.'>'.str_repeat("&nbsp;&nbsp;",$this->ContaEspacos).'&gt;'.$Nome.'</option>';
	}
}