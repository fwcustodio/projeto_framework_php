<?
include_once($_SESSION['FMBase'].'form_campos.class.php');
include_once($_SESSION['FMBase'].'ajax.class.php');

class LogsForm extends FormCampos
{
	public function LogsForm()
	{
		parent::FormCampos();
	}

	public function getFormFiltro()
	{
		$Metodo = "GET";
		
		//Campos de Filtro
		parent::setModFiltro(true);
		$UsuarioCod = parent::listBox(array(
		"Nome"        => "UsuarioCod",
		"Identifica"  => "Usuário",
		"TipoFiltro"  => "ValorFixo",
		"Valor"       => parent::retornaValor($Metodo,"UsuarioCod"),
		"Status"      => true,
		"Inicio"      => "Todos",
		"Tabela"      => "_usuarios",
		"CampoCod"    => "UsuarioCod",
		"FullSelect"  => "SELECT a.UsuarioCod, CONCAT(b.UsuarioDadosNome, ' (',a.Login,')') AS Nome
							FROM _usuarios a, usuario_dados b
						   WHERE a.UsuarioCod = b.UsuarioCod
						     AND a.Status = 'A'",
		"CampoDesc"   => "Nome"),false);
		parent::setFiltro(true,"Usuário:",$UsuarioCod,1);

		$ModuloCod = parent::listBox(array(
		"Nome"        => "ModuloCod",
		"Identifica"  => "Nome do Módulo",
		"TipoFiltro"  => "ValorFixo",
		"Valor"       => parent::retornaValor($Metodo,"ModuloCod"),
		"Status"      => true,
		"Inicio"      => "Todos",
		"Tabela"      => "_modulos",
		"CampoCod"    => "ModuloCod",
		"CampoDesc"   => "NomeMenu"),false);
		parent::setFiltro(true,"Módulo:",$ModuloCod,1);

		$Acao = parent::listaVetor(array(
		"Nome"        => "Acao",
		"Identifica"  => "Ação",
		"TipoFiltro"  => "Acao",
		"Valor"       => parent::retornaValor($Metodo,"Acao"),
		"Status"      => true,
		"Inicio"      => "Todas",
		"Vetor"       => array('Cad'=>'Cadastros','Alt'=>'Alterações','Del'=>'Remoções')),false);
		parent::setFiltro(true,"Ações Sql:",$Acao,1);

		$Ip = parent::inputTexto(array(
		"Nome"        => "Ip",
		"Identifica"  => "IP",
		"TipoFiltro"  => "ValorFixo",
		"Valor"       => parent::retornaValor($Metodo,"Ip"),
		"Largura"     => 20,
		"Max"         => 15,
		"Status"      => true,
		"ValidaJS"    => false),false);
		parent::setFiltro(true,"IP:",$Ip,2);

		$DataLog = parent::inputData(array(
		"Nome"        => "DataLog",
		"Identifica"  => "Data do Log",
		"TipoFiltro"  => "ValorVariavel",
		"Valor"       => parent::retornaValor($Metodo,"DataLog"),
		"Status"      => true,
		"ValidaJS"    => false),false);
		parent::setFiltro(true,"Data do Log:",$DataLog,2);


		parent::setModFiltro(false);										
		
		//Botão Padrão de Filtro
		parent::setFiltro(true,null,'&nbsp;',2);
		parent::setFiltro(true,null,$this->btFiltrar().'<input type="reset" name="Reset" value="Limpar" /> ',2);

		//Ajax
		$this->ajaxRetorno();
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

		parent::setFuncoes('$(document.body).ready(function(){ sisShowFiltro(); sis_filtrar();  $("#FormFiltro #BtFiltrar").click(function(){ sis_filtrar(); }) });');
	}
}
?>