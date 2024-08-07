<?
class EnderecoForm
{
    private $FormIntancia;

    public function EnderecoForm($Form)
    {
        $this->FormIntancia = $Form;
    }

    public function getFormManu($Cont = null)
    {
        $Metodo = "POST";

        $R['ArrayEndereco'.$Cont] = $this->FormIntancia->inputHidden(array(
        "Nome"   => "ArrayEndereco[$Cont]",
        "Valor"  => $Cont),false);

        $R["EnderecoDadosTipoCod".$Cont] = $this->FormIntancia->listBox(array(
        "Nome"        => "EnderecoDadosTipoCod".$Cont,
        "Identifica"  => "Tipo de Endereço ".$Cont,
        "Valor"       => $this->FormIntancia->retornaValor($Metodo,"EnderecoDadosTipoCod".$Cont),
        "Status"      => true,
        "ValidaJS"    => true,
        "Tabela"      => "endereco_dados_tipo",
        "CampoCod"    => "EnderecoDadosTipoCod",
        "CampoDesc"   => "EnderecoDadosTipo"),false);

        $R['TipoEndereco'.$Cont] = $this->FormIntancia->inputTexto(array(
        "Nome"       => "TipoEndereco".$Cont,
        "Identifica" => "TipoEndereco",
        "Valor"      => $this->FormIntancia->retornaValor($Metodo,"TipoEndereco".$Cont),
        "Largura"    => 20,
        "Max"        => 100,
        "ValidaJS"   => false),false);

        //Dados de Endereço
        $R['Estado'.$Cont] = $this->FormIntancia->inputUF("Estado".$Cont, $this->FormIntancia->retornaValor($Metodo,"Estado".$Cont), "MT");

        $R["Cidade".$Cont] = $this->FormIntancia->inputTexto(array(
        "Nome"       => "Cidade".$Cont,
        "Identifica" => "Cidade",
        "Valor"      => $this->FormIntancia->retornaValor($Metodo,"Cidade".$Cont),
        "Largura"    => 20,
        "Max"        => 70,
        "ValidaJS"   => false),false);

        $R["Rua".$Cont] = $this->FormIntancia->inputTexto(array(
        "Nome"       => "Rua".$Cont,
        "Identifica" => "Rua",
        "Valor"      => $this->FormIntancia->retornaValor($Metodo,"Rua".$Cont),
        "Largura"    => 20,
        "Max"        => 50,
        "ValidaJS"   => false),false);

        $R["Numero".$Cont] = $this->FormIntancia->inputTexto(array(
        "Nome"       => "Numero".$Cont,
        "Identifica" => "Numero",
        "Valor"      => $this->FormIntancia->retornaValor($Metodo,"Numero".$Cont),
        "Largura"    => 10,
        "Max"        => 10,
        "ValidaJS"   => false),false);

        $R["Bairro".$Cont] = $this->FormIntancia->inputTexto(array(
        "Nome"       => "Bairro".$Cont,
        "Identifica" => "Bairro",
        "Valor"      => $this->FormIntancia->retornaValor($Metodo,"Bairro".$Cont),
        "Largura"    => 20,
        "Max"        => 50,
        "ValidaJS"   => false),false);

        $R["CEP".$Cont] = $this->FormIntancia->inputCEP(array(
        "Nome"       => "CEP".$Cont,
        "Identifica" => "CEP",
        "Valor"      => $this->FormIntancia->retornaValor($Metodo,"CEP".$Cont),
        "ValidaJS"   => false),false);

        $R["Complemento".$Cont] = $this->FormIntancia->inputTexto(array(
        "Nome"       => "Complemento".$Cont,
        "Identifica" => "Complemento",
        "Valor"      => $this->FormIntancia->retornaValor($Metodo,"Complemento".$Cont),
        "Largura"    => 20,
        "Max"        => 250,
        "ValidaJS"   => true),false);

        return $R;	
    }


    public function ajaxRetorno()
    {
        $Ajax = new Ajax();

        $this->FormIntancia->setFuncoes($Ajax->ajaxRequest(array(
        "Nome"       => "addEndereco",
        "ParInicial" => "idForm",
        "Parametros" => "{IdForm:idForm}",
        "URL"        => $_SESSION['UrlBase']."cadastros/endereco/endereco.ajax.php?Op=End",
        "Metodo"     => "POST",
        "Completa"   => "function(Req){ retornoEndereco(idForm, Req.responseText); } ")));

        $this->FormIntancia->setFuncoes('
        //Endereço
        function retornoEndereco(idForm, req)
        {
                $("#FormManu"+idForm+" #conteinerEndereco").append(req);
        }

        //Removendo Endereco
        function removeEndereco(idForm, cont)
        {
            var conta = 0;

            $("#FormManu"+idForm+" [@name^=EnderecoDadosTipoCod]").each(function() { conta += 1; });

            if(conta <= 1)
            {
                            alert("Deve haver ao menos um endereço!");
                            return;
            }

            $("#endereco"+cont).remove();
        }
        ');
    }
}