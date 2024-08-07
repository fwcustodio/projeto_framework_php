<?
include_once($_SESSION['FMBase'] . 'filtrar.class.php');

class ParceirosSQL {

    public function filtrarSql($ObjForm) {
        //Filtro Dinamico
        $Fil = new Filtrar($ObjForm);

        $Sql = "SELECT ParceirosCod, ParceirosNome, IF(ParceirosLink = '', '--' , CONCAT(ParceirosTipo,ParceirosLink)) AS ParceirosLink,
						CASE ParceirosSituacao
						WHEN 'A' THEN 'Ativo'
						WHEN 'I' THEN 'Inativo'
						END AS ParceirosSituacao						
				FROM parceiros a
				WHERE 1 = 1";

        $Sql .= $Fil->getStringSql("ParceirosNome", "ParceirosNome", "Texto");
        $Sql .= $Fil->getStringSql("ParceirosLink", "ParceirosLink", "Texto");
        $Sql .= $Fil->getStringSql("ParceirosSituacao", "ParceirosSituacao", "Texto");
        //Sql de Impressão
        $Sql .= $Fil->printSql("ParceirosCod", $_GET['SisReg']);

        return $Sql;
    }

    public function visualizarSql($Cod) {
        $Sql = "SELECT ParceirosCod, ParceirosNome, ParceirosComentario, CONCAT(ParceirosTipo,ParceirosLink) AS ParceirosLink,
						CASE ParceirosSituacao
							WHEN 'A' THEN 'Ativo'
							WHEN 'I' THEN 'Inativo'
						END AS ParceirosSituacao
				FROM parceiros
				WHERE ParceirosCod = $Cod";

        return $Sql;
    }

    public function cadastrarSql($ObjForm) {
        //Variaveis
        $VAR[] = $ObjForm->getCampoRetorna("ParceirosNome", false, "Texto");
        $VAR[] = $ObjForm->getCampoRetorna("ParceirosComentario", true, "Texto");
        $VAR[] = $ObjForm->getCampoRetorna("ParceirosTipo", false, "Texto");
        $VAR[] = $ObjForm->getCampoRetorna("ParceirosLink", false, "Texto");
        $VAR[] = $ObjForm->getCampoRetorna("ParceirosSituacao", false, "Texto");

        $Sql = "INSERT INTO parceiros 
				(ParceirosNome, ParceirosComentario, ParceirosTipo, ParceirosLink, ParceirosSituacao) VALUES 
				(%s, %s, %s, %s, %s)";

        return vsprintf($Sql, $VAR);
    }

    public function alterarSql($ObjForm) {
        //Variaveis
        $VAR[] = $ObjForm->getCampoRetorna("ParceirosNome", false, "Texto");
        $VAR[] = $ObjForm->getCampoRetorna("ParceirosComentario", true, "Texto");
        $VAR[] = $ObjForm->getCampoRetorna("ParceirosTipo", false, "Texto");
        $VAR[] = $ObjForm->getCampoRetorna("ParceirosLink", false, "Texto");
        $VAR[] = $ObjForm->getCampoRetorna("ParceirosSituacao", false, "Texto");
        $VAR[] = $ObjForm->getCampoRetorna("Id", false, "Inteiro");

        $Sql = "UPDATE parceiros SET 
				ParceirosNome = %s, ParceirosComentario = %s, ParceirosTipo = %s, ParceirosLink = %s, ParceirosSituacao = %s
                         WHERE ParceirosCod = %s";

        return vsprintf($Sql, $VAR);
    }

    public function getDadosSql($Id) {
        $Sql = "SELECT ParceirosCod, ParceirosNome, ParceirosComentario, ParceirosLink, ParceirosTipo, ParceirosArquivo, ParceirosExtensao, ParceirosSituacao
				FROM parceiros 
				WHERE ParceirosCod = $Id";

        return $Sql;
    }

    public function removerSql($Cod) {
        $Sql = "DELETE FROM parceiros 
				WHERE ParceirosCod = $Cod";

        return $Sql;
    }

    public function retornaNomeArquivo($Id) {
        $Sql = "SELECT ParceirosArquivo, ParceirosExtensao
				 FROM parceiros
				 WHERE ParceirosCod = " . $Id . "";

        return $Sql;
    }

    public function imagemSql($Cod, $Imagem, $Extensao) {
        $Sql = "UPDATE parceiros SET ParceirosArquivo = '$Imagem', ParceirosExtensao = '$Extensao'
				WHERE ParceirosCod = $Cod";

        return $Sql;
    }

}