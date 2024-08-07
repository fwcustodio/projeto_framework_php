<?
include_once($_SESSION['FMBase'].'filtrar.class.php');

class AlterarSenhaSQL
{
	public function filtrarSql($ObjForm)
	{
		//Filtro Dinamico
		$Fil = new Filtrar($ObjForm);
		
		$Sql = "SELECT UsuarioCod, Nome, Login,
					   NumeroAcessos, UltimoAcesso
				FROM   _usuarios
			    WHERE  Status = 'A' 
				AND    UsuarioCod = ".$_SESSION['UsuarioCod']."";
			   			
		return $Sql;
	}
	
	public function visualizarSql($Cod)
	{
		$Sql = "SELECT UsuarioCod, Nome, Login, Email, DataCadastro,
					   UltimoAcesso, NumeroAcessos,
					   if(Tipo = 'A', 'Administrador', 'Usuário Comum') as Tipo
			    FROM   _usuarios
			    WHERE  Status = 'A' AND UsuarioCod = ".$_SESSION['UsuarioCod']."";
		
		return $Sql;
	}

	public function cadastrarSql($ObjForm)
	{

	}	
	
	public function alterarSql($ObjForm)
	{		
		$FPHP = new FuncoesPHP();

		$Senha = $ObjForm->getCampoRetorna('Senha');

		//Criptografando Senha
		if(!empty($Senha))
		$SenhaDeUsuario  = crypt($Senha, ConfigSIS::$CFG['StringCrypt']);

		//Variaveis
		$VAR[] = $ObjForm->getCampoRetorna('Nome');
		$VAR[] = $ObjForm->getCampoRetorna('Email');

		$Sql = "UPDATE _usuarios
				   SET Nome = '%s', Email = '%s'";

		if(!empty($SenhaDeUsuario))
		$Sql.= ", Senha = '$SenhaDeUsuario'";

		$Sql .= " WHERE UsuarioCod = ".$_SESSION['UsuarioCod']."";

		return vsprintf($Sql,$VAR);
	}

	public function getDadosSql($Id)
	{
		$Sql = "SELECT UsuarioCod, Nome, Email
				FROM _usuarios 
				WHERE UsuarioCod = $Id";
		
		return $Sql;
	}
	
	public function removerSql($Cod)
	{
	}
}
?>