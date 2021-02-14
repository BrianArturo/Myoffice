<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
include("inc/config.php");
session_start();
if (!isset($_SESSION["auth_id"]) ){ header ("Location:index.php"); }
if($_GET['logout']=="logout"){ unset($_SESSION); }
$caso_id = auyama_decrypt(base64_decode(rawurldecode ($_GET["id"])));

if(isset($_POST["asign"])){

	$user_id = auyama_decrypt(base64_decode(rawurldecode ($_POST["user"])));
	$query = "INSERT into user_casos (user_id, caso_id) VALUES(".$mysqli->real_escape_string($user_id).",".$mysqli->real_escape_string($caso_id).")";
	$mysqli->real_query($query);
	 
}
if(isset($_GET["id"]) && isset($_GET["user_id"]) ){
	/*echo "Caso: ".auyama_decrypt(base64_decode(rawurldecode ($_GET["id"])));
	echo "<br>";
	echo "User: ".auyama_decrypt(base64_decode(rawurldecode ($_GET["user_id"])));*/

	$caso_id = $mysqli->real_escape_string(auyama_decrypt(base64_decode(rawurldecode ($_GET["id"]))));
	$user_id = $mysqli->real_escape_string(auyama_decrypt(base64_decode(rawurldecode ($_GET["user_id"]))));
	$DELETE_ASSIGN = "DELETE from  user_casos where user_id=".$user_id ." && caso_id=".$caso_id." ";
	$mysqli->real_query($DELETE_ASSIGN);
	  
}

?>
<!DOCTYPE HTML>
    <head>
        <title><?php echo $TITULO ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">


		<?php include("inc/header.php"); ?>


    </head>
    <body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

 

        <?php include("inc/menu.php"); ?>
		<div class="container-fluid" style="max-width:1450px;margin-top:20px;">
    		<div id="content" class="wrapper">
				<div class="row">
					<div class="col-md-6"><h3>Usuario Vs Casos</h3></div>
					<div class="col-md-6 text-right">
						 
					</div>
				</div>
			</div>
		</div>

            <div class="container-fluid mb-5" style="max-width:1450px; ">
    			<div id="content" class="wrapper">
				
				<div class="row mt-3 mb-3">
						<div class="col-md-4 m-auto  ">
						<p>Asigna el control de este caso a un usuario:</p>
						<form id="form1" name="form1" method="post" action="">
							<select class="form-control mb-3" name="user" required>
								<option value="" disabled selected>Selecciona un usuario</option>
								<?php
								$query = "SELECT * from users where created_by='".$_SESSION["auth_id"]."' order by id desc ";

								$mysqli->real_query($query);
								$res = $mysqli->use_result();

								while ($linea = $res->fetch_assoc()) {
										echo '<option value="'.rawurlencode(base64_encode(auyama_encrypt($linea["id"]))).'">'.$linea["name"].'</option>';
								}
								?>
							</select>
							<button type="submit" name="asign" class="btn btn-success btn-lg mt-3 btn-block">Asigna caso</button>
						</form>
						</div>
				</div>		 
						
				<div class="row mt-3 mt-3">
						<div class="col-md-12">
							<hr />
						</div>
				</div>
				<div class="row mt-3 mt-3">
						<div class="col-md-12 ">
						<table id="contatti" class="table table-striped table-bordered display" <?php echo $usersettings; ?> style="width:100%">
									<thead>
										<tr>
											<th>Id</th>
											<th>Nombre</th>
											<th>Empresa</th>
											<th>Estado</th>
											<th>Email</th>
										</tr>
									</thead>
								<tbody>
								<?php
								#var_dump($_SESSION);
								$SQL_USERS="SELECT user_casos.caso_id, users.* from users left  join user_casos  on  ( users.id=user_casos.user_id ) where user_casos.caso_id =".$caso_id." order by  users.id desc";
								 
						
								$mysqli2->real_query($SQL_USERS);
								$res = $mysqli2->use_result();
						
								while ($linea = $res->fetch_assoc()) {
									echo '<tr>';
										echo '<td  rowspan="2">';
										echo '<a  class="btn btn-link" href="view_user.php?id='.rawurlencode(base64_encode(auyama_encrypt($linea["id"]))).'">	EDITAR</a>';
									echo '<br>';
										echo '<a class="btn btn-link" href="assign.php?user_id='.rawurlencode(base64_encode(auyama_encrypt($linea["id"]))).
											  '&amp;id='.rawurlencode(base64_encode(auyama_encrypt($linea["caso_id"]))).'"> QUITAR ACCESO</a> '.$linea["id"].'';
										echo '</td>';
										echo '<td>'.$linea["name"].'</td>';
										echo '<td>'. $linea["company"].'</td>';
										echo '<td class="bg-'.$linea["status"].'">'. $linea["status"].'</td>';
										echo '<td><a href="mailto:'. $linea["email"].'" ><i class="fa fa-envelope" aria-hidden="true"></i> '.
											  $linea["email"].'</a></td>';
								echo "</tr>";
						
						
								}
								?></tbody>
								<tfoot>
									<tr>
										<th>Id</th>
										<th>Nombre</th>
										<th>Empresa</th>
										<th>Estado</th>
										<th>Email</th>
									</tr>
								</tfoot>
							</table>
							</div>
							</div>
 
				</div>
    		</div>

        <?php include("inc/footer.php"); ?>
		<script type="text/javascript" src="js/script.js"></script>
    </body>
</html>
