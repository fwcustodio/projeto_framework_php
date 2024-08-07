<?
//Starta Sess�o
session_start();

///Content
header("Content-Type: text/html; charset=ISO-8859-1",true);

//Verifica se sess�o ainda esta ativa
if(empty($_SESSION['UsuarioCod'])) exit("sessaoexpirada");

//Classe de configura��es
include_once($_SESSION['DirBase'].'framework/config.conf.php'); ConfigSIS::Conf();

//Definindo M�dulo/Pacote
define("MODULO", "destaque");
define("PACOTE", "cadastros");

//Chamando Arquivos do Sistema
include_once($_SESSION['FMBase'] . 'acesso.class.php');
$Ac = new Acesso();

//Includes
include_once($_SESSION['DirBase'] . PACOTE . '/' . MODULO . '/' . MODULO . '.class.php');

switch ($_GET['Op']) {
    case "Fil": ##Filtro
        //Verificando permiss�es
        $Ac->setOpcao($_GET['Op']);
        $Ac->acessoModulo();

        include_once($_SESSION['DirBase'] . PACOTE . '/' . MODULO . '/' . MODULO . '.form.php');

        $Form = new DestaqueForm();
        $Des = new Destaque();
        $FPHP = new FuncoesPHP();

        try {

            $Form->setEnv("true");
            $Form->setOp($_GET['Op']);
            $Form->getFormFiltro();

            echo($FPHP->formataErro($Form->getErro()) . $Des->filtrar($Form));
        } catch (Exception $E) {
            echo($E->getMessage());
        }

        break;

    case "Vis" : ##Visualizar 
        //Verificando permiss�es
        $Ac->setOpcao($_GET['Op']);
        $Ac->acessoModulo();

        $Des = new Destaque();

        try {
            echo($Des->visualizar());
        } catch (Exception $E) {
            echo($E->getMessage());
        }

        break;

    case "Cad": ##Cadastrar 
        //Verificando permiss�es
        $Ac->setOpcao($_GET['Op']);
        $Ac->acessoModulo();

        include_once($_SESSION['DirBase'] . PACOTE . '/' . MODULO . '/' . MODULO . '.form.php');

        $Form = new DestaqueForm();
        $Form->setDecodificacao(false); //Semente para requisi��es n�o ajax!

        $Des = new Destaque();

        try {

            $Form->setEnv($_GET['Env']);
            $Form->setOp($_GET['Op']);
            $Form->setNomeForm("FormManu");
            $Campos = $Form->getFormManu();

            if ($Form->getEnv() === true) {
                $Erro = $Form->getErro();

                if (empty($Erro)) {
                    $Des->cadastrar($Form);

                    echo("true");
                } else {
                    $FPHP = new FuncoesPHP();
                    echo $FPHP->formataErro($Erro);
                }
            } else {

                $Op = $Form->getOp();
                include_once($_SESSION['DirBase'] . PACOTE . '/' . MODULO . '/' . MODULO . '.tpl.php');
                echo $Form->geraFuncoes(), $Form->geraMascaras(), $Form->geraOnLoad(),
                $Form->geraValidacaoJS("validaForm" . $Id, "FormManu" . $Id);
            }
        } catch (Exception $E) {
            echo($E->getMessage());
        }

        break;

    case "Alt": ##Alterar
        //Verificando permiss�es
        $Ac->setOpcao($_GET['Op']);
        $Ac->acessoModulo();

        include_once($_SESSION['DirBase'] . PACOTE . '/' . MODULO . '/' . MODULO . '.form.php');

        $Form = new DestaqueForm();
        $Form->setDecodificacao(false); //Semente para requisi��es n�o ajax!
        $Des = new Destaque();

        try {

            $Form->setEnv($_GET['Env']);
            $Form->setOp($_GET['Op']);

            if ($Form->getEnv() === true) {
                $Form->getFormManu();

                $Erro = $Form->getErro();

                if (empty($Erro)) {
                    $Des->alterar($Form);

                    echo("true");
                } else {
                    $FPHP = new FuncoesPHP();
                    echo $FPHP->formataErro($Erro);
                }
            } else {
                if (!is_array($_POST['SisReg']))
                    throw new Exception("Nenhum registro selecionado!");

                foreach ($_POST['SisReg'] as $Id) {
                    $Form->setNomeForm("FormManu" . $Id);
                    $Des->getDados($Id, "POST");
                    $Campos = $Form->getFormManu();
                    $Op = $Form->getOp();

                    include($_SESSION['DirBase'] . PACOTE . '/' . MODULO . '/' . MODULO . '.tpl.php');
                    echo $Form->geraFuncoes(), $Form->geraMascaras(), $Form->geraOnLoad(),
                    $Form->geraValidacaoJS("validaForm" . $Id, "FormManu" . $Id);
                }
            }
        } catch (Exception $E) {
            echo($E->getMessage());
        }

        break;

    case "Del": ##Remover
        //Verificando permiss�es
        $Ac->setOpcao($_GET['Op']);
        $Ac->acessoModulo();

        //Instancia Classe
        $Des = new Destaque();

        //Executa metodo de remo��o	
        echo $Des->remover();

        break;
}
