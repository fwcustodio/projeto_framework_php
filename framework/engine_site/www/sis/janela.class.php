<?
include_once($_SESSION['FMBase'].'funcoes_php.class.php'); 
class Janela 
{

	private $Con  = null;
	private $Janelas = array();
	private $Fun;
	
	public function Janela()
	{
		$this->Con = Conexao::conectar();
		$this->Fun = new FuncoesPHP();	
	}
	
	public function geraJanelas()
	{
		try{ $this->boasVindas("bvindas"); } catch (Exception $E){}				
		try{ if($this->permissao("Vis", $_SESSION['UsuarioCod'], "log")) $this->jLog("log"); } catch (Exception $E){}
	}
	
	
	public function setJanelas($Conteudo)
	{
		$this->Janelas[] = $Conteudo;
	}
	
	public function getJanelas()
	{
		return $this->Janelas;
	}
	
	public function colunaA()
	{
		$Array = array();
		foreach($this->getJanelas() as $Coluna)
		{
			if($Coluna['Coluna'] == 'A')
			{
				$Array[$Coluna['Posicao']] = $Coluna;
			}
		}
		
		ksort($Array);
		return $Array;
	}	
	
	public function colunaB()
	{
		$Array = array();
		foreach($this->getJanelas() as $Coluna)
		{
			if($Coluna['Coluna'] == 'B')
			{
				$Array[$Coluna['Posicao']] = $Coluna;
			}
		}
		
		ksort($Array);
		return $Array;
	}
	
	public function colunaC()
	{
		$Array = array();
		foreach($this->getJanelas() as $Coluna)
		{
			if($Coluna['Coluna'] == 'C')
			{
				$Array[$Coluna['Posicao']] = $Coluna;
			}
		}
		
		ksort($Array);
		return $Array;
	}
	
	/*
	*	Boas Vindas
	*/
	
	public function boasVindas($NomeModulo)
	{
		$Titulo= 'Boas-Vindas';
		$Conteudo = '';
		$Rodape = '';
		$TamanhoJanela = '';
		
		$ColunaPosicao = $this->getColunaPosicao($NomeModulo, $_SESSION['UsuarioCod']);
		$Display = ($ColunaPosicao['Visivel'] == 'N')? 'style="display:none"' : '';
		$Imagem  = ($ColunaPosicao['Visivel'] == 'N')? 'min' : 'max';
		
		$Conteudo.='<a href="javascript:void(0)" id="minimizar" onClick="minimizarGadget(this)"><img src="figuras/'.$Imagem.'.jpg"  border="0"  /></a></div>
					<div class="itemContent" '.$Display.'>
					<div class="conteudomeio">
						<script src="js/AC_RunActiveContent.js" type="text/javascript"></script>
							<div class="index_texto">
								Ol&aacute;, <b>'.$_SESSION['NomeUser'].'</b>!<br /> &uacute;ltimo acesso '.$_SESSION['Ua'].'
									<div align="center" style="padding-top:8px">
										<script type="text/javascript">AC_FL_RunContent( \'codebase\',\'http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0\',\'width\',\'143\',\'height\',\'44\',\'src\',\'figuras/relogio_site\',\'quality\',\'high\',\'pluginspage\',\'http://www.macromedia.com/go/getflashplayer\',\'wmode\',\'transparent\',\'movie\',\'figuras/relogio_site\' ); //end AC code</script>
											<noscript>
												<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" width="143" height="44">
													<param name="movie" value="figuras/relogio_site.swf">
													<param name="quality" value="high">
													<param name="wMode" value="transparent">
													<embed src="figuras/relogio_site.swf" width="143" height="44" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" wmode="transparent"></embed>
												</object>
											</noscript>
									</div>
							</div>
					</div>';
		
				
		
		$Array = array('Conteudo'=> $Conteudo,
					   'Titulo'  => $Titulo,
					   'Rodape'  => $Rodape,
					   'Tamanho' => $TamanhoJanela,
					   'Modulo'  => $NomeModulo,
					   'Coluna'  => $ColunaPosicao['Coluna'],
					   'Posicao' => $ColunaPosicao['Posicao'],
					   'Visivel' => $ColunaPosicao['Visivel']);
		
		$this->setJanelas($Array);
	}
		
	public function jLog($NomeModulo)
	{		
		$Titulo			= 'Log';
		$Conteudo 		= '';
		$Rodape 		= '';
		$TamanhoJanela 	= '';
		
		$ColunaPosicao = $this->getColunaPosicao($NomeModulo, $_SESSION['UsuarioCod']);
		$Display = ($ColunaPosicao['Visivel'] == 'N')? 'style="display:none"' : '';
		
		$Sql = "SELECT a.LogCod, b.Nome, 
					   e.NomeMenu, 
					   CASE
					   WHEN a.Acao = 'Cad'  THEN 'Cadastro'
					   WHEN a.Acao = 'Alt'  THEN 'Alteração' 
					   WHEN a.Acao = 'Del'  THEN 'Remoção'
					   END as Acao, 
					   
					   f.NomePermissao, a.Ip, DATE_FORMAT(a.DataLog,'%d/%m/%Y ás %H:%i:%s') AS Data , a.DataLog
				
				FROM  _log a INNER JOIN _usuarios b ON (a.UsuarioCod = b.UsuarioCod) 
					  INNER JOIN _modulos e         ON (a.ModuloCod  = e.ModuloCod) 
					  INNER JOIN _opcoes_modulo f   ON (a.OpcoesModuloCod  = f.OpcoesModuloCod)
				WHERE a.LogOculto = 'N'
				ORDER BY a.DataLog DESC
				LIMIT 30"; 
		
		$Historico = $this->Con->executar($Sql);
		
		$Imagem  = ($ColunaPosicao['Visivel'] == 'N')? 'min' : 'max';
		
		$Conteudo.='<a href="javascript:void(0)" id="minimizar" onClick="minimizarGadget(this)"><img src="figuras/'.$Imagem.'.jpg"  border="0"  /></a></div>
					<div class="itemContent" '.$Display.'>
					<div class="conteudomeio" style="height:215px;">
						<table class="janelatabela" width="100%" border="0" cellspacing="1" cellpadding="1">
							<tr class="janelatitulo">
								<td nowrap="nowrap" ><b>Usuario</b></td>
								<td nowrap="nowrap" align="center"><b>Ação</b></td>
								<td nowrap="nowrap" align="center"><b>Modulo</b></td>
								<td nowrap="nowrap" align="center"><b>Data</b></td>
							</tr>';
		
		while($DadosHistorico = mysqli_fetch_array($Historico))
		{		
			if($i == 1) {
			 	$cor = '#eef3fa';
			 	$i = 0;
				} else {
			 	$cor = '#FFFFFF';
			 	$i = 1;
				}	
			
			$Conteudo.='<tr class="janelaconteudo" bgColor='.$cor.'>
							<td nowrap="nowrap" ><a style="text-decoration:none; color:#0b310a;" href="relatorios/log/log.php">'.$DadosHistorico['Nome'].'</a></td>
							<td nowrap="nowrap" ><a style="text-decoration:none; color:#0b310a;" href="relatorios/log/log.php">'.$DadosHistorico['Acao'].'</a></td>
							<td nowrap="nowrap" ><a style="text-decoration:none; color:#0b310a;" href="relatorios/log/log.php">'.$DadosHistorico['NomeMenu'].'</a></td>
							<td nowrap="nowrap" align="center"><a style="text-decoration:none; color:#0b310a;" href="relatorios/log/log.php">'.$DadosHistorico['Data'].'</a></td>
						</tr>';				
		}
		
		$Conteudo.='	</table>
					</div>';
		
		$Array = array('Conteudo'=> $Conteudo,
					   'Titulo'  => $Titulo,
					   'Rodape'  => $Rodape,
					   'Tamanho' => $TamanhoJanela,
					   'Modulo'  => $NomeModulo,
					   'Coluna'  => $ColunaPosicao['Coluna'],
					   'Posicao' => $ColunaPosicao['Posicao'],
					   'Visivel' => $ColunaPosicao['Visivel']);
		
		$this->setJanelas($Array);
	}

	public function permissao($Opcao, $UsuarioCod, $Modulo)
	{
		$Sql = "SELECT 	d.TipoPermissaoCod  
				FROM 	_opcoes_modulo a, _modulos b, 
						_usuarios c, _tipo_permissao d  
				WHERE 	b.ModuloNome = '$Modulo' AND 
						a.IdPermissao = '$Opcao' AND 
						d.Permissao = 'S' AND 
						b.ModuloCod = a.ModuloCod AND 
						a.OpcoesModuloCod = d.OpcoesModuloCod AND 
						c.UsuarioCod = d.UsuarioCod AND 
						c.UsuarioCod = $UsuarioCod";
						
		return ($this->Con->execNLinhas($Sql) > 0) ? true : false;
	}

	public function getColunaPosicao($Modulo, $UsuarioCod)
	{
		$Sql = "SELECT Coluna, Posicao, Visivel
				FROM _janela
				WHERE ModuloNome = '$Modulo' 
				AND UsuarioCod = $UsuarioCod";	
		
		return $this->Con->execLinha($Sql);
	}	
}
