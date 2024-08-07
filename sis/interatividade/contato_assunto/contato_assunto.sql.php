<?
include_once($_SESSION['FMBase'].'filtrar.class.php');

class AssuntoSQL
{
	public function filtrarSql($ObjForm)
	{
		//Filtro Dinamico
		$Fil = new Filtrar($ObjForm);
		
		$Sql = "SELECT ContatoAssuntoCod, Assunto 
				FROM contato_assunto  
				WHERE 1 ";

		$Sql .= $Fil->getStringSql("Assunto","Assunto", "Texto");
		//Sql de Impressão
		$Sql .= $Fil->printSql("ContatoAssuntoCod",$_GET['SisReg']);

			   			
		return $Sql;
	}
	
	public function visualizarSql($Cod)
	{
		$Sql = "SELECT ContatoAssuntoCod, Assunto 
				FROM contato_assunto  
				WHERE ContatoAssuntoCod = $Cod";
		
		return $Sql;
	}

	public function cadastrarSql($ObjForm)
	{
		//Variaveis
		$VAR[] = $ObjForm->getCampoRetorna("Assunto",false,"Texto");

		$Sql = "INSERT INTO contato_assunto 
				(Assunto) VALUES 
				(%s)";
		
		return vsprintf($Sql,$VAR);
	}	
	
	public function alterarSql($ObjForm)
	{		
		//Variaveis
		$VAR[] = $ObjForm->getCampoRetorna("Assunto",false,"Texto");
		$VAR[] = $ObjForm->getCampoRetorna("Id",false,"Inteiro");

		$Sql = "UPDATE contato_assunto SET 
				Assunto = %s 
				WHERE ContatoAssuntoCod = %s";
		
		return vsprintf($Sql,$VAR);
	}

	public function getDadosSql($Id)
	{
		$Sql = "SELECT ContatoAssuntoCod, Assunto
				FROM contato_assunto 
				WHERE ContatoAssuntoCod = $Id";
		
		return $Sql;
	}
	
	public function removerSql($Cod)
	{
		$Sql = "DELETE FROM contato_assunto 
				WHERE ContatoAssuntoCod = $Cod";
		
		return $Sql;
	}
}
?>