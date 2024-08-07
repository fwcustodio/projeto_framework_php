<?PHP
include_once($_SESSION['FMBase'].'form_campos.class.php');
include_once($_SESSION['FMBase'].'ajax.class.php');
include_once($_SESSION['DirBase'].'cadastros/endereco/endereco.form.php');
include_once($_SESSION['DirBase'].'cadastros/contato/contato.form.php');

class UsuariosForm extends FormCampos
{
    public function  __construct(){
        parent::FormCampos();
    }

    public function getFormFiltro()
    {
        $Metodo = "GET";

        parent::setModFiltro(true);

       $UsuarioDadosNome = parent::inputSuggest(array(
        "Nome"       => "UsuarioDadosNome",
        "Identifica" => "Nome do Usuário",
        "Valor"      => parent::retornaValor($Metodo,"UsuarioDadosNome"),
        "Largura"    => 20,
        "TipoFiltro" => "suggest",
        "Url"        => 'usuarios.ajax.php?Op=BusNome',
        "Tratar"     => array("L","H","A")),false);
        parent::setFiltro(true,"Nome do Usuário:",$UsuarioDadosNome,1); 

        $Login = parent::inputSuggest(array(
        "Nome"       => "Login",
        "TipoFiltro" => "suggest",
        "Identifica" => "Login",
        "Valor"      => parent::retornaValor($Metodo,"Login"),
        "Largura"    => 20,
        "Tabela"     => "_usuarios",
        "Campo"      => "login",
        "Tratar"     => array("L","H","A")),false);
        parent::setFiltro(true,"Login:",$Login,1);

        $Status = parent::listaVetor(array(
        "Nome"        => "Status",
        "Identifica"  => "Situação",
        "TipoFiltro"  => "ValorFixo",
        "Valor"       => parent::retornaValor($Metodo,"Status"),
        "Status"      => true,
        "ValidaJS"    => false,
        "Inicio"      => "Todas",
        "Vetor"       => array("A"=>"Ativo", "I"=>"Inativo")),false);
        parent::setFiltro(true,"Situação:",$Status,1);

        parent::setModFiltro(false);

        //Botão Padrão de Filtro
        parent::setFiltro(true,null,$this->btFiltrar().'<input type="reset" name="Reset" value="Limpar" /> ',1);

        //Ajax
        $this->ajaxRetorno();
    }

    public function getFormManu()
    {
        $Metodo = "POST";

        $R["Id"] = parent::inputHidden(array(
        "Nome"   => "Id",  
        "Valor"  => parent::retornaValor($Metodo,"Id")),true);

        $R["UsuarioDadosNome"] = parent::inputTexto(array(
        "Nome"        => "UsuarioDadosNome",
        "Identifica"  => "Nome do Usuário",
        "Valor"       => parent::retornaValor($Metodo,"UsuarioDadosNome"),
        "Largura"     => 30,
        "Min"         => 1,
        "Max"         => 100,
        "Status"      => true,
        "Aba"         => 1,
        "ValidaJS"    => true),true);

        $R["UsuarioDadosNascimento"] = parent::inputData(array(
        "Nome"        => "UsuarioDadosNascimento",
        "Identifica"  => "Data de Nascimento",
        "Valor"       => parent::retornaValor($Metodo,"UsuarioDadosNascimento"),
        "Status"      => true,
        "Aba"         => 1,
        "Min"         => "01/01/1900",
        "Max"         => date("d/m/Y"),
        "ValidaJS"    => true),false);

        $R["Status"] = parent::listaVetor(array(
        "Nome"        => "Status",
        "Identifica"  => "Situação",
        "Valor"       => parent::retornaValor($Metodo,"Status"),
        "Aba"         => 1,
        "Status"      => true,
        "ValidaJS"    => true,
        "Inicio"      => true,
        "Vetor"       => array("A"=>"Ativo", "I"=>"Inativo")),true);

        return $R;
    }

    public function getFormAcesso()
    {
        //Op Alteração
        $Op = parent::getOp();

        $EmAlteracao = ($Op == "Alt") ? false : true;

        $Metodo = "POST";

        $R["Login"] = parent::inputTexto(array(
        "Nome"       => "Login",
        "Identifica" => "Login",
        "Valor"      => parent::retornaValor($Metodo,"Login"),
        "Largura"    => 20,
        "Status"     => $EmAlteracao,
        "Min"        => 1,
        "Max"        => 20,
        "ValidaJS"   => true,
        "Aba"        => 4,
        "Tratar"     => array("L","H","A")),$EmAlteracao);

        $R["Senha"]  = parent::inputSenha(array(
        "Nome"       => "Senha",
        "Identifica" => "Senha do Usuário",
        "Valor"      => parent::retornaValor($Metodo,"Senha"),
        "Largura"    => 20,
        "Min"        => 6,
        "Max"        => 30,
        "Aba"        => 4,
        "ValidaJS"   => $EmAlteracao),$EmAlteracao);

        $R["RepitaSenha"] = parent::inputSenha(array(
        "Nome"       => "RepitaSenha",
        "Identifica" => "Repita Senha do Usuário",
        "Valor"      => parent::retornaValor($Metodo,"RepitaSenha"),
        "Largura"    => 20,
        "Min"        => 6,
        "Max"        => 30,
        "Aba"        => 4,
        "ValidaJS"   => $EmAlteracao),$EmAlteracao);

        //IdForm
        $IdForm =  parent::retornaValor($Metodo,"Id");

        //Verificação de Senha
        if($Obrigatorio === true)
        $this->setStringJS("if(d.Senha.value != d.RepitaSenha.value) { manipulaAbas('".$IdForm."' ,4); alert('Senha e Repita Senha devem Ser Iguais!'); d.Senha.focus(); return false; }");

        $R["Email"] = parent::inputEmail(array(
        "Nome"       => "Email",
        "Identifica" => "Email",
        "Valor"      => parent::retornaValor($Metodo,"Email"),
        "Aba"        => 4,
        "ValidaJS"   => true),true);

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

        parent::setFuncoes('$(document.body).ready(function(){ sis_filtrar();  $("#FormFiltro #BtFiltrar").click(function(){ sis_filtrar(); }) });');

        //Preeche Padrão
        parent::setFuncoes('

        function cboxes(container_div_id, operacao, idForm)
        {
            var link = "#FormManu"+idForm+" div#"+container_div_id+" a";
            var els = "#FormManu"+idForm+" div#"+container_div_id+" input";

            // este codigo serve apenas para trocar o background dos links e pode
            // ser retirado desta função caso queira usá-la em outro
            if ($(link).attr("class").indexOf("on") >= 0)
            {
                $(link).attr("class", "pgrupo_titulo pgrupo_off");
                operacao = "none";
            }
            else
            {
                $(link).attr("class", "pgrupo_titulo pgrupo_on");
                operacao = "all";
            }

            $(els).each(function()
            {
                if (operacao == "all")
                {
                    this.checked = true;
                }
                else
                {
                    if (operacao == "none")
                    {
                        this.checked = false;
                    }
                    else
                    {
                        this.checked = !this.checked;
                    }
                }
            });
        }
        ');
    }
}