<?
/**
 * 	@copyright DEMP - Soluções em Tecnologia da Informação Ltda
 * 	@author Pablo Vanni - pablovanni@gmail.com
 * 	@since 01/06/2006
 * 	<br>Última Atualização: 28/05/2007<br>
 * 	Autualizada Por: Pablo Vanni - pablovanni@gmail.com<br>
 * 	@name Gera um menu para acesso aos módulos do sistema
 * 	@version 2.0
 *  	@package Framework
 */
//Classes Nescessárias
include_once($_SESSION['FMBase'] . 'conexao.class.php');
include_once($_SESSION['DirBase'] . 'cadastros/usuarios/permissoes_css.class.php');
include_once($_SESSION['DirBase'] . 'cadastros/usuarios/permissoes.sql.php');
include_once($_SESSION['FMBase'] . 'menu.bd.php');

class UsuariosPermissao extends MenuBD {

    /**
     * 	Atributos da Classe
     */
    private $Css;
    private $Html;
    private $Per;
    private $Form;
    private $ModoVis;
    private $JS;
    private $IdForm;
    public $EscondeModulos = array();   // by Bruno Cassol on 9/7/2009: lista de código de módulos a esconder. Ex: 1, 2, 3

    /**
     * 	Metodo Construtor
     * 	@return VOID
     */

    public function  __construct() {
        parent::MenuBd();

        $this->Html = "";
        $this->JS = array();

        $this->setModoVis(true);

        $this->Css = new PermissoesCSS();
        $this->Per = new PermissoesSQL();
        $this->Form = new FormCampos();
    }

    public function setIdForm($Valor) {
        $this->IdForm = $Valor;
    }

    public function setHtml($Valor) {
        $this->Html.= $Valor;
    }

    public function addJS($JsCode) {
        $this->JS[] = $JsCode;
    }

    public function getJS() {
        return '<script type="text/javascript">
		' . join("\n", $this->JS) . '
		</script>';
    }

    public function getHtml() {
        $css = $this->getJS();
        return $css . $this->Html;
    }

    public function setModoVis($Valor) {
        $this->ModoVis = $Valor;
    }

    public function getModoVis() {
        return $this->ModoVis;
    }

    /**
     * 	Gera um menu de acesso aos modulos do sistema
     * 	@return String
     */
    public function geraPermissoes() {
        //Grupos Base
        $ExecGD = parent::gruposDiponiveis();
        $ContGD = $this->Con->nLinhas($ExecGD);

        //Inicia Div
        $this->setHtml("\n<div id=\"PermissoesUsuarios\">\n");

        if ($ContGD > 0) {
            while ($LinhaGrupo = @mysqli_fetch_array($ExecGD)) {
                //Inicia Grupo
                $this->setHtml($this->Css->iniciaGrupo($LinhaGrupo['GrupoCod'], $LinhaGrupo['GrupoDesc'], $this->IdForm));

                //Gera Módulos e submodulos
                $this->geraModulos($LinhaGrupo['GrupoCod']);

                //Finaliza Grupos
                $this->setHtml($this->Css->finalizaGrupo());
            }
        }
        //Finaliza Div
        $this->setHtml("</div>\n");

        return $this->getHtml();
    }

    public function geraModulos($GrupoCod) {
        $RsModulos = parent::modulosGrupoSemReferencia($GrupoCod, "T");
        $NModulos = $this->Con->nLinhas($RsModulos);

        if ($NModulos > 0) {
            while ($DadosModulo = @mysqli_fetch_array($RsModulos)) {
                $this->geraModulo($DadosModulo['ModuloCod']);
            }
        }
    }

    public function geraModulo($ModuloCod, $isSub = false) {
        $DadosModulo = parent::dadosModulo($ModuloCod);

        if(in_array($DadosModulo['ModuloCod'], $this->EscondeModulos)) return;

        //Se módulo Possui Sub Modulo
        if (parent::existeSubModulo($ModuloCod)) {
            $this->geraSubModulo($ModuloCod);
        } else {
            if (is_array($DadosModulo)) {
                //Inicia Módulo
                $this->setHtml($this->Css->iniciaModulo(
                                $ModuloCod, $DadosModulo['NomeMenu'], $this->IdForm, $isSub)
                );
                $RSOpcoes = $this->Con->executar($this->Per->opcoesModulo($ModuloCod));

                $ConteudoPermissoes = '<div class="cbox_grupo">';

                while ($DadosOpcoes = @mysqli_fetch_array($RSOpcoes)) {
                    $ConteudoPermissoes.= ' <div class="cbox">
					  ' . $this->Form->checkBox(array(
                                                "Nome" => $DadosOpcoes['IdPermissao'] . $DadosOpcoes['OpcoesModuloCod'],
                                                "Vale" => "S",
                                                "Identifica" => $DadosOpcoes['NomePermissao'],
                                                "Valor" => $_POST[$DadosOpcoes['IdPermissao'] . $DadosOpcoes['OpcoesModuloCod']],
                                                "Status" => $this->getModoVis(),
                                                "Checked" => false), false) .
                                            '</div>';
                }
                $ConteudoPermissoes .= '</div>';
                $this->setHtml($ConteudoPermissoes);
                //Finaliza Módulo
                $this->setHtml($this->Css->finalizaModulo());
            }
        }
    }

    public function geraSubModulo($ModuloCod) {
        $DadosSubModulo = parent::dadosModulo($ModuloCod);
        $RsModulos = parent::modulosReferentes($ModuloCod);
        $NModulos = $this->Con->nLinhas($RsModulos);

        if ($NModulos > 0) {
            //Inicia Sub Módulo
            $this->setHtml($this->Css->iniciaModulo(
                            $ModuloCod, $DadosSubModulo['NomeMenu'], $this->IdForm, true));
            while ($DadosModulo = @mysqli_fetch_array($RsModulos)) {
                //Se módulo Possui Sub Modulo
                if (parent::existeSubModulo($DadosModulo['ModuloCod'])) {
                    $this->geraSubModulo($DadosModulo['ModuloCod']);
                } else {
                    //Gerando Módulos
                    $this->geraModulo($DadosModulo['ModuloCod'], true);
                }
            }
            //Finaliza Sub Módulo
            $this->setHtml($this->Css->finalizaModulo());
        }
    }
}