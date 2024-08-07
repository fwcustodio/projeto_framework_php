<fieldset id="fild<?=$Id?>" class="fildManu">
<legend class="legendaManu"><?=$Ac->opcaoExt($Op);?></legend>
  <form id="FormManu<?=$Id?>" name="FormManu<?=$Id?>" method="post" action="" onSubmit="return false" >
	  <table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr><td width="170" align="right" class="textoForm">Galeria de Arquivos:</td><td><?=$Campos['ArquivoCategoriaNome']?></td></tr>
		<tr>
		  <td align="right" class="textoForm">Publicar:</td>
		  <td class="textoForm"><?
                $PubS = (empty($_POST['Publicar']) or $_POST['Publicar'] == 'S') ? 'checked="checked"': '';
                $PubN = ($_POST['Publicar'] == 'N') ? 'checked="checked"': '';
          ?>
              <label>
                <input type="radio" name="Publicar"  value="S" <?=$PubS?> />
                Sim</label>
              <label>
                <input type="radio" name="Publicar"  value="N" <?=$PubN?>/>
                N&atilde;o</label>
          </td>
	    </tr>
		<tr>
          <td align="right" class="textoForm">Situa&ccedil;&atilde;o:</td>
		  <td class="textoForm"><?
                $SitS = (empty($_POST['Situacao']) or $_POST['Situacao'] == 'A') ? 'checked="checked"': '';
                $SitN = ($_POST['Situacao'] == 'I') ? 'checked="checked"': '';
          ?>
              <label>
                <input type="radio" name="Situacao"  value="A" <?=$SitS?> />
                Ativo</label>
              <label>
                <input type="radio" name="Situacao"  value="I" <?=$SitN?>/>
                Inativo</label>
          </td>
	    </tr>
		

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
		  <td align="right" class="tdManuOpcoes">&nbsp;</td>
		  <td>
		  <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td class="tdManuOpcoes">
			  <div class="manuOpcoes">
                <? if($Op == "Cad") { ?>
                <input type="button" name="Button" value="Cadastrar" onclick="if(validaForm()) cadastraBd()" />
                <? } elseif($Op == "Alt") { ?>
				<input name="Id" type="hidden" id="Id" value="<?=$Id?>" />
                <input type="button" name="Button" value="Alterar" onclick="if(validaForm<?=$Id?>()) alteraBd('FormManu<?=$Id?>','fild<?=$Id?>')" />
                <? } ?>
                <input name="DescartUm" type="button" id="DescartUm" onclick="$('#fild<?=$Id?>').remove()" value="Descartar"/>
                <? if($Op == "Alt") { ?>
                <input name="FTodos" type="button" id="FTodos" onclick="$('#manu').html('')" value="Descartar Todos" />
				<? } ?>
              </div>			  </td>
            </tr>
          </table></td>
	    </tr>
    </table>
</form>
</fieldset>