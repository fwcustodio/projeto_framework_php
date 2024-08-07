<?
include_once($_SESSION['FMBase'].'filtrar.class.php');

class ContatoCategoriaSQL
{
	public function filtrarSql($ObjForm)
	{
		//Filtro Dinamico
		$Fil = new Filtrar($ObjForm);
		
		$Sql = "SELECT ContatoCategoriaCod, ContatoCategoria 
				FROM contato_categoria  
				WHERE 1 ";

		$Sql .= $Fil->getStringSql("ContatoCategoria","ContatoCategoria", "Texto");
		//Sql de Impressão
		$Sql .= $Fil->printSql("ContatoCategoriaCod",$_GET['SisReg']);

			   			
		return $Sql;
	}
	
	public function visualizarSql($Cod)
	{
		$Sql = "SELECT ContatoCategoriaCod, ContatoCategoria 
				FROM contato_categoria  
				WHERE ContatoCategoriaCod = $Cod";
		
		return $Sql;
	}

	public function cadastrarSql($ObjForm)
	{
		//Variaveis
		$VAR[] = $ObjForm->getCampoRetorna("ContatoCategoria",false,"Texto");

		$Sql = "INSERT INTO contato_categoria 
				(ContatoCategoria) VALUES 
				(%s)";
		
		return vsprintf($Sql,$VAR);
	}	
	
	public function alterarSql($ObjForm)
	{		
		//Variaveis
		$VAR[] = $ObjForm->getCampoRetorna("ContatoCategoria",false,"Texto");
		$VAR[] = $ObjForm->getCampoRetorna("Id",false,"Inteiro");

		$Sql = "UPDATE contato_categoria SET 
				ContatoCategoria = %s 
				WHERE ContatoCategoriaCod = %s";
		
		return vsprintf($Sql,$VAR);
	}

	public function getDadosSql($Id)
	{
		$Sql = "SELECT ContatoCategoriaCod, ContatoCategoria
				FROM contato_categoria 
				WHERE ContatoCategoriaCod = $Id";
		
		return $Sql;
	}
	
	public function removerSql($Cod)
	{
		$Sql = "DELETE FROM contato_categoria 
				WHERE ContatoCategoriaCod = $Cod";
		
		return $Sql;
	}
}
?>