<?
include_once($_SESSION['FMBase'].'filtrar.class.php');

class SecaoSQL
{
	public function filtrarSql($ObjForm)
	{
		//Filtro Dinamico
		$Fil = new Filtrar($ObjForm);
		
		$FilhosDe = $ObjForm->getCampoFiltro('FilhosDe');
		
		if(!empty($FilhosDe))
		{
		  $SqlFilhos = " AND c.SecaoNome = '$FilhosDe' ";

		  $Op = 'INNER';
		  
		  $SqlFilhos2 = "LEFT JOIN secao e ON a.SecaoCod = e.SecaoCod AND e.SecaoNome = '$FilhosDe'";
		}
		else
		{
		  $Op = 'LEFT';
		}
		
		$Sql = "SELECT a.SecaoCod, b.SecaoGrupoNome, 
					   if(c.SecaoNome IS NULL, '<em>Seção Principal</em>', c.Secaonome) as SecaoPai, 
					   a.Situacao,
					   a.SecaoNome, a.SecaoPosicao, 
					   if(a.Publicar = 'S', 'Sim', 'Não') as Publicar,
                                           IF(ISNULL(a.ExibirMenu),'---',
                                           CASE a.ExibirMenu
                                                WHEN 'S' THEN 'Sim'
                                                WHEN 'N' THEN 'Não'
					   END) AS ExibirMenu,
					   a.Link 
				FROM   secao a INNER JOIN secao_grupo b 
					   $Op JOIN secao c ON (a.SecaoPai = c.SecaoCod $SqlFilhos) 
					   $SqlFilhos2
				WHERE  a.SecaoGrupoCod = b.SecaoGrupoCod";

		$Sql .= $Fil->getStringSql("SecaoGrupoCod","a.SecaoGrupoCod","Inteiro");
		$Sql .= $Fil->getStringSql("SecaoNome","a.SecaoNome","Texto");
		$Sql .= $Fil->getStringSql("Publicar","a.Publicar","Texto");
		$Sql .= $Fil->getStringSql("ExibirMenu","a.ExibirMenu","Texto");

		//Sql de Impressão
		$Sql .= $Fil->printSql("SecaoCod",$_GET['SisReg']);
		
		return $Sql;
	}
	
	public function visualizarSql($Cod)
	{
		$Sql = "SELECT a.SecaoGrupoCod, a.SecaoPai, a.SecaoNome, a.SecaoPosicao, 
					   CASE a.Publicar
					   		WHEN 'S' THEN 'Sim'
					   		WHEN 'N' THEN 'Não'
					   END AS Publicar, 
					   CASE a.ExibirMenu
					   		WHEN 'S' THEN 'Sim'
					   		WHEN 'N' THEN 'Não'
                                                        WHEN 'NULL' THEN '---'
					   END AS ExibirMenu, a.SecaoConteudo, a.Link, 					   					   
					   b.SecaoGrupoNome					   
				FROM   secao a, secao_grupo b  
				WHERE  SecaoCod 	   = $Cod
				AND    a.SecaoGrupoCod = b.SecaoGrupoCod";
		
		return $Sql;
	}

	public function cadastrarSql($ObjForm)
	{
		//Variaveis
		$VAR[] = $ObjForm->getCampoRetorna("SecaoGrupoCod",false,"Inteiro");
		$VAR[] = $ObjForm->getCampoRetorna("SecaoPai",true,"Inteiro");
		$VAR[] = $ObjForm->getCampoRetorna("SecaoNome",false,"Texto");
		$VAR[] = $ObjForm->getCampoRetorna("SecaoPosicao",false,"Inteiro");
		$VAR[] = $ObjForm->getCampoRetorna("Publicar",false,"Texto");
		$VAR[] = $ObjForm->getCampoRetorna("Situacao",false,"Texto");

		if($_POST['Tipo'] == 'C')
		{
			$VAR[] = $ObjForm->getCampoRetorna("ExibirMenu",false,"Texto");
			$VAR[] = $ObjForm->getCampoRetorna("MostrarFilhos",false,"Texto");
			$VAR[] = $ObjForm->getCampoRetorna("SecaoConteudo",false,"Texto");
			$VAR[] = $ObjForm->getCampoRetorna("AutorCod",true,"Inteiro");	
			$VAR[] = "NULL";
			$VAR[] = "NULL";
			$VAR[] = "NULL";
		}
		else if($_POST['Tipo'] == 'L')
		{
			$VAR[] = "NULL";
			$VAR[] = "NULL";
			$VAR[] = "NULL";	
			$VAR[] = "NULL";
			$VAR[] = $ObjForm->getCampoRetorna("LinkTipo",true,"Texto");	
			$VAR[] = $ObjForm->getCampoRetorna("Link",false,"Texto");
			$VAR[] = $ObjForm->getCampoRetorna("LinkTarget",false,"Texto");
		}
		else
		{
			throw new Exception("Tipo Inválido!");
		}

		$Sql = "INSERT INTO secao 
				(SecaoGrupoCod, SecaoPai,  SecaoNome, SecaoPosicao, Publicar, Situacao, ExibirMenu, MostrarFilhos, SecaoConteudo, AutorCod, LinkTipo, Link, LinkTarget) VALUES
				(%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)";
		
		return vsprintf($Sql,$VAR);
	}		
	
	public function alterarSql($ObjForm, $Block)
	{	
		if($Block)
		{
			$Con = Conexao::conectar();
			
			$_POST['Tipo'] == 'L';
		}
		
		//Variaveis
		$VAR[] = $ObjForm->getCampoRetorna("SecaoGrupoCod",false,"Inteiro");
		$VAR[] = $ObjForm->getCampoRetorna("SecaoPai",true,"Inteiro");
		$VAR[] = $ObjForm->getCampoRetorna("SecaoNome",false,"Texto");
		$VAR[] = $ObjForm->getCampoRetorna("SecaoPosicao",false,"Inteiro");
		$VAR[] = $ObjForm->getCampoRetorna("Publicar",false,"Texto");
		$VAR[] = $ObjForm->getCampoRetorna("Situacao",false,"Texto");

		if($_POST['Tipo'] == 'C')
		{
			$VAR[] = $ObjForm->getCampoRetorna("ExibirMenu",false,"Texto");
			$VAR[] = $ObjForm->getCampoRetorna("MostrarFilhos",false,"Texto");
			$VAR[] = $ObjForm->getCampoRetorna("SecaoConteudo",false,"Texto");	
			$VAR[] = $ObjForm->getCampoRetorna("AutorCod",true,"Inteiro");	
			$VAR[] = "NULL";
			$VAR[] = "NULL";
			$VAR[] = "NULL";
		}
		else if($_POST['Tipo'] == 'L')
		{
			$VAR[] = "NULL";
			$VAR[] = "NULL";	
			$VAR[] = "NULL";	
			$VAR[] = "NULL";
			
			if($Block)
			{
				$Id = $ObjForm->getCampoRetorna("Id",false,"Inteiro");
				
				$ObjForm->setCampoRetorna("LinkTipo",$Con->execRLinha($this->getDadosSql($Id),"LinkTipo"));		
				$ObjForm->setCampoRetorna("Link",$Con->execRLinha($this->getDadosSql($Id),"Link"));
				$ObjForm->setCampoRetorna("Link",$Con->execRLinha($this->getDadosSql($Id),"LinkTarget"));
				
				$VAR[] = $ObjForm->getCampoRetorna("LinkTipo",true,"Texto");		
				$VAR[] = $ObjForm->getCampoRetorna("Link",false,"Texto");
				$VAR[] = $ObjForm->getCampoRetorna("LinkTarget",false,"Texto");	
			}
			else 
			{
				$VAR[] = $ObjForm->getCampoRetorna("LinkTipo",true,"Texto");		
				$VAR[] = $ObjForm->getCampoRetorna("Link",false,"Texto");
				$VAR[] = $ObjForm->getCampoRetorna("LinkTarget",false,"Texto");
			}
		}
		else
		{
			throw new Exception("Tipo Inválido!");
		}

		$VAR[] = $ObjForm->getCampoRetorna("Id",false,"Inteiro");

		$Sql = "UPDATE secao SET 
				SecaoGrupoCod = %s, SecaoPai = %s, SecaoNome = %s, SecaoPosicao = %s, 
				Publicar = %s, Situacao = %s,
				ExibirMenu = %s, MostrarFilhos = %s, SecaoConteudo = %s, AutorCod = %s, 
				LinkTipo = %s, Link = %s, LinkTarget = %s 
				WHERE SecaoCod = %s";
		
		return vsprintf($Sql,$VAR);
	}

	public function publicarSql($SecaoCod)
	{
		$Sql = "UPDATE secao SET Publicar = 'S' WHERE SecaoCod = $SecaoCod";
		
		return $Sql;
	}
	
	public function naoPublicarSql($SecaoCod)
	{
		$Sql = "UPDATE secao SET Publicar = 'N' WHERE SecaoCod = $SecaoCod";
		
		return $Sql;
	}	
	
	public function getDadosSql($Id)
	{
		$Sql = "SELECT a.SecaoCod, a.SecaoGrupoCod, a.SecaoPai,
					   a.AutorCod, b.AutorNome, a.SecaoNome, a.SecaoPosicao, 
					   a.Publicar, a.Situacao, 
					   a.ExibirMenu, a.MostrarFilhos, a.LinkTipo, 
					   a.Link, a.SecaoConteudo , a.LinkTarget  
				FROM   secao a LEFT JOIN autor b ON (a.AutorCod = b.AutorCod) 
				WHERE  a.SecaoCod = $Id";
		
		return $Sql;
	}
	
	public function getDadosPaiSql($SecaoCod)
	{
		$Sql = "SELECT SecaoPai, FROM secao WHERE SecaoCod = $SecaoCod";
		
		return $Sql;
	}	
	
	public function removerSql($Cod)
	{
		$Sql = "DELETE FROM secao WHERE SecaoCod = $Cod";
		
		return $Sql;
	}
	
	/*POSICOES*/
	public function mudaPosicao($SecaoCod, $Operacao)
	{
		$Sql = "UPDATE secao SET SecaoPosicao = SecaoPosicao {$Operacao} 1 WHERE SecaoCod = $SecaoCod";
		
		return $Sql;
	}
	
	/*ARQUIVOS*/
	public function cadastrarArquivoSecaoSql($SecaoCod, $ArquivoCod)
	{
		$Sql = "INSERT INTO arquivo_secao (SecaoCod, ArquivoCod) VALUES ($SecaoCod, $ArquivoCod)";
		
		return $Sql;
	}
	
	public function removerArquivoSecaoSql($SecaoCod)
	{
		$Sql = "DELETE FROM arquivo_secao WHERE SecaoCod = $SecaoCod";
		
		return $Sql;		
	}
	/*GALERIA DE ARQUIVOS*/
	
	/*GALERIA DE MIDIA*/
	
	public function cadastrarGaleriaMidiaSecaoSql($SecaoCod, $GaleriaMidiaCod)
	{
		$Sql = "INSERT INTO galeria_midia_secao (SecaoCod, GaleriaMidiaCod) VALUES ($SecaoCod, $GaleriaMidiaCod)";
		
		return $Sql;
	}
	
	public function removerGaleriaMidiaSecaoSql($SecaoCod)
	{
		$Sql = "DELETE FROM galeria_midia_secao WHERE SecaoCod = $SecaoCod";
		
		return $Sql;		
	}
	
	/*GALERIA DE MIDIA*/	
	
	/*ENQUETES*/
	
	public function cadastrarEnqueteSecaoSql($SecaoCod, $EnqueteCod)
	{
		$Sql = "INSERT INTO enquete_secao (SecaoCod, EnqueteCod) VALUES ($SecaoCod, $EnqueteCod)";
		
		return $Sql;
	}
	
	public function removerEnqueteSecaoSql($SecaoCod)
	{
		$Sql = "DELETE FROM enquete_secao WHERE SecaoCod = $SecaoCod";
		
		return $Sql;		
	}
	
	/*ENQUETES*/
	
	/*REVISOES*/
	public function setRevisaoSql($SecaoCod)
	{
		$Sql = "INSERT INTO secao_revisao (SecaoCod, UsuarioCod, RevisaoData) VALUES ($SecaoCod, ".$_SESSION['UsuarioCod'].",now())";
		
		return $Sql;		
	}
	
	public function getRevisoesSql($SecaoCod)
	{
		$Sql = "SELECT   b.UsuarioDadosNome, DATE_FORMAT(a.RevisaoData, '%d/%m/%Y %H:%i') as RevisaoData 
				FROM     secao_revisao a INNER JOIN usuario_dados b 
				WHERE    a.UsuarioCod = b.UsuarioCod 
				AND	     a.SecaoCod = $SecaoCod 
			    ORDER BY SecaoRevisaoCod DESC";
		
		return $Sql;		
	}
	
	public function removeRevisoesSql($NoticiaCod)
	{
		$Sql = "DELETE FROM secao_revisao WHERE SecaoCod = $NoticiaCod";
		
		return $Sql;
	}
	/*REVISOES*/
	
	public function filhosSql($Id)
	{
		$Sql = "SELECT SecaoCod 
				FROM secao 
				WHERE SecaoPai = $Id";
		
		return $Sql;
	}

}
?>