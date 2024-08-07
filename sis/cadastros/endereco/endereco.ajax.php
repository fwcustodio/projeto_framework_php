<?
//Starta Sessão
session_start();

///Content
header("Content-Type: text/html; charset=ISO-8859-1",true);

//Verifica se sessão ainda esta ativa
if(empty($_SESSION['DirBase'])) exit("Sessão Expirada!");

//Classe de configurações
include_once($_SESSION['DirBase'].'framework/config.conf.php'); ConfigSIS::Conf();

//Definindo Módulo/Pacote
define("MODULO","endereco");
define("PACOTE","cadastros");

//Includes
include_once($_SESSION['DirBase'].PACOTE.'/'.MODULO.'/'.MODULO.'.class.php');

switch ($_GET['Op'])
{
    case "End": ##Endereço

        include_once($_SESSION['DirBase'].'cadastros/usuarios/usuarios.form.php');

        $Form = new UsuariosForm();

        try
        {
            //Nome do Formulário
            $IdForm = (empty($_POST['IdForm'])) ? "" : $_POST['IdForm'];

            $Cont = (empty($_POST['Cont'])) ? mt_rand() : $_POST['Cont'];
            $Form->setNomeForm("FormManu".$IdForm);
            $CamposEnd = $Form->getFormEndereco($Cont);

            include_once($_SESSION['DirBase'].'cadastros/endereco/endereco.tpl.php');
            echo $Form->geraFuncoes(), $Form->geraMascaras(), $Form->geraOnLoad();
        }
        catch (Exception $E)
        {
            echo($E->getMessage());
        }
        
    break;
}
?>