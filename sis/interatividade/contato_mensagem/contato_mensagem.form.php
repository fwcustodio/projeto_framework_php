<?
include_once($_SESSION['FMBase'].'form_campos.class.php');
include_once($_SESSION['FMBase'].'ajax.class.php');

class MensagemForm extends FormCampos
{
	public function MensagemForm()
	{
		parent::FormCampos();
	}

	public function getFormFiltro()
	{
		$Metodo = "GET";
		
		//Campos de Filtro
		parent::setModFiltro(true);
		$ContatoDepartamentoCod = parent::listBox(array(
		"Nome"        => "ContatoDepartamentoCod",
		"Identifica"  => "Departamento",
		"Valor"       => parent::retornaValor($Metodo,"ContatoDepartamentoCod"),
		"Inicio"	  => "Todos",
		"Status"      => true,
		"ValidaJS"    => false,
		"Tabela"      => "contato_departamento",
		"CampoCod"    => "ContatoDepartamentoCod",
		"CampoDesc"   => "Departamento"),false);
		parent::setFiltro(true,"Departamento:",$ContatoDepartamentoCod,1);

		$AssuntoCod = parent::listBox(array(
		"Nome"        => "ContatoAssuntoCod",
		"Identifica"  => "Assunto",
		"Valor"       => parent::retornaValor($Metodo,"ContatoAssuntoCod"),
		"Inicio"	  => "Todos",
		"Status"      => true,
		"ValidaJS"    => false,
		"Tabela"      => "contato_assunto",
		"CampoCod"    => "ContatoAssuntoCod",
		"CampoDesc"   => "Assunto"),false);
		parent::setFiltro(true,"Assunto:",$AssuntoCod,1);

		$Criacao = parent::inputData(array(
		"Nome"        => "Criacao",
		"Identifica"  => "Criacao",
		"TipoFiltro"  => "ValorVariavel",
		"Valor"       => parent::retornaValor($Metodo,"Criacao"),
		"Largura"     => 10,
		"Status"      => true,
		"ValidaJS"    => false),false);
		parent::setFiltro(true,"Data de Criação:",$Criacao,2);

		$Status = parent::listaVetor(array(
		"Nome"        => "Status",
		"Identifica"  => "Status",
		"TipoFiltro"  => "ValorFixo",
		"Valor"       => parent::retornaValor($Metodo,"Status"),
		"Inicio"	  => "Todos",
		"Status"      => true,
		"ValidaJS"    => false,
		"Vetor"       => array("L"=>"Lida", "N"=>"Não Lida")),false);
		parent::setFiltro(true,"Status:",$Status,2);


		parent::setModFiltro(false);										
		
		//Botão Padrão de Filtro
		parent::setFiltro(true,null,$this->btFiltrar().'<input type="reset" name="Reset" value="Limpar" /> ',2);

		//Ajax
		$this->ajaxRetorno();
	}
	
	public function getFormManu()
	{
		$Metodo = "POST";
		
		$Op = parent::getOp();
//
//		$R["Id"] = parent::inputHidden(array(
//		"Nome"   => "Id",
//		"Valor"  => parent::retornaValor($Metodo,"Id")),true);
//
//		$R["ContatoDepartamentoCod"] = parent::listBox(array(
//		"Nome"        => "ContatoDepartamentoCod",
//		"Identifica"  => "Departamento",
//		"TipoFiltro"  => "ValorFixo",
//		"Valor"       => parent::retornaValor($Metodo,"ContatoDepartamentoCod"),
//		"Status"      => true,
//		"ValidaJS"    => true,
//		"Tabela"      => "contato_departamento",
//		"Inicio"      => true,
//		"CampoCod"    => "ContatoDepartamentoCod",
//		"CampoDesc"   => "Departamento"),true);
//
//		$R["AssuntoCod"] = parent::listBox(array(
//		"Nome"        => "AssuntoCod",
//		"Identifica"  => "Assunto",
//		"TipoFiltro"  => "ValorFixo",
//		"Valor"       => parent::retornaValor($Metodo,"AssuntoCod"),
//		"Status"      => true,
//		"ValidaJS"    => true,
//		"Tabela"      => "contato_assunto",
//		"Inicio"      => true,
//		"CampoCod"    => "ContatoAssuntoCod",
//		"CampoDesc"   => "Assunto"),true);
//
//		$R["Status"] = parent::listaVetor(array(
//		"Nome"        => "Status",
//		"Identifica"  => "Status",
//		"TipoFiltro"  => "ValorFixo",
//		"Valor"       => parent::retornaValor($Metodo,"Status"),
//		"Inicio"      => true,
//		"Status"      => true,
//		"ValidaJS"    => true,
//		"Vetor"       => array("L"=>"Lida", "NL"=>"Não Lida")),true);
//
//		$R["Observacoes"] = parent::inputHtmlEditor(array(
//		"Nome"        => "Observacoes",
//		"Identifica"  => "Observações",
//		"Valor"       => parent::retornaValor($Metodo,"Observacoes"),
//		"Largura"     => "100%",
//		"Altura"      => 300,
//		"Ferramentas" => "Default",
//		"Tratar"	  => array("L"),
//		"ValidaJS"    => false),true);
		
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
		"Nome"       => "sis_marcar_lida", 
		"URL"        => MODULO.".ajax.php?Op=Lid",
		"Form"       => "FormGrid",
		"Metodo"     => "POST",
		"Completa"   => "function(Req){ retornoLida(Req.responseText); } ")));
		
		parent::setFuncoes($Ajax->ajaxRequest(array(
		"Nome"       => "sis_marcar_naolida", 
		"URL"        => MODULO.".ajax.php?Op=Nli",
		"Form"       => "FormGrid",
		"Metodo"     => "POST",
		"Completa"   => "function(Req){ retornoNaoLida(Req.responseText); } ")));


		parent::setFuncoes('
		function visualiza(p ) {  
			PARVIS = (p != null) ? p+"&"+$(\'#FormGrid\').fastSerialize() : $(\'#FormGrid\').fastSerialize(); requestvisualiza(); 
		} 
		
		function requestvisualiza() { 
			$.ajax({ 
			   url:\'contato_mensagem.ajax.php?Op=Vis\', 
			   cache: true, 
			   type: \'POST\', 
			   datatype:\'html\',
			   data: PARVIS,
			   success:function(Req)
			   { 
				  $(\'#manu\').html(Req); 
					sis_filtrar();
				  } 
			   })
		}

						   
		function retornoLida(ret)
		{
			eval(ret);
	
			//Variaveis
			var se = parseInt(retorno["selecionados"]);
			var pb = parseInt(retorno["lida"]);
			var ms = retorno["mensagem"];
			
			//Mensagem
			var possivelMensagem = (ms != "" && ms!="undefined") ? "Motivo:\n"+ms : ms;
			
			//Interpretação
			if(pb > 0)
			{
				if(pb != se)
				{
					var msgPlural = (pb == 1) ? "foi marcado como Lido" : "foram marcados como Lido";
					var msgRemovidos = "Entre os "+se+" registros selecionados "+pb+" "+msgPlural+".\n\n";
					alert("Atenção, nem todos os registros foram marcados como \"Lido\"!\n\n"+msgRemovidos+possivelMensagem);
					
					sis_busca_filtro();
				}
				else
				{
					var plural = (pb == 1) ? "" : "s";
					alert("Registro"+plural+" marcado"+plural+" como Lido"+plural+"!"); 
					sis_busca_filtro();
				}
			}
			else
			{
				alert("Atenção nenhum registro selecionado pode ser marcado como Lido!\n\n"+possivelMensagem);
			}
		}
		

		function retornoNaoLida(ret)
		{
			eval(ret);
	
			//Variaveis
			var se  = parseInt(retorno["selecionados"]);
			var npb = parseInt(retorno["naolida"]);
			var ms  = retorno["mensagem"];
			
			//Mensagem
			var possivelMensagem = (ms != "" && ms!="undefined") ? "Motivo:\n"+ms : ms;
			
			//Interpretação
			if(npb > 0)
			{
				if(npb != se)
				{
					var msgPlural = (npb == 1) ? "foi marcado como Não Lido" : "foram marcados como Não Lido";
					var msgRemovidos = "Entre os "+se+" registros selecionados "+npb+" "+msgPlural+".\n\n";
					alert("Atenção, nem todos os registros foram marcados como \"Não Lido\"!\n\n"+msgRemovidos+possivelMensagem);
					
					sis_busca_filtro();
				}
				else
				{
					var plural = (npb == 1) ? "" : "s";
					alert("Registro"+plural+" marcado"+plural+" como Não Lido"+plural+"!"); 
					sis_busca_filtro();
				}
			}
			else
			{
				alert("Atenção nenhum registro selecionado pode ser marcado como Não Lido!\n\n"+possivelMensagem);
			}
		}		
		
		');

		parent::setFuncoes('$(document.body).ready(function(){ sis_filtrar();  $("#FormFiltro #BtFiltrar").click(function(){ sis_filtrar(); }) });');

	}
}
?>