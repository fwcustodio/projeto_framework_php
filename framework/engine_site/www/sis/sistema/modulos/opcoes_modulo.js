var OPCAOMODULO = 0;
var OPCOES = false;

function retornoOpcoes(form, req)
{
	OPCAOMODULO +=1;
	$('#'+form+' #opcoesModulo').append(req);
}

function opcoesPadrao(form)
{
	$('#'+form+' #preencheForm').attr('src','../../figuras/bt_preencherform.gif');
	OPCOES = true;
	
	$('#'+form+' #opcoesModulo').empty();
	
	OPCAOMODULO = 0;
	moduloOpcoes(form);
	OPCAOMODULO = 1;
	moduloOpcoes(form);
	OPCAOMODULO = 2;
	moduloOpcoes(form);
	OPCAOMODULO = 3;
	moduloOpcoes(form);
	OPCAOMODULO = 4;
	moduloOpcoes(form);
}

function preencherOpcoes(form)
{
	if(OPCOES == false)
	{
		alert('Primeiro chame o formulário padrão!');
		return false;
	}
	
	$('#'+form+' #NomePermissao0').val("Cadastrar");
	$('#'+form+' #IdPermissao0').val("Cad");
	$('#'+form+' #Funcao0').val("sis_cadastrar()");
	$('#'+form+' #ImagemOn0').val("bullet_adicionar.gif");
	$('#'+form+' #ImagemOff0').val("cad_off.gif");
	$('#'+form+' #PrecisaId0').val("N");
	$('#'+form+' #AltP0').val("Cadastrar");
	$('#'+form+' #AltNP0').val("Sem permissão para cadastrar");
	$('#'+form+' #Pos0').val("0");	
	
	$('#'+form+' #NomePermissao1').val("Alterar");
	$('#'+form+' #IdPermissao1').val("Alt");
	$('#'+form+' #Funcao1').val("sis_alterar()");
	$('#'+form+' #ImagemOn1').val("bullet_editar.gif");
	$('#'+form+' #ImagemOff1').val("alt_off.gif");
	$('#'+form+' #PrecisaId1').val("S");
	$('#'+form+' #AltP1').val("Alterar");
	$('#'+form+' #AltNP1').val("Sem permissão para alterar");
	$('#'+form+' #Pos1').val("1");	

	$('#'+form+' #NomePermissao2').val("Visualizar");
	$('#'+form+' #IdPermissao2').val("Vis");
	$('#'+form+' #Funcao2').val("sis_visualizar()");
	$('#'+form+' #ImagemOn2').val("bullet_visualizar.gif");
	$('#'+form+' #ImagemOff2').val("vis_off.gif");
	$('#'+form+' #PrecisaId2').val("S");
	$('#'+form+' #AltP2').val("Visualizar");
	$('#'+form+' #AltNP2').val("Sem permissão para visualizar");
	$('#'+form+' #Pos2').val("2");	

	$('#'+form+' #NomePermissao3').val("Remover");
	$('#'+form+' #IdPermissao3').val("Del");
	$('#'+form+' #Funcao3').val("sis_remover()");
	$('#'+form+' #ImagemOn3').val("bullet_excluir.gif");
	$('#'+form+' #ImagemOff3').val("del_off.gif");
	$('#'+form+' #PrecisaId3').val("S");
	$('#'+form+' #AltP3').val("Deletar");
	$('#'+form+' #AltNP3').val("Sem permissão para deletar");
	$('#'+form+' #Pos3').val("3");		
	
	$('#'+form+' #NomePermissao4').val("Filtrar");
	$('#'+form+' #IdPermissao4').val("Fil");
	$('#'+form+' #Funcao4').val("sis_busca_filtro()");
	$('#'+form+' #ImagemOn4').val("fil_on.gif");
	$('#'+form+' #ImagemOff4').val("fil_off.gif");
	$('#'+form+' #PrecisaId4').val("N");
	$('#'+form+' #AltP4').val("Filtrar");
	$('#'+form+' #AltNP4').val("Sem permissão para filtrar");
	$('#'+form+' #Pos4').val("4");		
}