<?
include_once($_SESSION['FMBase'].'form_campos.class.php');
include_once($_SESSION['FMBase'].'ajax.class.php');

class GaleriaArquivoAForm extends FormCampos
{
	public function GaleriaArquivoAForm()
	{
		parent::FormCampos();
	}

	public function getFormFiltro()
	{
		$Metodo = "GET";
                $FPHP = new FuncoesPHP();
		
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
		
		$ArquivoData = parent::inputData(array(
		"Nome"        => "ArquivoData",
		"Identifica"  => "Data dos Arquivos",
		"TipoFiltro"  => "ValorVariavel",
		"Valor"       => parent::retornaValor($Metodo,"ArquivoData"),
                "VMax"        => $FPHP->convertData($FPHP->dataAtual()),
  		"Status"      => true,
		"ValidaJS"    => false),false);
		parent::setFiltro(true,"Data dos Arquivos:",$ArquivoData,1);
		
		$TipoArquivo = parent::listaVetor(array(
		"Nome"        => "TipoArquivo",
		"Identifica"  => "Tipo de Arquivo",
		"TipoFiltro"  => "ValorFixo",
		"Valor"       => parent::retornaValor($Metodo,"TipoArquivo"),
		"Status"      => true,
		"Inicio"      => true,
		"Vetor"       => array("F"=>"Fotos e Imagens","V"=>"Video","A"=>"Audios")),false);
		parent::setFiltro(true,"Tipo de Arquivo:",$TipoArquivo,1);

		$AutorNome = parent::inputSuggest(array(
		"Nome"        => "AutorNome",
		"Identifica"  => "Nome do Autor",
		"Largura"     => 25,
		"TipoFiltro"  => "Suggest",
		"Valor"       => parent::retornaValor($Metodo,"AutorNome"),
		"Tabela"      => "autor",
		"Campo"       => "AutorNome"),false);
		parent::setFiltro(true,"Autor:",$AutorNome,2);
		
		$Legenda = parent::inputSuggest(array(
		"Nome"        => "Legenda",
		"Identifica"  => "Legenda",
		"Valor"       => parent::retornaValor($Metodo,"Legenda"),
                "TipoFiltro"  => "Suggest",
                "Tabela"      => "galeria_arquivo",
                "Campo"       => "Legenda",
		"Largura"     => 20,
		"Status"      => true,
		"ValidaJS"    => false),false);
		parent::setFiltro(true,"Legenda:",$Legenda,2);	

		parent::setModFiltro(false);										
		
		//Botão Padrão de Filtro
		parent::setFiltro(true,null,$this->btFiltrar().'<input type="reset" name="Reset" value="Limpar" /> ',2);

		//Ajax
		$this->ajaxRetorno();
	}
	
	public function getFormManu()
	{	
		$Op = parent::getOp();
		
		$Metodo = "POST";
		
		$Env = parent::getEnv();
                $FPHP = new FuncoesPHP();
		
		$R["Id"] = parent::inputHidden(array(
		"Nome"   => "Id",  
		"Valor"  => parent::retornaValor($Metodo,"Id")),true);
		
		if($Op == "Cad")
		{
			$R["GaleriaMidiaCod"] = parent::listBox(array(
			"Nome"        => "GaleriaMidiaCod",
			"Identifica"  => "GaleriaMidiaCod",
			"Valor"       => parent::retornaValor($Metodo,"GaleriaMidiaCod"),
			"Status"      => true,
			"Inicio"      => "Selecione Uma Galeria",
			"Tabela"      => "galeria_midia",
			"CampoCod"    => "GaleriaMidiaCod",
			"CampoDesc"   => "GaleriaNome",
			"Adicional"   => "onChange='geraUpload()'",
			"ValidaJS"    => true),true);				
		}
		
		$R["AutorNome"]  = parent::inputSuggest(array(
		"Nome"        => "AutorNome",
		"Identifica"  => "AutorNome",
		"Valor"       => parent::retornaValor($Metodo,"AutorNome"),
		"Largura"     => 30,
		"Tabela"      => "autor",
		"Campo"       => "AutorNome",
		"Hidden"      => "AutorCod",
		"Url"         => $_SESSION['UrlBase'].'cadastros/autor/autor.ajax.php?Op=BuscaNome'),false);
		
		$R["AutorCod"] = parent::inputHidden(array(
		"Nome"   => "AutorCod",  
		"Valor"  => parent::retornaValor($Metodo,"AutorCod")),true);
					
		$R["Legenda"] = parent::inputTexto(array(
		"Nome"        => "Legenda",
		"Identifica"  => "Legenda",
		"Valor"       => parent::retornaValor($Metodo,"Legenda"),
		"Largura"     => (($Op == "Cad") ? 50 : 25),
		"Status"      => true,
		"ValidaJS"    => false),false);

		$R["DataPublicacao"] = parent::inputData(array(
		"Nome"        => "DataPublicacao",
		"Identifica"  => "Data dos Arquivos",
                "VMax"        => $FPHP->convertData($FPHP->dataAtual()),
		"Valor"       => parent::retornaValor($Metodo,"DataPublicacao"),
		"Status"      => true,
		"ValidaJS"    => false),false);
				
		$IdForm = parent::retornaValor($Metodo,"Id");
		
		return $R;	
	}
	
	public function getFormProjeto($Codigo, $Conteiner = false)
	{
		$Op 	= parent::getOp();		
		$Metodo = ($Op == "Cad") ? "GET" : "POST";	
		
		if(!empty($Codigo))
		{	
			$Array = array(
			"Nome"        => "GaleriaArquivoCod",
			"Identifica"  => "Galeria",
			"Valor"       => parent::retornaValor($Metodo,"GaleriaArquivoCod"),
			"Status"      => true,
			"Inicio"      => true,
			"CampoCod"    => "GaleriaArquivoCod",
			"CampoDesc"   => "Identificacao",
			"FullSelect"  => "SELECT a.GaleriaArquivoCod, a.Identificacao
							  FROM projeto_multimidia a
							  INNER JOIN projeto_etapa_tarefa b ON a.ProjetoEtapaTarefaCod = b.ProjetoEtapaTarefaCod
							  INNER JOIN projeto_etapa c ON b.ProjetoEtapaCod = c.ProjetoEtapaCod
							  INNER JOIN projeto d ON c.ProjetoCod = d.ProjetoCod
							  WHERE d.ProjetoCod = ".$Codigo,
			"ValidaJS"    => true);
							
			if($Conteiner == true) $Array['Conteiner'] = "cGaleriaArquivoCod";
				
			$R["GaleriaArquivoCod"] = parent::listBox($Array,true);

		}
		else
		{				
			$Array = array(
			"Nome"        => "GaleriaArquivoCod",
			"Identifica"  => "Galeria",
			"Valor"       => parent::retornaValor($Metodo,"GaleriaArquivoCod"),
			"Status"      => false,
			"Inicio"      => "Selecione o Projeto",
			"Vetor"       => array(),
			"ValidaJS"    => true);
			
			if($Conteiner == true) $Array['Conteiner'] = "cGaleriaArquivoCod";
			
			$R["GaleriaArquivoCod"] = parent::listaVetor($Array,true);
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
		
		parent::setFuncoes('
		 function geraUpload()
		  {
			  var GaleriaMidiaCod = $("#GaleriaMidiaCod").val();
			  
			  if(GaleriaMidiaCod != "") 
			  {
				$("#ExibeMultiploUploar").show();
				$("#ValidaMultiploUploar").hide();
				
				$("#enviarArquivos").modal({
					"url" : "'.$_SESSION['UrlBase'].'conteudo/upload/index.php",
					"backgroundOpacity" : 0.7
				});
			  } else {
				$("#ExibeMultiploUploar").hide();
				$("#ValidaMultiploUploar").show();  
			  }
		  }
		');
		
		parent::setFuncoes('$(document.body).ready(function(){ sis_filtrar();  $("#FormFiltro #BtFiltrar").click(function(){ sis_filtrar(); }) });');
	}
}
?>