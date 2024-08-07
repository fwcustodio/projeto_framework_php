<?php
require_once($_SESSION['FMBase'] . 'form_campos.class.php');
require_once($_SESSION['FMBase'] . 'ajax.class.php');

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
abstract class FormBase extends FormCampos {
    abstract public function getFormManu();
}

?>
