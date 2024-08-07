<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of funcoes_php_site
 *
 * @package framework_site
 * @author fernando
 * @data 30/03/2012
 */
class FuncoesPhpSite {
    private static $Instancia ;

    private function __construct() {}

    public static function getInstancia(){
        if(is_a(self::$Instancia, 'FuncoesPhpSite')){return self::$Instancia;} //verifica se o atributo ja está instanciado: true retorna a instancia
        else{self::$Instancia = new FuncoesPhpSite();return self::$Instancia;}
    }

    /*Converte um nome de classe padrão em nome de arquivo
     *ex.: classe MinhaClasseBase - retorno minha_classe_base*/
    public function converteNomeClasseToNomeArquivo($NomeClasse) {
        $NomeArquivo = '';
        $Tam = strlen($NomeClasse);

        for ($index = 0; $index < $Tam; $index++) {
            $MycharAtual = $NomeClasse[$index];
            if (!strcmp($MycharAtual, strtoupper($MycharAtual)) && $index > 0) {
                $NomeArquivo .= '_' . strtolower($MycharAtual);
            } else if (!strcmp($MycharAtual, strtoupper($MycharAtual))) {
                $NomeArquivo .= strtolower($MycharAtual);
            } else {
                $NomeArquivo .= $MycharAtual;
            }
        }
        return $NomeArquivo;
    }

     public function converteNomeArquivoToNomeClasse($NomeArquivo) {
         $NomeClasse = '';
         if($NomeArquivo){
             $ArrayPartes = explode('_', $NomeArquivo);
             if(is_array($ArrayPartes)){
                 foreach ($ArrayPartes as $Parte) {
                     $NomeClasse .= ucfirst($Parte);
                 }
             }
         }
         return $NomeClasse;
     }
}

?>
