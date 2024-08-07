<?
include_once($_SESSION['FMBase'].'form_campos.class.php');
include_once($_SESSION['FMBase'].'ajax.class.php');
include_once($_SESSION['DirBase'].'conteudo/servicos_categoria/servicos_nivel/servicos_nivel.class.php');

class CatServForm extends FormCampos
{
	private $CatPai;
	
	public function CatServForm()
	{
		parent::FormCampos();
		$this->CatPai = new ServicoNivel();
	}

	public function getFormFiltro()
	{
		$Metodo = "GET";
		
		//Campos de Filtro
		parent::setModFiltro(true);
		
		$ServicoCategoriaNome = parent::inputSuggest(array(
		"Nome"        => "ServicoCategoriaNome",
		"Identifica"  => "ServicoCategoriaNome",
		"TipoFiltro"  => "Suggest",
		"Valor"       => parent::retornaValor($Metodo,"ServicoCategoriaNome"),
		"Largura"     => 50,
		"Tabela"      => "servico_categoria",
		"Campo"       => "ServicoCategoriaNome"),false);
		parent::setFiltro(true,"Categoria:",$ServicoCategoriaNome,1);
		
		
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
		
		$R["ServicoCategoriaNome"] = parent::inputTexto(array(
		"Nome"        => "ServicoCategoriaNome",
		"Identifica"  => "Categoria da Servico",
		"Valor"       => parent::retornaValor($Metodo,"ServicoCategoriaNome"),
		"Largura"     => 30,
		"Status"      => true,
		"ValidaJS"    => true),true);	
		
		//$R["CategoriaPai"] = parent::listaVetor(array(
//		"Nome"        => "CategoriaPai",
//		"Identifica"  => "Categoria Pai",
//		"Conteiner"   => "cCategoriaPai",
//		"Valor"       => parent::retornaValor($Metodo,"CategoriaPai"),
//		"Status"      => true,
//		"Inicio"      => "Selecione o Grupo",
//		"Vetor"       => array()),false);
				
		//Campo Secao Pai
		$ServicoNivel = new ServicoNivel();
				
		$R["CategoriaPai"] = $ServicoNivel->geraCampoSecao("CategoriaPai",parent::retornaValor($Metodo,"CategoriaPai"), "Não Informar...");
		
		//$R["Posicao"] = parent::listaVetor(array(
//		"Nome"        => "Posicao",
//		"Identifica"  => "Posição",
//		"Valor"       => parent::retornaValor($Metodo,"Posicao"),
//		"Inicio"      => '?',
//		"Status"      => true,
//		"Ordena"      => false,
//		"ValidaJS"    => true,
//		"Vetor"       => array_combine(range(1,99),range(1,99))),true);
		
		
		return $R;	
	}
	
	public function getFormCategoriaPai($Codigo, $Conteiner = false)
	{
		$Metodo 	= "POST";
		$Op 		= parent::getOp();
		
		if(!empty($Codigo))
		{
			try
			{ 
				//Campo Secao Pai
				$CategoriaPai = new CategoriaPai();
				
				$R["CategoriaPai"] = $CategoriaPai->geraCampoSecao("CategoriaPai",parent::retornaValor($Metodo,"CategoriaPai"), $Codigo,"Não Informar...");
				
				if($Conteiner == true)
				{
					$R["CategoriaPai"] = '<div id="cCategoriaPai">'.$R["CategoriaPai"].'</div>';
				}
			}
			catch (Exception $E)
			{
				
			}
		}
		else
		{			
			$Array = array(
			"Nome"        => "CategoriaPai",
			"Identifica"  => "Categoria Pai",
			"Valor"       => parent::retornaValor($Metodo,"CategoriaPai"),
			"Status"      => false,
			"Inicio"      => "Selecione a Categoria",
			"Vetor"       => array(),
			"ValidaJS"    => true);
			
			if($Conteiner == true) $Array['Conteiner'] = "cCategoriaPai";
			
			$R["CategoriaPai"] = parent::listaVetor($Array,true);
		}
		
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