<div id="opcoes<?=$Cod?>">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
	  <td align="right" class="textoForm">Permiss&atilde;o:</td>
	  <td><?=$Campos['NomePermissao'.$Cod]?></td>
	  <td align="right" class="textoForm">ID Permiss&atilde;o: </td>
	  <td><?=$Campos['IdPermissao'.$Cod]?></td>
	  <td align="right" class="textoForm">Fun&ccedil;&atilde;o:</td>
	  <td><?=$Campos['Funcao'.$Cod]?></td>
	</tr>
	<tr>
	  <td align="right" class="textoForm">Imagem ON: </td>
	  <td><?=$Campos['ImagemOn'.$Cod]?></td>
	  <td align="right" class="textoForm">Imagem OFF: </td>
	  <td><?=$Campos['ImagemOff'.$Cod]?></td>
	  <td align="right" class="textoForm">Precisa ID?</td>
	  <td ><?=$Campos['PrecisaId'.$Cod]?></td>
	</tr>
	<tr>
	  <td align="right" class="textoForm">Com Permiss&atilde;o: </td>
	  <td><?=$Campos['AltP'.$Cod]?></td>
	  <td align="right" class="textoForm">Sem Permiss&atilde;o: </td>
	  <td><?=$Campos['AltNP'.$Cod]?></td>
	  <td align="right" class="textoForm">Posi&ccedil;&atilde;o:</td>
	  <td><?=$Campos['Pos'.$Cod]?></td>
	</tr>
	<tr>
	  <td align="right" class="textoForm">&nbsp;</td>
	  <td>&nbsp;</td>
	  <td align="right" class="textoForm">&nbsp;</td>
	  <td>&nbsp;</td>
	  <td align="right" class="textoForm"><input name="OpcoesModulo[<?=$Cod?>]" type="hidden" id="OpcoesModulo[<?=$Cod?>]" value="<?=$Cod?>" /></td>
	  <td height="30"><span class="manuOpcoes">
	    <input name="DescartOpUm" type="button" id="DescartOpUm" onclick="$('#opcoes<?=$Cod?>').remove()" value="Descartar"/>
	  </span></td>
    </tr>
	<tr>
	  <td height="15" colspan="6" align="right" class="textoForm"><hr /></td>
    </tr>
  </table>
</div>