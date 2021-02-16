<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
include("inc/config.php");
session_start();
if (!isset($_SESSION["auth_id"])) {
	header("Location:index.php");
}
if ($_GET['logout'] == "logout") {
	unset($_SESSION);
}
$_ID = auyama_decrypt(base64_decode(rawurldecode($_GET["id"])));
$count = auyama_decrypt(base64_decode(rawurldecode($_GET["count"])));
//$documentcount = auyama_decrypt(base64_decode(rawurldecode ($_GET["documentcount"])));
$document_count_sql = "SELECT COUNT(*) as total FROM documents WHERE caso_id='" . $_ID . "'";
$mysqli3 = new mysqli($host, $user, $pass, $name);
$mysqli3->real_query($document_count_sql);
$documentcount = $mysqli3->use_result();
$documentcount = $documentcount->fetch_assoc();
//var_dump($document_count["total"]);exit();
$mysqli3->close();
?>
<!DOCTYPE HTML>

<head>
	<title><?php echo $TITULO ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="favicon.ico" type="image/x-icon">
	<?php include("inc/header.php"); ?>
	<link rel="stylesheet" href="js/fancybox/jquery.fancybox.min.css" />
	<link href="https://unpkg.com/nanogallery2@2.4.2/dist/css/nanogallery2.min.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="https://unpkg.com/nanogallery2@2.4.2/dist/jquery.nanogallery2.min.js"></script>
	<script src="js/fancybox/jquery.fancybox.min.js"></script>
</head>

<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">



	<?php include("inc/menu.php"); ?>
	<div class="container-fluid maxwidth">
		<div id="content" class="wrapper">
			<div class="row">
				<div class="col-md-8 m-auto text-left">
					<h3>Documentos-Cantidad: <?php echo $documentcount["total"] ?> </h3>
					<a href="view_caso.php?id=<?php echo rawurlencode(base64_encode(auyama_encrypt($_ID))); ?>&count=<?php echo rawurlencode(base64_encode(auyama_encrypt($count))); ?>">
						<button type="button" class="btn btn-primary mt-2">REGRESAR AL CASO</button>
					</a>
					<input type="hidden" class="form-control" id="caso_id" name="caso_id" value="<?php echo rawurlencode(base64_encode(auyama_encrypt($_ID))); ?>">
				</div>
			</div>
		</div>
	</div>

	<div class="container-fluid mb-5" style="max-width:1450px; ">
		<div id="content" class="wrapper">
			<div class="col-md-8 mt-3 ml-auto mr-auto">
				<?php
				$QUERY_SETTINGS = "SELECT * FROM settings";
				$mysqli->real_query($QUERY_SETTINGS);
				$result = $mysqli->use_result();

				while ($row = $result->fetch_assoc()) {
					$s[$row["name"]] = $row["value"];
					//echo $row["name"]." - > ".$row["value"]."<br>";
				}

				echo "Dimensiòn maxima de cargamento: " . (int)(ini_get('upload_max_filesize')) . " Mb <br>";
				echo "Archivos permitidos para el cargamento: " . $s["attachment_allow"] . "<br>";
				?>

				<input id="fileupload" type="file" name="files[]" data-url="ajax_document.php" class="mt-3 mb-3" accept="<?php echo $s["attachment_allow"]; ?>" multiple>
				<hr />
				<div id="progress">

					<div class="progress-bar-striped progress-bar-animated bg-success" id="speedbar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"> </div>
				</div>
			</div>
			<style>
				#fileupload {
					padding: 5px;
				}

				.bar {
					height: 18px;
					background: green;
					border-radius: 5px;
				}
			</style>
			<script type="text/javascript">
				$(function() {
					var caso_id_val = '<?php echo $_GET["id"]; ?>';
					$.ajax({
						type: 'POST',
						url: 'ajax_document_list.php',
						data: {
							'function': 'list_document',
							'caso_id': caso_id_val
						},
						success: function(response) {
							$("#filespool").html(response);
							update_icons();

						}
					});
					$("#fileupload").fileupload({
						dataType: "json",
						maxFileSize: 16000000,
						formData: {
							caso_id: caso_id_val
						},
						sequentialUploads: true,
						url: 'ajax_document.php',
						done: function(e, data) {
							//console.log(data.result)
							//console.log(data.result.files)
							var filename = data.result.files;
							if (filename.length > 0) {
								console.log("File caricato...");
								var mypublicfilename;
								var myrealfilename;
								$.each(data.result.files, function(index, file) {
									//$('<p/>').text(file).appendTo(document.body);
									mypublicfilename = file.name;
									myrealfilename = file.deleteType[0];
								});
								$.ajax({
									type: 'POST',
									url: 'ajax_document_list.php',
									data: {
										'function': 'add_document',
										'caso_id': caso_id_val,
										'name': mypublicfilename,
										'realname': myrealfilename
									},

									success: function(response) {
										//console.log("Ajax Sended");
										//console.log("Database aggiornato...");
										console.log("File Saved");
										console.log("Respuesta" + response);
										$("h3").text("Documentos-Cantidad: " + response);
										//window.location.reload();

									}
								});


							} else {
								console.log("Error...");
								$('<p/>').text("error cargando el archivo").appendTo(document.body);
							}
						},
						stop: function(e, data) {

							console.log('Uploads finished');
							console.log(data);

							$.ajax({
								type: 'POST',
								url: 'ajax_document_list.php',
								data: {
									'function': 'list_document',
									'caso_id': caso_id_val
								},
								success: function(response) {
									$("#filespool").html(response);
									update_icons();

									console.log(response);
								},
							});


						},
						error: function(e, data, error) {
							console.log('error: ' + error);
							console.log('Data: ' + data.result);
							console.log('e: ' + e.Type);
						},
						progress: function(e, data) {

						},
						progressall: function(e, data) {
							var progress = parseInt(data.loaded / data.total * 100, 10);
							console.log(data.bitrate);

							$("#speedbar").html((data.bitrate / 1000000).toFixed(2) + " mb/s");
							$('#progress #speedbar').css(
								'width',
								progress + '%'
							);
							if (progress == 100) {
								$("#speedbar").toggle();
								console.info("All uploads done");

							}
						}
					})




				});
			</script>
			<?php

			#https://github.com/blueimp/jQuery-File-Upload/wiki/PHP-user-directories
			?>
			<div class="col-md-12   ">
				<div class="row" id="filespool">
				</div>
			</div>

			<!--
<div data-nanogallery2='{
    "thumbnailHeight":  200,
    "thumbnailWidth":   200,
  }'>
  <?php
	$SQL_DOCUMENTS = "SELECT * FROM  documents   where documents.caso_id=" . $_ID . " order by extention";
	$mysqli4 = new mysqli($host, $user, $pass, $name);
	$mysqli4->real_query($SQL_DOCUMENTS);
	$contacts = $mysqli4->use_result();
	while ($u = $contacts->fetch_assoc()) {
		if (strtolower($u["extention"]) == "jpg" or $u["extention"] == "png" or $u["extention"] == "jpeg") {
			echo '<a href="../download/' . $u["caso_id"] . '/' . $u["name"] . '">' . $u["realname"] . '</a>';
		}
	}
	?>
			</div>-->
		</div>
	</div>
	</div>

	<?php include("inc/footer.php"); ?>
	<script src="js/jQuery-File-Upload-master/js/vendor/jquery.ui.widget.js"></script>
	<script src="js/jQuery-File-Upload-master/js/jquery.iframe-transport.js"></script>
	<script src="js/jQuery-File-Upload-master/js/jquery.fileupload.js"></script>
	<script type="text/javascript" src="js/script.js"></script>
</body>

</html>