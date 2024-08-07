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
define("MODULO","contato");
define("PACOTE","cadastros");

//Includes
include_once($_SESSION['DirBase'].PACOTE.'/'.MODULO.'/'.MODULO.'.class.php');

switch ($_GET['Op'])
{
    case "Contato": ##Contato

        include_once($_SESSION['DirBase'].'cadastros/usuarios/usuarios.form.php');
        include_once($_SESSION['DirBase'].'cadastros/contato/contato.class.php');

        $Form  = new UsuariosForm();
        $Conta = new Contato();

        try
        {
            //Nome do Formulário
            $IdForm = (empty($_POST['IdForm'])) ? "" : $_POST['IdForm'];

            $Cont = (empty($_POST['Cont'])) ? mt_rand() : $_POST['Cont'];
            $Form->setNomeForm("FormManu".$IdForm);
            $CamposCont = $Form->getFormContato($Cont);

            //Conteudo
            $ConteudoTipoContato  = $Conta->getConteudoTipoContato($CamposCont,$IdForm,$Cont);
            $ConteudoTipoContato .= $ConteudoTipoContato;

            echo $Conta->getConteudoContato($CamposCont,$IdForm,$Cont,$ConteudoTipoContato);
        }
        catch (Exception $E)
        {
            echo($E->getMessage());
        }

    break;

    case "TipoContato": ##TipoContato

        include_once($_SESSION['DirBase'].'cadastros/usuarios/usuarios.form.php');
        include_once($_SESSION['DirBase'].'cadastros/contato/contato.class.php');

        $Form  = new UsuariosForm();
        $Conta = new Contato();

        try
        {
            //Nome do Formulário
            $IdForm = (empty($_POST['IdForm'])) ? "" : $_POST['IdForm'];
            $Cont   = (empty($_POST['Cont']))   ? "" : $_POST['Cont'];

            $Form->setNomeForm("FormManu".$IdForm);
            $CamposCont = $Form->getFormTipoContato($Cont);

            $Html = $Conta->getConteudoTipoContato($CamposCont, $IdForm, $Cont);

            echo $Html;
        }
        catch (Exception $E)
        {
                echo($E->getMessage());
        }

    break;
}
?>