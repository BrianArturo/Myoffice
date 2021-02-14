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
 
  $status_caso = explode(",",$s["status_caso"]);
  foreach($status_caso as $n){
    $status_caso_key = explode(":",$n);
    #echo $status_caso_key[0]." ->> ".$status_caso_key[1]."<br>";
    $status_caso_array[$status_caso_key[0]]=$status_caso_key[1];
  }




/*
FIN SETTINGS
*/
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

  <?php
		$id = auyama_decrypt(base64_decode(rawurldecode ($_GET["id"])));
    $QUERY_CONTACTO = "SELECT * from contacts where contact_id='".$id."'";
    #echo $QUERY_CONTACTO;
		$mysqli->real_query($QUERY_CONTACTO);
	  $result = $mysqli->use_result();
				 $c = $result->fetch_assoc();  
  ?>

    <?php include("inc/menu.php"); ?>
		<div class="container-fluid maxwidth"  >
    		<div id="content" class="wrapper">
          <div class="row">
              <div class="col-md-6 mt-3"><h3>Contacto N° <?php echo $c["contact_id"] ?></h3></div>
              <div class="col-md-6 mt-3 text-right"> </div>
              <div class="col-md-12"> <hr> </div>
              
          </div>
			</div>
		</div>

 

    
    <div class="container-fluid maxwidth"  >
      <div id="content" class="wrapper">
      <form method="post"  id="contact_form" name="contact_form">
        <div class="row"  >  

            <div class="col-md-4"> 
              <div class="form-group">
                <label for="name">Nombre:</label>
                <input type="text" class="form-control" id="name" name="name"  value="<?php echo $c["name"]; ?>" placeholder="Nombre contacto" required>
                <input type="hidden"   id="contact_id" name="contact_id"  value="<?php echo $_GET["id"]; ?>"  required>
                <input type="hidden"   id="created_by" name="created_by"  value="<?php echo $_SESSION["auth_id"]; ?>">
              </div>
            </div> 

            <div class="col-md-4"> 
              <div class="form-group">
                <label for="phone">Telefono:</label>
                <div class="input-group">
                  <div class="input-group-prepend ">
                      <div class="input-group-text input-group"><i class="fa fa-phone" aria-hidden="true"></i></div>
                  </div>
                  <input type="text" class="form-control" id="phone" name="phone"  value="<?php echo $c["phone"]; ?>" placeholder="Telefono" >
                </div>



               
              </div>
            </div> 

            <div class="col-md-4"> 
              <div class="form-group">
              <label for="email">E-mail:</label>
                <div class="input-group">
                  <div class="input-group-prepend ">
                      <div class="input-group-text input-group"><i class="fa fa-envelope" aria-hidden="true"></i></div>
                  </div>
                  <input type="email" class="form-control" id="email" name="email"  value="<?php echo $c["email"]; ?>" placeholder="Email" >
                </div>


              </div>
            </div> 
   
            <div class="col-md-4"> 
              <div class="form-group">
                <label for="nit">Nit:</label>
                <input type="text" class="form-control" id="nit" name="nit"  value="<?php echo $c["nit"]; ?>" placeholder="Cedula de ciudadania" >
              </div>
            </div> 

            <div class="col-md-4"> 
              <div class="form-group ">
                <label for="nit">Fecha de nacimiento:</label>
                <div class="input-group">
                  <div class="input-group-prepend ">
                      <div class="input-group-text input-group"><i class="fa fa-calendar" aria-hidden="true"></i></div>
                  </div>
                  <input type="date" class="form-control  date" id="birthdate" name="birthdate"   value="<?php echo date("Y-m-d",strtotime($c["birthdate"]));  ?>" placeholder="dd/mm/YYYY" data-provide="datepicker">
                </div>
              </div>
            </div> 

            <div class="col-md-4"> 
              <div class="form-group">
                <label for="birthplace">Lugar de nacimiento:</label>
                <input type="text" class="form-control" id="birthplace" name="birthplace"  value="<?php echo $c["birthplace"]; ?>" placeholder="Ciudad/Pais" >
              </div>
            </div> 
            <div class="col-md-4"> 
              <div class="form-group">
                <label for="address">Direcion:</label>
                <input type="text" class="form-control" id="address" name="address"  value="<?php echo $c["address"]; ?>" placeholder="Direccion de residencia" >
              </div>
            </div> 

            <div class="col-md-4"> 
              <div class="form-group">
                <label for="citty">Ciudad:</label>
                <input type="text" class="form-control" id="citty" name="citty"  value="<?php echo $c["citty"]; ?>" placeholder="Ciudad de residencia" >
              </div>
            </div> 

            <div class="col-md-12">  
            <?php if($c["contact_id"]>0){ ?>
              <button type="submit" id="update_contact" name="update_contact" class="btn btn-primary mt-2">ACTUALIZAR</button>
            <?php }else{  ?>
              <button type="submit" id="create_contact" name="create_contact" class="btn btn-primary mt-2">CREA CONTACTO</button>
            <?php } ?>
            </div>
            <div class="col-md-12 alert alert-success text-center hidden4ajax" id="log">  
            </div> 
            <div class="col-md-12 text-center hidden4ajax" id="spinner">  
              <i class="fa fa-spinner fa-3x fa-fw fa-spin" aria-hidden="true"></i> Guardando las modificaciones...
            </div>
        </div> 
        </form>     
      </div>   
      <?php if($c["contact_id"]>0){ ?>
          <div class="row"> 
          <div class="col-md-12"> 
                  <h3>Casos</h3>
                  <hr> 
                </div>
            <div class="col-md-12 row-zebra "> 
                  <div class="row mr-1 ml-1 font-weight-bold">
                    <div class="col-md-1"></div> 
                    <div class="col-md-3">NOMBRE</div> 
                    <div class="col-md-3">CODIGO</div> 
                    <div class="col-md-2">ESTADO</div> 
                    <div class="col-md-3">CREADO</div> 
                  </div>           
                  <?php 
                      $SQL_CASOS="SELECT * FROM casos_contacts join casos on casos_contacts.caso_id=casos.caso_id where contact_id=".$c["contact_id"];
                      
                      $mysqli2->real_query($SQL_CASOS);
                      $casos = $mysqli2->use_result();
                
                      while ($u = $casos->fetch_assoc()) {
                          echo  '<div class="row mr-1 ml-1">'.
                                  '<div class="col-md-1" data-toggle="tooltip" data-placement="top" title="Muestra la tarjeta del caso">'.
                                  '<a href="view_caso.php?id='.rawurlencode(base64_encode(auyama_encrypt($u["caso_id"]))).'">'.
                                  '<i class="fa fa-archive fa-2x" aria-hidden="true"></i>'.
                                  '</a>'.
                                  '</div>'.
                                  '<div class="col-md-3">'.$u["name"].'</div>'.
                                  '<div class="col-md-3">'.$u["code"].'</div>'.
                                  '<div class="col-md-2">'.$status_caso_array[$u["status"]].'</div>'.
                                  '<div class="col-md-3">'.$u["creation_date"].'</div>'.
                                '</div>';
    
                      } 
                  
                  ?>
                
            </div>
        </div>      
      <?php } ?>
    </div>



    <?php include("inc/footer.php"); ?>
		<script type="text/javascript" src="js/script.js"></script>
    </body>
</html>
