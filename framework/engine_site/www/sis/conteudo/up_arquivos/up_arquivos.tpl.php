<? 
//Interface para cadastro

if($Op=="Cad") { 

?>
<fieldset id="fild<?=$Id?>" class="fildManu">
<legend class="legendaManu"><?=$Ac->opcaoExt($Op);?></legend>
  <form enctype="multipart/form-data" id="FormManu<?=$Id?>" name="FormManu<?=$Id?>" method="post" action="" onSubmit="return false" >
	  <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
			<td align="right" class="textoForm">Nome do Arquivo:</td>
		  <td><?=$Campos['ArquivoNome']?></td></tr><tr>
		<tr><td width="170" align="right" class="textoForm">Categoria:</td><td><?=$Campos['ArquivoCategoriaCod']?></td></tr>
		<tr><td width="170" align="right" class="textoForm">Data da Publicação:</td><td><?=$Campos['DataPublicacao']?></td></tr>
		<tr><td width="170" align="right" class="textoForm" valign="top">Descrição:</td><td><?=$Campos['ArquivoDescricao']?></td></tr>
		<tr>
		  <td align="right" class="textoForm">Arquivos:</td>
		  <td><?=$Campos['Arquivos']?></td>
	    </tr>
          
		<tr>
		  <td align="right" class="tdManuOpcoes"><span class="textoForm">
		    <span style="display:none"><input name="FormaCadastro" type="radio" value="Unico" checked="checked" /><input name="FormaCadastro" type="radio" value="Varios" /></span><?=$Campos['Id']?>
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
<? 
} 

//Inteface para alteração
elseif($Op == "Alt")
{
?>
<fieldset id="fild<?=$Id?>" style="padding:0;margin:0; width:450px; float:left; overflow:auto; margin:6px">
<form enctype="multipart/form-data" id="FormManu<?=$Id?>" name="FormManu<?=$Id?>" method="post" action="" onSubmit="return false" >
  <table width="100%" border="0" cellspacing="0" cellpadding="12" >
    <tr>
      <td align="center">
	  <?
		if($_POST['TipoArquivo'] == "F")
		{
			echo '<img src="'.$_SESSION['UrlBase'].'conteudo/arquivos/arquivos/'.$_POST['GaleriaMidiaCod'].'/fotos/tb/'.$Id.'.jpg"  border="0" style="margin:3px" >';
		}
		elseif ($_POST['TipoArquivo'] == "V")
		{
			echo '<img src="'.$_SESSION['UrlBase'].'figuras/icone_video.gif" border="0" style="margin:3px" >';
		}      
	  
	  ?></td>
    </tr>
    <tr>
      <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
			<td align="right" class="textoForm">Nome do Arquivo:</td>
		  <td><?=$Campos['ArquivoNome']?></td></tr><tr>
          <td align="right" class="textoForm">Galeria de arquivos:</td>
          <td><?=$Campos['ArquivoCategoriaCod']?></td>
        </tr>
        <tr>
          <td align="right" class="textoForm">Data:</td>
          <td><?=$Campos['DataPublicacao']?></td>
        </tr>
        
        <tr><td width="170" align="right" class="textoForm" valign="top">Descrição:</td><td><?=$Campos['ArquivoDescricao']?></td></tr>
      </table></td>
    </tr>
    <tr>
      <td align="center"><input type="button" name="Button" value="Alterar" onclick="if(validaForm<?=$Id?>()) alteraBd('FormManu<?=$Id?>','fild<?=$Id?>')" />
                    <input name="DescartUm" type="button" id="DescartUm" onclick="$('#fild<?=$Id?>').remove()" value="Descartar"/>
                    <input name="FTodos" type="button" id="FTodos" onclick="$('#manu').html('')" value="Descartar Todos" />
                    <span class="textoForm"><span class="tdManuOpcoes">
                    <?=$Campos['Id']?>
        </span></span>	</td>
    </tr>
  </table>
</form>
</fieldset>
<? } ?>