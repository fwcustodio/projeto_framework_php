<?
include_once($_SESSION['FMBase'].'form_campos.class.php');
include_once($_SESSION['FMBase'].'ajax.class.php');

class GaleriaMidiaForm extends FormCampos
{
	public function GaleriaMidiaForm()
	{
		parent::FormCampos();
	}

	public function getFormFiltro()
	{
		$Metodo = "GET";
		
		//Campos de Filtro
		parent::setModFiltro(true);
		
		$GaleriaNome = parent::inputSuggest(array(
		"Nome"        => "GaleriaNome",
		"Identifica"  => "Nome da Galeria",
		"Largura"     => 25,
		"TipoFiltro"  => "Suggest",
		"Valor"       => parent::retornaValor($Metodo,"GaleriaNome"),
		"Tabela"      => "galeria_midia",
		"Campo"       => "GaleriaNome"),false);
		parent::setFiltro(true,"Nome da Galeria:",$GaleriaNome,1);

		
		$DataCriacao = parent::inputData(array(
		"Nome"        => "DataCriacao",
		"Identifica"  => "Data de Criação",
		"TipoFiltro"  => "ValorVariavel",
		"Valor"       => parent::retornaValor($Metodo,"DataCriacao"),
		"Status"      => true,
		"ValidaJS"    => true),false);
		parent::setFiltro(true,"Data de Criação:",$DataCriacao,1);
		

		$Publicar = parent::listaVetor(array(
		"Nome"        => "Publicar",
		"Identifica"  => "Publicar",
		"TipoFiltro"  => "ValorFixo",
		"Valor"       => parent::retornaValor($Metodo,"Publicar"),
		"Status"      => true,
		"Inicio"      => "Todos",
		"Ordena"      => false,
		"Vetor"       => array("S"=>"Registros Publicados", "N"=>"Registros Não Publicados")),false);
		parent::setFiltro(true,"Publicar:",$Publicar,2);
		
		$Situacao = parent::listaVetor(array(
		"Nome"        => "Situacao",
		"Identifica"  => "Situação",
		"TipoFiltro"  => "ValorFixo",
		"Valor"       => parent::retornaValor($Metodo,"Situacao"),
		"Status"      => true,
		"Inicio"      => "Todas",
		"Ordena"      => false,
		"Vetor"       => array("A"=>"Registros Ativos", "I"=>"Registros Inativos")),false);
		parent::setFiltro(true,"Situação:",$Situacao,2);

		parent::setModFiltro(false);										
		
		//Botão Padrão de Filtro
		parent::setFiltro(true,null,$this->btFiltrar().'<input type="reset" name="Reset" value="Limpar" /> ',1);

		//Ajax
		$this->ajaxRetorno(false);
	}
	
	public function getFormPop()
	{
		$Metodo = "GET";
		
		//Campos de Filtro
		parent::setModFiltro(true);
		
		$GaleriaNome = parent::inputSuggest(array(
		"Nome"        => "GaleriaNome",
		"Identifica"  => "Nome da Galeria",
		"Largura"     => 25,
		"TipoFiltro"  => "Suggest",
		"Valor"       => parent::retornaValor($Metodo,"GaleriaNome"),
		"Tabela"      => "galeria_midia",
		"Campo"       => "GaleriaNome"),false);
		parent::setFiltro(true,"Galeria:",$GaleriaNome,1);

		$Publicar = parent::listaVetor(array(
		"Nome"        => "Publicar",
		"Identifica"  => "Publicar",
		"TipoFiltro"  => "ValorFixo",
		"Valor"       => parent::retornaValor($Metodo,"Publicar"),
		"Status"      => true,
		"Inicio"      => "Todos",
		"Ordena"      => false,
		"Vetor"       => array("S"=>"Registros Publicados", "N"=>"Registros Não Publicados")),false);
		parent::setFiltro(true,"Publicar:",$Publicar,2);
		
		parent::setModFiltro(false);										
		
		//Botão Padrão de Filtro
		parent::setFiltro(true,null,$this->btFiltrar().'<input type="reset" name="Reset" value="Limpar" /> ',1);
		
			//Ajax
		$this->ajaxRetorno(true);
	}		
	
	public function getFormManu()
	{
		$Metodo = "POST";
		
		$Op = parent::getOp();
		
		$R["Id"] = parent::inputHidden(array(
		"Nome"   => "Id",  
		"Valor"  => parent::retornaValor($Metodo,"Id")),true);
		
		$R["GaleriaNome"] = parent::inputTexto(array(
		"Nome"        => "GaleriaNome",
		"Identifica"  => "Nome da Galeria",
		"Largura"     => 30,
		"Valor"       => parent::retornaValor($Metodo,"GaleriaNome"),
		"Status"      => true,
		"ValidaJS"    => true),true);

		if($Op == "Cad")
		{
			$DataCriacao = parent::retornaValor($Metodo,"DataCriacao");
			
			if(empty($DataCriacao))
			{
				$DataCriacao = date("d/m/Y");
			}
		}
		else 
		{
			$DataCriacao = parent::retornaValor($Metodo,"DataCriacao");
		}
		
		$R["DataCriacao"] = parent::inputData(array(
		"Nome"        => "DataCriacao",
		"Identifica"  => "Data de Criação",
		"Valor"       => $DataCriacao,
		"Status"      => true,
		"ValidaJS"    => false),false);

		parent::listaVetor(array(
		"Nome"        => "Capa",
		"Identifica"  => "Capa",
		"Valor"       => parent::retornaValor($Metodo,"Capa"),
		"Status"      => true,
		"Vetor"       => array("N"=>"Não", "S"=>"Sim")),true);

//		$IdForm = parent::retornaValor($Metodo,"Id");
//		$R["Arquivos"] = parent::uploadMultiploJQuery(array(
//		"Nome"        => "Arquivos".$IdForm,
//		"Identifica"  => "Arquivos",
//		"Tipos"       => array('jpg','jpeg','gif','png'),
//		"Max" 	      => 1,
//		"Status"      => true),false);

		parent::listaVetor(array(
		"Nome"        => "Publicar",
		"Identifica"  => "Publicar",
		"Valor"       => parent::retornaValor($Metodo,"Publicar"),
		"Status"      => true,
		"Vetor"       => array("N"=>"Não", "S"=>"Sim")),true);
		
		parent::listaVetor(array(
		"Nome"        => "Situacao",
		"Identifica"  => "Situação",
		"Valor"       => parent::retornaValor($Metodo,"Situacao"),
		"Status"      => true,
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
	
	public function ajaxRetorno($Pop)
	{
		$Ajax = new Ajax();
		
		$ParPop = $Pop ? '&Pop=true' : '';
		
		parent::setFuncoes($Ajax->ajaxUpdate(array(
		"Nome"       => "sis_filtrar", 
		"URL"        => MODULO.".ajax.php?Op=Fil".$ParPop,
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