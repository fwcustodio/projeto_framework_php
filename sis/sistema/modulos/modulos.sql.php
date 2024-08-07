<?
include_once($_SESSION['FMBase'].'filtrar.class.php');

class ModulosSQL
{
	public function filtrarSql($ObjForm)
	{
		//Filtro Dinamico
		$Fil = new Filtrar($ObjForm);
				
		$Sql = "SELECT a.ModuloCod, a.ModuloNome, a.NomeMenu, 
					   b.GrupoDesc, a.ModuloDesc, a.Posicao, 
					   if(a.VisivelMenu = 'S','Sim','Não') as VisivelMenu 
				FROM   _modulos a, _grupomodulo b  
				WHERE  a.GrupoCod = b.GrupoCod";
			   
		$Sql .= $Fil->getStringSql("NomeMenu","a.NomeMenu");
		$Sql .= $Fil->getStringSql("GrupoCod","a.GrupoCod");
		$Sql .= $Fil->getStringSql("VisivelMenu","a.VisivelMenu");
				
		return $Sql;
	}
	
	public function visualizarSql()
	{
		if(!is_array($_POST['SisReg'])) throw new Exception("Nenhum registro selecionado!");
		
		$Ids = implode(",",$_POST['SisReg']);
		
		$Sql = "SELECT a.ModuloCod, a.ModuloNome, a.NomeMenu, 
					   b.GrupoDesc, a.ModuloDesc, a.Posicao, 
					   if(a.VisivelMenu = 'S','Sim','Não') as VisivelMenu,
					   a.Help  
				FROM   _modulos a, _grupomodulo b  
				WHERE  a.GrupoCod = b.GrupoCod AND a.ModuloCod IN($Ids)";
		
		return $Sql;
	}

	public function cadastrarSql($ObjForm)
	{
		$FPHP = new FuncoesPHP();
		
		//Variaveis			
		$VAR[] = $ObjForm->getCampoRetorna('GrupoCod');
		$VAR[] = (int) $ObjForm->getCampoRetorna('Referencia');	
		$VAR[] = $ObjForm->getCampoRetorna('ModuloNome');	
		$VAR[] = $ObjForm->getCampoRetorna('NomeMenu');	
		$VAR[] = $ObjForm->getCampoRetorna('ModuloDesc');	
		$VAR[] = $ObjForm->getCampoRetorna('VisivelMenu');	
		$VAR[] = $ObjForm->getCampoRetorna('Posicao');
		$VAR[] = $ObjForm->getCampoRetorna('Help');	
		
		$Sql = "INSERT INTO _modulos (GrupoCod, ModuloReferente, ModuloNome, NomeMenu, ModuloDesc, VisivelMenu, Posicao, Help) 
				VALUES (%s,%s,'%s','%s','%s','%s',%s, '%s')";
		
		return vsprintf($Sql,$VAR);
	}	
	
	public function alterarSql($ObjForm)
	{
		$FPHP = new FuncoesPHP();
		
		//Variaveis			
		$VAR[] = $ObjForm->getCampoRetorna('GrupoCod',false,"Inteiro");
		$VAR[] = $ObjForm->getCampoRetorna('Referencia',true,"Inteiro");
		$VAR[] = $ObjForm->getCampoRetorna('ModuloNome',false,"Texto");	
		$VAR[] = $ObjForm->getCampoRetorna('NomeMenu',false,"Texto");	
		$VAR[] = $ObjForm->getCampoRetorna('ModuloDesc',false,"Texto");	
		$VAR[] = $ObjForm->getCampoRetorna('VisivelMenu',false,"Texto");	
		$VAR[] = $ObjForm->getCampoRetorna('Posicao',false,"Inteiro");
		$VAR[] = $ObjForm->getCampoRetorna('Help',true,"Texto");		
		$VAR[] = $ObjForm->getCampoRetorna('Id',false,"Inteiro");

		$Sql = "UPDATE _modulos 
				SET    GrupoCod    = %s, 
					   ModuloReferente = %s, 
					   ModuloNome  = %s, 
					   NomeMenu    = %s, 
					   ModuloDesc  = %s, 
					   VisivelMenu = %s, 
					   Posicao     = %s, 
					   Help        = %s  
			    WHERE  ModuloCod   = %s";
		
		return vsprintf($Sql,$VAR);
	}

	public function getDadosSql($Id)
	{
		$Sql = "SELECT ModuloCod, GrupoCod, ModuloReferente, ModuloNome, NomeMenu, 
					  ModuloDesc, VisivelMenu, Posicao, Help   
			   FROM   _modulos 
			   WHERE  ModuloCod = $Id ";
		
		return $Sql;
	}
	
	public function getDadosOpcoesSql($Id)
	{
		$Sql = "SELECT OpcoesModuloCod, ModuloCod, NomePermissao, IdPermissao, ImagemOn,
					   ImagemOff, AltP, AltNP, PrecisaId, Funcao, Posicao
			   FROM   _opcoes_modulo
			   WHERE  ModuloCod = $Id ";
		
		return $Sql;
	}

	public function getDadosOpcaoSql($Id)
	{
		$Sql = "SELECT OpcoesModuloCod, ModuloCod, NomePermissao, IdPermissao, ImagemOn,
					   ImagemOff, AltP, AltNP, PrecisaId, Funcao, Posicao
			   FROM   _opcoes_modulo
			   WHERE  OpcoesModuloCod = $Id ";
		
		return $Sql;
	}	
	
	public function removerSql($Cod)
	{
		$Sql = "DELETE FROM _modulos WHERE ModuloCod = $Cod";			
		
		return $Sql;
	}
	
	public function removerOpcoesSql($ModuloCod)
	{
		$Sql = "DELETE FROM _opcoes_modulo WHERE ModuloCod = $ModuloCod";			
		
		return $Sql;
	}	
	
	public function removeTipoPermissaoModulo($OpcoesModuloCod)
	{
		$Sql = "DELETE FROM _tipo_permissao WHERE OpcoesModuloCod = $OpcoesModuloCod";			
		
		return $Sql;
	}
	
	public function modulosGrupo($GrupoCod)
	{
		$Sql = "SELECT a.ModuloCod, a.NomeMenu    
				FROM   _modulos a, _grupomodulo b 
				WHERE  a.GrupoCod = b.GrupoCod  AND a.GrupoCod = $GrupoCod ";
		
		return $Sql;
	}
	
	public function permissaoModulo($ModuloCod)
	{
		$Sql = "SELECT b.TipoPermissaoCod, b.NomePermissao,  
				  FROM _permissoes a, _tipo_permissao b 
				 WHERE a.PermissaoCod = b.PermissaoCod AND 
				 	   a.ModuloCod = $ModuloCod 
			  ORDER BY b.Posicao ASC";
		
		return $Sql;
	}
	
	public function cadastrarOpcoesModuloSql($ObjForm, $ModuloCod, $Cod)
	{
		//Variaveis			
		$VAR[] = $ObjForm->getCampoRetorna('NomePermissao'.$Cod);	
		$VAR[] = $ObjForm->getCampoRetorna('IdPermissao'.$Cod);	
		$VAR[] = $ObjForm->getCampoRetorna('ImagemOn'.$Cod);	
		$VAR[] = $ObjForm->getCampoRetorna('ImagemOff'.$Cod);	
		$VAR[] = $ObjForm->getCampoRetorna('AltP'.$Cod);		
		$VAR[] = $ObjForm->getCampoRetorna('AltNP'.$Cod);
		$VAR[] = $ObjForm->getCampoRetorna('PrecisaId'.$Cod);
		$VAR[] = $ObjForm->getCampoRetorna('Funcao'.$Cod);
		$VAR[] = $ObjForm->getCampoRetorna('Pos'.$Cod);
		
		$Sql = "INSERT INTO _opcoes_modulo (ModuloCod, NomePermissao, IdPermissao, ImagemOn, 
				ImagemOff, AltP, AltNP, PrecisaId, Funcao, Posicao) VALUES ($ModuloCod,'%s','%s',
				'%s','%s','%s','%s','%s','%s','%s')";
		
		return vsprintf($Sql,$VAR);		
	}
	
	public function getPacote($GrupoCod)
	{
		$Sql = "SELECT Pacote FROM _grupomodulo WHERE GrupoCod = $GrupoCod";
		
		return $Sql;
	}
}
?>