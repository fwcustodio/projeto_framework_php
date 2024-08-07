<?
/**
*	@copyright DEMP - Solu��es em Tecnologia da Informa��o Ltda
*	@author Pablo Vanni - pablovanni@gmail.com
*	@since 30/05/2006
*	<br>�ltima Atualiza��o: 28/05/2007<br>
*	Autualizada Por: Pablo Vanni - pablovanni@gmail.com<br>
*	@name Verifica usu�rios para efetuar login no sistema
* 	@version 2.0
*  	@package Login
*/

include_once($_SESSION['FMBase'].'variaveis.class.php');
include_once($_SESSION['FMBase'].'menu.class.php');
include_once($_SESSION['DirBase'].'login/login.bd.php');

class Login extends LoginBD 
{
	/**
	*	Atributos da Classe
	*/
	private $Var, $Menu;
	
	/**
	*	Metodo Construtor
	*	@return VOID
	*/	
	public function Login()
	{
		parent::LoginBD();
				
		$this->Var  = new Variaveis();
		$this->Menu = new Menu();
	}
	
	/**
	*	Verifica e autentica o usu�rio no sistema
	*	@return Booleano
	*/
	public function verificaUsuario($ObjForm)
	{	
		$StringCrypt = ConfigSIS::$CFG['StringCrypt'];
		
		$UserName = $ObjForm->getCampoRetorna('UserName');
		$UserPass = crypt($ObjForm->getCampoRetorna('UserPass'),$StringCrypt);

		//Executa
		try
		{			
			//Executa Query de Verifica��o
			$RS = parent::verifica($UserName, $UserPass);

			if($this->Con->nLinhas($RS) == 1)
			{		
				//Buscando Variaveis
				$Linha = $this->Con->linha($RS);
				
				//Registra Sess�o
				$_SESSION['UsuarioCod'] = $Linha["UsuarioCod"];
				$_SESSION['UserName']   = $Linha["Login"];
				$_SESSION['NomeUser']   = $Linha["Nome"];
				
				//Ultimo Acesso
				$_SESSION['Ua'] = $Linha["Ua"];
							
				//Gera Menu de Acesso
				$_SESSION['Menu'] = $this->Menu->geraMenu($_SESSION['UrlBase']);
				
				//Inicia Transa��o
				$this->Con->startTransaction();

				//Atualiza Dados de Acesso
				parent::atualizaAcessos($this->Var->dataHora());
					
				//Finaliza Transa��o
				return $this->Con->stopTransaction();
			}
			else 
			{
				return false;
			}
		}
		catch(Exception $E)
		{
			//Finaliza Transa��o
			$this->Con->stopTransaction($E->getMessage());
			
			throw new Exception($E->getMessage());
		}
	}
}