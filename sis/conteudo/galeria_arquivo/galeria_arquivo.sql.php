<?
include_once($_SESSION['FMBase'].'filtrar.class.php');

class GaleriaArquivoASQL
{
	public function filtrarSql($ObjForm)
	{
		//Filtro Dinamico
		$Fil = new Filtrar($ObjForm);
		
		$Sql = "SELECT a.ArquivoCategoriaCod,  a.ArquivoCategoriaNome, 
					   Publicar, Situacao, 
		       		   (SELECT count(ArquivoCod) 
		       		   	FROM arquivo 
		       		   	WHERE arquivo.ArquivoCategoriaCod = a.ArquivoCategoriaCod) as NArquivos		       		   
				FROM arquivo_categoria a WHERE 1";

		$Sql .= $Fil->getStringSql("ArquivoCategoriaNome","ArquivoCategoriaNome","Texto");
		$Sql .= $Fil->getStringSql("Publicar","a.Publicar","Texto");
		$Sql .= $Fil->getStringSql("ArquivoCategoriaNome","a.ArquivoCategoriaNome","Texto");
		
		//Sql de Impressão
		$Sql .= $Fil->printSql("ArquivoCategoriaCod",$_GET['SisReg']);
			   			
		return $Sql;
	}
	
	public function visualizarSql($Cod)
	{
		$Sql = "SELECT ArquivoCategoriaCod, ArquivoCategoriaNome,  
						CASE Publicar 
						   WHEN 'S' THEN 'Sim'
						   WHEN 'N' THEN 'Não'
						END  AS Publicar,
						CASE Situacao
						  WHEN 'A' THEN 'Ativo'
						  WHEN 'I' THEN 'Inativo'
						END AS Situacao
				FROM arquivo_categoria  
				WHERE ArquivoCategoriaCod = $Cod";
		
		return $Sql;
	}

	public function cadastrarSql($ObjForm)
	{
		//Variaveis
		$VAR[] = $ObjForm->getCampoRetorna("ArquivoCategoriaNome",false,"Texto");
		$VAR[] = $ObjForm->getCampoRetorna("Publicar",false,"Texto");
		$VAR[] = $ObjForm->getCampoRetorna("Situacao",false,"Texto");

		$Sql = "INSERT INTO arquivo_categoria 
				( ArquivoCategoriaNome, Publicar, Situacao) VALUES 
				( %s, %s, %s)";
		
		return vsprintf($Sql,$VAR);
	}	
	
	public function alterarSql($ObjForm)
	{		
		//Variaveis
		$VAR[] = $ObjForm->getCampoRetorna("ArquivoCategoriaNome",false,"Texto");
		$VAR[] = $ObjForm->getCampoRetorna("Publicar",false,"Texto");
		$VAR[] = $ObjForm->getCampoRetorna("Situacao",false,"Texto");
		$VAR[] = $ObjForm->getCampoRetorna("Id",false,"Inteiro");

		$Sql = "UPDATE arquivo_categoria SET 
				ArquivoCategoriaNome = %s, Publicar = %s, Situacao = %s 
				WHERE ArquivoCategoriaCod = %s";
		
		return vsprintf($Sql,$VAR);
	}

	public function getDadosSql($Id)
	{
		$Sql = "SELECT ArquivoCategoriaCod, ArquivoCategoriaNome, Publicar, Situacao
				FROM arquivo_categoria 
				WHERE ArquivoCategoriaCod = $Id";
	
		return $Sql;
	}
	
	public function removerSql($Cod)
	{
		$Sql = "DELETE FROM arquivo_categoria 
				WHERE ArquivoCategoriaCod = $Cod";
		
		return $Sql;
	}
}
?>