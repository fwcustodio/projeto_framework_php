<?

include_once($_SESSION['FMBase'] . 'filtrar.class.php');

class EnqueteSQL {

    public function filtrarSql($ObjForm) {
        //Filtro Dinamico
        $Fil = new Filtrar($ObjForm);

        $Sql = "SELECT EnqueteCod, EnquetePergunta, 
					CASE
						WHEN Publicar = 'S' THEN 'Sim'
						WHEN Publicar = 'N' THEN 'Não'
					END AS Publicar,
					
					CASE
						WHEN Situacao = 'A' THEN 'Ativo'
						WHEN Situacao = 'I' THEN 'Inativo'
					END AS Situacao,
					
					CASE
						WHEN TipoPublicacao = 'PS' THEN 'Principal e Seções'
						WHEN TipoPublicacao = 'P' THEN 'Apenas Página Principal'
						WHEN TipoPublicacao = 'S' THEN 'Definir Seções'
						WHEN TipoPublicacao = 'G' THEN 'Todas as Páginas'
					END AS TipoPublicacao 
				FROM enquete a
				WHERE 1 ";

        $Sql .= $Fil->getStringSql("EnquetePergunta", "EnquetePergunta", "Texto");
        $Sql .= $Fil->getStringSql("DataInicioPublicacao", "DataInicioPublicacao", "Data");
        $Sql .= $Fil->getStringSql("DataFimPublicacao", "DataFimPublicacao", "Data");
        $Sql .= $Fil->getStringSql("Publicar", "Publicar", "Texto");
        $Sql .= $Fil->getStringSql("Situacao", "Situacao", "Texto");
        $Sql .= $Fil->getStringSql("TipoPublicacao", "TipoPublicacao", "Texto");
        //Sql de Impressão
        $Sql .= $Fil->printSql("EnqueteCod", $_GET['SisReg']);


        return $Sql;
    }

    public function filtrarPopSql($ObjForm) {
        //Filtro Dinamico
        $Fil = new Filtrar($ObjForm);

        $Sql = "SELECT a.EnqueteCod, a.EnquetePergunta 
				FROM enquete a  
				WHERE  a.Situacao  = 'A' ";

        $Sql .= $Fil->getStringSql("EnquetePergunta", "a.EnquetePergunta", "Texto");

        return $Sql;
    }

    public function visualizarSql($Cod) {
        $Sql = "SELECT EnqueteCod, EnquetePergunta, 
					   DATE_FORMAT(DataInicioPublicacao, '%d/%m/%Y') AS DataInicioPublicacao,
					   DATE_FORMAT(DataFimPublicacao, '%d/%m/%Y') AS DataFimPublicacao,
					   DATE_FORMAT(DataInicioPublicacao, '%H:%i') AS HoraInicioPublicacao,
					   DATE_FORMAT(DataFimPublicacao, '%H:%i') AS HoraFimPublicacao,
						CASE
							WHEN Publicar = 'S' THEN 'Sim'
							WHEN Publicar = 'N' THEN 'Não'
						END AS Publicar,
					   DATE_FORMAT(Criada, '%d/%m/%Y') AS Criada,
						CASE
							WHEN Situacao = 'A' THEN 'Ativo'
							WHEN Situacao = 'I' THEN 'Inativo'
						END AS Situacao,
						CASE
							WHEN MostrarNumeroVotos = 'S' THEN 'Sim'
							WHEN MostrarNumeroVotos = 'N' THEN 'Não'
						END AS MostrarNumeroVotos,
						CASE
							WHEN MostrarPorcentagem = 'S' THEN 'Sim'
							WHEN MostrarPorcentagem = 'N' THEN 'Não'
						END AS MostrarPorcentagem,
						CASE
							WHEN TipoPublicacao = 'PS' THEN 'Principal e Seções'
							WHEN TipoPublicacao = 'P' THEN 'Apenas Página Principal'
							WHEN TipoPublicacao = 'S' THEN 'Definir Seções'
							WHEN TipoPublicacao = 'G' THEN 'Todas as Páginas'
						END AS TipoPublicacao  
				FROM enquete a
				WHERE EnqueteCod = $Cod";

        return $Sql;
    }

    public function visualizarRespostasSql($Cod) {
        $Sql = "SELECT EnqueteRespostaCod, Resposta, Votos
				FROM enquete_resposta
				WHERE EnqueteCod = $Cod";

        return $Sql;
    }

    public function cadastrarSql($ObjForm) {
        $FPHP = new FuncoesPHP();

        //Variaveis
        $VAR[] = $ObjForm->getCampoRetorna("EnquetePergunta", false, "Texto");
        $VAR[] = $ObjForm->getCampoRetorna("Publicar", false, "Texto");
        $VAR[] = $ObjForm->getCampoRetorna("Situacao", false, "Texto");
        $VAR[] = $ObjForm->getCampoRetorna("MostrarNumeroVotos", false, "Texto");
        $VAR[] = $ObjForm->getCampoRetorna("MostrarPorcentagem", false, "Texto");

        $DataInicioPublicacao = $FPHP->convertDataHora($_POST['DataInicioPublicacao']);

        $DataFimPublicacao = $FPHP->convertDataHora($_POST['DataFimPublicacao']);

        if (!empty($_POST['HoraInicioPublicacao'])) {
            $HoraInicioPublicacao = $FPHP->verificaHora($_POST['HoraInicioPublicacao']);
            if (empty($HoraInicioPublicacao))
                throw new Exception("ATENÇÃO: A Hora de início não está correta!");
        }

        if (!empty($_POST['HoraFimPublicacao'])) {
            $HoraFimPublicacao = $FPHP->verificaHora($_POST['HoraFimPublicacao']);
            if (empty($HoraFimPublicacao))
                throw new Exception("ATENÇÃO: A Hora de término não está correta!");
        }

        $DataInicio = trim($DataInicioPublicacao . " " . $_POST['HoraInicioPublicacao']);
        $DataTermino = trim($DataFimPublicacao . " " . $_POST['HoraFimPublicacao']);

        $DataInicio = (empty($DataInicio) ? 'NULL' : "'$DataInicio'");
        $DataTermino = (empty($DataTermino) ? 'NULL' : "'$DataTermino'");

        $Sql = "INSERT INTO enquete 
				( EnquetePergunta, DataInicioPublicacao, DataFimPublicacao, Publicar, Criada, Situacao, MostrarNumeroVotos, MostrarPorcentagem) VALUES 
				( %s, $DataInicio, $DataTermino, %s, NOW(), %s, %s, %s)";

        return vsprintf($Sql, $VAR);
    }

    public function alterarFullSql($ObjForm) {
        $FPHP = new FuncoesPHP();

        //Variaveis
        $VAR[] = $ObjForm->getCampoRetorna("EnquetePergunta", false, "Texto");
        $VAR[] = $ObjForm->getCampoRetorna("Publicar", false, "Texto");
        $VAR[] = $ObjForm->getCampoRetorna("Situacao", false, "Texto");
        $VAR[] = $ObjForm->getCampoRetorna("MostrarNumeroVotos", false, "Texto");
        $VAR[] = $ObjForm->getCampoRetorna("MostrarPorcentagem", false, "Texto");
        $VAR[] = $ObjForm->getCampoRetorna("Id", false, "Inteiro");

        $DataInicioPublicacao = $FPHP->convertDataHora($_POST['DataInicioPublicacao']);
        $DataFimPublicacao = $FPHP->convertDataHora($_POST['DataFimPublicacao']);

        if (!empty($_POST['HoraInicioPublicacao'])) {
            $HoraInicioPublicacao = $FPHP->verificaHora($_POST['HoraInicioPublicacao']);
            if (empty($HoraInicioPublicacao))
                throw new Exception("ATENÇÃO: A Hora de início não está correta!");
        }

        if (!empty($_POST['HoraFimPublicacao'])) {
            $HoraFimPublicacao = $FPHP->verificaHora($_POST['HoraFimPublicacao']);
            if (empty($HoraFimPublicacao))
                throw new Exception("ATENÇÃO: A Hora de término não está correta!");
        }

        $DataInicio = trim($DataInicioPublicacao . " " . $_POST['HoraInicioPublicacao']);
        $DataTermino = trim($DataFimPublicacao . " " . $_POST['HoraFimPublicacao']);

        $DataInicio = (empty($DataInicio) ? 'NULL' : "'$DataInicio'");
        $DataTermino = (empty($DataTermino) ? 'NULL' : "'$DataTermino'");

        $Sql = "UPDATE enquete SET 
				EnquetePergunta = %s, DataInicioPublicacao = $DataInicio, DataFimPublicacao = $DataTermino, Publicar = %s, Situacao = %s, MostrarNumeroVotos = %s, MostrarPorcentagem = %s
				WHERE EnqueteCod = %s";

        return vsprintf($Sql, $VAR);
    }
    
    public function alterarHalfSql($ObjForm) {
        $FPHP = new FuncoesPHP();

        //Variaveis
        $VAR[] = $ObjForm->getCampoRetorna("Publicar", false, "Texto");
        $VAR[] = $ObjForm->getCampoRetorna("Situacao", false, "Texto");
        $VAR[] = $ObjForm->getCampoRetorna("MostrarNumeroVotos", false, "Texto");
        $VAR[] = $ObjForm->getCampoRetorna("MostrarPorcentagem", false, "Texto");
        $VAR[] = $ObjForm->getCampoRetorna("Id", false, "Inteiro");

        $DataInicioPublicacao = $FPHP->convertDataHora($_POST['DataInicioPublicacao']);
        $DataFimPublicacao = $FPHP->convertDataHora($_POST['DataFimPublicacao']);

        if (!empty($_POST['HoraInicioPublicacao'])) {
            $HoraInicioPublicacao = $FPHP->verificaHora($_POST['HoraInicioPublicacao']);
            if (empty($HoraInicioPublicacao))
                throw new Exception("ATENÇÃO: A Hora de início não está correta!");
        }

        if (!empty($_POST['HoraFimPublicacao'])) {
            $HoraFimPublicacao = $FPHP->verificaHora($_POST['HoraFimPublicacao']);
            if (empty($HoraFimPublicacao))
                throw new Exception("ATENÇÃO: A Hora de término não está correta!");
        }

        $DataInicio = trim($DataInicioPublicacao . " " . $_POST['HoraInicioPublicacao']);
        $DataTermino = trim($DataFimPublicacao . " " . $_POST['HoraFimPublicacao']);

        $DataInicio = (empty($DataInicio) ? 'NULL' : "'$DataInicio'");
        $DataTermino = (empty($DataTermino) ? 'NULL' : "'$DataTermino'");

        $Sql = "UPDATE enquete SET 
                    DataInicioPublicacao = $DataInicio, 
                    DataFimPublicacao = $DataTermino, 
                    Publicar = %s, 
                    Situacao = %s, 
                    MostrarNumeroVotos = %s, 
                    MostrarPorcentagem = %s
                 WHERE EnqueteCod = %s";

        return vsprintf($Sql, $VAR);
    }

    public function getDadosSql($Id) {
        $Sql = "SELECT EnqueteCod, EnquetePergunta, 
		
				DATE_FORMAT(DataInicioPublicacao, '%d/%m/%Y') AS DataInicioPublicacao,
				DATE_FORMAT(DataFimPublicacao, '%d/%m/%Y') AS DataFimPublicacao,
				DATE_FORMAT(DataInicioPublicacao, '%H:%i') AS HoraInicioPublicacao,
				DATE_FORMAT(DataFimPublicacao, '%H:%i') AS HoraFimPublicacao,
				Publicar, Situacao, MostrarNumeroVotos, MostrarPorcentagem, TipoPublicacao
				FROM enquete 
				WHERE EnqueteCod = $Id";

        return $Sql;
    }

    public function removerSql($Cod) {
        $Sql = "DELETE FROM enquete WHERE EnqueteCod = $Cod";

        return $Sql;
    }

    public function getEnquetesSecaoSql($SecaoCod) {
        $Sql = "SELECT a.EnqueteCod, b.EnquetePergunta 
				FROM   enquete_secao a INNER JOIN enquete b  
				WHERE  a.EnqueteCod = b.EnqueteCod AND 
					   a.SecaoCod = $SecaoCod";

        return $Sql;
    }

    public function getEnquetesNoticiaSql($NoticiaCod) {
        $Sql = "SELECT a.EnqueteCod, b.EnquetePergunta 
				FROM   enquete_noticia a INNER JOIN enquete b  
				WHERE  a.EnqueteCod = b.EnqueteCod AND 
					   a.NoticiaCod = $NoticiaCod";

        return $Sql;
    }

    /* SQL DE DADOS DINAMICOS */

    public function cadastrarDadosSql($EnqueteCod, $Resposta) {
        $Sql = "INSERT INTO enquete_resposta
                        (EnqueteCod, Resposta) VALUES
                        ($EnqueteCod, '$Resposta')";

        return $Sql;
    }
    
    public function alterarDadosSql($EnqueteRespostaCod, $Resposta) {
        $Sql = "UPDATE enquete_resposta SET
                    Resposta = '$Resposta'
                WHERE EnqueteRespostaCod = $EnqueteRespostaCod";

        return $Sql;
    }

    public function removerDadosSql($EnqueteCod) {
        $Sql = "DELETE FROM enquete_resposta WHERE  EnqueteCod = $EnqueteCod";

        return $Sql;
    }

    public function removerDadosEnqueteSql($EnqueteCod) {
        $Sql = "DELETE FROM enquete_resposta WHERE  EnqueteRespostaCod = $EnqueteCod";

        return $Sql;
    }

    public function getDadosDadosSql($EnqueteCod) {
        $Sql = "SELECT EnqueteRespostaCod, EnqueteCod, Resposta
		  FROM enquete_resposta
		 WHERE EnqueteCod = $EnqueteCod";

        return $Sql;
    }
    
    public function getQuatidadeVotoEnqueteSql($EnqueteCod)
    {
        $Sql = "SELECT SUM(Votos)AS Quantidade
		  FROM enquete_resposta
		 WHERE EnqueteCod = $EnqueteCod";

        return $Sql;
    }
}