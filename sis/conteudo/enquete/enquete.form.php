<?
include_once($_SESSION['FMBase'] . 'form_campos.class.php');
include_once($_SESSION['FMBase'] . 'ajax.class.php');

class EnqueteForm extends FormCampos {

    public function __construct() {
        parent::FormCampos();
    }

    public function getFormFiltro() {
        $Metodo = "GET";

        //Campos de Filtro
        parent::setModFiltro(true);
        $EnquetePergunta = parent::inputSuggest(array(
        "Nome" => "EnquetePergunta",
        "Identifica" => "Pergunta",
        "TipoFiltro" => "Suggest",
        "Valor" => parent::retornaValor($Metodo, "EnquetePergunta"),
        "Largura" => 30,
        "Tabela" => "enquete",
        "Campo" => "EnquetePergunta"), false);
        parent::setFiltro(true, "Pergunta:", $EnquetePergunta, 1);


        $Publicar = parent::listaVetor(array(
        "Nome" => "Publicar",
        "Identifica" => "Publicar",
        "TipoFiltro" => "ValorFixo",
        "Valor" => parent::retornaValor($Metodo, "Publicar"),
        "Status" => true,
        "Inicio" => "Todos",
        "Vetor" => array("S" => "Sim", "N" => "Não")), false);
        parent::setFiltro(true, "Publicar:", $Publicar, 2);

        $Situacao = parent::listaVetor(array(
        "Nome" => "Situacao",
        "Identifica" => "Situacao",
        "TipoFiltro" => "ValorFixo",
        "Valor" => parent::retornaValor($Metodo, "Situacao"),
        "Status" => true,
        "Inicio" => "Todas",
        "Vetor" => array("A" => "Ativo", "I" => "Inativo")), false);
        parent::setFiltro(true, "Situação:", $Situacao, 2);

        $TipoPublicacao = parent::listaVetor(array(
        "Nome" => "TipoPublicacao",
        "Identifica" => "TipoPublicacao",
        "TipoFiltro" => "ValorFixo",
        "Valor" => parent::retornaValor($Metodo, "TipoPublicacao"),
        "Inicio" => "Todos",
        "Status" => true,
        "Vetor" => array("PS" => "Principal e Seções", "P" => "Apenas Página Principal", "S" => "Definir Seções", "G" => "Todas as Páginas")), false);
        parent::setFiltro(true, "Tipo de Publicação:", $TipoPublicacao, 1);


        parent::setModFiltro(false);

        //Botão Padrão de Filtro
        parent::setFiltro(true, null, $this->btFiltrar() . '<input type="reset" name="Reset" value="Limpar" /> ', 2);

        //Ajax
        $this->ajaxRetorno(false);
    }

    public function getFormPop() {
        $Metodo = "GET";

        //Campos de Filtro
        parent::setModFiltro(true);

        $EnquetePergunta = parent::inputSuggest(array(
        "Nome" => "EnquetePergunta",
        "Identifica" => "Pergunta",
        "TipoFiltro" => "Suggest",
        "Valor" => parent::retornaValor($Metodo, "EnquetePergunta"),
        "Largura" => 30,
        "Tabela" => "enquete",
        "Campo" => "EnquetePergunta",
        "Condicao" => "AND Publicar = :S: AND Situacao = :A:"), false);
        parent::setFiltro(true, "Pergunta:", $EnquetePergunta, 1);

        parent::setModFiltro(false);

        //Botão Padrão de Filtro
        parent::setFiltro(true, null, $this->btFiltrar() . '<input type="reset" name="Reset" value="Limpar" /> ', 1);

        //Ajax
        $this->ajaxRetorno(true);
    }

    public function getFormManu() {
        $Metodo = "POST";

        $Op                    = parent::getOp();
        $QuantidadeVotoEnquete = parent::retornaValor($Metodo, "QuantidadeVotoEnquete");
        $Status                = (!empty ($QuantidadeVotoEnquete))? false : true;

        $R["Id"] = parent::inputHidden(array(
        "Nome" => "Id",
        "Valor" => parent::retornaValor($Metodo, "Id")), true);
        
        $R["QuantidadeVotoEnquete"] = parent::inputHidden(array(
        "Nome" => "QuantidadeVotoEnquete",
        "Valor" => $QuantidadeVotoEnquete), true);

        $R["EnquetePergunta"] = parent::inputTexto(array(
        "Nome" => "EnquetePergunta",
        "Identifica" => "Pergunta",
        "Valor" => parent::retornaValor($Metodo, "EnquetePergunta"),
        "Largura" => 60,
        "Status" => $Status,
        "ValidaJS" => $Status), $Status);

        $R["DataInicioPublicacao"] = parent::inputData(array(
        "Nome" => "DataInicioPublicacao",
        "Identifica" => "Data de Início",
        "Valor" => parent::retornaValor($Metodo, "DataInicioPublicacao"),
        "Status" => true,
        "ValidaJS" => false), false);

        $R["HoraInicioPublicacao"] = parent::inputTexto(array(
        "Nome" => "HoraInicioPublicacao",
        "Identifica" => "Hora de Início",
        "Valor" => parent::retornaValor($Metodo, "HoraInicioPublicacao"),
        "Status" => true,
        "Largura" => 5,
        "Mascara" => "99:99",
        "ValidaJS" => false), false);

        $R["DataFimPublicacao"] = parent::inputData(array(
        "Nome" => "DataFimPublicacao",
        "Identifica" => "Data de Término",
        "Valor" => parent::retornaValor($Metodo, "DataFimPublicacao"),
        "Status" => true,
        "VMin" => "DataInicioPublicacao",
        "ValidaJS" => false), false);

        $R["HoraFimPublicacao"] = parent::inputTexto(array(
        "Nome" => "HoraFimPublicacao",
        "Identifica" => "Hora de Término",
        "Valor" => parent::retornaValor($Metodo, "HoraFimPublicacao"),
        "Status" => true,
        "Largura" => 5,
        "Mascara" => "99:99",
        "ValidaJS" => false), false);

        $R["TipoPublicacao"] = parent::listaVetor(array(
        "Nome" => "TipoPublicacao",
        "Identifica" => "Tipo de Publicação",
        "Valor" => parent::retornaValor($Metodo, "TipoPublicacao"),
        "Inicio" => true,
        "ValidaJS" => true,
        "Status" => true,
        "Vetor" => array("P" => "Apenas Página Principal")), true);

        parent::listaVetor(array(
        "Nome" => "MostrarNumeroVotos",
        "Identifica" => "Mostrar Numero de Votos",
        "Valor" => parent::retornaValor($Metodo, "MostrarNumeroVotos"),
        "Inicio" => true,
        "ValidaJS" => false,
        "Status" => true,
        "Vetor" => array("S" => "Sim", "N" => "Não")), true);

        parent::listaVetor(array(
        "Nome" => "MostrarPorcentagem",
        "Identifica" => "Mostrar Porcentagem",
        "Valor" => parent::retornaValor($Metodo, "MostrarPorcentagem"),
        "Inicio" => true,
        "ValidaJS" => false,
        "Status" => true,
        "Vetor" => array("S" => "Sim", "N" => "Não")), true);

        parent::listaVetor(array(
        "Nome" => "Publicar",
        "Identifica" => "Publicar",
        "Valor" => parent::retornaValor($Metodo, "Publicar"),
        "Inicio" => true,
        "ValidaJS" => false,
        "Status" => true,
        "Vetor" => array("S" => "Sim", "N" => "Não")), true);

        parent::listaVetor(array(
        "Nome" => "Situacao",
        "Identifica" => "Situação",
        "Valor" => parent::retornaValor($Metodo, "Situacao"),
        "Inicio" => true,
        "ValidaJS" => false,
        "Status" => true,
        "Vetor" => array("A" => "Ativo", "I" => "Inativo")), true);

        return $R;
    }

    public function getFormDados($Cod) {
        $Metodo = "POST";
        
        $QuantidadeVotoEnquete = parent::retornaValor($Metodo, "QuantidadeVotoEnquete");
        $Status                = (!empty ($QuantidadeVotoEnquete))? false : true;

        $R["ContadorDados"] = parent::inputHidden(array(
        "Nome" => "ContadorDados[" . $Cod . "]",
        "Valor" => $Cod), false);

        $R["EnqueteResposta"] = parent::inputTexto(array(
        "Nome" => "EnqueteResposta" . $Cod,
        "Identifica" => "Resposta",
        "Valor" => parent::retornaValor($Metodo, "EnqueteResposta" . $Cod),
        "Largura" => 50,
        "Max" => 250,
        "Status" => $Status,
        "ValidaJS" => false), $Status);

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

    public function ajaxRetorno($Pop) {
        $Ajax = new Ajax();

        $ParPop = $Pop ? '&Pop=true' : '';

        parent::setFuncoes($Ajax->ajaxUpdate(array(
        "Nome" => "sis_filtrar",
        "URL" => MODULO . ".ajax.php?Op=Fil" . $ParPop,
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


        /* FUNÇÂO PARA DADOS */
        parent::setFuncoes('

        function addDados(idForm)
        {
            var randJS = Math.random();
            randJS     = randJS+""; 
            randJS     = randJS.replace(".","");
            $.ajax({url: "enquete.ajax.php?Op=AddDados", 
                   type: "post", 
                   data: {"RandJS":randJS, "IdForm":idForm }, 
               datatype: "html",
                success: function(Req) 
                { 
                    $("#FormManu"+idForm+" #conteiner_respostas").append(Req);
                } 
            });
        }

        function removeItenDado(obj, idForm)
        {
                var conta = 0;
                $("#FormManu"+idForm+" [@name^=EnqueteResposta]").each(function() { conta += 1; });

                if(conta <= 2)
                {
                                alert("Deve haver ao menos duas respostas!");
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