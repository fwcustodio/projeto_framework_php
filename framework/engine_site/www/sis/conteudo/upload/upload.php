<?php
/*
This is an upload script for SWFUpload that attempts to properly handle uploaded files
in a secure way.

Notes:
	
	SWFUpload doesn't send a MIME-TYPE. In my opinion this is ok since MIME-TYPE is no better than
	 file extension and is probably worse because it can vary from OS to OS and browser to browser (for the same file).
	 The best thing to do is content sniff the file but this can be resource intensive, is difficult, and can still be fooled or inaccurate.
	 Accepting uploads can never be 100% secure.
	 
	You can't guarantee that SWFUpload is really the source of the upload.  A malicious user
	 will probably be uploading from a tool that sends invalid or false metadata about the file.
	 The script should properly handle this.
	 
	The script should not over-write existing files.
	
	The script should strip away invalid characters from the file name or reject the file.
	
	The script should not allow files to be saved that could then be executed on the webserver (such as .php files).
	 To keep things simple we will use an extension whitelist for allowed file extensions.  Which files should be allowed
	 depends on your server configuration. The extension white-list is _not_ tied your SWFUpload file_types setting
	
	For better security uploaded files should be stored outside the webserver's document root.  Downloaded files
	 should be accessed via a download script that proxies from the file system to the webserver.  This prevents
	 users from executing malicious uploaded files.  It also gives the developer control over the outgoing mime-type,
	 access restrictions, etc.  This, however, is outside the scope of this script.
	
	SWFUpload sends each file as a separate POST rather than several files in a single post. This is a better
	 method in my opinions since it better handles file size limits, e.g., if post_max_size is 100 MB and I post two 60 MB files then
	 the post would fail (2x60MB = 120MB). In SWFupload each 60 MB is posted as separate post and we stay within the limits. This
	 also simplifies the upload script since we only have to handle a single file.
	
	The script should properly handle situations where the post was too large or the posted file is larger than
	 our defined max.  These values are not tied to your SWFUpload file_size_limit setting.
	
*/

	session_start();

	//Definindo Módulo/Pacote
	define("MODULO","arquivos");
	define("PACOTE","conteudo");

	include_once('../../framework/config.conf.php'); 					ConfigSIS::Conf();
	include_once($_SESSION['FMBase'].'acesso.class.php'); 	      		 	$Ac = new Acesso();
	include_once($_SESSION['FMBase'].'conexao.class.php');					$Con = Conexao::conectar();
	include_once($_SESSION['FMBase'].'funcoes_php.class.php');				$FPHP    = new FuncoesPHP();
	include_once($_SESSION['DirBase'].'cadastros/autor/autor.class.php');	$Autor = new Autor();
	include_once($_SESSION['FMBase'].'arquivos.class.php');					$Arq = new Arquivos();
	


	// Code for Session Cookie workaround
	if (isset($_POST["PHPSESSID"])) {
		session_id($_POST["PHPSESSID"]);
	} else if (isset($_GET["PHPSESSID"])) {
		session_id($_GET["PHPSESSID"]);
	}

// Check post_max_size (http://us3.php.net/manual/en/features.file-upload.php#73762)
	$POST_MAX_SIZE = ini_get('post_max_size');
	$unit = strtoupper(substr($POST_MAX_SIZE, -1));
	$multiplier = ($unit == 'M' ? 1048576 : ($unit == 'K' ? 1024 : ($unit == 'G' ? 1073741824 : 1)));

	if ((int)$_SERVER['CONTENT_LENGTH'] > $multiplier*(int)$POST_MAX_SIZE && $POST_MAX_SIZE) {
		header("HTTP/1.1 500 Internal Server Error"); // This will trigger an uploadError event in SWFUpload
		echo "POST exceeded maximum allowed size.";
		exit(0);
	}


// Settings
	$upload_name = "Filedata";
	$max_file_size_in_bytes = 2147483647;				// 2GB in bytes
	$extension_whitelist = array("jpg", "jpeg", "gif", "png", "wmv", "avi", "mp3", "wma");	// Allowed file extensions
	$valid_chars_regex = '.A-Z0-9_ !@#$%^&()+={}\[\]\',~`-';				// Characters allowed in the file name (in a Regular Expression format)

// Other variables	
	$MAX_FILENAME_LENGTH = 260;
	$file_name = "";
	$file_extension = "";
	$uploadErrors = array(
        0=>"There is no error, the file uploaded with success",
        1=>"The uploaded file exceeds the upload_max_filesize directive in php.ini",
        2=>"The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form",
        3=>"The uploaded file was only partially uploaded",
        4=>"No file was uploaded",
        6=>"Missing a temporary folder"
	);

// Variaveis por POST
	$GaleriaMidiaCod = $_REQUEST['GaleriaMidiaCod'];
	
	$AutorCod 		 = $_REQUEST['AutorCod'];
	$AutorNome 		 = $_REQUEST['AutorNome'];
	$AutorCod 		 = $Autor->novoAutorMultiplo(utf8_decode($AutorNome), $AutorCod);
	$AutorCod		 = (empty($AutorCod)) ? 'NULL' : $AutorCod;
	$Legenda 		 = $_REQUEST['Legenda'];
	$DataPublicacao	 = $FPHP->convertData($_REQUEST['DataPublicacao']);


// ########################### SCRIPT DE INTERAÇÃO COM SISTEMA BY ROGER
	$Extensao = strtolower($Arq->extenssaoArquivo($_FILES[$upload_name]["name"]));

	if($Extensao == "jpg" or $Extensao == "jpeg" or $Extensao == 'gif' or $Extensao == 'png') {
		$TipoArquivo  = "F";
		$save_path    = $_SESSION['DirBaseSite'].'arquivos/multimidia/'.$GaleriaMidiaCod.'/fotos/';
		$save_path_tb = $_SESSION['DirBaseSite'].'arquivos/multimidia/'.$GaleriaMidiaCod.'/fotos/tb/';
	} elseif ($Extensao == "wmv" or $Extensao == "avi") {
		$TipoArquivo = "V";
		$save_path   = $_SESSION['DirBaseSite'].'arquivos/multimidia/'.$GaleriaMidiaCod.'/videos/';
	} else if($Extensao == "mp3" or $Extensao == "wma") {
		$TipoArquivo = "A";
		$save_path   = $_SESSION['DirBaseSite'].'arquivos/multimidia/'.$GaleriaMidiaCod.'/audios/';
	} else {
		HandleError("File size outside allowed lower bound");
		exit(0);
	}	

	$Con->executar("INSERT INTO galeria_arquivo 
				   				(GaleriaMidiaCod, AutorCod, Legenda, DataPublicacao, TipoArquivo, Extensao) 
						 VALUES (".$GaleriaMidiaCod.", ".$AutorCod.", '".utf8_decode($Legenda)."', '".$DataPublicacao."', '".$TipoArquivo."', '".$Extensao."')");
	
	$GaleriaArquivoCod = $Con->ultimoId("galeria_arquivo","GaleriaArquivoCod");
	
	$file_name = $GaleriaArquivoCod.".".$Extensao;
	
// ########################### SCRIPT DE INTERAÇÃO COM SISTEMA BY ROGER


// Validate the upload
	if (!isset($_FILES[$upload_name])) {
		HandleError("No upload found in \$_FILES for " . $upload_name);
		exit(0);
	} else if (isset($_FILES[$upload_name]["error"]) && $_FILES[$upload_name]["error"] != 0) {
		HandleError($uploadErrors[$_FILES[$upload_name]["error"]]);
		exit(0);
	} else if (!isset($_FILES[$upload_name]["tmp_name"]) || !@is_uploaded_file($_FILES[$upload_name]["tmp_name"])) {
		HandleError("Upload failed is_uploaded_file test.");
		exit(0);
	} else if (!isset($_FILES[$upload_name]['name'])) {
		HandleError("File has no name.");
		exit(0);
	}
	
// Validate the file size (Warning: the largest files supported by this code is 2GB)
	$file_size = @filesize($_FILES[$upload_name]["tmp_name"]);
	if (!$file_size || $file_size > $max_file_size_in_bytes) {
		HandleError("File exceeds the maximum allowed size");
		exit(0);
	}
	
	if ($file_size <= 0) {
		HandleError("File size outside allowed lower bound");
		exit(0);
	}


// Validate file name (for our purposes we'll just remove invalid characters)
/*	if (strlen($file_name) == 0 || strlen($file_name) > $MAX_FILENAME_LENGTH) {
		HandleError("Invalid file name");
		exit(0);
	}
*/

// Validate that we won't over-write an existing file
	if (file_exists($save_path . $file_name)) {
		HandleError("File with this name already exists");
		exit(0);
	}

// Validate file extension
	$path_info = pathinfo($_FILES[$upload_name]['name']);
	$file_extension = $path_info["extension"];
	$is_valid_extension = false;
	foreach ($extension_whitelist as $extension) {
		if (strcasecmp($file_extension, $extension) == 0) {
			$is_valid_extension = true;
			break;
		}
	}
	if (!$is_valid_extension) {
		HandleError("Invalid file extension");
		exit(0);
	}





// Validate file contents (extension and mime-type can't be trusted)
	/*
		Validating the file contents is OS and web server configuration dependant.  Also, it may not be reliable.
		See the comments on this page: http://us2.php.net/fileinfo
		
		Also see http://72.14.253.104/search?q=cache:3YGZfcnKDrYJ:www.scanit.be/uploads/php-file-upload.pdf+php+file+command&hl=en&ct=clnk&cd=8&gl=us&client=firefox-a
		 which describes how a PHP script can be embedded within a GIF image file.
		
		Therefore, no sample code will be provided here.  Research the issue, decide how much security is
		 needed, and implement a solution that meets the needs.
	*/


// Process the file
	/*
		At this point we are ready to process the valid file. This sample code shows how to save the file. Other tasks
		 could be done such as creating an entry in a database or generating a thumbnail.
		 
		Depending on your server OS and needs you may need to set the Security Permissions on the file after it has
		been saved.
	*/
	
	if(($TipoArquivo == "F") && ($Extensao == "jpg" or $Extensao == "jpeg")) 
	{
		$imagem_nome = $file_name;
		$caminho = $pasta.$imagem_nome;
		
		function reduz_imagem($img, $max_x, $max_y, $nome_foto) {
			list($width, $height) = getimagesize($img);
			$original_x = $width;
			$original_y = $height;
			// se a largura for maior que altura
			if($original_x >= $original_y) {
				$porcentagem = (100 * $max_x) / $original_x;
			}
			// se a altura for maior que a largura
			else {
				$porcentagem = (100 * $max_y) / $original_y;
			}
			$tamanho_x = $original_x * ($porcentagem / 100);
			$tamanho_y = $original_y * ($porcentagem / 100);
			$image_p = imagecreatetruecolor($tamanho_x, $tamanho_y);
			$image = imagecreatefromjpeg($img);
			imagecopyresampled($image_p, $image, 0, 0, 0, 0, $tamanho_x, $tamanho_y, $width, $height);
			return imagejpeg($image_p, $nome_foto, 100);
		}
		
		reduz_imagem($_FILES[$upload_name]["tmp_name"], 650, 650, $save_path.$imagem_nome);
		
		function createthumb($name,$filename,$new_w,$new_h)
		{
			$src_img = imagecreatefromjpeg($name);
			
			$old_x = imagesx($src_img);
			$old_y = imagesy($src_img);
			if ($old_x > $old_y) {
			$thumb_w = $new_w;
			$thumb_h = $old_y*($new_h/$old_x);
			}
			if ($old_x < $old_y) {
			$thumb_w = $old_x*($new_w/$old_y);
			$thumb_h = $new_h;
			}
			if ($old_x == $old_y) {
			$thumb_w = $new_w;
			$thumb_h = $new_h;
			}
			
			$dst_img = imagecreatetruecolor($thumb_w,$thumb_h);
		
			imagecopyresampled($dst_img,$src_img,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y);
			
		
			imagejpeg($dst_img,$filename);
		
			imagedestroy($dst_img);
			imagedestroy($src_img);
		}	
		
		createthumb($save_path.$file_name, $save_path_tb.$file_name,138,138);
		
		exit(0);

	} 
	elseif(($TipoArquivo == "F") && ($Extensao == "gif" or $Extensao == "png"))
	{
		copy($_SESSION['DirBase']."conteudo/upload/figuras/padrao.gif",$save_path_tb.$file_name);

		if(!move_uploaded_file($_FILES[$upload_name]["tmp_name"], $save_path.$file_name)) {
			HandleError("Invalid file extension");
		}

	} else {
		if(!move_uploaded_file($_FILES[$upload_name]["tmp_name"], $save_path.$file_name)) {
			HandleError("Invalid file extension");
			exit(0);
		}
	}


/* Handles the error output. This error message will be sent to the uploadSuccess event handler.  The event handler
will have to check for any error messages and react as needed. */
function HandleError($message) {
	echo $message;
}
?>