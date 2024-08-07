<?
include_once($_SESSION['FMBase'].'form_campos.class.php');
include_once($_SESSION['FMBase'].'ajax.class.php');

class UpArquivoForm extends FormCampos
{
	public function __construct() {
            parent::FormCampos();
	}

	public function getFormFiltro()
	{
		$Metodo = "GET";
		
		//Campos de Filtro
		parent::setModFiltro(true);

		$ArquivoCategoriaCod = parent::listBox(array(
		"Nome"        => "ArquivoCategoriaCod",
		"Identifica"  => "ArquivoCatecoriaCod",
		"TipoFiltro"  => "ValorFixo",
		"Valor"       => parent::retornaValor($Metodo,"ArquivoCategoriaCod"),
		"Status"      => true,
		"ValidaJS"    => false,
		"Inicio"      => "Selecione uma Categoria",
		"Tabela"      => "arquivo_categoria",
		"CampoCod"    => "ArquivoCategoriaCod",
		"CampoDesc"   => "ArquivoCategoriaNome"),false);
		parent::setFiltro(true,"Categoria:",$ArquivoCategoriaCod,1);

		$ArquivoNome = parent::inputSuggest(array(
		"Nome"        => "ArquivoNome",
		"Identifica"  => "ArquivoNome",
		"TipoFiltro"  => "ValorVariavel",
		"Valor"       => parent::retornaValor($Metodo,"ArquivoNome"),
		"Tabela"      => "arquivo",
		"Campo"       => "ArquivoNome",
		"Condicao"	  => "GROUP BY ArquivoNome"),false);
		parent::setFiltro(true,"Arquivo:",$ArquivoNome,1);

		$DataPublicacao = parent::inputData(array(
		"Nome"        => "DataPublicacao",
		"Identifica"  => "DataPublicacao",
		"TipoFiltro"  => "ValorVariavel",
		"Valor"       => parent::retornaValor($Metodo,"DataPublicacao"),
		"Status"      => true,
		"ValidaJS"    => false),false);
		parent::setFiltro(true,"Data de Publicacao:",$DataPublicacao,2);

		$Downloads = parent::inputInteiro(array(
		"Nome"        => "Downloads",
		"Identifica"  => "Downloads",
		"TipoFiltro"  => "ValorVariavel",
		"Valor"       => parent::retornaValor($Metodo,"Downloads"),
		"Status"      => true,
		"ValidaJS"    => false),false);
		parent::setFiltro(true,"Downloads:",$Downloads,2);		

		parent::setModFiltro(false);										
		
		//Botão Padrão de Filtro
		parent::setFiltro(true,null,$this->btFiltrar().'<input type="reset" name="Reset" value="Limpar" /> ',2);

		//Ajax
		$this->ajaxRetorno(false);
	}
	
	public function getFormPop()
	{
		$Metodo = "GET";
		
		//Campos de Filtro
		parent::setModFiltro(true);
		
		$ArquivoNome = parent::inputSuggest(array(
		"Nome"        => "ArquivoNome",
		"Identifica"  => "ArquivoNome",
		"Valor"       => parent::retornaValor($Metodo,"ArquivoNome"),
		"Tabela"      => "arquivo",
		"Campo"       => "ArquivoNome"),false);
		parent::setFiltro(true,"Nome:",$ArquivoNome,1);
		
		$ArquivoCategoriaCod = parent::listBox(array(
		"Nome"        => "ArquivoCategoriaCod",
		"Identifica"  => "ArquivoCatecoriaCod",
		"Valor"       => parent::retornaValor($Metodo,"ArquivoCategoriaCod"),
		"Status"      => true,
		"ValidaJS"    => false,
		"Inicio"      => "Todas",
		"Tabela"      => "arquivo_categoria",
		"CampoCod"    => "ArquivoCategoriaCod",
		"CampoDesc"   => "ArquivoCategoriaNome"),false);
		parent::setFiltro(true,"Categoria:",$ArquivoCategoriaCod,2);
                
		parent::setModFiltro(false);		

		//Botão Padrão de Filtro
		parent::setFiltro(true,null,$this->btFiltrar().'<input type="reset" name="Reset" value="Limpar" /> ',2);
		
		//Ajax
		$this->ajaxRetorno(true);
	}	
		
	public function getFormManu()
	{
		$Op = parent::getOp();
		
		$Metodo =  "POST";
		
		$Env = parent::getEnv();
		
		$R["Id"] = parent::inputHidden(array(
		"Nome"   => "Id",  
		"Valor"  => parent::retornaValor($Metodo,"Id")),true);
		
		
                $R["ArquivoNome"] = parent::inputTexto(array(
                "Nome"        => "ArquivoNome",
                "Identifica"  => "ArquivoNome",
                "Valor"       => parent::retornaValor($Metodo,"ArquivoNome"),
                "Largura"     => 30,
                "Status"      => true,
                "ValidaJS"    => true),true);
		
		
		$R["ArquivoCategoriaCod"] = parent::listBox(array(
		"Nome"        => "ArquivoCategoriaCod",
		"Identifica"  => "ArquivoCategoriaCod",
		"Valor"       => parent::retornaValor($Metodo,"ArquivoCategoriaCod"),
		"Status"      => true,
		"ValidaJS"    => false,
		"Inicio"      => "Selecione uma Categoria",
		"Tabela"      => "arquivo_categoria",
		"CampoCod"    => "ArquivoCategoriaCod",
		"CampoDesc"   => "ArquivoCategoriaNome"),true);

		$R["ArquivoDescricao"] = parent::textArea(array(
		"Nome"        => "ArquivoDescricao",
		"Identifica"  => "ArquivoDescricao",
		"Valor"       => parent::retornaValor($Metodo,"ArquivoDescricao"),
		"Colunas"     => 30,
		"Linhas"      => 5,
		"Tratar"     => array("L","H","A"),
		"ValidaJS"    => false),false);
		
		if($Op == 'Cad'){
			$_POST['DataPublicacao'] = date('d/m/Y');
		}

		$R["DataPublicacao"] = parent::inputData(array(
		"Nome"        => "DataPublicacao",
		"Identifica"  => "DataPublicacao",
		"Valor"       => parent::retornaValor($Metodo,"DataPublicacao"),
		"Status"      => true,
		"ValidaJS"    => false),true);

		$R["Downloads"] = parent::inputInteiro(array(
		"Nome"        => "Downloads",
		"Identifica"  => "Downloads",
		"Valor"       => parent::retornaValor($Metodo,"Downloads"),
		"Status"      => true,
		"ValidaJS"    => false),false);

		$R["HashCod"] = parent::inputInteiro(array(
		"Nome"        => "HashCod",
		"Identifica"  => "HashCod",
		"Valor"       => parent::retornaValor($Metodo,"HashCod"),
		"Status"      => true,
		"ValidaJS"    => false),false);
						
		$R["Arquivos"] = parent::uploadMultiploJQuery(array(
		"Nome"        => "Arquivos".$Id,
		"Identifica"  => "Arquivos",
		"Max" 	      => 5,
		"Status"      => true),false);		
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
		"Completa"   => "function(Req){  retornoRemover(Req.responseText); } ")));

		parent::setFuncoes('$(document.body).ready(function(){ sis_filtrar();  $("#FormFiltro #BtFiltrar").click(function(){ sis_filtrar(); }) });');
	}
}