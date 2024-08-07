<fieldset id="fild<?=$Id?>" class="fildManu">
<legend class="legendaManu"><?=$Ac->opcaoExt($Op);?></legend>
  <form id="FormManu<?=$Id?>" name="FormManu<?=$Id?>" method="post" action="" onSubmit="return false" >
	  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td valign="top" class="classConteudo"><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td align="right" class="textoForm" id="conteudo4">Posi&ccedil;&atilde;o:</td>
              <td><?=$Campos['SecaoPosicao']?></td>
            </tr>
             
            <tr>
              <td width="150" height="25" align="right" class="textoForm">Grupo:</td>
              <td><?=$Campos['SecaoGrupoCod']?></td>
            </tr>
           
            <tr>
              <td height="25" align="right" class="textoForm">Se&ccedil;&atilde;o Pai:</td>
              <td><?=$Campos['SecaoPai']?></td>
            </tr>
            <tr>
              <td height="25" align="right" class="textoForm">Nome da Se&ccedil;&atilde;o/Link:</td>
              <td><?=$Campos['SecaoNome']?></td>
            </tr>
            
<!--            <tr>
              <td width="150" height="25" align="right" class="textoForm">Exibir em:</td>
              <td><?=$Campos['SecaoExibirEm']?></td>
            </tr>-->
            
            <tr id="trTipoInformacao">
              <td height="25" align="right" class="textoForm">Tipo de Informa&ccedil;&atilde;o:</td>
              <td class="textoForm"><?
									  if($Op == "Alt")
									  {
										$ChecadoCAlt = ($_POST['Tipo'] == 'C') ? 'checked="checked"' : '';
										$ChecadoLAlt = ($_POST['Tipo'] == 'L') ? 'checked="checked"' : '';
									  }
									  else
									  {
										$ChecadoCAlt = '';
										$ChecadoLAlt = '';
									  }
									  ?>
                  <label>
                  <input name="Tipo" type="radio" value="C"  <? if($Op == "Cad") { ?> checked="checked" <? } ?> <?=$ChecadoCAlt?> onclick="verificaTipo('<?=$Id?>')" />
                    Conte&uacute;do</label>
                <label>
                  <input type="radio" name="Tipo" value="L" onclick="verificaTipo('<?=$Id?>')" <?=$ChecadoLAlt?> />
                  Link</label></td>
            </tr>
            <tr id="conteudo9">
              <td colspan="2" class="textoForm"><?=$Campos['SecaoConteudo']?></td>
            </tr>
            <tr id="linkConteudo" style="display:none">
              <td height="30" align="right" class="textoForm">Link:</td>
              <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td><?=$Campos['LinkTipo'].$Campos['Link']?></td>
                  <td align="right" class="textoForm">Abrir:</td>
                  <td class="textoForm"><label></label><label>
                    <?=$Campos['LinkTarget']?>
                  </label></td>
                </tr>
              </table></td>
            </tr>
            <tr id="conteudo10" >
              <td colspan="2" class="textoForm"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr id="conteudo3">
                  <td width="85" align="right" class="textoForm">Autor/Fonte:</td>
                  <td><?=$Campos['AutorNome']?>
                      <?=$Campos['AutorCod']?></td>
                </tr>
              </table></td>
            </tr>
          </table></td>
          <td valign="top" class="classConfig"><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td align="center" class="classTitulo">Configura&ccedil;&otilde;es</td>
            </tr>
            <tr>
              <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr id="conteudo2">
                  <td align="right" class="textoForm">Exibir no Menu:</td>
                  <td class="textoForm">
				   <?
                  		$ExibirMS = (empty($_POST['ExibirMenu']) or $_POST['ExibirMenu'] == 'S') ? 'checked="checked"': '';
						$ExibirMN = ($_POST['ExibirMenu'] == 'N') ? 'checked="checked"': '';
				  ?>
                  
                  <label><input type="radio" name="ExibirMenu"  value="S" <?=$ExibirMS?> />Sim</label>
                  <label><input type="radio" name="ExibirMenu"  value="N" <?=$ExibirMN?>/>Não</label>                  </td>
                </tr>
                <tr id="conteudo">
                  <td align="right" class="textoForm">No Menu Mostrar Filhos:</td>
                  <td class="textoForm">
				  <?
                  		$MostrarFilhosS = ($_POST['MostrarFilhos'] == 'S') ? 'checked="checked"': '';
						$MostrarFilhosN = (empty($_POST['MostrarFilhos']) or $_POST['MostrarFilhos'] == 'N') ? 'checked="checked"': '';
				  ?>
                  
                  <label><input type="radio" name="MostrarFilhos"  value="S" <?=$MostrarFilhosS?> />Sim</label>
                  <label><input type="radio" name="MostrarFilhos"  value="N" <?=$MostrarFilhosN?>/>Não</label>                  </td>
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
                        N&atilde;o</label>                  </td>
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
                        Inativo</label>                  </td>
                </tr>
                
              </table></td>
            </tr>
            <tr id="conteudo15">
              <td align="center" class="classTitulo">Anexos</td>
            </tr>
            <tr>
              <td>
              <table width="100%" border="0" cellspacing="0" cellpadding="0" id="tabelaAnexo">

                <tr>
                  <td valign="top">
                  
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">

                      <tr>
                        <td height="25" align="center" valign="middle"><img src="figuras/bt_add_arquivo.gif" onclick="buscaArquivo('<?=$Id?>')" style="cursor:pointer" /></td>
                      </tr>
                      <tr>
                        <td><table width="100%" border="0" cellspacing="0" cellpadding="0" id="cTArquivo">
                            <?=$ConteudoArquivo?>
                        </table></td>
                      </tr>

                  </table>
                  
                  </td>
                </tr>
                
                <tr>
                  <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td height="25" align="center" valign="middle"><img src="figuras/bt_add_galeria.gif" onclick="buscaGaleriaMidia('<?=$Id?>')" style="cursor:pointer" /></td>
                      </tr>
                      <tr>
                        <td><table width="100%" border="0" cellspacing="0" cellpadding="0" id="cTGaleriaMidia">
                            <?=$ConteudoGaleriaMidia?>
                        </table></td>
                      </tr>
                  </table></td>
                </tr>
                
                <? /*
                <tr>
                  <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                       <td height="25" align="center" valign="middle"><img src="figuras/bt_add_enquete.gif" onclick="buscaEnquete('<?=$Id?>')" style="cursor:pointer" /></td>
                      
					  </tr>
                      <tr>
                        <td><table width="100%" border="0" cellspacing="0" cellpadding="0" id="cTEnquete">
                            <?=$ConteudoEnquete?>
                        </table></td>
                      </tr>
                  </table></td>
                </tr>
                */?>
                
              </table></td>
            </tr>
            <? 
			if($Op == "Alt") 
			{ 
			
			?>
            <tr>
              <td align="center" class="classTitulo">Revis&otilde;es</td>
            </tr>
            <tr>
              <td>
              <div id="divRevisoes">
              <?=$Secao->getRevisoes($Id);?>
              </div>
            </td>
            </tr>
            <? } ?>
          </table></td>
        </tr>
      </table>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">

		<? if($Op == "Cad") { ?>
		<tr style="display:none">
		  <td class="textoFormInterno"><input name="FormaCadastro" type="radio" value="Unico" checked="checked" />
              Somente Este 
              <input name="FormaCadastro" type="radio" value="Varios" />
              V&aacute;rios</td>
	    </tr>
		<? } ?>
		<tr>
		  <td>
		  <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td align="center" class="tdManuOpcoes">
			  <div class="manuOpcoes">
                <? if($Op == "Cad") { ?>
                <input type="button" name="Button" value="Cadastrar Se&ccedil;&atilde;o" onclick="if(validaForm()) cadastraBd()" />
                <? } elseif($Op == "Alt") { ?>
				<input name="Id" type="hidden" id="Id" value="<?=$Id?>" />
                <input type="button" name="Button" value="Alterar Se&ccedil;&atilde;o" onclick="if(validaForm<?=$Id?>()) alteraBd('FormManu<?=$Id?>','fild<?=$Id?>')" />
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