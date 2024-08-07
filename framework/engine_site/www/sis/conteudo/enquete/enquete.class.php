<?
include_once($_SESSION['FMBase'] . 'grid_padrao.class.php');
include_once($_SESSION['FMBase'] . 'grid_visualizar.class.php');
include_once($_SESSION['FMBase'] . 'grid_click.class.php');
include_once($_SESSION['DirBase'] . 'conteudo/enquete/enquete.sql.php');
include_once($_SESSION['DirBase'] . 'conteudo/atualizacao_log/atualizacao_log.class.php');

class Enquete extends EnqueteSQL {
    /*
     * 	Seta a C�digo Chave	
     * 	@return String
     */

    public function getChave() {
        return "EnqueteCod";
    }

    /*
     * 	Seta o C�digo usado para gravar o m�dulo de Ultimas Atualiza��es
     * 	@return String
     */

    public function getAtualizacaoModuloCod() {
        return 1;
    }

    /*
     * 	Retorna um array com os parametros utilizados no filtro
     * 	@return Array
     */

    public function getParametros() {
        $Fil = new Filtrar();

        $Padrao = array("PaginaAtual", "QuemOrdena", "TipoOrdenacao");

        $MeusParametros = array("EnquetePergunta", "DataInicioPublicacao", "DataFimPublicacao", "Publicar", "Situacao", "TipoPublicacao");

        $HiddenParametros = $Fil->getHiddenParametros($MeusParametros);

        return array_merge($Padrao, $MeusParametros, $HiddenParametros);
    }

    /**
     * 	Repons�vel pela filtragem dos dados na grid
     * 	@return String
     */
    public function filtrar($ObjForm) {
        $Gr = new GridPadrao();

        //Grid de Visualiza��o- Configura��es
        $Gr->setListados(array("EnquetePergunta"));
        $Gr->setTitulos(array("Pergunta"));

        //Setando Parametros
        Parametros::setParametros("GET", $this->getParametros());

        //Impress�o
        if ($_GET['ModoPrint'] == 'true') {
            $Gr->setQLinhas(0);
            $Gr->setModoImpressao(true);
        } else {
            $Gr->setQLinhas(ConfigSIS::$CFG['QLinhasGrid']);
            $Gr->setModoImpressao(false);
        }

        //Configura��es Fixas da Grid
        $Gr->setSql(parent::filtrarSql($ObjForm));
        $Gr->setChave($this->getChave());
        $Gr->setTipoOrdenacao($_GET['TipoOrdenacao']);
        $Gr->setQuemOrdena($_GET['QuemOrdena']);
        $Gr->setPaginaAtual($_GET['PaginaAtual']);

        //Fundo para publicar = Nao
        $Gr->setCondicaoTodosResultados("\$R = (\$Linha['Publicar'] == 'N�o' ) ? true : false;", "sis_fundoNaoPublicar");

        //Fundo para situacao = Inativo
        $Gr->setCondicaoTodosResultados("\$R = (\$Linha['Situacao'] == 'Inativo') ? true : false;", "sis_fundoInativo");

        //Retornando a Grid Formatada - HTML	
        return $Gr->inForm($Gr->montaGridPadrao() . "<hr />" . $this->getLegenda(), "FormGrid");
    }

    public function filtrarPop($ObjForm) {
        $Gr = new GridClick();

        //Grid de Visualiza? - Configura?s
        $Gr->setListados(array("EnquetePergunta"));
        $Gr->setTitulos(array("Pergunta"));

        //Setando Parametros
        Parametros::setParametros("GET", array("PaginaAtual", "QuemOrdena", "TipoOrdenacao", "EnquetePergunta", "IdiomaCod", "IdForm", "TipoCampo"));

        //Configura?s Fixas da Grid
        $Gr->setSql(parent::filtrarPopSql($ObjForm));
        $Gr->setChave($this->getChave());
        $Gr->setTipoOrdenacao($_GET['TipoOrdenacao']);
        $Gr->setQuemOrdena($_GET['QuemOrdena']);
        $Gr->setPaginaAtual($_GET['PaginaAtual']);
        $Gr->setQLinhas(ConfigSIS::$CFG['QLinhasGrid']);

        //Marcar Contas pagas
        $Gr->setCondicaoTodosResultados("return(true) ? true : false;", "mao");

        //Retornando a Grid Formatada - HTML
        //Arquivo Repons�vel pela a��o do click
        $INCJS = '<script type="text/javascript" src="' . $_SESSION['JSBase'] . 'js/grid_pop.js" />';

        return $Gr->inForm($Gr->montaGridClick(), "FormGrid") . $INCJS;
    }

    /**
     * 	Monta Estrutura de Visualiza��o dos Registros Selecionados
     * 	@return String
     */
    public function visualizar() {
        $Gr = new GridVisualizar();

        //Grid de Visualiza? Detalhada
        $Gr->setListados(array("EnqueteCod", "EnquetePergunta", "DataInicioPublicacao", "DataFimPublicacao", "HoraInicioPublicacao", "HoraFimPublicacao", "Publicar", "Criada", "Situacao", "MostrarNumeroVotos", "MostrarPorcentagem", "Resposta", "Votos"));
        $Gr->setTitulos(array("Enquete", "Pergunta", "Data de In�cio", "Data de T�rmino", "Hora de In�cio", "Hora de T�rmino", "Publicar", "Data de Cria��o", "Situa��o", "Mostrar Numero de Votos", "Mostrar Porcentagem", "Resposta", "Votos"));

        //Configura?s Fixas da Grid
        $Gr->setChave($this->getChave());

        //Retornando a Grid Formatada - HTML
        if (!is_array($_POST['SisReg']))
            throw new Exception("Nenhum registro selecionado!");

        foreach ($_POST['SisReg'] as $Cod) {
            $Gr->setSql(parent::visualizarSql($Cod));
            $Vis .= $Gr->montaGridVisualizar();

            $Gr->setSql(parent::visualizarRespostasSql($Cod));
            $Vis .= $Gr->montaGridVisualizar();
            $Vis .= "<div style='height=50px;'>&nbsp;</div>";
        }

        return $Vis;
    }

    /**
     * 	Repons�vel pelo Cadastro das Informa��es
     * 	@return Void
     */
    public function cadastrar($ObjForm) {
        //Inicia Conex�o
        $Con = Conexao::conectar();

        //Inicia Transa��o		
        $Con->startTransaction();

        //Inicia Classe de Logs
        $Log = new Log();

        //Executa Sql		
        $Con->executar(parent::cadastrarSql($ObjForm));

        //Ultimo Id da Tabela
        $EnqueteCod = $Con->ultimoInsertId();

        //Log Oculto
        $Con->setLogOculto(true);

        //Cadastrando dados
        $this->cadastrarAlterarDados($EnqueteCod);

        //Log Oculto
        $Con->setLogOculto(false);

        //Grava Log
        $Log->geraLog($EnqueteCod);

        //Inicia Class Atualiza��o
        $Atualizacao = new AtualizacaoLog();

        //Gra�a Atualiza��o
        $Atualizacao->geraUltimaAtualizacao($this->getAtualizacaoModuloCod(), $EnqueteCod, "A");

        //Finaliza Transa��o					
        $Con->stopTransaction();
    }

    /**
     * 	Repons�vel pela altera��o das Informa��es
     * 	@return Void
     */
    public function alterar($ObjForm) {
        //Inicia Conex�o
        $Con = Conexao::conectar();

        //Inicia Transa��o		
        $Con->startTransaction();

        //Inicia Classe de Logs
        $Log = new Log();
        
        //Gets
        $EnqueteCod            = $ObjForm->get('Id');
        $QuantidadeVotoEnquete = $ObjForm->get('QuantidadeVotoEnquete');
        
        //Executa Sql
        if(!empty($QuantidadeVotoEnquete)){            
            $Con->executar(parent::alterarHalfSql($ObjForm));
        }else{
            $Con->executar(parent::alterarFullSql($ObjForm));
            
            //Log Oculto
            $Con->setLogOculto(true);

            //Cadastrando dados
            $this->cadastrarAlterarDados($EnqueteCod);

            //Log Oculto
            $Con->setLogOculto(false);
        }
        
        //Grava Log
        $Log->geraLog($EnqueteCod);

        //Finaliza Transa��o					
        $Con->stopTransaction();
    }

    /**
     * 	Repons�vel pela exclus�o das Informa��es dos registros selecionados
     * 	@return String
     */
    public function remover() {
        //Inicia Variaveis de Buffer
        $Mensagem = array(); //Array de Mensagens
        $RSelecionados = count($_POST['SisReg']); //Numero de Registros Selecionados na Grid
        $RApagados = 0; //Numero de Registros Apagados
        //Intercepta Erros
        try {
            if (!is_array($_POST['SisReg']))
                throw new Exception("Nenhum registro selecionado!");

            //Inicia Conex�o
            $Con = Conexao::conectar();

            //Inicia Transa��o
            $Con->startTransaction();

            //Inicia Classe de Logs
            $Log = new Log();

            foreach ($_POST['SisReg'] as $Chave) {
                
                $QuantidadeVotoEnquete = $this->getQuatidadeVotoEnquete($Chave);
                
                if(!empty($QuantidadeVotoEnquete)) {
                    $Mensagem[] = 'Esta enquete \''.$Con->execRLinha(parent::getDadosSql($Chave), "EnquetePergunta") . '\' n�o pode ser excluida, pois j� possui votos computados no sistema!';
                } else {
                    $Con->executar(parent::removerSql($Chave));

                    //Log Oculto
                    $Con->setLogOculto(true);

                    //Removendo Dados
                    $this->removerDados($Chave);

                    //Log Oculto
                    $Con->setLogOculto(false);

                    //Grava Log
                    $Log->geraLog($Chave);

                    //Inicia Class Atualiza��o
                    $Atualizacao = new AtualizacaoLog();

                    //Gra�a Atualiza��o
                    $Atualizacao->removerUltimaAtualizacao($this->getAtualizacaoModuloCod(), $Chave);

                    $RApagados++;
                }
            }
            $Con->stopTransaction();
        } catch (Exception $E) {
            $FPHP = new FuncoesPHP();

            $Mensagem[] = $FPHP->limpaStringJS($E->getMessage());
        }

        return 'var retorno = {"selecionados":' . $RSelecionados . ', "apagados":' . $RApagados . ',"mensagem":"' . implode("\\n", $Mensagem) . '"}';
    }

    /**
     * 	Retorna os dados gravados no banco encapsulados na superglobal desejada 
     * 	@return Void
     */
    public function getDados($Id, $Metodo) {
        //Instancia Fun��es PHP
        $FPHP = new FuncoesPHP();

        //Inicia Conex�o	
        $Con = Conexao::conectar();

        //Extrai Dados Sql
        $DadosSql = array_values($Con->execLinhaArray(parent::getDadosSql($Id)));

        //Define Campos
        $Campos = array("Id", "EnquetePergunta", "DataInicioPublicacao", "DataFimPublicacao", "HoraInicioPublicacao", "HoraFimPublicacao", "Publicar", "Situacao", "MostrarNumeroVotos", "MostrarPorcentagem", "TipoPublicacao");

        //Combina Sql com Campos Definidos
        $CamposForm = array_combine($Campos, $DadosSql);

        //Extrai Variaveis para o metodo desejado
        $FPHP->extractVar($CamposForm, $Metodo);
    }

    public function getNomeEnquete($EnqueteCod) {
        //Inicia Conex�o	
        $Con = Conexao::conectar();

        $Html  = '<tr><td>';
        $Html .= $Con->execRLinha(parent::getDadosSql($EnqueteCod), "EnquetePergunta");
        $Html .= '</td><td width="20" align="center"><input type="hidden" name="ArrayEnqueteCod[' . $EnqueteCod . ']" id="ArrayEnqueteCod' . $EnqueteCod . '" value="' . $EnqueteCod . '" /><img src="' . $_SESSION['UrlBase'] . 'figuras/del_2.gif" alt="Remover" onclick="$(this).parent().parent().remove();" style="cursor:pointer" /></td></tr>';

        return $Html;
    }

    public function getListaEnquetesSecao($SecaoCod) {
        //Inicia Conex�o	
        $Con = Conexao::conectar();

        $RSEnqueteSecao = $Con->executar(parent::getEnquetesSecaoSql($SecaoCod));

        while ($DadosEnquete = mysqli_fetch_array($RSEnqueteSecao)) {
            $Html .= '<tr><td>';
            $Html .= $DadosEnquete['EnquetePergunta'];
            $Html .= '</td><td width="20" align="center"><input type="hidden" name="ArrayEnqueteCod[' . $DadosEnquete['EnqueteCod'] . ']" id="ArrayEnqueteCod' . $DadosEnquete['EnqueteCod'] . '" value="' . $DadosEnquete['EnqueteCod'] . '" /><img src="' . $_SESSION['UrlBase'] . 'figuras/del_2.gif" alt="Remover" onclick="$(this).parent().parent().remove();" style="cursor:pointer" /></td></tr>';
        }

        return $Html;
    }

    public function getListaEnquetesNoticia($NoticiaCod) {
        //Inicia Conex�o	
        $Con = Conexao::conectar();

        $RSEnqueteNoticia = $Con->executar(parent::getEnquetesNoticiaSql($NoticiaCod));

        while ($DadosEnquete = mysqli_fetch_array($RSEnqueteNoticia)) {
            $Html .= '<tr><td>';
            $Html .= $DadosEnquete['EnquetePergunta'];
            $Html .= '</td><td width="20" align="center"><input type="hidden" name="ArrayEnqueteCod[' . $DadosEnquete['EnqueteCod'] . ']" id="ArrayEnqueteCod' . $DadosEnquete['EnqueteCod'] . '" value="' . $DadosEnquete['EnqueteCod'] . '" /><img src="' . $_SESSION['UrlBase'] . 'figuras/del_2.gif" alt="Remover" onclick="$(this).parent().parent().remove();" style="cursor:pointer" /></td></tr>';
        }

        return $Html;
    }

    /* DADOS */

    public function cadastrarAlterarDados($EnqueteCod) {
        //Inicia Conex�o	
        $Con = Conexao::conectar();

        //Instancia Fun��es PHP
        $FPHP = new FuncoesPHP();
        
        //Recupera Array
        $ArrayAtual = $_POST['ContadorDados'];
        
        $ArrayRS = $Con->execTodosArray(parent::getDadosDadosSql($EnqueteCod));
        if(!empty($ArrayRS)){        
            foreach($ArrayRS as $Dados)
            {
                $ArrayBanco[$Dados['EnqueteRespostaCod']] = $Dados['EnqueteRespostaCod'];
            }
        }else{
            $ArrayBanco = array();
        }       

        //Cadastra
        $CadastraRespostas = array_diff($ArrayAtual, $ArrayBanco);
        if (is_array($CadastraRespostas)) {
            foreach($CadastraRespostas as $Codigo) {
                $Resposta = $FPHP->converteHTML(utf8_decode($_POST['EnqueteResposta' . $Codigo]));

                $Con->executar(parent::cadastrarDadosSql($EnqueteCod, $Resposta));
            }
        }
        
        //Alterar
        $AlteraRespostas = array_intersect($ArrayAtual, $ArrayBanco);
        if (is_array($AlteraRespostas)) {
            foreach($AlteraRespostas as $Codigo) {
                $Resposta = $FPHP->converteHTML(utf8_decode($_POST['EnqueteResposta' . $Codigo]));

                $Con->executar(parent::alterarDadosSql($Codigo, $Resposta));
            }
        }
        
        //Remove
        $RemoveRespostas = array_diff($ArrayBanco, $ArrayAtual);
        if (is_array($RemoveRespostas)) {
            foreach($RemoveRespostas as $Codigo) {
                $Con->executar(parent::removerDadosEnqueteSql($Codigo));
            }
        }        
    }

    public function removerDados($EnqueteCod) {
        //Inicia Conex�o	
        $Con = Conexao::conectar();

        //Verifica se Existe
        if ($Con->existe('enquete_resposta', 'EnqueteCod', $EnqueteCod)) {
            $Con->executar(parent::removerDadosSql($EnqueteCod));
        }
    }

    public function getDadosDados($EnqueteCod, $ObjForm) {
        //Instancia Fun��es PHP
        $FPHP = new FuncoesPHP();

        //Inicia Conex�o	
        $Con = Conexao::conectar();

        //ResultSet Com Dados
        $RSDados               = $Con->executar(parent::getDadosDadosSql($EnqueteCod));        
        $QuantidadeVotoEnquete = $this->getQuatidadeVotoEnquete($EnqueteCod);
        
        //Buffer Html
        $BufferHtml = '';

        while ($Dados = @mysqli_fetch_array($RSDados)) {
            //Criando POTS
            $Cod = $Dados["EnqueteRespostaCod"];

            $_POST["EnqueteResposta" . $Cod] = $Dados['Resposta'];

            //Recuperando Dados nos Campos
            $Campos = $ObjForm->getFormDados($Cod);
            
            if(!empty($QuantidadeVotoEnquete)){
                $BufferHtml.= "<div id='campoRespostas' class='dadosLinha" . $Cod . "'>" . $Campos['ContadorDados'] . $Campos['EnqueteResposta'] . "&nbsp;&nbsp;<img src=\"" . $_SESSION['UrlBase'] . "figuras/del_2_off.gif\" border=\"0\" /></div>";
            }else{
                $BufferHtml.= "<div id='campoRespostas' class='dadosLinha" . $Cod . "'>" . $Campos['ContadorDados'] . $Campos['EnqueteResposta'] . "&nbsp;&nbsp;<img src=\"" . $_SESSION['UrlBase'] . "figuras/del_2.gif\" border=\"0\" onClick=\"removeItenDado(this, '$EnqueteCod')\" style=\"cursor:pointer\" /></div>";
            }

            
        }

        return $BufferHtml;
    }
    
    public function getQuatidadeVotoEnquete($EnqueteCod){
        $Con = Conexao::conectar();
        return $Con->execRLinha(parent::getQuatidadeVotoEnqueteSql($EnqueteCod), 'Quantidade');
    }

    /* DADOS */

    public function getLegenda() {
        $HTML = '<table width="100%" border="0" cellpadding="3" cellspacing="0">
                    <tr>
                      <td width="25%" class="fundoLegenda bordaLeg">Legenda:</td>
                      <td width="25%" align="center" class="sis_fundoNaoPublicar fTamanho bordaLeg">Enquete N�o Publicada</td>
                      <td width="25%" align="center" class="sis_fundoInativo fTamanho bordaLeg">Enquete Inativada</td>
                      <td width="25%" align="center" class="sis_fundoNormal fTamanho bordaLeg">Enquete Ativa e Publicada</td>
                    </tr>
                 </table>';

        return $HTML;
    }

}