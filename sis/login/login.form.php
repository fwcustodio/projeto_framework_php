<?
include_once($_SESSION['FMBase'].'form_campos.class.php');
include_once($_SESSION['FMBase'].'ajax.class.php');

class LoginForm extends FormCampos 
{
	public function LoginForm()
	{
		parent::FormCampos();
	}

	public function getFormManu()
	{
		$Metodo = "POST";
		
		$Login = parent::inputTexto(array(
		"Nome"       => "UserName",
		"Identifica" => "Nome do Usuário",
		"Valor"      => parent::retornaValor($Metodo,"UserName"),
		"Id"         => "UserName",
		"Largura"    => 30,
		"Min"        => 1,
		"Max"        => 30,
		"Tratar"     => array("L","H","A"),
		"ValidaJS"   => true,
		"Estilo"     => "width:220px; height:21px;",
		"Adicional"  => "class='campo'"),true);

		$Senha = parent::inputSenha(array(
		"Nome"       => "UserPass",
		"Identifica" => "Senha do Usuário",
		"Valor"      => parent::retornaValor($Metodo,"UserPass"),
		"Id"         => "UserPass",
		"Largura"    => 20,
		"Min"        => 6,
		"Max"        => 20,
		"Tratar"     => array("L","H","A"),
		"ValidaJS"   => true,
		"Estilo"     => "width:220px; height:21px;",
		"Adicional"  => "class='campo'"),true);

		$Botao = parent::botao(array(
		"Nome"       => "BtLogin",
		"Identifica" => "",
		"Tipo"       => "button",
		"SRC"        => $_SESSION['UrlBase']."figuras/bt_efetuar_login.gif",
		"Estilo"     => "cursor:pointer; background:url(".$_SESSION['UrlBase']."figuras/bt_efetuar_login.gif); background-repeat:no-repeat; width:86px; height:22px;",
		"Adicional"  => "onClick=\"if(validacao())login()\""));
				
		//Ajax
		self::ajaxRetorno();
				
		return array("Login"=>$Login, "Senha" =>$Senha, "Botao"=>$Botao);
	}	
		
	public function ajaxRetorno()
	{
		$Ajax = new Ajax();
		
		$Url = (empty($_GET['Ref'])) ? 'principal.php' : urldecode($_GET['Ref']);
		
		parent::setFuncoes($Ajax->ajaxRequest(array(
		"Nome"       => "login", 
		"URL"        => $_SESSION['UrlBase']."login/login.php",
		"Completa"   => 'function(Req) 
		{ 
			if(Req.responseText == "true") 
			{ 
				$("#tabelaLogin").fadeOut();
				$("#Form").append("<img src=\''.$_SESSION['UrlBase'].'figuras/loading.gif\' border=\'0\' />");
				window.location="'.$Url.'"; 
			} 
			else 
			{ 
				$("#erro").empty().html(Req.responseText); 
				$("#erro").show("slow"); 
				setTimeout(function(){ $("#erro").fadeOut(); },5000);
			} 
		}')));		
		
		/*
		Função Anterior
		parent::setFuncoes($Ajax->ajaxRequest(array(
		"Nome"       => "login", 
		"URL"        => $_SESSION['UrlBase']."login/login.php",
		"Completa"   => 'function(Req) { sis_autLogin(Req); }')));*/
	}
}
?>