<?
include_once($_SESSION['FMBase'].'form_campos.class.php');
include_once($_SESSION['FMBase'].'ajax.class.php');

class GrupoModuloForm extends FormCampos
{
	public function GrupoModuloForm()
	{
		parent::FormCampos();
	}

	public function getFormFiltro()
	{
		$Metodo = "GET";
		
		parent::setModFiltro(true);
		
		$NomeGrupo = parent::inputSuggest(array(
		"Nome"       => "NomeGrupo",
		"Identifica" => "Nome do Grupo",
		"Valor"      => parent::retornaValor($Metodo,"NomeGrupo"),
		"Largura"    => 30,
		"TipoFiltro"  => "Suggest",
		"Tabela"     => "_grupomodulo",
		"Campo"      => "GrupoDesc",
		"Limite"     => 10,
		"Tratar"     => array("L","H","A")),false);
		parent::setFiltro(true,"Nome do Grupo:",$NomeGrupo,1);

		$Pacote = parent::inputSuggest(array(
		"Nome"       => "Pacote",
		"Identifica" => "Pacote",
		"Valor"      => parent::retornaValor($Metodo,"Pacote"),
		"Largura"    => 30,
		"TipoFiltro"  => "Suggest",
		"Tabela"     => "_grupomodulo",
		"Campo"      => "Pacote",
		"Limite"     => 10,
		"Tratar"     => array("L","H","A")),false);
		parent::setFiltro(true,"Pacote:",$Pacote,1);
		
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
						
		$R["NomeGrupo"] = parent::inputTexto(array(
		"Nome"       => "NomeGrupo",
		"Identifica" => "Nome do Grupo",
		"Valor"      => parent::retornaValor($Metodo,"NomeGrupo"),
		"Largura"    => 30,
		"Min"        => 1,
		"Max"        => 50,
		"ValidaJS"   => true,
		"Tratar"     => array("L","H","A")),true);

		$R["Pacote"] = parent::inputTexto(array(
		"Nome"       => "Pacote",
		"Identifica" => "Pacote",
		"Valor"      => parent::retornaValor($Metodo,"Pacote"),
		"Largura"    => 30,
		"Min"        => 1,
		"Max"        => 50,
		"ValidaJS"   => true,
		"Tratar"     => array("L","H","A")),true);
		
		$R["Posicao"] = parent::listaVetor(array(
		"Nome"       => "Posicao",
		"Identifica" => "Posição",
		"Valor"      => parent::retornaValor($Metodo,"Posicao"),
		"Inicio"     => "Selecione a Posição",
		"Vetor"      => array(0,1,2,3,4,5,6,7,8,9),
		"ValidaJS"   => true),true);
				
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
	
	public function ajaxCadastro()
	{
		$Ajax = new Ajax();
		
		parent::setFuncoes($Ajax->ajaxUpdate(array(
		"Nome"       => "sis_cadastrar",
		"URL"        => MODULO.".ajax.php?Op=Cad&Pop=true",
		"TipoDado"   => 'script',
		"Conteiner"  => 'manu')));

		parent::setFuncoes($Ajax->ajaxRequest(array(
		"Nome"       => "cadastraBd",
		"URL"        => MODULO.".ajax.php?Op=Cad&Env=true",
		"Form"       => "FormManu",
		"Metodo"     => "POST",
		"Completa"   => "function(Req){ retornoCadastrarPop(Req.responseText); } ")));
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

		parent::setFuncoes('$(document.body).ready(function(){ sis_filtrar();  $("#FormFiltro #BtFiltrar").click(function(){ sis_filtrar(); }) });');
	}
}
?>