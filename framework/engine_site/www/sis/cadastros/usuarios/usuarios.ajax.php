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
define("MODULO","usuarios");
define("PACOTE","cadastros");

//Chamando Arquivos do Sistema
include_once($_SESSION['FMBase'].'acesso.class.php');
$Ac = new Acesso();

//Includes
include_once($_SESSION['DirBase'].PACOTE.'/'.MODULO.'/'.MODULO.'.class.php');

switch ($_GET['Op'])
{
    case "Fil": ##Filtro

        //Verificando Permissões
        $Ac->setOpcao($_GET['Op']);
        $Ac->acessoModulo();

        include_once($_SESSION['DirBase'].PACOTE.'/'.MODULO.'/'.MODULO.'.form.php');

        $Form = new UsuariosForm();
        $USR  = new Usuarios();
        $FPHP = new FuncoesPHP();

        try
        {
            $Form->setEnv("true");
            $Form->setOp($_GET['Op']);
            $Form->getFormFiltro();

            echo($FPHP->formataErro($Form->getErro()).$USR->filtrar($Form));
        }
        catch (Exception $E)
        {
                echo($E->getMessage());
        }

    break;

    case "Vis" : ##Visualizar

        //Verificando Permissões
        $Ac->setOpcao($_GET['Op']);
        $Ac->acessoModulo();

        include_once($_SESSION['DirBase'].PACOTE.'/'.MODULO.'/'.MODULO.'.form.php');
        include_once($_SESSION['DirBase'].PACOTE.'/'.MODULO.'/'.MODULO.'_permissao.class.php');

        $USR  = new Usuarios();

        try
        {
                echo($USR->visualizar());
        }
        catch (Exception $E)
        {
                echo($E->getMessage());
        }

    break;

    case "Cad": ##Cadastrar

        //Verificando Permissões
        $Ac->setOpcao($_GET['Op']);
        $Ac->acessoModulo();

        include_once($_SESSION['DirBase'].PACOTE.'/'.MODULO.'/'.MODULO.'.form.php');
        include_once($_SESSION['DirBase'].PACOTE.'/'.MODULO.'/'.MODULO.'_permissao.class.php');

        $Form = new UsuariosForm();
        $USR  = new Usuarios();
        $FPHP = new FuncoesPHP();

        try
        {
            $Form->setEnv($_GET['Env']);
            $Form->setOp($_GET['Op']);
            $Form->setNomeForm("FormManu");
            $Campos = $Form->getFormManu();
            $Campos = array_merge($Form->getFormAcesso(), $Campos);

            if($Form->getEnv() === true)
            {
                $Erro .= $Form->getErro();

                if(empty($Erro))
                {
                    $USR->cadastrar($Form);

                    echo("true");
                }
                else
                {
                    echo $FPHP->formataErro($Erro);
                }
            }
            else
            {
                $Op = $Form->getOp();
                include_once($_SESSION['DirBase'].PACOTE.'/'.MODULO.'/'.MODULO.'.tpl.php');

                //Loads
                $Form->setOnLoad("Abas","manipulaAbas('', 1)");
                $Form->setOnLoad("ContEnd", "addContato(''); addEndereco(''); ");
//                $Form->setOnLoad("Tipo", "tipoPessoa(''); ");

                echo $Form->geraFuncoes(), $Form->geraMascaras(), $Form->geraOnLoad(),
                     $Form->geraValidacaoJS("validaForm".$Id,"FormManu".$Id);

            }
        }
        catch (Exception $E)
        {
                echo($E->getMessage());
        }

    break;

    case "Alt": ##Alterar

        //Verificando Permissões
        $Ac->setOpcao($_GET['Op']);
        $Ac->acessoModulo();

        include_once($_SESSION['DirBase'].PACOTE.'/'.MODULO.'/'.MODULO.'.form.php');
        include_once($_SESSION['DirBase'].PACOTE.'/'.MODULO.'/'.MODULO.'_permissao.class.php');
        include_once($_SESSION['DirBase'].'cadastros/contato/contato.class.php');
        include_once($_SESSION['DirBase'].'cadastros/endereco/endereco.class.php');

        $Form = new UsuariosForm();
        $USR  = new Usuarios();
        $FPHP = new FuncoesPHP();

        //Classe de Contatos
        $Conta = new Contato();

        //Classe Endreço
        $End   = new Endereco();

        try
        {
            $Form->setEnv($_GET['Env']);
            $Form->setOp($_GET['Op']);

            if($Form->getEnv() === true)
            {
                $Form->getFormManu();
                $Form->getFormAcesso();

                $Erro = $Form->getErro();

                if(empty($Erro))
                {
                    $USR->alterar($Form);

                    echo("true");
                }
                else
                {
                    echo $FPHP->formataErro($Erro);
                }
            }
            else
            {
                if(!is_array($_POST['SisReg'])) throw new Exception("Nenhum registro selecionado!");

                foreach ($_POST['SisReg'] as $Id)
                {
                    $Campos = array();

                    //Nome do Formulário
                    $Form->setNomeForm("FormManu".$Id);

                    //Seta ID
                    $Form->setCampoRetorna("Id",$Id);

                    //Opção  - Operação
                    $Op = $Form->getOp();

                    //Dados Padrões
                    $USR->getDados($Id,"POST");

                    //Dados de Permisssões
                    $USR->getDadosPermissoes($Id,"POST");
                    
                    //Recupera os Dados referentes ao Usuario
                    $USR->getDadosUsuario($Id, "POST");

                    $Campos =  $Form->getFormManu();
                    $Campos += $Form->getFormAcesso();

                    //Load para abrir a primeira aba
                    $Form->setOnLoad("Abas","manipulaAbas('".$Id."', 1)");

                    echo $Form->geraFuncoes(), $Form->geraMascaras(), $Form->geraOnLoad(), $Form->geraValidacaoJS("validaForm".$Id,"FormManu".$Id);

                    //Contato
                    $ConteudoContato = $Conta->getContato($_POST['ContatoCod'], $Id, $Form);

                    //Endereço
                    $ConteudoEnd = $End->getEndereco($_POST['EnderecoCod'], $Id, $Form);

                    include($_SESSION['DirBase'].PACOTE.'/'.MODULO.'/'.MODULO.'.tpl.php');

                    $Form->resetTodos();
                }
            }
        }
        catch (Exception $E)
        {
            echo($E->getMessage());
        }

    break;

    case "Del": ##Remover

        //Verificando Permissões
        $Ac->setOpcao($_GET['Op']);
        $Ac->acessoModulo();

        $USR  = new Usuarios();

        echo $USR->remover();

    break;

    case "LoadAcesso": ##Opções de Acesso

            //Include Classe de Permissões
            include_once($_SESSION['DirBase'].PACOTE.'/usuario_tipo/usuario_tipo.class.php');
            include_once($_SESSION['DirBase'].PACOTE.'/usuarios/usuarios_permissao.class.php');

            //Recupera Usuario Tipo
            $UsuarioTipoCod = $_POST['UsuarioTipoCod'];

            if(empty($UsuarioTipoCod)) exit("Tipo Inválido");

            //Instancia Classe
            $UT = new UsuarioTipo();

            try
            {
                    $UT->getDadosPermissoes($UsuarioTipoCod,"POST");

                    $UP = new UsuariosPermissao();

                    echo $UP->geraPermissoes();
            }
            catch (Exception $E)
            {
                    echo($E->getMessage());
            }

    break;

    case "BusNome": ##Buscar Usuario

        $Limite   = (empty($_POST['Limite'])) ? 10 : $_POST['Limite'];
        $q        = @utf8_decode($_POST['q']);

        try
        {
            $Con = Conexao::conectar();

            $Sql = "
            SELECT UsuarioDadosNome
            FROM  usuario_dados
            WHERE UsuarioDadosNome LIKE '".$q."%' GROUP BY UsuarioDadosNome
            ORDER BY UsuarioDadosNome ASC
            LIMIT ".$Limite." ";

            $RS  = $Con->executar($Sql);

            while($Dados = @mysqli_fetch_array($RS))
            {
                echo $Dados['UsuarioDadosNome']."\n";
            }
        }
        catch(Exception $E)
        {
                echo "";
        }

    break;

    case "Bus": ##Buscar Usuario

        $Limite   = (empty($_POST['Limite'])) ? 10 : $_POST['Limite'];
        $q        = @utf8_decode($_POST['q']);

        try
        {
            $Con = Conexao::conectar();

            $Sql = "
            SELECT UsuarioCod, UsuarioDadosNome
            FROM  usuario_dados
            WHERE UsuarioDadosNome LIKE '".$q."%'
            ORDER BY UsuarioDadosNome ASC
            LIMIT ".$Limite;

            $RS  = $Con->executar($Sql);

            while($Dados = @mysqli_fetch_array($RS))
            {
                echo $Dados['UsuarioDadosNome'].'|'.$Dados['UsuarioCod']."\n";
            }
        }
        catch(Exception $E)
        {
            echo "";
        }

    break;


    case "BuscaNome": ##Buscar Cliente

        $Limite   = (empty($_POST['Limite'])) ? 10 : $_POST['Limite'];
        $q        = @utf8_decode($_POST['q']);

        try
        {
            $Con = Conexao::conectar();

            $Sql = "SELECT UsuarioCod, UsuarioDadosNome
                            FROM   _usuarios
                            WHERE  UsuarioDadosNome LIKE '$q%'
                            LIMIT  $Limite GROUP BY UsuarioDadosNome";

            $RS  = $Con->executar($Sql);

            while($Dados = @mysqli_fetch_array($RS))
            {
                echo $Dados['UsuarioDadosNome']."|".$Dados['UsuarioCod']."\n";
            }
        }
        catch(Exception $E)
        {
            echo "";
        }

    break;
}
?>
