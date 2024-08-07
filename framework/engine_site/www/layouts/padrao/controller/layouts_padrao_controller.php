<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of home
 *
 * @author fernando
 */
class LayoutsPadraoController extends ControllerBase {
    private static $Instancia ;
    private $LayoutsPadraoModel;

    public function __construct() {
        $this->LayoutsPadraoModel = new LayoutsPadraoModel();
    }
    
    public static function getInstancia(){
        if(is_a(self::$Instancia, 'LayoutsPadraoController')){return self::$Instancia;} //verifica se o atributo ja est� instanciado: true retorna a instancia
        else{self::$Instancia = new LayoutsPadraoController();return self::$Instancia;}
    }

    public function init(){
        return array();
    }
    
    public function getScripts() {
        return array();
    }
    
    public function getDados(){ 
        $DADOS = array();
        
        //$Form = new LayoutsPadraoFormPrincipal();
        //$Form->setDecodificacao(false);        
        $DADOS['FormPrincipal'] = '<p>Sem form principal</p>'; //$Form->getFormManu();        
        $DADOS['FormValidacoes'] = '';//$Form->geraFuncoes() . $Form->geraMascaras() . $Form->geraOnLoad() . $Form->geraValidacaoJS("validaForm", "FormManu");
        return $DADOS;        
    }

    public function getParametros(){
        return array();
    }
}
?>