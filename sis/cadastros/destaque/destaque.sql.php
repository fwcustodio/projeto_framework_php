<?
include_once($_SESSION['FMBase'] . 'filtrar.class.php');

class DestaqueSQL {

    public function filtrarSql($ObjForm) {
        //Filtro Dinamico
        $Fil = new Filtrar($ObjForm);

        $Sql = "SELECT DestaqueCod, DestaqueTitulo, IF(DestaqueLink = '', '--' , CONCAT(DestaqueTipo,DestaqueLink)) AS DestaqueLink
				FROM destaque
				WHERE 1 ";

        $Sql .= $Fil->getStringSql("DestaqueTitulo", "DestaqueTitulo");
        $Sql .= $Fil->getStringSql("DestaqueLink", "DestaqueLink");
        //Sql de Impressão
        $Sql .= $Fil->printSql("DestaqueCod", $_GET['SisReg']);


        return $Sql;
    }

    public function visualizarSql($Cod) {
        $Sql = "SELECT l.DestaqueTitulo, l.DestaquePrioridade, l.DestaqueDescricao, IF(l.DestaqueLink = '', '--' , CONCAT(l.DestaqueTipo,l.DestaqueLink)) AS DestaqueLink,
                                CASE DestaqueLinkTarget
                                        WHEN '_blank' THEN 'Nova Página'
                                        WHEN '_parent' THEN 'Mesma Página'
                                END AS DestaqueLinkTarget
				FROM destaque l
				WHERE DestaqueCod = $Cod";

        return $Sql;
    }

    public function cadastrarSql($ObjForm) {
        //Variaveis
        $VAR[] = $ObjForm->getCampoRetorna("DestaqueTitulo", false, "Texto");
        $VAR[] = $ObjForm->getCampoRetorna("DestaquePrioridade", false, "Inteiro");
        $VAR[] = $ObjForm->getCampoRetorna("DestaqueDescricao", false, "Texto");
        //$VAR[] = $ObjForm->getCampoRetorna("PortifolioCod",false,"Inteiro");
        $VAR[] = $ObjForm->getCampoRetorna("DestaqueTipo", false, "Texto");
        $VAR[] = $ObjForm->getCampoRetorna("DestaqueLink", false, "Texto");
        $VAR[] = $ObjForm->getCampoRetorna("DestaqueLinkTarget", false, "Texto");

        $Sql = "INSERT INTO destaque
				(DestaqueTitulo, DestaquePrioridade, DestaqueDescricao,  DestaqueTipo, DestaqueLink, DestaqueLinkTarget) VALUES
				(%s, %s, %s, %s, %s, %s)";

        return vsprintf($Sql, $VAR);
    }

    public function alterarImagemSql($DestaqueCod, $DestaqueImagem) {
        $Sql = "UPDATE  destaque SET DestaqueImagem = '$DestaqueImagem'
				WHERE DestaqueCod = $DestaqueCod";

        return $Sql;
    }

    public function alterarSql($ObjForm) {
        //Variaveis
        $VAR[] = $ObjForm->getCampoRetorna("DestaqueTitulo", false, "Texto");
        $VAR[] = $ObjForm->getCampoRetorna("DestaquePrioridade", false, "Inteiro");
        $VAR[] = $ObjForm->getCampoRetorna("DestaqueDescricao", false, "Texto");
        // $VAR[] = $ObjForm->getCampoRetorna("PortifolioCod",false,"Inteiro");
        $VAR[] = $ObjForm->getCampoRetorna("DestaqueTipo", false, "Texto");
        $VAR[] = $ObjForm->getCampoRetorna("DestaqueLink", false, "Texto");
        $VAR[] = $ObjForm->getCampoRetorna("DestaqueLinkTarget", false, "Texto");

        $VAR[] = $ObjForm->getCampoRetorna("Id", false, "Inteiro");

        $Sql = "UPDATE destaque SET
				DestaqueTitulo = %s, DestaquePrioridade = %s,DestaqueDescricao = %s,DestaqueTipo = %s, DestaqueLink = %s, DestaqueLinkTarget = %s
				WHERE DestaqueCod = %s";

        return vsprintf($Sql, $VAR);
    }

    public function getDadosSql($Id) {
        $Sql = "SELECT DestaqueCod,  DestaqueTitulo, DestaquePrioridade, DestaqueDescricao,  DestaqueImagem, DestaqueTipo, DestaqueLink, DestaqueLinkTarget
				FROM destaque
				WHERE DestaqueCod = $Id";

        return $Sql;
    }

    public function removerSql($Cod) {
        $Sql = "DELETE FROM destaque
				WHERE DestaqueCod = $Cod";

        return $Sql;
    }

    public function getDadosFotosSql($Id) {
        $Sql = "SELECT DestaqueImagem
				FROM destaque
				WHERE DestaqueCod = $Id";

        return $Sql;
    }

}