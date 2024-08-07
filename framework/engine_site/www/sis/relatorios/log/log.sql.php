<?
include_once($_SESSION['FMBase'].'filtrar.class.php');

class LogsSQL
{
	public function filtrarSql($ObjForm)
	{
		//Filtro Dinamico
		$Fil = new Filtrar($ObjForm);
		
		$Sql = "SELECT a.LogCod, c.UsuarioDadosNome, 
					   e.NomeMenu, 
					   CASE
							   WHEN a.Acao = 'Cad'  THEN 'Cadastro'
							   WHEN a.Acao = 'Alt'  THEN 'Alteraчуo' 
							   WHEN a.Acao = 'Del'  THEN 'Remoчуo'
						   END as Acao, 
					   
					   f.NomePermissao, a.Ip, a.DataLog 
				
				FROM  _log a 
					  INNER JOIN _usuarios b 	ON (a.UsuarioCod = b.UsuarioCod) 
					  INNER JOIN usuario_dados c    ON (b.UsuarioCod = c.UsuarioCod) 
					  INNER JOIN _modulos e         ON (a.ModuloCod  = e.ModuloCod) 
					  INNER JOIN _opcoes_modulo f   ON (a.OpcoesModuloCod  = f.OpcoesModuloCod)
				WHERE a.LogOculto = 'N' ";

		$Sql .= $Fil->getStringSql("UsuarioCod","a.UsuarioCod");
		$Sql .= $Fil->getStringSql("ModuloCod","a.ModuloCod");
		$Sql .= $Fil->getStringSql("Acao","a.Acao");
		$Sql .= $Fil->getStringSql("Ip","a.Ip");
		$Sql .= $Fil->getStringSql("DataLog","DATE_FORMAT(a.DataLog,'%Y/%m/%d')","Data");

		//Sql de Impressуo
		$Sql .= $Fil->printSql("a.LogCod",$_GET['SisReg']);
	
		return $Sql;
	}
}
?>