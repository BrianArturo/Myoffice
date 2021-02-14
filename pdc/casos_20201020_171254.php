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
$QUERY_SETTINGS="SELECT * FROM settings where owned_by = '".$_SESSION["auth_id"]."' or owned_by = '".$_SESSION["created_by"]."'";
$mysqli->real_query($QUERY_SETTINGS);
$result = $mysqli->use_result();

while ($row = $result->fetch_assoc()) {
  $s[$row["name"]]=$row["value"];
  #echo $row["name"]." - > ".$row["value"]."<br>";
}
$contacts_type = explode(",",$s["status_caso"]);

foreach($contacts_type as $c){
  $contacts_type_key = explode(":",$c);
  #echo $contacts_type_key[0]." ->> ".$contacts_type_key[1]."<br>";
	$status_caso[$contacts_type_key[0]]=$contacts_type_key[1];
	//var_dump($contacts_type_key[0]);
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
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
       
 <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

		<?php include("inc/header.php"); ?>
        <script  src="https://code.jquery.com/ui/1.12.0/jquery-ui.min.js" integrity="sha256-eGE6blurk5sHj+rmkfsGYeKyZx3M4bG+ZlFyA7Kns7E="  crossorigin="anonymous"></script>
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    </head>
    <body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

 

        <?php include("inc/menu.php"); ?>
		<div class="container-fluid maxwidth"  >
    		<div id="content" class="wrapper"><br>
			<div class="row">
				<div class="col-md-6"><h3>Casos</h3></div>
				<?php
				if($_SESSION["type"] !="guest"){
				?>
				<div class="col-md-6 text-right">
					<h3><a href="add_casos.php" class="btn btn-primary" >CREAR <i class="fa fa-plus-circle" aria-hidden="true"></i></a> </h3>
				</div>
		
				<?php } ?>
			</div>
				
				

			</div>
		</div>

            <div class="container-fluid mb-5 maxwidth"  >
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


#echo $usersettings;
?> 	<br>		
<div class="responsive"> 
<table id="contatti" class="table  table-bordered display dt-responsive " <?php //echo $usersettings; ?> style="width:100%">
			<thead>
				<tr>
					<th>Caso</th>
					<th>Ver</th>
					<th>Nombre</th>
					<th>Codice</th>
					<th>Estado</th>
					<th>Fecha</th>
				</tr>
	        </thead>
        <tbody>
		<?php

			/*var_dump($_SESSION["type"]);
			var_dump($target_id);*/
			if($_SESSION["type"] =="admin" ){
				if($target_id> 0){
					$query = "SELECT * from casos where status='".$target_id."' and created_by='".$_SESSION["auth_id"]."'";
				}else{
					$query = "SELECT * from casos where status>0 and created_by='".$_SESSION["auth_id"]."' or created_by='".$_SESSION["created_by"]."' or created_by IN (SELECT id FROM users WHERE created_by ='".$_SESSION["auth_id"]."' ) ";
				}
		}else{
				if($target_id> 0){
					$query = "SELECT casos.*, user_casos.user_id from casos left  join user_casos on  ( user_casos.caso_id = casos.caso_id ) where created_by =".$_SESSION["auth_id"]." or created_by =".$_SESSION["created_by"]." and status='".$target_id."'";
				}else{
					//$query = "SELECT casos.*, user_casos.user_id from casos left  join user_casos on  ( user_casos.caso_id = casos.caso_id ) where created_by =".$_SESSION["auth_id"]." or created_by =".$_SESSION["created_by"]." and status>0";
					$query = "SELECT casos.*, user_casos.user_id from casos left  join user_casos on  ( user_casos.caso_id = casos.caso_id )";
				}
		}
		//var_dump($query);
		$mysqli->real_query($query);
		$res = $mysqli->use_result();
		$count=1;
		while ($linea = $res->fetch_assoc()) {
			if($linea["user_id"]==$_SESSION["auth_id"] or $_SESSION["type"] =="admin" )
			{
				
			
			echo '<tr>';
				echo '<td style="width:1%;">'.$count.'</td>';
				if(  $_SESSION["type"] =="guest" ){
					#'<!--<i class="fa fa-trash fa-2x delete_caso" aria-hidden="true " object="delete_caso" item="'.rawurlencode(base64_encode(auyama_encrypt($linea["caso_id"]))).'"></i>--> '. 
					echo '<td style="width:5%;"> '.
					'<a  href="visitante.php?id='.rawurlencode(base64_encode(auyama_encrypt($linea["caso_id"]))).'&count='.rawurlencode(base64_encode(auyama_encrypt($count))).'"   >
					<button class="btn btn-primary" ><i class="fa fa-pencil-square-o  " aria-hidden="true"></i></button></a> ';
				}
				else{
					#'<!--<i class="fa fa-trash fa-2x delete_caso" aria-hidden="true " object="delete_caso" item="'.rawurlencode(base64_encode(auyama_encrypt($linea["caso_id"]))).'"></i>--> '. 
				echo '<td style="width:5%;"> '.
				'<a  href="view_caso.php?id='.rawurlencode(base64_encode(auyama_encrypt($linea["caso_id"]))).'&count='.rawurlencode(base64_encode(auyama_encrypt($count))).'" data-toggle="tooltip"  >
				<button class="btn btn-primary" >  Entrar al caso </button></a> ';
			}
				/*if(  $_SESSION["type"] =="admin" ){
					echo '<a  href="assign.php?id='.rawurlencode(base64_encode(auyama_encrypt($linea["caso_id"]))).'"> '.
					'<button class="btn btn-primary" ><i class="fa fa-user-plus  " aria-hidden="true"></i></button></a> ';
				}*/
				//echo $linea["caso_id"];
				#echo 'Id:'.$linea["caso_id"];
				$count++;
				echo '</td>';
				echo '<td style="width:10%;">'.$linea["name"].'</td>';
				echo '<td style="width:10%;">'.$linea["code"].'</td>';
 
            echo '<td style="width:10%;" class="bg-'.$status_caso[$linea["status"]].'">'.$status_caso[$linea["status"]].'</td>';
				echo '<td style="width:10%;">'. date (" H:i d/m/Y", strtotime( $linea["last_update"]) ).'</td>';
		 
		echo "</tr>";
		
	}

		}
		?></tbody>
		<tfoot>
            <tr>
				<th>Caso</th>
				<th>Ver</th>
				<th>Nombre</th>
				<th>Codice</th>
				<th>Estado</th>
				<th>Fecha</th>
            </tr>
        </tfoot>

    </table>
					</div>

				</div>
    		</div>

		<?php
		if($_SESSION["type"] !="guest"){
			include("inc/footer.php");	
		}?>
		<script type="text/javascript" src="js/script.js"></script>
      
    </body>
</html>
