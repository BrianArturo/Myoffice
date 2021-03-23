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

?>
<!DOCTYPE HTML>

<head>
	<title><?php echo $TITULO ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">


	<?php include("inc/header.php"); ?>
	<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.min.js" integrity="sha256-eGE6blurk5sHj+rmkfsGYeKyZx3M4bG+ZlFyA7Kns7E=" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

</head>

<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">



	<?php include("inc/menu.php"); ?>

	<?php
	if ($_SESSION["type"] != "guest") {?>
	
	<div class="container-fluid maxwidth">
		<div id="content" class="wrapper">
			<br>
			<div class="row">
				<div class="col-md-6">
					<h3>Contacto</h3>
				</div>
				<div class="col-md-6 text-right">
					<h3><a href="view_contact.php" class="btn btn-secondary" style="background-color: #CB6CE6;">CREAR CONTACTO <i class="fa fa-plus-circle" aria-hidden="true"></i></a> </h3>
				</div>
			</div>



		</div>
	</div>

	<div class="container-fluid mb-5 maxwidth">
		<div id="content" class="wrapper">

			<?php

			$SQL_chk    = "select * from user_setting where id='" . $_SESSION["auth_id"] . "' ;";
			$mysqli->real_query($SQL_chk);
			$res2 = $mysqli->use_result();

			while ($setting = $res2->fetch_assoc()) {
				if ($setting["var"] == "len") {
					$usersettings .= " data-page-length=\"" . $setting["val"] . "\" ";
				}
				if ($setting["var"] == "order") {
					$x = str_split(',', $setting["val"]);
					$usersettings .= "     data-order='[[" . $x[0] . " ,\"" . trim($x[1]) . "\" ]]'     ";
				}
			}


			#echo $usersettings;
			?><br>
			<table id="contatti" class="table table-striped table-bordered display" <?php //echo $usersettings; 
																					?> style="width:100%">
				<thead>
					<tr>
						<th style="width: 100px;">Opciones</th>
						<th>Nombre</th>
						<th>Telefono</th>
						<th>Nit</th>
						<th>Ciudad</th>
						<th>Fecha</th>
						<th>Email</th>
					</tr>
				</thead>
				<tbody>
					<?php
					#var_dump($_SESSION);
					if ($_SESSION["created_by"] == NULL) {
						$query = "SELECT * from contacts where status>0 order by contact_id desc ";
					} else {
						//$query = "SELECT * from users where created_by='".$_SESSION["auth_id"]."' order by id desc ";
						if ($_SESSION["type"] == "admin") {
							$query = "SELECT * from contacts where status>0 and created_by='" . $_SESSION["auth_id"] . "' or created_by='" . $_SESSION["created_by"] . "' or created_by IN (SELECT id from users where created_by= '" . $_SESSION["auth_id"] . "') order by contact_id desc ";
						} else {

							$query = "SELECT * from contacts where status>0 and created_by='" . $_SESSION["auth_id"] . "' or created_by='" . $_SESSION["created_by"] . "' order by contact_id desc ";
						}
					}
					//$query = "SELECT * from contacts where status>0 order by contact_id desc ";


					$mysqli->real_query($query);
					$res = $mysqli->use_result();

					while ($linea = $res->fetch_assoc()) {
						echo '<tr>';
						echo '<td>' .
							'<i class="fa fa-trash fa-2x delete_contact" aria-hidden="true " object="delete_contact" item="' . rawurlencode(base64_encode(auyama_encrypt($linea["contact_id"]))) . '"></i> ' .
							'<a  href="view_contact.php?id=' . rawurlencode(base64_encode(auyama_encrypt($linea["contact_id"]))) . '">' .
							'<button class="btn btn-secondary" style="background-color: #CB6CE6;" > EDITAR </button></a>' .
							'</td>';
						echo '<td>' . $linea["name"] . '</td>';
						echo '<td>' . $linea["phone"] . '</td>';
						echo '<td>' . $linea["nit"] . '</td>';
						echo '<td>' . $linea["citty"] . '</td>';
						echo '<td>' . date(" H:i d/m/Y", strtotime($linea["datalog"])) . '</td>';
						echo '<td><a href="mailto:' . $linea["email"] . '" ><i class="fa fa-envelope" aria-hidden="true"></i> ' .
							$linea["email"] . '</a></td>';
						echo "</tr>";
					}
					?></tbody>
				<tfoot>
					<tr>
						<th style="width: 100px;">Opciones</th>
						<th>Nombre</th>
						<th>Telefono</th>
						<th>nit</th>
						<th>Ciudad</th>
						<th>Fecha</th>
						<th>Email</th>
					</tr>
				</tfoot>

			</table>


		</div>
	</div>
	<?php }else{ 
		 ydhatta();
		}
	 ?>

	<?php
	if ($_SESSION["type"] != "guest") {
		include("inc/footer.php");
	} ?>
	<script type="text/javascript" src="js/script.js"></script>
</body>

</html>