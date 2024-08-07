</div>		  </td>
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
		  <td align="right" class="tdManuOpcoes"><span class="textoForm">
		    <?=$Campos['Id']?>
		  </span></td>
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
                <input name="SDados" type="checkbox" id="SDados" value="S" />
Somente Dados
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