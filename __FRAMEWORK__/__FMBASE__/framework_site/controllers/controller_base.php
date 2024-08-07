<?php
require_once $_SESSION['FMBase'] . 'framework_site/helpers/view_helpers.php';

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
abstract class ControllerBase extends ViewHelpers {
    abstract function init();
    abstract function getScripts();
    abstract function getParametros();
}

?>
