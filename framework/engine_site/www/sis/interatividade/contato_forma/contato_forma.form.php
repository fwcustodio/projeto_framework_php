<?
include_once($_SESSION['FMBase'].'form_campos.class.php');
include_once($_SESSION['FMBase'].'ajax.class.php');
include_once($_SESSION['DirBase'].'interatividade/endereco/endereco.form.php');
include_once($_SESSION['DirBase'].'interatividade/contato/contato.form.php');


class ContatoFormaForm extends FormCampos
{
	public function ContatoFormaForm()
	{
		parent::FormCampos();
	}

	public function getFormFiltro()
	{
		$Metodo = "GET";

		$Titulo = parent::inputSuggest(array(
		"Nome"        => "Titulo",
		"Identifica"  => "Titulo",
		"TipoFiltro"  => "Suggest",
		"Valor"       => parent::retornaValor($Metodo,"Titulo"),
		"Largura"     => 40,
		"Tabela"      => "contato_contato",
		"Campo"       => "Titulo"),false);
		parent::setFiltro(true,"Titulo:",$Titulo,1);



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

		$R["Titulo"] = parent::inputTexto(array(
		"Nome"        => "Titulo",
		"Identifica"  => "Titulo",
		"Valor"       => parent::retornaValor($Metodo,"Titulo"),
		"Largura"     => 40,
		"Max"		  => 70,
		"Status"      => true,
		"ValidaJS"    => true),true);
		
		$R["Descricao"] = parent::textArea(array(
		"Nome"        => "Descricao",
		"Identifica"  => "Descricão",
		"Valor"       => parent::retornaValor($Metodo,"Descricao"),
		"Linhas"      => 4,
		"Colunas"     => 30,
		"Status"      => true,
		"ValidaJS"    => false),false);
		
		return $R;	
	}
	
	/*
	Formulário de Contato
	*/
	public function getFormContato($Cont = null)
	{
		$FormCont = new ContatoForm($this);
		
		$R  = $FormCont->getFormContato($Cont);
		$R += $FormCont->getFormTipo($Cont);
		
		return $R;
	}
	
	public function getFormDadosContato($Cont = null)
	{
		$FormCont = new ContatoForm($this);
		
		$R = $FormCont->getFormContato($Cont);
		
		return $R;
	}	

	public function getFormTipoContato($Cont = null)
	{
		$FormCont = new ContatoForm($this);
		
		$R = $FormCont->getFormTipo($Cont);
		
		return $R;
	}
	
	/*
	Formulário de Endereços
	*/
	public function getFormEndereco($Cont = null)
	{
		$FormEnd = new EnderecoForm($this);
		
		return $FormEnd->getFormManu($Cont);	
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
		"Nome"       => "addEndereco", 
		"ParInicial" => "idForm",
		"Parametros" => "{IdForm:idForm}",
		"URL"        => MODULO.".ajax.php?Op=End",
		"Metodo"     => "POST",
		"Completa"   => "function(Req){ retornoEndereco(idForm, Req.responseText); } ")));	
	
		parent::setFuncoes($Ajax->ajaxRequest(array(
		"Nome"       => "addContato", 
		"ParInicial" => "idForm",
		"Parametros" => "{IdForm:idForm}",
		"URL"        => MODULO.".ajax.php?Op=Contato",
		"Metodo"     => "POST",
		"Completa"   => "function(Req){ retornoContato(idForm, Req.responseText); } ")));	

		parent::setFuncoes($Ajax->ajaxRequest(array(
		"Nome"       => "addTipoContato", 
		"ParInicial" => "cont, idForm",
		"Parametros" => "{Cont:cont, IdForm:idForm}",
		"URL"        => MODULO.".ajax.php?Op=TipoContato",
		"Metodo"     => "POST",
		"Completa"   => "function(Req){ retornoTipoContato(cont, idForm, Req.responseText); } ")));		


		parent::setFuncoes('$(document.body).ready(function(){ sis_filtrar();  $("#FormFiltro #BtFiltrar").click(function(){ sis_filtrar(); }) });');
			
		parent::SetFuncoes('
		
		
		//Tipo Pessoa
		function tipoPessoa(idForm)
		{
			var load;
			var valor = $("#FormManu"+idForm).val();
			if(valor == \'\') return $("#FormManu"+idForm).empty();
			load = (valor == "PF") ? "cliente.ajax.php?Op=PF" : "cliente.ajax.php?Op=PJ";
			$("#FormManu"+idForm+" #conteinerTipoPessoa").load(load);
		}
		
		//Endereço
		function retornoEndereco(idForm, req)
		{
			$("#FormManu"+idForm+" #conteinerEndereco").append(req);
		}
		
		//Removendo Endereco
		function removeEndereco(idForm, cont)
		{
			var conta = 0;
			
			$("#FormManu"+idForm+" [@name^=EnderecoDadosTipoCod]").each(function() { conta += 1; });
		
			if(conta <= 1)
			{
					alert("Deve haver ao menos um endereço!");
					return;
			}	

			$("#endereco"+cont).remove();
		}
		
		
		//Contato
		function retornoContato(idForm, req)
		{
			$("#FormManu"+idForm+" #conteinerContato").append(req);
		}
		
		//Adicionar Tipo Contato
		function retornoTipoContato(cont, idForm, req)
		{
			$("#FormManu"+idForm+" #conteinerTipoContato"+cont).append(req);
		}
		
		//Removendo contato
		function removeContato(idForm, cont)
		{
			var conta = 0;
			
			$("#FormManu"+idForm+" [@name^=NomeContato]").each(function() { conta += 1; });
		
			if(conta <= 1)
			{
					alert("Deve haver ao menos um contato!");
					return;
			}	

			$("#contato"+cont).remove();
		}
		
		
		//Remove tipo contato
		function removeTipoContato(idForm, cont,  objeto)
		{
			var conta = 0;
			
			$("#FormManu"+idForm+" #contato"+cont+" [@name^=ContatoCategoriaCod]").each(function() { conta += 1; });	
			
			if(conta <= 1)
			{
					alert("Deve haver ao menos um tipo de contato!");
					return;
			}
			
			$(objeto).parent().parent().remove();
		}
		
		');
	}

}
?>