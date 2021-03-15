<?php
setlocale(LC_MONETARY, 'es_CO');
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
include("inc/config.php");
session_start();
if (!isset($_SESSION["auth_id"])) {
  header("Location:index.php");
}
if ($_GET['logout'] == "logout") {
  session_destroy();
  unset($_SESSION);
}

?>
<!DOCTYPE HTML>

<head>
  <title><?php echo $TITULO ?></title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="favicon.ico" type="image/x-icon">
  <?php include("inc/header.php"); ?>
  <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.min.js" integrity="sha256-eGE6blurk5sHj+rmkfsGYeKyZx3M4bG+ZlFyA7Kns7E=" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link href="https://unpkg.com/nanogallery2@2.4.2/dist/css/nanogallery2.min.css" rel="stylesheet" type="text/css">
  <script type="text/javascript" src="https://unpkg.com/nanogallery2@2.4.2/dist/jquery.nanogallery2.min.js"></script>
  <link rel="stylesheet" href="js/fancybox/jquery.fancybox.min.css" />
  <script src="js/fancybox/jquery.fancybox.min.js"></script>



</head>

<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

  <?php
  /*
    SETTINGS
  */
  $QUERY_SETTINGS = "SELECT * FROM settings where owned_by = '" . $_SESSION["auth_id"] . "'or owned_by = '" . $_SESSION["created_by"] . "'";
  $mysqli->real_query($QUERY_SETTINGS);
  $result = $mysqli->use_result();

  while ($row = $result->fetch_assoc()) {
    $s[$row["name"]] = $row["value"];
    #echo $row["name"]." - > ".$row["value"]."<br>";
  }
  $contacts_type = explode(",", $s["contacts_type"]);
  foreach ($contacts_type as $c) {
    $contacts_type_key = explode(":", $c);
    # echo $contacts_type_key[0]." ->> ".$contacts_type_key[1]."<br>";
    $contact_type[$contacts_type_key[0]] = $contacts_type_key[1];
  }
  $status_caso = explode(",", $s["status_caso"]);
  foreach ($status_caso as $n) {
    $status_caso_key = explode(":", $n);
    #echo $status_caso_key[0]." ->> ".$status_caso_key[1]."<br>";
    $status_caso_array[$status_caso_key[0]] = $status_caso_key[1];
  }




  /*
    FIN SETTINGS
  */
  $id = auyama_decrypt(base64_decode(rawurldecode($_GET["id"])));
  $count = auyama_decrypt(base64_decode(rawurldecode($_GET["count"])));

  // INICIO access CONTROL
  $QUERY_CASOS_CHECK = "SELECT * from user_casos where caso_id=" . $id . " AND user_id=" . $_SESSION["auth_id"] . "; ";

  $mysqli2->real_query($QUERY_CASOS_CHECK);
  $resultaccess = $mysqli2->use_result();

  if ($o = $resultaccess->fetch_assoc() or $_SESSION["type"] == "admin") {
    // si puedes ver esto
  } else {
    header("Location: casos.php?mmmmm");
    exit();
  }
  // Fin access CONTROL

  $mysqli2->close();
  $mysqli2 = new mysqli($host, $user, $pass, $name);
  $QUERY_CASOS = "SELECT * from casos where caso_id='" . $id . "'";
  $mysqli->real_query($QUERY_CASOS);
  $result = $mysqli->use_result();
  $c = $result->fetch_assoc();
  $mysqli2->close();

  ?>

  <?php include("inc/menu.php"); ?>
  <div class="container-fluid maxwidth">
    <div id="content" class="wrapper">
      <div class="row">
        <div class="col-md-6 mt-3">
          <h3>Caso N° <?php echo $count ?> ID:<?php echo $id ?> </h3>
          <?php

          if ($_SESSION["type"] == "admin") {
            echo '<a  href="assign.php?id=' . rawurlencode(base64_encode(auyama_encrypt($id))) . '"> ' .
              '<button class="btn btn-primary" ><i class="fa fa-user-plus  " aria-hidden="true"></i> ASIGNAR CASO A USUARIO</button></a> ';
          }

          ?>
        </div>
        <div class="col-md-6 mt-3 text-right"> </div>
        <div class="col-md-12">
          <hr>
          <input type="hidden" class="form-control" id="caso_id" name="caso_id" value="<?php echo rawurlencode(base64_encode(auyama_encrypt($c["caso_id"]))); ?>">
        </div>
        <div class="col-md-12">
          <form action="datos_bitacora.php" method="GET">
            <input type="hidden" name="id_caso" value="<?php echo $id; ?>">
            <button type="submit" class="btn" style="background-color: #CEED53;">BITACORA</button>
          </form>
        </div>
      </div>
    </div>
  </div>
<br>
  <div class="container-fluid maxwidth">
    <div id="content" class="wrapper ">

    <form method="post" id="contact_form" >
      <div class="row" id="infobox">


        <div class="col-md-12">
          <div class="form-group form-inline">
            <label for="name" class="font-weight-bold mr-2">Nombre: </label>
            <span id="caso_name"> <?php echo empty($c["name"])  ? "Vacio!" : $c["name"];  ?> </span>
            <input type="text" class="form-control hidden4ajax ml-1" id="caso_name_field" name="caso_name_field" value="<?php echo $c["name"]; ?>" />
            <button type="button" id="caso_name_field_save" class="btn btn-danger btn-sm hidden4ajax ml-1">guarda</button>
          </div>
        </div>

        <div class="col-md-3  ">
          <div class="form-group form-inline">
            <label for="code" class="font-weight-bold mr-2">Codigo:</label>
            <span id="caso_code"> <?php echo empty($c["code"])  ? "Vacio!" : $c["code"]; ?> </span>
            <div class="hidden4ajax" id="caso_code_container">
              <input type="text" class="form-control hidden4ajax ml-1" id="caso_code_field" name="caso_code_field" value="<?php echo $c["code"]; ?>" />
              <button type="button" id="caso_code_field_save" class="btn btn-danger btn-sm hidden4ajax ml-1 ">Guarda</button>
            </div>
          </div>
        </div>

        
          <div class="col-md-3 form-inline mitimiti">
            <select class="form-control custom-select small_on_mobile" id="status" name="status" required>
              <option value="<?php echo $c["status"]; ?>" disabled selected>
                <?php echo $status_caso_array[$c["status"]]; ?></option>
              <?php
              foreach ($status_caso_array as $k => $v) {
                echo '<option value="' . $k . '">' . $v . '</option>';
              }
              ?>
            </select>
            <input type="hidden" id="desstatus" name="desstatus">
            <button id="save_status_caso" class="btn btn-primary text-center" data-toggle="tooltip" data-placement="top" title="Actualisa status del caso"> <i class="fa fa-floppy-o  " aria-hidden="true"> </i> ACTUALIZAR</button>
          </div>
        
        <div class="col-md-3">
          <label for="status" class="font-weight-bold">Creado: </label>
          <?php echo date("d/m/Y H:i", strtotime($c["creation_date"])); ?>

        </div>

        <div class="col-md-3">
          <label for="status" class="font-weight-bold">Actualizado: </label>
          <?php echo date("d/m/Y H:i", strtotime($c["last_update"])); ?>
        </div>

        <div class="col-md-3  ">
          <div class="form-group form-inline">
            <label for="cuantia" class="font-weight-bold mr-2">Cuantia: </label>
            <span id="cuantia_caso">
              <?php
              echo empty($c["cuantia"])  ? "Vacio!" : "$ " . number_format($c["cuantia"], 0);
              ?>
            </span>
            <div class="hidden4ajax" id="cuantia_caso_container" style="display:none">
              <input data-type="currency" type="text" class="form-control hidden4ajax  ml-1" id="cuantia_caso_field" name="cuantia_caso_field" value="<?php echo "$" . number_format($c["cuantia"], 0); ?>" />
              <button type="button" id="cuantia_caso_field_save" class="btn btn-danger btn-sm ml-1 hidden4ajax">guarda</button>
            </div>
          </div>
        </div>


        <?php
        $mysqli2 = new mysqli($host, $user, $pass, $name);
        $SELECT_OPTIONS = "SELECT * from casos_options where caso_id='" . $id . "' order by option_type ";
        $mysqli2->real_query($SELECT_OPTIONS);
        $options = $mysqli2->use_result();

        while ($o = $options->fetch_assoc()) {



          $htmlinfo    .=   '<div class="col-10 col-md-3 option_box" option_id="' . $o["options_id"] . '">' .
            '<div class="form-group text-truncate" data-toggle="tooltip" data-placement="top" title="' . $o["name"] . '">' .
            '<span class="  hiddeondesktop option_delete_box text-right" option_id="' . $o["options_id"] . '">' .
            '<i class="fa fa-trash fa-2x ml-3 mr-3" aria-hidden="true"></i> ' .
            '</span>' .
            '<i class="fa fa-2x ' . $option_type[$o["option_type"]] . '" aria-hidden="true"></i> ' .
            '<span class="hiddeondesktop">' . $o["name"] . ': </span> ' .
            $o["value"] .
            '</div>' .
            '</div>';
        }
        echo $htmlinfo;
        ?>
      </div>
      </form>
      <div class="row">


        <div class="col-md-12 mt-3" id="contact">
          <h3>
            <button type="button" class="btn btn-secondary" id="add-contact">
              <i class="fa fa-plus fa-1x" aria-hidden="true"></i> CREAR
            </button>
            Contactos
          </h3>
          <hr>
        </div>








        <div class="col-md-12 mt-3 mb-3 dontshow" id="contact-form">
          <div class="row">
            <div class="col-md-5">
              <input type="text" class="form-control" id="contact_name" name="contact_name" placeholder="Busca contacto por nombre ">

            </div>
            <div class="col-md-2">

            </div>

            <div class="col-md-2">

              <input type="text" class="form-control" id="contact_name_new" name="contact_name_new" placeholder="Nombre contacto" required>
            </div>
            <div class="col-md-3">
              <button type="button" class="btn btn-secondary" id="create_add_client" name="create_add_client">
                <i class="fa fa-user" aria-hidden="true"></i> Crea Contacto
              </button>

            </div>

          </div>

        </div>
        <div class="col-md-12  " id="contact-spool">
          <div class="row mr-1 ml-1 font-weight-bold d-none d-lg-flex   ">
            <div class="col-md-2"></div>
            <div class="col-md-3 text-truncate">NOMBRE</div>
            <div class="col-md-2">TELEFONO</div>
            <div class="col-md-2">EMAIL</div>
            <div class="col-md-1">NIT</div>
            <div class="col-md-2">RELACIÒN</div>
          </div>
          <?php
          $SQL_CONTACTS = "SELECT * FROM casos_contacts join contacts on casos_contacts.contact_id=contacts.contact_id where caso_id=" . $c["caso_id"];
          $document_count_sql = "SELECT COUNT(*) as total FROM documents WHERE caso_id='" . $id . "'";
          $mysqli2->real_query($SQL_CONTACTS);
          $contacts = $mysqli2->use_result();

          $mysqli3 = new mysqli($host, $user, $pass, $name);
          $mysqli3->real_query($document_count_sql);
          $document_count = $mysqli3->use_result();
          $document_count = $document_count->fetch_assoc();
          //var_dump($document_count["total"]);exit();
          $mysqli3->close();
          foreach ($contact_type as $k => $v) {
            $options_contact_type .= '<option value="' . $k . '">' . $v . '</option>';
          }

          while ($u = $contacts->fetch_assoc()) {

            if (!empty($u["email"])) {
              $count_contact = 0;
              ++$count_contact;
              $email = '<a href="mailto:' . $u["email"] . '">' . $u["email"] . '</a>';
              // $email_icon=' <i class="fa fa-envelope-o fa-2x mt-2 guestaccess" id="ct-'.$count_contact.'" aria-hidden="true" contact-id="'.rawurlencode(base64_encode(auyama_encrypt($u["contact_id"]))).'"></i> ';
              $email_icon = "";
            } else {
              $email = "";
              $email_icon = "";
            }
            echo  '<div class="row mr-1 ml-1">' .
              '<div class="col-md-2" data-toggle="tooltip" data-placement="top" >' .
              '<button class="btn btn-primary delete-contact"   contact-id="' . rawurlencode(base64_encode(auyama_encrypt($u["contact_id"]))) . '"><i class="fa fa-trash   mt-2  " aria-hidden="true"  ></i></button> ' .
              '<a href="view_contact.php?id=' . rawurlencode(base64_encode(auyama_encrypt($u["contact_id"]))) . '">' .
              '<button class="btn btn-primary"><i class="fa fa-address-book   mt-2" aria-hidden="true"></i> EDITAR</button>' .
              '</a>' .
              $email_icon .
              '</div>' .
              '<div class="col-md-3 text-truncate">' . $u["name"] . '</div>' .
              '<div class="col-md-2">' . $u["phone"] . '</div>' .
              '<div class="col-md-2 text-truncate">' . $email . '</div>' .
              '<div class="col-md-1">' . $u["nit"] . '</div>' .
              '<div class="col-md-2 form-inline">' .
              '<select style="width:100px" class="form-control custom-select small_on_mobile" id="contact_type' . $u["contact_id"] . '" name="contact_type' . $u["contact_id"] . '" required>' .
              '<option value="' . $u["contact_type"] . '" disabled selected>' . $contact_type[$u["contact_type"]] . '</option>' .
              $options_contact_type .
              '</select><button class="btn btn-primary ml-2" id="save_contact_type" selectctl="contact_type' . $u["contact_id"] . '"  contact="' . rawurlencode(base64_encode(auyama_encrypt($u["contact_id"]))) . '"><i class="fa fa-floppy-o fa-2x"  aria-hidden="true" ></i></button>' .
              '</div>' .
              '</div>';
          }

          ?>

        </div>

        <div class="col-md-12">
          <form method="post">
            <label for="notes"></label>
            <h3>Descripcion del caso:</h3>
            <hr>
            <textarea name="notes" id="notes" class="notes" style="width:100%;"><?php echo $c["description"]; ?></textarea>
        </div>
        <div class="col-md-12">
          <button type="button" id="save_caso" name="save_caso" class="btn btn-primary">ACTUALIZAR </button> </form>
        </div>
        <div class="col-md-12 alert alert-success text-center hidden4ajax" id="log">
        </div>
        <div class="col-md-12 text-center hidden4ajax" id="spinner">
          <i class="fa fa-spinner fa-3x fa-fw fa-spin" aria-hidden="true"></i> Guardando las modificaciones...
        </div>


        <div class="col-md-12 mt-3 mb-3 dontshow" id="note-form">
          <div class="row">
            <div class="col-md-8 m-auto">
              <form method="post" id="notes-form" name="notes-form">
                <input type="text" class="form-control" id="note_category" name="note_category" placeholder="Categorias">

            </div>
            <div class="col-md-8 m-auto">
              <textarea name="note_text" id="note_text" class="notes" placeholder="Nota, texto libre"> </textarea>

            </div>
            <div class="col-md-8 m-auto">
              <button type="button" id="save_note" name="save_note" class="btn btn-primary">GUARDAR </button> </form>
            </div>

            <div class="col-md-8 m-auto text-center hidden4ajax" id="spinner-note">
              <i class="fa fa-spinner fa-3x fa-fw fa-spin" aria-hidden="true"></i> Guardando las modificaciones...
            </div>
          </div>

        </div>


        <div class="col-md-12 mt-3">
          <h3>
            <button type="button" id="ver_pagos" name="ver_pagos" class="btn btn-secondary" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample" data-placement="top" title="Ver Pagos del caso">
              VER PAGO
            </button> Pagos
          </h3>
          <hr>
        </div>

        <div class="container-fluid mb-5 maxwidth">
          <div id="content" class="wrapper">

            <table id="collapseExample" class="table table-striped table-bordered display collapse" <?php $usersettings = "";
                                                                                                    echo $usersettings; ?> style="width:100%">
              <thead>
                <tr>
                  <th>Tipo de Pago</th>
                  <th>Valor</th>
                  <th>Fecha</th>
                  <th>Descripcion</th>
                  <th>Anular Pago</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $query = "SELECT * from pagos where caso_id='" . $c["caso_id"] . "'";
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
                  echo '<input id="pagoId" type="hidden" value="' . $linea["pago_id"] . '" />';
                  echo '<td id="tipoPago" style="width:25%;">' . $linea["tipo_pago"] . '</td>';
                  echo '<td class="valorPago" style="width:25%;">' . $linea["valor"] . '</td>';
                  echo '<td style="width:25%;">' . date("d/m/Y", strtotime($linea["fecha"])) . '</td>';
                  echo '<td  style="width:25%;">' . $linea["descripcion"] . '</td>';
                  if ($linea["anulado"] == 0) {
                    echo '<td style="width:15%;text-align:center; font-size:25px;cursor:pointer;"><i id="anularCaso" class="fa fa-times"></i></td>';
                  } else {
                    echo '<td>Pago Anulado</td>';
                  }
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
                  <th>Anular Pago</th>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>

        <!--
        <div class="col-md-12 mt-3">
          <h3>
            <button type="button" id="ver_galeria" name="ver_galeria" class="btn btn-secondary" data-toggle="collapse"
              href="#nanogallery2" role="button" aria-expanded="false" aria-controls="nanogallery2" data-placement="top"
              title="Ver Galeria">
              <i class="fa fa-plus fa-2x" aria-hidden="true"></i>
            </button> Galeria de imagenes y videos
          </h3>
          <hr>
        </div>
        <div class="container-fluid mb-5 maxwidth">
          <div id="galeria" class="wrapper">
            <div id="nanogallery2" data-nanogallery2='{
                  "thumbnailHeight":  100,
                  "thumbnailWidth":   100,
           
                }' class="collapse">
              <?php
              /*
                $SQL_DOCUMENTS="SELECT * FROM  documents   where documents.caso_id=".$id." order by extention";
                $mysqli4 = new mysqli($host, $user, $pass, $name);
                 $mysqli4->real_query($SQL_DOCUMENTS);
                 $contacts = $mysqli4->use_result();
                 while( $u = $contacts->fetch_assoc())
                 {
                 if(strtolower($u["extention"])=="jpg" or $u["extention"]=="png" or $u["extention"]=="jpeg")
                 {
                   echo'<a href="../download/'.$u["caso_id"].'/'.$u["name"].'">'.$u["realname"].'</a>';
                 }
                 
                 }*/
              ?>
            </div>
          </div>
        </div>
-->
        
        <div class="col-md-12 mt-3 pt-3">
          <h3 class="">
            <a href="documentos.php?id=<?php echo rawurlencode(base64_encode(auyama_encrypt($c["caso_id"]))); ?>&count=<?php echo rawurlencode(base64_encode(auyama_encrypt($count))); ?>&documentcount=<?php echo rawurlencode(base64_encode(auyama_encrypt($document_count["total"]))); ?>">
              <button type="button" class="btn btn-secondary" data-toggle="tooltip" data-placement="top" title="Agrega un documento">
                </i> SUBIR DOCUMENTOS
              </button>
            </a>
            Documentos(<?php echo $document_count["total"] ?>):

          </h3>


        </div>
        <div class="col-md-12">
          <input id="buscar" class="form-control btn border-primary w-25" type="text" placeholder="Buscar Documento..." onkeyup="buscar()">
        </div>
        <hr>
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
  <script type="text/javascript">
    var caso_id = $("#caso_id").val();
    $.ajax({
      type: 'POST',
      url: 'ajax_document_list.php',
      data: {
        'function': 'list_document',
        'caso_id': caso_id
      },
      success: function(response) {
        $("#filespool").html(response);
        update_icons();

      }
    });

    function buscar() {
      var name = document.getElementById('buscar').value
      $.ajax({
        type: 'POST',
        url: 'ajax_document_list.php',
        data: {
          'function': 'buscar',
          'caso_id': caso_id,
          'name': name
        },
        success: function(response) {
          $("#filespool").html(response);
          update_icons();

        }
      });

    }
  </script>
  <script type="text/javascript">
      var form = document.getElementById('contact_form');
      form.elements.status.onchange = function() {
      var option = this.options[this.selectedIndex];
      this.form.elements.desstatus.value = option.innerHTML;
    }
  </script>

</body>

</html>