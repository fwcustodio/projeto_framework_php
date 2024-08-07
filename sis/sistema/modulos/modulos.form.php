<?
include_once($_SESSION['FMBase'].'form_campos.class.php');
include_once($_SESSION['FMBase'].'ajax.class.php');

class ModulosForm extends FormCampos
{
	public function ModulosForm()
	{
		parent::FormCampos();
	}

	public function getFormFiltro()
	{
		$Metodo = "GET";
		
		parent::setModFiltro(true);
		
		$NomeMenu = parent::inputSuggest(array(
		"Nome"       => "NomeMenu",
		"Identifica" => "Nome no Menu",
		"Valor"      => parent::retornaValor($Metodo,"NomeMenu"),
		"Largura"    => 30,
		"TipoFiltro" => "Suggest",
		"Tabela"     => "_modulos",
		"Campo"      => "NomeMenu",
		"Limite"     => 10,
		"Tratar"     => array("L","H","A")),false);
		parent::setFiltro(true,"Nome no Menu:",$NomeMenu,1);

		$Grupo = parent::listBox(array(
		"Nome"       => "GrupoCod",
		"Identifica" => "Grupo",
		"TipoFiltro" => "ValorFixo",
		"Valor"      => parent::retornaValor($Metodo,"GrupoCod"),
		"Inicio"     => "Selecione o Grupo",
		"Tabela"     => "_grupomodulo",
		"CampoCod"   => "GrupoCod",
		"CampoDesc"  => "GrupoDesc",
		"ValidaJS"   => true),false);
		parent::setFiltro(true, "Grupo:", $Grupo,1);
		
		$VisivelMenu = parent::listaVetor(array(
		"Nome"       => "VisivelMenu",
		"Identifica" => "Visivel no Menu",
		"TipoFiltro" => "ValorFixo",
		"Valor"      => parent::retornaValor($Metodo,"VisivelMenu"),
		"Inicio"     => "Todas",
		"Vetor"      => array("S"=>"Visiveis", "N"=>"Não Visiveis")),false);
		parent::setFiltro(true, "Visibilidade:", $VisivelMenu,1);
		
		parent::setModFiltro(false);
					
		//Botão Padrão de Filtro
		parent::setFiltro(true,null,$this->btFiltrar().'<input type="reset" name="Reset" value="Limpar" /> ',1);

		//Ajax
		$this->ajaxRetorno();
	}
	
	public function getFormManu()
	{
		$Metodo = "POST";
		
		$Op = parent::getOp();
		
		$R["Id"] = parent::inputHidden(array(
		"Nome"   => "Id",  
		"Valor"  => parent::retornaValor($Metodo,"Id")),true);
		
		$R["GrupoCod"] = parent::listBox(array(
		"Nome"       => "GrupoCod",
		"Identifica" => "Grupo",
		"Valor"      => parent::retornaValor($Metodo,"GrupoCod"),
		"UrlNovo"     => $_SESSION['UrlBase']."sistema/grupomodulo/grupomodulo.novo.php",
		"PopLargura"  => 600,
		"PopAltura"   => 300,
		"Inicio"     => "Selecione o Grupo",
		"Tabela"     => "_grupomodulo",
		"CampoCod"   => "GrupoCod",
		"CampoDesc"  => "GrupoDesc",
		"Adicional"  => "onChange=\"chamaReferencia('".parent::getNomeForm()."','mReferentes',0)\"",
		"ValidaJS"   => true),true);
		
		$R["Referencia"] = parent::listaVetor(array(
		"Nome"       => "Referencia",
		"Valor"      => null,
		"Status"     => false,
		"Vetor"      => array("Selecione o Grupo")),false);		
		
		$R["ModuloNome"] = parent::inputTexto(array(
		"Nome"       => "ModuloNome",
		"Identifica" => "Nome do Módulo",
		"Valor"      => parent::retornaValor($Metodo,"ModuloNome"),
		"Largura"    => 30,
		"Min"        => 1,
		"Max"        => 70,
		"ValidaJS"   => true,
		"Tratar"     => array("L","H","A")),true);

		$R["NomeMenu"] = parent::inputTexto(array(
		"Nome"       => "NomeMenu",
		"Identifica" => "Nome no Menu",
		"Valor"      => parent::retornaValor($Metodo,"NomeMenu"),
		"Largura"    => 30,
		"Min"        => 1,
		"Max"        => 50,
		"ValidaJS"   => true,
		"Tratar"     => array("L","H","A")),true);
		
		$R["ModuloDesc"] = parent::textArea(array(
		"Nome"       => "ModuloDesc", 
		"Identifica" => "Descrição do Módulo", 
		"Valor"      => parent::retornaValor($Metodo,"ModuloDesc"),
		"Colunas"    => 30,
		"Linhas"     => 5,
		"Tratar"     => array("L","H","A"),
		"ValidaJS"   => true), true);

		$R["VisivelMenu"] = parent::listaVetor(array(
		"Nome"       => "VisivelMenu",
		"Identifica" => "Visivel no Menu?",
		"Valor"      => parent::retornaValor($Metodo,"VisivelMenu"),
		"Padrao"     => 'S',
		"Vetor"      => array("S"=>"Sim", "N"=>"Não"),
		"ValidaJS"   => true),true);
				
		$R["Posicao"] = parent::listaVetor(array(
		"Nome"       => "Posicao",
		"Identifica" => "Posição",
		"Valor"      => parent::retornaValor($Metodo,"Posicao"),
		"Inicio"     => "Selecione a Posição",
		"Vetor"      => array("0"=>"0","1"=>"1","2"=>"2","3"=>"3","4"=>"4","5"=>"5","6"=>"6","7"=>"7","8"=>"8","9"=>"9"),
		"ValidaJS"   => true),true);
		
		$R["Help"] = parent::inputHtmlEditor(array(
		"Nome"        => "Help",
		"Identifica"  => "Help",
		"Valor"       => parent::retornaValor($Metodo,"Help"),
		"Largura"     => 600,
		"Altura"      => 300,
		"Ferramentas" => "Custon1",
 		"ValidaJS"    => false),false);				
		
		return $R;		
	}
	
	public function getFormReferencia($Opcao)
	{
		$Metodo = "POST";
		
		$Op = parent::getOp();
		
		if($Opcao == "A")
		{
			return  parent::listBox(array(
			"Nome"       => "Referencia",
			"Identifica" => "Referencia",
			"Valor"      => parent::retornaValor($Metodo,"Referencia"),
			"Inicio"     => "Não Possui Referencia",
			"Tabela"     => "_modulos",
			"CampoCod"   => "ModuloCod",
			"CampoDesc"  => "NomeMenu",
			"Condicao"   => "WHERE GrupoCod = '".parent::retornaValor($Metodo,"GrupoCod")."'",
			"ValidaJS"   => false),false);
		}
		else 
		{		
			return parent::listaVetor(array(
			"Nome"       => "Referencia",
			"Valor"      => null,
			"Status"     => false,
			"Vetor"      => array("Selecione o Grupo")),true);
		}		
	}
	
	public function getFormOpcao($Cod = 0)
	{		
		$Metodo = "POST";
		
		$Op = parent::getOp();
		
		$R["NomePermissao$Cod"] = parent::inputTexto(array(
		"Nome"       => "NomePermissao$Cod",
		"Identifica" => "Nome da Permisão $Cod",
		"Valor"      => parent::retornaValor($Metodo,"NomePermissao$Cod"),
		"Largura"    => 20,
		"Min"        => 1,
		"Max"        => 30,
		"Tratar"     => array("L","H","A")),true);
		
		$R["IdPermissao$Cod"] = parent::inputTexto(array(
		"Nome"       => "IdPermissao$Cod",
		"Identifica" => "Id da Permisão $Cod",
		"Valor"      => parent::retornaValor($Metodo,"IdPermissao$Cod"),
		"Largura"    => 10,
		"Min"        => 1,
		"Max"        => 10,
		"Tratar"     => array("L","H","A")),true);		
		
		$R["Funcao$Cod"] = parent::inputTexto(array(
		"Nome"       => "Funcao$Cod",
		"Identifica" => "Função $Cod",
		"Valor"      => parent::retornaValor($Metodo,"Funcao$Cod"),
		"Largura"    => 20,
		"Min"        => 1,
		"Max"        => 50,
		"Tratar"     => array("L","H","A")),true);			
		
		$R["ImagemOn$Cod"] = parent::inputTexto(array(
		"Nome"       => "ImagemOn$Cod",
		"Identifica" => "Imagem ON $Cod",
		"Valor"      => parent::retornaValor($Metodo,"ImagemOn$Cod"),
		"Largura"    => 20,
		"Min"        => 1,
		"Max"        => 30,
		"Tratar"     => array("L","H","A")),true);		

		$R["ImagemOff$Cod"] = parent::inputTexto(array(
		"Nome"       => "ImagemOff$Cod",
		"Identifica" => "Imagem OFF $Cod",
		"Valor"      => parent::retornaValor($Metodo,"ImagemOff$Cod"),
		"Largura"    => 20,
		"Min"        => 1,
		"Max"        => 30,
		"Tratar"     => array("L","H","A")),true);	
		
		$R["PrecisaId$Cod"] = parent::listaVetor(array(
		"Nome"       => "PrecisaId$Cod",
		"Identifica" => "Precisa Id $Cod",
		"Valor"      => parent::retornaValor($Metodo,"PrecisaId$Cod"),
		"Vetor"      => array("S"=>"Sim", "N"=>"Não")),true);
		
		$R["AltP$Cod"] = parent::inputTexto(array(
		"Nome"       => "AltP$Cod",
		"Identifica" => "Texto com Permissão $Cod",
		"Valor"      => parent::retornaValor($Metodo,"AltP$Cod"),
		"Largura"    => 20,
		"Min"        => 1,
		"Max"        => 75,
		"Tratar"     => array("L","H","A")),true);	
		
		$R["AltNP$Cod"] = parent::inputTexto(array(
		"Nome"       => "AltNP$Cod",
		"Identifica" => "Texto sem Permissão$Cod",
		"Valor"      => parent::retornaValor($Metodo,"AltNP$Cod"),
		"Largura"    => 20,
		"Min"        => 1,
		"Max"        => 75,
		"Tratar"     => array("L","H","A")),true);	
		
		$R["Pos$Cod"] = parent::listaVetor(array(
		"Nome"       => "Pos$Cod",
		"Identifica" => "Posição $Cod",
		"Valor"      => parent::retornaValor($Metodo,"Pos$Cod"),
		"Inicio"     => "Posição",
		"Vetor"      => array("0"=>"0","1"=>"1","2"=>"2","3"=>"3","4"=>"4","5"=>"5","6"=>"6","7"=>"7","8"=>"8","9"=>"9")),true);

		return $R;			
	}	
	
	public function btFiltrar()
	{
		//Padrão Para Busca em Ajax -> Executado Pelo Observer
		return parent::botao(array(
		"Nome"       => "BtFiltrar",
		"Identifica" => "Filtrar",
		"Tipo"       => "button",
		"Estilo"     => "cursor:pointer"));
	}
	
	public function ajaxRetorno()
	{
		$Ajax = new Ajax();
			
		parent::setFuncoes($Ajax->ajaxUpdate(array(
		"Nome"       => "sis_filtrar", 
		"URL"        => MODULO.".ajax.php?Op=Fil",
		"Form"       => "FormFiltro",
		"VarPar"     => "PARFIL",
		"Metodo"     => "get",
		"TipoDado"   => 'script',
		"Conteiner"  => 'corpoPrincipal')));		
		
		parent::setFuncoes('function sis_busca_filtro(){ sis_atualizar("'.MODULO.'"); }');
		
		parent::setFuncoes($Ajax->ajaxUpdate(array(
		"Nome"       => "visualiza", 
		"URL"        => MODULO.".ajax.php?Op=Vis",
		"VarPar"     => "PARVIS",
		"Form"       => "FormGrid",
		"Metodo"     => "POST",
		"Conteiner"  => 'manu')));		

		parent::setFuncoes($Ajax->ajaxUpdate(array(
		"Nome"       => "sis_cadastrar", 
		"URL"        => MODULO.".ajax.php?Op=Cad",
		"TipoDado"   => 'script',
		"Conteiner"  => 'manu')));		

		parent::setFuncoes($Ajax->ajaxRequest(array(
		"Nome"       => "cadastraBd", 
		"URL"        => MODULO.".ajax.php?Op=Cad&Env=true",
		"Form"       => "FormManu",
		"Metodo"     => "POST",
		"Completa"   => "function(Req){ retornoCadastrar(Req.responseText); } ")));	
		
		parent::setFuncoes($Ajax->ajaxUpdate(array(
		"Nome"       => "alteraForm", 
		"URL"        => MODULO.".ajax.php?Op=Alt",
		"VarPar"     => "PARALT",
		"Conteiner"  => 'manu',
		"TipoDado"   => 'script',
		"Form"       => "FormGrid",
		"Metodo"     => "POST")));				

		parent::setFuncoes($Ajax->ajaxRequestForm(array(
		"Nome"       => "alteraBd", 
		"URL"        => MODULO.".ajax.php?Op=Alt&Env=true",
		"Metodo"     => "POST",
		"Completa"   => "function(Req){ retornoAlterar(Req.responseText, conteiner); } ")));					
		
		parent::setFuncoes($Ajax->ajaxRequest(array(
		"Nome"       => "sis_apagar", 
		"URL"        => MODULO.".ajax.php?Op=Del",
		"Form"       => "FormGrid",
		"Metodo"     => "POST",
		"Completa"   => "function(Req){ retornoRemover(Req.responseText); } ")));
		
		parent::setFuncoes($Ajax->ajaxRequest(array(
		"Nome"       => "moduloOpcoes", 
		"ParInicial" => "form",
		"URL"        => MODULO.".ajax.php?Op=Mod",
		"Metodo"     => "POST",
		"Parametros" => "{Id:OPCAOMODULO}",
		"Completa"   => "function(Req){ retornoOpcoes(form, Req.responseText); } ")));	
		
		parent::setFuncoes($Ajax->ajaxLoad(array(
		"Nome"       => "chamaReferencia", 
		"ParInicial" => "ref",
		"URL"        => MODULO.".ajax.php?Op=GMod",
		"Parametros" => "{GrupoCod:$('#'+form+' #GrupoCod').val(), Referencia:ref }")));
		
		//Popula ListBox
		parent::setFuncoes("
		function updateListBox(form, campo)
		{
			if(campo == 'GrupoCod')
			{
				var retorno = $.ajax({ url: '".$_SESSION['UrlBase']."sistema/grupomodulo/grupomodulo.ajax.php?Op=Novo', async: false }).responseText;
				$('#'+form+' #'+campo).append(retorno);	
				$('#'+form+' #Referencia').attr('disabled','');	
				$('#'+form+' #Referencia').children().remove();
				$('#'+form+' #Referencia').append('<option value=\"\">Não Possui Referencia</option>');
			}
		}
		");
		
		parent::setFuncoes('$(document.body).ready(function(){ sis_filtrar();  $("#FormFiltro #BtFiltrar").click(function(){ sis_filtrar(); }) });');
	}
}
?>