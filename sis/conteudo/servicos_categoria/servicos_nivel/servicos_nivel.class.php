<?
include_once($_SESSION['DirBase']."conteudo/servicos_categoria/servicos_nivel/servicos_nivel.sql.php");

class ServicoNivel extends ServicoNivelSQL
{	
	//Atributos da Classe
	private $FPHP, $Con;
	
	//BufferHtml / Contador de Espaços
	private $Html, $ContaEspacos;
	
	//Valor a Ser Selecionado
	private $ValorSelecionado;
	
	//Construtor
	public function ServicoNivel()
	{
		//Instancia Funções PHP
		$this->FPHP = new FuncoesPHP();
		
		//Inicia Conexão	
		$this->Con = Conexao::conectar();		
	}
	
	public function geraCampoSecao($NomeCampo, $Valor,  $Inicio = NULL)
	{		
		//Inicia Campo Select
		$this->Html = '<select name="'.$NomeCampo.'" id="'.$NomeCampo.'">';
		
		//Adiciona Opção Incial
		if($Valor <> '') $this->ValorSelecionado = $Valor;
		$Selecionado  = ($this->ValorSelecionado == '') ? 'selected' : '';
		$MsgSelecione = (empty($Inicio)) ? 'Selecione uma Categoria...' : $Inicio; 
		$this->Html  .= '<option value="" '.$Selecionado.'>'.$MsgSelecione.'</option>';
		
		//Pais  Absolutos
		$RSPais = $this->Con->executar(parent::getPais($Grupo));
		
		//Percorre Pais Absolutos
		while ($DadosPais = mysqli_fetch_array($RSPais))
		{
			$this->ContaEspacos = 0;
			
			$this->montaOption($DadosPais['ServicoCategoriaCod'],$DadosPais['ServicoCategoriaNome']);
			
			$this->geraSubSecao($DadosPais['ServicoCategoriaCod']);
		}
		
		//Final do Select
		$this->Html .= '</select>';
		
		//Retorno
		return $this->Html;
	}
	
	private function geraSubSecao($CategoriaRaiz)
	{
		//Incrementa Contador de Espaços
		$this->ContaEspacos +=1;
		
		//ResultSet de SubSecoes
		$RSSubsecao = $this->Con->executar(parent::getSecoesDoPai($CategoriaRaiz));
		
		//Percorre SebSecoes
		while ($DadosSub = mysqli_fetch_array($RSSubsecao))
		{	
			if($this->Con->existe('servico_categoria','ServicoCategoriaCodPai',$DadosSub['ServicoCategoriaCod']))
			{
				$this->montaOption($DadosSub['ServicoCategoriaCod'],$DadosSub['ServicoCategoriaNome']);
				
				$this->geraSubSecao($DadosSub['ServicoCategoriaCod']);
				
				$this->ContaEspacos -=1;
			}
			else 
			{
				$this->montaOption($DadosSub['ServicoCategoriaCod'],$DadosSub['ServicoCategoriaNome']);
			}
		}
	}
	
	private function montaOption($Cod, $Nome)
	{
		$Selecionado  = ("$this->ValorSelecionado" == "$Cod") ? 'selected' : '';
		
		$this->Html .= '<option value="'.$Cod.'" '.$Selecionado.'>'.str_repeat("&nbsp;&nbsp;",$this->ContaEspacos).'&gt;'.$Nome.'</option>';
	}
}