<?
include_once($_SESSION['FMBase'].'form_campos.class.php');
include_once($_SESSION['FMBase'].'ajax.class.php');

class DepartamentoForm extends FormCampos
{
	public function DepartamentoForm()
	{
		parent::FormCampos();
	}

	public function getFormFiltro()
	{
		$Metodo = "GET";
		
		//Campos de Filtro
		parent::setModFiltro(true);
		$Departamento = parent::inputSuggest(array(
		"Nome"        => "Departamento",
		"Identifica"  => "Departamento",
		"TipoFiltro"  => "Suggest",
		"Valor"       => parent::retornaValor($Metodo,"Departamento"),
		"Largura"     => 20,
		"Tabela"      => "contato_departamento",
		"Campo"       => "Departamento"),false);
		parent::setFiltro(true,"Departamento:",$Departamento,1);

		$Status = parent::listaVetor(array(
		"Nome"        => "Status",
		"Identifica"  => "Status",
		"TipoFiltro"  => "ValorFixo",
		"Valor"       => parent::retornaValor($Metodo,"Status"),
		"Inicio"	  => "Todos",
		"Status"      => true,
		"ValidaJS"    => false,
		"Vetor"       => array("A"=>"Ativo", "I"=>"Inativo")),false);
		parent::setFiltro(true,"Status:",$Status,1);
	/*	
		$Finalidade = parent::listaVetor(array(
		"Nome"        => "Finalidade",
		"Identifica"  => "Finalidade",
		"TipoFiltro"  => "ValorFixo",
		"Valor"       => parent::retornaValor($Metodo,"Finalidade"),
		"Inicio"	  => "Todos",
		"Status"      => true,
		"ValidaJS"    => false,
		"Vetor"       => array("Co"=>"Contato", "Pr"=>"Procura Por Imóvel")),false);
		parent::setFiltro(true,"Finalidade:",$Status,2);

*/
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
		
		$R["Departamento"] = parent::inputTexto(array(
		"Nome"        => "Departamento",
		"Identifica"  => "Departamento",
		"TipoFiltro"  => "Texto",
		"Valor"       => parent::retornaValor($Metodo,"Departamento"),
		"Largura"     => 20,
		"Max"		  => 250,
		"Status"      => true,
		"ValidaJS"    => true),true);
		
		$R["Status"] = parent::listaVetor(array(
		"Nome"        => "Status",
		"Identifica"  => "Status",
		"TipoFiltro"  => "ValorFixo",
		"Valor"       => parent::retornaValor($Metodo,"Status"),
		"Inicio"      => true,
		"Status"      => true,
		"ValidaJS"    => true,
		"Vetor"       => array("A"=>"Ativo", "I"=>"Inativo")),true);
		/*
		$R["Finalidade"] = parent::listaVetor(array(
		"Nome"        => "Finalidade",
		"Identifica"  => "Finalidade",
		"TipoFiltro"  => "ValorFixo",
		"Valor"       => parent::retornaValor($Metodo,"Finalidade"),
		"Inicio"      => true,
		"Status"      => true,
		"ValidaJS"    => true,
		"Vetor"       => array("Co"=>"Contato", "Pr"=>"Procura Por Imóvel")),true);
*/

		
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

	public function getFormDados($Cod)
	{
		$Metodo = "POST";
		
		$R["ContadorDados"] = parent::inputHidden(array(
		"Nome"   => "ContadorDados[".$Cod."]",  
		"Valor"  => $Cod),false);

		$R["UsuarioCod"] = parent::listBox(array(
		"Nome"        => "UsuarioCod".$Cod,
		"Identifica"  => "Usuario",
		"TipoFiltro"  => "ValorFixo",
		"Valor"       => parent::retornaValor($Metodo,"UsuarioCod".$Cod),
		"Status"      => true,
		"ValidaJS"    => false,
		"Inicio"	  => true,
		"Tabela"      => "_usuarios",
		"CampoCod"    => "UsuarioCod",
		"FullSelect"  => "SELECT a.UsuarioCod, CONCAT(UsuarioDadosNome, ' (',Login,')') AS Nome 
						    FROM _usuarios a, usuario_dados b
						   WHERE a.UsuarioCod = b.UsuarioCod
						     AND a.Status = 'A'",
		"Adicional"	  => " style='width:190px' ",
		"CampoDesc"   => "Nome"),true);

		return $R;
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
		
		/*FUNÇÂO PARA DADOS*/
		parent::setFuncoes('

		function addDados(idForm)
		{
			var randJS = Math.random();
			randJS     = randJS+""; 
			randJS     = randJS.replace(".","");
			$.ajax({       url: "contato_departamento.ajax.php?Op=AddDados", 
					      type: "post", 
					      data: {"RandJS":randJS,"Id":idForm}, 
				      datatype: "html",
					   success: function(Req) 
					   { 
					   		$("#FormManu"+idForm+" #conteiner_usuarios").append(Req); 
					   		
					   } 
				   });
		}
		
		function removeItenDado(obj, idForm)
		{
			var conta = 0;
			$("#FormManu"+idForm+" [@name^=UsuarioCod]").each(function() { conta += 1; });

			if(conta <= 1)
			{
					alert("Deve haver ao menos um usuário cadastrado no departamento!");
					return;
			}	
			
			if(confirm("Tem certeza que deseja remover este registro!"))
			{
				$(obj).parent().remove();
			}
		}		
		');	
	}
}
?>