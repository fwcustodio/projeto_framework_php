<?
include_once($_SESSION['FMBase'].'filtrar.class.php');

class DepartamentoSQL
{
	public function filtrarSql($ObjForm)
	{
		//Filtro Dinamico
		$Fil = new Filtrar($ObjForm);
		
		$Sql = "SELECT ContatoDepartamentoCod, Departamento, 
					CASE
					WHEN Status = 'A' THEN 'Ativo'
					WHEN Status = 'I' THEN 'Inativo'
					END AS Status,
					CASE
					WHEN Finalidade = 'Co' THEN 'Contato'
					WHEN Finalidade = 'Pr' THEN 'Procura por Imóvel'
					END AS Finalidade
				FROM contato_departamento  
				WHERE 1 ";

		$Sql .= $Fil->getStringSql("Departamento","Departamento", "Texto");
		$Sql .= $Fil->getStringSql("Status","Status", "Texto");
		//Sql de Impressão
		$Sql .= $Fil->printSql("ContatoDepartamentoCod",$_GET['SisReg']);

			   			
		return $Sql;
	}
	
	public function visualizarSql($Cod)
	{
		$Sql = "SELECT ContatoDepartamentoCod, Departamento,
					CASE
					WHEN Status = 'A' THEN 'Ativo'
					WHEN Status = 'I' THEN 'Inativo'
					END AS Status, 
					CASE
					WHEN Finalidade = 'Co' THEN 'Contato'
					WHEN Finalidade = 'Pr' THEN 'Procura por Imóvel'
					END AS Finalidade
				FROM contato_departamento  
				WHERE ContatoDepartamentoCod = $Cod";
		
		return $Sql;
	}


	public function visualizarUsuariosSql($Cod)
	{
		$Sql = "SELECT b.UsuarioCod, c.Nome, c.Login, c.Email
				FROM contato_responsavel b, _usuarios c
				WHERE b.ContatoDepartamentoCod = $Cod
				AND b.UsuarioCod = c.UsuarioCod";

		return $Sql;
	}
	
	public function cadastrarSql($ObjForm)
	{
		//Variaveis
		$VAR[] = $ObjForm->getCampoRetorna("Departamento",false,"Texto");
		$VAR[] = $ObjForm->getCampoRetorna("Status",false,"Texto");

		$Sql = "INSERT INTO contato_departamento 
				(Departamento, Status) VALUES 
				(%s,%s)";
		
		return vsprintf($Sql,$VAR);
	}	
	
	public function alterarSql($ObjForm)
	{		
		//Variaveis
		$VAR[] = $ObjForm->getCampoRetorna("Departamento",false,"Texto");
		$VAR[] = $ObjForm->getCampoRetorna("Status",false,"Texto");
		$VAR[] = $ObjForm->getCampoRetorna("Id",false,"Inteiro");

		$Sql = "UPDATE contato_departamento SET 
				Departamento = %s , Status = %s
				WHERE ContatoDepartamentoCod = %s";
		
		return vsprintf($Sql,$VAR);
	}

	public function getDadosSql($Id)
	{
		$Sql = "SELECT ContatoDepartamentoCod, Departamento, Status, Finalidade
				FROM contato_departamento 
				WHERE ContatoDepartamentoCod = $Id";
		
		return $Sql;
	}
	
	public function removerSql($Cod)
	{
		$Sql = "DELETE FROM contato_departamento 
				WHERE ContatoDepartamentoCod = $Cod";
		
		return $Sql;
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	public function cadastrarDadosSql($DepartamentoCod, $UsuarioCod)
	{
		$Sql = "INSERT INTO contato_responsavel
							(ContatoDepartamentoCod, UsuarioCod) 
				VALUES 		($DepartamentoCod, $UsuarioCod)";
		
		return $Sql;
	}
	
	public function removerDadosSql($DepartamentoCod)
	{
		$Sql = "DELETE FROM contato_responsavel
				WHERE  ContatoDepartamentoCod = $DepartamentoCod";
		
		return $Sql;
	}

	public function getDadosDadosSql($DepartamentoCod)
	{
		$Sql = "SELECT ContatoResponsavelCod, ContatoDepartamentoCod, UsuarioCod
				FROM   contato_responsavel
				WHERE  ContatoDepartamentoCod = $DepartamentoCod";
		
		return $Sql;
	}
}
?>