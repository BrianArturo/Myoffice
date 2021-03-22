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
		<?php include("inc/header.php"); ?>
    </head>
    <body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
  <?php include("inc/menu.php"); ?>
	<div class="container-fluid mb-5" style="max-width:800px; ">
            <div id="content" class="wrapper">
                <div class="row">
								<div class="col-md-12 text-center">
									<h3>Informaciones del usuario</h3>
								</div>
                    <div class="col-md-12 p-3">
								<?php
								if(isset($_POST["updateuser"])){
									$CHANGE_PASSWORD   = "UPDATE users set password ='".md5($_POST["password"])."' where id='". $_SESSION["auth_id"]."' ;";
									$mysqli2->real_query($CHANGE_PASSWORD);
                                    login('password' ,$MSG[5]);
									echo 	'<div class="alert alert-success" role="alert">'.
									'<i class="fa fa-floppy-o fa-2x" aria-hidden="true"></i> '.$MSG[4].
										'</div>';
								}

								$SELECT_USERS   = "select * from users where id='". $_SESSION["auth_id"]."' ;";
																					$mysqli->real_query($SELECT_USERS);
																					$user = $mysqli->use_result();
																					$u = $user->fetch_assoc();
							?>
            </div>
        </div>
					<form id="form1" name="form1" method="post" action="">
						<div class="form-group row m-auto " style="max-width:900px;" >

							<div class="col-md-7">
								<label for="name" class="col-form-label">Nombre:</label>
								<?php echo $u["name"]; ?>
							</div>
							<div class="col-md-5">
								<label for="email" class="col-form-label">email:</label>
								<?php echo $u["email"]; ?>
							</div>
							<div class="col-md-7">
								<label for="accounttype" class="col-form-label">Tipo:</label>
								<?php $usera_array=array("user"=>"Empleado","admin"=>"Administrador"); echo $usera_array[$u["type"]]; ?>
							</div>
							<div class="col-md-5">
								<label for="company" class="col-form-label">Empresa:</label>
								<?php echo $u["company"]; ?>
							</div>
							<div class="col-md-7">
								<input class="form-control" type="text" name="password" title="" placeholder="Compilar solo si se quiere cambiar la password" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*_=+-]).{8,12}$" required>
								<em class="text-secondary">Maximo/min 12/8 maiuscula/minuscula y !@#$%^&*_=+-</em>
							</div>
							<div class="col-md-3  ">
								<button type="submit" name="updateuser" class="btn btn-success" style="background-color: #CB6CE6;">Modifica Usuario</button>
							</div>
						</div>
					</form>
			</div>
   </div>
   <?php
		if($_SESSION["type"] !="guest"){
			include("inc/footer.php");	
		}?>
		<script type="text/javascript" src="js/script.js"></script>
    </body>
</html>