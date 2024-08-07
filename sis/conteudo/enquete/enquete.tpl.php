<fieldset id="fild<?=$Id?>" class="fildManu">
<legend class="legendaManu"><?=$Ac->opcaoExt($Op);?></legend>
  <form id="FormManu<?=$Id?>" name="FormManu<?=$Id?>" method="post" action="" onSubmit="return false" >
	  <table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr><td width="170" align="right" class="textoForm">Pergunta:</td><td><?=$Campos['QuantidadeVotoEnquete'].$Campos['EnquetePergunta']?></td></tr>
		<tr>
	    <td width="170" align="right" class="textoForm"> In&iacute;cio da Publica&ccedil;&atilde;o:</td><td class="textoForm"><?=$Campos['DataInicioPublicacao']?> ás <?=$Campos['HoraInicioPublicacao']?></td></tr>
		<tr>
		  <td width="170" align="right" class="textoForm">Fim da Publica&ccedil;&atilde;o:</td><td  class="textoForm"><?=$Campos['DataFimPublicacao']?> ás <?=$Campos['HoraFimPublicacao']?><input type="hidden" name="TipoPublicacao" id="TipoPublicacao" value="P" /></td></tr>
		<tr><td width="170" align="right" class="textoForm">Mostrar Numero de Votos:</td>
		  <td class="textoForm">
		  <?
                $MNVS = (empty($_POST['MostrarNumeroVotos']) or $_POST['MostrarNumeroVotos'] == 'S') ? 'checked="checked"': '';
                $MNVN = ($_POST['MostrarNumeroVotos'] == 'N') ? 'checked="checked"': '';
          ?>
              <label>
              <input type="radio" name="MostrarNumeroVotos"  value="S" <?=$MNVS?> />
                Sim</label>
              <label>
              <input type="radio" name="MostrarNumeroVotos"  value="N" <?=$MNVN?>/>
                N&atilde;o</label>
          </td>
		</tr>
		<tr><td width="170" align="right" class="textoForm">Mostrar Porcentagem:</td>
		  <td class="textoForm"><?
                $MPS = (empty($_POST['MostrarPorcentagem']) or $_POST['MostrarPorcentagem'] == 'S') ? 'checked="checked"': '';
                $MPN = ($_POST['MostrarPorcentagem'] == 'N') ? 'checked="checked"': '';
          ?>
              <label>
              <input type="radio" name="MostrarPorcentagem"  value="S" <?=$MPS?> />
                Sim</label>
              <label>
              <input type="radio" name="MostrarPorcentagem"  value="N" <?=$MPN?>/>
                N&atilde;o</label>          </td>
		</tr>
		<tr>
		  <td align="right" class="textoForm">Publicar:</td>
		  <td class="textoForm"><?
                $PubS = (empty($_POST['Publicar']) or $_POST['Publicar'] == 'S') ? 'checked="checked"': '';
                $PubN = ($_POST['Publicar'] == 'N') ? 'checked="checked"': '';
          ?>
              <label>
                <input type="radio" name="Publicar"  value="S" <?=$PubS?> />
                Sim</label>
              <label>
                <input type="radio" name="Publicar"  value="N" <?=$PubN?>/>
                N&atilde;o</label>          </td>
	    </tr>
		<tr>
          <td align="right" class="textoForm">Situa&ccedil;&atilde;o:</td>
		  <td class="textoForm"><?
                $SitS = (empty($_POST['Situacao']) or $_POST['Situacao'] == 'A') ? 'checked="checked"': '';
                $SitN = ($_POST['Situacao'] == 'I') ? 'checked="checked"': '';
          ?>
              <label>
                <input type="radio" name="Situacao"  value="A" <?=$SitS?> />
                Ativo</label>
              <label>
                <input type="radio" name="Situacao"  value="I" <?=$SitN?>/>
                Inativo</label>          </td>
	    </tr>
		

		<tr>
		  <td align="right" class="textoForm">&nbsp;</td>
		  <td class="textoFormInterno">
                    <span class="textoForm">
                      <? if(!empty($_POST['QuantidadeVotoEnquete'])){?>      
                              <img border="0" src="<?=$_SESSION['UrlBase']?>figuras/bt_add_resposta_off.gif" />
                      <? }else{?>
                              <img border="0" src="<?=$_SESSION['UrlBase']?>figuras/bt_add_resposta.gif" onclick="addDados('<?=$Id?>')" style="cursor:pointer" />
                      <? }?>
                    </span>
                  </td>
	    </tr>
		<tr>
		  <td align="right" valign="top" class="textoForm">&nbsp;</td>
		  <td class="textoFormInterno"><div id="conteiner_respostas"><?=$ConteudoRespostas?></div></td>
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