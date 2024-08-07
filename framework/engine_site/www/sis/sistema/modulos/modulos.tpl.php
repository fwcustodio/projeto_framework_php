<fieldset id="fild<?=$Id?>" class="fildManu">
<legend class="legendaManu"><?=$Ac->opcaoExt($Op);?></legend>
  <form id="FormManu<?=$Id?>" name="FormManu<?=$Id?>" method="post" action="" onSubmit="return false" >
	  <table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
		  <td width="170" align="right" class="textoForm">	      Grupo:</td>
		  <td><?=$Campos['GrupoCod']?></td>
		</tr>
		<tr>
          <td align="right" class="textoForm">Referencia:</td>
		  <td ><div id="mReferentes"><?=$Campos['Referencia']?></div></td>
	    </tr>
		<tr>
		  <td align="right" class="textoForm">Nome do M&oacute;dulo:</td>
		  <td ><?=$Campos['ModuloNome']?></td>
		</tr>
		<tr>
		  <td align="right" class="textoForm">Nome no Menu:</td>
		  <td><?=$Campos['NomeMenu']?></td>
		</tr>
		<tr>
          <td align="right" class="textoForm">Descri&ccedil;&atilde;o do M&oacute;dulo:</td>
		  <td><?=$Campos['ModuloDesc']?></td>
	    </tr>
		<tr>
          <td align="right" class="textoForm">Deve ser Visivel no Menu?</td>
		  <td><?=$Campos['VisivelMenu']?></td>
	    </tr>
		<tr>
          <td align="right" class="textoForm">Posi&ccedil;&atilde;o:</td>
		  <td><?=$Campos['Posicao']?></td>
	    </tr>		
		<tr>
          <td align="right" class="textoForm">Help:</td>
		  <td><?=$Campos['Help']?></td>
	    </tr>		
		<tr>
		<tr>
		  <td height="10" colspan="2"><hr /></td>
	    </tr>
		<tr>
		  <td height="30" colspan="2" bgcolor="#E4ECF7" class="textoFormInterno" style="padding-left:10px;">
		  <img src="../../figuras/bt_addfuncoes.gif" alt="Adicionar Op&ccedil;&otilde;es" width="107" height="14" border="0" onclick="moduloOpcoes('FormManu<?=$Id?>')" style="cursor:pointer"/>
		  <img src="../../figuras/bt_chamarform.gif" width="144" height="14" onclick="opcoesPadrao('FormManu<?=$Id?>')" style="cursor:pointer"/><span class="textoFormInterno" style="padding-left:10px;">
		  <img src="../../figuras/bt_preencherform_off.gif" width="158" height="14" onclick="preencherOpcoes('FormManu<?=$Id?>')" style="cursor:pointer" id="preencheForm"/>
		  </span></td>
	    </tr>
		<tr>
		  <td colspan="2">
		  <div id="opcoesModulo" style="padding-top:10px;">
<? /*		  </div>		  </td>
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
                Somente Dados <? } ?>
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
</fieldset><!--*/ ?>
