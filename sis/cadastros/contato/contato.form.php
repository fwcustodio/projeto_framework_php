<?
class ContatoForm
{
    private $FormIntancia;

    public function ContatoForm($Form)
    {
        $this->FormIntancia = $Form;
    }

    public function getFormContato($Cont = null)
    {
        $Metodo = "POST";

        $R['ArrayContato'.$Cont] = $this->FormIntancia->inputHidden(array(
        "Nome"   => "ArrayContato[$Cont]",
        "Valor"  => $Cont),true);

        $R["NomeContato".$Cont] = $this->FormIntancia->inputTexto(array(
        "Nome"       => "NomeContato".$Cont,
        "Identifica" => "NomeContato ".$Cont,
        "Valor"      => $this->FormIntancia->retornaValor($Metodo,"NomeContato".$Cont),
        "Largura"    => 23,
        "Max"        => 50,
        "ValidaJS"   => true),false);

        $R["ContatoObservacao".$Cont] = $this->FormIntancia->textArea(array(
        "Nome"        => "ContatoObservacao".$Cont,
        "Identifica"  => "Observações de Contato ".$Cont,
        "Valor"       => $this->FormIntancia->retornaValor($Metodo,"ContatoObservacao".$Cont),
        "Linhas"      => 2,
        "Colunas"     => 28,
        "Status"      => true,
        "ValidaJS"    => false),false);

        return $R;
    }

    public function getFormTipo($Cont = null)
    {
        $Metodo = "POST";

        $R["ContatoCategoriaCod".$Cont] = $this->FormIntancia->listBox(array(
        "Nome"        => "ContatoCategoriaCod[".$Cont."][]",
        "Identifica"  => "Categoria do contato ".$Cont,
        "Valor"       => $this->FormIntancia->retornaValor($Metodo,"ContatoCategoriaCod".$Cont),
        "Status"      => true,
        "Tabela"      => "contato_categoria",
        "CampoCod"    => "ContatoCategoriaCod",
        "CampoDesc"   => "CONCAT(': ',ContatoCategoria)",
        "Adicional"   => "style=\"direction:rtl\""),false);

        $R["Contato".$Cont] = $this->FormIntancia->inputTexto(array(
        "Nome"       => "Contato[".$Cont."][]",
        "Identifica" => "Contato ".$Cont,
        "Valor"      => $this->FormIntancia->retornaValor($Metodo,"Contato".$Cont),
        "Largura"    => 23,
        "Max"        => 100),false);

        return $R;
    }

    public function ajaxRetorno()
    {
         $Ajax = new Ajax();

        $this->FormIntancia->setFuncoes($Ajax->ajaxRequest(array(
        "Nome"       => "addContato",
        "ParInicial" => "idForm",
        "Parametros" => "{IdForm:idForm}",
        "URL"        => $_SESSION['UrlBase']."cadastros/contato/contato.ajax.php?Op=Contato",
        "Metodo"     => "POST",
        "Completa"   => "function(Req){ retornoContato(idForm, Req.responseText); } ")));

        $this->FormIntancia->setFuncoes($Ajax->ajaxRequest(array(
        "Nome"       => "addTipoContato",
        "ParInicial" => "cont, idForm",
        "Parametros" => "{Cont:cont, IdForm:idForm}",
        "URL"        => $_SESSION['UrlBase']."cadastros/contato/contato.ajax.php?Op=TipoContato",
        "Metodo"     => "POST",
        "Completa"   => "function(Req){ retornoTipoContato(cont, idForm, Req.responseText); } ")));

        $this->FormIntancia->setFuncoes('
        //Contato
        function retornoContato(idForm, req)
        {
                $("#FormManu"+idForm+" #conteinerContato").append(req);
        }

        //Adicionar Tipo Contato
        function retornoTipoContato(cont, idForm, req)
        {
                $("#FormManu"+idForm+" #conteinerTipoContato"+cont).append(req);
        }

        //Removendo contato
        function removeContato(idForm, cont)
        {
            var conta = 0;

            $("#FormManu"+idForm+" [@name^=NomeContato]").each(function() { conta += 1; });

            if(conta <= 1)
            {
                alert("Deve haver ao menos um contato!");
                return;
            }

            $("#contato"+cont).remove();
        }

        //Remove tipo contato
        function removeTipoContato(idForm, cont,  objeto)
        {
            var conta = 0;

            $("#FormManu"+idForm+" #contato"+cont+" [@name^=ContatoCategoriaCod]").each(function() { conta += 1; });

            if(conta <= 1)
            {
                            alert("Deve haver ao menos um tipo de contato!");
                            return;
            }

            $(objeto).parent().parent().remove();
        }');
    }
}