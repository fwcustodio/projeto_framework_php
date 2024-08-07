<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of engine_site_conteudo_arquivos_class
 *
 * @author fernando
 */
class EngineSiteConteudoArquivosClass {
    private $Modulo;
    private $ModuloNomeArquivo;
    
    public function setModulo($Modulo){
        $this->ModuloNomeArquivo = $Modulo;
        $this->Modulo = $this->converteNomeClasse($Modulo);
    }

    public function getAjax() {
        return
                "<?php
    require_once('../../sis/framework/config.conf.php'); ConfigSIS::Conf(); ConfigSIS::load(); $".$this->Modulo."Controller = new ".$this->Modulo."Controller();

 switch (".'$_GET[\'Op\']'."){
     case 'myOption': echo true;
         break;
     default : echo false;
 }
    
    
    class ".$this->Modulo."Ajax extends AjaxBase {
        //put your code here
    }

?>";
    }
    
    
    public function getIndex($Layout){
        $Modulo = $this->converteNomeClasse($Modulo);
        
        return '<?php
require_once(\'../sis/framework/config.conf.php\'); ConfigSIS::Conf(); ConfigSIS::load(); $'.$this->Modulo.'Controller = new '.$this->Modulo.'Controller();

$Front = FrontController::getInstancia();
$Front->setLayout(\''.$Layout.'\');
$Front->setViews($'.$this->Modulo.'Controller->init());
$Front->setScripts($'.$this->Modulo.'Controller->getScripts());
$Front->setParametros($'.$this->Modulo.'Controller->getParametros());
$Front->renderizarPagina();';
        
    }
    
    
    
    public function getController($Modulo = NULL, $Tipo = NULL){//Tipo faz uma especialização do controller adicionando ou removendo alguns componestens
        $GetDados = '';
        if($Modulo){
            $Modulo = $this->converteNomeClasse($Modulo);
        }
        else{
            $Modulo = $this->Modulo;
        }
        
        if(strtolower($Tipo) == 'layout'){
            $GetDados = '
    /*Método usado para passar dados para o layout
      ex.: return array("Nomes" => array("Luís", "Paulo" , "João"))
      então dentro do arquivo .phtml do layout será posível recuperar estes dados
      da seguinte forma: $DADOS->Nomes
    */
    public function getDados(){
        return array();
    }    

';
            $GetInstancia = '
    public static function getInstancia(){
        if(is_a(self::$Instancia, \''.$Modulo.'Controller\')){return self::$Instancia;} //verifica se o atributo ja está instanciado: true retorna a instancia
        else{self::$Instancia = new '.$Modulo.'Controller();return self::$Instancia;}
    }';
            $Instancia = 'private static $Instancia;';
            
            $ArrayConfig = '$ArrayConfig = array(
            array(
                \'View\' => \'Rodape\',
                \'End\' => \'\'
                ),
            array(
                \'View\' => \'Topo\',
                \'End\' => \'\'
                )
        );';
        }
        else{
            $ArrayConfig = '$ArrayConfig = array(
            array(
                \'ViewLayout\' => \'Conteudo\',
                \'View\' => \'conteudo\',
                \'Modulo\' => Modulo
                )
        );';
        }
       return '<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of controller
 *
 * @author
 */
class '.$Modulo.'Controller extends ControllerBase {    
    private $'.$Modulo.'Model;
    '.$Instancia.'

    public function __construct() {
        $this->'.$Modulo.'Model = new '.$Modulo.'Model();
    }    
    '.$GetInstancia.'
    
    //Constante módulo setada automaticamente no ConfigSis    
    public function init(){        
        $DadosViews = array(
            \'conteudo\' => array(
                \'Nomes\' => array(\'Luis\',\'Claudio\',\'Paulo\')
            )
        );
        '.$ArrayConfig.'
        return array(
            \'Config\' => $ArrayConfig,
            \'Dados\' => $DadosViews
        );
    }
    
    //Script e estilos que serão adicionados ao layout
    public function getScripts(){
        return array(
            parent::getScript(\'JS\',$_SESSION[\'UrlBaseSite\'].Modulo.\'/js/main.js\'),
            parent::getScript(\'CSS\', $_SESSION[\'UrlBaseSite\'].Modulo.\'/css/main.css\')
        );
    }    

    '.$GetDados.'
    public function getParametros(){
        return array(
            \'TituloPaginaInterna\' => \''.$Modulo.'\'
        );
    }
}
?>';       
        
    }
    
    public function getCSS(){
        return '/* Menu */

/*
#menu ul {
    text-align:right;
    padding-bottom: 5px;
    margin-top: -30px;
    float:right;
}

#menu li {
    display:inline;
    list-style:none;
}

#menu img {
    height: 28px;
}

#menu li a {
    list-style:none;
    font:18px Calibri, sans-serif;
    color: #FFFFFF;
    text-decoration:none;
    float:left;
    padding: 7px;
}
*/';
    }
    
    public function getForm(){
        return '<?

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of form
 *
 * @author
 */
class '.$this->Modulo.'Form extends FormBase {

    public function '.$this->Modulo.'Form() {
        parent::FormCampos();
    }

    public function getFormManu() {
        $Metodo = "POST";
        $Op = $_GET[\'Op\'];
        
        $ArrayForm["Campo1"] = parent::inputTexto(array(
                    "Nome" => "Campo1",
                    "Identifica" => "Campo1",
                    "Valor" => parent::retornaValor($Metodo, "Campo1"),
                    "Id" => "Campo1",
                    "Largura" => 30,
                    "Min" => 1,
                    "Max" => 30,
                    "Tratar" => array("L", "H", "A"),
                    "ValidaJS" => true,
                    "Adicional" => "style=\'width:180px;\'"), true);

        $ArrayForm["Campo2"] = parent::listaVetor(array(
                    "Nome" => "Campo2",
                    "Identifica" => "Campo2",
                    "Valor" => parent::retornaValor($Metodo, "Campo2"),
                    "Status" => true,
                    "ValidaJS" => false,
                    "Inicio" => true,
                    "Vetor" => array("C" => "Componente", "I" => "Modulo")), false);

        $ArrayForm["Submit"] = parent::botao(array(
                    "Nome" => "Submit",
                    "Identifica" => "Submit",
                    "Tipo" => "button",
                    "Estilo" => "cursor:pointer;margin-top:50px;",
                    "Adicional" => " class=\"botaoForm\" onClick=\"if(validaForm())minhaFuncaoCadastro()}\""));
        return $ArrayForm;
    }
}
?>';
    }
    
    
    public function getJS(){
        return 'function ajaxBasico(){
    $.ajax({
        url: UrlBaseSite+\''.$this->ModuloNomeArquivo.'/ajax/'.$this->ModuloNomeArquivo.'_ajax.php?Op=getResposta&Env=true\',
        type: \'POST\',
        datatype:\'html\',
        data: $(\'#FormManu\').serialize(),
        complete:function(Req){
            retornoAjaxBasico(Req.responseText);
        }
    });
}';
    }
    
    
    public function getModel($Modulo = null){
        if($Modulo){
            $Modulo = $this->converteNomeClasse($Modulo);
        }
        else{
            $Modulo = $this->Modulo;
        }
        return '<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of model
 *
 * @author
 */
class '.$Modulo.'Model extends ModelBase {
    
    function __construct() {
        parent::ModelBase();
    }
    
        public function getDados(){
//        $Sql = \'SELECT SecaoCod, SecaoNome, Link, LinkTipo, LinkTarget
//                FROM secao
//                WHERE Publicar = "S"
//                AND Situacao = "A"
//                ORDER BY SecaoPosicao\';
//        return parent::getConexaoBD()->execTodosArray($Sql);
    }
}
?>';
    }
    
    public function getView(){
        return '<!-- É importante utilizar os helpers para um código mais limpo e facil de manter
Ex: getDocType() que retorna o doctype padrão -->
            
<div id="todoConteudo">
    <div id="conteudoPrincipal">
                <? 
//            foreach ($DADOS->'.$this->Modulo.' as $Item): var_dump($Item);
//            endforeach;
        ?>
    </div>
</div>';
    }
    
    
    public function getLayout(){
        return '<?= $this->getDocType() ?>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title><?= $this->getTitulo() ?></title>
        <?= $this->getMetaCodificacao(\'ISO-8859-1\') ?>
        <? foreach ($this->getScritpsPadrao() as $Script): echo $Script; endforeach; //adiciona os scripts padrões do site.. lightbox, validação, util etc ?>
        <? foreach ($this->getEstilosPadrao() as $Estilo): echo $Estilo; endforeach; //adiciona os estilos(css) padrões do site..?>
        <?= $this->getScript(\'CSS\', UrlBaseSite . \'css/main.css\') ?>
        <?= $this->getScript(\'JS\', UrlBaseSite . \'js/main.js\') ?>
        <? foreach ($DADOS->Scritps as $Script): echo $Script; endforeach; ?>
    </head>
    <body>
        
        <div id="topo">
            <?= $DADOS->Views->Topo ?><!-- View topo  - Menu, Slider ,Logo.. etc-->
        </div>
        
        <div id="main">
            <div id="conteudo">
                <?= $DADOS->Views->Conteudo ?><!-- View conteúdo -->
            </div>
            <div style="clear: both;"></div>
        </div>

        <div id="divRodape">
            <?= $DADOS->Views->Rodape ?><!-- View rodapé -->
        </div>

    </body>
</html>';
    }
    

    private function converteNomeClasse($NomeArquivo) {
        $NomeClasse = '';
        $ArrayPartes = explode('_', $NomeArquivo);
        foreach ($ArrayPartes as $Parte) {
            $NomeClasse .= ucfirst($Parte);
        }
        return $NomeClasse;
    }
}
?>
