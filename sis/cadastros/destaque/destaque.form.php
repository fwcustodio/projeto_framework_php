<?
include_once($_SESSION['FMBase'].'form_campos.class.php');
include_once($_SESSION['FMBase'].'ajax.class.php');

class DestaqueForm extends FormCampos
{
	public function __construct()
	{
		parent::FormCampos();
	}

	public function getFormFiltro()
	{
		$Metodo = "GET";
		
		//Campos de Filtro
		parent::setModFiltro(true);
		$DestaqueTitulo = parent::inputSuggest(array(
		"Nome"        => "DestaqueTitulo",
		"Identifica"  => "Titulo",
		"TipoFiltro"  => "Suggest",
		"Valor"       => parent::retornaValor($Metodo,"DestaqueTitulo"),
		"Largura"     => 20,
		"Tabela"      => "destaque",
		"Campo"       => "DestaqueTitulo"),false);
		parent::setFiltro(true,"Titulo:",$DestaqueTitulo,1);

                $DestaqueLink = parent::inputSuggest(array(
		"Nome"        => "DestaqueLink",
		"Identifica"  => "Link",
		"TipoFiltro"  => "Suggest",
		"Valor"       => parent::retornaValor($Metodo,"DestaqueLink"),
		"Largura"     => 20,
		"Tabela"      => "destaque",
		"Campo"       => "DestaqueLink"),false);
		parent::setFiltro(true,"Link:",$DestaqueLink,1);

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
		
		$R["DestaqueTitulo"] = parent::inputTexto(array(
		"Nome"        => "DestaqueTitulo",
		"Identifica"  => "Titulo",
		"Valor"       => parent::retornaValor($Metodo,"DestaqueTitulo"),
		"Largura"     => 80,
		"Status"      => true,
		"ValidaJS"    => true),true);

		$R["DestaqueDescricao"] = parent::inputHtmlEditor(array(
		"Nome"        => "DestaqueDescricao",
		"Identifica"  => "Descricao",
		"Valor"       => parent::retornaValor($Metodo,"DestaqueDescricao"),
		"Largura"     => "100%",
		"Altura"      => 400,
		"Ferramentas" => "Custon1",
		"Tratar"	  => array("L"),
		"ValidaJS"    => false),true);

		$R["DestaquePrioridade"] = parent::listaVetor(array(
		"Nome"        => "DestaquePrioridade",
		"Identifica"  => "Prioridade",
		"Valor"       => parent::retornaValor($Metodo,"DestaquePrioridade"),
		"Inicio"      => '?',
		"Status"      => true,
		"Ordena"      => false,
		"ValidaJS"    => true,
		"Vetor"       => array_combine(range(1,99),range(1,99))),true);		
		
                $R["DestaqueTipo"] = parent::listaVetor(array(
		"Nome"        => "DestaqueTipo",
		"Identifica"  => "Tipo de Link",
		"Valor"       => parent::retornaValor($Metodo,"DestaqueTipo"),
		"Status"      => true,
		"Ordena"      => false,
		"Padrao"      => 'http://',
		"Vetor"       => array('http://'  => 'http://',
                                       'https://' => 'https://',
                                       'mailto:'  => 'Email',
                                       ''    	  => 'Outros')),false);

		$R["DestaqueLink"] = parent::inputTexto(array(
		"Nome"        => "DestaqueLink",
		"Identifica"  => "Link",
		"Valor"       => parent::retornaValor($Metodo,"DestaqueLink"),
		"Largura"     => 30,
		"Status"      => true,
		"ValidaJS"    => false),false);
                
                $R["DestaqueLinkTarget"] = parent::listaVetor(array(
		"Nome"        => "DestaqueLinkTarget",
		"Identifica"  => "Visualização do Link",
		"Valor"       => parent::retornaValor($Metodo,"DestaqueLinkTarget"),
		"Status"      => true,
		"Ordena"      => false,
		"Padrao"      => 'Mesma Página',
		"Vetor"       => array('_parent'  => 'Mesma Página',
                                       '_blank'   => 'Nova Página')),false);

		$R["DestaqueImagem"] = parent::uploadMultiploJQuery(array(
		"Nome"        => "DestaqueImagem".$Id,
		"Identifica"  => "Imagens",
		"Tipos"  	  => array('gif', 'jpg'),
		"Max" 	      => 1,
		"Status"      => true),true);
                
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