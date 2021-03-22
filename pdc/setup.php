<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
include("inc/config.php");
session_start();
if (!isset($_SESSION["auth_id"]) ){ header ("Location:index.php"); }
if($_GET['logout']=="logout"){ unset($_SESSION); }
$_ID =0;
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
				<h3>Setup/Configuraciones</h3>
			</div>
		</div>

            <div class="container-fluid mb-5" style="max-width:1450px; ">
    			<div id="content" class="wrapper">

<?php



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
			if(isset($_GET["id"])){
				$_ID = auyama_decrypt(base64_decode(rawurldecode ($_GET["id"])));
			} 
			
	
	
#if(!in_array ($_ID,array(3,4,5,7)) AND ($_ID > 0)){ 	   
if( $_SESSION["type"] =="admin" &&  !in_array($_ID,array(1,2,3,4,6,8,9,10))   ){ 	  // INI controllo di sicurezza
	
			if(isset($_POST["update_setting"])){
				if($_SESSION["created_by"]==NULL)
				{
					$UPDATE_SETTING = 'UPDATE settings set value = "'.$mysqli2->real_escape_string($_REQUEST["value"]).'" where setting_id='.$_ID;
				}else
				{
					$UPDATE_SETTING = "UPDATE settings set value = '".$mysqli2->real_escape_string($_REQUEST["value"])."' where setting_id='".$_ID."' and owned_by = '".$_SESSION["auth_id"]."'";
				}
				
				#echo $UPDATE_SETTING;
				login('settings' ,$MSG[6]);
				echo 	'<div class="alert alert-success" role="alert">'.
						'<i class="fa fa-floppy-o fa-2x" aria-hidden="true"></i> Las modificas han sido guardadas'.
				  		'</div>';
				$mysqli2->real_query($UPDATE_SETTING);
			}

			$QUERY_SETTINGS="SELECT * FROM settings";
			$mysqli->real_query($QUERY_SETTINGS);
			$result = $mysqli->use_result();
			
			while ($row = $result->fetch_assoc()) {
			$s[$row["name"]]=$row["value"];
			#echo $row["name"]." - > ".$row["value"]."<br>";
			
				if($_ID == $row["setting_id"]){



					echo '<div class="row mb-2  rounded">';
					echo '<div class="col-md-12 m-3 p-3"><h3>'.strtoupper($row["name"]).'</h3></div>';
					echo '<div class="col-md-6 m-3 p-3">';
						echo '<form action="" method="post">';
						
						if($row["setting_id"]!=10){
							echo '<input type="text" name="value" id="value" class="border border-dark" value="'.$row["value"].'">';
						}else{
							echo '<textarea name="value" id="value" class="notes border border-dark" >'.$row["value"].'</textarea>';

						}
					echo '</div><div class="col-md-2 m-3 p-3">';
					echo '<button type="submit" id="update_setting" name="update_setting" class="btn btn-primary ">GUARDA</button>';
					echo '</form>';
					echo '</div>';
					echo '<div class="col-md-12 m-3 p-3">'.
								'<div class="alert alert-primary" role="alert">'.
								'<i class="fa fa-exclamation-triangle fa-2x" aria-hidden="true"></i> '.
									$row["optional"].
						  		'</div>'.
							'</div>';
					echo '</div>';		
				}
			}

		}else{
			ydhatta();
		} // FIN controllo di sicurezza


if($_SESSION["type"] =="admin" ){
?>

<div class="row mt-2">
<div class="col-md-12">
<table id="contatti" class="table table-striped table-bordered display" <?php echo $usersettings; ?> style="width:100%">
			<thead>
				<tr>
					<th>Id configuracion</th>
					<th>Propiedad</th>
					<th>Valor</th>
				</tr>
	        </thead>
        <tbody>
		<?php
		#var_dump($_SESSION);
		if($_SESSION["created_by"]==NULL)
		{
			$query = "SELECT * from settings where owned_by IS NULL";
		}else{
			$query = "SELECT * from settings where owned_by = '".$_SESSION["auth_id"]."' order by setting_id desc ";
		}
		$mysqli->real_query($query);
		$res = $mysqli->use_result();
		$count=1;
		while ($linea = $res->fetch_assoc()) {
			//if($linea["setting_id"]==5 or $linea["setting_id"]==7 )
			//{
			echo '<tr>';
				//echo '<td><a  href="setup.php?id='.rawurlencode(base64_encode(auyama_encrypt($linea["setting_id"]))).'"><i class="fa fa-pencil-square-o fa-2x" aria-hidden="true"></i> '.$linea["setting_id"].'</a></td>';
				echo '<td><a  href="setup.php?id='.rawurlencode(base64_encode(auyama_encrypt($linea["setting_id"]))).'"> <button class="btn" style="background-color: #CB6CE6;">ENTRAR</button></a></td>';
				echo '<td>'.$linea["name"].'</td>';
				echo '<td>'. $linea["value"].'</td>';
		echo "</tr>";
		$count++;
	//}
		
		}
		?></tbody>
		<tfoot>
            <tr>
				<th>Id configuracion</th>
				<th>Propiedad</th>
				<th>Valor</th>
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
