<?php
//include_once($_SESSION['FMBase'] . 'conexao.class.php');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of path_controller_base
 *
 * @package framework_site
 * @author fernando
 * @data 30/03/2012
 */
abstract class ModelBase extends ViewHelpers{
    private $Con;

    protected function ModelBase() {
        //$this->Con = Conexao::conectar();
    }

    protected function getConexaoBD() {
        return $this->Con;
    }

    public function startTransaction() {
        $this->Con->startTransaction();
    }

    public function stopTransaction() {
        $this->Con->stopTransaction();
    }

    public function getUltimoInsertId(){
        return $this->Con->ultimoInsertId();
    }
}

?>
