<?

include_once($_SESSION['FMBase'] . 'filtrar.class.php');

class InfoProdSQL {

    public function filtrarSql($ObjForm) {
        //Filtro Dinamico
        $Fil = new Filtrar($ObjForm);

        $Sql = "SELECT a.ServicosInformacoesCod, a.ServicosCod, a.Nome, b.ServicoNome, a.Status,a.Mensagem,
						CASE
						WHEN a.Status = 'NL' THEN 'Não Lida'
						WHEN a.Status = 'L' THEN 'Lida'
						END AS Status,
						DATE_FORMAT(ServicosInformacaoData, '%d/%m/%Y às %h:%i:%s') AS ServicosInformacaoData
				FROM servicos_informacoes a JOIN servico_produto b ON a.ServicosCod = b.ServicoProdutoCod
				WHERE a.ServicosCod = b.ServicoProdutoCod";

        $Sql .= $Fil->getStringSql("Nome", "a.Nome");
        //$Sql .= $Fil->getStringSql("ServicoNome", "b.ServicoNome");
        $Sql .= $Fil->getStringSql("Status", "Status");
        //Sql de Impressão
        $Sql .= $Fil->printSql("ServicosInformacoesCod", $_GET['SisReg']);

        return $Sql;
    }

    public function visualizarSql($Cod) {
        $Sql = "SELECT a.ServicosCod, a.ServicosInformacoesCod, a.Nome, a.Email, a.Telefone, a.Observacoes, a.Status, DATE_FORMAT(a.ServicosInformacaoData, '%d/%m/%Y às %H:%i:%s') AS ServicosInformacaoData, b.ServicoNome,a.Mensagem
		, CASE
						WHEN a.Status = 'NL' THEN 'Não Lida'
						WHEN a.Status = 'L' THEN 'Lida'
						END AS Status
				FROM  servicos_informacoes  a, servico_produto b
				WHERE a.ServicosCod = b.ServicoProdutoCod
				AND ServicosInformacoesCod = $Cod";

        return $Sql;
    }

    public function cadastrarSql($ObjForm) {
//        //Variaveis
//        $VAR[] = $ObjForm->getCampoRetorna("PortifolioCod", true, "Inteiro");
//        $VAR[] = $ObjForm->getCampoRetorna("Nome", true, "Texto");
//        $VAR[] = $ObjForm->getCampoRetorna("Email", true, "Texto");
//        $VAR[] = $ObjForm->getCampoRetorna("Telefone", true, "Texto");
//        $VAR[] = $ObjForm->getCampoRetorna("PortifolioCategoriaCod",false,"Inteiro");
//
//        $Sql = "INSERT INTO portifolio_informacoes
//				(ServicoProdutoCod, Nome, Email, Telefone) VALUES
//				(%s, %s, %s, %s, %s)";
//
//        return vsprintf($Sql, $VAR);
    }

    public function alterarSql($ObjForm) {
//        //Variaveis
//        $VAR[] = $ObjForm->getCampoRetorna("Observacoes", false, "Texto");
//        $VAR[] = $ObjForm->getCampoRetorna("Status", false, "Texto");
//        $VAR[] = $ObjForm->getCampoRetorna("Id", true, "Inteiro");
//
//        $Sql = "UPDATE portifolio_informacoes SET
//				Observacoes = %s, Status = %s
//				WHERE PortifolioInformacoesCod = %s";
//
//        return vsprintf($Sql, $VAR);
    }

    public function getDadosSql($Id) {
        $Sql = "SELECT a.ServicosInformacoesCod, a.Status, a.Observacoes, b.ServicoNome
				FROM servicos_informacoes a , servico_produto b
				WHERE ServicosInformacoesCod = $Id";

        return $Sql;
    }

    public function removerSql($Cod) {
        $Sql = "DELETE FROM servicos_informacoes
				WHERE ServicosInformacoesCod = $Cod";

        return $Sql;
    }
    
    public function publicarSql($ProdutoCod)
    {
            $Sql = "UPDATE servicos_informacoes SET Status = 'L' WHERE ServicosInformacoesCod = $ProdutoCod";

            return $Sql;
    }

    public function naoPublicarSql($ProdutoCod)
    {
            $Sql = "UPDATE servicos_informacoes SET Status = 'NL' WHERE ServicosInformacoesCod = $ProdutoCod";

            return $Sql;
    }

}

?>