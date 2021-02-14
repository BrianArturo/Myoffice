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
				<h3>Log</h3>
			</div>
		</div>

            <div class="container-fluid mb-5" style="max-width:1450px; ">
    			<div id="content" class="wrapper">

                <?php
if(  $_SESSION["type"] !="admin" ){
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


#echo $usersettings;
?>
<table id="contatti" class="table table-striped table-bordered display" <?php //echo $usersettings; ?> style="width:100%">
			<thead>
				<tr>
					<th>Log Id</th>
					<th>User</th>
					<th>data</th>
					<th>Section</th>
					<th>Ip</th>
					<th>Msg</th>
				</tr>
	        </thead>
        <tbody>
		<?php
		#var_dump($_SESSION);
		if($_SESSION["created_by"]==NULL){
			$query = "SELECT * from log order by log desc ";
		}
		else{
			$query = "SELECT * from log where user_id = '".$_SESSION["auth_id"]."'  or user_id IN (SELECT id from users where created_by= '".$_SESSION["auth_id"]."') order by log desc ";
		}

		$mysqli->real_query($query);
		$res = $mysqli->use_result();

		while ($linea = $res->fetch_assoc()) {
			echo '<tr>';
				echo '<td>'. $linea["log"].'</td>';
				echo '<td>'. $linea["user_id"].'</td>';
				echo '<td>'. $linea["data"].'</td>';
				echo '<td>'. $linea["section"].'</td>';
				echo '<td>'. $linea["ip"].'</td>';
				echo '<td>'. $linea["msg"].'</td>';
 
		echo "</tr>";


		}
		?></tbody>
		<tfoot>
            <tr>
            <th>Log Id</th>
            <th>User</th>
            <th>data</th>
            <th>Section</th>
            <th>Ip</th>
            <th>Msg</th>
            </tr>
        </tfoot>

    </table>

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
