<?
class PermissoesSQL {

    public function gruposDisponiveis() {
        $Sql = "SELECT GrupoCod, GrupoDesc
                  FROM _grupomodulo
              ORDER BY Posicao ASC";

        return $Sql;
    }

    public function modulos() {
        $Sql = "SELECT ModuloCod, NomeMenu
		  FROM _modulos
              ORDER BY Posicao ASC ";
        return $Sql;
    }

    public function modulosGrupo($GrupoCod) {
        $Sql = "SELECT ModuloCod, NomeMenu
		  FROM _modulos
	         WHERE GrupoCod = $GrupoCod
	      ORDER BY Posicao ASC";

        return $Sql;
    }

    public function opcoesModulo($ModuloCod) {
        $Sql = "SELECT OpcoesModuloCod, ModuloCod, NomePermissao, IdPermissao
                  FROM _opcoes_modulo
                 WHERE ModuloCod = $ModuloCod
              ORDER BY Posicao ASC ";

        return $Sql;
    }

    /* PERMISSOES DE USUARIOS */

    public function tipoPermissao($OpcoesModuloCod, $UsuarioCod) {
        $Sql = "SELECT Permissao
                  FROM _tipo_permissao
                 WHERE UsuarioCod     = $UsuarioCod
                   AND OpcoesModuloCod = $OpcoesModuloCod ";

        return $Sql;
    }

    public function cadastraOpcaoSql($OpcoesModuloCod, $UsuarioCod, $Permissao) {
        $Sql = "INSERT INTO _tipo_permissao (OpcoesModuloCod, UsuarioCod, Permissao) VALUES ($OpcoesModuloCod, $UsuarioCod,	'$Permissao')";

        return $Sql;
    }

    public function removePermissoesSql($UsuarioCod) {
        $Sql = "DELETE FROM _tipo_permissao WHERE UsuarioCod = $UsuarioCod";

        return $Sql;
    }

    /* CONFIGURAÇÔES DE ACESSO */

    public function tipoConfiguracaoAcessoPermissao($OpcoesModuloCod, $UsuarioTipoCod) {
        $Sql = "SELECT Permissao
                  FROM usuario_tipo_config
                 WHERE UsuarioTipoCod = $UsuarioTipoCod
                   AND OpcoesModuloCod = $OpcoesModuloCod";

        return $Sql;
    }

    public function cadastraConfiguracaoAcessoSql($OpcoesModuloCod, $UsuarioTipoCod, $Permissao) {
        $Sql = "INSERT INTO usuario_tipo_config (OpcoesModuloCod, UsuarioTipoCod, Permissao) VALUES ($OpcoesModuloCod, $UsuarioTipoCod,	'$Permissao')";

        return $Sql;
    }

    public function removeConfiguracaoAcessoSql($UsuarioTipoCod) {
        $Sql = "DELETE FROM usuario_tipo_config WHERE UsuarioTipoCod = $UsuarioTipoCod";

        return $Sql;
    }
}