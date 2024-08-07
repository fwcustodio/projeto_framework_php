<?
//Starta/Limpa/Destroi
session_start();
session_unset();
session_destroy();

//Busca Dados de Configuração
include_once('framework/config.conf.php'); ConfigSIS::Conf();
                                
//Includes
include_once($_SESSION['DirBase'].'login/login.form.php');      

//Intanciando Classes
$Form   = new LoginForm();
$Form->setNomeForm("Form");
$Campos = $Form->getFormManu(); 
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?=ConfigSIS::$CFG['NomeCliente']?></title>
<link href="<?=$_SESSION['CSSBase']?>css/css.css" rel="stylesheet" type="text/css">
<link href="<?=$_SESSION['UrlBase']?>figuras/favicon.ico" rel="shortcut icon" />
<!--[if IE]><style type="text/css" media="screen"> .loading{position:absolute;bottom:-65px;	right:0; } </style><![endif]-->
<script type="text/javascript" src="<?=$_SESSION['JSBase']?>js/jquery.js"></script>
<script type="text/javascript" src="<?=$_SESSION['JSBase']?>js/validacao.js"></script>
<script type="text/javascript" src="<?=$_SESSION['JSBase']?>js/util.js"></script>
<? echo $Form->geraValidacaoJS("validacao","Form"),$Form->geraFuncoes(),$Form->geraOnLoad();?>
<script language="javascript">
$(document).ready(function(){
if('<?=$_GET['Msg']?>' != '')
{ 
	$("#erro").empty().html('Sua sessão expirou!');
	$('#erro').show("slow");
	setTimeout(function(){ $("#erro").fadeOut(); },5000);
}
$("#Form").keyup(function(event){ if(event.keyCode==13 || event.which==13)login();}); $("#Form #UserName").focus(); });
</script>
</head>
<body>
<div id="carregando" class="loading"><img src="figuras/loading.gif"></div>
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center" valign="top" background="figuras/bg.gif" bgcolor="#244E56" style="background-repeat:repeat-x"><table width="90%" height="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td height="21"></td>
      </tr>
      <tr>
        <td height="10"><table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="5" height="51" background="figuras/borda_top_esq.gif" style="background-repeat:no-repeat"></td>
            <td height="51" align="center" valign="middle" background="figuras/borda_top_bg.gif" style="background-repeat:repeat-x"><table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td align="left" valign="middle"><img src="figuras/logo_cliente.gif" ></td>
                <td align="right" valign="middle"><img src="figuras/logo_gcd.gif" ></td>
              </tr>
            </table></td>
            <td width="5" height="51" background="figuras/borda_top_dir.gif" style="background-repeat:no-repeat"></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="5" background="figuras/borda_meio_esq.gif" style="background-repeat:repeat-y"></td>
            <td align="center" valign="middle" background="figuras/borda_meio_bg.gif" bgcolor="#FFFFFF" style="background-repeat:repeat-x">
			<form action="" method="post" name="Form" id="Form" onSubmit="return false" style="padding:0; margin:0;">
			<table width="360" border="0" cellpadding="0" cellspacing="0" id="tabelaLogin">
              <tr>
                <td width="360" height="25" background="figuras/bg_login_top.gif" class="titulo-arial-UPER-BOLD" style="background-repeat:no-repeat">efetuar login </td>
              </tr>
              <tr>
                <td width="360" align="center" valign="top" background="figuras/bg_login_meio.gif" style="background-repeat:repeat-y; padding-top:10px;"><span style="padding-top:10px" class="texto-arial-11"> Informe seu usu&aacute;rio e senha para entrar no sistema.</span><br>
                    <br>
					<table width="82%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td width="16%" valign="middle" class="texto-campos-arial-azul">Usu&aacute;rio:&nbsp;</td>
                        <td width="84%" align="right" valign="middle"><?=$Campos['Login']?></td>
                      </tr>
                      <tr>
                        <td height="27" valign="middle" class="texto-campos-arial-azul">Senha:&nbsp;</td>
                        <td align="right" valign="middle"><?=$Campos['Senha']?></td>
                      </tr>
                      <tr>
                        <td height="28" align="left" valign="top">&nbsp;</td>
                        <td align="right" valign="middle"><?=$Campos['Botao']?></td>
                      </tr>
                    </table>
                  <div id="erro" class="error"></div>
				  <br>
                </td>
              </tr>
              <tr>
                <td width="360" height="4" background="figuras/bg_login_rodape.gif" style="background-repeat:no-repeat"></td>
              </tr>
            </table>
			</form>			
                <br></td>
            <td width="5" background="figuras/borda_meio_dir.gif" style="background-repeat:repeat-y"></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td height="10" align="center" valign="top"><table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td height="5" background="figuras/borda_meio_esq.gif" style="background-repeat:repeat-y"></td>
            <td height="5" align="center" valign="top" bgcolor="#FFFFFF" style="background-repeat:repeat-x"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="91%"></td>
                <td width="9%" align="right" valign="bottom"><a href="http://www.demp.com.br" target="_blank"><img src="figuras/demp_logo_color.gif" border="0"></a></td>
              </tr>
            </table></td>
            <td height="5" background="figuras/borda_meio_dir.gif" style="background-repeat:repeat-y"></td>
          </tr>
          <tr>
            <td width="5" height="5" background="figuras/borda_rodape_esq.gif" style="background-repeat:no-repeat"></td>
            <td height="5" align="center" valign="top" background="figuras/borda_rodape_bg.gif" style="background-repeat:repeat-x">&nbsp;</td>
            <td width="5" height="5" background="figuras/borda_rodape_dir.gif" style="background-repeat:no-repeat"></td>
          </tr>
        </table></td>
      </tr>
    </table>
    </td>
  </tr>
</table>
</body>
</html>