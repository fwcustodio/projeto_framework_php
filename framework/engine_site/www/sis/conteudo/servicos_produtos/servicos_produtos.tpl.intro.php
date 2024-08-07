<fieldset id="fild" class="fildManu">
<legend class="legendaManu">Alterar Texto de Introdução</legend>
  
<form enctype="multipart/form-data" id="FormManuIntro" name="FormManuIntro" method="post" action="" onSubmit="return false" >
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td align="left" valign="top" class="classConteudo" bgcolor="#E6F2FF"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td><?=$Campos['TextoIntroducao']?></td>
          </tr>
        
        <tr>
          <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td class="tdManuOpcoes"> <div class="manuOpcoes">
                <input type="button" name="Button" value="Alterar" onclick="alteraBdIntro('FormManuIntro','fild')" />
               
                <input name="FTodos" type="button" id="FTodos" onclick="$('#manu').html('')" value="Descartar" />

                </div>	</td>
              </tr>
          </table></td>
          </tr>
      </table></td>
      </tr>
  </table>
</form >
</fieldset>