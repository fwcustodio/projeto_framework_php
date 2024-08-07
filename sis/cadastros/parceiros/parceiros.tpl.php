<fieldset id="fild<?=$Id?>" class="fildManu">
<legend class="legendaManu"><?=$Ac->opcaoExt($Op);?></legend>
  <form id="FormManu<?=$Id?>" name="FormManu<?=$Id?>" method="post" action="" onSubmit="return false" enctype="multipart/form-data" >  <!-- PARA MÓDULOS COM UPLAOD COLOCAR:  NO TAG DO FORM -->
	  <table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr><td width="170" align="right" class="textoForm">Nome:</td><td><?=$Campos['ParceirosNome']?></td></tr>
		<tr><td width="170" align="right" class="textoForm">Comentário:</td><td><?=$Campos['ParceirosComentario']?></td></tr>
		<tr>
		  <td align="right" class="textoForm">Imagem <strong>(100x90px)</strong>: </td>
		  <td><table width="100%" border="0" cellspacing="2" cellpadding="2" style="margin-top:10px; margin-bottom:10px;" bgcolor="#d5e3f4">
		    <tr>
		      <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
		        <tr>
		          <td width="250"><span style="font-family:Arial, Helvetica, sans-serif; font-size:13px; margin-top:5px; margin-bottom:5px">
		            <?=$Campos['Imagens']?>
		            </span></td>
		          <td><? if($Op == "Alt" && !empty($_POST['ParceirosArquivo'])) { ?>
		            <span style="font-family:Arial, Helvetica, sans-serif; font-size:13px; margin-top:5px; margin-bottom:5px">
		              <input type="checkbox" id="Manter" name="Manter" value="S" checked="checked" />
		              Desejo manter o arquivo atual.</span>
		            <? } ?></td>
		          </tr>
		        </table></td>
	        </tr>
            <? if($Op == "Alt" && !empty($_POST['ParceirosArquivo'])) { ?>
		    <tr>
		      <td height="1"><?=$ArquivoExib?></td>
	        </tr>
            <? } ?>
		    </table></td>
	    </tr>
		<tr><td width="170" align="right" class="textoForm">Link:</td><td><?=$Campos['ParceirosTipo']?> <?=$Campos['ParceirosLink']?></td></tr>
		<tr><td width="170" align="right" class="textoForm">Situação:</td><td><?=$Campos['ParceirosSituacao']?></td></tr>

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
                <!--<input type="button" name="Button" value="Cadastrar" onclick="if(validaForm()) cadastraBd()" />-->
                <input type="button" name="Button" value="Cadastrar" onclick="if(validaForm()) bdCadastraAltera('<?=MODULO?>.ajax.php?Op=Cad&Env=true', retornoCadastrar, cadastraBd)" />
                
                <? } elseif($Op == "Alt") { ?>
                <input name="Id" type="hidden" id="Id" value="<?=$Id?>" />
                <!--<input type="button" name="Button" value="Alterar" onclick="if(validaForm<?=$Id?>()) alteraBd('FormManu<?=$Id?>','fild<?=$Id?>')" />-->
                <input type="button" name="Button" value="Alterar" onclick="if(validaForm<?=$Id?>()) bdCadastraAltera('<?=MODULO?>.ajax.php?Op=Alt&Env=true', retornoAlterar, alteraBd, <?=$Id?>)" />
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
