<fieldset id="fild<?=$Id?>" class="fildManu">
<legend class="legendaManu"><?=$Ac->opcaoExt($Op);?></legend>
  
<form enctype="multipart/form-data" id="FormManu<?=$Id?>" name="FormManu<?=$Id?>" method="post" action="" onSubmit="return false" >
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td align="left" valign="top" class="classConteudo" bgcolor="#E6F2FF"><table width="100%" border="0" cellspacing="0" cellpadding="0">

          <tr>
          <td width="172" align="right" class="textoForm">Posição:</td>
          <td><?=$Campos['ServicoPosicao']?></td>
        </tr>
	 <tr>
          <td width="172" align="right" class="textoForm">Categoria:</td>
          <td><?=$Campos['ServicoCategoriaCod']?></td>
        </tr>
        <tr>
          <td width="172" align="right" class="textoForm">Titulo:</td>
          <td><?=$Campos['ServicoNome']?></td>
        </tr>
      <?php /*?>  <tr>
          <td width="172" align="right" class="textoForm">Resumo:</td>
          <td><?=$Campos['ServicoResumo']?></td>
        </tr>
        <tr><?php */?>
          <td colspan="2" align="right" class="textoForm"><?=$Campos['ServicoDescricao']?></td>
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
          <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td class="tdManuOpcoes"> <div class="manuOpcoes">
                <? if($Op == "Cad") { ?>
                <input type="button" name="Button" value="Cadastrar" onclick="if(validaForm()) bdCadastraAltera('<?=MODULO?>.ajax.php?Op=Cad&amp;Env=true', retornoCadastrar, cadastraBd)" />
                <? } elseif($Op == "Alt") { ?>
                <input name="Id" type="hidden" id="Id" value="<?=$Id?>" />
                <input type="button" name="Button" value="Alterar" onclick="if(validaForm<?=$Id?>()) bdCadastraAltera('<?=MODULO?>.ajax.php?Op=Alt&amp;Env=true', retornoAlterar, alteraBd, <?=$Id?>)" />
                <? } ?>
                <input name="DescartUm" type="button" id="DescartUm" onclick="$('#fild<?=$Id?>').remove()" value="Descartar"/>
                <? if($Op == "Alt") { ?>
                <input name="FTodos" type="button" id="FTodos" onclick="$('#manu').html('')" value="Descartar Todos" />
                <? } ?>
              </div>	</td>
            </tr>
          </table></td>
        </tr>
      </table></td>
      <td width="300" align="right" valign="top" bgcolor="#E6F2FF" class="classConfig"><table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td class="classTitulo" align="center">Configura&ccedil;&atilde;o</td>
        </tr>
        <tr>
          <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
                    <td align="right" class="textoForm">Publicar:</td>
                    <td align="left" class="textoForm"><?
                  		$PubS = (empty($_POST['ServicoPublicar']) or $_POST['ServicoPublicar'] == 'S') ? 'checked="checked"': '';
						$PubN = ($_POST['ServicoPublicar'] == 'N') ? 'checked="checked"': '';
				  ?>
                        <label>
                        <input type="radio" name="ServicoPublicar"  value="S" <?=$PubS?> />
                          Sim</label>
                        <label>
                        <input type="radio" name="ServicoPublicar"  value="N" <?=$PubN?>/>
                          N&atilde;o</label>                    </td>
                  </tr>
                  <tr>
                    <td align="right" class="textoForm">Situa&ccedil;&atilde;o:</td>
                    <td align="left" class="textoForm"><?
                $SitS = (empty($_POST['ServicoSituacao']) or $_POST['ServicoSituacao'] == 'A') ? 'checked="checked"': '';
                $SitN = ($_POST['ServicoSituacao'] == 'I') ? 'checked="checked"': '';
          ?>
                        <label>
                        <input type="radio" name="ServicoSituacao"  value="A" <?=$SitS?> />
                          Ativo</label>
                        <label>
                        <input type="radio" name="ServicoSituacao"  value="I" <?=$SitN?>/>
                          Inativo</label>                    </td>
                  </tr>
                  
                  
                   <?php /*?><tr>
                    <td align="right" class="textoForm">Exibir na P&aacute;gina Inicial?</td>
                    <td align="left" class="textoForm"><?
                $IniN = (empty($_POST['ServicoMenu']) or $_POST['ServicoMenu'] == 'N') ? 'checked="checked"': '';
                $IniS = ($_POST['ServicoMenu'] == 'S') ? 'checked="checked"': '';
          ?>
                        <label>
                        <input type="radio" name="ServicoMenu"  value="S" <?=$IniS?> />
                          Sim</label>
                        <label>
                        <input type="radio" name="ServicoMenu"  value="N" <?=$IniN?>/>
                          Não</label>                    </td>
                  </tr><?php */?>
                  
                  <?php /*?> <tr>
                    <td align="right" class="textoForm">Prioridade:</td>
                    <td align="left" class="textoForm"><?=$Campos['ServicoPrioridade']?></td>
                  </tr>
                  <?php */?>
                  
                  
              </table></td>
        </tr>
           
           

        
        <tr>
          <td> 



		  </td>
        </tr>

           
           
   
          
       
        <tr>
          <td class="classTitulo" align="center">Anexos</td>
        </tr>
        <tr>
          <td>
          
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td height="25" align="center" valign="middle"><img src="../../figuras/bt_add_arquivo.gif" onclick="buscaArquivo('<?=$Id?>')" style="cursor:pointer" /></td>
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
          <td align="center"><img src="../../figuras/bt_add_galeria.gif" onclick="buscaGaleriaMidia('<?=$Id?>')" style="cursor:pointer" /></td>
        </tr>
        <tr>
          <td><table width="100%" border="0" cellspacing="0" cellpadding="0" id="cTGaleriaMidia">
            <?=$ConteudoGaleriaMidia?>
          </table></td>
        </tr>
        <tr>
          <td align="center" class="classTitulo">Imagem de Capa do Servi&ccedil;o</td>
        </tr>
        <tr id="conteudo15">
          <td align="center" class="classTituloDescricao"><div style="text-align:justify"> <em>A imagem ser&aacute; inserida para capa do (Principal) do Serviço.</em><br />
            <br />
           
            <strong>- Capa:</strong> (*.jpg *.gif) (610x310px) </div></td>
        </tr>
        <tr>
          <td align="center"><br />
            <table width="98%" border="0" align="center" cellpadding="0" cellspacing="1">
              <tr>
                <td align="center"><div class="campoUpload">
                  <?=$Campos['ImagemServico']?>
                  </div></td>
              </tr>
              <? if($Op == 'Alt') { 
				  $RsFoto = $Serv->geraFotoVis($Id);
				  if(!empty($RsFoto)) {
			  ?>
              <tr>
                <td><table width="255" border="0" align="center" cellpadding="0" cellspacing="0">
                  <tr>
                    <td width="130" align="center"><?=$RsFoto?></td>
                    <td><span class="textoForm">
                      <label>
                        <input name="Manter" type="checkbox" id="Manter" value="OK" checked="checked" />
                        Manter imagem.</label>
                    </span></td>
                  </tr>
                </table></td>
              </tr>
              <? } } ?>
            </table></td>
        </tr>
         <tr>
          <td align="center" class="classTitulo">Imagem Homepage</td>
        </tr>
        <tr id="conteudo15">
          <td align="center" class="classTituloDescricao"><div style="text-align:justify"> <em>A imagem ser&aacute; inserida na página inicial.</em><br />
            <br />
           
            <strong>- Imagem:</strong> (*.jpg *.gif) (100x190px) </div></td>
        </tr>
        
        
         <tr>
          <td align="center"><br />
            <table width="98%" border="0" align="center" cellpadding="0" cellspacing="1">
              <tr>
                <td align="center"><div class="campoUpload">
                  <?=$Campos['ImagemServicoHomepage']?>
                  </div></td>
              </tr>
              <? if($Op == 'Alt') { 
			  
				  $RsFoto2 = $Serv->geraFotoVisHome($Id);
				
				  
				  if(!empty($RsFoto2)) {
			  ?>
             
              <tr>
                <td><table width="255" border="0" align="center" cellpadding="0" cellspacing="0">
                  <tr>
                    <td width="130" align="center"><?=$RsFoto2?></td>
                    <td><span class="textoForm">
                      <label>
                        <input name="ManterHome" type="checkbox" id="ManterHome" value="OK" checked="checked" />
                        Manter imagem.</label>
                    </span></td>
                  </tr>
                </table></td>
              </tr>
              <? } } ?>
            </table></td>
        </tr>
        
        
        
        
        </table>
        </td>
    </tr>
  </table>
</form >
</fieldset>