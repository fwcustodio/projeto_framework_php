<? 
//Interface para cadastro

if($Op=="Cad") { 

?>
<fieldset id="fild<?=$Id?>" class="fildManu">
<legend class="legendaManu"><?=$Ac->opcaoExt($Op);?></legend>
  <form enctype="multipart/form-data" id="FormManu<?=$Id?>" name="FormManu<?=$Id?>" method="post" action="" onSubmit="return false" >
	  <table width="100%" border="0" cellspacing="0" cellpadding="0">
      	<tr><td width="170" align="right" class="textoForm">Galeria:</td><td><?=$Campos['Id']?><?=$Campos['GaleriaMidiaCod']?></td></tr>
		<tr>
		  <td width="170" align="right" class="textoForm">Autor/Cr&eacute;ditos:</td><td><?=$Campos['AutorCod']?>
		    <?=$Campos['AutorNome']?></td>
		</tr>
		<tr><td width="170" align="right" class="textoForm">Legenda:</td><td><?=$Campos['Legenda']?></td></tr>
		<tr><td width="170" align="right" class="textoForm">Data dos Arquivos:</td><td><?=$Campos['DataPublicacao']?></td></tr>
		<tr>
		  <td align="right" class="textoForm">&nbsp;</td>
		  <td><i class="textoForm" style="font-size:11px">Tamanho Máximo para imagens 2mb<br />
          		 Tamanho Máximo para videos e arquivos 8mb
                 </i></td>
	    </tr>
        <tr>
		  <td align="right" class="textoForm">&nbsp;</td>
		  <td>&nbsp;</td>
	    </tr>
        
		<tr>
		  <td align="right" class="textoForm">&nbsp;</td>
		  <td>

          <div id="ExibeMultiploUploar" style="display:none">
            <span id="enviarArquivos" class="modal"><img src="<?=$_SESSION['UrlBase']?>figuras/bt_enviar_arquivos_on.gif" border="0" /></span>
          </div>
          
          <div id="ValidaMultiploUploar">
          	<img src="<?=$_SESSION['UrlBase']?>figuras/bt_enviar_arquivos_off.gif" border="0" />
          </div>
          </td>
	    </tr>

		
		<tr>
          <td align="right" class="tdManuOpcoes">&nbsp;</td>
		  <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td class="tdManuOpcoes"><div class="manuOpcoes">
                    <? if($Op == "Alt") { ?>
                    <input name="Id" type="hidden" id="Id" value="<?=$Id?>" />
                    <input type="button" name="Button2" value="Alterar" onclick="if(validaForm<?=$Id?>()) alteraBd('FormManu<?=$Id?>','fild<?=$Id?>')" />
                    <? } ?>
                    <? if($Op == "Alt") { ?>
                    <input name="DescartUm2" type="button" id="DescartUm2" onclick="$('#fild<?=$Id?>').remove()" value="Descartar"/>
                    <input name="FTodos2" type="button" id="FTodos2" onclick="$('#manu').html('')" value="Descartar Todos" />
                    <? } ?>
                </div></td>
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
<fieldset id="fild<?=$Id?>" style="padding:0;margin:0; width:430px; float:left; overflow:auto; margin:6px; padding:5px; border:1px solid #9fadcf">
<form id="FormManu<?=$Id?>" name="FormManu<?=$Id?>" method="post" action="" onSubmit="return false" >
  
  
<table width="100%" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td width="130px" height="120px"><?
		if($_POST['TipoArquivo'] == "F")
		{

			echo '<img src="'.$_SESSION['UrlBaseSite'].'arquivos/multimidia/'.$_POST['GaleriaMidiaCod'].'/fotos/tb/'.$Id.'.'.$_POST['Extensao'].'"  border="0" style="margin:3px" >';
		}
		elseif ($_POST['TipoArquivo'] == "V")
		{
			echo '<img src="'.$_SESSION['UrlBase'].'figuras/icone_video.gif" border="0" style="margin:3px" >';
		}
		elseif ($_POST['TipoArquivo'] == "A")
		{
			echo '<img src="'.$_SESSION['UrlBase'].'figuras/icone_audio.gif" border="0" style="margin:3px" >';
		}      

	  ?></td>
    <td>
    
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="170" align="right" class="textoForm">Autor/Cr&eacute;ditos:</td><td><?=$Campos['AutorCod']?>
                <?=$Campos['AutorNome']?></td>
            </tr>
            <tr><td width="60" align="right" class="textoForm">Legenda:</td><td><?=$Campos['Legenda']?></td></tr>
            <tr><td width="60" align="right" class="textoForm">Data:</td><td><?=$Campos['DataPublicacao']?></td></tr>
        </table>

    
    </td>
  </tr>
  <tr>
  <td></td>
  <td><div style="padding-top:10px">
      <input type="button" name="Button" value="Alterar" onclick="if(validaForm<?=$Id?>()) alteraBd('FormManu<?=$Id?>','fild<?=$Id?>')" />
                    <input name="DescartUm" type="button" id="DescartUm" onclick="$('#fild<?=$Id?>').remove()" value="Descartar"/>
                    <input name="FTodos" type="button" id="FTodos" onclick="$('#manu').html('')" value="Descartar Todos" />
                    <span class="textoForm"><span class="tdManuOpcoes">
                    <?=$Campos['Id']?>
        </span></span>	
        </div></td>
  </tr>
  
</table>
</form>
</fieldset>
<? } ?>