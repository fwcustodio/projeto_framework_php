<?
//Starta Sessão
session_start();

// HTTP/1.1 - Elimina Cache
@header("Cache-Control: no-store, no-cache, must-revalidate");
@header("Cache-Control: post-check=0, pre-check=0", false);
@header("Content-Type: text/html; charset=ISO-8859-1",true);

//Busca Dados de Configuração
include_once('../framework/config.conf.php'); ConfigSIS::Conf();
include_once($_SESSION['DirBase']."framework/help.class.php");
?>
<table height="100%" width="100%" style="background:transparent url(<?=$_SESSION['UrlBase']?>figuras/alpha.gif);">
<tr align="center" valign="middle">
<td>

<table  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="15" height="20" background="<?=$_SESSION['UrlBase']?>figuras/help_border_1.gif" style="background-repeat:no-repeat"></td>
    <td height="20" align="right" valign="middle" background="<?=$_SESSION['UrlBase']?>figuras/help_border_2.gif" style="background-repeat:repeat-x"><a onClick="$('#help').hide()" style="cursor:pointer" ><img src="<?=$_SESSION['UrlBase']?>figuras/bt_fechar_div.gif" border="0"/></a></td>
    <td width="15" height="20" background="<?=$_SESSION['UrlBase']?>figuras/help_border_3.gif" style="background-repeat:no-repeat"></td>
  </tr>
  <tr>
    <td width="15" background="<?=$_SESSION['UrlBase']?>figuras/help_border_4.gif" style="background-repeat:repeat-y"></td>
    <td>
	<div class="conteudo"><? try{ new Help($_POST['Mod']); } catch(Exception $E) { echo $E->getMessage(); }?></div>
</td>
    <td width="15" height="15" background="<?=$_SESSION['UrlBase']?>figuras/help_border_5.gif" style="background-repeat:repeat-y"></td>
  </tr>
  <tr>
    <td width="15" height="15" background="<?=$_SESSION['UrlBase']?>figuras/help_border_6.gif" style="background-repeat:no-repeat"></td>
    <td height="15" background="<?=$_SESSION['UrlBase']?>figuras/help_border_7.gif" style="background-repeat:repeat-x"></td>
    <td width="15" height="15" background="<?=$_SESSION['UrlBase']?>figuras/help_border_8.gif" style="background-repeat:no-repeat"></td>
  </tr>
</table>
</td></tr>
</table>