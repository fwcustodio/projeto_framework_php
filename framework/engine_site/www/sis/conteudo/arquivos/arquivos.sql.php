<?
include_once($_SESSION['FMBase'].'filtrar.class.php');

class GaleriaArquivoASQL
{
	public function filtrarSql($ObjForm)
	{
		//Filtro Dinamico
		$Fil 	 = new Filtrar($ObjForm);
		$Legenda = $ObjForm->getCampoFiltro("Legenda", "Texto");
		
		$Sql = "SELECT a.GaleriaArquivoCod, b.GaleriaMidiaCod,
					   b.GaleriaNome AS Identificacao,
					   (if(c.AutorNome IS NULL,'<em>(Não Informado)</em>',c.AutorNome)) as AutorNome,
					   a.AutorCod, 
					   (if(a.Legenda IS NULL, '<em>(Não Possui)</em>',a.Legenda)) as Legenda, 
					   DATE_FORMAT(a.DataPublicacao, '%Y-%m-%d') AS DataPublicacao,
					   a.GaleriaArquivoCod as IdArquivo, 
					   a.TipoArquivo, a.Extensao   				
				FROM   galeria_arquivo a 
					   INNER JOIN galeria_midia b ON (a.GaleriaMidiaCod = b.GaleriaMidiaCod)
					   LEFT JOIN autor c ON (a.AutorCod = c.AutorCod) 
				WHERE 1 ";
				
		$Sql .= $Fil->getStringSql("GaleriaNome","b.GaleriaNome","Texto");
		$Sql .= $Fil->getStringSql("AutorNome","c.AutorNome","Texto");
		
		if(!empty($Legenda)) $Sql .= " AND a.Legenda LIKE '%".$Legenda."%'";
		
		$Sql .= $Fil->getStringSql("ArquivoData","a.DataPublicacao","Data");
		$Sql .= $Fil->getStringSql("TipoArquivo","a.TipoArquivo","Texto");
		
		//Sql de Impressão
		$Sql .= $Fil->printSql("a.GaleriaArquivoCod",$_GET['SisReg']);

		return $Sql;
	}
	
	public function visualizarSql($Cod)
	{
		$Sql = "SELECT a.GaleriaArquivoCod AS CodigoArquivo, a.GaleriaArquivoCod, b.GaleriaMidiaCod,
					   b.GaleriaNome AS Identificacao,
					   (if(c.AutorNome IS NULL,'<em>(Não Informado)</em>',c.AutorNome)) as AutorNome, 
					   a.AutorCod, 
					   (if(a.Legenda IS NULL, '<em>(Não Possui)</em>',a.Legenda)) as Legenda, 
					   DATE_FORMAT(a.DataPublicacao, '%Y/%m/%d') AS DataPublicacao,
					   CONCAT(a.GaleriaArquivoCod, '.', a.Extensao ) AS GaleriaArquivoCod, 
					   a.TipoArquivo, a.Extensao   
				
				FROM   galeria_arquivo a 
					   INNER JOIN galeria_midia b ON (a.GaleriaMidiaCod = b.GaleriaMidiaCod)
					   LEFT JOIN autor c ON (a.AutorCod = c.AutorCod) 
				WHERE  a.GaleriaArquivoCod = $Cod";
		
		return $Sql;
	}

	public function cadastrarSql($ObjForm)
	{
		//Variaveis
		$VAR[] = $ObjForm->getCampoRetorna("GaleriaMidiaCod",true,"Inteiro");
		$VAR[] = $ObjForm->getCampoRetorna("AutorCod",true,"Texto");
		$VAR[] = $ObjForm->getCampoRetorna("Legenda",true,"Texto");
		$VAR[] = $ObjForm->getCampoRetorna("DataPublicacao",true,"Data");
		$VAR[] = $ObjForm->getCampoRetorna("TipoArquivo",true,"Texto");
		$VAR[] = $ObjForm->getCampoRetorna("Extensao",true,"Texto");

		$Sql = "INSERT INTO galeria_arquivo 
				(GaleriaMidiaCod, AutorCod, Legenda, DataPublicacao, TipoArquivo, Extensao) VALUES 
				(%s, %s, %s, %s, %s, %s)";
		
		return vsprintf($Sql,$VAR);
	}	
	
	public function alterarSql($ObjForm)
	{		
		//Variaveis
		$VAR[] = $ObjForm->getCampoRetorna("Legenda",true,"Texto");
		$VAR[] = $ObjForm->getCampoRetorna("DataPublicacao",false,"Data");
		$VAR[] = $ObjForm->getCampoRetorna("AutorCod",true,"Texto");
		$VAR[] = $ObjForm->getCampoRetorna("Id");

		$Sql = "UPDATE galeria_arquivo SET 
				Legenda = %s, DataPublicacao = %s, AutorCod = %s
				WHERE GaleriaArquivoCod = %s";

		
		return vsprintf($Sql,$VAR);
	}

	public function getDadosSql($Id)
	{
		$Sql = "SELECT a.GaleriaArquivoCod, a.GaleriaMidiaCod, c.GaleriaNome AS Identificacao,
					   a.AutorCod, a.Legenda, b.AutorNome,
					   DATE_FORMAT(a.DataPublicacao,'%d/%m/%Y') DataPublicacao, a.TipoArquivo,
					   a.Extensao 
				FROM   galeria_arquivo a LEFT JOIN autor b ON a.AutorCod = b.AutorCod, galeria_midia c
				WHERE  a.GaleriaMidiaCod = c.GaleriaMidiaCod  AND a.GaleriaArquivoCod = $Id";

		return $Sql;
	}
	
	public function removerSql($Cod)
	{
		$Sql = "DELETE FROM galeria_arquivo WHERE GaleriaArquivoCod = $Cod";
		
		return $Sql;
	}
}
?>