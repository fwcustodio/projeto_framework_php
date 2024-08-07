<script>
$("#FormManu<?=$Id?> #tabBox1 .b1").css("background-position","0 -32px");
$("#FormManu<?=$Id?> #tabBox1 .b2").css("background-position","0 -32px");
$("#FormManu<?=$Id?> #tabBox1 .tabConteudo").css("background-position","0 -32px");
</script>





<fieldset id="fild<?=$Id?>" class="fildManu">
<legend class="legendaManu"><?=$Ac->opcaoExt($Op);?></legend>
  <form id="FormManu<?=$Id?>" name="FormManu<?=$Id?>" method="post" action="" onSubmit="return false" >
	  <table width="100%" border="0" cellspacing="0" cellpadding="0">
		
		<tr>
		  <td>
		  <!--Inicia Abas-->

  		<div id="tabBox1" class="tabBox" onclick="manipulaAbasImagem('<?=$Id?>' ,1)">
            <b class="b1"></b>
            <b class="tabConteudo">Informações Básicas</b>
            <b class="b2"></b>
        </div>
        <div id="tabBox2" class="tabBox" onclick="manipulaAbasImagem('<?=$Id?>' ,2)">
            <b class="b1"></b>
            <b class="tabConteudo">Endereços</b>
            <b class="b2"></b>
        </div>
        <div id="tabBox3" class="tabBox" onclick="manipulaAbasImagem('<?=$Id?>' ,3)">
            <b class="b1"></b>
            <b class="tabConteudo">Contatos</b>
            <b class="b2"></b>
        </div>
        <div id="tabBox4" class="tabBox" onclick="manipulaAbasImagem('<?=$Id?>' ,4)">
            <b class="b1"></b>
            <b class="tabConteudo">Dados de Acesso</b>
            <b class="b2"></b>
        </div>
        <div id="tabBox5" class="tabBox" onclick="manipulaAbasImagem('<?=$Id?>' ,5)">
            <b class="b1"></b>
            <b class="tabConteudo">Permissões</b>
            <b class="b2"></b>
        </div>
          
		  <br style="clear:both" />
          
          
          
        <!-- Inicio da Box1 -->
        <div id="AbaConteudo1" class="bordaBox">
            <b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
            <!-- Conteudo da Box -->
            <div class="bordaConteudo">
                <div id="content">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td width="170" align="right" class="textoForm"> Nome do Usuário:</td>
                        <td><?=$Campos['UsuarioDadosNome']?></td>
                      </tr>
                      <tr>
                        <td align="right" class="textoForm">Data de Nascimento:</td>
                        <td><?=$Campos['UsuarioDadosNascimento']?></td>
                      </tr>
                      <tr>
                        <td align="right" class="textoForm">Situação:</td>
                        <td><?=$Campos['Status']?></td>
                      </tr>
                    </table>		 
                </div>
             </div>
             <!-- Fim do Conteudo da Box -->
             <b class="b4"></b><b class="b3"></b><b class="b2"></b><b class="b1"></b>
        </div>
        <!-- Fim da Box1 -->
        
        
		  
        <!-- Inicio da Box1 -->
        <div id="AbaConteudo2" class="bordaBox">
            <b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
            <!-- Conteudo da Box -->
            <div class="bordaConteudo">
                <div id="content">
		  	
                        <div class="cabecaAdd">
                         <a href="javascript:addEndereco('<?=$Id?>')" class="textoFormInterno"><img src="<?=$_SESSION['UrlBase']?>cadastros/endereco/figuras/bt_add_end.gif" border="0" /></a>
                        </div>
                        
                        <div id="conteinerEndereco"><?=$ConteudoEnd?></div>
                   	    <div style="clear:both"></div>
		  
                </div>
             </div>
             <!-- Fim do Conteudo da Box -->
             <b class="b4"></b><b class="b3"></b><b class="b2"></b><b class="b1"></b>
        </div>
        <!-- Fim da Box1 -->
        
        
        
        
        
        
        
        
        
        
        
        
		  
       <!-- Inicio da Box1 -->
        <div id="AbaConteudo3" class="bordaBox">
            <b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
            <!-- Conteudo da Box -->
            <div class="bordaConteudo">
                <div id="content">
                
                
		  	
                    <div class="cabecaAdd">
                    <a href="javascript:addContato('<?=$Id?>')" class="textoFormInterno"><img src="<?=$_SESSION['UrlBase']?>cadastros/contato/figuras/add_contato.gif" border="0" /></a>
                    </div>
                    
                    <div id="conteinerContato"><?=$ConteudoContato?></div>
                    <div style="clear:both"></div>
          
          
                </div>
             </div>
             <!-- Fim do Conteudo da Box -->
             <b class="b4"></b><b class="b3"></b><b class="b2"></b><b class="b1"></b>
        </div>
        <!-- Fim da Box1 -->
        
        
        
        
        
        
        
        
        
        
		  
       <!-- Inicio da Box1 -->
        <div id="AbaConteudo4" class="bordaBox">
            <b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
            <!-- Conteudo da Box -->
            <div class="bordaConteudo">
                <div id="content">
		 
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td width="170" align="right" class="textoForm"> Login:</td>
                        <td><?=$Campos['Login']?></td>
                      </tr>
                      <tr>
                        <td align="right" class="textoForm">Senha:</td>
                        <td><?=$Campos['Senha']?></td>
                      </tr>
                      <tr>
                        <td align="right" class="textoForm">Repita Senha:</td>
                        <td><?=$Campos['RepitaSenha']?></td>
                      </tr>
                      <tr>
                        <td align="right" class="textoForm">E-mai:</td>
                        <td><?=$Campos['Email']?></td>
                      </tr>
                    </table>		 
		 
                </div>
             </div>
             <!-- Fim do Conteudo da Box -->
             <b class="b4"></b><b class="b3"></b><b class="b2"></b><b class="b1"></b>
        </div>
        <!-- Fim da Box1 -->
        
        
        
        
        
        
        
       <!-- Inicio da Box1 -->
        <div id="AbaConteudo5" class="bordaBox">
            <b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
            <!-- Conteudo da Box -->
            <div class="bordaConteudo">
                <div id="content">
		  	
                    
                    <div id="conteinerPermissoes">
                    <?
                    $UP = new UsuariosPermissao();
                    
                    $UP->setIdForm($Id);
                    
					if($_SESSION['UserName'] != "root") {
						$UP->EscondeModulos = array(2, 3);
						//$UP->EscondeGrupos  = array(12);
					}
					
                    echo $UP->geraPermissoes();
                    ?> 		  
                    </div>
                  

                </div>
             </div>
             <!-- Fim do Conteudo da Box -->
             <b class="b4"></b><b class="b3"></b><b class="b2"></b><b class="b1"></b>
        </div>
        <!-- Fim da Box1 -->

		  </td>
	    </tr>
		<tr>
		  <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
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
              <td width="170" align="right" class="tdManuOpcoes"><span class="textoForm">
                <?=$Campos['Id']?>
              </span></td>
              <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td class="tdManuOpcoes"><div class="manuOpcoes">
                        <? if($Op == "Cad") { ?>
                        <input type="button" name="Button" value="Cadastrar" onclick="if(validaForm()) cadastraBd()" />
                        <? } elseif($Op == "Alt") { ?>
                        <input type="button" name="Button" value="Alterar" onclick="if(validaForm<?=$Id?>()) alteraBd('FormManu<?=$Id?>','fild<?=$Id?>')" />
                        <? } ?>
                        <input name="DescartUm" type="button" id="DescartUm" onclick="$('#fild<?=$Id?>').remove()" value="Descartar"/>
                        <? if($Op == "Alt") { ?>
                        <input name="FTodos" type="button" id="FTodos" onclick="$('#manu').html('')" value="Descartar Todos" />
                        <? } ?>
                    </div></td>
                  </tr>
              </table></td>
            </tr>
            
          </table></td>
	    </tr>
    </table>
  </form>
</fieldset>