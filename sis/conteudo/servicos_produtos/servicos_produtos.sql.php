<?
include_once($_SESSION['FMBase'].'filtrar.class.php');

class ServicoSQL
{
	public function filtrarSql($ObjForm)
	{
		//Filtro Dinamico
		$Fil = new Filtrar($ObjForm);
		
		$Sql = "SELECT a.ServicoProdutoCod, a.ServicoCategoriaCod, a.ServicoNome, b.ServicoCategoriaNome,
					   IF(!ISNULL(ServicoFoto),'Sim','Não') AS ServicoFoto,
						CASE 
							WHEN a.ServicoPublicar = 'S' THEN 'Sim'
							WHEN a.ServicoPublicar = 'N' THEN 'Não'
						END AS ServicoPublicar,
						CASE 
							WHEN a.ServicoMenu = 'S' THEN 'Sim'
							WHEN a.ServicoMenu = 'N' THEN 'Não'
						END AS ServicoMenu,
						CASE 
							WHEN a.ServicoSituacao = 'A' THEN 'Ativo'
							WHEN a.ServicoSituacao = 'I' THEN 'Inativo'
						END AS ServicoSituacao, 
						IF(ISNULL(ServicoPrioridade), '-----', ServicoPrioridade) AS ServicoPrioridade
				
				FROM servico_produto a
                         INNER JOIN servico_categoria b ON a.ServicoCategoriaCod = b.ServicoCategoriaCod
				WHERE 1 ";

		$Sql .= $Fil->getStringSql("ServicoCategoriaCod","a.ServicoCategoriaCod");
		$Sql .= $Fil->getStringSql(addslashes("ServicoNome"),"a.ServicoNome","Texto");
		$Sql .= $Fil->getStringSql("ServicoPublicar","a.ServicoPublicar");
		//Sql de Impressão
		$Sql .= $Fil->printSql("a.ServicoProdutoCod",$_GET['SisReg']);

			   			
		return $Sql;
	}
	
	public function visualizarSql($Cod)
	{
		$Sql = "SELECT a.ServicoProdutoCod, a.ServicoCategoriaCod, a.ServicoNome,  a.ServicoDescricao, b.ServicoCategoriaNome,
				CASE 
					WHEN a.ServicoPublicar = 'S' THEN 'Sim'
					WHEN a.ServicoPublicar = 'N' THEN 'Não'
				END AS ServicoPublicar,
				CASE 
							WHEN a.ServicoMenu = 'S' THEN 'Sim'
							WHEN a.ServicoMenu = 'N' THEN 'Não'
						END AS ServicoMenu,
				CASE 
					WHEN a.ServicoSituacao = 'A' THEN 'Ativo'
					WHEN a.ServicoSituacao = 'I' THEN 'Inativo'
				END AS ServicoSituacao, 
				IF(ISNULL(ServicoPrioridade), '-----', ServicoPrioridade) AS ServicoPrioridade
				FROM servico_produto a
                         INNER JOIN servico_categoria b ON a.ServicoCategoriaCod = b.ServicoCategoriaCod
				WHERE ServicoProdutoCod = $Cod ";
		
		return $Sql;
	}

	public function cadastrarSql($ObjForm)
	{
		//Variaveis
		$VAR[] = $ObjForm->getCampoRetorna("ServicoPosicao",false,"Inteiro");
                $VAR[] = $ObjForm->getCampoRetorna("ServicoCategoriaCod",false,"Inteiro");
		$VAR[] = $ObjForm->getCampoRetorna("ServicoNome",false,"Texto");
		
		$VAR[] = $ObjForm->getCampoRetorna("ServicoDescricao",false,"Texto");
		$VAR[] = $ObjForm->getCampoRetorna("ServicoPublicar",false,"Texto");
		$VAR[] = $ObjForm->getCampoRetorna("ServicoSituacao",false,"Texto");
		$VAR[] = $ObjForm->getCampoRetorna("ServicoMenu",false,"Texto");
		$VAR[] = $ObjForm->getCampoRetorna("ServicoPrioridade",true,"Inteiro");
		
		$Sql = "INSERT INTO servico_produto 
				(ServicoPosicao,ServicoCategoriaCod, ServicoNome,  ServicoDescricao, ServicoPublicar, ServicoSituacao, ServicoMenu, ServicoPrioridade) VALUES
				(%s,%s,%s, %s, %s, %s, %s, %s)";
		
		return vsprintf($Sql,$VAR);
	}	
	
	public function cadastraImagemSql($ServicoProdutoCod, $FotoNome)
	{
		$Sql = "INSERT INTO servico_produto (ServicoProdutoCod, FotoNome)
				VALUES ($ServicoProdutoCod, '$FotoNome')";
		
		return $Sql;
	}	

	public function alterarTextoIntroSql($ObjForm)
	{
		$VAR[] = $ObjForm->getCampoRetorna("TextoIntroducao",true,"Texto");
		
		$Sql = "UPDATE servico_introducao SET 
				ServicoIntroducaoConteudo = %s
				WHERE ServicoIntroducaoCod = 1";
		
		return vsprintf($Sql,$VAR);
	}

	public function getDadosTexto()
	{
		$Sql = "SELECT ServicoIntroducaoConteudo 
				  FROM servico_introducao
				 WHERE 1";
				 
		return $Sql;
	}

	public function alterarSql($ObjForm)
	{		
		//Variaveis
                 $VAR[] = $ObjForm->getCampoRetorna("ServicoPosicao",false,"Inteiro");
		$VAR[] = $ObjForm->getCampoRetorna("ServicoCategoriaCod",false,"Inteiro");
		$VAR[] = $ObjForm->getCampoRetorna("ServicoNome",false,"Texto");
		
		$VAR[] = $ObjForm->getCampoRetorna("ServicoDescricao",false,"Texto");
		$VAR[] = $ObjForm->getCampoRetorna("ServicoPublicar",false,"Texto");
		$VAR[] = $ObjForm->getCampoRetorna("ServicoSituacao",false,"Texto");
		$VAR[] = $ObjForm->getCampoRetorna("ServicoMenu",false,"Texto");
		$VAR[] = $ObjForm->getCampoRetorna("ServicoPrioridade",true,"Inteiro");
		$VAR[] = $ObjForm->getCampoRetorna("Id",false,"Inteiro");
		
		
		
		
		$Sql = "UPDATE servico_produto SET 
				ServicoPosicao = %s,ServicoCategoriaCod = %s, ServicoNome = %s, ServicoDescricao = %s, ServicoPublicar = %s, ServicoSituacao = %s, ServicoMenu = %s, ServicoPrioridade = %s
				WHERE ServicoProdutoCod = %s";
		
		return vsprintf($Sql,$VAR);
	}
	
	public function getArquivosSql($Id)
	{
		$Sql = "SELECT ServicoProdutoCod, ServicoCategoriaCod, ServicoFoto,ServicoHome
				FROM   servico_produto
				WHERE  ServicoProdutoCod = $Id
				AND    !ISNULL(ServicoFoto)";

		return $Sql;
	}
	
	public function getArquivosSqlHome($Id)
	{
		$Sql = "SELECT ServicoProdutoCod, ServicoCategoriaCod, ServicoHome
				FROM   servico_produto
				WHERE  ServicoProdutoCod = $Id
				AND    (!ISNULL(ServicoHome) AND ServicoHome != '') ";

		return $Sql;
	}
	
	
	public function removeImagemServicoSql($Id)
	{
		$Sql = "UPDATE servico_produto SET
				ServicoFoto = NULL
				WHERE ServicoProdutoCod = $Id";
		
		return $Sql;
	}
	
		public function removeImagemServicoSqlHome($Id)
	{
		$Sql = "UPDATE servico_produto SET
				ServicoHome = NULL
				WHERE ServicoProdutoCod = $Id";
		
		return $Sql;
	}
	
	
	public function alteraImagemSql($ServicoProdutoCod, $FotoNome)
	{
		$Sql = "UPDATE servico_produto SET
				ServicoFoto = '$FotoNome'
				WHERE ServicoProdutoCod = $ServicoProdutoCod";
		
		return $Sql;
	}
	
	
	public function alteraImagemSqlHome($ServicoProdutoCod, $FotoNome)
	{
		$Sql = "UPDATE servico_produto SET
				ServicoHome = '$FotoNome'
				WHERE ServicoProdutoCod = $ServicoProdutoCod";
		
		return $Sql;
	}
	
	
	
	public function deletaImagemSql($ServicoProdutoCod)
	{
		$Sql = "DELETE FROM servico_foto 
				WHERE ServicoProdutoCod = $ServicoProdutoCod";
		
		return $Sql;
	}
	
	public function getDadosSql($Id)
	{
                    $Sql = "SELECT a.ServicoProdutoCod, a.ServicoPosicao ,a.ServicoCategoriaCod, a.ServicoNome,  a.ServicoDescricao, a.ServicoPublicar, a.ServicoSituacao
				FROM servico_produto a
				WHERE a.ServicoProdutoCod = $Id";
		
		return $Sql;
	}
	
	public function removerSql($Cod)
	{
		$Sql = "DELETE FROM servico_produto 
				WHERE ServicoProdutoCod = $Cod";
		
		return $Sql;
	}
	
	public function getDadosFotosSql($Id)
	{
		$Sql = "SELECT FotoNome, FotoLancNome
				FROM servico_produto  
				WHERE ServicoProdutoCod = $Id";
		
		return $Sql;
	}
	
	/*ARQUIVOS*/
	public function cadastrarArquivoSecaoSql($SecaoCod, $ArquivoCod)
	{
		$Sql = "INSERT INTO arquivo_servicos (ServicosCod, ArquivoCod) VALUES ($SecaoCod, $ArquivoCod)";
		
		return $Sql;
	}
	
	public function removerArquivoSecaoSql($SecaoCod)
	{
		$Sql = "DELETE FROM arquivo_servicos WHERE ServicosCod = $SecaoCod";
		
		return $Sql;		
	}
	/*GALERIA DE ARQUIVOS*/
	
	/*GALERIA DE MIDIA*/
	
	public function cadastrarGaleriaMidiaSecaoSql($ServicoProdutoCod, $GaleriaMidiaCod)
	{
		$Sql = "INSERT INTO galeria_midia_servico (ServicoProdutoCod, GaleriaMidiaCod) VALUES ($ServicoProdutoCod, $GaleriaMidiaCod)";
		
		return $Sql;
	}
	
	public function removerGaleriaMidiaSecaoSql($ServicoProdutoCod)
	{
		$Sql = "DELETE FROM galeria_midia_servico WHERE ServicoProdutoCod = $ServicoProdutoCod";
		
		return $Sql;		
	}
	
	
	public function verificaServicioIncio(){
		
		$Sql = "SELECT ServicoMenu
				FROM servico_produto
				WHERE ServicoMenu = 'S'
				" ;
		return $Sql;
	}
	/*GALERIA DE MIDIA*/	
}