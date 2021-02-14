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
	<link href="dist/summernote-bs4.css" rel="stylesheet">
  <script src="dist/summernote-bs4.js"></script>

	<?php include("inc/header.php"); ?>


</head>
<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<?php include("inc/menu.php"); ?>


	<div class="container  " style="margin-top:30px;">
		<div class="row"  >
    		<div   class="col-md-12">
				  	<h3>Gestione preventivo</h3>
			</div>
		</div>
		<?php

		$id = auyama_decrypt(base64_decode(rawurldecode ($_GET["id"])));
		$query = "SELECT * from Contatti where id='".$id."'";
		$mysqli->real_query($query);
	  $result = $mysqli->use_result();
				 $u = $result->fetch_assoc();
				 if($u["ritorno"]=="No"){
					  $u["data_servizio_ritorno"]="N/A";
					  $u["ora_servizio_ritorno"]="N/A";
				 }
		?>

		<div class="form-group row reply">
			<div class="col-md-12 decoline"><label for="email" class="col-form-label"><i class="fa fa-users" aria-hidden="true"></i>  Anagrafica</label></div>

			<hr>
			<div class="col-md-6"><span>Id Cliente: </span><?php echo $u["id"]; ?> </div>
			<div class="col-md-6"><span>Nome e Cognome: </span> <?php echo $u["nome"]; ?></div>
			<div class="col-md-6"><span>E-mail: </span><?php echo $u["email"]; ?> </div>
			<div class="col-md-6"><span>Telefono: </span><?php echo $u["telefono"]; ?></div>
			<div class="col-md-6"><span>Data servizio: </span><?php echo $u["data_servizio"]; ?> </div>
			<div class="col-md-6"><span>Ora partenza:  </span><?php echo $u["ora_servizio"]; ?></div>
			<div class="col-md-6"><span>Passeggeri:  </span><?php echo $u["passeggeri"]; ?></div>
			<div class="col-md-6"><span>Veicolo:  </span><?php echo $u["veicolo"]; ?></div>
			<div class="col-md-6"><span>Codice Promozionale:  </span><?php echo $u["promocode"]; ?></div>
			<div class="col-md-6"><span>Privacy:  </span><?php echo $u["privacy"]; ?></div>
			<div class="col-md-6"><span>Condizioni:  </span><?php echo $u["condizioni"]; ?></div>
			<div class="col-md-6"><span>Newsletter:  </span><?php echo $u["newsletter"]; ?></div>
			<div class="col-md-6"><span>Status:  </span><?php echo $u["status"]; ?></div>
			<div class="col-md-6"><span>Lingua:  </span><?php echo $u["lang"]; ?></div>

			<div class="col-md-6"><span>Data/ora richiesta:  </span><?php echo date (" H:i d/m/Y", strtotime( $u["datalog"]) ); ?></div>
			<div class="col-md-12"><span>Note:  </span><br><?php echo  $u["note"] ; ?></div>
			<div class="col-md-12"><span>Indirizzo ip/Agent:  </span><?php echo $u["ip_address"]; ?></div>


		</div>
		<div class="form-group row reply">
			<div class="col-md-12 decoline"><label for="carica" class="col-form-label"><i class="fa fa-car" aria-hidden="true"></i> Partenza</label></div>

			<div class="col-md-6"><span>Partenza: </span> <?php echo $u["citta_partenza"]; ?></div>
			<div class="col-md-6"><span>Indirizzo: </span><?php echo $u["indirizzo_partenza"]; ?></div>
		</div>
		<div class="form-group row reply">
			<div class="col-md-12 decoline"><label for="carica" class="col-form-label"><i class="fa fa-plane" aria-hidden="true"></i> Destinazione</label></div>

			<div class="col-md-6"><span>Città: </span><?php echo $u["citta_destinazione"]; ?></div>
			<div class="col-md-6"><span>Indirizzo: </span><?php echo $u["indirizzo_destinazione"]; ?></div>
		</div>
		<div class="form-group row reply">
			<div class="col-md-12 decoline"><label for="carica" class="col-form-label"><i class="fa fa-undo" aria-hidden="true"></i> Ritorno</label></div>

			<div class="col-md-6"><span>Data ritorno: </span><?php echo $u["data_servizio_ritorno"]; ?></div>
			<div class="col-md-6"><span>Ora Ritorno: </span><?php echo $u["ora_servizio_ritorno"]; ?></div>

		</div>


					<form id="quote-form" name="quote-form" >
								<div class="form-group row ">
									<div class="col-md-12   decoline ">
											<label for="carica" class="col-form-label"><i class="fa fa-fighter-jet" aria-hidden="true"></i> Preventivo</label>
									</div>
									<div class="hidden4ajax" id="form-save"  >
											<div class="row"    >
													<div class="col-md-12 text-center">
															<i class="fa fa-cog fa-spin fa-3x fa-fw margin-bottom"></i> Salvataggio in corso...
													</div>
											</div>
									</div>
									<div class="quote-box" id="quote-box">
													<div class="col-md-4 input-group" style="padding:15px;">
														<span class="input-group-addon">€</span>
														<input type="text" id="price" name="price" class="form-control" placeholder="Esempio importo preventivo 123,00">
													</div>
													<div class="col-md-12">Note: </span>
														<textarea name="notes" id="notes"  class="form-control notes"> </textarea>
													</div>

												<div class="form-group row reply">
													<div class="col-md-6"><button type="button" name="send" id="send" class="btn btn-info btn-md btn-block">INVIO PREVENTIVO</button></div>
												</div>
												<input type="hidden" id="lang" name="lang" class="" value="<?php echo $u["lang"]; ?>" >
												<input type="hidden" id="client_id" name="client_id" class="" value="<?php echo $id; ?>" >
									 </div>
							</div>
				</form>


			<div class="row reply"  >
	    	<div   class="col-md-12 decoline">
					  	<h3>Preventivi già inviati:</h3>
				</div>

				<?php
				$query = "SELECT * from quota where  user_id=".$id." order by quota_id desc ";

				$mysqli2->real_query($query);
				$res = $mysqli2->use_result();

				while ($q = $res->fetch_assoc()) {

					echo '<div class="col-md-2"><span>Id preventivo<span> '.$q["quota_id"].'</div><div class="col-md-3"><span>Data invio:</span> '.$q["date"].'</div>';
					echo '<div class="col-md-3"><span>Importo:</span> € '.$q["price"].'</div>';
					echo '<div class="col-md-12"><span>Note:</span></div>';
					echo '<div class="col-md-12">'.$q["note"].'</div>';
					echo '<div class="col-md-12"><hr></div>';
				}

				 ?>
				 </div>
			</div>

</div>

<?php

/*
INSERT INTO `quota` (`quota_id`, `user_id`, `date`, `price`, `note`, `status`) VALUES (NULL, '12', 'now()', '123,45', 'ciao', 'ok');
*/
 ?>
        <?php include("inc/footer.php"); ?>
        <script type="text/javascript" src="js/script.js"></script>
    </body>
</html>
