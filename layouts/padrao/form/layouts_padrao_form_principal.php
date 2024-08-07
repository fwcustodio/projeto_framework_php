<?
require_once($_SESSION['FMBase'] . 'form_campos.class.php');
require_once($_SESSION['FMBase'] . 'ajax.class.php');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of layouts_padrao_form
 *
 * @author fernando
 */
class LayoutsPadraoFormPrincipal extends FormCampos {

    public function LayoutsPadraoFormPrincipal() {
        parent::FormCampos();
    }

    public function getFormManu() {
        $Metodo = "POST";
        $Op = $_GET['Op'];
        
        $ArrayForm["Layout"] = parent::inputTexto(array(
                    "Nome" => "Layout",
                    "Identifica" => "Layout",
                    "Valor" => parent::retornaValor($Metodo, "Layout"),
                    "Id" => "Layout",
                    "Largura" => 30,
                    "Min" => 1,
                    "Max" => 30,
                    "Tratar" => array("L", "H", "A"),
                    "ValidaJS" => true,
                    "Adicional" => "style='width:180px;'"), true);
        
        $ArrayForm["PastaSistema"] = parent::inputTexto(array(
                    "Nome" => "PastaSistema",
                    "Identifica" => "Pasta do sistema",
                    "Valor" => parent::retornaValor($Metodo, "PastaSistema"),
                    "Id" => "PastaSistema",
                    "Largura" => 30,
                    "Min" => 1,
                    "Max" => 30,
                    "Tratar" => array("L", "H", "A"),
                    "ValidaJS" => true,
                    "Adicional" => "style='width:180px;'"), true);
        
        $ArrayForm["NomeModulo"] = parent::inputTexto(array(
                    "Nome" => "NomeModulo",
                    "Identifica" => "Nome do modulo",
                    "Valor" => parent::retornaValor($Metodo, "NomeModulo"),
                    "Id" => "NomeModulo",
                    "Largura" => 30,
                    "Min" => 1,
                    "Max" => 30,
                    "Tratar" => array("L", "H", "A"),
                    "ValidaJS" => true,
                    "Adicional" => "style='width:180px;'"), true);
        
        $ArrayForm["InputAddView"] = parent::inputTexto(array(
                    "Nome" => "InputAddView",
                    "Identifica" => "InputAddView",
                    "Valor" => parent::retornaValor($Metodo, "InputAddView"),
                    "Id" => "InputAddView",
                    "Largura" => 30,
                    "Min" => 1,
                    "Max" => 30,
                    "Tratar" => array("L", "H", "A"),
                    "ValidaJS" => false,
                    "Adicional" => "style='width:180px;'"), false);

//        $ArrayForm["TipoAdicao"] = parent::listaVetor(array(
//                    "Nome" => "TipoAdicao",
//                    "Identifica" => "Tipo",
//                    "Valor" => parent::retornaValor($Metodo, "TipoAdicao"),
//                    "Status" => true,
//                    "ValidaJS" => false,
//                    "Inicio" => true,
//                    "Vetor" => array("C" => "Componente", "I" => "Modulo")), false);

        $ArrayForm['Submit'] = parent::botao(array(
                    "Nome" => "Submit",
                    "Identifica" => 'Gerar',
                    "Tipo" => "button",
                    "Estilo" => "cursor:pointer;margin-top:50px;",
                    "Adicional" => " class=\"botaoForm\" onClick=\"if(validaForm()){gerarArquivos()}\""));
        return $ArrayForm;
    }
}
?>