<?
include_once('../../framework/config.conf.php'); ConfigSIS::Conf();
?>

<style type="text/css">
body {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	color: #000;
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}

#modalCompleto {
	background:#FFF;
	font-family:Arial, Helvetica, sans-serif;
	font-size:12px;
	padding:10px;
	text-align:right;
}

</style>

<script type="text/javascript" src="<?=$_SESSION['JSBase']?>js/jquery.js"></script>
<script>
	var GaleriaMidiaCod = $('#FormManu #GaleriaMidiaCod').val();
	var AutorCod 		= $('#FormManu #AutorCod').val();
	var AutorNome 		= $('#FormManu #AutorNome').val();
	var Legenda			= $('#FormManu #Legenda').val();
	var DataPublicacao  = $('#FormManu #DataPublicacao').val();
	
	
	var Parametros = "?GaleriaMidiaCod="+GaleriaMidiaCod+"&AutorCod="+AutorCod+"&AutorNome="+AutorNome+"&Legenda="+Legenda+"&DataPublicacao="+DataPublicacao+"";
	
	$("#modalIframe").html('<iframe id="iframeteste" name="iframeteste" frameborder="0" src="<?=$_SESSION['UrlBase']?>conteudo/upload/selectup.php'+Parametros+'" width="650" height="450"></iframe>');
</script>


<div id="modalCompleto">
	<div><a href="#" rel="modalclose"><img src="<?=$_SESSION['UrlBase']?>conteudo/upload/figuras/bt_fechar_div.gif" border="0" /></a></div>
    <div id="modalIframe">
        aguarde...
    </div>
</div>