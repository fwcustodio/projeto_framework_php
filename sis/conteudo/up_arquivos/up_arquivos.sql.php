<?
include_once($_SESSION['FMBase'].'filtrar.class.php');

class UpArquivoSQL
{
	public function filtrarSql($ObjForm)
	{
		//Filtro Dinamico
		$Fil = new Filtrar($ObjForm);
		
		$Sql = "SELECT a.ArquivoCod, c.ArquivoCategoriaNome, a.ArquivoNome, a.DataPublicacao, a.Downloads
				FROM   arquivo a JOIN arquivo_categoria c
				ON a.ArquivoCategoriaCod = c.ArquivoCategoriaCod";

		$Sql .= $Fil->getStringSql("ArquivoCategoriaCod","c.ArquivoCategoriaCod", "Texto");
		$Sql .= $Fil->getStringSql("ArquivoNome","a.ArquivoNome", "Texto");
		$Sql .= $Fil->getStringSql("DataPublicacao","DATE_FORMAT(a.DataPublicacao, '%Y/%m/%d')", "Data");
		
		$Sql .= $Fil->getStringSql("Downloads", "a.Downloads");
		
		//Sql de Impressão
		$Sql .= $Fil->printSql("ArquivoCod",$_GET['SisReg']);
	   			
		return $Sql;
	}
	
	public function filtrarPopSql($ObjForm)
	{
		//Filtro Dinamico
		$Fil = new Filtrar($ObjForm);
		
		$Sql = "SELECT a.ArquivoCod, c.ArquivoCategoriaNome, a.ArquivoNome, a.DataPublicacao, a.Downloads
				FROM   arquivo a JOIN arquivo_categoria c
				ON a.ArquivoCategoriaCod = c.ArquivoCategoriaCod
				WHERE c.Situacao  = 'A'";

		$Sql .= $Fil->getStringSql("ArquivoCategoriaNome","c.ArquivoCategoriaNome");
		$Sql .= $Fil->getStringSql("ArquivoNome","a.ArquivoNome");
                $Sql .= $Fil->getStringSql("ArquivoDownloads","a.Downloads");
			   			
		return $Sql;
	}			
	
	public function visualizarSql($Cod)
	{
		$Sql = "SELECT c.ArquivoCategoriaNome,  a.ArquivoNome, a.ArquivoDescricao, DATE_FORMAT(a.DataPublicacao,'%d/%m/%Y') AS DataPublicacao, a.Downloads, a.Extensao   						
				FROM arquivo a JOIN arquivo_categoria c ON a.ArquivoCategoriaCod = c.ArquivoCategoriaCod
                                WHERE a.ArquivoCod = $Cod";
                
		return $Sql;
	}

	public function cadastrarSql($ObjForm)
	{
		//Variaveis
		$VAR[] = $ObjForm->getCampoRetorna("ArquivoCategoriaCod",false,"Inteiro");
		$VAR[] = $ObjForm->getCampoRetorna("ArquivoNome",false,"Texto");
		$VAR[] = $ObjForm->getCampoRetorna("ArquivoDescricao",false,"Texto");
		$Data  = $ObjForm->getCampoRetorna("DataPublicacao",false,"Data");
		$VAR[] = $ObjForm->getCampoRetorna("HashCod",false,"Texto");
		$VAR[] = $ObjForm->getCampoRetorna("Extensao",false,"Texto");		
		
		//Adiciona hora momentanea a Publicacao
		$Data  = "'".str_replace("'","", $Data)." ".date("H:i:s")."'";
		
		$Sql = "INSERT INTO arquivo 
				(ArquivoCategoriaCod, ArquivoNome, ArquivoDescricao, DataPublicacao, HashCod, Extensao) VALUES 
				(%s, %s, %s, $Data, %s, %s)";
		
		return vsprintf($Sql,$VAR);
	}	
	
	public function alterarSql($ObjForm)
	{		
		//Variaveis
		$VAR[] = $ObjForm->getCampoRetorna("ArquivoCategoriaCod",false,"Inteiro");
		$VAR[] = $ObjForm->getCampoRetorna("ArquivoNome",false,"Texto");
		$VAR[] = $ObjForm->getCampoRetorna("ArquivoDescricao",false,"Texto");
		$VAR[] = $ObjForm->getCampoRetorna("DataPublicacao",false,"Data");
		
		$VAR[] = $ObjForm->getCampoRetorna("Id",false,"Inteiro");

		//Adiciona hora momentanea a Publicacao
		$Data  = "'".str_replace("'","", $Data)." ".date("H:i:s")."'";
		
		$Sql = "UPDATE arquivo SET 
				ArquivoCategoriaCod = %s, ArquivoNome = %s, ArquivoDescricao = %s, DataPublicacao = %s
				WHERE ArquivoCod = %s";
		
		return vsprintf($Sql,$VAR);
	}

	public function getDadosSql($Id)
	{
		$Sql = "SELECT ArquivoCod, ArquivoCategoriaCod, ArquivoNome, ArquivoDescricao, 
					  DATE_FORMAT(DataPublicacao,'%d/%m/%Y') AS DataPublicacao, HashCod, Extensao, Downloads
				FROM arquivo 
				WHERE ArquivoCod = $Id";
		
		return $Sql;
	}
	
	public function removerSql($Cod)
	{
		$Sql = "DELETE FROM arquivo
                WHERE ArquivoCod = $Cod";
		
		return $Sql;
	}
	
	public function getArquivoSecaoSql($SecaoCod)
	{
		$Sql = "SELECT a.ArquivoCod, b.ArquivoNome 
				FROM   arquivo_secao a INNER JOIN arquivo b  
				WHERE  a.ArquivoCod = b.ArquivoCod AND 
					   a.SecaoCod = $SecaoCod";
		
		return $Sql;
	}
	
	public function getArquivoAcoesSql($SecaoCod)
	{
		$Sql = "SELECT a.ArquivoCod, b.ArquivoNome 
				FROM   arquivo_acoes a INNER JOIN arquivo b  
				WHERE  a.ArquivoCod = b.ArquivoCod AND 
					   a.AcoesCadastroCod = $SecaoCod";
		
		return $Sql;
	}
	
	public function getArquivoServicosSql($SecaoCod)
	{
		$Sql = "SELECT a.ArquivoCod, b.ArquivoNome 
				FROM   arquivo_servicos a INNER JOIN arquivo b  
				WHERE  a.ArquivoCod = b.ArquivoCod AND 
					   a.ServicosCod = $SecaoCod";
		
		return $Sql;
	}
	
	public function getArquivoEventoSql($EventoCod)
	{
		$Sql = "SELECT a.ArquivoCod, b.ArquivoNome 
				FROM   evento_arquivo a INNER JOIN arquivo b  
				WHERE  a.ArquivoCod = b.ArquivoCod AND 
					   a.EventoCod = $EventoCod";
		
		return $Sql;
	}	
	
	public function getArquivoPublicacaoSql($PublicacaoCod)
	{
		$Sql = "SELECT a.ArquivoCod, b.ArquivoNome 
				FROM   arquivo_publicacao a INNER JOIN arquivo b  
				WHERE  a.ArquivoCod = b.ArquivoCod AND 
					   a.PublicacaoCod = $PublicacaoCod";
		
		return $Sql;
	}

//	public function getArquivoNoticiaSql($NoticiaCod)
//	{
//		$Sql = "SELECT a.ArquivoCod, b.ArquivoNome
//				FROM   arquivo_noticia a INNER JOIN arquivo b
//				WHERE  a.ArquivoCod = b.ArquivoCod AND
//					   a.NoticiaCod = $NoticiaCod";
//
//		return $Sql;
//	}

        public function getArquivoProdutoSql($SecaoCod)
	{
		$Sql = "SELECT a.ArquivoCod, b.ArquivoNome
				FROM   arquivo_produto a INNER JOIN arquivo b
				WHERE  a.ArquivoCod = b.ArquivoCod AND
					   a.ProdutoCod = $SecaoCod";

		return $Sql;
	}

        public function getListaArquivoPortifolioSql($SecaoCod)
	{
		$Sql = "SELECT a.ArquivoCod, b.ArquivoNome
				FROM   arquivo_portifolio a INNER JOIN arquivo b
				ON a.ArquivoCod = b.ArquivoCod WHERE
					   a.PortifolioCod = $SecaoCod";

		return $Sql;
	}

}
?>