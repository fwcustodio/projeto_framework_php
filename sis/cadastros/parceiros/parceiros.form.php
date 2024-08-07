<?
include_once($_SESSION['FMBase'] . 'form_campos.class.php');
include_once($_SESSION['FMBase'] . 'ajax.class.php');

class ParceirosForm extends FormCampos {

    public function __construct() {
        parent::FormCampos();
    }

    public function getFormFiltro() {
        $Metodo = "GET";

        //Campos de Filtro
        parent::setModFiltro(true);
        $ParceirosNome = parent::inputSuggest(array(
                    "Nome" => "ParceirosNome",
                    "Identifica" => "Nome",
                    "TipoFiltro" => "Suggest",
                    "Valor" => parent::retornaValor($Metodo, "ParceirosNome"),
                    "Largura" => 20,
                    "Tabela" => "parceiros",
                    "Campo" => "ParceirosNome"), false);
        parent::setFiltro(true, "Nome:", $ParceirosNome, 1);

        $ParceirosLink = parent::inputSuggest(array(
                    "Nome" => "ParceirosLink",
                    "Identifica" => "Link",
                    "TipoFiltro" => "Suggest",
                    "Valor" => parent::retornaValor($Metodo, "ParceirosLink"),
                    "Largura" => 20,
                    "Tabela" => "parceiros",
                    "Campo" => "ParceirosLink"), false);
        parent::setFiltro(true, "Link:", $ParceirosLink, 1);

        $ParceirosSituacao = parent::listaVetor(array(
                    "Nome" => "ParceirosSituacao",
                    "Identifica" => "Situação",
                    "TipoFiltro" => "Suggest",
                    "Valor" => parent::retornaValor($Metodo, "ParceirosSituacao"),
                    "Inicio" => "Todos",
                    "Vetor" => array('A' => 'Ativo', 'I' => 'Inativo')), false);
        parent::setFiltro(true, "Situação:", $ParceirosSituacao, 2);



        parent::setModFiltro(false);

        //Botão Padrão de Filtro
        parent::setFiltro(true, null, $this->btFiltrar() . '<input type="reset" name="Reset" value="Limpar" /> ', 1);

        //Ajax
        $this->ajaxRetorno();
    }

    public function getFormManu() {
        $Metodo = "POST";

        $Op = parent::getOp();

        $R["Id"] = parent::inputHidden(array(
                    "Nome" => "Id",
                    "Valor" => parent::retornaValor($Metodo, "Id")), true);

        $R["ParceirosNome"] = parent::inputTexto(array(
                    "Nome" => "ParceirosNome",
                    "Identifica" => "Nome",
                    "Valor" => parent::retornaValor($Metodo, "ParceirosNome"),
                    "Largura" => 40,
                    "Status" => true,
                    "ValidaJS" => true), true);


        $R["ParceirosComentario"] = parent::inputHtmlEditor(array(
                    "Nome" => "ParceirosComentario",
                    "Identifica" => "Comentario",
                    "Valor" => parent::retornaValor($Metodo, "ParceirosComentario"),
                    "Largura" => "500",
                    "Altura" => "250",
                    "Ferramentas" => "Basic",
                    "Tratar" => array("L"),
                    "ValidaJS" => false), false);

        $R["ParceirosTipo"] = parent::listaVetor(array(
                    "Nome" => "ParceirosTipo",
                    "Identifica" => "Tipo de Link",
                    "Valor" => parent::retornaValor($Metodo, "ParceirosTipo"),
                    "Status" => true,
                    "Ordena" => false,
                    "Padrao" => 'http://',
                    "Vetor" => array('http://' => 'http://',
                                     'https://' => 'https://',
                                     'mailto:' => 'Email',
                                     -'' => 'Outros')), false);


        $R["ParceirosLink"] = parent::inputTexto(array(
                    "Nome" => "ParceirosLink",
                    "Identifica" => "Link",
                    "Valor" => parent::retornaValor($Metodo, "ParceirosLink"),
                    "Largura" => 30,
                    "Status" => true,
                    "ValidaJS" => false), false);

        $R["ParceirosSituacao"] = parent::listaVetor(array(
                    "Nome" => "ParceirosSituacao",
                    "Identifica" => "Situação",
                    "Valor" => parent::retornaValor($Metodo, "ParceirosSituacao"),
                    "Status" => true,
                    "Ordena" => false,
                    "Inicio" => true,
                    "Vetor" => array('A' => 'Ativo', 'I' => 'Inativo'),
                    "ValidaJS" => true), true);

        $Id = parent::retornaValor($Metodo, "Id");

        $R["Imagens"] = parent::uploadMultiploJQuery(array(
                    "Nome" => "Imagens" . $Id,
                    "Identifica" => "Imagens",
                    "Tipos" => array('jpg', 'jpeg', 'gif', 'png'),
                    "Max" => 1,
                    "Status" => true), true);

        return $R;
    }

    public function btFiltrar() {
        //Padrão Para Busca em Ajax -> Executado Pelo Observer
        return parent::botao(array(
                    "Nome" => "BtFiltrar",
                    "Identifica" => "Filtrar",
                    "Tipo" => "button",
                    "Estilo" => "cursor:pointer"));
    }

    public function ajaxRetorno() {
        $Ajax = new Ajax();

        parent::setFuncoes($Ajax->ajaxUpdate(array(
                    "Nome" => "sis_filtrar",
                    "URL" => MODULO . ".ajax.php?Op=Fil",
                    "Form" => "FormFiltro",
                    "VarPar" => "PARFIL",
                    "Metodo" => "get",
                    "TipoDado" => 'script',
                    "Conteiner" => 'corpoPrincipal')));

        parent::setFuncoes('function sis_busca_filtro(){ sis_atualizar("' . MODULO . '"); }');

        parent::setFuncoes($Ajax->ajaxUpdate(array(
                    "Nome" => "visualiza",
                    "URL" => MODULO . ".ajax.php?Op=Vis",
                    "VarPar" => "PARVIS",
                    "Form" => "FormGrid",
                    "Metodo" => "POST",
                    "Conteiner" => 'manu')));

        parent::setFuncoes($Ajax->ajaxUpdate(array(
                    "Nome" => "sis_cadastrar",
                    "URL" => MODULO . ".ajax.php?Op=Cad",
                    "TipoDado" => 'script',
                    "Conteiner" => 'manu')));

        parent::setFuncoes($Ajax->ajaxRequest(array(
                    "Nome" => "cadastraBd",
                    "URL" => MODULO . ".ajax.php?Op=Cad&Env=true",
                    "Form" => "FormManu",
                    "Metodo" => "POST",
                    "Completa" => "function(Req){ retornoCadastrar(Req.responseText); } ")));

        parent::setFuncoes($Ajax->ajaxUpdate(array(
                    "Nome" => "alteraForm",
                    "URL" => MODULO . ".ajax.php?Op=Alt",
                    "VarPar" => "PARALT",
                    "Conteiner" => 'manu',
                    "TipoDado" => 'script',
                    "Form" => "FormGrid",
                    "Metodo" => "POST")));

        parent::setFuncoes($Ajax->ajaxRequestForm(array(
                    "Nome" => "alteraBd",
                    "URL" => MODULO . ".ajax.php?Op=Alt&Env=true",
                    "Metodo" => "POST",
                    "Completa" => "function(Req){ retornoAlterar(Req.responseText, conteiner); } ")));

        parent::setFuncoes($Ajax->ajaxRequest(array(
                    "Nome" => "sis_apagar",
                    "URL" => MODULO . ".ajax.php?Op=Del",
                    "Form" => "FormGrid",
                    "Metodo" => "POST",
                    "Completa" => "function(Req){ retornoRemover(Req.responseText); } ")));

        parent::setFuncoes('$(document.body).ready(function(){ sis_filtrar();  $("#FormFiltro #BtFiltrar").click(function(){ sis_filtrar(); }) });');
    }

}