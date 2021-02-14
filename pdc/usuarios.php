<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
include("inc/config.php");
session_start();
if (!isset($_SESSION["auth_id"]) ){ header ("Location:index.php"); }
if($_GET['logout']=="logout"){ unset($_SESSION); }

?>
<!DOCTYPE HTML>
    <head>
        <title><?php echo $TITULO ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="icon" href="favicon.ico" type="image/x-icon">

		<?php include("inc/header.php"); ?>


    </head>
    <body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

 

        <?php include("inc/menu.php"); ?>
		<div class="container-fluid" style="max-width:1450px;margin-top:20px;">
    		<div id="content" class="wrapper">
				<div class="row">
					<div class="col-md-6"><h3>Usuarios</h3></div>
					
					<?php 
						if (!isset($_GET['f'])){ ?>
					<div class="col-md-6 text-right" id="add" style="display: block;">
						<h3 ><a href="usuarios.php?f=add" class="btn btn-primary" onclick="add()">
							CREAR USUARIO <i class="fa fa-plus-circle" aria-hidden="true"></i></a> </h3>
					</div><?php } ?>
				</div>
			</div>
		</div>

            <div class="container-fluid mb-5" style="max-width:1450px; ">
    			<div id="content" class="wrapper">

<?php
if(  $_SESSION["type"] !="admin" and $_SESSION["type"] !="superadmin"  ){
	ydhatta();
}else{
			$SQL_chk    = "select * from user_setting where id='".$_SESSION["auth_id"]."' ;";
			$mysqli->real_query($SQL_chk);
			$res2 = $mysqli->use_result();

			while ($setting = $res2->fetch_assoc()) {
				if($setting["var"]=="len"){
					$usersettings.=" data-page-length=\"".$setting["val"]."\" ";
				}
				if($setting["var"]=="order"){
					$x=str_split(',',$setting["val"]);
				 	$usersettings.="     data-order='[[".$x[0]." ,\"".trim($x[1])."\" ]]'     ";
				}

			}


if($_GET["f"]=="add"){

	if(isset($_POST["adduser"])){
		
		$myuser = new mysqli($host, $user, $pass, $name);
		if($_SESSION["created_by"]==NULL){
			$SQL_ADD_USER = "INSERT users (id,name,company,email,status,type,password) ".
						" VALUES (NULL, '".$_POST["name"]."','".$_POST["company"]."','".trim($_POST["email"])."','enable','".$_POST["accounttype"]."','".md5(trim($_POST["password"]))."' )";
		}
		else{
			$SQL_ADD_USER = "INSERT users (id,name,company,email,status,type,password,created_by)".
						" VALUES (NULL, '".$_POST["name"]."','".$_POST["company"]."','".trim($_POST["email"])."','enable','".$_POST["accounttype"]."','".md5(trim($_POST["password"]))."','".$_SESSION["auth_id"]."' )";
		}
		$myuser->real_query($SQL_ADD_USER);
		$myuser->close();
		login("users" ,$MSG[3]);
		echo    '<div class="row mb-2  rounded"><div class="col-md-12 m-3 p-3">'.
		'<div class="alert alert-success text-center" role="alert">'.
		'<i class="fa fa-exclamation-triangle fa-2x" aria-hidden="true"></i> '.
		$MSG[3].
		'</div>'.
		'</div></div>';

	}else{
	?>
	<form id="form1" name="form1" method="post" action="">
		<div class="form-group row m-auto " style="max-width:900px;" >
			<div class="col-md-12 text-center">
				<h3>Crea usuario</h3>
			</div>
			<div class="col-md-6">
				<label for="name" class="col-form-label">Nombre*</label>
				<input class="form-control" type="text" name="name" placeholder="Nombre y Apellido" required>
			</div>
			<div class="col-md-6">
				<label for="email" class="col-form-label">email*</label>
				<input class="form-control" type="text" name="email" placeholder="Direccion de correo" required>
			</div>
			
			<div class="col-md-6">
				<label for="accounttype" class="col-form-label">Tipo*</label>
				<select class="form-control" name="accounttype" required>
					<option value="user" disabled  selected> Selecciona el tipo de usuario</option>
					<option value="user">Empleado</option>
				    <option value="admin"> Administrador</option>
					<option value="guest">Visitante</option>
				</select>
			</div>
			<div class="col-md-6">
				<label for="company" class="col-form-label">Empresa</label>
				<?php
			if($_SESSION["created_by"]==NULL){
				?>
			<input class="form-control" type="text" name="company" placeholder="Nombre Empresa" >
			<?php
			}else{
			?>
			<input readonly class="form-control" type="text" name="company" value="<?php echo $_SESSION["sezione"]?>" >
<?php } ?>	
			</div>
			<div class="col-md-6">
				<label for="company" class="col-form-label">Password*</label>
				<input class="form-control" type="text" name="password" placeholder="Maximo/min 12/8 maiuscula/minuscula y !@#$%^&*_=+-" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*_=+-]).{8,12}$" required>
			</div>
			<div class="col-md-12 text-center">
				<button type="submit" name="adduser" class="btn btn-success m-3">Crea Usuario</button>
			</div>
			<div class="col-md-12">
				 <hr />
			</div>
		</div>
	</form>
	<?php
	}
	}

?>
<div class="row mt-3 mt-3">
<div class="col-md-12">
<table id="contatti" class="table table-striped table-bordered display" <?php //echo $usersettings; ?> style="width:100%">
			<thead>
				<tr>
					<th>Id</th>
					<th>Nombre</th>
					<th>Empresa</th>
					<th>Estado</th>
					<th>Tipo</th>
					<th>Email</th>
				</tr>
	        </thead>
        <tbody>
		<?php
		//var_dump($_SESSION["created_by"]);
		if($_SESSION["created_by"]==NULL){
			$query = "SELECT * from users where created_by<>'".$_SESSION["auth_id"]."' order by id desc ";
		}
		else{
			$query = "SELECT * from users where created_by='".$_SESSION["auth_id"]."' order by id desc ";
		}
		$mysqli->real_query($query);
		$res = $mysqli->use_result();

		while ($linea = $res->fetch_assoc()) {
			
			echo '<tr>';
				echo '<td><a  href="view_user.php?id='.rawurlencode(base64_encode(auyama_encrypt($linea["id"]))).'">
					<button class="btn btn-primary" >ENTRAR</button>

				 '.$linea["id"].'</a></td>';
				echo '<td>'.$linea["name"].'</td>';
				echo '<td>'. $linea["company"].'</td>';
				echo '<td class="bg-'.$linea["status"].'"><a href="disable_user.php?status='.rawurlencode(base64_encode(auyama_encrypt($linea["status"]))).'&id='.rawurlencode(base64_encode(auyama_encrypt($linea["id"]))).'">'.$linea["status"].'</a></td>';
				echo '<td>'. $linea["type"].'</td>';
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
				<th>Tipo</th>
				<th>Email</th>
            </tr>
        </tfoot>
    </table>
	</div>
	</div>
<?php } ?>
				</div>
    		</div>

			<?php
		if($_SESSION["type"] !="guest"){
			include("inc/footer.php");	
		}?>
		<script type="text/javascript" src="js/script.js"></script>
    </body>
</html>