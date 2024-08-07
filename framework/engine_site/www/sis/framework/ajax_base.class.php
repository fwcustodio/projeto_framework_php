<?
session_start();

header("Content-Type: text/html; charset=ISO-8859-1",true);

include_once('config.conf.php'); ConfigSIS::Conf();

include_once($_SESSION['FMBase'].'conexao.class.php');

switch ($_GET['Op'])
{
	case "AlteraPaginacao":

        try
        {
            if(!is_string($_GET['Modulo'])) throw new Exception("Erro");
            if(!is_numeric($_GET['Registros']) or $_GET['Registros'] < 1 or $_GET['Registros'] > 200) throw new Exception("Erro");
            
            $Con = Conexao::conectar();

            $NLinhas = $Con->execNLinhas("SELECT UsuarioPaginacaoCod FROM _usuario_paginacao WHERE ModuloNome = '".$_GET['Modulo']."' AND UsuarioCod = ".$_SESSION['UsuarioCod']);

            if($NLinhas > 0)
            {
                $Con->executar("UPDATE _usuario_paginacao SET Registros = ".$_GET['Registros']." WHERE UsuarioCod = ".$_SESSION['UsuarioCod']." AND ModuloNome = '".$_GET['Modulo']."'");
            }
            else
            {
                $Con->executar("INSERT INTO _usuario_paginacao (ModuloNome, UsuarioCod, Registros) VALUES ('".$_GET['Modulo']."',".$_SESSION['UsuarioCod'].",".$_GET['Registros'].")");
            }

            echo 'true';
        }
        catch (Exception $E)
        {
            echo $E->getMessage();
        }

	break;
}
?>