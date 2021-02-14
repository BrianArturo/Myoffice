<?php
session_start();
if($_GET["logout"]=="true"){
	  session_start();
    session_destroy();
    setcookie("PHPSESSID","",time()-3600,"/");
    unset($_SESSION);
}

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
include("../pdc/inc/config.php");
$id=auyama_decrypt(base64_decode(rawurldecode (str_replace("-nube24-", "%2F",$_GET["id"]))));
 
if(isset($_POST["password"])){
  $password = $mysqli->real_escape_string($_POST["password"]); 
  $CHECKPASSWORD="SELECT * FROM `casos_contacts` where contact_id=(SELECT contact_id from contacts where password='".$password."') and caso_id='".$id."'";
  #echo $CHECKPASSWORD;
  $mysqliauth = new mysqli($host, $user, $pass, $name);
  $QUERY_CASOS = "SELECT * from casos where caso_id='".$id."'";
  $mysqliauth->real_query($CHECKPASSWORD);
  $result = $mysqliauth->use_result();
  $auth = $result->fetch_assoc();  
  #echo "::".$auth["caso_id"]."::";
  if($auth["caso_id"] == $id){
    $_SESSION["ospite"]=$auth["caso_id"];
  }
  $mysqliauth->close();
}
 /*
    SETTINGS
  */
  $QUERY_SETTINGS="SELECT * FROM settings where owned_by = (SELECT created_by from casos where caso_id ='".$_SESSION["ospite"]."')";
  //var_dump($QUERY_SETTINGS);exit();
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
 
  $mysqliu = new mysqli($host, $user, $pass, $name);
  $QUERY_CASOS = "SELECT * from casos where caso_id='".$id."'";
  $mysqliu->real_query($QUERY_CASOS);
  $result = $mysqliu->use_result();
  $c = $result->fetch_assoc();  



/*
FIN SETTINGS
*/
?>
<!DOCTYPE HTML>
    <head>
        <title>Nube 24 | <?php echo $_SESSION["ospite"]; ?> </title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <?php include("../pdc/inc/header.php"); ?>
        <script  src="https://code.jquery.com/ui/1.12.0/jquery-ui.min.js" integrity="sha256-eGE6blurk5sHj+rmkfsGYeKyZx3M4bG+ZlFyA7Kns7E="  crossorigin="anonymous"></script>
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <link href="../pdc/css/style.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="js/fancybox/jquery.fancybox.min.css" />
        <script src="js/fancybox/jquery.fancybox.min.js"></script>



    </head>
    <body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<div class="container-fluid maxwidth"  >
<div id="content" class="wrapper">


 

   


<?php if($_SESSION["ospite"]>0){ ?>
          <div class="row">
              <div class="col-md-6 mt-3"><h3>Caso N° <?php echo $c["caso_id"] ?></h3></div>
              <div class="col-md-6 mt-3 text-right"> <a href="?logout=true"><i class="fa fa-sign-out" aria-hidden="true"></i>
 Logout</a></div>
 
          </div>
        <div class="row">
            <div class="col-md-3"> 
                <label for="name" class="font-weight-bold mr-2">Nombre: </label>
                <span id="caso_name" > <?php echo $c["name"]; ?> </span> 
            </div> 
            <div class="col-md-3  "> 
                <label for="code" class="font-weight-bold mr-2">Codigo:</label>  
                <span id="caso_code" > <?php echo $c["code"]; ?> </span>
            </div>
            <div class="col-md-3  "> 
                <label for="code" class="font-weight-bold mr-2">Status:</label>  
                <span id="caso_code" > <?php  echo $status_caso_array[$c["status"]]; ?></span>
            </div> 
            <div class="col-md-3">  
                <label for="status" class="font-weight-bold">Creado:</label>
                <?php echo date("d/m/Y H:i",strtotime($c["creation_date"])); ?>
            </div>
            <div class="col-md-3">  
                <label for="status" class="font-weight-bold">Actualizado:</label> 
                <?php echo date("d/m/Y H:i",strtotime($c["last_update"])); ?>
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
        <!-- CONTACTOS -->
        <div class="col-md-12 row-zebra " id="contact-spool"> 
              <div class="row  font-weight-bold    ">
                 
                <div class="col-md-4">NOMBRE</div> 
                <div class="col-md-2">TELEFONO</div> 
                <div class="col-md-2">EMAIL</div> 
                <div class="col-md-2">NIT</div> 
                <div class="col-md-2">RELACIÒN</div> 
              </div>           
              <?php 
                  $SQL_CONTACTS="SELECT * FROM casos_contacts join contacts on casos_contacts.contact_id=contacts.contact_id where caso_id=".$id;
                   
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
                      echo  '<div class="row  ">'.
                              '<div class="col-md-4  ">'.$u["name"].'</div>'.
                              '<div class="col-md-2">'.$u["phone"].'</div>'.
                              '<div class="col-md-2  ">'.$email.'</div>'.
                              '<div class="col-md-2">'.$u["nit"].'</div>'.
                              '<div class="col-md-2  ">'.
                              $contact_type[$u["contact_type"]].
                              '</div>'.
                            '</div>';
 
                  } 
              
              ?>
             
            </div>
             <!-- FIN CONTACTOS -->
             <!-- DESCRIPZION -->
             <div class="col-md-12 mt-3"> 
                <h3>Descripcion del caso:</h3>
                <?php echo $c["description"]; ?> 
             </div>
             <!-- fin DESCRIPZION -->
             <!-- NOTAS -->
             <div class="col-md-12 mt-3"> 
                <h3>Notas:</h3>
 
             </div>
             <div class="col-md-12 mt-3 row-zebra "  > 
                <?php 
                    
                    $SQL_NOTES="SELECT * FROM casos_notes join notes on casos_notes.notes_id=notes.notes_id where  notes.status>0 and caso_id=".$id." order by creation asc";
                    $mysqli2->real_query($SQL_NOTES);
                    $notes = $mysqli2->use_result();
                    while ($n = $notes->fetch_assoc()) {
                        echo    '<div class="row">'.
                                    '<div class="col-md-3"><strong>Fecha:</strong> '.$n["creation"].'</div>'.
                                    '<div class="col-md-8 "><strong>Categorias:</strong> '.$n["tags"].'</div>'.
                                    '<div class="col-md-12"><strong>NOTA:</strong> '.$n["note"].'</div>'.
                                '</div>';
                    } 
                ?>
            </div>
            <!-- FIN NOTAS -->
            <!-- DOCUMENTOS -->
            <div class="col-md-12 mt-3"  > 
                <h3>Documentos</h3>
            </div>
            <div class="col-md-12" > 
              <div class="row" > 
                  <?php
                      $mydocs = new mysqli($host, $user, $pass, $name);
                      $SQL_DOCUMENTS="SELECT * FROM  documents   where documents.caso_id=".$id."  order by extention";
                      # echo $SQL_DOCUMENTS;
                      $mydocs->real_query($SQL_DOCUMENTS);
                      $documentos = $mydocs->use_result();

                      while ($u = $documentos->fetch_assoc()) {
                        if(strtolower($u["extention"])=="jpg" or $u["extention"]=="png" or $u["extention"]=="jpeg" )
                        {
                          echo  '<div class="  col-6 col-lg-1 p-lg-2 m-lg-2 mb-2 text-center  ">'.
                                  '<a href="../download/'.$u["caso_id"].'/'.$u["name"].'" class="fancy'.strtolower($u["extention"]).'" target="blank">
                                  <div class=" ">
                                  <img src="../download/'.$u["caso_id"].'/'.$u["name"].'" style="width:50px;height:50px;">'.
                                    '<div class="m-1 text-truncate ">'. $u["realname"].'</div>'.
                                  '</div></img></a>'.
                                '</div>';
                              }else{
                                echo  '<div class="  col-6 col-lg-1 p-lg-2 m-lg-2 mb-2 text-center  ">'.
                                '<a href="../download/'.$u["caso_id"].'/'.$u["name"].'" class="fancy'.strtolower($u["extention"]).'" target="blank">'.
                                  '<div class=" "><i class="fa  fa-file '.strtolower($u["extention"]).'  fa-3x" aria-hidden="true"  ></i></div>'.
                                  '<div class="m-1 text-truncate ">'. $u["realname"].'</div>'.
                                '</a>'.
                              '</div>';
                            }
                      } 
                  ?>
              </div>
            </div>
            <!-- FIN DOCUMENTOS -->

          <?php }else{ 
            $url = parse_url($_SERVER['REQUEST_URI']);
            #print_r( $url);
            ?>  
            <div class="row">
                <div class="col-md-4 card card-container">
                    <p class="text-center"><img id="profile-img" class="profile-img-card" src="../pdc/images/logo.png" style="max-width:150px;"/></p>
                    <p id="profile-name" class="profile-name-card"></p>
                    <form action="<?php echo $url["path"]; ?>" method='POST' enctype='multipart/form-data' class="form-signin">
                        <div class="form-group  ">
                            <span>Escribe la contraseña presente en la email:</span>
                            <input type="password" name="password" class="form-control form-control-lg m-0 mb-3 mt-3" placeholder="Password" required>
                        <button class="btn btn-lg btn-primary btn-block btn-signin form-control form-control-lg" id="signin"  name="signin" type="submit">ENTRA</button>
                        </div>
                    </form>
                </div>
            </div>
          <?php } ?>  
        </div>
    </div>
<script type="text/javascript">
        $(".doc, .docx, .odf, .txt, .rtf").addClass("fa-file-word-o ");
        $(".pptx").addClass("fa-file-powerpoint-o ");
        $(".flv").addClass("fa-file-video-o ");
        $(".xls, .xlsx, .ods").addClass("fa-file-excel-o");
        $(".mp4, .3gp").addClass("fa-file-video-o");
        $(".pdf").addClass("fa-file-pdf-o");
        //$(".png, .jpg, .jpeg, .gif").addClass("fa-file-image-o");
        $(".mp3, .wav").addClass("fa-file-audio-o");
        $(".zip").addClass("fa-file-archive-o");
        $('.fancyjpg, .fancypng, .fancygif, .fancyjpeg').fancybox();  

</script>

         
 
    </body>
</html>