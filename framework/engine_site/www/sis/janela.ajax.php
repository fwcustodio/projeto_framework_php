<?
//Starta Sess?
session_start();
include_once($_SESSION['FMBase'].'conexao.class.php'); 

$Con = Conexao::conectar();

$TipoInteracao 	= $_REQUEST['TipoInteracao'];
$Situacao 		= ($_POST['Situacao'] == 'Max')? 'S' : 'N';
$UsuarioCod 	= $_SESSION['UsuarioCod'];
$Modulo 		= $_POST['Modulo'];

switch ($TipoInteracao)
{
	case "MaxORMin": 
		
		if(!empty($UsuarioCod))
		{
			$Sql = "UPDATE _janela SET Visivel = '".$Situacao."'
					WHERE UsuarioCod = ".$UsuarioCod."
					AND ModuloNome = '".$Modulo."'";
					
			$Con->executar($Sql); 
		}   
	break;
	
	case "Serial":
		
		if(!empty($UsuarioCod))
		{
			$Con->startTransaction();			
			
			$Sql = $Con->executar("SELECT ModuloNome, Visivel FROM _janela WHERE UsuarioCod =".$UsuarioCod);
			$ArrayVisivel = array();
			while($Rs = @mysqli_fetch_array($Sql))
			{
				$NomeModulo = $Rs['ModuloNome'];
				$ArrayVisivel[$NomeModulo] = $Rs['Visivel'];
			}
			
			$Sort1 = array();		
			$Sort2 = array();		
			$Sort3 = array();	
			
			$Parametro = $_POST['Parametros'];
			
			$Array = explode("&", $Parametro);
			
			foreach($Array as $LerArray)
			{
				if($LerArray{4} == '1')
					$Sort1[] = ereg_replace("sort[0-9]\[\]=", "", $LerArray);
					
				elseif($LerArray{4} == '2')
					$Sort2[] = ereg_replace("sort[0-9]\[\]=", "", $LerArray);
					
				else
					$Sort3[] = ereg_replace("sort[0-9]\[\]=", "", $LerArray);
			}
			
			$ContA = 0;
			if(count($Sort1) > 0){
				foreach($Sort1 as $ColunaA)
				{
					$Con->executar("UPDATE _janela SET Visivel = '".$ArrayVisivel[$ColunaA]."', Coluna = 'A', Posicao = ".++$ContA." WHERE UsuarioCod = ".$UsuarioCod." AND ModuloNome = '".$ColunaA."'");
				}
			}
			
			$ContB = 0;
			if(count($Sort2) > 0){
				foreach($Sort2 as $ColunaB)
				{
					$Con->executar("UPDATE _janela SET Visivel = '".$ArrayVisivel[$ColunaB]."', Coluna = 'B', Posicao = ".++$ContB." WHERE UsuarioCod = ".$UsuarioCod." AND ModuloNome = '".$ColunaB."'");
				}
			}
			
			$ContC = 0;
			if(count($Sort3) > 0){
				foreach($Sort3 as $ColunaC)
				{
					$Con->executar("UPDATE _janela SET Visivel = '".$ArrayVisivel[$ColunaC]."', Coluna = 'C', Posicao = ".++$ContC." WHERE UsuarioCod = ".$UsuarioCod." AND ModuloNome = '".$ColunaC."'");
				}
			}
			
			$Con->stopTransaction();
		}
  
	break;
}
