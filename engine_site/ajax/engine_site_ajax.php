<?php
require_once('../../sis/framework/config.conf.php'); ConfigSIS::Conf(); $ConfigSIS = new ConfigSIS(); $ConfigSIS->load(); 

 switch ($_GET['Op']){
     case 'geraArquivos':
         if($_GET['Env'] == true){
             $Controller = EngineSiteController::getInstancia();
             $Resp = $Controller->gerarArquivos($_POST);
             echo $Resp;
         }         
         break;
     default : echo 'swith inv�lido no arquivo .ajax';
         break;
 }

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of engine_site_ajax
 *
 * @author fernando
 */
class EngineSiteAjax extends AjaxBase {
    //put your code here
}

?>
