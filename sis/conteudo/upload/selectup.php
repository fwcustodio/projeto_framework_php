<?
include_once('../../framework/config.conf.php'); ConfigSIS::Conf();
?>

<html>
<head>

<link href="<?=$_SESSION['UrlBase']?>conteudo/upload/css/default.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="<?=$_SESSION['UrlBase']?>conteudo/upload/swfupload/swfupload.js"></script>
<script type="text/javascript" src="<?=$_SESSION['UrlBase']?>conteudo/upload/js/swfupload.queue.js"></script>
<script type="text/javascript" src="<?=$_SESSION['UrlBase']?>conteudo/upload/js/fileprogress.js"></script>
<script type="text/javascript" src="<?=$_SESSION['UrlBase']?>conteudo/upload/js/handlers.js"></script>

<script type="text/javascript">
	var swfu;

	window.onload = function() {
		var settings = {
			flash_url : "<?=$_SESSION['UrlBase']?>conteudo/upload/swfupload/swfupload.swf",
			upload_url: "<?=$_SESSION['UrlBase']?>conteudo/upload/upload.php",
			post_params: { "PHPSESSID" : "<?php echo session_id(); ?>",
						   "GaleriaMidiaCod" : "<?=$_GET['GaleriaMidiaCod']?>",
						   "AutorCod" : "<?=$_GET['AutorCod']?>",
						   "AutorNome" : "<?=$_GET['AutorNome']?>",
						   "Legenda" : "<?=$_GET['Legenda']?>",
						   "DataPublicacao" : "<?=$_GET['DataPublicacao']?>"
			},
			file_size_limit : "8 MB",
			file_types : "*.jpg;*.jpeg;*.gif;*.png;*.wmv;*.avi;*.mp3;*.wma",
			file_types_description : "Arquivos de Mídia",
			file_upload_limit : 100,
			file_queue_limit : 0,
			custom_settings : {
				progressTarget : "fsUploadProgress",
				cancelButtonId : "btnCancel"
			},
			debug: false,

			// Button settings
			button_image_url: "<?=$_SESSION['UrlBase']?>conteudo/upload/figuras/TestImageNoText_65x29.png",
			button_width: "76",
			button_height: "29",
			button_placeholder_id: "spanButtonPlaceHolder",
			button_text: '&nbsp;',
			button_text_style: ".theFont { font-size: 16; }",
			button_text_left_padding: 12,
			button_text_top_padding: 3,
			
			// The event handler functions are defined in handlers.js
			file_queued_handler : fileQueued,
			file_queue_error_handler : fileQueueError,
			file_dialog_complete_handler : fileDialogComplete,
			upload_start_handler : uploadStart,
			upload_progress_handler : uploadProgress,
			upload_error_handler : uploadError,
			upload_success_handler : uploadSuccess,
			upload_complete_handler : uploadComplete,
			queue_complete_handler : queueComplete	// Queue plugin event
		};

		swfu = new SWFUpload(settings);
	 };
</script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
</head>
<body>
<div id="content" align="center" style="padding-top:20px; padding-bottom:20px; margin-left:30px; background:#FFF;">


	<form id="form1" action="index.php" method="post" enctype="multipart/form-data">
        <div class="fieldset flash" id="fsUploadProgress" style=" background:#FFF;">
        <span class="legend">Lista de Transferencia</span>
        </div>
        <div  style=" background:#FFF;" id="divStatus"> </div>
        <div>
            <span id="spanButtonPlaceHolder"  style=" background:#FFF;"></span>
            <input id="btnCancel" type="button" value="Cancelar todos os envios" onClick="swfu.cancelQueue();" disabled="disabled" style="margin-left: 2px; font-size: 8pt; height: 29px;" />
        </div>
	</form>
</div>
</body>
</html>
