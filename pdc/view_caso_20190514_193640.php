<?php
 header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
 header("Cache-Control: post-check=0, pre-check=0", false);
 header("Pragma: no-cache");
include("inc/config.php");
session_start();
if (!isset($_SESSION["auth_id"]) ){ header ("Location:index.php"); }
if($_GET['logout']=="logout"){ session_destroy();unset($_SESSION); }

?>
<!DOCTYPE HTML>
    <head>
        <title>Auyama Software</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

		    <?php include("inc/header.php"); ?>
        <script  src="https://code.jquery.com/ui/1.12.0/jquery-ui.min.js" integrity="sha256-eGE6blurk5sHj+rmkfsGYeKyZx3M4bG+ZlFyA7Kns7E="  crossorigin="anonymous"></script>
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

        <link rel="stylesheet" href="js/fancybox/jquery.fancybox.min.css" />
        <script src="js/fancybox/jquery.fancybox.min.js"></script>



    </head>
    <body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

  <?php
  /*
    SETTINGS
  */
      $QUERY_SETTINGS="SELECT * FROM settings";
      $mysqli->real_query($QUERY_SETTINGS);
      $result = $mysqli->use_result();
      
      while ($row = $result->fetch_assoc()) {
        $s[$row["name"]]=$row["value"];
        #echo $row["name"]." - > ".$row["value"]."<br>";
      }
      $contacts_type = explode(",",$s["contacts_type"]);
      foreach($contacts_type as $c){
        $contacts_type_key = explode(":",$c);
        # echo $contacts_type_key[0]." ->> ".$contacts_type_key[1]."<br>";
        $contact_type[$contacts_type_key[0]]=$contacts_type_key[1];
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
    $id = auyama_decrypt(base64_decode(rawurldecode ($_GET["id"])));
    
    // INICIO access CONTROL
    $QUERY_CASOS_CHECK = "SELECT * from user_casos where caso_id=".$id." AND user_id=".$_SESSION["auth_id"]."; ";
   
    $mysqli2->real_query($QUERY_CASOS_CHECK);
    $resultaccess = $mysqli2->use_result();
      
    if ($o = $resultaccess->fetch_assoc() OR $_SESSION["type"]=="admin"  ) {
        // si puedes ver esto
    }else{
        header("Location: casos.php?mmmmm");
        exit();
    }
    // Fin access CONTROL

    $mysqli2->close();
    $mysqli2 = new mysqli($host, $user, $pass, $name);

    $QUERY_CASOS = "SELECT * from casos where caso_id='".$id."'";
		$mysqli->real_query($QUERY_CASOS);
	  $result = $mysqli->use_result();
				 $c = $result->fetch_assoc();  
  ?>

    <?php include("inc/menu.php"); ?>
		<div class="container-fluid maxwidth"  >
    		<div id="content" class="wrapper">
          <div class="row">
              <div class="col-md-6 mt-3"><h3>Caso N° <?php echo $c["caso_id"] ?></h3></div>
              <div class="col-md-6 mt-3 text-right"> </div>
              <div class="col-md-12"> <hr> 
                <input type="hidden" class="form-control" id="caso_id" name="caso_id" value="<?php echo rawurlencode(base64_encode(auyama_encrypt($c["caso_id"]))); ?>" >
              </div>
          </div>
			</div>
		</div>

    <div class="container-fluid maxwidth"  >
      <div id="content" class="wrapper ">
        <div class="row" id="infobox" >  
 
  
            <div class="col-md-12"> 
              <div class="form-group form-inline">
                  <label for="name" class="font-weight-bold mr-2">Nombre: </label>
                  <span id="caso_name" > <?php echo $c["name"]; ?> </span> 
                  <input type="text" class="form-control hidden4ajax ml-1" id="caso_name_field" name="caso_name_field" value="<?php echo $c["name"]; ?>"/>
                  <button type="button" id="caso_name_field_save" class="btn btn-danger btn-sm hidden4ajax ml-1">guarda</button>
              </div> 
            </div> 

            <div class="col-md-3  "> 
              <div class="form-group form-inline">
                  <label for="code" class="font-weight-bold mr-2">Codigo:</label>  
                  <span id="caso_code" > <?php echo $c["code"]; ?> </span>
                  <div class="hidden4ajax" id="caso_code_container">
                    <input type="text" class="form-control hidden4ajax ml-1" id="caso_code_field" name="caso_code_field" value="<?php echo $c["code"]; ?>"/>
                    <button type="button" id="caso_code_field_save" class="btn btn-danger btn-sm hidden4ajax ml-1">guarda</button>
                 </div>
              </div> 
            </div>  

 
            <div class="col-md-3 form-inline"> 
              <select class="form-control custom-select small_on_mobile" id="status" name="status" required>
                  <option value="<?php echo $c["status"]; ?>" disabled selected><?php  echo $status_caso_array[$c["status"]]; ?></option>
                  <?php
                    foreach( $status_caso_array as $k => $v){
                        echo '<option value="'.$k.'">'.$v.'</option>';
                    }
                  ?>
              </select> <i class="fa fa-floppy-o fa-2x  ml-3  " id="save_status_caso" aria-hidden="true" ></i> 
            </div>
            <div class="col-md-3">  
            <label for="status" class="font-weight-bold">Creado:</label> <?php echo date("d/m/Y H:i",strtotime($c["creation_date"])); ?>
 
            </div>

            <div class="col-md-3">  
            <label for="status" class="font-weight-bold">Actualizado:</label> <?php echo date("d/m/Y H:i",strtotime($c["last_update"])); ?>
            </div>
            <?php
              $SELECT_OPTIONS="SELECT * from casos_options where caso_id='".$id."' order by option_type ";
              $mysqli2->real_query($SELECT_OPTIONS);
              $options = $mysqli2->use_result();
      
              while ($o = $options->fetch_assoc()) {

                $htmlinfo    .=   '<div class="col-md-3 option_box" option_id="'.$o["options_id"].'">'.
                                      '<div class="form-group text-truncate" data-toggle="tooltip" data-placement="top" title="'.$o["name"].'">'.
                                          '<i class="fa   '.$option_type[$o["option_type"]].'" aria-hidden="true"></i> '.
                                          '<span class="hiddeondesktop">'.$o["name"].': </span> '.
                                          $o["value"].
                                        '</div>'.
                                    '</div>';

              }
              echo $htmlinfo;
            ?>
        </div> 
        <div class="row"  >  


            <div class="col-md-12" id="contact"> 
              <h3>
                <button type="button" class="btn btn-secondary" id="add-contact" >
                <i class="fa fa-plus fa-2x" aria-hidden="true"></i> 
                </button>
                Contactos
            </h3>
              <hr> 
            </div>




 

 

            <div class="col-md-12 mt-3 mb-3 dontshow" id="contact-form"  > 
                <div class="row">
                    <div class="col-md-5" >   
                      <input type="text" class="form-control" id="contact_name" name="contact_name"   placeholder="Busca contacto por nombre "  >
                       
                    </div>
                    <div class="col-md-2" >  

                    </div>
                    
                    <div class="col-md-2" >  
                      
                      <input type="text" class="form-control" id="contact_name_new" name="contact_name_new"   placeholder="Nombre contacto" required>
                    </div>
                    <div class="col-md-3" > 
                        <button type="button" class="btn btn-secondary" id="create_add_client" name="create_add_client">
                          <i class="fa fa-user" aria-hidden="true"></i> Crea Contacto
                        </button>
                         
                    </div>
                     
                </div>
            
            </div>
            <div class="col-md-12 row-zebra " id="contact-spool"> 
              <div class="row mr-1 ml-1 font-weight-bold d-none d-lg-flex   ">
                <div class="col-md-2"></div> 
                <div class="col-md-2 text-truncate">NOMBRE</div> 
                <div class="col-md-2">TELEFONO</div> 
                <div class="col-md-2">EMAIL</div> 
                <div class="col-md-2">NIT</div> 
                <div class="col-md-2">RELACIÒN</div> 
              </div>           
              <?php 
                  $SQL_CONTACTS="SELECT * FROM casos_contacts join contacts on casos_contacts.contact_id=contacts.contact_id where caso_id=".$c["caso_id"];
                   
                  $mysqli2->real_query($SQL_CONTACTS);
                  $contacts = $mysqli2->use_result();
            

                  foreach( $contact_type as $k => $v){
                    $options_contact_type.='<option value="'.$k.'">'.$v.'</option>';
                  }

                  while ($u = $contacts->fetch_assoc()) {

                    if(!empty($u["email"])){
                      ++$count_contact;
                      $email='<a href="mailto:'.$u["email"].'">'.$u["email"].'</a>';
                      $email_icon=' <i class="fa fa-envelope-o fa-2x mt-2 guestaccess" id="ct-'.$count_contact.'" aria-hidden="true" contact-id="'.rawurlencode(base64_encode(auyama_encrypt($u["contact_id"]))).'"></i> ';
                    }else{
                      $email="";
                      $email_icon="";
                    }
                      echo  '<div class="row mr-1 ml-1">'.
                              '<div class="col-md-2" data-toggle="tooltip" data-placement="top" >'.
                              '<i class="fa fa-trash fa-2x mt-2 delete-contact" aria-hidden="true" contact-id="'.rawurlencode(base64_encode(auyama_encrypt($u["contact_id"]))).'"></i> '.
                              '<a href="view_contact.php?id='.rawurlencode(base64_encode(auyama_encrypt($u["contact_id"]))).'">'.
                              '<i class="fa fa-address-book fa-2x mt-2" aria-hidden="true"></i>'.
                              '</a>'.
                              $email_icon.
                              '</div>'.
                              '<div class="col-md-2 text-truncate">'.$u["name"].'</div>'.
                              '<div class="col-md-2">'.$u["phone"].'</div>'.
                              '<div class="col-md-2 text-truncate">'.$email.'</div>'.
                              '<div class="col-md-2">'.$u["nit"].'</div>'.
                              '<div class="col-md-2 form-inline">'.
                              '<select class="form-control custom-select small_on_mobile" id="contact_type'.$u["contact_id"].'" name="contact_type'.$u["contact_id"].'" required>'.
                              '<option value="'.$u["contact_type"].'" disabled selected>'.$contact_type[$u["contact_type"]].'</option>'.
                              $options_contact_type.
                              '</select><i class="fa fa-floppy-o fa-2x  ml-3" id="save_contact_type" selectctl="contact_type'.$u["contact_id"].'"  contact="'.rawurlencode(base64_encode(auyama_encrypt($u["contact_id"]))).'" aria-hidden="true" ></i>'.
                              '</div>'.
                            '</div>';
 
                  } 
              
              ?>
             
            </div>
            
              <div class="col-md-12"> 
              <form method="post">
                <label for="notes"></label> 
                <h3>Descripcion del caso:</h3>
                <hr> 
                <textarea name="notes" id="notes"  class="notes" style="width:100%;"><?php echo $c["description"]; ?></textarea>
              </div>
              <div class="col-md-12">  
                <button type="button" id="save_caso" name="save_caso" class="btn btn-primary">ACTUALIZAR </button> </form>
              </div>
              <div class="col-md-12 alert alert-success text-center hidden4ajax" id="log">  
              </div> 
              <div class="col-md-12 text-center hidden4ajax" id="spinner">  
                  <i class="fa fa-spinner fa-3x fa-fw fa-spin" aria-hidden="true"></i> Guardando las modificaciones...
              </div>
            
            <div class="col-md-12 mt-3"> 
              <h3>
                <button type="button" id="add_note" name="add_note" class="btn btn-secondary" data-toggle="tooltip" data-placement="top" title="Agrega una nota">
                <i class="fa fa-plus fa-2x" aria-hidden="true"></i> 
                </button> Notas
            </h3>
              <hr> 
            </div>



            <div class="col-md-12 mt-3 mb-3 dontshow" id="note-form"  > 
                <div class="row">
                    <div class="col-md-8 m-auto" > 
                      <form method="post" id="notes-form" name="notes-form">  
                      <input type="text" class="form-control" id="note_category" name="note_category"   placeholder="Categorias"  >
                       
                    </div>
                    <div class="col-md-8 m-auto" >   
                       <textarea name="note_text" id="note_text"  class="notes"  placeholder="Nota, texto libre"> </textarea>
                       
                    </div>
                    <div class="col-md-8 m-auto">  
                      <button type="button" id="save_note" name="save_note" class="btn btn-primary">Guarda </button> </form>
                    </div>
  
                    <div class="col-md-8 m-auto text-center hidden4ajax" id="spinner-note">  
                        <i class="fa fa-spinner fa-3x fa-fw fa-spin" aria-hidden="true"></i> Guardando las modificaciones...
                    </div>
                </div>
            
            </div>


 

            <div class="col-md-12 mt-3 row-zebra " id="note-spool"> 
 
              <?php 
                    $SQL_NOTES="SELECT * FROM casos_notes join notes on casos_notes.notes_id=notes.notes_id where  notes.status>0 and caso_id=".$c["caso_id"]." order by creation asc";
                    
                    $mysqli2->real_query($SQL_NOTES);
                    $notes = $mysqli2->use_result();

                    while ($n = $notes->fetch_assoc()) {
                        echo  '<div class="row mr-1 ml-1">'.
                                '<div class="col-md-1">'.
                                  '<i class="fa fa-trash fa-2x delete_note" aria-hidden="true " object="delete_note" item="'.rawurlencode(base64_encode(auyama_encrypt($n["notes_id"]))).'"></i>'.
                                '</div>'.
                                '<div class="col-md-3"><strong>Fecha:</strong> '.$n["creation"].'</div>'.
                                '<div class="col-md-8 "><strong>Categorias:</strong> '.$n["tags"].'</div>'.
                                '<div class="col-md-12"><strong>NOTA:</strong> '.$n["note"].'</div>'.
                              '</div>';
  
                    } 
                
                ?>
            </div>
            <div class="col-md-12 mt-3 pt-3"> 
              <h3>
                <a href="documentos.php?id=<?php echo rawurlencode(base64_encode(auyama_encrypt($c["caso_id"]))); ?>">
                <button type="button" class="btn btn-secondary" data-toggle="tooltip" data-placement="top" title="Agrega un documento">
                <i class="fa fa-plus fa-2x" aria-hidden="true"></i> 
                </button>
                </a>
                Documentos
            </h3>
              <hr> 
            </div>
            <div class="col-md-12   ">
              <div class="row " id="filespool">      
  
              </div>
            </div>

        </div>      
      </div>      
    </div>



    <?php include("inc/footer.php"); ?>
		<script type="text/javascript" src="js/jquery.pressAndHold.js"></script>
		<script type="text/javascript" src="js/script.js"></script>
		<script type="text/javascript" src="js/options.js"></script>
		<script type="text/javascript" src="js/caso.js"></script>
		<script type="text/javascript" src="js/email.js"></script>
    <script type="text/javascript"  >
    var caso_id = $("#caso_id").val();
    $.ajax({
										type: 'POST',
										url: 'ajax_document_list.php',
										data: {
											'function':      'list_document',
											'caso_id':		caso_id 
										},
										success: function(response){
											 $("#filespool").html(response);
											update_icons();	
											 
										}
                  }); 
                  

    
    </script>
    </body>
</html>
