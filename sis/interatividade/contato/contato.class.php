<?
include_once($_SESSION['DirBase'].'interatividade/contato/contato.sql.php');
include_once($_SESSION['DirBase'].'interatividade/contato/contato.form.php');

/*
Classe de Contato
*/
class Contato extends ContatoSQL
{	
	private $MinimoContatos; //Numero Minimo de Contatos
	private $MaxContato; //numero maximo de contatos
	
	public function Contato()
	{
		$this->MinimoContatos = 1;
		$this->MaxContato     = 10;
	}
	
	//Sets
	public function setMinimoContatos($Valor) { $this->MinimoContatos = $Valor; }
	public function setMaxContato($Valor)     { $this->MaxContato     = $Valor; }
	
	//Gets
	public function getMinimoContatos() { return $this->MinimoContatos; }
	public function getMaxContato()     { return $this->MaxContato;     }
	
	/*
		Cadastra Contato
	*/
	public function cadastrarContato($ObjForm)
	{
		//Conexão com o banco
		$Con = Conexao::conectar();
				
		//Contato
		$Con->executar(parent::cadastrarContatoSql());

		//Contato Cod Gerado
		$this->setContatoCod($Con->ultimoId("contato", "ContatoCod"));		
		
		//Recupera o Array Com os Contatos Selecionados
		$ArrayContato = $_POST['ArrayContato'];
		
		//Validando Número de Contatos
		if(!is_array($ArrayContato))
		{
			if($this->MinimoContatos > 0) throw new Exception("Deve haver pelo menos um contato!");
		}
		else 
		{
			if(count($ArrayContato) > $this->MaxContato) throw new Exception("O número maximo de contatos permitidos é $this->MaxContato!");
		}
		
		//Setando valores do form
//			foreach($ArrayContato as $Chave=>$Nada) $ObjForm->getFormContato($Chave);
		
		//Seta os dados Apresentados pelo form		
		foreach($ArrayContato as $Chave=>$Nada)
		{
			$Padrao = $_POST['PadraoCont'] == $Chave ? "'S'" : "'N'";
			
			//Monta Array
			$ArrayDados = array("NomeContato"       => "'".$_POST['NomeContato'.$Chave]."'", 
								"ContatoObservacao" => "'".$_POST['ContatoObservacao'.$Chave]."'",
								"Padrao"            => $Padrao);							  	

			//Cadastra os dados do contato
			$this->cadastrarContatoDados($ArrayDados);	
			
			//Seta ultimo id dados
			$this->setContatoDadosCod($Con->ultimoId("contato_dados", "ContatoDadosCod"));
			
			//Cadastra Tipos de Contatos
			foreach ($_POST["Contato"][$Chave] as $ChaveTipo=>$Nada)
			{
				$ContatoCategoriaCod = $_POST["Contato"][$Chave][$ChaveTipo];
				$ContatoCategoriaCod = $ContatoCategoriaCod;
				$ContatoCategoriaCod = htmlspecialchars($ContatoCategoriaCod);
				
				$ArrayTipo = array("ContatoCategoriaCod" => $_POST["ContatoCategoriaCod"][$Chave][$ChaveTipo], "Contato"=>$_POST["Contato"][$Chave][$ChaveTipo]);


				$this->cadastrarTipoContato($ArrayTipo);
			}
		}
	}	
	
	public function cadastrarContatoDados($ArrayDados)
	{
		$Con = Conexao::conectar();
		
		$Con->executar(parent::cadastrarContatoDadosSql($ArrayDados));
	}	
	
	public function cadastrarTipoContato($ArrayTipo)
	{
		$Con = Conexao::conectar();
		
		$Con->executar(parent::cadastrarTipoContatoSql($ArrayTipo));
	}		
	
	
	public function alterarContato($ObjForm, $ContatoCod)
	{
		//Conexão com o banco
		$Con = Conexao::conectar();
		
		//Remove contatos existentes
		$this->removerTipoContato($ContatoCod);

		//Remove os Dados de Contato
		$this->removerContatoDados($ContatoCod);

		//Contato Cod Gerado
		$this->setContatoCod($ContatoCod);		
		
		//Recupera o Array Com os Contatos Selecionados
		$ArrayContato = $_POST['ArrayContato'];
		
		//Validando Número de Contatos
		if(!is_array($ArrayContato))
		{
			if($this->MinimoContatos > 0) throw new Exception("Deve haver pelo menos um contato!");
		}
		else 
		{
			if(count($ArrayContato) > $this->MaxContato) throw new Exception("O número maximo de contatos permitidos é $this->MaxContato!");
		}
		
		//Setando valores do form
		foreach($ArrayContato as $Chave=>$Nada) $ObjForm->getFormContato($Chave);
		
		//Seta os dados Apresentados pelo form		
		foreach($ArrayContato as $Chave=>$Nada)
		{
			$Padrao = $_POST['PadraoCont'] == $Chave ? "'S'" : "'N'";
			
			//Monta Array
			$ArrayDados = array(
			"NomeContato" 		=> $ObjForm->getCampoRetorna("NomeContato".$Chave,true,"Texto"), 
			"ContatoObservacao" => $ObjForm->getCampoRetorna("ContatoObservacao".$Chave,true,"Texto"),
			"Padrao"			=> $Padrao
			);							  	

			//Cadastra os dados do contato
			$this->cadastrarContatoDados($ArrayDados);	
			
			//Seta ultimo id dados
			$this->setContatoDadosCod($Con->ultimoId("contato_dados", "ContatoDadosCod"));
			
			//Cadastra Tipos de Contatos
			foreach ($_POST["Contato"][$Chave] as $ChaveTipo=>$Nada)
			{
				$ContatoCategoriaCod = $_POST["Contato"][$Chave][$ChaveTipo];
				$ContatoCategoriaCod = utf8_decode($ContatoCategoriaCod);
				$ContatoCategoriaCod = htmlspecialchars($ContatoCategoriaCod);
				
				$ArrayTipo = array("ContatoCategoriaCod" => $_POST["ContatoCategoriaCod"][$Chave][$ChaveTipo], "Contato"=> $_POST["Contato"][$Chave][$ChaveTipo]);

				$this->cadastrarTipoContato($ArrayTipo);
			}
		}

	}		
	
	/**
	*	Reponsável pela remoção das informações
	*	@return Void
	*/	
	public function removerContato($ContatoCod)
	{
		$Con = Conexao::conectar();
		
		//Remove os tipos de contato
		$this->removerTipoContato($ContatoCod);
		
		//Remore o Contato
		$Con->executar(parent::removerContatoSql($ContatoCod));
		
		//Remove os Dados de Contato
		$this->removerContatoDados($ContatoCod);
	}
	
	//Remove contato por ID
	public function removerContatoDados($ContatoCod)
	{
		//Conexao
		$Con = Conexao::conectar();
					
		//Dados do Contato		
		$Con->executar(parent::removerContatoDadosSql($ContatoCod));
	}

	//Remove contato Tipo
	public function removerTipoContato($ContatoCod)
	{
		//Conexao
		$Con = Conexao::conectar();

		//Recupera o Código de Dados
		$RsContatoDados = $Con->executar(parent::getContatoDadosSql($ContatoCod));
		
		while($ContatoDadosCod = mysqli_fetch_array($RsContatoDados)) 
		{
			//Dados do Contato		
			$Con->executar(parent::removerContatoTipoSql($ContatoDadosCod['ContatoDadosCod']));
		}
	}

	public function getContato($ContatoCod, $Id, $ObjForm)
	{
		
		$FPHP = new FuncoesPHP();
			
		$Con = Conexao::conectar();
		
		$ContatoCod = "'".$ContatoCod."'";
		
		$Sql  = parent::getContatoDadosSql($ContatoCod);
		
		$HtmlContato = "";
		$HtmlTipo    = ""; 
		
		$RSCont = $Con->executar($Sql);
		$NCont  = $Con->nLinhas($RSCont);			
		
		if($NCont > 0)
		{
			while ($DadosCont = @mysqli_fetch_assoc($RSCont))
			{		
				$Cont = $DadosCont['ContatoDadosCod'];
				
				$Campos = array("ContatoDadosCod".$Cont, "NomeContato".$Cont, "ContatoObservacao".$Cont, "PadraoCont".$Cont);
			
				$ComposForm = array_combine($Campos, $DadosCont);
			
				$FPHP->extractVar($ComposForm, "POST");
				
				$PadraoChecado = "";
				
				if($_POST['PadraoCont'.$Cont] == "S")
				{
					$PadraoChecado = 'checked="checked"';
				}
				
				//Dados do Tipo de Contato
				$Sql    = parent::getTipoContatoSql($Cont);
				$RsTipo = $Con->executar($Sql);
				$NTipo  = $Con->nLinhas($RsTipo);
				
				if($NTipo > 0)
				{
					$HtmlTipo = "";
					while ($DadosTipo = @mysqli_fetch_assoc($RsTipo))
					{							
						$Campos = array("ContatoTipoCod".$Cont, "ContatoCategoriaCod".$Cont, "Contato".$Cont);
						$ComposForm = array_combine($Campos, $DadosTipo);
			
						$FPHP->extractVar($ComposForm, "POST");
						
						$CamposCont = $ObjForm->getFormTipoContato($Cont);
						$HtmlTipo .= $this->getConteudoTipoContato($CamposCont,$Id,$Cont); 
					}	
				}
				
				$CamposCont = $ObjForm->getFormDadosContato($Cont);

				$HtmlContato.= $this->getConteudoContato($CamposCont,$Id,$Cont,$HtmlTipo, $PadraoChecado);
			}
		}
		
		return $HtmlContato;
	}
	
	public function getConteudoContato($CamposCont, $IdForm, $Cont, $ConteudoTipoContato, $PadraoChecado = '')
	{	 
		$Html = '<fieldset id="contato'.$Cont.'" style="width:338px; margin:3px; border:2px solid #ced8e1; background:#e0e6ed; float:left;">
		<table width="100%" border="0" cellspacing="0" cellpadding="1">
		<tr>
		    	<td>
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
				<td colspan=3>
				<input type="radio" name="PadraoCont" id="PadraoCont" value="'.$Cont.'" '.$PadraoChecado.' /> <span class="textoForm"> Definir como contato Principal</span>
				</td>
				</tr>
				<tr>
				<td width="110" align="right" class="textoForm">Contato:</td>
		        <td>'.$CamposCont['NomeContato'.$Cont].$CamposCont['ArrayContato'.$Cont].'</td>
				<td><a href="javascript:addTipoContato('.$Cont.',\''.$IdForm.'\')">
		        <img src="'.$_SESSION['UrlBase'].'figuras/bt_add.gif" border="0" /></a>
				</td>
				</tr>
				</table>
				</td></tr><tr><td>
		        <div id="conteinerTipoContato'.$Cont.'">'.$ConteudoTipoContato.'</div></td></tr><tr><td><table width="100%" border="0" cellspacing="0" cellpadding="0">
		        <tr><td width="110" align="right" class="textoForm">Observa&ccedil;&otilde;es:</td><td>'.$CamposCont['ContatoObservacao'.$Cont].'</td>
		        </tr></table></td></tr><tr><td align="center"><span class="textoForm">
		        <input name="DescartEnd" type="button" id="DescartEnd" onclick="removeContato(\''.$IdForm.'\',\''.$Cont.'\')" value="Descartar Este Contato"/></span></td>
		        </tr></table></fieldset>';	
		
		return $Html;	
	}
	
	public function getConteudoTipoContato($CamposCont, $IdForm, $Cont)
	{
		$Html = '<div>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
		<td width="110" align="right" class="textoForm">
		'.$CamposCont['ContatoCategoriaCod'.$Cont].'</td>
		<td>'.$CamposCont['Contato'.$Cont].'</td>
        		 <td><img src="'.$_SESSION['UrlBase'].'figuras/bt_remover.gif" border="0" onclick="removeTipoContato(\''.$IdForm.'\',\''.$Cont.'\',this)" style="cursor:pointer" /></td></tr></table></div>';

		return $Html;
	}
}
?>