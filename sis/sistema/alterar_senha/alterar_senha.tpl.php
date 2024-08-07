<fieldset id="fild<?=$Id?>" class="fildManu">
<legend class="legendaManu"><?=$Ac->opcaoExt($Op);?></legend>
  <form id="FormManu<?=$Id?>" name="FormManu<?=$Id?>" method="post" action="" onSubmit="return false" >  <!-- PARA MÓDULOS COM UPLAOD COLOCAR: enctype="multipart/form-data" NO TAG DO FORM -->
	  <table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr><td width="170" align="right" class="textoForm">Nome:</td><td><?=$Campos['Nome']?></td></tr>
		<tr><td width="170" align="right" class="textoForm">Senha:</td><td><?=$Campos['Senha']?></td></tr>
		<tr><td width="170" align="right" class="textoForm">E-mail:</td><td><?=$Campos['Email']?></td></tr>

		<? if($Op == "Cad") { ?>
		<tr>
		  <td align="right" class="textoForm">Cadastrar:</td>
		  <td class="textoFormInterno"><input name="FormaCadastro" type="radio" value="Unico" checked="checked" />
              Somente Este 
              <input name="FormaCadastro" type="radio" value="Varios" />
              V&aacute;rios</td>
	    </tr>
		<? } ?>
		<tr>
		  <td align="right" class="tdManuOpcoes"><span class="textoForm">
		    <?=$Campos['Id']?>
		  </span></td>
		  <td>
		  <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td class="tdManuOpcoes">
			  <div class="manuOpcoes">
               
                <? if($Op == "Alt") { ?>
				<input name="Id" type="hidden" id="Id" value="<?=$Id?>" />
                <input type="button" name="Button" value="Alterar" onclick="if(validaForm<?=$Id?>()) alteraBd('FormManu<?=$Id?>','fild<?=$Id?>')" />
                <? } ?>
                <input name="DescartUm" type="button" id="DescartUm" onclick="$('#fild<?=$Id?>').remove()" value="Descartar"/>

              </div>			  </td>
            </tr>
          </table></td>
	    </tr>
    </table>
  </form>
</fieldset>