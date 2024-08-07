<?
include_once($_SESSION['FMBase'].'form_campos.class.php');
include_once($_SESSION['FMBase'].'ajax.class.php');
include_once($_SESSION['DirBase'].'conteudo/secao/secao_pai/secao_pai.class.php');

class SecaoForm extends FormCampos
{
	public function __construct() {
            parent::FormCampos();
	}

	public function getFormFiltro()
	{
		$Metodo = "GET";
		
		//Campos de Filtro
		parent::setModFiltro(true);
		
		$SecaoGrupoCod = parent::listBox(array(
		"Nome"        => "SecaoGrupoCod",
		"Identifica"  => "Grupo",
		"TipoFiltro"  => "ValorFixo",
		"Valor"       => parent::retornaValor($Metodo,"SecaoGrupoCod"),
		"Status"      => true,
		"Inicio"      => 'Todos',
		"Tabela"      => "secao_grupo",
		"CampoCod"    => "SecaoGrupoCod",
		"CampoDesc"   => "SecaoGrupoNome"),false);
		parent::setFiltro(true,"Grupo Para:",$SecaoGrupoCod,1);

		$SecaoNome = parent::inputSuggest(array(
		"Nome"        => "SecaoNome",
		"Identifica"  => "Nome da Seção",
		"TipoFiltro"  => "Suggest",
		"Valor"       => parent::retornaValor($Metodo,"SecaoNome"),
		"Largura"     => 30,
		"Tabela"      => "secao",
		"Campo"       => "SecaoNome"),false);
		parent::setFiltro(true,"Nome da Seção:",$SecaoNome,1);

		$Publicar = parent::listaVetor(array(
		"Nome"        => "Publicar",
		"Identifica"  => "Publicar",
		"TipoFiltro"  => "ValorFixo",
		"Inicio"      => 'Sim e Não',
		"Ordena"      => false,
		"Valor"       => parent::retornaValor($Metodo,"Publicar"),
		"Status"      => true,
		"Vetor"       => array('S'=>'Sim','N'=>'Não')),false);
		parent::setFiltro(true,"Publicar:",$Publicar,2);

		$ExibirMenu = parent::listaVetor(array(
		"Nome"        => "ExibirMenu",
		"Identifica"  => "ExibirMenu",
		"TipoFiltro"  => "ValorFixo",
		"Inicio"      => 'Todos',
		"Ordena"      => false,
		"Valor"       => parent::retornaValor($Metodo,"ExibirMenu"),
		"Status"      => true,
		"Vetor"       => array('S'=>'Sim','N'=>'Não')),false);
		parent::setFiltro(true,"Exibir no Menu:",$ExibirMenu,2);
	
		$FilhosDe = parent::inputSuggest(array(
		"Nome"        => "FilhosDe",
		"Identifica"  => "Filhos De",
		"Valor"       => parent::retornaValor($Metodo,"FilhosDe"),
		"Largura"     => 30,
                "TipoFiltro"  => "Suggest",
		"Tabela"      => "secao",
		"Campo"       => "SecaoNome"),false);
		parent::setFiltro(true,"Filhos De:",$FilhosDe,3);

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
		
		
		$R["SecaoPosicao"] = parent::listaVetor(array(
		"Nome"        => "SecaoPosicao",
		"Identifica"  => "Posição",
		"Valor"       => parent::retornaValor($Metodo,"SecaoPosicao"),
		"Inicio"      => '?',
		"Status"      => true,
		"Ordena"      => false,
		"ValidaJS"    => true,
		"Vetor"       => array_combine(range(1,99),range(1,99))),true);		
		
		$R["SecaoGrupoCod"] = parent::listBox(array(
		"Nome"        => "SecaoGrupoCod",
		"Identifica"  => "Grupo",
		"Valor"       => parent::retornaValor($Metodo,"SecaoGrupoCod"),
		"Padrao"      => 1,
		"Status"      => true,
		"ValidaJS"    => true,
		"Inicio"      => true,
		"ValidaJS"    => true,
		"Tabela"      => "secao_grupo",
		"CampoCod"    => "SecaoGrupoCod",
		"CampoDesc"   => "SecaoGrupoNome",
		"Adicional"   => "onChange=\"chamaReferencia('".parent::getNomeForm()."','cSecaoPai')\""),true);
		
		//Seção Pai
		$R["SecaoPai"] = parent::listaVetor(array(
		"Nome"        => "SecaoPai",
		"Identifica"  => "Secao Pai",
		"Conteiner"   => "cSecaoPai",
		"Valor"       => parent::retornaValor($Metodo,"SecaoPai"),
		"Status"      => parent::getEnv(),
		"Inicio"      => "Selecione o Grupo",
		"Vetor"       => array()),false);
		
		$R["SecaoNome"] = parent::inputTexto(array(
		"Nome"        => "SecaoNome",
		"Identifica"  => "Nome da Seção",
		"Valor"       => parent::retornaValor($Metodo,"SecaoNome"),
		"Largura"     => 50,
		"Status"      => true,
		"ValidaJS"    => true),true);
				
		parent::listaVetor(array(
		"Nome"        => "Publicar",
		"Identifica"  => "Publicar",
		"Valor"       => parent::retornaValor($Metodo,"Publicar"),
		"Status"      => true,
		"Inicio"      => true,
		"Ordena"      => false,
		"Vetor"       => array('S'=>'Sim','N'=>'Não')),true);
		
		parent::listaVetor(array(
		"Nome"        => "Situacao",
		"Identifica"  => "Situação",
		"Valor"       => parent::retornaValor($Metodo,"Situacao"),
		"Inicio"	  => true,
		"ValidaJS"	  => false,
		"Status"      => true,
		"Vetor"       => array("A"=>"Ativo", "I"=>"Inativo")),true);
		
		$IdForm = parent::retornaValor($Metodo,"Id");
		parent::setStringJS(' if($("#FormManu'.$IdForm.' input[@type=radio][@name=Tipo][@checked]").val() == "C") { ');

		parent::listaVetor(array(
		"Nome"        => "ExibirMenu",
		"Identifica"  => "Exibir no Menu",
		"Valor"       => parent::retornaValor($Metodo,"ExibirMenu"),
		"Status"      => true,
		"Inicio"      => true,
		"Ordena"      => false,
		"Vetor"       => array('S'=>'Sim','N'=>'Não')),$_POST['Tipo'] == "C");
		
		parent::listaVetor(array(
		"Nome"        => "MostrarFilhos",
		"Identifica"  => "Mostrar Filhos",
		"Valor"       => parent::retornaValor($Metodo,"MostrarFilhos"),
		"Status"      => true,
		"Inicio"      => true,
		"Padrao"      => 'N',
		"Ordena"      => false,
		"Vetor"       => array('S'=>'Sim','N'=>'Não')),$_POST['Tipo'] == "C");

		$R["SecaoConteudo"] = parent::inputHtmlEditor(array(
		"Nome"        => "SecaoConteudo",
		"Identifica"  => "Conteúdo",
		"Valor"       => parent::retornaValor($Metodo,"SecaoConteudo"),
		"Largura"     => "100%",
		"Altura"      => 400,
		"Tratar"      => array("L"),
		"Ferramentas" => "Custon1",
		"ValidaJS"    => false),$_POST['Tipo'] == "C");
		
		parent::setStringJS(' } ');
		
		$R["LinkTipo"] = parent::listaVetor(array(
		"Nome"        => "LinkTipo",
		"Identifica"  => "Tipo de Link",
		"Valor"       => parent::retornaValor($Metodo,"LinkTipo"),
		"Status"      => true,
		"Ordena"      => false,
		"Padrao"      => 'http://',
		"Vetor"       => array('http://'  => 'http://',
							   'https://' => 'https://',
							   'mailto:'  => 'Email',
							   ''         => 'Outros')),false);
		$R["Link"] = parent::inputTexto(array(
		"Nome"        => "Link",
		"Identifica"  => "Link",
		"Valor"       => parent::retornaValor($Metodo,"Link"),
		"Largura"     => 35,
		"Status"      => true), $_POST['Tipo'] == "L");
		
		$R["LinkTarget"] = parent::listaVetor(array(
		"Nome"        => "LinkTarget",
		"Identifica"  => "Target",
		"Valor"       => parent::retornaValor($Metodo,"LinkTarget"),
		"Status"      => true,
		"Inicio"      => false,
		"Ordena"      => false,
		"Vetor"       => array('_self'=>'Mesma Página','_blank'=>'Página em Branco')),false);
		
		$R["AutorNome"]  = parent::inputSuggest(array(
		"Nome"        => "AutorNome",
		"Identifica"  => "AutorNome",
		"Valor"       => parent::retornaValor($Metodo,"AutorNome"),
		"Largura"     => 50,
		"Tabela"      => "autor",
		"Campo"       => "AutorNome",
		"Hidden"      => "AutorCod",
		"Url"         => $_SESSION['UrlBase'].'cadastros/autor/autor.ajax.php?Op=BuscaNome'),false);
		
		$R["AutorCod"] = parent::inputHidden(array(
		"Nome"   => "AutorCod",  
		"Valor"  => parent::retornaValor($Metodo,"AutorCod")),true);
		
		/* Acho que isso num precisa mais mais num tenho saco de ver :D
		$R["HiddenBlock"] = parent::inputHidden(array(
		"Nome"   => "HiddenBlock",  
		"Valor"  => parent::retornaValor($Metodo,"HiddenBlock")),true);*/
		
		return $R;	
	}

	public function getFormSecaoPai($Codigo, $Conteiner = false)
	{
		$Metodo 	= "POST";
		$Op 		= parent::getOp();
		
		if(!empty($Codigo))
		{				
			try
			{ 
				//Campo Secao Pai
				$SecaoPai = new SecaoPai();
				
				$R["SecaoPai"] = $SecaoPai->geraCampoSecao("SecaoPai",parent::retornaValor($Metodo,"SecaoPai"), $Codigo,"Não Informar...");
				
				if($Conteiner == true)
				{
					$R["SecaoPai"] = '<div id="cSecaoPai">'.$R["SecaoPai"].'</div>';
				}
			}
			catch (Exception $E)
			{
				
			}
		}
		else
		{			
			$Array = array(
			"Nome"        => "SecaoPai",
			"Identifica"  => "Seção Pai",
			"Valor"       => parent::retornaValor($Metodo,"SecaoPai"),
			"Status"      => false,
			"Inicio"      => "Selecione o Grupo",
			"Vetor"       => array(),
			"ValidaJS"    => true);
			
			if($Conteiner == true) $Array['Conteiner'] = "cSecaoPai";
			
			$R["SecaoPai"] = parent::listaVetor($Array,true);
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

		parent::setFuncoes($Ajax->ajaxLoad(array(
		"Nome"       => "chamaReferencia", 
		"URL"        => MODULO.".ajax.php?Op=SecaoPai",
		"Parametros" => "{'SecaoGrupoCod':$('#'+form+' #SecaoGrupoCod').val()}")));
		
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
		function buscaEnquete(idForm)      { window.open(\''.$_SESSION['UrlBase'].'interatividade/enquete/enquete.pop.php?TipoCampo=Enquete&IdForm=\'+idForm,\'JEnquete\',\'toolbar=no,location=no,directories=no,status=yes,menubar=no,scrollbars=yes,resizable=no,width=650,height=550\'); }
		
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
				$.ajax({url:"'.$_SESSION['UrlBase'].'conteudo/up_arquivos/up_arquivos.ajax.php?Op=GetArquivo",
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
				$.ajax({url:"'.$_SESSION['UrlBase'].'conteudo/galeria_midia/galeria_midia.ajax.php?Op=GetGaleriaMidia",
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
		
		/*ENQUETE*/		
		parent::setFuncoes('
		function getEnquete(idForm, enqueteCod)
		{  
			//verifica se já não existe
			if( $("#FormManu"+idForm+" #ArrayEnqueteCod"+enqueteCod).length <= 0)
			{
				$.ajax({url:"'.$_SESSION['UrlBase'].'interatividade/enquete/enquete.ajax.php?Op=GetEnquete",
						type: "POST",
						datatype:"html",
						data: {"EnqueteCod":enqueteCod},
						complete:function(Req){ $("#FormManu"+idForm+" #cTEnquete").append(Req.responseText) }  
						});
			}
			else
			{
				alert("Você não pode selecionar a mesma enquete mais de uma vez!");
			}
		}		
		');
		

		/*POSICOES GRID*/		
		parent::setFuncoes('
		function secaoPosicao(secaoCod, operacao)
		{  
			//Recupera Posicao Atual
			var posicao = $("#sis_grid_posicao"+secaoCod).text();
			
			//Limpa Posicao (espacos em branco)
			posicao = $.trim(posicao);
			
			//Valida Posicao
			if(posicao == "99" && operacao == "+") { alert("A Posição máxima é 99!"); return; }
			if(posicao == "1"  && operacao == "-") { alert("A Posição mínima é 1!");  return; }
			
			$.get("secao.ajax.php?Op=MudaPosicao",{"SecaoCod":secaoCod, "Posicao":posicao, "Operacao":operacao},
			function(retorno)
			{
				if(retorno == "true")
				{
					$("#sis_grid_posicao"+secaoCod).text(eval(posicao+" "+operacao+" 1"));
				}
				else
				{
					alert(retorno);
				}
			});
		}		
		');		
		
		parent::setFuncoes('$(document.body).ready(function(){ sis_filtrar();  $("#FormFiltro #BtFiltrar").click(function(){ sis_filtrar(); }) });');
	}
}
?>
