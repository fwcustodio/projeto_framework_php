<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?=ConfigSIS::$CFG["TituloAdm"]?></title>
<script type="text/javascript" language="javascript"> var URLBASE = '<?=$_SESSION['UrlBase']?>'; </script>
<link href="<?=$_SESSION['CSSBase']?>css/css.css" rel="stylesheet" type="text/css" />
<link href="<?=$_SESSION['CSSBase']?>css/menu.css?v=1" rel="stylesheet" type="text/css" media="screen" />
<link href="<?=$_SESSION['UrlBase']?>figuras/favicon.ico" rel="shortcut icon" />
<!--[if IE]><style type="text/css" media="screen">body { behavior: url(<?=$_SESSION['CSSBase']?>css/menuie.htc); font-size: 100%; } #menu ul li {float: left; width: 100%;} #menu ul li a {height: 1%;}  #menu a, #menu h2 { font: bold 0.7em/1.4em arial, helvetica, sans-serif;} .loading{position:absolute;bottom:-65px;	right:0; } </style><![endif]-->
<script type="text/javascript" src="<?=$_SESSION['JSBase']?>js/jquery.js"></script>
<script type="text/javascript" src="<?=$_SESSION['JSBase']?>js/jquery.livequery.js"></script>
<script type="text/javascript" src="<?=$_SESSION['JSBase']?>js/jquery.validation.js"></script>
<script type="text/javascript" src="<?=$_SESSION['JSBase']?>js/validacao.js"></script>
<script type="text/javascript" src="<?=$_SESSION['JSBase']?>js/util.js"></script>
<script type="text/javascript" src="<?=$_SESSION['JSBase']?>js/filtro.js"></script>
<script type="text/javascript" src="<?=$_SESSION['JSBase']?>js/jquery.blockUI.js"></script>

<!-- INICIO MODAL LOGIN -->
<div id="loaderNovoLogin" style="display:none">
<form action="" method="post" name="FormLogin" id="FormLogin" onSubmit="return false" style="padding:0; margin:0;">
<table width="360" border="0" cellpadding="0" cellspacing="0" id="tabelaLogin" align="center">
  <tr>
    <td width="360" height="25" class="titulo-arial-UPER-BOLD">efetuar login </td>
  </tr>
  <tr>
    <td width="360" align="center" valign="top" style="padding-top:10px;"><span style="padding-top:10px" class="texto-arial-11"> Informe seu usu&aacute;rio e senha para entrar no sistema.</span><br>
        <br>
        <table width="82%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="16%" valign="middle" class="texto-campos-arial-azul">Usu&aacute;rio:&nbsp;</td>
            <td width="84%" align="right" valign="middle"><input type="text" name="UserName" id="UserName" class="campo" style="width:220px; height:21px;" /></td>
          </tr>
          <tr>
            <td height="27" valign="middle" class="texto-campos-arial-azul">Senha:&nbsp;</td>
            <td align="right" valign="middle"><input type="password" name="UserPass" id="UserPass" class="campo" style="width:220px; height:21px;" /></td>
          </tr>
          <tr>
            <td height="28" align="left" valign="top">&nbsp;</td>
            <td align="right" valign="middle"><input onclick="novoLogin()" type="image" src="<?=$_SESSION['UrlBase']?>figuras/bt_efetuar_login.gif" name="BtLogin" id="BtLogin" style="cursor:pointer;" /></td>
          </tr>
        </table>
      <div id="erro" class="error"></div>
      <br>
    </td>
  </tr>
</table>
</form>	
</div>
<!-- FIM MODAL LOGIN -->
