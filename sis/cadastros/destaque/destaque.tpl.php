<fieldset id="fild<?=$Id?>" class="fildManu">
<legend class="legendaManu"><?=$Ac->opcaoExt($Op);?></legend>
  <form enctype="multipart/form-data" id="FormManu<?=$Id?>" name="FormManu<?=$Id?>" method="post" action="" onSubmit="return false" >  <!-- PARA MÓDULOS COM UPLAOD COLOCAR: enctype="multipart/form-data" NO TAG DO FORM -->
	  <table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr><td width="170" align="right" class="textoForm">Titulo:</td><td><?=$Campos['DestaqueTitulo']?></td></tr>
        <tr><td width="170" align="right" class="textoForm">Prioridade:</td><td><?=$Campos['DestaquePrioridade']?></td></tr>
		<tr><td width="170" align="right" class="textoForm">Descrição:</td><td><?=$Campos['DestaqueDescricao']?></td></tr>
		<tr>
		  <td align="right" class="textoForm">&nbsp;</td>
		  <td>&nbsp;</td>
	    </tr>
           <tr><td width="170" align="right" class="textoForm">Link:</td><td><?=$Campos['DestaqueTipo']?> <?=$Campos['DestaqueLink']?></td></tr>
          <?php /*?> <tr><td width="170" align="right" class="textoForm">Obra:</td><td><?=$Campos['PortifolioCod']?></tr>
<?php */?>

            <tr>
		  <td align="right" class="textoForm">Destino:</td>
		  <td><?=$Campos['DestaqueLinkTarget']?></td>
	    </tr>
           <tr>
		  <td align="right" class="textoForm">&nbsp;</td>
		  <td>&nbsp;</td>
	    </tr>
		<tr>
          <td width="170" align="right" bgcolor="#E7ECF1" class="textoForm">Imagem:</td><td bgcolor="#E7ECF1">
		
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="100"><?=$Campos['DestaqueImagem']?></td>
            <td width="90"><? if($Op == "Alt") { ?><span class="textoForm">
		<input type="checkbox" name="Manter" id="Manter" checked="checked" value="Ok" />Manter</span></td>
            <td><img src="<?=$_SESSION['UrlBaseSite']?>arquivos/destaque/<?=$_POST['DestaqueImagem']?>?Cache=<?=date('d-m-Y-h-m-s').mt_rand(0,59889)?>" border="0"  width="100px" height="100px"/><? } ?></td>
          </tr>
		 </table>
		</td></tr>
        
        
		<tr>
		  <td align="right" bgcolor="#E7ECF1" class="textoForm">&nbsp;</td>
		  <td bgcolor="#E7ECF1" class="textoFormInterno">
          
 (<strong>Extens&otilde;es suportadas:</strong> .gif / .jpg)
 <br />
 (<strong>Tamanho ideal:</strong> 988x310px)</td>
	    </tr>
        


		<tr>
		  <td align="right" class="textoForm">&nbsp;</td>
		  <td class="textoFormInterno">&nbsp;</td>
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
                <input type="button" name="Button" value="Cadastrar" onclick="if(validaForm()) bdCadastraAltera('<?=MODULO?>.ajax.php?Op=Cad&Env=true', retornoCadastrar, cadastraBd)" />
                <? } elseif($Op == "Alt") { ?>
				<input name="Id" type="hidden" id="Id" value="<?=$Id?>" />
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