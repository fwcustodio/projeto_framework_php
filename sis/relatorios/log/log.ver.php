<?
class VerLog
{
	//Código do Log
	private $LogCod;
	
	//Array de Logs
	private $ArrayLog;
	
	//Variavel de Instancia
	private $Con;
	
	//Metodo Para Setar Código do Log
	public function setLogCod($Valor)
	{
		$this->LogCod = $Valor;
	}
	
	//Metodo Para Recuperar Código do Log
	public function getLogCod()
	{
		if(empty($this->LogCod))
		{
			throw new Exception("Código do Log Não Foi Setado");
		}
		else 
		{
			return $this->LogCod;
		}
	}
	
	
	//Meditodo Construtor
	public function VerLog()
	{
		//Instancia Conexão
		$this->Con = Conexao::conectar();
	}	
	
	//Metodo para gerar array de log
	public function setArrayLog()
	{
		//Inicia Array
		$this->ArrayLog = array();
		
		//$Dados do Log
		$DadosDesteLog = $this->getDadosLog($this->getLogCod());
		
		//Código Sql
		$Sql = "SELECT LogCod, UsuarioCod, ModuloCod, OpcoesModuloCod, 
					   SqlInterpretado, Tabela, Cod, Hash,
					   Ip, 
					   DATE_FORMAT(DataLog, '%d/%m/%Y às %H:%i:%s') AS DataLog, 
					   Acao, LogOculto
				FROM   _log  
				WHERE  Cod       = ".$DadosDesteLog['Cod']."      AND 
					   Tabela    = '".$DadosDesteLog['Tabela']."' AND 
					   LogOculto = 'N'";
		
		//Result Set
		$RSLog = $this->Con->executar($Sql);
		
		//Gera Array De Logs
		while ($DadosLog = mysqli_fetch_assoc($RSLog))
		{			
			$this->ArrayLog[$DadosLog['Tabela']][$DadosLog['LogCod']] = $DadosLog;
		}
	}
	
	//Monta Log
	public function montaLog()
	{	
		$Posicao = 1;
		
		$this->setArrayLog();
		
		
		$Html = '<table width="100%" border="0" cellspacing="0" cellpadding="0">';
		
		foreach ($this->ArrayLog as $NomeTabela=>$Tabelas)
		{	
			$Html .= '<tr><td id="logTituloTabela">Entidade: '.$NomeTabela.'</td></tr><tr>';
			
			$Html .= '<td align="center">';
			
			$IdCarrosel = "meuCarrosel".mt_rand();
			
			$Html .= '<ul id="'.$IdCarrosel.'" class="jcarousel-skin-tango">';
			
			//Contador de Posições
			$ContaPosicao = 0;
			
			//Dados Anteriores ao registro
			$DadosAnteriores = null;
			
			foreach ($Tabelas as $Dados)
			{	
				$ContaPosicao +=1;
				
				if($Dados['LogCod'] == $this->LogCod) { $Posicao = $ContaPosicao; }
				
				$Html.='<li>';
				$Html.= $this->organizaDadosLog($Dados, $DadosAnteriores);
				$Html.= '</li>';
				
				$DadosAnteriores = $Dados['SqlInterpretado'];
			}
			
			$Html.='</ul>';
			
			$Html .= '</td></tr>';
		}
		
		$Html .= '</table>';
		
		$Script = $this->getScriptCarrosel($IdCarrosel,$Posicao);

		return $Html.$Script;
	}
	
	//Scropt do carrosel
	public function getScriptCarrosel($Conteiner, $Posicao)
	{
		return '<script type="text/javascript"> jQuery(document).ready(function() { jQuery("#'.$Conteiner.'").jcarousel({ visible: 2, scroll:1,start:'.$Posicao.'}); });</script>';
	}
	
	//Organiza Dados do Log
	public function organizaDadosLog($DadosLog, $DadosAnteriores = null)
	{	
		$NomeUsuario     = $this->getNomeUsuario($DadosLog['UsuarioCod']);
		$NomeOperacao    = $this->getNomeOpreracao($DadosLog['OpcoesModuloCod']);
		$NomeModulo      = $this->getNomeModulo($DadosLog['ModuloCod']);
		$DataLog         = $DadosLog['DataLog'];
		$IP              = $DadosLog['Ip'];
		$LogOculto       = $this->getLogOculto($DadosLog['Hash']);
		
		if($DadosLog['Acao'] == 'Del')
		{
			$SqlInterpretado = '<div id="idLogRemovido"><div>Registro Removido</div></div>';	
		}
		else 
		{
			$SqlInterpretado = $this->interpretaArraySql($DadosLog['SqlInterpretado'],$DadosAnteriores); 
		}
		
		$Html = '<table width="96%" border="0" cellspacing="0" cellpadding="0" id="logTabelaConteudo">
				 <tr><td>Usuário <b>'.$NomeUsuario.'</b> executou a operação de <b>'.$NomeOperacao.'</b> no módulo <b>'.$NomeModulo.'</b> em <b>'.$DataLog.'</b> usando o IP: <b>'.$IP.'</b></td>
  				 </tr><tr><td>'.$SqlInterpretado.'</td></tr>';	
		
		if(!empty($LogOculto))
		{
			$Conteiner = 'moLog'.mt_rand();
			
			$Html .= '<tr><td><div id="logOcultoImagem"><img src="'.$_SESSION['UrlBase'].'figuras/mostrar_log.gif" border="0" onclick="mostraOcultaLog(\''.$Conteiner.'\',this)" style="cursor:pointer" /></div><div id="'.$Conteiner.'" class="logOcultoDiv">'.$LogOculto.'</div></td></tr>';	
		}
		
		$Html .= '</table>';
		
		return $Html;
	}
	
	public function getNomeUsuario($UsuarioCod)
	{
		$Sql = "SELECT b.UsuarioDadosNome AS Nome
				  FROM _usuarios a, 
					   usuario_dados b
				 WHERE a.UsuarioCod = b.UsuarioCod
				   AND a.UsuarioCod = $UsuarioCod ";
		
		return $this->Con->execRLinha($Sql);
	}
	
	public function getNomeOpreracao($OpcoesModuloCod)
	{
		$Sql = "SELECT NomePermissao FROM _opcoes_modulo WHERE OpcoesModuloCod = $OpcoesModuloCod";
		
		return $this->Con->execRLinha($Sql);
	}
	
	public function getNomeModulo($ModuloCod)
	{
		$Sql = "SELECT NomeMenu FROM _modulos WHERE ModuloCod = $ModuloCod";
		
		return $this->Con->execRLinha($Sql);
	}
	
	//Metodo para recuperar os dados do Log
	public function getDadosLog($LogCod)
	{
		$Sql = "SELECT Cod, Tabela, Hash FROM _log WHERE LogCod = $LogCod";
		
		return $this->Con->execLinha($Sql);
	}
	
	//Interpreta Array Sql
	public function interpretaArraySql($DadosAtuais, $DadosAnteriores = null)
	{
		$Html = '';
		
		if(empty($DadosAnteriores))
		{
			if(empty($DadosAtuais))
			{	
				$Html .= '<div id="idLogRemovido"><div>Registro Removido</div></div>';
			}
			else 
			{
				@eval('$ArrayAtual = '.$DadosAtuais.';');
				
				if(is_array($ArrayAtual))
				{
					$Html.='<table width="100%" border="0" cellspacing="0" cellpadding="0" id="logTabelaInterpretado">';
					
					foreach ($ArrayAtual as $Campo=>$Valor)
					{
						$Html.= '<tr><td align="right" class="logTdCampo">'.$Campo.':</td><td class="logTdValor">'.$Valor.'</td></tr>';
					}
					
					$Html .= '</table>';
				}
			}
		}
		else 
		{
			@eval('$ArrayAtual    = '.$DadosAtuais.';');
			@eval('$ArrayAnterior = '.$DadosAnteriores.';');
			
			if(is_array($ArrayAtual) AND is_array($ArrayAnterior))
			{
				
				$Html.='<table width="100%" border="0" cellspacing="0" cellpadding="0" id="logTabelaInterpretado">';
				
				foreach ($ArrayAtual as $Campo=>$Valor)
				{
					$ClassAlterado = ' campoAlterado';
					
					//Limpa
					$Campo = trim($Campo);
					
					$ValorAnterior = $ArrayAnterior[$Campo];
					
					//Limpa
					$Valor         = trim($Valor);
					$ValorAnterior = trim($ValorAnterior);
					
					if("$Valor" == "$ValorAnterior")
					$ClassAlterado = '';					
					
					$Html.= '<tr><td class="logTdCampo'.$ClassAlterado.'" align="right">'.$Campo.':</td><td class="logTdValor'.$ClassAlterado.'">'.$Valor.'</td></tr>';
				}
				
				$Html .= '</table>';
			}
		}
		
		return $Html;
	}
	
	/*GERA O LOG OCULTO*/
	public function getLogOculto($Hash)
	{
		$ArrayLog = array();
		
		//Código Sql
		$Sql = "SELECT LogCod, UsuarioCod, ModuloCod, OpcoesModuloCod, 
					   SqlInterpretado, Tabela, Cod, Hash,
					   Ip, 
					   DATE_FORMAT(DataLog, '%d/%m/%Y às %H:%i:%s') AS DataLog, 
					   Acao, LogOculto
				FROM   _log  
				WHERE  Hash      = '$Hash' AND 
					   LogOculto = 'S'";
		
		//Result Set
		$RSLog = $this->Con->executar($Sql);
			
		//Gera Array De Logs
		while ($DadosLog = mysqli_fetch_assoc($RSLog))
		{			
			$ArrayLog[$DadosLog['Tabela']][$DadosLog['LogCod']] = $DadosLog;
		}
		
		//Gerando Código Html
		$Html = '';
		
		foreach ($ArrayLog as $NomeTabela=>$Tabelas)
		{
			//Dados Anteriores ao registro
			$DadosAnteriores = null;
			
			foreach ($Tabelas as $Dados)
			{
				$Html .= $this->interpretaArraySql($Dados['SqlInterpretado'],$DadosAnteriores); 	
			}
		}
		
		return $Html;
	}
}