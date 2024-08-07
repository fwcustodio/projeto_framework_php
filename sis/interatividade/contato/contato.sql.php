<?
/*
	CONTATO
*/
class ContatoSQL
{		
	/*
	$ContatoCod      - C�digo Gerado Pela Inser��o de Um Contato
	$ContatoDadosCod - C�digo Gerado pela inser��o de Dados de Um contato
	*/
	private $ContatoCod, $ContatoDadosCod;
	
	/*
		Seta o c�digo do Contato
	*/
	public function setContatoCod($ContatoCod)
	{
		$this->ContatoCod = $ContatoCod;
	}
	
	/*
		Recupera o c�digo do Contato
	*/
	public function getContatoCod()
	{
	    return $this->ContatoCod;
	}	
	
	public function setContatoDadosCod($ContatoDadosCod)
	{
	    $this->ContatoDadosCod = $ContatoDadosCod;
	}	
	
	public function getContatoDadosCod()
	{
	    return $this->ContatoDadosCod;
	}	
	
	public function visualizarContatoDadosSql($ContatoCod)
	{		
		$Sql = "SELECT a.NomeContato, a.ContatoObservacao
				FROM   contato_dados a, contato_categoria b  
				WHERE  a.ContatoCod  = b.ContatoCod AND 
					   a.ContatoCod  = $ContatoCod";
		
		return $Sql;
	}

	public function cadastrarContatoSql()
	{
		$Sql = "INSERT INTO contato (ContatoCod) VALUES (NULL)";

		return $Sql;
	}
	
	public function cadastrarContatoDadosSql($ArrayDados)
	{		
		$Sql = "INSERT INTO contato_dados
				(ContatoCod, NomeContato, ContatoObservacao, Padrao) VALUES 
				($this->ContatoCod, %s, %s, %s)";
		
		return vsprintf($Sql,$ArrayDados);
	}

	public function cadastrarTipoContatoSql($ArrayDados)
	{	
		$Sql = "INSERT INTO contato_tipo
				(ContatoDadosCod, ContatoCategoriaCod, Contato) VALUES 
				($this->ContatoDadosCod, %s, '%s')";
				
		return vsprintf($Sql,$ArrayDados);
	}		
		
	public function getContatoDadosSql($ContatoCod)
	{
		$Sql = "SELECT ContatoDadosCod, NomeContato, ContatoObservacao, Padrao 
				FROM   contato_dados  
				WHERE  ContatoCod  = $ContatoCod";
		
		return $Sql;
	}

	public function getTipoContatoSql($ContatoDadosCod)
	{
		$Sql = "SELECT ContatoTipoCod, ContatoCategoriaCod, Contato 
				FROM   contato_tipo  
				WHERE  ContatoDadosCod  = $ContatoDadosCod";
		
		return $Sql;
	}	
	
	public function removerContatoSql($ContatoCod)
	{
		$Sql = "DELETE FROM contato WHERE ContatoCod = $ContatoCod";
		
		return $Sql;
	}
	
	public function removerContatoDadosSql($ContatoCod)
	{
		$Sql = "DELETE FROM contato_dados WHERE ContatoCod = $ContatoCod";
		
		return $Sql;
	}
	
	public function removerContatoTipoSql($ContatoDadosCod)
	{
		$Sql = "DELETE FROM contato_tipo WHERE ContatoDadosCod = $ContatoDadosCod";
		
		return $Sql;
	}
}
?>