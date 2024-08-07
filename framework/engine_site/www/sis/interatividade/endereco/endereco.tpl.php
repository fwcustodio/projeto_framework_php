<fieldset id="endereco<?=$Cont?>" style="width:338px; margin:3px; border:2px solid #ced8e1; background:#e0e6ed; float:left; ">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
  <td height="40" colspan="2" align="left" class="textoForm">
  <input type="radio" name="PadraoEnd" id="PadraoEnd" value="<?=$Cont?>" <?=$PadraoChecado?> /> Definir como endere&ccedil;o Padr&atilde;o</td>
  </tr>
<tr>
  <td width="100" align="right" class="textoForm">Tipo de Endere&ccedil;o:</td>
  <td><?=$CamposEnd['EnderecoDadosTipoCod'.$Cont]?>
    <?=$CamposEnd['ArrayEndereco'.$Cont]?></td>
</tr>
<tr>
  <td align="right" class="textoForm">País:</td>
  <td><?=$CamposEnd['Pais'.$Cont]?></td>
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
  <td align="right" class="textoForm">Endere&ccedil;o:</td>
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
  <td align="right" class="textoForm">Mapa:</td>
  <td>
  <?=$CamposEnd['Mapa'.$Cont]?>&nbsp;<img id="infoHelp<?=$Cont?>" title="Como Adicionar um Mapa |
1°) Acesse http://maps.google.com.br;<br>
2°) Insira o endereço e clique em 'Pesquisar no Mapa';<br>
3°) No canto direito, superior ao mapa, clique em 'Criar link para esta página';<br>
4°) Copie o SEGUNDO link e cole-o no campo ao lado." src="<?=$_SESSION['UrlBase']?>figuras/ico_help.gif" align="absmiddle" border="0" />

</td>
</tr>
<tr>
  <td height="30" colspan="2" align="center" class="textoForm"><input name="DescartEnd" type="button" id="DescartEnd" onclick="removeEndereco('<?=$IdForm?>','<?=$Cont?>')" value="Descartar Este Endereco"/></td>
  </tr>
</table>
</fieldset>
<script language="javascript">
	$(document.body).ready(function() { 
	$('#infoHelp<?=$Cont?>').cluetip({cluetipClass: 'default',
	 splitTitle: '|',
	 showTitle: false,
	 positionBy: 'bottomTop'});
	 });
</script>