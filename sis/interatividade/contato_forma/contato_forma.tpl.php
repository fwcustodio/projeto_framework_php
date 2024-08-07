<fieldset id="fild<?=$Id?>" class="fildManu">
<legend class="legendaManu"><?=$Ac->opcaoExt($Op);?></legend>
  <form id="FormManu<?=$Id?>" name="FormManu<?=$Id?>" method="post" action="" onSubmit="return false" >
	  <table width="100%" border="0" cellspacing="0" cellpadding="0">
      
        <tr>
          <td align="right" class="textoForm">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
        		<tr>
		  <td width="170" align="right" class="textoForm">Titulo:</td>
		  <td align="left"><?=$Campos['Titulo']?></td>
	    </tr>
		<tr>
		  <td width="170" align="right" class="textoForm">Descri&ccedil;&atilde;o:</td>
		  <td align="left"><?=$Campos['Descricao']?></td>
	    </tr>
        </table>
        
        </td>
        </tr>		
     
        <tr><td align="right" class="textoForm"><hr style=" border:1px solid #ced8e1; background:#e0e6ed;" /></td>
        </tr>		
        <tr><td align="left" class="textoForm"><a href="javascript:addContato('<?=$Id?>')" class="textoFormInterno"><img src="<?=$_SESSION['UrlBase']?>figuras/add_contato.gif" border="0" /></a></td>
        </tr>
        <tr>
          <td colspan="2" align="left" class="textoForm">
          <div id="conteinerContato"><?=$ConteudoContato?></div>
          </td>
        </tr>        
		<tr><td height="20" colspan="2" align="right" class="textoForm"><hr style=" border:1px solid #ced8e1; background:#e0e6ed;" /></td>
        </tr>
        <tr><td align="left" class="textoForm"><a href="javascript:addEndereco('<?=$Id?>')" class="textoFormInterno"><img src="<?=$_SESSION['UrlBase']?>figuras/bt_add_end.gif" border="0" /></a></td>
        </tr>
        <tr><td  align="left" class="textoForm"><div id="conteinerEndereco"><?=$ConteudoEnd?></div></td>
        </tr>

		<? if($Op == "Cad") { ?>
        
        <tr><td  align="left" class="textoForm">
        
 <table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
		  <td align="right" class="textoForm">Cadastrar:</td>
		  <td class="textoFormInterno"><input name="FormaCadastro" type="radio" value="Unico" checked="checked" />
              Somente Este 
              <input name="FormaCadastro" type="radio" value="Varios" />
              V&aacute;rios</td>
	    </tr>
</table>
        </td>
        </tr>
        
        

		<? } ?>
		<tr><td height="20" colspan="2" align="right" class="textoForm"><hr style=" border:1px solid #ced8e1; background:#e0e6ed;" /></td>
        </tr>
		<tr>

		  <td>
		  <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td class="tdManuOpcoes">		  <span class="textoForm">
		    <?=$Campos['Id']?>
		  </span>
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