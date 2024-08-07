<?
include_once($_SESSION['FMBase'] . 'grid_padrao.class.php');
include_once($_SESSION['FMBase'] . 'grid_visualizar.class.php');
include_once($_SESSION['FMBase'] . 'arquivos.class.php');
include_once($_SESSION['DirBase'] . PACOTE . '/' . MODULO . '/' . MODULO . '.sql.php');

class Destaque extends DestaqueSQL {
    /*
     * 	Seta a Código Chave	
     * 	@return String
     */

    public function getChave() {
        return "DestaqueCod";
    }

    /*
     * 	Retorna um array com os parametros utilizados no filtro
     * 	@return Array
     */

    public function getParametros() {
        $Fil = new Filtrar();

        $Padrao = array("PaginaAtual", "QuemOrdena", "TipoOrdenacao");

        $MeusParametros = array("DestaqueTitulo", "DestaqueTipo", "DestaqueLink");

        $HiddenParametros = $Fil->getHiddenParametros($MeusParametros);

        return array_merge($Padrao, $MeusParametros, $HiddenParametros);
    }

    /**
     * 	Reponsável pela filtragem dos dados na grid
     * 	@return String
     */
    public function filtrar($ObjForm) {
        $Gr = new GridPadrao();

        //Grid de Visualização- Configurações
        $Gr->setListados(array("DestaqueTitulo", "DestaqueLink"));
        $Gr->setTitulos(array("Titulo", "Link"));

        //Setando Parametros
        Parametros::setParametros("GET", $this->getParametros());

        //Impressão
        if ($_GET['ModoPrint'] == 'true') {
            $Gr->setQLinhas(0);
            $Gr->setModoImpressao(true);
        } else {
            $Gr->setQLinhas(ConfigSIS::$CFG['QLinhasGrid']);
            $Gr->setModoImpressao(false);
        }

        //Configurações Fixas da Grid
        $Gr->setSql(parent::filtrarSql($ObjForm));
        $Gr->setChave($this->getChave());
        $Gr->setTipoOrdenacao($_GET['TipoOrdenacao']);
        $Gr->setQuemOrdena($_GET['QuemOrdena']);
        $Gr->setPaginaAtual($_GET['PaginaAtual']);

        //Retornando a Grid Formatada - HTML
        return $Gr->inForm($Gr->montaGridPadrao(), "FormGrid");
    }

    /**
     * 	Monta Estrutura de Visualização dos Registros Selecionados
     * 	@return String
     */
    public function visualizar() {
        $Gr = new GridVisualizar();

        //Grid de Visualiza? Detalhada
        $Gr->setListados(array("DestaqueTitulo", "DestaquePrioridade", "DestaqueDescricao", "DestaqueTipo", "DestaqueLink", "DestaqueLinkTarget"));
        $Gr->setTitulos(array("Titulo", "Prioridade", "Descrição", "Destaque Tipo", "Destaque Link", "Destino"));

        //Configura?s Fixas da Grid
        $Gr->setChave($this->getChave());

        //Retornando a Grid Formatada - HTML
        if (!is_array($_POST['SisReg']))
            throw new Exception("Nenhum registro selecionado!");

        foreach ($_POST['SisReg'] as $Cod) {
            $Gr->setSql(parent::visualizarSql($Cod));
            $Vis .= '<table width="100%" border="0" cellspacing="0" cellpadding="0">
					  <tr>
						<td width="100px">' . $this->geraFotoVis($Cod) . '</td>
						<td>' . $Gr->montaGridVisualizar() . '</td>
					  </tr>
					</table>';
        }

        return $Vis;
    }

    public function geraFotoVis($Id) {
        //Inicia Conexão
        $Con = Conexao::conectar();

        $FotosSql = $Con->execLinhaArray(parent::getDadosFotosSql($Id));

        $Foto = '<table border="0" cellspacing="10" cellpadding="10" >';

        $Foto.= '<div><img src=' . $_SESSION["UrlBaseSite"] . 'arquivos/destaque/' . $FotosSql["DestaqueImagem"] . ' border="0"  width="100px" height="100px"/></div>';

        $Foto.='</table>';

        return $Foto;
    }

    /**
     * 	Reponsável pelo Cadastro das Informações
     * 	@return Void
     */
    public function cadastrar($ObjForm) {
        //Inicia Conexão
        $Con = Conexao::conectar();

        //Inicia Transação		
        $Con->startTransaction();

        //Inicia Classe de Logs
        $Log = new Log();

        //Executa Sql		
        $Con->executar(parent::cadastrarSql($ObjForm));

        //Código Gerado
        $UnidadeCod = $Con->ultimoInsertId();

        $this->cadastraFoto($UnidadeCod);

        //Grava Log
        $Log->geraLog($UnidadeCod);

        //Finaliza Transação					
        $Con->stopTransaction();
    }

    public function cadastraFoto($UnidadeCod) {
        $Con = Conexao::conectar();

        $Arq = new Arquivos();
        $Arq->setQualidade("95");

        $NomeArquivo = "destaque" . $UnidadeCod . "." . strtolower($Arq->extenssaoArquivo($_FILES['DestaqueImagem']['name'][0]));
        $DirBase = $_SESSION['DirBaseSite'] . "arquivos/destaque/" . $NomeArquivo;
        $Posicao = 0;

        //Upload - Imagem 
        $Arq->trataImagem("DestaqueImagem", $DirBase, null, 988, null, $Posicao);

        $Con->executar(parent::alterarImagemSql($UnidadeCod, $NomeArquivo));
    }

    /**
     * 	Reponsável pela alteração das Informações
     * 	@return Void
     */
    public function alterar($ObjForm) {
        //Inicia Conexão
        $Con = Conexao::conectar();

        //Inicia Transação		
        $Con->startTransaction();

        //Inicia Classe de Logs
        $Log = new Log();

        //Executa Sql		
        $Con->executar(parent::alterarSql($ObjForm));

        $Manter = $_POST['Manter'];
        $Id = $ObjForm->getCampoRetorna('Id');

        if ($Manter != "Ok") {
            $Con->setLogOculto(true);

            $this->alteraFoto($Id);
        }


        //Grava Log
        $Log->geraLog($ObjForm->getCampoRetorna('Id'));

        //Finaliza Transação					
        $Con->stopTransaction();
    }

    public function alteraFoto($Id) {
        $Con = Conexao::conectar();

        $Arq = new Arquivos();

        $Arquivo = $Con->execLinha(parent::getDadosFotosSql($Id));

        $NomeArquivoAntigo = $Arquivo['DestaqueImagem'];

        @unlink($_SESSION['DirBaseSite'] . "arquivos/destaque/" . $NomeArquivoAntigo);

        $NomeArquivoNovo = "destaque" . $Id . "." . strtolower($Arq->extenssaoArquivo($_FILES['DestaqueImagem']['name'][0]));

        $DirBase = $_SESSION['DirBaseSite'] . "arquivos/destaque/" . $NomeArquivoNovo;
        $Posicao = 0;
        //Upload - Imagem 
        $Arq->trataImagem("DestaqueImagem", $DirBase, null, 988, null, $Posicao);

        $Con->executar(parent::alterarImagemSql($Id, $NomeArquivoNovo));
    }

    /**
     * 	Reponsável pela exclusão das Informações dos registros selecionados
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

            //Inicia Conexão
            $Con = Conexao::conectar();

            //Inicia Transação
            $Con->startTransaction();

            //Inicia Classe de Logs
            $Log = new Log();

            foreach ($_POST['SisReg'] as $Chave) {

                $Arquivo = $Con->execLinha(parent::getDadosFotosSql($Chave));

                $NomeArquivoAntigo = $Arquivo['DestaqueImagem'];

                @unlink($_SESSION['DirBaseSite'] . "arquivos/destaque/" . $NomeArquivoAntigo);

                $Con->executar(parent::removerSql($Chave));
                $RApagados++;

                //Grava Log
                $Log->geraLog($Chave);
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
        //Instancia Funções PHP
        $FPHP = new FuncoesPHP();

        //Inicia Conexão	
        $Con = Conexao::conectar();

        //Extrai Dados Sql
        $DadosSql = array_values($Con->execLinhaArray(parent::getDadosSql($Id)));

        //Define Campos
        $Campos = array("Id", "DestaqueTitulo", "DestaquePrioridade", "DestaqueDescricao", "DestaqueImagem", "DestaqueTipo", "DestaqueLink", "DestaqueLinkTarget");

        //Combina Sql com Campos Definidos
        $CamposForm = array_combine($Campos, $DadosSql);

        //Extrai Variaveis para o metodo desejado
        $FPHP->extractVar($CamposForm, $Metodo);
    }

}