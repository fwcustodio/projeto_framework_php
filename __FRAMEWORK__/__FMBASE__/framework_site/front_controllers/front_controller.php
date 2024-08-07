<?php
require_once $_SESSION['FMBase'].'framework_site/front_controllers/front_controller_base.php';
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of path_controlador_site
 *
 * @package framework_site
 * @author fernando
 * @data 30/03/2012
 */
class FrontController extends FrontControllerBase{
    private static $Instancia ;

    private function __construct() {}

    public static function getInstancia(){
        if(is_a(self::$Instancia, 'FrontController')){return self::$Instancia;} //verifica se o atributo ja está instanciado: true retorna a instancia
        else{self::$Instancia = new FrontController();return self::$Instancia;}
    }

    /*Solicita para o metodo parent::getPaginaRenderizada, a pagina do módulo*/
    public function renderizarPagina(){
        echo parent::getPaginaRenderizada();
    }
}

?>
