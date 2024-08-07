<?php
require_once $_SESSION['FMBase'] . 'framework_site/helpers/view_helpers.php';
require_once $_SESSION['FMBase'] . 'framework_site/classes/funcoes_php_site.php';

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of front_controller_base
 *
 * @package framework_site
 * @author fernando
 * @data 30/03/2012
 */
abstract class FrontControllerBase extends ViewHelpers {

    /* Se nenhum layout for setado, apenas as views serão renderizadas
     * e se não possuir views, então uma mensagem de aviso será renderizada*/
    private $Layout;


    /*Scritps passados pelo módulo para serem adicionados ao layout*/
    private $ScritpsModulo;


    /*Contém parâmetros passados pelo modulo, usados para
     * configurar a exibição da página.
     * ex: $ParametrosPagina->TituloInterno - adiciona no layout o titulo interno da página*/
    private $ParametrosPagina;


    /* Conteúdo do array: ex
     * array( array('menu','conteudo'), array('menu' => $Menu, 'conteudo' = > $Conteudo))     *
     * as variaveis $Menu e $Conteudo são geradas no modulo que esta usando esta classe,
     * através do model, controller e das views que ele possui, e depois passada ao
     * FrontController, no arquivo index.php do módulo
     *
     *O EXEMPLO ESTÁ ERRADO - Refazer em breve...! ver como funciona em um controller do site da Oficina
     *  */
    private $Views;


    /* Caso true: renderiza o layout.. false não renderiza ..renderiza as views */
    private $RenderizarLayout = true;


    /* Recupera o nome do layout */
    public function getNomeLayout() {
        return $this->Layout;
    }

    /* Seta o layout que será renderizado */
    public function setLayout($Layout) {
        $this->Layout = $Layout;
    }


    /* Conteúdo do array: ex
     * array( array('menu','conteudo'), array('menu' => $Menu, 'conteudo' => $Conteudo))
     *
     * as variaveis $Menu e $Conteudo são geradas no modulo que esta usando esta classe,
     * através do model, controller e das views que ele possui, e depois passada ao
     * PathControladorSite no arquivo index.php do módulo */
    public function setViews(array $Views) {
        $this->Views = $Views;
    }


    public function setScripts(array $Scripts){
        $this->ScritpsModulo = $Scripts;
    }


    /*Seta os pametros de configuração da página
     * ver comentário na variável $ParametrosPagina*/
    public function setParametros(array $ParametrosPagina) {
        $this->ParametrosPagina = $ParametrosPagina;
    }



    /*Retorna dados da classe passada, como nome do módulo da classe e o endereço seu arquivo.
     *As informações são extraidas através no nome padronizado
     *ex.: ParceirosModel = meu_sistema/www/parceiros/model/parceiros_model.php
     *ex.: LayoutsPadraoModel = meu_sistema/www/layouts/padrao/model/layouts_padrao_model.php
     *ex.: ControllerBase - por ter 'Base' no nome tem tratamento diferenciado a busca é feita
     * no FMBase/framework_site/+nome do modulo no plural(controller - controllers)
     * FMBase/framework_site/controllers/controller_base.php*/
    public function getDadosClassInclude($NomeClasse) {
        $FPHPSite = FuncoesPhpSite::getInstancia();

        $NomeArquivo = $FPHPSite->converteNomeClasseToNomeArquivo($NomeClasse);
        $ArrayPartes = explode('_', $NomeArquivo);
        $Entidade = $ArrayPartes[count($ArrayPartes)-1];

        //verifica se a classe é solicitada em um modulo do sitema ou no framework
        if($Entidade == 'base'){
            $EndBase = $_SESSION['FMBase'].'framework_site/';
            $AdicionalPesquisa = 's';
        }
        else{
            $EndBase = $_SESSION['DirBaseSite'];
            $AdicionalPesquisa = '';
        }

        $Modulo = '';
        $EndConcat = '';
        $Cont = 0;
        $TamanhoNome = 0;

        /*extrai o nome no modulo fazendo a verificação de até onde
         *o começo do arquivo  é o nome do modulo
         *ex.: engine_site_controller... qual é o nome do módulo? engine ou engine_site?
         *então esse foreach faz essa verificão no diretório para checkar*/
        foreach ($ArrayPartes as $Parte) {
            $Adicional = $EndConcat == ''?'':'_';
            $EndConcat .= $Adicional.$Parte.$AdicionalPesquisa;
            $Cont ++;

            if(file_exists($EndBase.$EndConcat)){
                $Modulo = $EndConcat;
                $TamanhoNome = $Cont;
            }
        }

        $EndArquivo = '';
        $TamArray = count($ArrayPartes);
        $EndArquivo = $EndBase.$Modulo.'/';

        /*Verifica a profundidade do direitorio concatenando as pastas até o local do arquivo*/
        for($Cont = 0; $Cont < $TamArray; $Cont ++){
            if(file_exists($EndArquivo.$ArrayPartes[$Cont])){
                $EndArquivo .= $ArrayPartes[$Cont].'/';
            }
            else{
               continue;
            }
        }
        return parent::arrayToObject(array('Modulo' => $Modulo, 'Entidade' => $Entidade, 'NomeArquivo' => $NomeArquivo.'.php', 'EnderecoArquivo' => $EndArquivo));
    }



    /* Seta se o layout será renderizado ou não no modulo
     * Caso seja setado com false será imprimido na tela apenas as views */
    public function setRenderizarLayout($RenderizarLayout) {
        $this->RenderizarLayout = $RenderizarLayout;
    }



    /* Método utilizado para montar e renderizar o layout resultante de acordo
     * com todos os metodos utilizados no index no modulo( layout, titulo, views e etc) */
    protected function getPaginaRenderizada() {
        if ($this->RenderizarLayout == true) {

            $FPHPSite = FuncoesPhpSite::getInstancia();
            $NomeLayout = strtolower($this->Layout);

            require_once $_SESSION['DirBaseSite'].'layouts/'.$NomeLayout.'/controller/layouts_'.$NomeLayout.'_controller.php';

            $NomeLayoutClasse = $FPHPSite->converteNomeArquivoToNomeClasse($NomeLayout);// converte o nome do layout do tipo meu_layout para MeuLayout
            $Controller = 'Layouts'.$NomeLayoutClasse.'Controller';

            eval("\$ControllerLayout = $Controller::getInstancia();");//pega uma instancia do controller do layout DINAMICAMENTE -  sem saber o nome do layout

            $DadosLayout = $ControllerLayout->getDados();
            $DadosViewsLayout = $ControllerLayout->init();
            $ViewsLayout = (is_array($DadosViewsLayout) && count($DadosViewsLayout) > 0)?parent::extractViews($DadosViewsLayout):array();
            $ViewsLayout['Views'] = (is_array($ViewsLayout['Views']) && count($ViewsLayout['Views']) > 0)?$ViewsLayout['Views']:array();
            $ArrayScripts['Scritps'] = (is_array($this->ScritpsModulo) && count($this->ScritpsModulo) > 0)?$this->ScritpsModulo:array();//dados de scritp passados pelo módulo(js e css)

            //$this->Views se refere ás views passadas ao frontcontroller pelo módulo
            $ViewsModulo = (is_array($this->Views) && count($this->Views) > 0)?parent::extractViews($this->Views):array();
            $ViewsModulo['Views'] = (is_array($ViewsModulo['Views']) && count($ViewsModulo['Views']) > 0)?$ViewsModulo['Views']:array();

            //merge(junta) as views passados pelo layout com as views passadas pelo módulo
            $Views['Views'] = array_merge($ViewsLayout['Views'],$ViewsModulo['Views']);

            $ParametrosPagina = is_array($this->ParametrosPagina)?$this->ParametrosPagina:array();

            $DADOS = array();
            $DADOS = array_merge($DADOS, $DadosLayout); //adiciona os dados que vão ser usados no layout
            $DADOS = array_merge($DADOS, $Views);// adicionada todas as views já processadas
            $DADOS = array_merge($DADOS, $ArrayScripts);// adicionada todos os scritps passados pelo módulo(js e css)
            $DADOS = array_merge($DADOS, $ParametrosPagina);//Adiciona o parametros setados no modulo

            $EndLayout = $_SESSION['DirBaseSite'].'layouts/'.$NomeLayout.'/layouts_' . strtolower($this->Layout) . '.phtml';
            return parent::extractLayout($EndLayout, parent::arrayToObject($DADOS));

        //renderiza as views passadas pelo módulo
        } else {
            $ViewsModulo = parent::extractViews($this->Views);
            if(is_array($ViewsModulo['Views'])){
                $Html = '';
            foreach ($ViewsModulo['Views'] as $View):
                $Html.= $View;
            endforeach;
            return $Html;
            }
            else{
                return '<strong>Layouts desabilitados para este módulo.</strong>';
            }
        }
    }
}
?>