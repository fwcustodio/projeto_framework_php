<?
class PermissoesCSS {

    public function iniciaGrupo($GrupoCod, $Descricao, $IdForm) {
        return "<div id=\"grupo_$GrupoCod\" class=\"pgrupo\"><a class=\"pgrupo_titulo pgrupo_off\" href=\"javascript:cboxes('grupo_$GrupoCod',null,'$IdForm')\">$Descricao</a>";
    }

    public function finalizaGrupo() {
        return '<div class="clear_div">&nbsp;</div></div>';
    }

    public function iniciaModulo($ModCod, $ModNome, $IdForm, $isSub = false) {
        $subhtml = ($isSub ? ' psub' : '');
        return "<div id=\"mod_$ModCod\" class=\"pmodulo$subhtml\"><a class=\"pgrupo_titulo pgrupo_off\" href=\"javascript:cboxes('mod_$ModCod',null,'$IdForm')\">$ModNome</a>";
    }

    public function finalizaModulo() {
        return '</div>';
    }
}