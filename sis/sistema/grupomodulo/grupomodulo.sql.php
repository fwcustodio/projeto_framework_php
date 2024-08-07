<?
include_once($_SESSION['FMBase'].'filtrar.class.php');

class GrupoModuloSQL
{
	public function filtrarSql($ObjForm)
	{
		//Filtro Dinamico
		$Fil = new Filtrar($ObjForm);
							
		$Sql = "SELECT GrupoCod, GrupoDesc, Pacote, Posicao 
			    FROM   _grupomodulo  
			    WHERE  1 ";
				
		$Sql .= $Fil->getStringSql("NomeGrupo","NomeGrupo");
		$Sql .= $Fil->getStringSql("Pacote","Pacote");
				
		return $Sql;
	}
	
	public function visualizarSql()
	{
		if(!is_array($_POST['SisReg'])) throw new Exception("Nenhum registro selecionado!");
		
		$Ids = implode(",",$_POST['SisReg']);
		
		$Sql = "SELECT GrupoDesc, Pacote, Posicao 
			    FROM   _grupomodulo
			    WHERE  GrupoCod IN($Ids)";
		
		return $Sql;
	}

	public function cadastrarSql($ObjForm)
	{
		$FPHP = new FuncoesPHP();
		
		//Variaveis			
		$VAR[] = $ObjForm->getCampoRetorna('NomeGrupo');	
		$VAR[] = $ObjForm->getCampoRetorna('Pacote');
		$VAR[] = $ObjForm->getCampoRetorna('Posicao');	
		
		$Sql = "INSERT INTO _grupomodulo (GrupoDesc, Pacote, Posicao) 
				VALUES ('%s','%s','%s')";
		
		return vsprintf($Sql,$VAR);
	}	
	
	public function alterarSql($ObjForm)
	{
		$FPHP = new FuncoesPHP();
		
		//Variaveis			
		$VAR[] = $ObjForm->getCampoRetorna('NomeGrupo');	
		$VAR[] = $ObjForm->getCampoRetorna('Pacote');
		$VAR[] = $ObjForm->getCampoRetorna('Posicao');	
		$VAR[] = $ObjForm->getCampoRetorna('Id');
		
		$Sql = "UPDATE _grupomodulo  
				   SET GrupoDesc = '%s', Pacote = '%s', Posicao = '%s' 
				 WHERE GrupoCod = %s";
		
		return vsprintf($Sql,$VAR);
	}

	public function getDadosSql($Id)
	{
		$Sql = "SELECT GrupoCod, GrupoDesc, Pacote, Posicao 
			    FROM   _grupomodulo   
		        WHERE GrupoCod = $Id ";
		
		return $Sql;
	}
	
	public function removerSql($Cod)
	{
		$Sql = "DELETE FROM _grupomodulo WHERE GrupoCod = $Cod";			
		
		return $Sql;
	}
	
	public function dadosUltimoModuloSql()
	{
		$Sql = "SELECT GrupoCod as Cod, GrupoDesc as Nome
				FROM   _grupomodulo ORDER BY GrupoCod DESC LIMIT 1";
		
		return $Sql; 
	}
	
	public function grupoNivel($Nivel=0)
	{
		$Sql = "SELECT GrupoCod, GrupoDesc FROM _grupomodulo WHERE Nivel = $Nivel ORDER BY Posicao ASC";
		
		return $Sql;
	}
}
?>