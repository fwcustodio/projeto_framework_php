<?
include_once($_SESSION['FMBase'] . 'filtrar.class.php');
include_once('permissoes.sql.php');

class UsuariosSQL extends PermissoesSQL {

    private $UsuarioCod;

    public function setUsuarioCod($UsuarioCod) {
        $this->UsuarioCod = $UsuarioCod;
    }

    public function getUsuarioCod() {
        return $this->UsuarioCod;
    }

    public function filtrarSql($ObjForm) {
        //Filtro Dinamico
        $Fil = new Filtrar($ObjForm);

        $Sql = "SELECT a.UsuarioCod, b.UsuarioDadosNome, a.Login, a.NumeroAcessos, a.UltimoAcesso, 
                       IF(a.Status = 'A', 'Ativo', 'Inativo')AS Status 
                  FROM _usuarios a
            INNER JOIN usuario_dados b ON a.UsuarioCod = b.UsuarioCod
                 WHERE 1";

        $Sql .= $Fil->getStringSql("UsuarioDadosNome", "b.UsuarioDadosNome");
        $Sql .= $Fil->getStringSql("Login", "a.Login");
        $Sql .= $Fil->getStringSql("Status", "a.Status");

        //Sql de Impressão
        $Sql .= $Fil->printSql("a.UsuarioCod", $_GET['SisReg']);

        return $Sql;
    }

    public function visualizarSql($Cod) {

        $Sql = "SELECT a.UsuarioCod, b.UsuarioDadosNome, a.Login, a.NumeroAcessos, a.UltimoAcesso, a.Email, a.DataCadastro,
                       IF(a.Status = 'A', 'Ativo', 'Inativo')AS Status,
                       b.ContatoCod, b.EnderecoCod
		  FROM _usuarios a
            INNER JOIN usuario_dados b ON a.UsuarioCod = b.UsuarioCod
		 WHERE a.UsuarioCod = $Cod";

        return $Sql;
    }
    
    public function complementoContatoVisualizarSql($ContatoCod)
    {
        $Sql = "SELECT b.ContatoDadosCod, b.NomeContato, d.ContatoCategoria, c.Contato, b.ContatoObservacao
                  FROM contato a
            INNER JOIN contato_dados b ON a.ContatoCod = b.ContatoCod
            INNER JOIN contato_tipo c ON b.ContatoDadosCod = c.ContatoDadosCod
            INNER JOIN contato_categoria d ON c.ContatoCategoriaCod = d.ContatoCategoriaCod
                 WHERE a.ContatoCod = $ContatoCod";

        return $Sql;
    }

    public function complementoEnderecoVisualizarSql($EnderecoCod)
    {
        $Sql = "SELECT b.EnderecoDadosCod, c.EnderecoDadosTipo, b.Estado, b.Cidade, b.Rua, b.Numero, b.Bairro, b.CEP, b.Complemento
                  FROM endereco a
            INNER JOIN endereco_dados b ON a.EnderecoCod = b.EnderecoCod
            INNER JOIN endereco_dados_tipo c ON b.EnderecoDadosTipoCod = c.EnderecoDadosTipoCod
                 WHERE a.EnderecoCod = $EnderecoCod";

        return $Sql;
    }

    public function cadastrarUsuarioSql($ObjForm) {
        //Criptografando Senha
        $SenhaDeUsuario = crypt($ObjForm->getCampoRetorna('Senha'), ConfigSIS::$CFG['StringCrypt']);

        //Variaveis
        $VAR[] = $ObjForm->getCampoRetorna('UsuarioDadosNome', false, "Texto");
        $VAR[] = $ObjForm->getCampoRetorna('Login', false, "Texto");
        $VAR[] = $SenhaDeUsuario;
        $VAR[] = $ObjForm->getCampoRetorna('Email', false, "Texto");
        $VAR[] = $ObjForm->getCampoRetorna('Status', false, "Texto");

        $Sql = "INSERT INTO _usuarios (Nome, Login, Senha, Email, DataCadastro, Status) VALUES (%s, %s, '%s', %s, now(), %s)";

        return vsprintf($Sql, $VAR);
    }

    public function cadastrarUsuarioDadosSql($ObjForm) {
        $VAR[] = $ObjForm->getCampoRetorna("EnderecoCod", false, "Inteiro");
        $VAR[] = $ObjForm->getCampoRetorna("ContatoCod", false, "Inteiro");
        $VAR[] = $ObjForm->getCampoRetorna("UsuarioDadosNome", false, "Texto");
        $VAR[] = $ObjForm->getCampoRetorna("UsuarioDadosNascimento", true, "Data");

        $Sql = "INSERT INTO usuario_dados 
                        (UsuarioCod, EnderecoCod, ContatoCod, UsuarioDadosNome, UsuarioDadosNascimento) VALUES
                        (" . $this->getUsuarioCod() . ", %s, %s, %s, %s)";

        return vsprintf($Sql, $VAR);
    }

    public function alterarUsuarioSql($ObjForm) {
        $Senha = $ObjForm->getCampoRetorna('Senha');

        //Criptografando Senha
        if (!empty($Senha)) $SenhaDeUsuario = crypt($Senha, ConfigSIS::$CFG['StringCrypt']);

        //Variaveis
        $VAR[] = $ObjForm->getCampoRetorna('UsuarioDadosNome', false, "Texto");
        $VAR[] = $ObjForm->getCampoRetorna('Email', false, "Texto");
        $VAR[] = $ObjForm->getCampoRetorna('Status', false, "Texto");
        $VAR[] = $ObjForm->getCampoRetorna('Id');

        $Sql = "UPDATE _usuarios SET Nome = %s, Email = %s, Status = %s";

        if (!empty($SenhaDeUsuario)) $Sql.= ", Senha = '$SenhaDeUsuario'";

        $Sql .= " WHERE UsuarioCod = %s";

        return vsprintf($Sql, $VAR);
    }

    public function alterarUsuarioDadosSql($ObjForm) {
        $VAR[] = $ObjForm->getCampoRetorna("UsuarioDadosNome", false, "Texto");
        $VAR[] = $ObjForm->getCampoRetorna("UsuarioDadosNascimento", true, "Data");
        $VAR[] = $ObjForm->getCampoRetorna("Id", false, "Inteiro");

        $Sql = "UPDATE usuario_dados SET  UsuarioDadosNome = %s, UsuarioDadosNascimento = %s WHERE UsuarioCod =  %s ";

        return vsprintf($Sql, $VAR);
    }

    public function getDadosSql($Id) {
        $Sql = "SELECT UsuarioCod, Login, Email, Status 
                  FROM _usuarios
                 WHERE UsuarioCod = $Id ";

        return $Sql;
    }

    public function getDadosUsuarioSql($UsuarioCod) {
        $Sql = "SELECT EnderecoCod, ContatoCod, UsuarioDadosNome, DATE_FORMAT(UsuarioDadosNascimento,'%d/%m/%Y') UsuarioDadosNascimento
                  FROM usuario_dados 
                 WHERE UsuarioCod = $UsuarioCod";

        return $Sql;
    }

    public function removerSql($Cod) {
        $Sql = "DELETE FROM _usuarios WHERE UsuarioCod = $Cod";

        return $Sql;
    }

    public function removerJanelasSql($Cod) {
        $Sql = "DELETE FROM _janela WHERE UsuarioCod = $Cod";

        return $Sql;
    }

    public function removerPermissoesSql($Cod) {
        $Sql = "DELETE FROM _tipo_permissao WHERE UsuarioCod = $Cod";

        return $Sql;
    }

    public function inativaUsuarioSql($Cod) {
        $Sql = "UPDATE _usuarios SET Status = 'I' WHERE UsuarioCod = $Cod";

        return $Sql;
    }

    public function verificaLoginSql($UserName) {
        $Sql = "SELECT COUNT(UsuarioCod) as Total FROM _usuarios WHERE Login = '$UserName' AND Status = 'A'";

        return $Sql;
    }

}