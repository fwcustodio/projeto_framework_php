<?
include_once($_SESSION['FMBase'].'form_campos.class.php');
include_once($_SESSION['FMBase'].'ajax.class.php');

class InfoProdForm extends FormCampos
{
	public function InfoProdForm()
	{
		parent::FormCampos();
	}

	public function getFormFiltro()
	{
		$Metodo = "GET";
		
		//Campos de Filtro
		parent::setModFiltro(true);
	//	$PortifolioNome = parent::inputSuggest(array(
//		"Nome"        => "Nome",
//		"Identifica"  => "Nome",
//		"TipoFiltro"  => "Suggest",
//		"Valor"       => parent::retornaValor($Metodo,"Nome"),
//		"Tabela"      => "portifolio",
//		"Campo"       => "Nome"),false);
//		parent::setFiltro(true,"Nome:",$PortifolioNome,1);

		$Nome = parent::inputSuggest(array(
		"Nome"        => "Nome",
		"Identifica"  => "Nome",
		"TipoFiltro"  => "Suggest",
		"Valor"       => parent::retornaValor($Metodo,"Nome"),
		"Tabela"      => "servicos_informacoes",
		"Campo"       => "Nome",
		"ValidaJS"    => false),false);
		parent::setFiltro(true,"Nome:",$Nome,1);		

		$InformacoesSituacao = parent::listaVetor(array(
		"Nome"        => "Status",
		"Identifica"  => "Status",
		"Valor"       => parent::retornaValor($Metodo,"Status"),
		"Inicio"      => true,
		"ValidaJS"    => false,
		"Status"      => true,
		"Vetor"       => array("L"=>"Lida", "NL"=>"Não Lida")),false);
		parent::setFiltro(true,"Situação:",$InformacoesSituacao,1);


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

		parent::setFuncoes('
		//Publicar
		function secaoLida(op)
		{
			if(op == "NL")
			{
				if(!confirm("Você confima a retirada de publicação dos registros selecionados?")) return;

				var opcao = "NLid";
			}
			else
			{
				var opcao = "Lid";
			}

			$.ajax({ url:"'.MODULO.'.ajax.php?Op="+opcao, datatype:"html",type: "POST", data: $("#FormGrid").fastSerialize(), complete:function(Req)
			{
				if(op == "NL")
				{
					retornoNaoPublicar(Req.responseText);
				}
				else
				{
					retornoPublicar(Req.responseText);
				}
			}
			});
		}

		function retornoPublicar(ret)
		{
			eval(ret);

			//Variaveis
			var se = parseInt(retorno["selecionados"]);
			var pb = parseInt(retorno["publicados"]);
			var ms = retorno["mensagem"];

			//Mensagem
			var possivelMensagem = (ms != "" && ms!="undefined") ? "Motivo:\n"+ms : ms;

			//Interpretação
			if(pb > 0)
			{
				if(pb != se)
				{
					var msgPlural = (pb == 1) ? "apenas foi publicado com sucesso" : "foram publicados com sucesso";
					var msgRemovidos = "Entre os "+se+" registros selecionados "+pb+" "+msgPlural+".\n\n";
					alert("Atenção, nem todos os registros puderam ser publicados!\n\n"+msgRemovidos+possivelMensagem);

					sis_busca_filtro();
				}
				else
				{
					var plural = (pb == 1) ? "" : "s";
					alert("Registro"+plural+" publicado"+plural+" com sucesso!");
					sis_busca_filtro();
				}
			}
			else
			{
				alert("Atenção nenhum registro selecionado pode ser publicado!\n\n"+possivelMensagem);
			}
		}


		function retornoNaoPublicar(ret)
		{
			eval(ret);

			//Variaveis
			var se  = parseInt(retorno["selecionados"]);
			var npb = parseInt(retorno["naoPublicados"]);
			var ms  = retorno["mensagem"];

			//Mensagem
			var possivelMensagem = (ms != "" && ms!="undefined") ? "Motivo:\n"+ms : ms;

			//Interpretação
			if(npb > 0)
			{
				if(npb != se)
				{
					var msgPlural = (npb == 1) ? "apenas foi retirado de publicação" : "foram retirados de publicação com sucesso";
					var msgRemovidos = "Entre os "+se+" registros selecionados "+npb+" "+msgPlural+".\n\n";
					alert("Atenção, nem todos os registros foram retirados de publicação!\n\n"+msgRemovidos+possivelMensagem);

					sis_busca_filtro();
				}
				else
				{
					var plural = (npb == 1) ? "" : "s";
					alert("Registro"+plural+" retirado"+plural+" de publicação com sucesso!");
					sis_busca_filtro();
				}
			}
			else
			{
				alert("Atenção nenhum registro selecionado pode ser retirado de publicação!\n\n"+possivelMensagem);
			}
		}

		');


	}
}
?>