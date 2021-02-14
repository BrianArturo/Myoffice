<?php include("inc/config.php");
session_start();
if (!isset($_SESSION["auth_id"]) ){ header ("Location:index.php"); }
if($_GET['logout']=="logout"){ unset($_SESSION); }
?>
<!DOCTYPE HTML>
<head>
	<title><?php echo $TITULO ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
	<link href="css/style.css" rel="stylesheet" type="text/css">
	<link href="css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css">
	<script src="https://code.jquery.com/jquery-1.12.1.min.js"></script>
	<script src="js/jquery.dataTables.min.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
	<?php include("inc/header.php"); ?>


</head>
<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<?php include("inc/menu.php"); ?>
		<div class="container-fluid" style="max-width:1450px;margin-top:20px;">
    		<div id="content" class="wrapper">
				 
			</div>
		</div>
<div  >
	<div class="container-fluid" style="max-width:1150px;margin-top:50px;">
		<div id="content" class="wrapper">




<?php
#echo $_SESSION["sezione"];
if(isset($_POST["salva"])){


	$query_update = "INSERT INTO amira ".
					"( ".
						"id,".
						"nome,".
						"sezione,".
						"indirizzo,".
						"cap,".
						"comune,".
						"provincia,".
						"presso,".
						"cellulare,".
						"socio,".
						"stato,".
						"inscritto,".
						"giorno,".
						"mese,".
						"anno,".
						"luogo_nascita,".
						"email,".
						"carica".
					") ".
					"VALUES ( ".
						"NULL, ".
						"'".$mysqli->real_escape_string($_POST["nome"]) ."', ".
						"'".trim($_SESSION["sezione"])."', ".
						"'".$mysqli->real_escape_string($_POST["indirizzo"]) ."',".
						"'".$mysqli->real_escape_string($_POST["cap"]) ."',".
						"'".$mysqli->real_escape_string($_POST["comune"]) ."',".
						"'".$mysqli->real_escape_string($_POST["provincia"]) ."',".
						"'".$mysqli->real_escape_string($_POST["presso"]) ."',".
						"'".$mysqli->real_escape_string($_POST["cellulare"]) ."',".
						"'".$mysqli->real_escape_string($_POST["socio"]) ."',".
						"'".$mysqli->real_escape_string($_POST["stato"]) ."',".
						"'".$mysqli->real_escape_string($_POST["inscritto"]) ."',".
						"'".$mysqli->real_escape_string($_POST["giorno"]) ."',".
						"'".$mysqli->real_escape_string($_POST["mese"]) ."',".
						"'".$mysqli->real_escape_string($_POST["anno"]) ."',".
						"'".$mysqli->real_escape_string($_POST["luogo_nascita"]) ."',".
						"'".$mysqli->real_escape_string($_POST["email"]) ."',".
						"'".$mysqli->real_escape_string($_POST["carica"]) ."'".
					")";


	$mysqli->real_query($query_update);
	#echo $query_update;
}



	$query = "SELECT * from amira where id='".trim($_GET["id"])."'";
	$mysqli->real_query($query);
	$res = $mysqli->use_result();
	$r = $res->fetch_assoc();
?>
<form id="form1" name="form1" method="post" action="">
<div id="content" class="wrapper">
<div class="form-group row">
	<div class="col-md-2"><label for="nome" class="col-form-label">Nome*</label></div>
	<div class="col-md-10"><input class="form-control" type="text" name="nome" value="" required></div>
</div>

<div class="form-group row">
	<div class="col-md-2"><label for="indirizzo" class="col-form-label">indirizzo*</label></div>
	<div class="col-md-10"><input class="form-control" type="text" name="indirizzo" value="" required></div>
</div>
<div class="form-group row">
	<div class="col-md-2"><label for="cap" class="col-form-label">cap*</label></div>
	<div class="col-md-10"><input class="form-control" type="text" name="cap" value="" required></div>
</div>
<div class="form-group row">
	<div class="col-md-2"><label for="comune" class="col-form-label">comune*</label></div>
	<div class="col-md-10"><input class="form-control" type="text" name="comune" value="" required></div>
</div>
<div class="form-group row">
	<div class="col-md-2"><label for="provincia" class="col-form-label">provincia*</label></div>
	<div class="col-md-10">
	<select id="provincia" name="provincia" class="form-control"   required>
	<?php
	foreach ($province as $k=>$p){
		echo '<option value="'.$k.'">'.$p.'</option>';
		if ($r["provincia"]==$k){
				echo '<option value="'.$k.'" selected>'.$p.'</option>';
		}
	}
	?>
	</select>


 </div>
</div>
<div class="form-group row">
	<div class="col-md-2"><label for="presso" class="col-form-label">Presso/ Note/ Citofono</label></div>
	<div class="col-md-10"><input class="form-control" type="text" name="presso" value="<?php echo $r["presso"]; ?>"  ></div>
</div>
<div class="form-group row">
	<div class="col-md-2"><label for="cellulare" class="col-form-label">Recapiti telefonici*</label></div>
	<div class="col-md-10"><input class="form-control" type="text" name="cellulare" value="<?php echo $r["cellulare"]; ?>"  ></div>
</div>

<div class="form-group row">
	<div class="col-md-2"><label for="socio" class="col-form-label">Tipo di iscrizione</label></div>
	<div class="col-md-10">

<select id="socio" name="socio" class="form-control"   required>
	<?php
	foreach ($socio as $s){
		echo '<option value="'.$s.'">'.str_replace("_"," ",$s).'</option>';
		if ($r["socio"]==$s){
				echo '<option value="'.$s.'" selected>'.str_replace("_"," ",$s).'</option>';
		}
	}
	?>
	</select>
	</div>
</div>
<div class="form-group row">
	<div class="col-md-2"><label for="stato" class="col-form-label">stato</label></div>
	<div class="col-md-10">

<select id="stato" name="stato" class="form-control"   required>
	<?php
	foreach ($stato as $k=>$p){
		echo '<option value="'.$k.'">'.$p.'</option>';
		if ($r["stato"]==$k){
				echo '<option value="'.$k.'" selected>'.$p.'</option>';
		}
	}
	?>
	</select>

	</div>
</div>

<div class="form-group row">
	<div class="col-md-2"><label for="inscritto" class="col-form-label">Anno di iscrizione</label></div>
	<div class="col-md-10"><input class="form-control" type="text" name="inscritto" value="<?php echo $r["inscritto"]; ?>"  ></div>
</div>


<div class="form-group row">
	<div class="col-md-2"><label for="giorno" class="col-form-label">Data di nascita*</label></div>
	<div class="col-md-2"><input class="form-control" type="text" name="giorno" maxlength="2" size="2" value="<?php echo $r["giorno"]; ?>" required></div>
	<div class="col-md-2"><input class="form-control" type="text" name="mese" maxlength="2" size="2" value="<?php echo $r["mese"]; ?>" 	required></div>
	<div class="col-md-2"><input class="form-control" type="text" name="anno" maxlength="4" size="4"  value="<?php echo $r["anno"]; ?>" 	required></div>
	<div class="col-md-1">gg/mm/YYYY</div>
</div>
<div class="form-group row">
	<div class="col-md-2"><label for="luogo_nascita" class="col-form-label">luogo_nascita*</label></div>
	<div class="col-md-10"><input class="form-control" type="text" name="luogo_nascita" value="<?php echo $r["luogo_nascita"]; ?>" required></div>
</div>
<div class="form-group row">
	<div class="col-md-2"><label for="email" class="col-form-label">email*</label></div>
	<div class="col-md-10"><input class="form-control" type="text" name="email" value="<?php echo $r["email"]; ?>" required></div>
</div>
<div class="form-group row">
	<div class="col-md-2"><label for="carica" class="col-form-label">carica</label></div>
	<div class="col-md-10"><input class="form-control" type="text" name="carica" value="<?php echo $r["carica"]; ?>" ></div>
</div>

<div class="form-group row">
	<div class="col-md-12"><button type="submit" name="salva" class="btn btn-secondary btn-md btn-block">salva</button></div>

</div>
</form>
    		</div>
        </div>
        <? include("inc/footer.php"); ?>
        <script type="text/javascript" src="js/script.js"></script>
    </body>
</html>
