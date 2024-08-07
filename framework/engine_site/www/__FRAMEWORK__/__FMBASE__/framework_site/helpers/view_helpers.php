<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of path_controlador_helpers
 *
 * @package framework_site
 * @author fernando
 * @data 30/03/2012
 */
abstract class ViewHelpers {
    private $TipoDocType = 'TRANSITIONAL';
    private $TituloPagina;
    private $InstanciaFPHP;


    /* Seta o tipo de docType a ser usado */
    public function setTipoDocType($Tipo) {
        $this->TipoDocType = $Tipo;
    }



    /* Seta o titulo da página */
    public function setTitulo($Titulo) {
        $this->TituloPagina = $Titulo;
    }
    
    


    /* Retorna o titulo da página
     * caso ele não esteja setado o método retorna o título padrão da aplicação
     * que está setada na classe ConfigSIS */
    protected function getTitulo() {
        return $this->TituloPagina ? $this->TituloPagina : ConfigSIS::$CFG['TituloAdm'];
    }
    



    /*Retorna a instancia da FPHP*/
    protected function getFPHP(){
        require_once($_SESSION['FMBase'] . 'funcoes_php.class.php');
        if(is_a($this->InstanciaFPHP, FuncoesPHP)){
            return $this->InstanciaFPHP;
        }
        else{
            $this->InstanciaFPHP = new FuncoesPHP();
            return $this->InstanciaFPHP;
        }
    }





    /* Retorna o doctype ja formatado para a página
     * caso o tipo do doctype não seja setado no módulo, será usado o doctype padrão
     * TRANSITIONAL */
    protected function getDocType() {
        if (strtoupper($this->TipoDocType) == 'TRANSITIONAL') {
            return '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
        } else if (strtoupper($this->TipoDocType) == 'STRICT') {
            return '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">';
        } else {
            throw new InvalidArgumentException("Doctype <i>$this->TipoDocType</i> inválido!");
        }
    }


    /*Retorna em html a tag meta com a codificação passada
     *se não passado nenhuma, então é retornada a iso-8859-1*/
    protected function getMetaCodificacao($Codificacao = NULL) {
        if (!$Codificacao) {
            $Codificacao = 'ISO-8859-1';
        }
        return "<meta http-equiv='Content-Type' content='text/html; charset=$Codificacao'/>";
    }




    /* Recebe um array como parâmetro e retorna uma string com os dados */
    protected function getConteudoArray(array $Array) {
        implode($VarRetorno, $Array);
        return $VarRetorno;
    }



    /* Retorna uma tag script ou link, de acordo com os parâmetros passados */
    protected function getScript($Type, $SRC, $Conteudo = NULL) {
        if (strtoupper($Type) == 'JAVASCRIPT' || strtoupper($Type) == 'JS') {
            $SRC = $SRC == ''?'':'src="'.$SRC.'"';
            return "<script type='text/javascript' $SRC >$Conteudo</script>";
        } else if (strtoupper($Type) == 'CSS') {
            $SRC = $SRC == ''?'':'href="'.$SRC.'"';
            return "<link type='text/css' rel='stylesheet' $SRC/>";
        }
    }



    /* Retorna uma link(a) de acordo com os parâmetros passados
     * id, class, title.. */
    protected function getLink($Destino, $Title, $Conteudo, $Id = '', $Class = '', $Target = '') {
        $Id = $Id == ''?'':'id="'.$Id.'"';
        return "<a href='$Destino' title='$Title' $Id class='$Class' target='$Target' >$Conteudo</a>";
    }




    /*Retorna um checkbox*/
    protected function getInputCheckBox($Nome, $Valor = '', $Id = '', $Classe = '', $Checked = ''){
        $Id = $Id == ''?'':'id="'.$Id.'"';
        if(isset($Checked) && $Checked === TRUE){$Checked = 'checked';}
        return "<input type='checkbox' name='$Nome' $Id class='$Classe' value='$Valor' $Checked/>";
    }




    /*Retorna um input radio*/
    protected function getInputRadio($Nome, $Valor = '', $Id = '', $Classe = '', $Checked = ''){
        $Id = $Id == ''?'':'id="'.$Id.'"';
        if(isset($Checked) && $Checked === TRUE){$Checked = 'checked';}
        return "<input type='radio' name='$Nome' $Id class='$Classe' value='$Valor' $Checked/>";
    }




    /*Retorna uma img devidamente configurada de acordo com os parâmetros passados*/
    protected function getIMG($SRC, $Alt, $Width = '', $Height='', $Id = '', $Classe = '', $Adicional = ''){
        $Id = $Id == ''?'':'id="'.$Id.'"';
        $Height = $Height == ''?'':'height="'.$Height.'"';
        $Width = $Width == ''?'':'width="'.$Width.'"';
        return "<img src='$SRC' alt='$Alt' $Width $Height $Id class='$Classe' $Adicional/>";
    }




    /*Recebe um mysqli_result e retorna um array com os dados desse result*/
    protected function execToArray(mysqli_result $Exec){
        $ArrayResult = array();
        while ($Array = mysqli_fetch_array($Exec)){;
            $ArrayResult[] =  $Array;
        }
        return $ArrayResult;
    }



    /*Conta a quantidade de item em um iten -  arrays, objetos, etc..
  *função temporária em quanto não é implementado a interface contable em
  *uma classe que será a base*/
  protected function count($Itens) {
        $Cont = 0;
        foreach ($Itens as $Item) {
            $Cont++;
        }
        return $Cont;
    }



    /*Gera tabs multimidia e tabs arquivos relativo ao modulo e id passados
     *os componetes utilizados estaram nas pasta Dir passada por paâmetro*/
    protected function getTabsMultimidia($Modulo, $Id, $Dir = NULL) {
        $Dir = $Dir?$Dir:$_SESSION['FMBase'].'multimidia/';

        include_once($Dir.'componentes/tabs.php'); $Tabs = new Tabs();
        include_once($Dir.'componentes/tabs_multimidia.php'); $TabsMultimidia = new TabsMultimidia();
        include_once($Dir.'componentes/tabs_arquivos.php'); $TabsArquivos = new TabsArquivos();

        $ArrayAbas['Fotos'] = $TabsMultimidia->getMultimidia(array('Id' => $Id, 'Modulo' => $Modulo, 'Tipo' => 'F'));
        $ArrayAbas['Audios'] = $TabsMultimidia->getMultimidia(array('Id' => $Id, 'Modulo' => $Modulo, 'Tipo' => 'A'));
        $ArrayAbas['Vídeos'] = $TabsMultimidia->getMultimidia(array('Id' => $Id, 'Modulo' => $Modulo, 'Tipo' => 'V'));
        $ArrayAbas['Arquivos'] = $TabsArquivos->getArquivos(array('Id' => $Id, 'Modulo' => $Modulo));
        $ArrayConfig = array('PrimeiraAba' => 'Fotos');
        return $Tabs->exibeTabs($ArrayAbas,$ArrayConfig);
    }



    /*Retorna um array com a paginaca(html) e um array com os dados abtidos com a sql passada
     *não é necessario dar includes ou algo parecido, basta chamar este metodo e obter o retorno*/
    protected function paginacao($Sql, $Chave, $PaginaAtual = 1, $MetodoJavascript = 'listarPaginacao', $AlterarLinhas = false, $TotalPorPagina = 12){
        require_once($_SESSION['FMBase'].'paginacao.class.php');

        $Pag = new Paginacao();
        $Pag->setSql($Sql);
	$Pag->setChave($Chave);
        $Pag->setAlterarLinhas($AlterarLinhas);
	$Pag->setQLinhas($TotalPorPagina);
	$Pag->setMetodoFiltra($MetodoJavascript);
	$Pag->setPaginaAtual($PaginaAtual);
        return array('ArrayDados' => $this->execToArray($Pag->rsPaginado()), 'Paginacao' => $Pag->listaResultados());
    }




    /*Retorna os scritps padrão usados no desenvolvimento de site como
     *util.js, lightbox, validações e etc*/
    protected function getScritpsPadrao(){
        return array(
            $this->getScript('JS', $_SESSION['JSBase'] . 'js/jquery-1.7.1.min.js'),
            $this->getScript('JS', $_SESSION['JSBase'] . 'js/util.js'),
	    $this->getScript('JS', $_SESSION['JSBase'] . 'js/controller_javascript.js'),
            $this->getScript('JS', $_SESSION['JSBase'] . 'js/validacao.js'),
            $this->getScript('JS', $_SESSION['JSBase'] . 'js/jquery-lightbox-0.5/js/jquery.lightbox-0.5.pack.js'),
            $this->getScript('JS', $_SESSION['JSBase'] . 'js/jquery.blockUI.js'),
            $this->getScript('JS', $_SESSION['JSBase'] . 'js/modal.js'),
            $this->getScript('JS', $_SESSION['JSBase'] . 'js/tabs.js'),
            $this->getScript('JS', '', 'UrlBaseSite = "'.$_SESSION['UrlBaseSite'].'";'),
            $this->getScript('JS', '', 'JSBase = "'.$_SESSION['JSBase'].'";'),
            $this->getScript('JS', $_SESSION['JSBase'] . 'js/comandos_padroes.js')
            );
    }




    /*Retorna os estilo padrão da página.. css dos tabs, lightbox e etc*/
    protected function getEstilosPadrao(){
        return array(
            $this->getScript('CSS', $_SESSION['CSSBase'] . 'css/tabs.css'),
            $this->getScript('CSS', $_SESSION['JSBase'] . 'js/jquery-lightbox-0.5/css/jquery.lightbox-0.5.css'),
            $this->getScript('CSS', $_SESSION['CSSBase'] . 'css/framework_site/padrao.css')
        );
    }




    //by Fernando
    /* Converte um array e seu filhos para objeto
     * ex: var = array('teste'=>'value') - var->teste que retorna value */
    protected function arrayToObject($Array) {
        if (is_array($Array)) {
            foreach ($Array as $Key => $Value) {
                if (is_array($Value)) {
                    $Array[$Key] = (object) $this->arrayToObject($Value);
                }
            }
        }
        return (object) $Array;
    }



    /* Retorna o layout, ou view, já renderizado(carregado com os valores)
     * obs.: Todas as variáveis locais da função e os métodos
     * da classe Helper, estão acessiveis através do $this-> e serão carregados
     * pelo layout .phtml setado */
    protected function extract($Arquivo, $DADOS){
        if (!defined('UrlBaseSite')) {define('UrlBaseSite', $_SESSION['UrlBaseSite']);}
        if (is_file($Arquivo)) {
            ob_start();
            include $Arquivo;
            return ob_get_clean();
        } else {
            throw new InvalidArgumentException("ERRO! Arquivo " . $Arquivo . ' não encontrado!');
        }
    }





    /* Função usada para formatar o código html exebito
     * serve para identar - alinhar e deixar o código mais padronizado */
    protected function formataHTML($ConteudoHTML) {
        $Dom = new DOMDocument();
        $Dom->formatOutput = true;
        $Dom->preserveWhiteSpace = false;
//        $Dom->loadHTML($ConteudoHTML);
//        return $Dom->saveHTML();
        return $ConteudoHTML;
    }




    /* Retorna o layout já renderizado(carregado com os valores)
     * obs.: Todas as variáveis locais da função e os métodos
     * da classe Helper, estão acessiveis através do $this-> e serão carregados
     * pelo layout .phtml setado */
    protected function extractLayout($Layout, stdClass $DADOS) {
            return $this->formataHTML($this->extract($Layout, $DADOS));
    }




    /* Retorna as views, já renderizadas(carregadas com os valores)
     * obs.: Todas as variáveis locais da função e os métodos
     * da classe Helper, estão acessiveis através do $this-> e serão carregados
     * pelas views .phtml setadas */
    protected function extractViews(array $DadosViewsLayout){
        $DadosViews = array();

        if(is_array($DadosViewsLayout['Config'])){
            foreach ($DadosViewsLayout['Config'] as $View) {
            $NomeView = strtolower($View['View']);

            if($View['Modulo'] != '' && $View['Modulo'] != NULL){
                $Modulo = strtolower($View['Modulo']);
                $End = $_SESSION['DirBaseSite'].$Modulo .'/views/'.$Modulo.'_view_'.$NomeView.'.phtml';
            }
            else if($View['End'] != '' && $View['End'] != NULL){
                $End = $View['End'];
            }
            else{
                $End = $_SESSION['DirBaseSite'].'layouts/views/'.$NomeView.'/layouts_views_'.$NomeView.'.phtml';
            }

            if(!file_exists($End)){
                throw new InvalidArgumentException('ERRO! View '.$NomeView.' não foi encontrada no endereço '.$End);
            }
            else{
                $NomeViewLayout = $View['ViewLayout']?$View['ViewLayout']:$View['View'];
                $DadosViews['Views'][$NomeViewLayout] = $this->extract($End, $this->arrayToObject($DadosViewsLayout['Dados'][$View['View']]));
            }
        }
        return $DadosViews;
        }
        else{
            throw new InvalidArgumentException('ERRO! - Parametros passados no metodo <strong> getViews()<strong> do controller são inválidos');
        }
    }
}
?>
