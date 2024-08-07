<?
//Starta Sessão
session_start();

///Content
header("Content-Type: text/html; charset=ISO-8859-1",true);

//Verifica se sessão ainda esta ativa
if(empty($_SESSION['UsuarioCod'])) exit("sessaoexpirada");

//Classe de configurações
include_once($_SESSION['DirBase'].'framework/config.conf.php'); ConfigSIS::Conf();

//Definindo Módulo/Pacote
define("MODULO", "enquete");
define("PACOTE", "conteudo");

//Chamando Arquivos do Sistema
include_once($_SESSION['FMBase'] . 'acesso.class.php');
$Ac = new Acesso();

//Includes
include_once($_SESSION['DirBase'] . PACOTE . '/' . MODULO . '/' . MODULO . '.class.php');

switch ($_GET['Op']) {
    case "Fil": ##Filtro
        //Verificando permissões
        $Ac->setOpcao($_GET['Op']);
        $Ac->acessoModulo();

        include_once($_SESSION['DirBase'] . PACOTE . '/' . MODULO . '/' . MODULO . '.form.php');

        $Form = new EnqueteForm();
        $Enq = new Enquete();
        $FPHP = new FuncoesPHP();

        try {
            $Form->setEnv("true");
            $Form->setOp($_GET['Op']);
            $Form->getFormFiltro();

            //Tipo de Filtro
            $Pop = $_GET['Pop'];

            echo (empty($Pop)) ? ($FPHP->formataErro($Form->getErro()) . $Enq->filtrar($Form)) : ($FPHP->formataErro($Form->getErro()) . $Enq->filtrarPop($Form));
        } catch (Exception $E) {
            echo($E->getMessage());
        }

        break;

    case "Vis" : ##Visualizar 
        //Verificando permissões
        $Ac->setOpcao($_GET['Op']);
        $Ac->acessoModulo();

        $Enq = new Enquete();

        try {
            echo($Enq->visualizar());
        } catch (Exception $E) {
            echo($E->getMessage());
        }

        break;

    case "Cad": ##Cadastrar 
        //Verificando permissões
        $Ac->setOpcao($_GET['Op']);
        $Ac->acessoModulo();

        include_once($_SESSION['DirBase'] . PACOTE . '/' . MODULO . '/' . MODULO . '.form.php');

        $Form = new EnqueteForm();
        $Enq = new Enquete();

        try {

            $Form->setEnv($_GET['Env']);
            $Form->setOp($_GET['Op']);
            $Form->setNomeForm("FormManu");
            $Campos = $Form->getFormManu();

            if ($Form->getEnv() === true) {
                $ArrayDados = $_POST['ContadorDados'];

                foreach ($ArrayDados as $Cod)
                    $Form->getFormDados($Cod);

                $Erro = $Form->getErro();

                if (empty($Erro)) {
                    $Enq->cadastrar($Form);

                    echo("true");
                } else {
                    $FPHP = new FuncoesPHP();
                    echo $FPHP->formataErro($Erro);
                }
            } else {

                $Op = $Form->getOp();
                $Form->setOnLoad("CarregarCampo", "addDados(''); addDados('');");
                include_once($_SESSION['DirBase'] . PACOTE . '/' . MODULO . '/' . MODULO . '.tpl.php');
                echo $Form->geraFuncoes(), $Form->geraMascaras(), $Form->geraOnLoad(),
                $Form->geraValidacaoJS("validaForm" . $Id, "FormManu" . $Id);
            }
        } catch (Exception $E) {
            echo($E->getMessage());
        }

        break;

    case "Alt": ##Alterar
        //Verificando permissões
        $Ac->setOpcao($_GET['Op']);
        $Ac->acessoModulo();

        include_once($_SESSION['DirBase'] . PACOTE . '/' . MODULO . '/' . MODULO . '.form.php');

        $Form = new EnqueteForm();
        $Enq = new Enquete();

        try {

            $Form->setEnv($_GET['Env']);
            $Form->setOp($_GET['Op']);

            if ($Form->getEnv() === true) {
                $Form->getFormManu();

                $ArrayDados = $_POST['ContadorDados'];

                foreach ($ArrayDados as $Cod)
                    $Form->getFormDados($Cod);

                $Erro = $Form->getErro();

                if (empty($Erro)) {
                    $Enq->alterar($Form);

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
                    $Enq->getDados($Id, "POST");
                    
                    //Quantidade de Voto
                    $_POST["QuantidadeVotoEnquete"] = $Enq->getQuatidadeVotoEnquete($Id);
                    
                    $Campos = $Form->getFormManu();
                    $Op = $Form->getOp();

                    $ConteudoRespostas = $Enq->getDadosDados($Id, $Form);

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
        //Verificando permissões
        $Ac->setOpcao($_GET['Op']);
        $Ac->acessoModulo();

        //Instancia Classe
        $Enq = new Enquete();

        //Executa metodo de remoção	
        echo $Enq->remover();

        break;

    case "AddDados": ##Adicionando Dados

        include_once($_SESSION['DirBase'] . PACOTE . '/' . MODULO . '/' . MODULO . '.form.php');
        $Form = new EnqueteForm();

        $Cod = $_POST['RandJS'];
        $EnqueteNumero = $_POST['conta'];

        $IdForm = $_POST['IdForm'];

        $Campos = $Form->getFormDados($Cod);

        echo "<div id='campoRespostas' class='dadosLinha" . $Cod . "'>" . $Campos['ContadorDados'] . $Campos['EnqueteResposta'] . "&nbsp;&nbsp;<img src=\"" . $_SESSION['UrlBase'] . "figuras/del_2.gif\" border=\"0\" onClick=\"removeItenDado(this, '" . $IdForm . "')\" style=\"cursor:pointer\" /></div>";

        break;

    case "GetEnquete": ##Dados da Enquete

        try {
            //Instancia Classe
            $Enq = new Enquete();

            //Executa metodo de remoção	
            echo $Enq->getNomeEnquete($_POST['EnqueteCod']);
        } catch (Exception $E) {
            echo "";
        }

        break;
}
