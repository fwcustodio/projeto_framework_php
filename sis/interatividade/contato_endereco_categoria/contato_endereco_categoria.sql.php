<?
include_once($_SESSION['FMBase'].'filtrar.class.php');

class EnderecoCategoriaSQL
{
	public function filtrarSql($ObjForm)
	{
		//Filtro Dinamico
		$Fil = new Filtrar($ObjForm);
		
		$Sql = "SELECT EnderecoDadosTipoCod, EnderecoDadosTipo 
				FROM endereco_dados_tipo  
				WHERE 1 ";

		$Sql .= $Fil->getStringSql("EnderecoDadosTipo","EnderecoDadosTipo");
		//Sql de Impressão
		$Sql .= $Fil->printSql("EnderecoDadosTipoCod",$_GET['SisReg']);

			   			
		return $Sql;
	}
	
	public function visualizarSql($Cod)
	{
		$Sql = "SELECT EnderecoDadosTipoCod, EnderecoDadosTipo 
				FROM endereco_dados_tipo  
				WHERE EnderecoDadosTipoCod = $Cod";
		
		return $Sql;
	}

	public function cadastrarSql($ObjForm)
	{
		//Variaveis
		$VAR[] = $ObjForm->getCampoRetorna("EnderecoDadosTipo",false,"Texto");

		$Sql = "INSERT INTO endereco_dados_tipo 
				(EnderecoDadosTipo) VALUES 
				(%s)";
		
		return vsprintf($Sql,$VAR);
	}	
	
	public function alterarSql($ObjForm)
	{		
		//Variaveis
		$VAR[] = $ObjForm->getCampoRetorna("EnderecoDadosTipo",false,"Texto");
		$VAR[] = $ObjForm->getCampoRetorna("Id",false,"Inteiro");

		$Sql = "UPDATE endereco_dados_tipo SET 
				EnderecoDadosTipo = %s 
				WHERE EnderecoDadosTipoCod = %s";
		
		return vsprintf($Sql,$VAR);
	}

	public function getDadosSql($Id)
	{
		$Sql = "SELECT EnderecoDadosTipoCod, EnderecoDadosTipo
				FROM endereco_dados_tipo 
				WHERE EnderecoDadosTipoCod = $Id";
		
		return $Sql;
	}
	
	public function removerSql($Cod)
	{
		$Sql = "DELETE FROM endereco_dados_tipo 
				WHERE EnderecoDadosTipoCod = $Cod";
		
		return $Sql;
	}
}
?>