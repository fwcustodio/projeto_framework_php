<?
class SecaoPaiSQL
{
	//Retorna as Seções Pais
	protected function getPais($GrupoCod)
	{
		$Sql = "SELECT SecaoCod, SecaoNome, Link, LinkTipo 
				FROM   secao 
				WHERE  SecaoPai IS NULL";
					   
		if(!empty($GrupoCod)) {
			$Sql .= " AND SecaoGrupoCod = $GrupoCod";
		}	
		
		return $Sql;
	}
	
	
	//Retorna todas as seções de um pai
	protected function getSecoesDoPai($Pai)
	{
		$Sql = "SELECT   SecaoCod, SecaoNome 
				FROM     secao 
				WHERE    1";

		if(!empty($Pai)) {
			$Sql .= " AND SecaoPai = $Pai";
		}	
		
		$Sql .= " ORDER BY SecaoPosicao ";

		return $Sql;
	}
	
	//retorna dados de uma secao
	public function getDadosSecao($SecaoCod)
	{

		$Sql = "SELECT   SecaoCod, SecaoPai, SecaoNome     
				FROM     secao 
				WHERE    1";
				
		if(!empty($SecaoCod)) {
			$Sql .= " AND SecaoCod = $SecaoCod";
		}		
		return $Sql;
	}
}