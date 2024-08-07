<?php

require_once $_SESSION['DirBaseSite'] . 'engine_site/classes/engine_site_conteudo_arquivos_class.php';
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of home
 *
 * @author fernando
 */
class EngineSiteController extends ControllerBase {

    private static $Instancia;
    private $EngineSiteModel;
    private $ConteudoArquivos;

    private function __construct() {
        $this->EngineSiteModel = new EngineSiteModel();
        $this->ConteudoArquivos = new EngineSiteConteudoArquivosClass();
    }

    public static function getInstancia() {
        if (is_a(self::$Instancia, 'EngineSiteController')) {
            return self::$Instancia;
        } //verifica se o atributo ja est� instanciado: true retorna a instancia
        else {
            self::$Instancia = new EngineSiteController();
            return self::$Instancia;
        }
    }

    public function init() {
        return array();
    }

    public function getScripts() {
        return array();
    }

    public function getParametros() {
        return array();
    }

    public function gerarArquivos($Parametros) {
        $CaminhoSitema = $_SESSION['LocalDir'] . $Parametros['PastaSistema'] . '/';
        $CaminhoModulo = $CaminhoSitema . $Parametros['NomeModulo'] . '/';
        $Modulo = $Parametros['NomeModulo'];
        $Layout = $Parametros['Layout'];
        $ArquivoBase = $_SESSION['DirBaseSite'] . 'engine_site/arquivos/arquivo.php';
        $this->ConteudoArquivos->setModulo($Modulo);

        

        //verifica se est� tudo ok com os diret�rios para o proximo passso
        $this->verificaIntegridadeDiretorios($CaminhoSitema, $CaminhoModulo);

        //cria a pasta arquivos, se n�o existir
        if (isset($Parametros['checkBoxArquivos'])) {
            $this->criarDiretorio($CaminhoModulo . 'arquivos');
        }

        //cria a pasta ajax e o arquivo modulo_ajax.php
        if (isset($Parametros['checkBoxAjax'])) {
            $Tipo = 'ajax';
            $CaminhhoArquivo = $CaminhoModulo . $Tipo . '/' . $Modulo . '_' . $Tipo . '.php';

            $this->criarDiretorio($CaminhoModulo . $Tipo);
            copy($ArquivoBase, $CaminhhoArquivo);
            @chmod($CaminhhoArquivo, 0777);

            $Fp = @fopen($CaminhhoArquivo, "w");
            fwrite($Fp, $this->ConteudoArquivos->getAjax());
            fclose($Fp);
        }


        //cria a pasta controller e o arquivo modulo_controller.php
        if (isset($Parametros['checkBoxController'])) {
            $Tipo = 'controller';
            $CaminhhoArquivo = $CaminhoModulo . $Tipo . '/' . $Modulo . '_' . $Tipo . '.php';

            $this->criarDiretorio($CaminhoModulo . $Tipo);
            copy($ArquivoBase, $CaminhhoArquivo);
            @chmod($CaminhhoArquivo, 0777);

            $Fp = @fopen($CaminhhoArquivo, "w");
            fwrite($Fp, $this->ConteudoArquivos->getController());
            fclose($Fp);
        }


        //cria a pasta css e o arquivo main.css
        if (isset($Parametros['checkBoxCss'])) {
            $Tipo = 'css';
            $CaminhhoArquivo = $CaminhoModulo . $Tipo . '/main.css';

            $this->criarDiretorio($CaminhoModulo . $Tipo);
            copy($ArquivoBase, $CaminhhoArquivo);
            @chmod($CaminhhoArquivo, 0777);

            $Fp = @fopen($CaminhhoArquivo, "w");
            fwrite($Fp, $this->ConteudoArquivos->getCSS());
            fclose($Fp);
        }


        //cria a pasta form e o arquivo modulo_form.php
        if (isset($Parametros['checkBoxForm'])) {
            $Tipo = 'form';
            $CaminhhoArquivo = $CaminhoModulo . $Tipo . '/' . $Modulo . '_' . $Tipo . '.php';

            $this->criarDiretorio($CaminhoModulo . $Tipo);
            copy($ArquivoBase, $CaminhhoArquivo);
            @chmod($CaminhhoArquivo, 0777);

            $Fp = @fopen($CaminhhoArquivo, "w");
            fwrite($Fp, $this->ConteudoArquivos->getForm());
            fclose($Fp);
        }


        //cria a pasta js e o arquivo main.js
        if (isset($Parametros['checkBoxJs'])) {
            $Tipo = 'js';
            $CaminhhoArquivo = $CaminhoModulo . $Tipo . '/main.js';

            $this->criarDiretorio($CaminhoModulo . $Tipo);
            copy($ArquivoBase, $CaminhhoArquivo);
            @chmod($CaminhhoArquivo, 0777);

            $Fp = @fopen($CaminhhoArquivo, "w");
            fwrite($Fp, $this->ConteudoArquivos->getJS());
            fclose($Fp);
        }


        //cria a pasta model e o arquivo modulo_model.php
        if (isset($Parametros['checkBoxModel'])) {
            $Tipo = 'model';
            $CaminhhoArquivo = $CaminhoModulo . $Tipo . '/' . $Modulo . '_' . $Tipo . '.php';

            $this->criarDiretorio($CaminhoModulo . $Tipo);
            copy($ArquivoBase, $CaminhhoArquivo);
            @chmod($CaminhhoArquivo, 0777);

            $Fp = @fopen($CaminhhoArquivo, "w");
            fwrite($Fp, $this->ConteudoArquivos->getModel());
            fclose($Fp);
        }


        //cria a pasta views e os arquivos modulo_view_nome_view.php
        if (isset($Parametros['checkBoxViews'])) {
            $Tipo = 'views';
            $ArrayViews = explode('|', $Parametros['Views']);

            $this->criarDiretorio($CaminhoModulo . $Tipo);

            foreach ($ArrayViews as $View) {
                $CaminhhoArquivo = $CaminhoModulo . $Tipo . '/' . $Modulo . '_view_' . $View . '.phtml';
                copy($ArquivoBase, $CaminhhoArquivo);
                @chmod($CaminhhoArquivo, 0777);

                $Fp = @fopen($CaminhhoArquivo, "w");
                fwrite($Fp, $this->ConteudoArquivos->getView());
                fclose($Fp);
            }
        }

        //cria o arquivo index.php
        if (isset($Parametros['checkBoxIndex'])) {
            $CaminhhoArquivo = $CaminhoModulo . 'index.php';

            copy($ArquivoBase, $CaminhhoArquivo);
            @chmod($CaminhhoArquivo, 0777);

            $Fp = @fopen($CaminhhoArquivo, "w");
            fwrite($Fp, $this->ConteudoArquivos->getIndex($Layout));
            fclose($Fp);
        }


        /* LAYOUTS */
        if (isset($Parametros['Layout']) && isset($Parametros['checkBoxOpcaoLayout'])) {
            $Layout = $Parametros['Layout'];
            $EndBaseLayout = $CaminhoSitema . 'layouts/';
            $this->criarDiretorio($EndBaseLayout);
            $EndBaseArquivo = $EndBaseLayout . $Layout . '/';
            $this->criarDiretorio($EndBaseArquivo);

            $this->criarDiretorio($EndBaseArquivo . 'model');
            $ArquivoModel = $EndBaseArquivo . 'model' . '/layouts_' . $Layout . '_model.php';

            if (!file_exists($ArquivoModel)) {
                copy($ArquivoBase, $ArquivoModel);
                @chmod($ArquivoModel, 0777);

                $Fp = @fopen($ArquivoModel, "w");
                fwrite($Fp, $this->ConteudoArquivos->getModel('layouts_' . $Layout));
                fclose($Fp);
            }

            $this->criarDiretorio($EndBaseArquivo . 'controller');
            $ArquivoController = $EndBaseArquivo . 'controller' . '/layouts_' . $Layout . '_controller.php';

            if (!file_exists($ArquivoController)) {
                copy($ArquivoBase, $ArquivoController);
                @chmod($ArquivoController, 0777);

                $Fp = @fopen($ArquivoController, "w");
                fwrite($Fp, $this->ConteudoArquivos->getController('layouts_' . $Layout, $Tipo = 'layout'));
                fclose($Fp);
            }


            $CaminhhoArquivo = $EndBaseArquivo . '/layouts_' . $Layout . '.phtml';

            if (!file_exists($CaminhhoArquivo)) {
                copy($ArquivoBase, $CaminhhoArquivo);
                @chmod($CaminhhoArquivo, 0777);

                $Fp = @fopen($CaminhhoArquivo, "w");
                fwrite($Fp, $this->ConteudoArquivos->getLayout());
                fclose($Fp);
            }
        }
        
        $this->criaViewsLayout($CaminhoSitema.'layouts/views/', $ArquivoBase);
        
        $this->criarDiretorio($CaminhoSitema.'css');
        $CaminhhoArquivo = $CaminhoSitema . 'css/main.css';
        if (!file_exists($CaminhhoArquivo)) {
            copy($ArquivoBase, $CaminhhoArquivo);
            @chmod($CaminhhoArquivo, 0777);

            $Fp = @fopen($CaminhhoArquivo, "w");
            fwrite($Fp, $this->ConteudoArquivos->getCss());
            fclose($Fp);
        }
        
        $this->criarDiretorio($CaminhoSitema.'js');
        $CaminhhoArquivo = $CaminhoSitema . 'js/main.js';
        if (!file_exists($CaminhhoArquivo)) {
            copy($ArquivoBase, $CaminhhoArquivo);
            @chmod($CaminhhoArquivo, 0777);

            $Fp = @fopen($CaminhhoArquivo, "w");
            fwrite($Fp, $this->ConteudoArquivos->getJS());
            fclose($Fp);
        }
        
        
        
        


        return true;
    }

    private function criaViewsLayout($CaminhoPastaViews, $ArquivoBase) {
        $this->criarDiretorio($CaminhoPastaViews);

        $this->criarDiretorio($CaminhoPastaViews . 'rodape');
        $CaminhhoArquivo = $CaminhoPastaViews . 'rodape/layouts_views_rodape.phtml';
        if (!file_exists($CaminhhoArquivo)) {
            copy($ArquivoBase, $CaminhhoArquivo);
            @chmod($CaminhhoArquivo, 0777);

            $Fp = @fopen($CaminhhoArquivo, "w");
            fwrite($Fp, $this->ConteudoArquivos->getView());
            fclose($Fp);
        }


        $this->criarDiretorio($CaminhoPastaViews . 'rodape/model');
        $CaminhhoArquivo = $CaminhoPastaViews . 'rodape/model/layouts_views_rodape_model.php';
        if (!file_exists($CaminhhoArquivo)) {
            copy($ArquivoBase, $CaminhhoArquivo);
            @chmod($CaminhhoArquivo, 0777);

            $Fp = @fopen($CaminhhoArquivo, "w");
            fwrite($Fp, $this->ConteudoArquivos->getModel('layouts_views_rodape'));
            fclose($Fp);
        }


        $this->criarDiretorio($CaminhoPastaViews . 'topo');
        $CaminhhoArquivo = $CaminhoPastaViews . 'topo/layouts_views_topo.phtml';
        if (!file_exists($CaminhhoArquivo)) {
            copy($ArquivoBase, $CaminhhoArquivo);
            @chmod($CaminhhoArquivo, 0777);

            $Fp = @fopen($CaminhhoArquivo, "w");
            fwrite($Fp, $this->ConteudoArquivos->getView());
            fclose($Fp);
        }


        $this->criarDiretorio($CaminhoPastaViews . 'topo/model');
        $CaminhhoArquivo = $CaminhoPastaViews . 'topo/model/layouts_views_topo_model.php';
        if (!file_exists($CaminhhoArquivo)) {
            copy($ArquivoBase, $CaminhhoArquivo);
            @chmod($CaminhhoArquivo, 0777);

            $Fp = @fopen($CaminhhoArquivo, "w");
            fwrite($Fp, $this->ConteudoArquivos->getModel('layouts_views_topo'));
            fclose($Fp);
        }
    }

    /* Verifica se os diret�rios existem e define as permiss�es para leitura e grava��o
     * se a pasta do m�dulo n�o existir o metodo tenta criar-lo e atribuir para eles
     * as permiss�es
     * se a pasta do sistema n�o existir ent�o � gerado um alerta para o usu�rio de erro */

    private function verificaIntegridadeDiretorios($CaminhoSitema, $CaminhoModulo) {
        if (is_dir($CaminhoSitema)) {
            if (is_dir($CaminhoModulo)) {
//                if (!@chmod($CaminhoModulo, 0777)) {
//                    echo 'N�o foi poss�vel alterar as permiss�es na pasta do m�dulo!';
//                    exit();
//                }
                @chmod($CaminhoModulo, 0777); // tenta dar permiss�es para a pasta
            } else {
                $CriouPastaModulo = @mkdir($CaminhoModulo, 0777);
                if ($CriouPastaModulo) {
//                    if (!@chmod($CaminhoModulo, 0777)) {
//                        echo 'N�o foi poss�vel alterar as permiss�es na pasta do m�dulo!';
//                        exit();
//                    }
                    @chmod($CaminhoModulo, 0777); // tenta dar permiss�es para a pasta
                } else {
                    echo 'O m�dulo passado n�o existe e n�o pode ser criado!';
                    exit();
                }
            }
        } else {
            echo 'Pasta do sitema n�o encontrada no endere�o ' . $CaminhoSitema;
            exit();
        }
    }

    /* verifica se um diretorio ja existe
     * se existir tenta atribuir permiss�o de leitura e escrita para ele
     * se n�o existir tenta cria-lo e atribuir permiss�o de leitura e escrita */

    private function criarDiretorio($Diretorio) {
        if (!is_dir($Diretorio)) {
            @mkdir($Diretorio, 0777);
        }
        return @chmod($Diretorio, 0777);
    }
}
?>