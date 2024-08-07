<?
include_once($_SESSION['DirBase'].'interatividade/endereco/endereco.sql.php');
include_once($_SESSION['DirBase'].'interatividade/endereco/endereco.form.php');

class Endereco extends EnderecoSQL
{	
	private $EnderecoDados, $ArrayDadosCod;
	
	public function Endereco()
	{
		$this->ArrayDadosCod = array();
		$this->EnderecoDados = array();
	}
	
	public function setEnderecoDados($Dados)
	{
		$this->EnderecoDados[] = $Dados;
	}

	public function setArrayDadosCod($Valor)
	{
		$this->ArrayDadosCod[] = $Valor;
	}
	
	public function getArrayDadosCod()
	{
		return $this->ArrayDadosCod;
	}
	
	/*
		Cadastra endere�o
	*/
	public function cadastrarEndereco($ObjForm)
	{
		
		//Conex�o com o banco
		$Con = Conexao::conectar();
				
		//Endere�o
		$Con->executar(parent::cadastrarEnderecoSql());

		//Endere�o Cod Gerado
		$this->setEnderecoCod($Con->ultimoId("endereco", "EnderecoCod"));		
		
		//Recupera o Array Com os Endere�os Selecionados
		$ArrayEndereco = $_POST['ArrayEndereco'];
		
		//N�o foi apresentado
		if(!is_array($ArrayEndereco)) throw new Exception("Endere�o Inv�lido");
		
		//Setando valores do form
		foreach($ArrayEndereco as $Chave=>$Nada) $ObjForm->getFormEndereco($Chave);			

		//Seta os dados Apresentados pelo form		
		foreach($ArrayEndereco as $Chave=>$Nada)
		{
			$Padrao = $_POST['PadraoEnd'] == $Chave ? "'S'" : "'N'";
				
			$this->setEnderecoDados(array(
			"EnderecoDadosTipoCod" => $ObjForm->getCampoRetorna("EnderecoDadosTipoCod".$Chave,false,"Inteiro"), 
			"Pais"                 => $ObjForm->getCampoRetorna("Pais".$Chave,true,"Texto"), 
			"Estado"               => $ObjForm->getCampoRetorna("Estado".$Chave,true,"Texto"), 
			"Cidade"               => $ObjForm->getCampoRetorna("Cidade".$Chave,true,"Texto"),
			"Rua"                  => $ObjForm->getCampoRetorna("Rua".$Chave,true,"Texto"),
			"Numero"               => $ObjForm->getCampoRetorna("Numero".$Chave,true,"Texto"),
			"Bairro"               => $ObjForm->getCampoRetorna("Bairro".$Chave,true,"Texto"),
			"CEP"                  => $ObjForm->getCampoRetorna("CEP".$Chave,true,"Texto"),
			"Complemento"          => $ObjForm->getCampoRetorna("Complemento".$Chave,true,"Texto"),
			"Mapa"		           => $ObjForm->getCampoRetorna("Mapa".$Chave,true,"Texto"),
			"Padrao"               => $Padrao 
			));							  	
		}
		
		//Cadastra os dados do endere�o
		$this->cadastrarEnderecoDados();	
	}	
	
	public function cadastrarEnderecoDados()
	{
		$Con = Conexao::conectar();
		
		foreach ($this->EnderecoDados as $ArrayDados)
		{		
			$Sql[] = parent::cadastrarEnderecoDadosSql($ArrayDados);
		}
		
		$Con->executarArray($Sql);
	}	
	
	
	public function alterarEndereco($ObjForm, $EnderecoCod)
	{
		//Conex�o com o banco
		$Con = Conexao::conectar();
				
		//Remove os dados do endere�o
		$this->removerEnderecoDados($EnderecoCod);
		
		//Endere�o Cod Gerado
		$this->setEnderecoCod($EnderecoCod);		
		
		//Recupera o Array Com os Endere�os Selecionados
		$ArrayEndereco = $_POST['ArrayEndereco'];
		
		//N�o foi apresentado
		if(!is_array($ArrayEndereco)) throw new Exception("Endere�o Inv�lido");
		
		//Setando valores do form
		foreach($ArrayEndereco as $Chave=>$Nada) $ObjForm->getFormEndereco($Chave);	

		//Seta os dados Apresentados pelo form		
		foreach($ArrayEndereco as $Chave=>$Nada)
		{
			$Padrao = $_POST['PadraoEnd'] == $Chave ? "'S'" : "'N'";
		
			$this->setEnderecoDados(array(
			"EnderecoDadosTipoCod" => $ObjForm->getCampoRetorna("EnderecoDadosTipoCod".$Chave,false,"Inteiro"), 
			"Pais"                 => $ObjForm->getCampoRetorna("Pais".$Chave,true,"Texto"), 
			"Estado"               => $ObjForm->getCampoRetorna("Estado".$Chave,true,"Texto"), 
			"Cidade"               => $ObjForm->getCampoRetorna("Cidade".$Chave,true,"Texto"),
			"Rua"                  => $ObjForm->getCampoRetorna("Rua".$Chave,true,"Texto"),
			"Numero"               => $ObjForm->getCampoRetorna("Numero".$Chave,true,"Texto"),
			"Bairro"               => $ObjForm->getCampoRetorna("Bairro".$Chave,true,"Texto"),
			"CEP"                  => $ObjForm->getCampoRetorna("CEP".$Chave,true,"Texto"),
			"Complemento"          => $ObjForm->getCampoRetorna("Complemento".$Chave,true,"Texto"),
			"Mapa"		           => $ObjForm->getCampoRetorna("Mapa".$Chave,true,"Texto"),
			"Padrao"			   => $Padrao
			));	
		}
		
		//Cadastra os dados do endere�o
		$this->cadastrarEnderecoDados();	
	}	
		
	/**
	*	Repons�vel pela remo��o das informa��es
	*	@return Void
	*/	
	public function removerEndereco($EnderecoCod)
	{
		$Con = Conexao::conectar();
		
		$Con->executar(parent::removerEnderecoSql($EnderecoCod));
		
		$this->removerEnderecoDados($EnderecoCod);
	}
	
	//Remove endere�o por ID
	public function removerEnderecoDados($EnderecoCod)
	{
		//Conexao
		$Con = Conexao::conectar();
					
		//Dados do Endere�o			
		$Con->executar(parent::removerEnderecoDadosSql($EnderecoCod));
	}

	
	public function getEndereco($EnderecoCod, $IdForm, $ObjForm)
	{
		$FPHP = new FuncoesPHP();
			
		$Con = Conexao::conectar();
		
		$Sql  = parent::getEnderecoDadosSql($EnderecoCod);
		
		$HtmlEndereco = "";
		
		$RSEndereco = $Con->executar($Sql);
		$NEnderecos = $Con->nLinhas($RSEndereco);
		
		if($NEnderecos > 0)
		{
			while ($DadosEndereco = @mysqli_fetch_assoc($RSEndereco))
			{		
				$Cont = $DadosEndereco['EnderecoDadosCod'];
				
				$Campos = array("EnderecoDadosCod".$Cont, "EnderecoDadosTipoCod".$Cont, "Pais".$Cont, "Estado".$Cont, "Cidade".$Cont, "Rua".$Cont, "Numero".$Cont, "Bairro".$Cont, "CEP".$Cont, "Complemento".$Cont, "Mapa".$Cont, "PadraoEnd".$Cont);
			
				$CamposForm = array_combine($Campos, $DadosEndereco);
			
				$FPHP->extractVar($CamposForm, "POST");
				
				$PadraoChecado = '';
				
				if($_POST['PadraoEnd'.$Cont] == "S")
				{
					$PadraoChecado = 'checked="checked"';
				}
				
				ob_start();
				
				$CamposEnd = $ObjForm->getFormEndereco($Cont);
				include($_SESSION['DirBase'].'interatividade/endereco/endereco.tpl.php');
/*				echo '<script language="javascript"> $(document.body).ready(function() { $(\'#FormManu'.$IdForm.' #CEP'.$Cont.'\').mask(\'99999-999\'); }); </script>';*/
				
				$HtmlEndereco .= ob_get_contents();
				
				//Limpa Buffer
				ob_end_clean();		
			}
		}

		return $HtmlEndereco;
	}	
}
?>