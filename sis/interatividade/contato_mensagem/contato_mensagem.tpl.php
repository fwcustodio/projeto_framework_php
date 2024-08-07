<fieldset id="fild<?=$Id?>" class="fildManu">
<legend class="legendaManu"><?=$Ac->opcaoExt($Op);?></legend>
  <form id="FormManu<?=$Id?>" name="FormManu<?=$Id?>" method="post" action="" onSubmit="return false" >
	  <table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr><td width="170" align="right" class="textoForm">Departamento:</td><td><?=$Campos['ContatoDepartamentoCod']?></td></tr>
		<tr><td width="170" align="right" class="textoForm">Assunto:</td><td><?=$Campos['AssuntoCod']?></td></tr>
		<tr><td width="170" align="right" class="textoForm">Status:</td><td><?=$Campos['Status']?></td></tr>
        <? 
		$Con = Conexao::conectar();
		$Dados = $Con->execLinha($Msg->geraMensagemEmailSql());
		?>
		<tr>
		  <td align="right" class="textoForm"></td>
		  <td><?
				echo "<br><span class='textoForm'><b>Código da Mensagem:</b> ".$Dados['ContatoMensagemCod']."<br>
					  <b>Departamento:</b> ".$Dados['Departamento']."<br>
					  <b>Nome:</b> ".$Dados['Nome']."<br>
					  <b>E-mail:</b> ".$Dados['Email']."<br>
					  <b>Telefone:</b> ".$Dados['Telefone']."<br>
					  <b>País:</b> ".$Dados['Pais']."<br>
					  <b>Cidade:</b> ".$Dados['Cidade']." / <b>UF:</b> ".$Dados['UF']."<br>
					  <b>Mensagem:</b> ".$Dados['Mensagem']."<br><br>
					  ------------------------------------------------------------------------------------------------------<br>
					  A mensagem foi enviada através formulário de contato em <b>".$Dados['Criacao']."</b>.<br><br></span>";
		  
		  ?></td>
	    </tr>
       
		<tr>
		  <td align="right" class="textoForm">Observações:</td>
		  <td><?=$Campos['Observacoes']?></td>
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