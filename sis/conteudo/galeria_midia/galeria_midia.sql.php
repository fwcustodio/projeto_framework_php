<?
include_once($_SESSION['FMBase'].'filtrar.class.php');

class GaleriaMidiaSQL
{
	public function filtrarSql($ObjForm)
	{
		//Filtro Dinamico
		$Fil = new Filtrar($ObjForm);
		
		$Sql = "SELECT a.GaleriaMidiaCod, a.GaleriaNome, 
					   DATE_FORMAT(a.DataCriacao, '%Y-%m-%d') AS DataCriacao, a.Publicar, 
					   CASE
					   WHEN Capa = 'S' THEN 'Sim'
					   WHEN Capa = 'N' THEN 'Não'
					   END AS Capa,
					   a.Publicar, a.Situacao,
					   (SELECT count(GaleriaMidiaCod) 
		       		   	FROM galeria_arquivo 
		       		   	WHERE galeria_arquivo.GaleriaMidiaCod = a.GaleriaMidiaCod) as NArquivos	  
				FROM galeria_midia a
				WHERE 1";
				
		$Sql .= $Fil->getStringSql("GaleriaNome","a.GaleriaNome","Texto");
		$Sql .= $Fil->getStringSql("DataCriacao","a.DataCriacao","Data");
		$Sql .= $Fil->getStringSql("Capa","a.Capa","Texto");
		$Sql .= $Fil->getStringSql("Publicar","a.Publicar","Texto");
		$Sql .= $Fil->getStringSql("Situacao","a.Situacao","Texto");
		
		//Sql de Impressï¿½o
		$Sql .= $Fil->printSql("a.GaleriaMidiaCod",$_GET['SisReg']);
			
		return $Sql;
	}
	
	public function filtrarPopSql($ObjForm)
	{
		//Filtro Dinamico
		$Fil = new Filtrar($ObjForm);
		
		$Sql = "SELECT a.GaleriaMidiaCod, a.GaleriaNome, 
					   DATE_FORMAT(a.DataCriacao, '%Y-%m-%d') AS DataCriacao, a.Publicar, 
					   CASE
					   WHEN Capa = 'S' THEN 'Sim'
					   WHEN Capa = 'N' THEN 'Nï¿½o'
					   END AS Capa,
					   a.Publicar, a.Situacao,
					   (SELECT count(GaleriaMidiaCod) 
		       		   	FROM galeria_arquivo 
		       		   	WHERE galeria_arquivo.GaleriaMidiaCod = a.GaleriaMidiaCod) as NArquivos	  
				FROM galeria_midia a
				WHERE  a.Situacao = 'A' ";
				
		$Sql .= $Fil->getStringSql("GaleriaNome","a.GaleriaNome","Texto");
		$Sql .= $Fil->getStringSql("DataCriacao","a.DataCriacao","Data");
		$Sql .= $Fil->getStringSql("Publicar","a.Publicar","Texto");
			   			
		return $Sql;
	}		
	
	public function visualizarSql($Cod)
	{
		$Sql = "SELECT a.GaleriaMidiaCod, a.GaleriaNome, DATE_FORMAT(a.DataCriacao, '%d/%m/%Y') AS DataCriacao, 
					   CASE a.Publicar
					       WHEN 'S' THEN 'Sim'
					       WHEN 'N' THEN 'Não'
					   END AS Publicar, 
					   CASE
					   WHEN Capa = 'S' THEN 'Sim'
					   WHEN Capa = 'N' THEN 'Não'
					   END AS Capa,
					   CASE a.Situacao
					   	   WHEN 'A' THEN 'Ativo'
					   	   WHEN 'I' THEN 'Inativo'
					   END AS Situacao,
					   (SELECT count(GaleriaMidiaCod) 
		       		   	FROM galeria_arquivo 
		       		   	WHERE galeria_arquivo.GaleriaMidiaCod = a.GaleriaMidiaCod) as NArquivos	
				FROM   galeria_midia a  
				WHERE  a.GaleriaMidiaCod = $Cod";
		
		return $Sql;
	}

	public function cadastrarSql($ObjForm)
	{
		//Variaveis
		$VAR[] = $ObjForm->getCampoRetorna("GaleriaNome",false,"Texto");
		$VAR[] = $ObjForm->getCampoRetorna("DataCriacao",false,"Data");
		$VAR[] = $ObjForm->getCampoRetorna("Capa",false,"Texto");
		$VAR[] = $ObjForm->getCampoRetorna("Publicar",false,"Texto");
		$VAR[] = $ObjForm->getCampoRetorna("Situacao",false,"Texto");

		$Sql = "INSERT INTO galeria_midia 
				(GaleriaNome, DataCriacao, Capa, Publicar, Situacao) VALUES 
				(%s, %s, %s, %s, %s)";
		
		return vsprintf($Sql,$VAR);
	}	
	
	public function alterarSql($ObjForm)
	{		
		//Variaveis
		$VAR[] = $ObjForm->getCampoRetorna("GaleriaNome",false,"Texto");
		$VAR[] = $ObjForm->getCampoRetorna("DataCriacao",false,"Data");
		$VAR[] = $ObjForm->getCampoRetorna("Capa",false,"Texto");
		$VAR[] = $ObjForm->getCampoRetorna("Publicar",false,"Texto");
		$VAR[] = $ObjForm->getCampoRetorna("Situacao",false,"Texto");
		$VAR[] = $ObjForm->getCampoRetorna("Id",true,"Inteiro");

		$Sql = "UPDATE galeria_midia SET 
				GaleriaNome = %s, DataCriacao = %s, Capa = %s, Publicar = %s, Situacao = %s  
				WHERE GaleriaMidiaCod = %s";
		
		return vsprintf($Sql,$VAR);
	}

	public function getDadosSql($Id)
	{
		$Sql = "SELECT GaleriaMidiaCod, GaleriaNome,  DATE_FORMAT(DataCriacao, '%d/%m/%Y') AS DataCriacao, Capa, Publicar, Situacao 
				FROM galeria_midia 
				WHERE GaleriaMidiaCod = $Id";
		
		return $Sql;
	}
	
	public function removerSql($Cod)
	{
		$Sql = "DELETE FROM galeria_midia 
				WHERE GaleriaMidiaCod = $Cod";
		
		return $Sql;
	}

        public function getGaleriaMidiaAcoesSql($SecaoCod)
	{
		$Sql = "SELECT a.GaleriaMidiaCod, b.GaleriaNome
				FROM   galeria_midia_acoes a INNER JOIN galeria_midia b
				WHERE  a.GaleriaMidiaCod = b.GaleriaMidiaCod AND
					   a.AcoesCadastroCod = $SecaoCod";

		return $Sql;
	}
	
	public function getGaleriaMidiaSecaoSql($SecaoCod)
	{
		$Sql = "SELECT a.GaleriaMidiaCod, b.GaleriaNome 
				FROM   galeria_midia_secao a INNER JOIN galeria_midia b  
				WHERE  a.GaleriaMidiaCod = b.GaleriaMidiaCod AND 
					   a.SecaoCod = $SecaoCod";
		
		return $Sql;
	}
//
//	public function getGaleriaMidiaLocalidadeSql($LocalidadeCod)
//	{
//		$Sql = "SELECT a.GaleriaMidiaCod, b.GaleriaNome
//				FROM   galeria_midia_localidade a INNER JOIN galeria_midia b
//				WHERE  a.GaleriaMidiaCod = b.GaleriaMidiaCod AND
//					   a.LocalidadeCod = $LocalidadeCod";
//
//		return $Sql;
//	}
	
	public function getGaleriaMidiaHospedagemSql($HospedagemCod)
	{
		$Sql = "SELECT a.GaleriaMidiaCod, b.GaleriaNome 
				FROM   galeria_midia_hospedagem a INNER JOIN galeria_midia b  
				WHERE  a.GaleriaMidiaCod = b.GaleriaMidiaCod AND 
					   a.HospedagemCod = $HospedagemCod";
		
		return $Sql;
	}
	
	
	public function getGaleriaMidiaServicoSql($ServicoProdutoCod)
	{
		$Sql = "SELECT a.GaleriaMidiaCod, b.GaleriaNome 
				FROM   galeria_midia_servico a INNER JOIN galeria_midia b  
				WHERE  a.GaleriaMidiaCod = b.GaleriaMidiaCod AND 
					   a.ServicoProdutoCod = $ServicoProdutoCod";
		
		return $Sql;
	}

	
	
	public function getGaleriaMidiaEventoSql($EventoCod)	
	{
		$Sql = "SELECT a.GaleriaMidiaCod, b.GaleriaNome 
				FROM   evento_galeria a INNER JOIN galeria_midia b  
				WHERE  a.GaleriaMidiaCod = b.GaleriaMidiaCod AND 
					   a.EventoCod = $EventoCod";
		
		return $Sql;
	}	
	
	public function getGaleriaMidiaPublicacaoSql($PublicacaoCod)	
	{
		$Sql = "SELECT a.GaleriaMidiaCod, b.GaleriaNome 
				FROM   galeria_midia_publicacao a INNER JOIN galeria_midia b  
				WHERE  a.GaleriaMidiaCod = b.GaleriaMidiaCod AND 
					   a.PublicacaoCod = $PublicacaoCod";
		
		return $Sql;
	}


                public function getGaleriaMidiaProdutoSql($NoticiaCod)
	{
		$Sql = "SELECT a.GaleriaMidiaCod, b.GaleriaNome
				FROM   galeria_midia_produto a INNER JOIN galeria_midia b
				WHERE  a.GaleriaMidiaCod = b.GaleriaMidiaCod AND
					   a.ProdutoCod = $NoticiaCod";

		return $Sql;
	}



	public function getGaleriaMidiaNoticiaSql($NoticiaCod)	
	{
		$Sql = "SELECT a.GaleriaMidiaCod, b.GaleriaNome 
				FROM   galeria_midia_noticia a INNER JOIN galeria_midia b  
				WHERE  a.GaleriaMidiaCod = b.GaleriaMidiaCod AND 
					   a.NoticiaCod = $NoticiaCod";
		
		return $Sql;
	}
	
	public function cadastrarImagemSql($GaleriaMidiaCod, $ObjForm)
	{
		$GaleriaMidiaCapaExtensao = $ObjForm->getCampoRetorna("Extensao",false,"Texto");

		$Sql = "INSERT INTO galeria_midia_capa
				(GaleriaMidiaCod, GaleriaMidiaCapaExtensao) VALUES 
				($GaleriaMidiaCod, $GaleriaMidiaCapaExtensao)";

		return $Sql;
	}
	
	public function removeImagemCapa($Id)
	{
		$Sql = "DELETE FROM galeria_midia_capa 
				WHERE GaleriaMidiaCod = $Id";
		
		return $Sql;
	}

        public function getListaGaleriaMidiaPortifolioSql($SecaoCod)
	{
		$Sql = "SELECT a.GaleriaMidiaCod, b.GaleriaNome
				FROM   galeria_midia_portifolio a INNER JOIN galeria_midia b
				ON a.GaleriaMidiaCod = b.GaleriaMidiaCod AND
					   a.PortifolioCod = $SecaoCod";

		return $Sql;
	}
	
	       public function getListaGaleriaMidiaServicoSql($SecaoCod)
	{
		$Sql = "SELECT a.GaleriaMidiaCod, b.GaleriaNome
				FROM   galeria_midia_servico a INNER JOIN galeria_midia b
				ON a.GaleriaMidiaCod = b.GaleriaMidiaCod AND
					   a.ServicoProdutoCod = $SecaoCod";

		return $Sql;
	}

}
?>