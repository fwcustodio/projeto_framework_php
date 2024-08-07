<?
/*

*/
class EnderecoSQL
{		
	private $EnderecoCod;
	
	/*
		Seta o código do endereço 
	*/
	public function setEnderecoCod($EnderecoCod)
	{
		$this->EnderecoCod = $EnderecoCod;
	}
	
	/*
		Recupera o código do endereço
	*/
	public function getEnderecoCod()
	{
	    return $this->EnderecoCod;
	}	
	
	public function visualizarEnderecoSql($EnderecoCod)
	{		
		$Sql = "SELECT b.EnderecoDadosTipo, a.Estado, a.Cidade, a.Rua, a.Numero, a.Bairro, a.CEP, a.Complemento 
				FROM   endereco_dados a, endereco_dado_tipo b
				WHERE  a.EnderecoDadosTipoCod = b.EnderecoDadosTipoCod AND 
					   a.EnderecoCod  = $EnderecoCod";
		
		return $Sql;
	}

	public function cadastrarEnderecoSql()
	{
		$Sql = "INSERT INTO endereco (EnderecoCod) VALUES (NULL)";

		return $Sql;
	}
	
	public function cadastrarEnderecoDadosSql($ArrayDados)
	{		
		$Sql = "INSERT INTO endereco_dados  
				(EnderecoCod, EnderecoDadosTipoCod, Estado, Cidade, Rua, Numero, Bairro, CEP, Complemento) VALUES 
				($this->EnderecoCod, %s, %s, %s, %s, %s, %s, %s, %s)";

		return vsprintf($Sql,$ArrayDados);
	}	
		
	public function getEnderecoDadosSql($EnderecoCod)
	{
		$Sql = "SELECT EnderecoDadosCod, EnderecoDadosTipoCod, Estado, Cidade, Rua, Numero, Bairro, CEP, Complemento 
				FROM   endereco_dados  
				WHERE  EnderecoCod  = $EnderecoCod";
		
		return $Sql;
	}
		
	public function removerEnderecoSql($Cod)
	{
		$Sql = "DELETE FROM endereco WHERE EnderecoCod = $Cod";
		
		return $Sql;
	}
	
	public function removerEnderecoDadosSql($Cod)
	{
		$Sql = "DELETE FROM endereco_dados WHERE EnderecoCod = $Cod";
		
		return $Sql;
	}
}
?>