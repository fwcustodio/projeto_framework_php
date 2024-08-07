<?
session_start();

@header("Content-Type: text/html; charset=ISO-8859-1",true);

include_once('config.conf.php'); ConfigSIS::Conf();

include_once($_SESSION['FMBase'].'conexao.class.php');

class Complete
{	
	public function resultados()
	{
		$Tabela   = @utf8_decode($_POST['Tabela']);
		$Campo    = @utf8_decode($_POST['Campo']);
		$Limite   = (empty($_POST['Limite'])) ? 10 : $_POST['Limite']; 
		$q        = @utf8_decode($_POST['q']);
		$Condicao = @utf8_decode($_POST['Condicao']);
		
		//Converte Condicao
		if(!empty($Condicao))
		$Condicao = str_replace(":","'",$Condicao);
		
		try 
		{
			$Con = Conexao::conectar();
			
			$Sql = "SELECT $Campo Campo FROM $Tabela WHERE $Campo LIKE '%".$q."%' $Condicao LIMIT $Limite ";
			//echo $Sql;
			$RS  = $Con->executar($Sql);
			
			while($Dados = @mysqli_fetch_array($RS))
			{
				echo htmlspecialchars_decode($Dados['Campo'])."\n";
			}			
		}
		catch(Exception $E)
		{
			echo "";
		}
	}
}

//Executando Classe
$C = new Complete();
$C->resultados();
?>