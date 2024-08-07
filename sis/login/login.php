<?
//Starta Sess�o
session_start();

// HTTP/1.1 - Elimina Cache
@header("Cache-Control: no-store, no-cache, must-revalidate");
@header("Cache-Control: post-check=0, pre-check=0", false);
@header("Content-Type: text/html; charset=ISO-8859-1",true);

//Busca Dados de Configura��o
include_once('../framework/config.conf.php'); ConfigSIS::Conf();

include_once($_SESSION['DirBase'].'login/login.class.php'); 
include_once($_SESSION['DirBase'].'login/login.form.php');  

//M�dulo
define("MODULO","login");

$Form  = new LoginForm();
$Login = new Login();

//Setando Variaveis
$Form->setEnv("true");
$Form->getFormManu();

//Autentica
try 
{
	echo ($Login->verificaUsuario($Form)) ? "true" : "Nome do Usu�rio ou Senha Inv�lidos";
}
catch (Exception $E)
{
	echo $E->getMessage();
}
?>