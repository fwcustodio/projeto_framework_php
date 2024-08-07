<?
/**
*	@copyright DEMP - Soluções em Tecnologia da Informação Ltda
*	@author Pablo Vanni - pablovanni@gmail.com
*	@since 28/05/2007
*	<br>Última Atualização: 28/05/2007<br>
*	Autualizada Por: Pablo Vanni - pablovanni@gmail.com<br>
*	@name Metodos de Acesso ao banco de dados
* 	@version 2.0
*  	@package Login
*/

include_once($_SESSION['FMBase'].'conexao.class.php');

class LoginBD
{
	/**
	*	Atributos da Classe
	*/	
	protected $Con;
	
	/**
	*	Metodo Construtor
	*	@return VOID
	*/	
	public function LoginBD()
	{
		$this->Con  = Conexao::conectar();
	}
	
	/**
	*	Verifica se o usuário esta cadastrado no sistema
	*	@return ResultSet
	*/
	protected function verifica($Username, $UserPass)
	{	
		$Sql = "SELECT a.UsuarioCod, a.Login, UsuarioDadosNome as Nome, 
					   DATE_FORMAT(a.UltimoAcesso,'%d/%m/%Y as %H:%i') as Ua  
				FROM   _usuarios a, usuario_dados b
				WHERE a.UsuarioCod = b.UsuarioCod
				AND a.Login = '".$Username."' 
				AND a.Senha = '".$UserPass."'
				  AND a.Status = 'A'";

		return $this->Con->executar($Sql);
	}
	
	/**
	*	Atualiza Número de Acessos do Usuário Logado
	*	@return Booleano
	*/
	protected function atualizaAcessos($DataHora)
	{
		$Sql = "UPDATE _usuarios  
				SET UltimoAcesso ='".$DataHora."', NumeroAcessos = (NumeroAcessos + 1) 
				WHERE UsuarioCod = ".$_SESSION['UsuarioCod'];	
		
		return $this->Con->executar($Sql);
	}
}
?>