<fieldset id="fild<?=$Id?>" class="fildManu">
<legend class="legendaManu"><?=$Ac->opcaoExt($Op);?></legend>
  <form id="FormManu<?=$Id?>" name="FormManu<?=$Id?>" method="post" action="" onSubmit="return false" >
	  <table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
		  <td width="170" align="right" class="textoForm">	      Nome do Grupo:</td>
		  <td><?=$Campos['NomeGrupo']?></td>
		</tr>
		<tr>
		  <td align="right" class="textoForm">Pacote:</td>
		  <td ><?=$Campos['Pacote']?></td>
		</tr>
		<tr>
		  <td align="right" class="textoForm">Posi&ccedil;&atilde;o:</td>
		  <td><?=$Campos['Posicao']?></td>
		</tr>
		
		<!--Inica final do form-->
		<? if($Op == "Cad" and empty($_GET['Pop'])) { ?>
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
                <? } ?>
                <input name="DescartUm" type="button" id="DescartUm" onclick="<? if(!empty($_GET['Pop'])) { ?> self.close(); <? } else { ?>$('#fild<?=$Id?>').remove(); <? }?>" value="Descartar"/>
                <?  if($Op == "Alt") { ?>
                <input name="FTodos" type="button" id="FTodos" onclick="$('#manu').html('')" value="Descartar Todos" />
				<? } ?>
              </div>			  </td>
            </tr>
          </table></td>
	    </tr>
      <!--Finaliza Final Form-->
    </table>
  </form>
</fieldset>
