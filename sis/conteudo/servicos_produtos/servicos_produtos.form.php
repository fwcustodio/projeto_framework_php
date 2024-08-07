<?
include_once($_SESSION['FMBase'].'form_campos.class.php');
include_once($_SESSION['FMBase'].'ajax.class.php');
include_once($_SESSION['DirBase'].'conteudo/servicos_categoria/servicos_nivel/servicos_nivel.class.php');

class ServicoForm extends FormCampos
{
	public function __construct() {
            parent::FormCampos();
	}

	public function getFormFiltro()
	{
		$Metodo = "GET";
		
		//Campos de Filtro
		parent::setModFiltro(true);
		$ServicoCategoriaCod = parent::listBox(array(
		"Nome"        => "ServicoCategoriaCod",
		"Identifica"  => "Categoria",
		"Valor"       => parent::retornaValor($Metodo,"ServicoCategoriaCod"),
		"Status"      => true,
		"ValidaJS"    => false,
		"Inicio"	  => true,	
		"Tabela"      => "servico_categoria",
		"CampoCod"    => "ServicoCategoriaCod",
		"CampoDesc"   => "ServicoCategoriaNome"),false);
		parent::setFiltro(true,"Categoria:",$ServicoCategoriaCod,1);

		$ServicoNome = parent::inputSuggest(array(
		"Nome"        => "ServicoNome",
		"Identifica"  => "Servico/Produto",
		"TipoFiltro"  => "Suggest",
		"Valor"       => parent::retornaValor($Metodo,"ServicoNome"),
		"Largura"     => 20,
		"Tabela"      => "servico_produto",
		"Campo"       => "ServicoNome"),false);
		parent::setFiltro(true,"Nome:",$ServicoNome,1);

		$ServicoPublicar = parent::listaVetor(array(
		"Nome"        => "ServicoPublicar",
		"Identifica"  => "Publicar",
		"Valor"       => parent::retornaValor($Metodo,"ServicoPublicar"),
		"Status"      => true,
		"ValidaJS"    => false,
		"Inicio"	  => true,	
		"Vetor"       => array('S'=>'Sim', 'N'=>'Não')),false);
		parent::setFiltro(true,"Publicar:",$ServicoPublicar,1);
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
		//$SecaoPai = new SecaoPai();
		
		$R["Id"] = parent::inputHidden(array(
		"Nome"   => "Id",  
		"Valor"  => parent::retornaValor($Metodo,"Id")),true);
		
		$ServicoNivel = new ServicoNivel();
		
		$R["ServicoCategoriaCod"] = $ServicoNivel->geraCampoSecao("ServicoCategoriaCod",parent::retornaValor($Metodo,"ServicoCategoriaCod"), "Selecione uma Categoria...");	
			
		parent::inputTexto(array(
		"Nome"        => "ServicoCategoriaCod",
		"Identifica"  => "Categoria de Serviço",
		"Valor"       => parent::retornaValor($Metodo,"ServicoCategoriaCod"),
		"Status"      => true,
		"ValidaJS"    => true),true);


                $R["ServicoPosicao"] = parent::listaVetor(array(
		"Nome"        => "ServicoPosicao",
		"Identifica"  => "Posição",
		"Valor"       => parent::retornaValor($Metodo,"ServicoPosicao"),
		"Inicio"      => '?',
		"Status"      => true,
		"Ordena"      => false,
		"ValidaJS"    => true,
		"Vetor"       => array_combine(range(1,99),range(1,99))),true);


		$R["ServicoNome"] = parent::inputTexto(array(
		"Nome"        => "ServicoNome",
		"Identifica"  => "Nome",
		"Valor"       => parent::retornaValor($Metodo,"ServicoNome"),
		"Largura"     => 80,
		"Max"         => 150,
		"Status"      => true,
		"ValidaJS"    => true),true);

	
		$R["ServicoDescricao"] = parent::inputHtmlEditor(array(
		"Nome"        => "ServicoDescricao",
		"Identifica"  => "Descricao",
		"Valor"       => parent::retornaValor($Metodo,"ServicoDescricao"),
		"Largura"     => "100%",
		"Altura"      => 400,
		"Ferramentas" => "Custon1",
		"Tratar"      => array("L"),
		"ValidaJS"    => false),true);

		parent::listaVetor(array(
		"Nome"        => "ServicoPublicar",
		"Identifica"  => "Publicar",
		"Valor"       => parent::retornaValor($Metodo,"ServicoPublicar"),
		"Status"      => true,
		"Inicio"      => true,
		"Ordena"      => false,
		"Vetor"       => array('S'=>'Sim','N'=>'Não')),true);
		
		parent::listaVetor(array(
		"Nome"        => "ServicoSituacao",
		"Identifica"  => "Situação",
		"Valor"       => parent::retornaValor($Metodo,"ServicoSituacao"),
		"Inicio"	  => true,
		"ValidaJS"	  => false,
		"Status"      => true,
		"Vetor"       => array("A"=>"Ativo", "I"=>"Inativo")),true);

		$R["ImagemServico"] = parent::uploadMultiploJQuery(array(
		"Nome"        => "ImagemServico",
		"Identifica"  => "ImagemServico",
		"Tipos"  	  => array('gif', 'jpg'),
		"Max" 	      => 1,
		"Status"      => true,
		),false);
		

		$R["ImagemServicoHomepage"] = parent::uploadMultiploJQuery(array(
		"Nome"        => "ImagemServicoHomepage",
		"Identifica"  => "ImagemServicoHomepage",
		"Tipos"  	  => array('gif', 'jpg'),
		"Max" 	      => 1,
		"Status"      => true,
		),false);
		
	
		return $R;	
	}
	
	public function getFormManuIntro()
	{
		$Metodo = "POST";
		
		$Op = parent::getOp();
		
		$R["TextoIntroducao"] = parent::inputHtmlEditor(array(
		"Nome"        => "TextoIntroducao",
		"Identifica"  => "Texto de Introdução",
		"Valor"       => parent::retornaValor($Metodo,"TextoIntroducao"),
		"Largura"     => "100%",
		"Altura"      => 400,
		"Ferramentas" => "Custon1",
		"Tratar"      => array("L"),
		"ValidaJS"    => true),false);

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
		
		parent::setFuncoes($Ajax->ajaxRequestForm(array(
		"Nome"       => "alteraBdIntro", 
		"URL"        => MODULO.".ajax.php?Op=TextIntro&Env=true",
		"Metodo"     => "POST",
		"Completa"   => "function(Req){ retornoAlterar(Req.responseText, conteiner); } ")));	
		
		parent::setFuncoes($Ajax->ajaxRequest(array(
		"Nome"       => "sis_apagar", 
		"URL"        => MODULO.".ajax.php?Op=Del",
		"Form"       => "FormGrid",
		"Metodo"     => "POST",
		"Completa"   => "function(Req){ retornoRemover(Req.responseText); } ")));

		parent::setFuncoes('
		function verificaTipo(idForm)
		{			
			if($("#FormManu"+idForm+" input[@type=radio][@name=Tipo][@checked]").val() == "C")
			{
				$("#FormManu"+idForm+" tr[id^=\'conteudo\']").each(
				function() 
				{ 
					$(this).show();	
				});
				
				$("#FormManu"+idForm+" #tabelaAnexo").show();
				
				$("#FormManu"+idForm+" #linkConteudo").hide();
			}
			else
			{
				$("#FormManu"+idForm+" tr[id^=\'conteudo\']").each(
				function() 
				{ 
					$(this).hide();	
				});
				
				$("#FormManu"+idForm+" #tabelaAnexo").hide();
				
				$("#FormManu"+idForm+" #linkConteudo").show();
			}
		}
		
		//Publicar
		function secaoPub(op)
		{  
			if(op == "N")
			{
				if(!confirm("Você confima a retirada de publicação dos registros selecionados?")) return;
				
				var opcao = "NPub"; 
			}
			else
			{
				var opcao = "Pub";
			}
			
			$.ajax({ url:"'.MODULO.'.ajax.php?Op="+opcao, datatype:"html",type: "POST", data: $("#FormGrid").fastSerialize(), complete:function(Req)
			{ 
				if(op == "N")
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
		
		/* BUSCAR INTENS PARA AS SECOES*/
		function buscaArquivo(idForm)      { window.open(\''.$_SESSION['UrlBase'].'conteudo/up_arquivos/up_arquivos.pop.php?TipoCampo=Arquivo&IdForm=\'+idForm,\'JArquivo\',\'toolbar=no,location=no,directories=no,status=yes,menubar=no,scrollbars=yes,resizable=no,width=650,height=550\'); }
		function buscaGaleriaMidia(idForm) { window.open(\''.$_SESSION['UrlBase'].'conteudo/galeria_midia/galeria_midia.pop.php?TipoCampo=GaleriaMidia&IdForm=\'+idForm,\'JGaleriaMidia\',\'toolbar=no,location=no,directories=no,status=yes,menubar=no,scrollbars=yes,resizable=no,width=650,height=550\'); }
		function buscaEnquete(idForm)      { window.open(\''.$_SESSION['UrlBase'].'conteudo/enquete/enquete.pop.php?TipoCampo=Enquete&IdForm=\'+idForm,\'JEnquete\',\'toolbar=no,location=no,directories=no,status=yes,menubar=no,scrollbars=yes,resizable=no,width=650,height=550\'); }
		
		function retornoPop(idForm, tipo, valor)
		{
			if(tipo == "Arquivo")
			{
				getArquivo(idForm, valor);
			}
			else if(tipo == "GaleriaMidia")
			{
				getGaleriaMidia(idForm, valor);
			}
			else if(tipo == "Enquete")
			{
				getEnquete(idForm, valor);
			}
		}
		');


/*Arquivos*/		
		parent::setFuncoes('
		function getArquivo(idForm, arquivoCod)
		{  
			//verifica se já não existe
			if( $("#FormManu"+idForm+" #ArrayArquivoCod"+arquivoCod).length <= 0)
			{
				$.ajax({url:URLBASE+"conteudo/up_arquivos/up_arquivos.ajax.php?Op=GetArquivo",
						type: "POST",
						datatype:"html",
						data: {"ArquivoCod":arquivoCod},
						complete:function(Req){ $("#FormManu"+idForm+" #cTArquivo").append(Req.responseText) }  
						});
			}
			else
			{
				alert("Você não pode selecionar o mesmo arquivo mais de uma vez!");
			}
		}		
		');			

		/*GALERIA DE MIDIA*/		
		parent::setFuncoes('
		function getGaleriaMidia(idForm, galeriaMidiaCod)
		{  
			//verifica se já não existe
			if( $("#FormManu"+idForm+" #ArrayGaleriaMidiaCod"+galeriaMidiaCod).length <= 0)
			{
				$.ajax({url:URLBASE+"conteudo/galeria_midia/galeria_midia.ajax.php?Op=GetGaleriaMidia",
						type: "POST",
						datatype:"html",
						data: {"GaleriaMidiaCod":galeriaMidiaCod},
						complete:function(Req){ $("#FormManu"+idForm+" #cTGaleriaMidia").append(Req.responseText) }  
						});
			}
			else
			{
				alert("Você não pode selecionar a mesma galeria de midia mais de uma vez!");
			}
		}		
		');			
					
		parent::setFuncoes('
		function sis_textointro()
		{
			$.ajax({url: "'.MODULO.'.ajax.php?Op=TextIntro",
					type: "POST",
					datatype: "html",
					data: {"demp":"demp"},
					complete: function(Req){ 
								$("#manu").html(Req.responseText);
							  }  
					});
		}
		');
		
		parent::setFuncoes('$(document.body).ready(function(){ sis_filtrar();  $("#FormFiltro #BtFiltrar").click(function(){ sis_filtrar(); }) });');
	
		
	}
}