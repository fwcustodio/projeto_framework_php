<?
include_once($_SESSION['DirBase'].'cadastros/endereco/endereco.sql.php');
include_once($_SESSION['DirBase'].'cadastros/endereco/endereco.form.php');

class Endereco extends EnderecoSQL
{	
    private $EnderecoDados, $ArrayDadosCod;

    public function Endereco()
    {
        $this->ArrayDadosCod = array();
        $this->EnderecoDados = array();
    }

    public function setEnderecoDados($Dados)
    {
        $this->EnderecoDados[] = $Dados;
    }

    public function setArrayDadosCod($Valor)
    {
        $this->ArrayDadosCod[] = $Valor;
    }

    public function getArrayDadosCod()
    {
        return $this->ArrayDadosCod;
    }

    /*
            Cadastra endereço
    */
    public function cadastrarEndereco($ObjForm)
    {
        //Conexão com o banco
        $Con = Conexao::conectar();

        //Endereço
        $Con->executar(parent::cadastrarEnderecoSql());

        //Endereço Cod Gerado
        $this->setEnderecoCod($Con->ultimoId("endereco", "EnderecoCod"));

        //Recupera o Array Com os Endereços Selecionados
        $ArrayEndereco = $_POST['ArrayEndereco'];

        //Não foi apresentado
        if(!is_array($ArrayEndereco)) throw new Exception("Endereço Inválido");

        //Setando valores do form
        foreach($ArrayEndereco as $Chave=>$Nada) $ObjForm->getFormEndereco($Chave);

        //Seta os dados Apresentados pelo form
        foreach($ArrayEndereco as $Chave=>$Nada)
        {
            $this->setEnderecoDados(array(
            "EnderecoDadosTipoCod" => $ObjForm->getCampoRetorna("EnderecoDadosTipoCod".$Chave,false,"Inteiro"),
            "Estado"               => $ObjForm->getCampoRetorna("Estado".$Chave,true,"Texto"),
            "Cidade"               => $ObjForm->getCampoRetorna("Cidade".$Chave,true,"Texto"),
            "Rua"                  => $ObjForm->getCampoRetorna("Rua".$Chave,true,"Texto"),
            "Numero"               => $ObjForm->getCampoRetorna("Numero".$Chave,true,"Texto"),
            "Bairro"               => $ObjForm->getCampoRetorna("Bairro".$Chave,true,"Texto"),
            "CEP"                  => $ObjForm->getCampoRetorna("CEP".$Chave,true,"Texto"),
            "Complemento"          => $ObjForm->getCampoRetorna("Complemento".$Chave,true,"Texto")));
        }

        //Cadastra os dados do endereço
        $this->cadastrarEnderecoDados();
    }

    public function cadastrarEnderecoDados()
    {
        $Con = Conexao::conectar();

        foreach ($this->EnderecoDados as $ArrayDados)
        {
            $Sql[] = parent::cadastrarEnderecoDadosSql($ArrayDados);
        }

        $Con->executarArray($Sql);
    }


    public function alterarEndereco($ObjForm, $EnderecoCod)
    {
        //Conexão com o banco
        $Con = Conexao::conectar();

        //Remove os dados do endereço
        $this->removerEnderecoDados($EnderecoCod);

        //Endereço Cod Gerado
        $this->setEnderecoCod($EnderecoCod);

        //Recupera o Array Com os Endereços Selecionados
        $ArrayEndereco = $_POST['ArrayEndereco'];

        //Não foi apresentado
        if(!is_array($ArrayEndereco)) throw new Exception("Endereço Inválido");

        //Setando valores do form
        foreach($ArrayEndereco as $Chave=>$Nada) $ObjForm->getFormEndereco($Chave);

        //Seta os dados Apresentados pelo form
        foreach($ArrayEndereco as $Chave=>$Nada)
        {
            $this->setEnderecoDados(array(
            "EnderecoDadosTipoCod" => $ObjForm->getCampoRetorna("EnderecoDadosTipoCod".$Chave,false,"Inteiro"),
            "Estado"               => $ObjForm->getCampoRetorna("Estado".$Chave,true,"Texto"),
            "Cidade"               => $ObjForm->getCampoRetorna("Cidade".$Chave,true,"Texto"),
            "Rua"                  => $ObjForm->getCampoRetorna("Rua".$Chave,true,"Texto"),
            "Numero"               => $ObjForm->getCampoRetorna("Numero".$Chave,true,"Texto"),
            "Bairro"               => $ObjForm->getCampoRetorna("Bairro".$Chave,true,"Texto"),
            "CEP"                  => $ObjForm->getCampoRetorna("CEP".$Chave,true,"Texto"),
            "Complemento"          => $ObjForm->getCampoRetorna("Complemento".$Chave,true,"Texto")));
        }

        //Cadastra os dados do endereço
        $this->cadastrarEnderecoDados();
    }

    /**
    *	Reponsável pela remoção das informações
    *	@return Void
    */
    public function removerEndereco($EnderecoCod)
    {
        $Con = Conexao::conectar();

        $Con->executar(parent::removerEnderecoSql($EnderecoCod));

        $this->removerEnderecoDados($EnderecoCod);
    }

    //Remove endereço por ID
    public function removerEnderecoDados($EnderecoCod)
    {
        //Conexao
        $Con = Conexao::conectar();

        //Dados do Endereço
        $Con->executar(parent::removerEnderecoDadosSql($EnderecoCod));
    }


    public function getEndereco($EnderecoCod, $IdForm, $ObjForm)
    {
        $FPHP = new FuncoesPHP();

        $Con = Conexao::conectar();

        $Sql  = parent::getEnderecoDadosSql($EnderecoCod);

        $HtmlEndereco = "";

        $RSEndereco = $Con->executar($Sql);
        $NEnderecos = $Con->nLinhas($RSEndereco);

        if($NEnderecos > 0)
        {
            while ($DadosEndereco = @mysqli_fetch_assoc($RSEndereco))
            {
                $Cont = $DadosEndereco['EnderecoDadosCod'];

                $Campos = array("EnderecoDadosCod".$Cont, "EnderecoDadosTipoCod".$Cont, "Estado".$Cont, "Cidade".$Cont, "Rua".$Cont, "Numero".$Cont, "Bairro".$Cont, "CEP".$Cont, "Complemento".$Cont);

                $CamposForm = array_combine($Campos, $DadosEndereco);

                $FPHP->extractVar($CamposForm, "POST");

                ob_start();

                $CamposEnd = $ObjForm->getFormEndereco($Cont);
                include($_SESSION['DirBase'].'cadastros/endereco/endereco.tpl.php');
/*				echo '<script language="javascript"> $(document.body).ready(function() { $(\'#FormManu'.$IdForm.' #CEP'.$Cont.'\').mask(\'99999-999\'); }); </script>';*/

                $HtmlEndereco .= ob_get_contents();

                //Limpa Buffer
                ob_end_clean();
            }
        }

        return $HtmlEndereco;
    }
}