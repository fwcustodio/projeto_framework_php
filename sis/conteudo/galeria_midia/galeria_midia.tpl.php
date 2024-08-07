<fieldset id="fild<?=$Id?>" class="fildManu">
<legend class="legendaManu"><?=$Ac->opcaoExt($Op);?></legend>
  <form id="FormManu<?=$Id?>" name="FormManu<?=$Id?>" method="post" action="" onSubmit="return false" enctype="multipart/form-data" >
	  <table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
		  <td width="170" align="right" class="textoForm">Nome da Galeria:</td><td><?=$Campos['GaleriaNome']?></td></tr>
		<tr>
		  <td width="170" align="right" class="textoForm">Data de Cria&ccedil;&atilde;o:</td><td><?=$Campos['DataCriacao']?></td></tr>
		<tr>
		  <td align="right" class="textoForm">Galeria de Capa:</td>
		  <td class="textoForm"><?
                $CapaS = (empty($_POST['Capa']) or $_POST['Capa'] == 'S') ? 'checked="checked"': '';
                $CapaN = ($_POST['Capa'] == 'N') ? 'checked="checked"': '';
				
				$ExibeInicial = (empty($_POST['Capa']) or $_POST['Capa'] == 'S') ? 'S': 'N';
          ?>
          <script>exibeImg('<?=$ExibeInicial?>')</script>
		    <label>
		      <input type="radio" name="Capa"  value="S" <?=$CapaS?> onclick="exibeImg('S')" />Sim</label>
		    <label>
		      <input type="radio" name="Capa"  value="N" <?=$CapaN?> onclick="exibeImg('N')"/>N&atilde;o</label></td>
	    </tr>
		<tr>
		  <td align="right" class="textoForm">Imagem de Capa:</td>
		  <td class="textoForm">
          
          <div id="ImagemCapa" style="display:none">
              <br />
    
              <label>
                <input name="ImagemColuna" type="file" id="ImagemColuna" size="17" /> 
                <? if(($Op == "Alt") && ($_POST['Capa'] == 'S')) { ?><input name="Manter" type="checkbox" value="Ok" checked="checked" /> Manter Imagem<? } ?>
              </label>
              <br />
              (<strong>Extens&otilde;es suportadas:</strong> .gif / .jpg)
              <br />
              (<strong>Tamanho ideal:</strong> 446x200px)
              <br /><br />
		  </div>

          <div id="AlertaImagemCapa" style="display:none">
          <em><strong>Para cadastrar uma imagem de Capa, o campo "Galeria de Capa" deverá ser marcado como "Sim"</strong></em>
          </div>


</td>
	    </tr>
		<tr><td width="170" align="right" class="textoForm">Publicar:</td>
        <td class="textoForm">
		  <?
                $PubS = (empty($_POST['Publicar']) or $_POST['Publicar'] == 'S') ? 'checked="checked"': '';
                $PubN = ($_POST['Publicar'] == 'N') ? 'checked="checked"': '';
          ?>
          
          <label><input type="radio" name="Publicar"  value="S" <?=$PubS?> />Sim</label>
          <label><input type="radio" name="Publicar"  value="N" <?=$PubN?>/>Não</label>
         </td>
		</tr>
        <tr>
          <td width="170" align="right" class="textoForm">Situa&ccedil;&atilde;o:</td>
          <td class="textoForm">
		  <?
                $SitS = (empty($_POST['Situacao']) or $_POST['Situacao'] == 'A') ? 'checked="checked"': '';
                $SitN = ($_POST['Situacao'] == 'I') ? 'checked="checked"': '';
          ?>
          
          <label><input type="radio" name="Situacao"  value="A" <?=$SitS?> />Ativo</label>
          <label><input type="radio" name="Situacao"  value="I" <?=$SitN?>/>Inativo</label>
         </td>
		</tr>
		<? if($Op == "Cad") { ?>
		<tr>
		  <td align="right" class="textoForm">Cadastrar:</td>
		  <td class="textoFormInterno"><input name="FormaCadastro" type="radio" value="Unico" checked="checked" />Somente Este 
              <input name="FormaCadastro" type="radio" value="Varios" />V&aacute;rios</td>
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
                <input type="button" name="Button2" value="Cadastrar" onclick="if(validaForm()) bdCadastraAltera('<?=MODULO?>.ajax.php?Op=Cad&Env=true', retornoCadastrar, cadastraBd)" />
                <? } elseif($Op == "Alt") { ?>
                <input name="Id" type="hidden" id="Id" value="<?=$Id?>" />
                <input type="button" name="Button" value="Alterar" onclick="if(validaForm<?=$Id?>()) bdCadastraAltera('<?=MODULO?>.ajax.php?Op=Alt&Env=true', retornoAlterar, alteraBd, <?=$Id?>)" />
                <? } ?>                <input name="DescartUm" type="button" id="DescartUm" onclick="$('#fild<?=$Id?>').remove()" value="Descartar"/>
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