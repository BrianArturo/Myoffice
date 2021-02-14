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

 /*
    SETTINGS
  */
  $QUERY_SETTINGS="SELECT * FROM settings where owned_by = '".$_SESSION["auth_id"]."' or owned_by = '".$_SESSION["created_by"]."'";
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
    <title>My office| <?php echo $_SESSION["ospite"]; ?> </title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?php include("../pdc/inc/header.php"); ?>
    <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.min.js"
        integrity="sha256-eGE6blurk5sHj+rmkfsGYeKyZx3M4bG+ZlFyA7Kns7E=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link href="../pdc/css/style.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="js/fancybox/jquery.fancybox.min.css" />
    <script src="js/fancybox/jquery.fancybox.min.js"></script>
</head>
<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<?php include("inc/menu.php"); ?>
    <div class="container-fluid maxwidth">
        <div id="content" class="wrapper">
            <div class="row">
                <div class="col-md-6 mt-3">
                    <h3>Caso N° <?php echo $c["caso_id"] ?></h3>
                </div>
                <div class="col-md mt-3 text-right">
                       <a href="casos.php" class="btn btn-primary"><i class="fa fa-arrow-left"  aria-hidden="true"></i> Atras</a> 
                       <a href="index.php?logout=true" class="btn btn-primary"><i class="fa fa-sign-out" aria-hidden="true"></i> Logout</a></div>
                </div>
            <div class="row" id="infobox" style="margin-left: 0px;">
                <div class="col-md-3">
                    <label for="name" class="font-weight-bold mr-2">Nombre: </label>
                    <span id="caso_name"> <?php echo $c["name"]; ?> </span>
                </div>
                <div class="col-md-3  ">
                    <label for="code" class="font-weight-bold mr-2">Codigo:</label>
                    <span id="caso_code"> <?php echo $c["code"]; ?> </span>
                </div>
                <div class="col-md-3  ">
                    <label for="code" class="font-weight-bold mr-2">Status:</label>
                    <span id="caso_code"> <?php  echo $status_caso_array[$c["status"]]; ?></span>
                </div>
                <div class="col-md-3">
                    <label for="status" class="font-weight-bold">Creado:</label>
                    <?php echo date("d/m/Y H:i",strtotime($c["creation_date"])); ?>
                </div>
                <div class="col-md-3">
                    <label for="status" class="font-weight-bold">Actualizado:</label>
                    <?php echo date("d/m/Y H:i",strtotime($c["last_update"])); ?>
                </div>
                <div class="col-md-3">
                    <label for="cuantia" class="font-weight-bold">Cuantia:</label>
                    <?php  echo "$ ".number_format($c["cuantia"], 0); ?>
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
            <div class="col-md-12" id="contact-spool">
                <div class="row mr-1 font-weight-bold d-none d-lg-flex">

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
                            '</div>'.'<br>';
 
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
            <div class="col-md-12 mt-3 row-zebra ">
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
            <div class="col-md-12 mt-3">
                <h3>
                    <button type="button" id="ver_pagos" name="ver_pagos" class="btn btn-secondary"
                        data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false"
                        aria-controls="collapseExample" data-placement="top" title="Ver Pagos del caso">
                        VER PAGOS
                    </button> 
                </h3>
                <hr>
            </div>

            <div class="container-fluid mb-5 maxwidth">
                <div id="content" class="wrapper">

                    <table id="collapseExample" class="table table-striped table-bordered display collapse"
                        <?php echo $usersettings; ?> style="width:100%">
                        <thead>
                            <tr>
                                <th>Tipo de Pago</th>
                                <th>Valor</th>
                                <th>Fecha</th>
                                <th>Descripcion</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
              $query = "SELECT * from pagos where caso_id='".$c["caso_id"]."'";
              $mysqli3 = new mysqli($host, $user, $pass, $name);
              $mysqli3->real_query($query);
              $res = $mysqli3->use_result();
              
              while ($linea = $res->fetch_assoc()) {
              /* echo "Pago ID: ".$linea["pago_id"]."<br>";
               echo "Caso ID: ".$linea["caso_id"]."<br>";
               echo "Fecha: ".$linea["fecha"]."<br>";
               echo "Valor: ".$linea["valor"]."<br>";
               echo "Tipo: ".$linea["tipo_pago"]."<br>";*/
                
              echo '<tr>';
                echo '<input id="pagoId" type="hidden" value="'.$linea["pago_id"].'" />';
                echo '<td id="tipoPago" style="width:25%;">'.$linea["tipo_pago"].'</td>';
                echo '<td class="valorPago" style="width:25%;">'.$linea["valor"].'</td>';
                echo '<td style="width:25%;">'.date ("d/m/Y", strtotime( $linea["fecha"]) ).'</td>';
                echo '<td  style="width:25%;">'.$linea["descripcion"].'</td>';
               /* echo '<td style="width:5%;"> '.
                '<!--<i class="fa fa-trash fa-2x delete_caso" aria-hidden="true " object="delete_caso" item="'.rawurlencode(base64_encode(auyama_encrypt($linea["caso_id"]))).'"></i>--> '. 
                '<a  href="view_caso.php?id='.rawurlencode(base64_encode(auyama_encrypt($linea["caso_id"]))).'&count='.rawurlencode(base64_encode(auyama_encrypt($count))).'"><i class="fa fa-pencil-square-o fa-2x" aria-hidden="true"></i></a> ';
                //echo $linea["caso_id"];
                echo 'Id:'.$linea["caso_id"];
                $count++;
                echo '</td>';
                echo '<td style="width:10%;">'.$linea["name"].'</td>';
                echo '<td style="width:10%;">'.$linea["code"].'</td>';
                echo '<td style="width:10%;" class="bg-'.$status_caso[$linea["status"]].'">'.$status_caso[$linea["status"]].'</td>';
                echo '<td style="width:10%;">'. date (" H:i d/m/Y", strtotime( $linea["last_update"]) ).'</td>';*/
                echo "</tr>";
              }
              ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Tipo de Pago</th>
                                <th>Valor</th>
                                <th>Fecha</th>
                                <th>Descripcion</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <!-- DOCUMENTOS -->
            <div class="col-md-12 mt-3">
                <h3>Documentos</h3>
            </div>
            <div class="col-md-12">
                <div class="row">
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