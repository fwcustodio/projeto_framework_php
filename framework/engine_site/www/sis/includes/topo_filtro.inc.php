<table width="<?=ConfigSIS::$CFG['LarguraTabela']?>" align="<?=ConfigSIS::$CFG['AlinhaTabela']?>" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td width="10" height="39" background="<?=$_SESSION['UrlBase']?>figuras/bg_top.gif">&nbsp;</td>
    <td background="<?=$_SESSION['UrlBase']?>figuras/bg_top.gif"><a href="<?=$_SESSION['UrlBase']?>principal.php" title="Início"><img src="<?=$_SESSION['UrlBase']?>figuras/sis_logo.gif" border="0"/></a></td>
    <td align="center" background="<?=$_SESSION['UrlBase']?>figuras/bg_top.gif">&nbsp;</td>
    <td align="right" background="<?=$_SESSION['UrlBase']?>figuras/bg_top.gif"><img src="<?=$_SESSION['UrlBase']?>figuras/logo_cliente_topo.gif" border="0"/></td>
    <td width="10" background="<?=$_SESSION['UrlBase']?>figuras/bg_top.gif">&nbsp;</td>
  </tr>
  <tr> 
    <td height="5" colspan="6" background="<?=$_SESSION['UrlBase']?>figuras/sombra.gif"> </td>
  </tr>
</table>
<table width="<?=ConfigSIS::$CFG['LarguraTabela']?>" align="<?=ConfigSIS::$CFG['AlinhaTabela']?>" border="0" cellpadding="0" cellspacing="0" background="<?=$_SESSION['UrlBase']?>figuras/bg_title.gif" bgcolor="#CCCCCC">
  <tr>
    <td width="10" align="center">&nbsp;</td>
    <td align="left">
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td><?=$_SESSION['Menu']?></td>
      </tr>
    </table>
	</td>
    <td width="10" align="center">&nbsp;</td>
  </tr>
  <tr> 
    <td height="2" colspan="3" background="<?=$_SESSION['UrlBase']?>figuras/sombra_menu.gif"> </td>
  </tr>
</table>
<table width="<?=ConfigSIS::$CFG['LarguraTabela']?>" align="<?=ConfigSIS::$CFG['AlinhaTabela']?>" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="right" height="45"><table border="0" align="right" cellpadding="2" cellspacing="0">
      <tr>
        <td class="t11preto"><?=ConfigSIS::$CFG["TituloAdm"]?>&nbsp;(<?=$_SESSION['NomeUser']?>)</td>
        <td align="center"><a href="<?=$_SESSION['UrlBase']?>principal.php"><img src="<?=$_SESSION['UrlBase']?>figuras/inicio_ico.gif" border="0" /></a></td>
        <td align="center"><a href="<?=$_SESSION['UrlBase']?>"><img src="<?=$_SESSION['UrlBase']?>figuras/trocar_ico.gif" border="0" /></a></td>
        <td align="center"><a href="<?=$_SESSION['UrlBase']?>sair/"><img src="<?=$_SESSION['UrlBase']?>figuras/sair_ico.gif" border="0" /></a></td>
      </tr>
    </table></td>
  </tr>
</table>
<!--Filtro-->
<table width="<?=$Cliente["LarguraTabela"]?>" border="0" cellspacing="0" cellpadding="0" align="<?=$Cliente["AlinhaTabela"]?>">  
  <tr>
    <td width="160" bgcolor="#DBDBDB"><span class="style4"><img src="<?=$_SESSION['UrlBase']?>figuras/filtro.gif" width="150" height="30" /></span></td>
    <td width="190" bgcolor="#DBDBDB"><? //=$Ac->imagemAcao($Permissoes)?></td>
    <td bgcolor="#DBDBDB"><div align="center"><span class="tituloModulo">
      <? //=$Ac->nomeModulo()?>
    </span></div></td>
  </tr>
</table>