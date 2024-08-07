<?php
require_once('../sis/framework/config.conf.php');  ConfigSIS::Conf(); $ConfigSIS = new ConfigSIS(); $ConfigSIS->load();  $EngineSiteController = EngineSiteController::getInstancia();



$Controller = EngineSiteController::getInstancia();
$DadosTeste = ['PastaSistema' => 'engine_site', 'NomeModulo' => 'teste', 'Layout' => 'padrao', 'checkBoxArquivos' => 'on', 'checkBoxAjax' => 'on', 'checkBoxController' => 'on', 'checkBoxCss' => 'on', 'checkBoxForm' => 'on', 'checkBoxJs' => 'on', 'checkBoxModel' => 'on', 'checkBoxViews' => 'on', 'Views' => 'teste', 'checkBoxIndex' => 'on', 'checkBoxOpcaoLayout' => 'on'];
$Resp = $Controller->gerarArquivos($DadosTeste);
echo $Resp;


$Front = FrontController::getInstancia();
$Front->setLayout('PADRAO');
$Front->setViews($EngineSiteController->init());
$Front->setParametros($EngineSiteController->getParametros());
$Front->renderizarPagina();