<?
include_once($_SESSION['FMBase'].'filtrar.class.php');
include_once($_SESSION['FMBase'].'grid_padrao.class.php');
include_once($_SESSION['FMBase'].'grid_visualizar.class.php');
include_once($_SESSION['FMBase'].'form_campos.class.php');
include_once($_SESSION['DirBase'].PACOTE.'/'.MODULO.'/'.MODULO.'.sql.php');
include_once($_SESSION['DirBase'].'cadastros/endereco/endereco.class.php');
include_once($_SESSION['DirBase'].'cadastros/contato/contato.class.php');


class Usuarios extends UsuariosSQL {

    public function getChave() {
        return "UsuarioCod";
    }

    public function getParametros() {
        $Fil = new Filtrar();

        $Padrao = array("PaginaAtual","QuemOrdena","TipoOrdenacao");

        $MeusParametros = array("UsuarioDadosNome","Login","Situacao");

        $HiddenParametros = $Fil->getHiddenParametros($MeusParametros);

        return array_merge($Padrao, $MeusParametros, $HiddenParametros);
    }

    /**
     *	Repons�vel pela filtragem dos dados na grid
     *	@return String
     */
    public function filtrar($ObjForm) {
        $Gr  = new GridPadrao();

        //Grid de Visualiza��o - Configura��es
        $Gr->setListados(array("UsuarioDadosNome", "Login", "NumeroAcessos","UltimoAcesso","Status"));
        $Gr->setTitulos(array("Nome do Usu�rio", "Login", "N� de Acessos","�ltimo Acesso","Situa��o"));

        //Setando Parametros
        Parametros::setParametros("GET", $this->getParametros());

        //Impress�o
        if($_GET['ModoPrint'] == 'true') {
            $Gr->setQLinhas(0);
            $Gr->setModoImpressao(true);
        }
        else {
            $Gr->setQLinhas(ConfigSIS::$CFG['QLinhasGrid']);
            $Gr->setModoImpressao(false);
        }

        //Configura?s Fixas da Grid
        $Gr->setSql(parent::filtrarSql($ObjForm));
        $Gr->setChave($this->getChave());
        $Gr->setTipoOrdenacao($_GET['TipoOrdenacao']);
        $Gr->setQuemOrdena($_GET['QuemOrdena']);
        $Gr->setPaginaAtual($_GET['PaginaAtual']);
        $Gr->setCondicaoTodosResultados("\$R = (\$Linha['Status'] == 'Inativo') ? true : false;", "legendaVermelho");
        $Gr->setCondicaoTodosResultados("\$R = (\$Linha['Status'] == 'Ativo') ? true : false;", "legendaVerde");

        //Coverte a Data
        $Gr->setObjConverte(new FuncoesPHP(),"convertDataHora","UltimoAcesso",array("UltimoAcesso"));

        //Retornando a Grid Formatada - HTML
        return $Gr->inForm($Gr->montaGridPadrao()."<hr />".$this->getLegenda(),"FormGrid");
    }


    public function getLegenda() {

        return '<table width="100%" border="0" cellpadding="3" cellspacing="0">
                    <tr>
                        <td width="34%" class="fundoLegenda bordaLeg">Legenda:</td>
                        <td width="33%" align="center" class="legendaVerde fTamanho bordaLeg">Usu�rio Ativo</td>
                        <td width="33%" align="center" class="legendaVermelho fTamanho bordaLeg">Usu�rio Inativo</td>
                    </tr>
                </table>';
    }

    /**
     *	Repons�vel pelo ResultSet de visualiza��o dos dados
     *	@return ResultSet
     */
    public function visualizar() 
    {
        //Instancias
        $Con  = Conexao::conectar();
        $FPHP = new FuncoesPHP();		
        $Gr   = new GridVisualizar();
        
        //Grid de Visualiza��o Detalhada
        $Gr->setListados(array("UsuarioDadosNome", "Login", "Email", "NumeroAcessos","UltimoAcesso", "DataCadastro", "Status", "ContatoCod", "EnderecoCod", "Permissao"));
        $Gr->setTitulos(array("Nome do Usu�rio", "Login", "Email", "N� de Acessos","�ltimo Acesso", "Data de Cadastro", "Situa��o", "Contato", "Endere�o", "Permiss�es"));
        
        //Configura��es Fixas da Grid
        $Gr->setChave($this->getChave());
        
        if(!is_array($_POST['SisReg'])) throw new Exception("Nenhum registro selecionado!");

        foreach ($_POST['SisReg'] as $Chave) {
            
            $Gr->setObjConverte($FPHP,"convertDataHora","UltimoAcesso",array("UltimoAcesso"));
            $Gr->setObjConverte($FPHP,"convertDataHoraRetData","DataCadastro",array("DataCadastro"));
            $Gr->setObjConverte($this,"complementoContatoVisualizar","ContatoCod",array("ContatoCod"));
            $Gr->setObjConverte($this,"complementoEnderecoVisualizar","EnderecoCod",array("EnderecoCod"));
            $Gr->setObjConverte($this,"complementoPermissaoVisualizar","Permissao",array("UsuarioCod"));
            
            //Grid
            $Gr->setSql(parent::visualizarSql($Chave));
            $Vis .= $Gr->montaGridVisualizar(); 
        }
        return $Vis;
    }
    
    public function complementoPermissaoVisualizar($UsuarioCod) {
         //Inicia Conex�o
        $Con  = Conexao::conectar();
        $UP   = new UsuariosPermissao();
        
        if($_SESSION['UserName'] != "root") $UP->EscondeModulos = array(2,3);
        
        $UP->setModoVis(false);
        
        $this->getDadosPermissoes($UsuarioCod,"POST");
        return $UP->geraPermissoes();  
    }
    
    public function complementoContatoVisualizar($ContatoCod) {
    //Inicia Conex�o
        $Con  = Conexao::conectar();
        $FPHP = new FuncoesPHP();
        $AuxCod   = "";
        $AuxObs   = "";
        $Aux      = false;
        $Html     = "";

        $Sql = $Con->executar(parent::complementoContatoVisualizarSql($ContatoCod));

        while($Resut = @mysqli_fetch_array($Sql)) {
            if(!empty($Resut['NomeContato']) || !empty($Resut['Contato']) || !empty($Resut['ContatoObservacao'])) {

                if(empty($AuxCod)) $AuxCod = $Resut['ContatoDadosCod'];
                if(empty($AuxObs)) $AuxObs = $Resut['ContatoObservacao'];

                if($AuxCod != $Resut['ContatoDadosCod']) {
                    if(!empty($AuxObs)) {
                        $Html.= "<tr><td><strong>Observa��es:</strong></td><td>".$AuxObs."</td></tr></table></div>";
                    }else {
                        $Html.= "</table></div>";
                    }

                    $AuxCod = $Resut['ContatoDadosCod'];
                    $AuxObs = $Resut['ContatoObservacao'];
                    $Aux    = false;
                }

                if($AuxCod == $Resut['ContatoDadosCod'] && $Aux == false) {
                    $Html.= "<div id='".$AuxCod."' style='width:30%; margin:5px; border:1px solid #d0deee; background:#f4f7f9; float:left'><table>";

                    if(!empty($Resut['NomeContato'])) {
                        $Html.= "<tr><td><strong>Contato:</strong></td><td>".$Resut['NomeContato']."</td></tr>";
                    }
                    $Aux = true;
                }
                if(!empty($Resut['Contato'])) {
                    $Html.= "<tr><td><strong>".$Resut['ContatoCategoria'].":</strong></td><td>".$Resut['Contato']."</td></tr>";
                }

            }
        }

        if(!empty($Html)) {
            if(!empty($AuxObs)) {
                $Html.= "<tr><td><strong>Observa��es:</strong></td><td>".$AuxObs."</td></tr></table></div>";
            }else {
                $Html.= "</table></div>";
            }
        }
        return $Html;
    }

    public function complementoEnderecoVisualizar($EnderecoCod) {
    //Inicia Conex�o
        $Con  = Conexao::conectar();
        $Html = "";

        $Sql = $Con->executar(parent::complementoEnderecoVisualizarSql($EnderecoCod));

        while($Resut = @mysqli_fetch_array($Sql)) {
            $Html.= "<div id='".$Resut['EnderecoDadosCod']."' style='width:30%; margin:5px; border:1px solid #d0deee; background:#f4f7f9; float:left'><table>";

            if(!empty($Resut['EnderecoDadosTipo'])) {
                $Html.= "<tr><td><strong>Tipo de Endere�o:</strong></td><td>".$Resut['EnderecoDadosTipo']."</td></tr>";
            }

            if(!empty($Resut['Estado'])) {
                $Html.= "<tr><td><strong>Estado:</strong></td><td>".$Resut['Estado']."</td></tr>";
            }

            if(!empty($Resut['Cidade'])) {
                $Html.= "<tr><td><strong>Cidade:</strong></td><td>".$Resut['Cidade']."</td></tr>";
            }

            if(!empty($Resut['Rua'])) {
                $Html.= "<tr><td><strong>Logradouro:</strong></td><td>".$Resut['Rua']."</td></tr>";
            }

            if(!empty($Resut['Numero'])) {
                $Html.= "<tr><td><strong>N�mero:</strong></td><td>".$Resut['Numero']."</td></tr>";
            }

            if(!empty($Resut['Bairro'])) {
                $Html.= "<tr><td><strong>Bairro:</strong></td><td>".$Resut['Bairro']."</td></tr>";
            }

            if(!empty($Resut['CEP'])) {
                $Html.= "<tr><td><strong>Cep:</strong></td><td>".$Resut['CEP']."</td></tr>";
            }

            if(!empty($Resut['Complemento'])) {
                $Html.= "<tr><td><strong>Complemento:</strong></td><td>".$Resut['Complemento']."</td></tr>";
            }

            $Html.= "</table></div>";
        }

        return $Html;
    }

    /**
     *	Repons�vel pelo Cadastro das Informa��es
     *	@return Void
     */
    public function cadastrar($ObjForm)
    {
        //Instancias
        $Con     = Conexao::conectar();
        $Contato = new Contato();
        $End     = new Endereco();

        //Inicia Transa��o
        $Con->startTransaction();

        //Inicia Classe de Logs
        $Log = new Log();

        //Verifica Login
        $UserName = $ObjForm->getCampoRetorna('Login');        
        $Status   = $ObjForm->getCampoRetorna('Status');
        $this->verificaLogin($UserName, $Status);
        
        //Verifica Senha
        $Senha = $ObjForm->get('Senha');
        $RepitaSenha = $ObjForm->get('RepitaSenha');
        
        if($Senha != $RepitaSenha) throw new Exception("As senhas informadas n�o correspondem!");
        
        //Log Oculto
        $Con->setLogOculto(true);

        //Cadastra Endereco
        $End->cadastrarEndereco($ObjForm);

        //Cadastra Contatos
        $Contato->cadastrarContato($ObjForm);

        //Log Oculto
        $Con->setLogOculto(false);

        //Cadastra Usuario
        $Con->executar(parent::cadastrarUsuarioSql($ObjForm));

        //Log Oculto
        $Con->setLogOculto(true);

        //Ultimo Id
        $UID = $Con->ultimoId("_usuarios","UsuarioCod");

        //Cadastrando Janelas Para Pagina Inicial...
        $Con->executar("INSERT INTO _janela (ModuloNome, UsuarioCod, Visivel, Coluna, Posicao) VALUES ('bvindas', ".$UID.", 'S', 'A', 1)");
        $Con->executar("INSERT INTO _janela (ModuloNome, UsuarioCod, Visivel, Coluna, Posicao) VALUES ('log', ".$UID.", 'S', 'C', 2)");
        $this->setUsuarioCod($UID);

        //Seta Valores de endereco e contato Grava Usuario Tipo
        $ObjForm->setCampoRetorna("EnderecoCod",$End->getEnderecoCod());
        $ObjForm->setCampoRetorna("ContatoCod",$Contato->getContatoCod());

        //Cadastra Dados do Usuario
        $Con->executar(parent::cadastrarUsuarioDadosSql($ObjForm));

        $Auxiliar = true;
        //Cadastra Permissoes
        // Para cada GRUPO
        $RSGrupos = $Con->executar(parent::gruposDisponiveis());
        while ($DadosGrupo = @mysqli_fetch_array($RSGrupos)) {
            // Para cada M/ODULO
            $RSModulos = $Con->executar(parent::modulosGrupo($DadosGrupo['GrupoCod']));
            while ($DadosModulo = @mysqli_fetch_array($RSModulos)) {
                $RSOpcoes = $Con->executar(parent::opcoesModulo($DadosModulo['ModuloCod']));
                while ($DadosOpcoes = @mysqli_fetch_array($RSOpcoes)) {
                    $ValorOP = $_POST[$DadosOpcoes['IdPermissao'].$DadosOpcoes['OpcoesModuloCod']];
                    
                    if($ValorOP == "S"){
                        $Auxiliar = false;
                        $VOP      = 'S';
                    }else{
                        $VOP      = 'N';
                    }

                    $VSQL[] = parent::cadastraOpcaoSql($DadosOpcoes['OpcoesModuloCod'], $UID, $VOP);
                }
            }
        }

        if($Auxiliar == true) throw new Exception("Deve haver ao menos uma permiss�o selecionada!");
        
        //GRavando Permiss�es
        $Con->executarArray($VSQL);

        //Grava Log
        $Log->geraLog($UID);

        //Log Oculto
        $Con->setLogOculto(true);

        //Finaliza Transa��o
        $Con->stopTransaction();
    }

    /**
     *	Repons�vel pela altera��o das Informa��es
     *	@return Void
     */
    public function alterar($ObjForm) {
        //Instancias
        $Con     = Conexao::conectar();
        $Contato = new Contato();
        $End     = new Endereco();
        
        //Inicia Transa��o
        $Con->startTransaction();

        //Inicia Classe de Logs
        $Log = new Log();
		
        //Verifica Senha
        $Senha = $ObjForm->get('Senha');
        $RepitaSenha = $ObjForm->get('RepitaSenha');
        
        if(!empty($Senha))
        {
            if(empty($RepitaSenha)) throw new Exception("O campo de repiti��o da senha deve ser preenchido!");
            if($Senha != $RepitaSenha) throw new Exception("As senhas informadas n�o correspondem!");
        }
		
        //Usu�rioCod
        $UsuarioCod = $ObjForm->getCampoRetorna('Id');

        //C�digo do Endere�o
        $EnderecoCod = $Con->execRLinha(parent::getDadosUsuarioSql($UsuarioCod),"EnderecoCod");

        //C�digo do Contato
        $ContatoCod = $Con->execRLinha(parent::getDadosUsuarioSql($UsuarioCod),"ContatoCod");

        //Executa Altera��o de Usuario
        $Con->executar(parent::alterarUsuarioSql($ObjForm));

        //Verifica Login
        $UserName = $ObjForm->getCampoRetorna('Login');
        $Status   = $ObjForm->getCampoRetorna('Status');
        $this->verificaLogin($UserName, $Status);

        //Log Oculto
        $Con->setLogOculto(true);

        //Executa Altera��o dos Dados de Usu�rio
        $Con->executar(parent::alterarUsuarioDadosSql($ObjForm, $TipoUsuario));

        //Aletar endere�o Endere�os
        $End->alterarEndereco($ObjForm, $EnderecoCod);

        //Alterar Contato
        $Contato->alterarContato($ObjForm, $ContatoCod);

        //Remove todas as permiss�es do usu�rio
        $Con->executar(parent::removePermissoesSql($UsuarioCod));

        $Auxiliar = true;
        //Cadastra Novas Permissoes
        $RSGrupos = $Con->executar(parent::gruposDisponiveis());
        while ($DadosGrupo = @mysqli_fetch_array($RSGrupos)) {
            $RSModulos = $Con->executar(parent::modulosGrupo($DadosGrupo['GrupoCod']));
            while ($DadosModulo = @mysqli_fetch_array($RSModulos)) {
                $RSOpcoes = $Con->executar(parent::opcoesModulo($DadosModulo['ModuloCod']));
                while ($DadosOpcoes = @mysqli_fetch_array($RSOpcoes)) {
                    $ValorOP = $_POST[$DadosOpcoes['IdPermissao'].$DadosOpcoes['OpcoesModuloCod']];

                    if($ValorOP == "S"){
                        $Auxiliar = false;
                        $VOP      = 'S';
                    }else{
                        $VOP      = 'N';
                    }

                    $VSQL[] = parent::cadastraOpcaoSql($DadosOpcoes['OpcoesModuloCod'], $UsuarioCod, $VOP);
                }
            }
        }

        if($Auxiliar == true) throw new Exception("Deve haver ao menos uma permiss�o selecionada!");
        
        //GRavando Permiss�es
        $Con->executarArray($VSQL);

        //Grava Log
        $Log->geraLog($UsuarioCod);

        //Log Oculto
        $Con->setLogOculto(false);

        //Finaliza Transa��o
        $Con->stopTransaction();
    }

    /**
     *	Repons�vel pela exclus�o das Informa��es
     *	@return Void
     */
    public function remover()
    {
        //Inicia Variaveis de Buffer
        $Mensagem      = array();//Array de Mensagens
        $RSelecionados = count($_POST['SisReg']);//Numero de Registros Selecionados na Grid
        $RApagados     = 0;//Numero de Registros Apagados

        $Contato = new Contato();
        $End     = new Endereco();

        //Intercepta Erros
        try
        {
            //Inicia Conex�o
            $Con = Conexao::conectar();

            //Inicia Transa��o
            $Con->startTransaction();

            //Inicia Classe de Logs
            $Log = new Log();

            //Verifica se Existem Registros Selecionados
            if(!is_array($_POST['SisReg']))
            {
                $Mensagem[] = "Nenhum registro selecionado!";
            }
            else
            {
                //Percorre Array de Registros
                foreach ($_POST['SisReg'] as $Chave)
                {
                    //Verifica se j� existe alguma opera��o
                    if($Con->existe("_log","UsuarioCod",$Chave))
                    {
                       $Con->executar(parent::inativaUsuarioSql($Chave));

                       $RApagados += 1;

                        //Grava Log
                        $Log->geraLog($Chave);
                    }
                    else
                    {
                        //Log Oculto
                        $Con->setLogOculto(true);

                        //C�digo do Endere�o
                        $EnderecoCod = $Con->execRLinha(parent::getDadosUsuarioSql($Chave),"EnderecoCod");

                        //C�digo do Contato
                        $ContatoCod = $Con->execRLinha(parent::getDadosUsuarioSql($Chave),"ContatoCod");

                        //Remove Contato
                        $Contato->removerContato($ContatoCod);
                        
                        //Remove Endereco
                        $End->removerEndereco($EnderecoCod);

                        //Log Oculto
                        $Con->setLogOculto(false);
                        
                       //Remove Usu�rio
                        $Con->executar(parent::removerSql($Chave));
						
                       //Remove Janelas
                        $Con->executar(parent::removerJanelasSql($Chave));
						
                       //Remove Permissoes
                        $Con->executar(parent::removerPermissoesSql($Chave));

                        $RApagados += 1;

                        //Grava Log
                        $Log->geraLog($Chave);
                    }
                }
            }

            //Finaliza Transa��o
            $Con->stopTransaction();
        }
        catch (Exception $E)
        {
            $Mensagem[] =  $E->getMessage();
        }

        //Saida Array Javascript
        return 'var retorno = {"selecionados":'.$RSelecionados.', "apagados":'.$RApagados.',"mensagem":"'.implode("\\n",str_replace("<br>","\\n",$Mensagem)).'"}';
    }

    /**
     *	Repons�vel pela recupera��o dos dados gravado no banco de dados
     *	@return Void
     */
    public function getDados($Id, $Metodo) {
        $FPHP = new FuncoesPHP();

        $Con = Conexao::conectar();

        $Sql  = parent::getDadosSql($Id);

        $DadosSql = array_values($Con->execLinhaArray($Sql));

        $Campos = array("Id", "Login","Email","Status");

        $CamposForm = @array_combine($Campos, $DadosSql);

        $FPHP->extractVar($CamposForm, $Metodo);

        $this->getDadosPermissoes($Id,$Metodo);
    }

    public function getDadosUsuario($Id, $Metodo) {
        $FPHP = new FuncoesPHP();

        $Con = Conexao::conectar();

        $Sql  = parent::getDadosUsuarioSql($Id);

        $DadosSql = array_values($Con->execLinhaArray($Sql));

        $Campos = array("EnderecoCod", "ContatoCod", "UsuarioDadosNome", "UsuarioDadosNascimento");

        $CamposForm = array_combine($Campos, $DadosSql);

        $FPHP->extractVar($CamposForm, $Metodo);
    }

    public function getDadosPermissoes($Id,$Metodo) {
        $Con = Conexao::conectar();

        $RSModulos = $Con->executar(parent::modulos());
        while ($DadosModulo = @mysqli_fetch_array($RSModulos)) {
            $RSOpcoes = $Con->executar(parent::opcoesModulo($DadosModulo['ModuloCod']));
            while ($DadosOpcoes = @mysqli_fetch_array($RSOpcoes)) {
                $Sql = parent::tipoPermissao($DadosOpcoes['OpcoesModuloCod'],$Id);

                $VOP = $Con->execRLinha($Sql);

                $_POST[$DadosOpcoes['IdPermissao'].$DadosOpcoes['OpcoesModuloCod']] = $VOP;
            }
        }
    }


    public function verificaLogin($UserName, $Status)
    {
        $Con = Conexao::conectar();
        
        if($Status == "A")
        {
           $NLogins = $Con->execRLinha(parent::verificaLoginSql($UserName),"Total");
           
           if($NLogins > 1) throw new Exception("O login $UserName j� esta sendo usado no sistema!");
        }
    }
}