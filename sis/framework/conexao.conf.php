<?php 

class ConexaoConf 
{ 
	/*
	** Atributos da Classe
	*/ 	
	private $User, $Senha, $Banco, $Host;	

	/*
	** Método Construtor
	*/	
	protected function ConexaoConf()
	{
                //Variaveis de Conexao
		$this->setUser("root");
		$this->setSenha("enigma0001");
		$this->setBanco("engine_site");
		$this->setHost("192.168.0.2");
	}

	protected function setUser($Valor)
	{
		$this->User = $Valor;
	}
	
	protected function getUser()
	{
		return $this->User;
	}
	
	protected function setSenha($Valor)
	{
		$this->Senha = $Valor;
	}
	
	protected function getSenha()
	{
		return $this->Senha;
	}
	
	protected function setBanco($Valor)
	{
		$this->Banco = $Valor;
	}
	
	protected function getBanco()
	{
		return $this->Banco;
	}
	
	protected function setHost($Valor)
	{
		$this->Host = $Valor;
	}
	
	protected function getHost()
	{
		return $this->Host;
	}	
}
