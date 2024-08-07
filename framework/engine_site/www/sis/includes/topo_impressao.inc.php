<script>
$(document).ready(function(){
	$("body").css("background", "none");						   
});
</script>
<table width="<?=ConfigSIS::$CFG['LarguraTabela']?>" border="0" align="<?=ConfigSIS::$CFG['AlinhaTabela']?>" cellpadding="0" cellspacing="0">
  <tr>
    <td><table width="100%" height="10" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td height="10"><table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td height="48" align="center" valign="middle" bgColor="#0d252a"><table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td height="48" align="left" valign="middle"><img src="<?=$_SESSION['UrlBase']?>figuras/top_print3.jpg" width="125" height="48" /></td>
                      <td align="center" valign="middle" width="100%" height="48" class="tituloSis-trebushet-BOLD"><table width="100%" border="0" height="100%" cellspacing="0" cellpadding="0">
                          <tr>
                            <td>&nbsp;&nbsp;&nbsp;&nbsp;<?=$Ac->nomeModulo()?></td>
                          </tr>
                          <tr height="6px">
                            <td height="6px"><img src="<?=$_SESSION['UrlBase']?>figuras/top_print2.jpg" width="100%" height="6px" /></td>
                          </tr>
                        </table></td>
                      <td height="48" align="right" valign="middle"><img src="<?=$_SESSION['UrlBase']?>figuras/top_print1.jpg" width="109" height="48" ></td>
                    </tr>
                  </table></td>
              </tr>
            </table></td>
        </tr>
      </table></td>
  </tr>
</table>
<br />
