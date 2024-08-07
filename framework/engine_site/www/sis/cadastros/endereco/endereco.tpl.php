<fieldset id="endereco<?=$Cont?>" style="width:270px;  border:2px solid #ced8e1; background:#e0e6ed; float:left;">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
  <td align="right" class="textoForm">Tipo de Endere&ccedil;o:</td>
  <td><?=$CamposEnd['EnderecoDadosTipoCod'.$Cont]?>
    <?=$CamposEnd['ArrayEndereco'.$Cont]?></td>
</tr>
<tr>
  <td align="right" class="textoForm">Estado:</td>
  <td><?=$CamposEnd['NEndereco'.$Cont].$CamposEnd['Estado'.$Cont]?></td>
</tr>
<tr>
  <td align="right" class="textoForm">Cidade:</td>
  <td><?=$CamposEnd['Cidade'.$Cont]?></td>
</tr>
<tr>
  <td align="right" class="textoForm">Logradouro:</td>
  <td><?=$CamposEnd['Rua'.$Cont]?></td>
</tr>
<tr>
  <td align="right" class="textoForm">N&uacute;mero:</td>
  <td><?=$CamposEnd['Numero'.$Cont]?></td>
</tr>
<tr>
  <td align="right" class="textoForm">Bairro:</td>
  <td><?=$CamposEnd['Bairro'.$Cont]?></td>
</tr>
<tr>
  <td align="right" class="textoForm">Cep:</td>
  <td><?=$CamposEnd['CEP'.$Cont]?></td>
</tr>
<tr>
  <td align="right" class="textoForm">Complemento:</td>
  <td><?=$CamposEnd['Complemento'.$Cont]?></td>
</tr>
<tr>
  <td height="30" colspan="2" align="right" class="textoForm"><input name="DescartEnd" type="button" id="DescartEnd" onclick="removeEndereco('<?=$IdForm?>','<?=$Cont?>')" value="Descartar Este Endereco"/></td>
  </tr>
</table>
</fieldset>