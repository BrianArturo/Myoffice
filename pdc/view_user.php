<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
include("inc/config.php");
session_start();
if (!isset($_SESSION["auth_id"]) ){ header ("Location:index.php"); }
if($_GET['logout']=="logout"){ unset($_SESSION); }
/*
  SETTINGS
*/
$QUERY_SETTINGS="SELECT * FROM settings where owned_by = '".$_SESSION["auth_id"]."'";
$mysqli->real_query($QUERY_SETTINGS);
$result = $mysqli->use_result();

while ($row = $result->fetch_assoc()) {
  $s[$row["name"]]=$row["value"];
  #echo $row["name"]." - > ".$row["value"]."<br>";
}
$contacts_type = explode(",",$s["status_caso"]);
foreach($contacts_type as $c){
  $contacts_type_key = explode(":",$c);
  # echo $contacts_type_key[0]." ->> ".$contacts_type_key[1]."<br>";
	$status_caso[$contacts_type_key[0]]=$contacts_type_key[1];
	
	if(urldecode($_GET["status"]) ==$contacts_type_key[1]){
		$target_id=$contacts_type_key[0];
	}
}
/*
FIN SETTINGS
*/
?>
<!DOCTYPE HTML>
    <head>
        <title><?php echo $TITULO ?></title>
        <link rel="icon" href="favicon.ico" type="image/x-icon">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
		<?php include("inc/header.php"); ?>
    </head>
    <body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
        <?php include("inc/menu.php"); ?>
		<div class="container-fluid" style="max-width:1450px;margin-top:20px;">
    		<div id="content" class="wrapper">
				<div class="row">
					<div class="col-md-12"><h3>Usuario</h3></div>
				</div>
			</div>
		</div>

        <div class="container-fluid mb-5" style="max-width:1450px; ">
            <div id="content" class="wrapper">
                <div class="row mt-3 mt-3">
                    <div class="col-md-12">
                        <?php
                        if(  $_SESSION["type"] !="admin" ){
                            ydhatta();
                        }else{
                            $_ID = auyama_decrypt(base64_decode(rawurldecode ($_GET["id"])));
                            if(isset($_POST["updateuser"])){
                                $redirect=false;
                                if(strlen($_POST["password"])> 7){
                                    $SETPASSWORD=", password='".md5(trim($_POST["password"]))."'";
                                }else{
                                    $SETPASSWORD=" ";
                                }
                               $UPDATE_USERS   =    "UPDATE users set".
                                                    " name='".$_POST["name"]."' , email='".trim($_POST["email"])."' , type='".$_POST["accounttype"]."' , company='".$_POST["company"]."' ".
                                                    $SETPASSWORD." where id='".$_ID."' and id >1";
                               
                                if( $_POST["borra"]=="Si" ){
                                    $UPDATE_USERS="DELETE from users where id='".$_ID."' and id >2 limit 1";
                                    $redirect=true;
                                }
                                #echo  $UPDATE_USERS;
                                $mysqli2->real_query($UPDATE_USERS);
                                if($redirect){
                                    header("Location: usuarios.php");
                                }
                                
                            }

                            
                            $SELECT_USERS   = "select * from users where id='". $_ID."' ;";
                            $mysqli->real_query($SELECT_USERS);
                            $user = $mysqli->use_result();
                            $u = $user->fetch_assoc();
                             
                            
 ?>
	<form id="form1" name="form1" method="post" action="">
		<div class="form-group row m-auto " style="max-width:900px;" >
			<div class="col-md-12 text-center">
				<h3>Modifica usuario</h3>
			</div>
			<div class="col-md-6">
				<label for="name" class="col-form-label">Nombre*</label>
				<input class="form-control" type="text" name="name" value="<?php echo $u["name"]; ?>" required>
			</div>
			<div class="col-md-6">
				<label for="email" class="col-form-label">email*</label>
				<input class="form-control" type="text" name="email" value="<?php echo $u["email"]; ?>" required>
			</div>
            
			<div class="col-md-6">
				<label for="accounttype" class="col-form-label">Tipo*</label>
				<select class="form-control" name="accounttype" required>
                <?php
                    $usera_array=array("user"=>"Empleado","admin"=>"Administrador","guest"=>"Visitante");
                    echo '<option value="'.$u["type"].'" selected>'.$usera_array[$u["type"]].'</option>';
                ?>
					<option value="user"> Empleado</option>
					<option value="admin"> Administrador</option>
                    <option value="guest"> Visitante</option>
				</select>
			</div>
            
			<div class="col-md-6">
				<label for="company" class="col-form-label">Empresa</label>
				<input class="form-control" type="text" name="company" value="<?php echo $u["company"]; ?>" >
			</div>
			<div class="col-md-6">
				<label for="company" class="col-form-label">Password </label>
				<input class="form-control" type="text" name="password"  placeholder="Compilar solo si se quiere cambiar la password" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*_=+-]).{8,12}$" >
			</div>
            <!--
            <div class="col-md-6 mt-3">
                Borra este usuario:<br>
                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                        <label class="btn btn-info  ">
                        <input type="radio" name="borra"   value="Si" autocomplete="off"> Si </label>
                        <label class="btn btn-info active">
                        <input type="radio" name="borra"  value="No" autocomplete="off" checked=""> No </label>
                </div>
                -->
            </div>
			<div class="col-md-12  ">
            <em class="text-secondary">Maximo/min 12/8 maiuscula/minuscula y !@#$%^&*_=+-</em>
            </div>
			<div class="col-md-12 text-center">
				<button type="submit" name="updateuser" class="btn btn-success btn-lg m-3">Modifica Usuario</button>
			</div>

		</div>
	</form>



<?php
                        }
                        ?>
                    </div>
                </div>
                <div class="row mt-3 mt-3">
                    <div class="col-md-12">
                        <hr/>
                    </div>
                </div>

                <div class="row mt-3 mt-3">
                    <div class="col-md-12">
                    <table id="contatti" class="table table-striped table-bordered display" <?php echo $usersettings; ?> style="width:100%">
                        <thead>
                            <tr>
                                <th>Id caso</th>
                                <th>Nombre</th>
                                <th>Codice</th>
                                <th>Estado Caso</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>
                    <tbody>
                <?php
                $query = "SELECT casos.*, user_casos.user_id from casos left  join user_casos on  ( user_casos.caso_id = casos.caso_id ) where user_id =". $u["id"]." and status>0 order by caso_id desc ";
                 
                $mysqli2->real_query($query);
                $res = $mysqli2->use_result();
        
                while ($linea = $res->fetch_assoc()) {
                    echo '<tr>';
                        echo '<td>';
                        echo '<a href="view_caso.php?id='.rawurlencode(base64_encode(auyama_encrypt($linea["caso_id"]))).'">
                            <button class="btn btn-primary" >EDITAR</button></a>
                         ';
                        echo $linea["caso_id"];
                        echo '</td>';
                        echo '<td>'.$linea["name"].'</td>';
                        echo '<td>'.$linea["code"].'</td>';
         
                    echo '<td class="bg-'.$status_caso[$linea["status"]].'">'.$status_caso[$linea["status"]].'</td>';
                        echo '<td>'. date (" H:i d/m/Y", strtotime( $linea["last_update"]) ).'</td>';
                 
                echo "</tr>";
        
        
                }
                    ?></tbody>
                    <tfoot>
                        <tr>
                            <th>Id caso</th>
                            <th>Nombre</th>
                            <th>Codice</th>
                            <th>Estado Caso</th>
                            <th>Fecha</th>
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
