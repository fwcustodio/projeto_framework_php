<?
session_start();

@header("Content-Type: text/html; charset=ISO-8859-1",true);

include_once('config.conf.php'); ConfigSIS::Conf();

include_once($_SESSION['FMBase'].'conexao.class.php');

class ManterFiltro
{	
	private $Con;
	
	public function ManterFiltro()
	{
		$this->Con   = Conexao::conectar();
	}
	
	public function controlaFiltro()
	{
		if(!empty($_POST['Op']))
		{
			switch ($_POST['Op'])
			{
				case 'Cad':        $this->salvarFiltro();  break;
				case 'Del':        $this->removerFiltro(); break;
				case 'Fil': return $this->filtrarFiltro(); break;
				default: throw new Exception("Opção de Interação Inválida!");
			}
		}
	}
	
	public function filtrarFiltro()
	{
		//Atrubutos
		$UsuarioCod = $_SESSION['UsuarioCod'];
		$ModuloNome = $_POST['ModuloNome'];
		
		//Validação
		if(empty($UsuarioCod) or empty($ModuloNome)) throw new Exception("Informações Insuficientes para fazer o filtro!");
		
		//Recupera o Código do Módulo
		$ModuloCod = $this->Con->execRLinha("SELECT ModuloCod FROM _modulos WHERE ModuloNome = '".$ModuloNome."'");	
		
				//Seleciona os filtro
		$RSFiltros = $this->Con->executar("SELECT FiltroCod, ModuloCod, UsuarioCod, DataCriacao, FiltroNome, FiltroConteudo FROM _filtros WHERE UsuarioCod = $UsuarioCod AND ModuloCod = $ModuloCod");
		$NFiltros  = $this->Con->nLinhas($RSFiltros); 
		
		if($NFiltros > 0)
		{
			$Html = '<ul id=\"sis_filtrar_filtro_ul\">';
			
			$Html .= '<li><input type=\"button\" name=\"sis_bt_filtrar_close\" id=\"sis_bt_filtrar_close\" value=\"Fechar\" onclick=\"$(\'#sis_filtrar_filtro_ul\').remove()\"></li><hr width=\"100%\">';
			
			while ($Dados = @mysqli_fetch_array($RSFiltros))
			{
				$Html .= '<li><span id=\"span_remover\"><a href=\"javascript:void(0);\" onclick=\"sisRemoverFiltro(\''.$ModuloNome.'\','.$Dados['FiltroCod'].')\">Remover</a></span> <a href=\"javascript:void(0);\" onclick=\"sisBuscarFiltro(\''.$ModuloNome.'\', \''.$Dados['FiltroConteudo'].'\')\">'.$Dados['FiltroNome'].'</a></li>';
			}
			
			$Html .= '</ul>';
			
			return $Html;
		}
		else 
		{
			$Html = '<li><input type=\"button\" name=\"sis_bt_filtrar_close\" id=\"sis_bt_filtrar_close\" value=\"Fechar\" onclick=\"$(\'#sis_filtrar_filtro_ul\').remove()\"></li><hr width=\"100%\">';

			return '<ul id=\"sis_filtrar_filtro_ul\">'.$Html.'<li>Nenhum Filtro Encontrado</li></ul>';
		}		
	}
	
	public function salvarFiltro()
	{
		//Atrubutos
		$UsuarioCod     = $_SESSION['UsuarioCod'];
		$ModuloNome     = $_POST['ModuloNome'];
		$NomeFiltro     = utf8_decode($_POST['NomeFiltro']);
		$ConteudoFiltro = utf8_decode(urldecode($_POST['ConteudoFiltro']));
		
		//Validação
		if(empty($UsuarioCod) or empty($ModuloNome) or empty($NomeFiltro) or empty($ConteudoFiltro)) throw new Exception("Informações Insuficientes para gravar o filtro!");
		
		//Recupera o Código do Módulo
		$ModuloCod = $this->Con->execRLinha("SELECT ModuloCod FROM _modulos WHERE ModuloNome = '".$ModuloNome."'");			
		
		//Salva o filtro
		$this->Con->executar("INSERT INTO _filtros (ModuloCod, UsuarioCod, DataCriacao, FiltroNome, FiltroConteudo)VALUES ($ModuloCod, $UsuarioCod, now(), '$NomeFiltro', '$ConteudoFiltro')");
	}
	
	
	public function removerFiltro()
	{
		//Atrubutos
		$UsuarioCod     = $_SESSION['UsuarioCod'];
		$ModuloNome     = $_POST['ModuloNome'];
		$FiltroCod      = $_POST['FiltroCod'];
		
		//Validação
		if(empty($UsuarioCod) or empty($ModuloNome) or empty($FiltroCod)) throw new Exception("Informações Insuficientes para remover o filtro!");
		
		//Recupera o Código do Módulo
		$ModuloCod = $this->Con->execRLinha("SELECT ModuloCod FROM _modulos WHERE ModuloNome = '".$ModuloNome."'");	
		
		//Remove o filtro
		$this->Con->executar("DELETE FROM _filtros WHERE UsuarioCod = $UsuarioCod AND ModuloCod = $ModuloCod AND FiltroCod = $FiltroCod LIMIT 1");
	}	
}

##Executando-------------------------------------------------------------------------------------

try 
{
	//Intancia Classe
	$MF = new ManterFiltro();
	
	//Executa Metodo Controlador
	$Retorno = $MF->controlaFiltro();
	
	//Retorno
	echo '{Erro:false,Retorno:"'.$Retorno.'"}';
}
catch (Exception $E)
{
	echo '{Erro:true,Retorno:"Erro:'.$E->getMessage().'"}';
}
?>