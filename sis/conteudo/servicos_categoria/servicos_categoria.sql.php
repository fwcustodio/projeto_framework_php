<?
include_once($_SESSION['FMBase'].'filtrar.class.php');

class CatServSQL
{
	public function filtrarSql($ObjForm)
	{
		//Filtro Dinamico
		$Fil = new Filtrar($ObjForm);
		
		$Sql = "SELECT a.ServicoCategoriaCod, a.ServicoCategoriaNome, a.ServicoCategoriaCodPai
				FROM (
				      /* Os pais */
				      (
				          SELECT a.ServicoCategoriaCod, a.ServicoCategoriaNome, IFNULL(a.ServicoCategoriaCodPai, '..') AS ServicoCategoriaCodPai
				          FROM servico_categoria a
				          WHERE a.ServicoCategoriaCodPai IS NULL
				      )
				      UNION
				      /* Os filhos */
				      (
				          SELECT a.ServicoCategoriaCod, a.ServicoCategoriaNome, b.ServicoCategoriaPai
				          FROM servico_categoria a, (
				              SELECT b.ServicoCategoriaNome AS ServicoCategoriaPai, b.ServicoCategoriaCod
				              FROM servico_categoria b
				              WHERE 1
				          ) AS b
				          WHERE a.ServicoCategoriaCodPai = b.ServicoCategoriaCod
				      )
				)AS a
				WHERE 1";

		$Sql .= $Fil->getStringSql("ServicoCategoriaNome","a.ServicoCategoriaNome", "Texto");
		
		//Sql de Impressão
		$Sql .= $Fil->printSql("ServicoCategoriaCod",$_GET['SisReg']);
			   			
		return $Sql;
	}
	
	public function visualizarSql($Cod)
	{
		$Sql = "SELECT a.ServicoCategoriaCod, a.ServicoCategoriaNome, a.ServicoCategoriaCodPai
				FROM (
				      /* Os pais */
				      (
				          SELECT a.ServicoCategoriaCod, a.ServicoCategoriaNome, IFNULL(a.ServicoCategoriaCodPai, '..') AS ServicoCategoriaCodPai
				          FROM servico_categoria a
				          WHERE a.ServicoCategoriaCodPai IS NULL
				      )
				      UNION
				      /* Os filhos */
				      (
				          SELECT a.ServicoCategoriaCod, a.ServicoCategoriaNome, b.ServicoCategoriaPai
				          FROM servico_categoria a, (
				              SELECT b.ServicoCategoriaNome AS ServicoCategoriaPai, b.ServicoCategoriaCod
				              FROM servico_categoria b
				              WHERE 1
				          ) AS b
				          WHERE a.ServicoCategoriaCodPai = b.ServicoCategoriaCod
				      )
				)AS a
				WHERE ServicoCategoriaCod = $Cod";
		
		return $Sql;
	}

	public function cadastrarSql($ObjForm)
	{
		//Variaveis
		$VAR[] = $ObjForm->getCampoRetorna("ServicoCategoriaNome",false,"Texto");
		$VAR[] = $ObjForm->getCampoRetorna("CategoriaPai",true,"Inteiro");
		//$VAR[] = $ObjForm->getCampoRetorna("Posicao",false,"Inteiro");
		
		$Sql = "INSERT INTO servico_categoria 
				(ServicoCategoriaNome, ServicoCategoriaCodPai) VALUES 
				(%s, %s)";
		
		return vsprintf($Sql,$VAR);
	}	
	
	public function alterarSql($ObjForm)
	{		
		//Variaveis
		$VAR[] = $ObjForm->getCampoRetorna("ServicoCategoriaNome",false,"Texto");
		$VAR[] = $ObjForm->getCampoRetorna("CategoriaPai",true,"Inteiro");
		//	$VAR[] = $ObjForm->getCampoRetorna("Posicao",false,"Inteiro");
		$VAR[] = $ObjForm->getCampoRetorna("Id",false,"Inteiro");

		$Sql = "UPDATE servico_categoria SET 
				ServicoCategoriaNome = %s,  
				ServicoCategoriaCodPai = %s
				WHERE ServicoCategoriaCod = %s";
		
		return vsprintf($Sql,$VAR);
	}

	public function getDadosSql($Id)
	{
		$Sql = "SELECT ServicoCategoriaCod, ServicoCategoriaCodPai, 
					   ServicoCategoriaNome, CategoriaPosicao
				FROM   servico_categoria 
				WHERE  ServicoCategoriaCod = $Id";
		
		return $Sql;
	}
	
	public function removerSql($Cod)
	{
		$Sql = "DELETE FROM servico_categoria WHERE ServicoCategoriaCod = $Cod";
		
		return $Sql;
	}
	
	public function filhosSql($Id)
	{
		$Sql = "SELECT ServicoCategoriaCod 
				FROM servico_categoria 
				WHERE ServicoCategoriaCodPai = $Id";
		
		return $Sql;
	}
}
?>