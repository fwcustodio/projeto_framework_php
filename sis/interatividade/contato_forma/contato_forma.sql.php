<?
include_once($_SESSION['FMBase'].'filtrar.class.php');

class ContatoFormaSQL
{
	public function filtrarSql($ObjForm)
	{
		//Filtro Dinamico
		$Fil = new Filtrar($ObjForm);
		
		$Sql = "SELECT a.ContatoContatoCod, a.Titulo
				FROM contato_contato a
				WHERE 1";

		$Sql .= $Fil->getStringSql("Titulo","Titulo");
		//Sql de Impressão
		$Sql .= $Fil->printSql("a.ContatoContatoCod",$_GET['SisReg']);
			   			
		return $Sql;
	}
	
	public function visualizarSql($Cod)
	{
		$Sql = "SELECT a.ContatoContatoCod, a.Titulo, a.Descricao
				FROM contato_contato a
				WHERE ContatoContatoCod = $Cod";
		
		return $Sql;
	}
	
	public function infoContato($Cod)
	{
		
	/*	$Sql = "SELECT CONCAT( '<table><tr><td>',GROUP_CONCAT(c.NomeContato,':</td><td>',  d.Contato , ' (', e.ContatoCategoria, ')'
				SEPARATOR '</td></tr><tr><td>' ), '</td></tr></table>' ) AS DadosContato
				FROM contato_contato a
				LEFT JOIN contato b ON a.ContatoCod = b.ContatoCod
				LEFT JOIN contato_dados c ON a.ContatoCod = c.ContatoCod
				LEFT JOIN contato_tipo d ON c.ContatoDadosCod = d.ContatoDadosCod
				LEFT JOIN contato_categoria e ON d.ContatoCategoriaCod = e.ContatoCategoriaCod
				WHERE a.ContatoContatoCod = $Cod";
		*/
		$Sql = "SELECT Contato, e.ContatoCategoria
				FROM contato_contato a
				LEFT JOIN contato b ON a.ContatoCod = b.ContatoCod
				LEFT JOIN contato_dados c ON a.ContatoCod = c.ContatoCod
				LEFT JOIN contato_tipo d ON c.ContatoDadosCod = d.ContatoDadosCod
				LEFT JOIN contato_categoria e ON d.ContatoCategoriaCod = e.ContatoCategoriaCod
				WHERE a.ContatoContatoCod = $Cod";
	
		return $Sql;
	}
	
	public function infoEndereco($Cod)
	{
		$Sql = "SELECT IF(z.EnderecoDadosTipo IS NULL,'...',z.EnderecoDadosTipo) as EnderecoDadosTipo,
				       IF(y.Estado IS NULL,'...',y.Estado) as Estado,
				       IF(y.Cidade IS NULL,'...',y.Cidade) as Cidade,
				       IF(y.Rua IS NULL,'...',y.Rua) as Rua,
				       IF(y.Numero IS NULL,'...',y.Numero) as Numero,
				       IF(y.Bairro IS NULL,'...',y.Bairro) as Bairro,
				       IF(y.CEP IS NULL,'...',y.CEP) as CEP,
				       IF(y.Complemento IS NULL,'...',y.Complemento) as Complemento
				FROM contato_contato a , endereco_dados y, endereco_dados_tipo z
				WHERE a.ContatoContatoCod = $Cod
				AND a.EnderecoCod = y.EnderecoCod 
				AND y.EnderecoDadosTipoCod = z.EnderecoDadosTipoCod";
		return $Sql;
	}

	public function cadastrarSql($ObjForm)
	{
		//Variaveis
		$VAR[] = $ObjForm->getCampoRetorna("EnderecoCod",false,'Inteiro');
		$VAR[] = $ObjForm->getCampoRetorna("ContatoCod",false,'Inteiro');
		$VAR[] = $ObjForm->getCampoRetorna("Titulo",false,'Texto');
		$VAR[] = $ObjForm->getCampoRetorna("Descricao",false,'Texto');

		$Sql = "INSERT INTO contato_contato 
				(EnderecoCod, ContatoCod, Titulo, Descricao) VALUES 
				(%s, %s, %s, %s)";
		
		return vsprintf($Sql,$VAR);
	}	
	
	public function alterarSql($ObjForm)
	{		
		//Variaveis
		$VAR[] = $ObjForm->getCampoRetorna("Titulo",false,'Texto');
		$VAR[] = $ObjForm->getCampoRetorna("Descricao",false,'Texto');
		$VAR[] = $ObjForm->getCampoRetorna("Id");

		$Sql = "UPDATE contato_contato SET 
				Titulo = %s, Descricao = %s 
				WHERE ContatoContatoCod = %s";
		
		return vsprintf($Sql,$VAR);
	}

	public function getDadosSql($Id)
	{
		$Sql = "SELECT ContatoContatoCod, EnderecoCod, ContatoCod, Titulo, Descricao
				FROM contato_contato 
				WHERE ContatoContatoCod = $Id";
		
		return $Sql;
	}
	
	public function removerSql($Cod)
	{
		$Sql = "DELETE FROM contato_contato 
				WHERE ContatoContatoCod = $Cod";
		
		return $Sql;
	}


	public function getDadosContatoSql($ContatoCod)
	{
		$Sql = "SELECT ContatoContatoCod, EnderecoCod, ContatoCod
				FROM   contato_contato  
				WHERE  ContatoContatoCod = $ContatoCod";
		
		return $Sql;
	}
	
}
?>