<?php

class ConfigSIS {

    //Configura��es Do Sistema
    public static $CFG;
    private static $Instancia;

    /*
     * *Construtor
     */

    function ConfigSIS() {
        $this->setDiretorios();

    }
    // Remove the extra closing brace


    /*
     * * Padr�o SINGLETON para Instanciar as Configuira��es
     */

    static function Conf() {
        if (!isset(self::$Instancia)) {
            self::$Instancia = new ConfigSIS();
        }

        return self::$Instancia;
    }

    function setDiretorios() {
        if(!isset($_SESSION)){session_start();}

        if (!isset($_SESSION['DirBase'])) {
    
            $ROOT = $_SERVER['DOCUMENT_ROOT'];
            $HOST = "http://" . $_SERVER['HTTP_HOST'];
            $CLIENTE = "/engine_site/"; 
            $FRAMEWORK = '__FRAMEWORK__';

                 

            //Site & Sistema
            $_SESSION['DirBaseSite']       = $ROOT.'/';
            $_SESSION['UrlBaseSite']       = $HOST.$CLIENTE;
            $_SESSION['DirBase']           = $_SESSION['DirBaseSite']."sis/";
            $_SESSION['UrlBase']           = $_SESSION['UrlBaseSite']."sis/";
            $_SESSION['LocalDir']          = $ROOT.'/';

            //FrameWork
            $_SESSION['UrlFMBase']         =  $HOST.$CLIENTE.$FRAMEWORK."/__FMBASE__/";
            $_SESSION['FMBase']            =  $_SESSION['DirBaseSite'] .$FRAMEWORK."/__FMBASE__/";
            $_SESSION['JSBase']            =  $_SESSION['DirBaseSite'] .$FRAMEWORK."/__JSBASE__/";
            $_SESSION['CSSBase']           =  $_SESSION['DirBaseSite'] .$FRAMEWORK."/__CSSBASE__/";



        }

    }

    public function load(){
        @header("Content-Type: text/html; charset=ISO-8859-1",true);
        require_once $_SESSION['FMBase'].'framework_site/front_controllers/front_controller.php';

        function __autoload($ClassName) {
            $Front = FrontController::getInstancia();
            $DadosClasse = $Front->getDadosClassInclude($ClassName);

            if($DadosClasse->Entidade == 'base'){
                $DiretorioArquivoClasse = $_SESSION['FMBase'].'framework_site/'.$DadosClasse->Modulo.'/'.$DadosClasse->NomeArquivo;
            }
            else{
                $DiretorioArquivoClasse = $DadosClasse->EnderecoArquivo.$DadosClasse->NomeArquivo;
            }

            if(file_exists($DiretorioArquivoClasse) && !is_dir($DiretorioArquivoClasse)){
                require_once $DiretorioArquivoClasse;
                !defined('Modulo')?define('Modulo', $DadosClasse->Modulo.'/'):'';
            }
            else{
                var_dump($DadosClasse);
                throw new ErrorException("N�o foi poss�vel carregar a classe solicitada - ".$ClassName);
            }
        }
    }

}