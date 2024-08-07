<?
class ServicoNivelSQL
{
	//Retorna as Seções Pais
	protected function getPais($GrupoCod)
	{
		$Sql = "SELECT ServicoCategoriaCod, ServicoCategoriaNome 
				FROM   servico_categoria 
				WHERE  ServicoCategoriaCodPai IS NULL";
		
		return $Sql;
	}
	
	protected function getSecoesDoPai($Pai)
	{
		$Sql = "SELECT   ServicoCategoriaCod, ServicoCategoriaNome 
				FROM     servico_categoria 
				WHERE     ServicoCategoriaCodPai = $Pai 
				ORDER BY ServicoCategoriaCod ";
		
		return $Sql;
	}
}