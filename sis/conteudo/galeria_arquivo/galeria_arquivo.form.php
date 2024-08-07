<?
include_once($_SESSION['FMBase'].'form_campos.class.php');
include_once($_SESSION['FMBase'].'ajax.class.php');

class GaleriaArquivoAForm extends FormCampos
{
	public function GaleriaArquivoAForm()
	{
		parent::FormCampos();
	}

	public function getFormFiltro()
	{
		$Metodo = "GET";
		
		//Campos de Filtro
		parent::setModFiltro(true);
		$ArquivoCategoriaNome = parent::inputSuggest(array(
		"Nome"        => "ArquivoCategoriaNome",
		"Identifica"  => "ArquivoCategoriaNome",
		"TipoFiltro"  => "Suggest",
		"Valor"       => parent::retornaValor($Metodo,"ArquivoCategoriaNome"),
		"Tabela"      => "arquivo_categoria",
		"Campo"       => "ArquivoCategoriaNome"),false);
		parent::setFiltro(true,"Galeria:",$ArquivoCategoriaNome,1);

//		$Publicar = parent::listaVetor(array(
//		"Nome"        => "Documento",
//		"Identifica"  => "Documentos",
//		"Valor"       => parent::retornaValor($Metodo,"Documento"),
//		"Status"      => true,
//		"ValidaJS"    => false,
//		"Inicio"      => "Escolha uma opção",
//		"Vetor"       => array("S"=>"Sim", "N"=>"Não")),false);
//		parent::setFiltro(true,"Galeria de Documentos:",$Publicar,1);

		$Publicar = parent::listaVetor(array(
		"Nome"        => "Publicar",
		"Identifica"  => "Publicar",
		"Valor"       => parent::retornaValor($Metodo,"Publicar"),
		"Status"      => true,
		"ValidaJS"    => false,
		"Inicio"      => "Escolha uma opção",
		"Vetor"       => array("S"=>"Sim", "N"=>"Não")),false);
		parent::setFiltro(true,"Publicar:",$Publicar,1);
		

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
		
		$R["ArquivoCategoriaNome"] = parent::inputTexto(array(
		"Nome"        => "ArquivoCategoriaNome",
		"Identifica"  => "ArquivoCategoriaNome",
		"Valor"       => parent::retornaValor($Metodo,"ArquivoCategoriaNome"),
		"Status"      => true,
		"ValidaJS"    => false),true);
		
		parent::listaVetor(array(
		"Nome"        => "Publicar",
		"Identifica"  => "Publicar",
		"Valor"       => parent::retornaValor($Metodo,"Publicar"),
		"Status"      => true,
		"ValidaJS"    => false,
		"Vetor"       => array("S"=>"Sim", "N"=>"Não")),true);		
		
		parent::listaVetor(array(
		"Nome"        => "Situacao",
		"Identifica"  => "Situacao",
		"Valor"       => parent::retornaValor($Metodo,"Situacao"),
		"Status"      => true,
		"ValidaJS"    => false,
		"Inicio"      => "Escolha...",
		"Ordena"      => false,
		"Vetor"       => array("A"=>"Ativo", "I"=>"Inativo")),true);
		
		
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

		parent::setFuncoes('$(document.body).ready(function(){ sis_filtrar();  $("#FormFiltro #BtFiltrar").click(function(){ sis_filtrar(); }) });');
	}
}
?>