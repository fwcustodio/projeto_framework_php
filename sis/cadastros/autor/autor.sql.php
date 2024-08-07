<?
include_once($_SESSION['FMBase'].'filtrar.class.php');

class AutorSQL
{
	public function filtrarSql($ObjForm)
	{
		//Filtro Dinamico
		$Fil = new Filtrar($ObjForm);
		
		$Sql = "SELECT AutorCod, AutorNome 
				FROM autor  
				WHERE 1 ";

		$Sql .= $Fil->getStringSql("AutorNome","AutorNome","Texto");
		
		//Sql de Impressão
		$Sql .= $Fil->printSql("AutorCod",$_GET['SisReg']);
			   			
		return $Sql;
	}
	
	public function visualizarSql($Cod)
	{
		$Sql = "SELECT AutorNome, AutorCod 
				FROM autor  
				WHERE AutorCod = $Cod";
		
		return $Sql;
	}

	public function cadastrarSql($ObjForm)
	{
		//Variaveis
		$VAR[] = $ObjForm->getCampoRetorna("AutorNome",true,"Texto");

		$Sql = "INSERT INTO autor 
				(AutorNome) VALUES 
				(%s)";
		
		return vsprintf($Sql,$VAR);
	}	
	
	public function alterarSql($ObjForm)
	{		
		//Variaveis
		$VAR[] = $ObjForm->getCampoRetorna("AutorNome",true,"Texto");
		$VAR[] = $ObjForm->getCampoRetorna("Id",true,"Inteiro");

		$Sql = "UPDATE autor SET 
				AutorNome = %s 
				WHERE AutorCod = %s";
		
		return vsprintf($Sql,$VAR);
	}

	public function getDadosSql($Id)
	{
		$Sql = "SELECT AutorCod, AutorNome
				FROM autor 
				WHERE AutorCod = $Id";
		
		return $Sql;
	}
	
	public function removerSql($Cod)
	{
		$Sql = "DELETE FROM autor 
				WHERE AutorCod = $Cod";
		
		return $Sql;
	}
}
?>